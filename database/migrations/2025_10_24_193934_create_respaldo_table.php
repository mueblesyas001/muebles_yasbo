<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('respaldo', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre', 80);
            $table->string('Descripcion', 280)->nullable();
            $table->string('Ruta', 200);
            $table->date('Fecha');
            $table->foreignId('Usuario')->constrained('usuarios', 'id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('respaldo');
    }
};