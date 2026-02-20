<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'ApPaterno',
        'ApMaterno',
        'Telefono',
        'Fecha_nacimiento',
        'Cargo',
        'Sexo',
        'Area_trabajo'
    ];

    protected $casts = [
        'Fecha_nacimiento' => 'date'
    ];

    /**
     * Relación con Usuario (1:1)
     */
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'empleado_id', 'id');
    }

    /**
     * Relación con Pedidos (1:N)
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'Empleado_idEmpleado', 'id');
    }

    /**
     * Relación con Ventas (1:N)
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'Empleado_idEmpleado', 'id');
    }

    /**
     * Accesor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->Nombre . ' ' . $this->ApPaterno . ' ' . $this->ApMaterno);
    }

    /**
     * Accesor para edad
     */
    public function getEdadAttribute()
    {
        return $this->Fecha_nacimiento ? $this->Fecha_nacimiento->age : null;
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscarPorNombre($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('Nombre', 'LIKE', "%{$termino}%")
              ->orWhere('ApPaterno', 'LIKE', "%{$termino}%")
              ->orWhere('ApMaterno', 'LIKE', "%{$termino}%");
        });
    }
}