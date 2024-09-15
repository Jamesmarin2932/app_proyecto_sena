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
        Schema::create('descripcion_facturas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_de_compra');
            $table->string('producto');
            $table->string('cantidad');
            $table->decimal('sub_total', 8, 2);
            $table->decimal('descuento', 8, 2);
            $table->decimal('iva', 8,2);
            $table->decimal('total', 8,2);

            $table->unsignedBigInteger('id_factura');
 
             $table->foreign('id_factura')->references('id')->on('facturas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descripcion_facturas');
    }
};
