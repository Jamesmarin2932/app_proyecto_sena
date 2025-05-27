<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Campo 'id' auto incrementable
            $table->string('nombre_usuario'); // Nombre del usuario
            $table->string('identificacion')->unique(); // Identificación única (puede ser el número de cédula, pasaporte, etc.)
            $table->string('usuario')->unique(); // Nombre de usuario o correo electrónico único
            $table->string('password'); // Contraseña
            $table->timestamps(); // Campos 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
