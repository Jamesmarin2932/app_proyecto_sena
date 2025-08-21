<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Primero eliminar la restricción de clave foránea
            $table->dropForeign(['empresa_id']);
            
            // Luego eliminar la columna
            $table->dropColumn('empresa_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Para revertir: volver a añadir el campo
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('set null');
        });
    }
};