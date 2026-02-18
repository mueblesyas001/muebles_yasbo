<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'id'; 
    public $timestamps = true; 

    protected $fillable = [
        'Fecha_compra',
        'Total',
        'Proveedor_idProveedor'
    ];

    protected $casts = [
        'Fecha_compra' => 'datetime',
        'Total' => 'decimal:2'
    ];

    // Relación con Proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'Proveedor_idProveedor', 'id');
    }

    // Relación con Detalle_compra - CORREGIDA
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'Compra_idCompra', 'id'); // CAMBIADO: usar 'id'
    }

    // Relación con Productos a través de Detalle_compra
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_compra', 'Compra_idCompra', 'Producto')
                    ->withPivot('Cantidad', 'Precio_unitario', 'Subtotal');
    }

    // ELIMINAR getRouteKeyName() o actualizarlo
    public function getRouteKeyName()
    {
        return 'id'; // CAMBIADO: usar 'id'
    }
}