<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Producto',
        'Venta',
        'Cantidad'
    ];

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'id');
    }

    // Relación con Venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'Venta', 'id');
    }

    // Accesor para calcular el subtotal dinámicamente
    public function getSubtotalAttribute()
    {
        return $this->producto ? $this->producto->Precio * $this->Cantidad : 0;
    }

    // Accesor para subtotal formateado
    public function getSubtotalFormateadoAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    // Accesor para precio unitario desde el producto
    public function getPrecioUnitarioAttribute()
    {
        return $this->producto ? $this->producto->Precio : 0;
    }

    // Accesor para precio unitario formateado
    public function getPrecioUnitarioFormateadoAttribute()
    {
        return '$' . number_format($this->precio_unitario, 2);
    }
}