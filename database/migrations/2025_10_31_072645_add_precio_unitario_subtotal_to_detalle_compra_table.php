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
        Schema::table('detalle_compra', function (Blueprint $table) {
            $table->decimal('Precio_unitario', 10, 2)->after('Cantidad')->default(0);
            $table->decimal('Subtotal', 10, 2)->after('Precio_unitario')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('detalle_compra', function (Blueprint $table) {
            $table->dropColumn(['Precio_unitario', 'Subtotal']);
        });
    }
};