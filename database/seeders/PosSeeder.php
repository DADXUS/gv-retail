<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Cliente;

class PosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Productos
        $productos = [
            [
                'barcode' => '7750123456789',
                'nombre' => 'Galletas Oreo',
                'precio' => 1.50,
                'stock' => 50,
            ],
            [
                'barcode' => '7750987654321',
                'nombre' => 'Gaseosa Coca Cola 500ml',
                'precio' => 2.50,
                'stock' => 30,
            ],
            [
                'barcode' => '12345',
                'nombre' => 'Producto Prueba Corto',
                'precio' => 5.00,
                'stock' => 10,
            ],
        ];

        foreach ($productos as $p) {
            Producto::updateOrCreate(['barcode' => $p['barcode']], $p);
        }

        // Cliente por defecto
        Cliente::updateOrCreate(
            ['num_documento' => '00000000'],
            [
                'tipo_documento' => 'DNI',
                'razon_social' => 'Público en General',
            ]
        );
    }
}
