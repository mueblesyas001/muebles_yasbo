<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'remember_token')) {
                $table->rememberToken()->after('rol');
            }
        });

        DB::statement(
            "ALTER TABLE usuarios MODIFY rol ENUM(
                'Administración',
                'Administrador',
                'Almacén',
                'Encargado de almacén',
                'Logística',
                'Gerencia'
            ) NOT NULL"
        );
    }

    public function down(): void
    {
        // Elimina el token de recordar sesión si existiera
        Schema::table('usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('usuarios', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        });

        DB::statement(
            "ALTER TABLE usuarios MODIFY rol ENUM(
                'Administración',
                'Almacén',
                'Logística'
            ) NOT NULL"
        );
    }
};
