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
            $table->string('tipo_identificacion');
            $table->string('numero_identificacion');
            $table->string('cliente');
            $table->date('fecha');
            $table->string('codigo_del_producto');
            $table->string('producto');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('descuento', 10, 2);
            $table->decimal('iva', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('numero_factura')->unique(); // Nuevo campo para el número de factura
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
