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
        Schema::create('dato_clientes', function (Blueprint $table) {
            $table->id();

            // Identificación
            $table->string('tipo_identificacion'); // CC, NIT, CE, etc.
            $table->string('numero_identificacion')->unique();

            // Datos personales
            $table->string('nombres')->nullable(); // Puede ser null si es persona jurídica
            $table->string('apellidos')->nullable(); // Igual que arriba
            $table->string('razon_social')->nullable(); // Para personas jurídicas

            // Tipo de persona y tercero
            $table->enum('tipo_persona', ['natural', 'juridica'])->default('natural');
            $table->enum('tipo_tercero', ['cliente', 'proveedor', 'ambos'])->default('cliente');

            // Contacto
            $table->string('direccion');
            $table->string('departamento');
            $table->string('ciudad');
            $table->string('codigo_postal')->nullable();
            $table->string('pais')->default('Colombia');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();

            // Otros
            $table->string('actividad_economica')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('cuenta_gasto')->nullable(); // o el tipo que desees


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dato_clientes');
    }
};
