<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asientos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tercero_id')->nullable()->constrained('dato_clientes')->nullOnDelete();
    $table->string('cuenta');
    $table->date('fecha');
    $table->string('concepto');
    $table->decimal('debito', 15, 2)->default(0);
    $table->decimal('credito', 15, 2)->default(0);
    $table->decimal('saldo', 15, 2)->default(0);
    $table->integer('consecutivo')->nullable();
    $table->string('tipo')->nullable();
    $table->string('factura')->nullable();
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('asientos');
    }
};
