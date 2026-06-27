<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProductos = Producto::count();
        $totalVentas = Venta::count();
        $ingresosTotales = Venta::sum('total');

        return view('admin.dashboard', compact('totalProductos', 'totalVentas', 'ingresosTotales'));
    }

    public function index()
    {
        $productos = Producto::paginate(10);
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        return view('admin.productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:productos',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'barcode' => 'required|unique:productos,barcode,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $producto->update($request->all());

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function reportesVentas(Request $request)
    {
        $filtro = $request->get('filtro', 'hoy'); // hoy, semana, mes, todo

        $query = Venta::query();

        if ($filtro === 'hoy') {
            $query->whereDate('created_at', today());
        } elseif ($filtro === 'semana') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filtro === 'mes') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $totalVentas = $query->sum('total');
        $ventas = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.reportes.ventas', compact('ventas', 'totalVentas', 'filtro'));
    }

    public function reportesClientes()
    {
        $clientes = Cliente::withSum('ventas as total_compras', 'total')
            ->orderByDesc('total_compras')
            ->paginate(15);

        return view('admin.reportes.clientes', compact('clientes'));
    }
}
