<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CajeroController extends Controller
{
    public function index()
    {
        return view('cajero.dashboard');
    }

    public function searchClient(Request $request)
    {
        $tipo_documento = $request->get('tipo_documento');
        $num_documento = $request->get('num_documento');

        if (!$tipo_documento || !$num_documento) {
            return response()->json([
                'success' => false,
                'message' => 'Faltan parámetros'
            ], 400);
        }

        // Buscar en DB primero
        $cliente = Cliente::where('num_documento', $num_documento)->first();
        if ($cliente) {
            return response()->json([
                'success' => true,
                'cliente' => $cliente
            ]);
        }

        // Buscar en API json.pe si no existe en DB
        $token = env('JSONPE_TOKEN');
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token de API no configurado'
            ], 500);
        }

        $endpoint = $tipo_documento === 'DNI' ? 'dni' : 'ruc';

        $response = Http::withToken($token)->post("https://api.json.pe/api/{$endpoint}", [
            $endpoint => $num_documento
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                $nombre_o_razon_social = '';
                if ($tipo_documento === 'DNI') {
                    $nombre_o_razon_social = $data['data']['nombre_completo'] ?? '';
                } else {
                    $nombre_o_razon_social = $data['data']['nombre_o_razon_social'] ?? '';
                }

                // Concatenar el número de documento como se pidió "ambos que contengan el numero de documento"
                $razon_social_final = trim($nombre_o_razon_social) . ' - ' . $num_documento;

                $cliente = Cliente::create([
                    'tipo_documento' => $tipo_documento,
                    'num_documento' => $num_documento,
                    'razon_social' => $razon_social_final
                ]);

                return response()->json([
                    'success' => true,
                    'cliente' => $cliente
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Cliente no encontrado en API'
        ], 404);
    }

    public function searchProduct(Request $request)
    {
        $barcode = $request->get('barcode');
        $producto = Producto::where('barcode', $barcode)->first();

        if ($producto) {
            return response()->json([
                'success' => true,
                'producto' => $producto
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado'
        ], 404);
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'cliente_id' => 'nullable|exists:clientes,id',
        ]);

        try {
            DB::beginTransaction();

            if ($request->filled('cliente_id')) {
                $cliente = Cliente::findOrFail($request->cliente_id);
            } else {
                // Buscar o crear cliente "Público en General" (DNI: 00000000)
                $cliente = Cliente::firstOrCreate(
                    ['num_documento' => '00000000'],
                    [
                        'tipo_documento' => 'DNI',
                        'razon_social' => 'Público en General',
                    ]
                );
            }

            $tipo_comprobante = 'BOLETA';
            $serie_comprobante = 'B001';

            if ($cliente->tipo_documento === 'RUC') {
                $tipo_comprobante = 'FACTURA';
                $serie_comprobante = 'F001';
            }

            // Obtener el siguiente número de comprobante
            $ultimoComprobante = Venta::where('tipo_comprobante', $tipo_comprobante)
                ->where('serie_comprobante', $serie_comprobante)
                ->max('numero_comprobante');

            $siguienteNumero = $ultimoComprobante ? $ultimoComprobante + 1 : 1;

            // Calcular el total
            $total = 0;
            $detalles = [];
            foreach ($request->productos as $item) {
                $producto = Producto::lockForUpdate()->findOrFail($item['id']);

                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                }

                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                $detalles[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                ];

                // Deduct stock
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }

            // Crear Venta
            $venta = Venta::create([
                'tipo_comprobante' => $tipo_comprobante,
                'serie_comprobante' => $serie_comprobante,
                'numero_comprobante' => $siguienteNumero,
                'cliente_id' => $cliente->id,
                'total' => $total,
            ]);

            // Crear VentaDetalle
            foreach ($detalles as &$detalle) {
                $detalle['venta_id'] = $venta->id;
                VentaDetalle::create($detalle);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'venta_id' => $venta->id,
                'message' => 'Venta registrada exitosamente',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadReceipt($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);

        $pdf = Pdf::loadView('cajero.receipt', compact('venta'));

        return $pdf->download("comprobante_{$venta->serie_comprobante}-{$venta->numero_comprobante}.pdf");
    }
}
