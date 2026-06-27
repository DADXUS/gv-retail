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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_documento', ['DNI', 'RUC']);
            $table->string('num_documento', 11)->unique();
            $table->string('razon_social', 255);
            $table->string('email', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();

            // Índice para optimizar búsquedas por DNI/RUC
            $table->index('num_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
