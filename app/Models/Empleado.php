<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'Nombre',
        'ApPaterno',
        'ApMaterno',
        'Telefono',
        'Fecha_nacimiento',
        'edad',
        'Cargo',
        'Sexo',
        'Area_trabajo'
    ];

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'empleado_id');
    }

    public function tieneUsuario(): bool
    {
        return $this->usuario()->exists();
    }
    // ACCESOR para nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->Nombre . ' ' . $this->ApPaterno . ' ' . $this->ApMaterno);
    }

    // ACCESOR para nombre corto (solo para compatibilidad)
    public function getNombreAttribute($value)
    {
        return $value;
    }
}
