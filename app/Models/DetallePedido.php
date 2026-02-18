<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Producto',
        'Pedido',
        'Cantidad',
        'PrecioUnitario'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'Pedido', 'id');
    }
}