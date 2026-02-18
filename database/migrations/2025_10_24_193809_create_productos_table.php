<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre', 100);
            $table->string('Descripcion', 200)->nullable();
            $table->decimal('Precio', 10, 2);
            $table->integer('Cantidad');
            $table->integer('Cantidad_minima');
            $table->integer('Cantidad_maxima');
            $table->foreignId('Categoria')->constrained('categoria', 'id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};