<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // dato_clientes
        Schema::table('dato_clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('dato_clientes', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            }
        });

        // dato_productos
        Schema::table('dato_productos', function (Blueprint $table) {
            if (!Schema::hasColumn('dato_productos', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            }
        });

        // asientos
        Schema::table('asientos', function (Blueprint $table) {
            if (!Schema::hasColumn('asientos', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            }
        });

        // cuentas
        Schema::table('cuentas', function (Blueprint $table) {
            if (!Schema::hasColumn('cuentas', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('dato_clientes', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });

        Schema::table('dato_productos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });

        Schema::table('asientos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });

        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
