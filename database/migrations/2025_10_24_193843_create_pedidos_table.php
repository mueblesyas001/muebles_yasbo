<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->date('Fecha_entrega');
            $table->time('Hora_entrega');
            $table->string('Lugar_entrega', 200);
            $table->string('Estado', 100);
            $table->string('Prioridad', 80);
            $table->decimal('Total', 10, 2);
            $table->foreignId('Cliente_idCliente')->constrained('clientes', 'id');
            $table->foreignId('Empleado_idEmpleado')->constrained('empleados', 'id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};