<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compra';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'Producto',
        'Compra_idCompra', 
        'Cantidad',
        'Precio_unitario',
        'Subtotal'
    ];

    // Relación con Compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'Compra_idCompra', 'id');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'id');
    }
}