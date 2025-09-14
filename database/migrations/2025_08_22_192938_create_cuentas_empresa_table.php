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
        Schema::create('cuentas_empresa', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('empresa_id');
    $table->string('codigo');
    $table->string('nombre');
    $table->timestamps();

    $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
    $table->unique(['empresa_id', 'codigo']); // cada empresa puede repetir codigo, pero no entre empresas
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_empresa');
    }
};
