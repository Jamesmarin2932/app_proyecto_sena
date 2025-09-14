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
    Schema::create('consecutivos_asientos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('empresa_id')->constrained();
        $table->string('tipo_asiento', 10); // CC, RC, NC, CE
        $table->integer('ultimo_consecutivo')->default(0);
        $table->timestamps();
        
        $table->unique(['empresa_id', 'tipo_asiento']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consecutivo_asientos');
    }
};
