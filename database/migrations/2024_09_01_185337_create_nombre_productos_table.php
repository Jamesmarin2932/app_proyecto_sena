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
            $table->string('Codigo');
            $table->string('Nombre');
            $table->text('Descripcion');
            $table->decimal('Precio', 8,2);
            $table->integer('Stock'); 
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
