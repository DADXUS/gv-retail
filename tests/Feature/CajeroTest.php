<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CajeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_cajero_dashboard_is_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/cajero/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Lector de Código de Barras');
    }

    public function test_search_product_returns_json(): void
    {
        $user = User::factory()->create();
        $producto = Producto::create([
            'barcode' => '12345',
            'nombre' => 'Test Product',
            'precio' => 10.50,
            'stock' => 5
        ]);

        $response = $this->actingAs($user)->getJson('/cajero/api/search-product?barcode=12345');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'producto' => [
                'barcode' => '12345',
                'nombre' => 'Test Product',
            ]
        ]);
    }
}
