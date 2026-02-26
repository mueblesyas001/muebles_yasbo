<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'ApPaterno',
        'ApMaterno',
        'Correo',
        'Telefono',
        'Direccion',
        'Sexo',
        'estado'  // Agregado campo estado
    ];

    protected $casts = [
        'estado' => 'boolean', // Para tratar como booleano
    ];

    public function pedidos(){
        return $this->hasMany(Pedido::class, 'Cliente_idCliente', 'id');
    }
    
    // Scope para clientes activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }
    
    // Scope para clientes inactivos
    public function scopeInactivos($query)
    {
        return $query->where('estado', 0);
    }
    
    // Accesor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->Nombre . ' ' . $this->ApPaterno . ' ' . $this->ApMaterno);
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