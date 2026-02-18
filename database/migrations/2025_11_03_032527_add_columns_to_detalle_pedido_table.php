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
        Schema::table('detalle_pedido', function (Blueprint $table) {
            // Verificar si las columnas ya existen antes de agregarlas
            if (!Schema::hasColumn('detalle_pedido', 'Producto')) {
                $table->unsignedBigInteger('Producto')->after('id');
            }
            
            if (!Schema::hasColumn('detalle_pedido', 'Pedido')) {
                $table->unsignedBigInteger('Pedido')->after('Producto');
            }
            
            if (!Schema::hasColumn('detalle_pedido', 'Cantidad')) {
                $table->integer('Cantidad')->after('Pedido');
            }
            
            if (!Schema::hasColumn('detalle_pedido', 'PrecioUnitario')) {
                $table->decimal('PrecioUnitario', 10, 2)->after('Cantidad');
            }

            // Agregar claves foráneas si no existen
            if (Schema::hasTable('productos')) {
                $table->foreign('Producto')->references('id')->on('productos')->onDelete('cascade');
            }
            
            if (Schema::hasTable('pedidos')) {
                $table->foreign('Pedido')->references('id')->on('pedidos')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            // Eliminar claves foráneas
            $table->dropForeign(['Producto']);
            $table->dropForeign(['Pedido']);
            
            // Eliminar columnas (opcional)
            $table->dropColumn(['Producto', 'Pedido', 'Cantidad', 'PrecioUnitario']);
        });
    }
};
