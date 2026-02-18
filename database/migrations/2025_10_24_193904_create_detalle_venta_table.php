<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Producto')->constrained('productos', 'id');
            $table->foreignId('Venta')->constrained('ventas', 'id');
            $table->integer('Cantidad');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_venta');
    }
};