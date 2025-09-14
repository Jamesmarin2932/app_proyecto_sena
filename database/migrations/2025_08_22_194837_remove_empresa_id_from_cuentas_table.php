<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            if (Schema::hasColumn('cuentas', 'empresa_id')) {
                $table->dropForeign(['empresa_id']);
                $table->dropColumn('empresa_id');
            }
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            if (!Schema::hasColumn('cuentas', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            }
        });
    }
};
