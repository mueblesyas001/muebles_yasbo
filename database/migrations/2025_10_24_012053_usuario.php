<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('correo')->unique();
            $table->string('contrasena');
            $table->enum('rol', ['Administración', 'Almacén', 'Logística']);
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('usuarios');
    }
};