<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        .info-table, .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>{{ env('APP_NAME', 'Mi Tienda') }}</h1>
    <p>Comprobante: {{ $venta->tipo_comprobante }} {{ $venta->serie_comprobante }}-{{ str_pad($venta->numero_comprobante, 6, '0', STR_PAD_LEFT) }}</p>
    <p>Fecha: {{ $venta->fecha_emision }}</p>
</div>

<table class="info-table">
    <tr>
        <td width="20%"><strong>Cliente:</strong></td>
        <td>{{ $venta->cliente->razon_social }}</td>
    </tr>
    <tr>
        <td><strong>Documento:</strong></td>
        <td>{{ $venta->cliente->tipo_documento }} {{ $venta->cliente->num_documento }}</td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Precio Unit.</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $detalle)
        <tr>
            <td>{{ $detalle->producto->nombre }}</td>
            <td>{{ $detalle->cantidad }}</td>
            <td>S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
            <td>S/ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="3" class="text-right">TOTAL:</td>
            <td>S/ {{ number_format($venta->total, 2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="header">
    <p>¡Gracias por su compra!</p>
</div>

</body>
</html>
