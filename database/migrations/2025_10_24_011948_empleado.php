<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id(); 
            $table->string('Nombre', 100);
            $table->string('ApPaterno', 100);
            $table->string('ApMaterno', 100)->nullable();
            $table->string('Telefono', 15)->nullable();
            $table->date('Fecha_nacimiento');
            $table->string('Cargo', 100);
            $table->enum('Sexo', ['M', 'F']);
            $table->string('Area_trabajo', 100);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('empleados');
    }
};