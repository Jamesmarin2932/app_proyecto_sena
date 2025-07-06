<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->string('codigo_del_producto');
            $table->string('producto');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('iva', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('detalle_facturas');
    }
};
