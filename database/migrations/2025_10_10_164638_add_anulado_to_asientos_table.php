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
    Schema::table('asientos', function (Blueprint $table) {
        $table->boolean('anulado')->default(false);
    });
}

public function down()
{
    Schema::table('asientos', function (Blueprint $table) {
        $table->dropColumn('anulado');
    });
}

};
