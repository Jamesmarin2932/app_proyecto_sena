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
            $table->string('tipo_identificacion');
            $table->string('numero_identificacion');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('telefono');
            $table->string('correo');
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
