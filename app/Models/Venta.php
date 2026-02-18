<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Fecha',
        'Total',
        'Empleado_idEmpleado'
    ];

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'Empleado_idEmpleado', 'id');
    }

    // Relación con DetalleVenta
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'Venta', 'id');
    }

    // Accesor para fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return \Carbon\Carbon::parse($this->Fecha)->format('d/m/Y H:i:s');
    }

    // Scope para ventas del día
    public function scopeHoy($query)
    {
        return $query->whereDate('Fecha', today());
    }

    // Scope para ventas del mes
    public function scopeEsteMes($query)
    {
        return $query->whereMonth('Fecha', now()->month)
                    ->whereYear('Fecha', now()->year);
    }
}