<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();

            // Relaciones clave
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');

            $table->integer('cantidad')->unsigned()->default(1);
            $table->decimal('precio_unitario', 10, 2);

            // Subtotal calculado (Soporte nativo en Laravel para columnas generadas en MySQL)
            $table->rawIndex('subtotal', 'generated_subtotal_index')->storedAs('cantidad * precio_unitario');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
    }
};
