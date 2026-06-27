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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_comprobante', ['BOLETA', 'FACTURA']);
            $table->string('serie_comprobante', 4);
            $table->integer('numero_comprobante')->unsigned();
            $table->dateTime('fecha_emision')->useCurrent();

            // Relación con la tabla clientes
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');

            $table->decimal('total', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
