<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla (opcional si sigue convención)
    protected $table = 'proveedores';

    // Especificar la clave primaria
    protected $primaryKey = 'id';

    // Indicar que la clave primaria es auto-incrementable
    public $incrementing = true;

    // Especificar el tipo de la clave primaria
    protected $keyType = 'int';

    public $timestamps = false;
    
    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'Nombre',
        'ApPaterno',
        'ApMaterno',
        'Telefono',
        'Empresa_asociada',
        'Correo',
        'Sexo',
        'estado'  // Agregado campo estado
    ];

    protected $casts = [
        'estado' => 'boolean', // Para tratar como booleano
    ];

    // Relación con Compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'Proveedor_idProveedor', 'id');
    }
   
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->Nombre ?? '';
        $apPaterno = $this->ApPaterno ?? '';
        $apMaterno = $this->ApMaterno ?? '';
        
        $nombreCompleto = trim("$nombre $apPaterno $apMaterno");
        
        return !empty($nombreCompleto) ? $nombreCompleto : 'Proveedor';
    }
    
    // Scope para proveedores activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }
    
    // Scope para proveedores inactivos
    public function scopeInactivos($query)
    {
        return $query->where('estado', 0);
    }
    
    // Métodos de estado
    public function isActive(): bool
    {
        return $this->estado == 1;
    }
    
    public function isInactive(): bool
    {
        return $this->estado == 0;
    }
    
    public function getEstadoTextAttribute(): string
    {
        return $this->estado ? 'Activo' : 'Inactivo';
    }
    
    public function getEstadoColorAttribute(): string
    {
        return $this->estado ? 'success' : 'danger';
    }
    
    public function getEstadoBadgeAttribute(): string
    {
        return $this->estado 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-danger">Inactivo</span>';
    }
}