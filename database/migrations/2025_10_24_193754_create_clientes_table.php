<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre', 100);
            $table->string('ApPaterno', 100);
            $table->string('ApMaterno', 100)->nullable();
            $table->string('Correo', 100);
            $table->string('Telefono', 10);
            $table->string('Direccion', 200);
            $table->string('Sexo', 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};