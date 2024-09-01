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
        Schema::create('nombre_productos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->decimal('precio',8 ,2);
            $table->unsignedBigInteger('id_datos_productos');
 
             $table->foreign('id_datos_productos')->references('id')->on('dato_productos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nombre_productos');
    }
};
