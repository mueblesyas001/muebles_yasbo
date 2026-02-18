<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Producto')->constrained('productos', 'id');
            $table->foreignId('Compra_idCompra')->constrained('compras', 'id');
            $table->integer('Cantidad');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compra');
    }
};