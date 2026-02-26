<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tabla usuarios
        if (Schema::hasTable('usuarios') && !Schema::hasColumn('usuarios', 'estado')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->boolean('estado')->default(1)->after('rol')->comment('1=Activo, 0=Inactivo');
            });
        }

        // 2. Tabla empleados
        if (Schema::hasTable('empleados') && !Schema::hasColumn('empleados', 'estado')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->boolean('estado')->default(1)->after('Area_trabajo')->comment('1=Activo, 0=Inactivo');
            });
        }

        // 3. Tabla productos
        if (Schema::hasTable('productos') && !Schema::hasColumn('productos', 'estado')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->boolean('estado')->default(1)->after('Categoria')->comment('1=Activo, 0=Inactivo');
            });
        }

        // 4. Tabla categoria
        if (Schema::hasTable('categoria') && !Schema::hasColumn('categoria', 'estado')) {
            Schema::table('categoria', function (Blueprint $table) {
                $table->boolean('estado')->default(1)->after('Proveedor')->comment('1=Activa, 0=Inactiva');
            });
        }

        // 5. Tabla clientes
        if (Schema::hasTable('clientes') && !Schema::hasColumn('clientes', 'estado')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->boolean('estado')->default(1)->after('Sexo')->comment('1=Activo, 0=Inactivo');
            });
        }

        // 6. Tabla proveedores
        if (Schema::hasTable('proveedores') && !Schema::hasColumn('proveedores', 'estado')) {
            Schema::table('proveedores', function (Blueprint $table) {
                $table->boolean('estado')->default(1)->after('Sexo')->comment('1=Activo, 0=Inactivo');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar campo estado de todas las tablas (con verificación)
        $tables = ['usuarios', 'empleados', 'productos', 'categoria', 'clientes', 'proveedores'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'estado')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropColumn('estado');
                });
            }
        }
    }
}