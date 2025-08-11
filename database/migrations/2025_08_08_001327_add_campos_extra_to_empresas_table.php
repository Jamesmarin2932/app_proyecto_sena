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
    Schema::table('empresas', function (Blueprint $table) {
        $table->string('nombre_razon_social')->nullable()->after('nombre');
        $table->string('nombre_comercial')->nullable()->after('nombre_razon_social');
        $table->string('nit')->nullable()->after('nombre_comercial');
        $table->string('direccion')->nullable()->after('nit');
        $table->string('ciudad')->nullable()->after('direccion');
        $table->string('departamento')->nullable()->after('ciudad');
        $table->string('pais')->nullable()->after('departamento');
        $table->string('actividad_economica')->nullable()->after('pais');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            //
        });
    }
};
