<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Proveedor',
        'estado'  // Agregado campo estado
    ];

    protected $casts = [
        'estado' => 'boolean', // Para tratar como booleano
    ];

    // Relación con Proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'Proveedor', 'id');
    }

    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'Categoria', 'id');
    }
    
    // Scope para categorías activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 1);
    }
    
    // Scope para categorías inactivas
    public function scopeInactivas($query)
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
        return $this->estado ? 'Activa' : 'Inactiva';
    }
    
    public function getEstadoColorAttribute(): string
    {
        return $this->estado ? 'success' : 'danger';
    }
    
    public function getEstadoBadgeAttribute(): string
    {
        return $this->estado 
            ? '<span class="badge bg-success">Activa</span>' 
            : '<span class="badge bg-danger">Inactiva</span>';
    }
}