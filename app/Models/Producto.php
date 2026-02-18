<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 

class Producto extends Model{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Precio',
        'Cantidad',
        'Cantidad_minima',
        'Cantidad_maxima',
        'Categoria'
    ];

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Categoria', 'id');
    }

    // Relación con Detalle_venta
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'Producto', 'id');
    }

    // Relación con Detalle_compra
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'Producto', 'id');
    }

    // Relación con Detalle_pedido
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class, 'Producto', 'id');
    }

    // Accesor para precio formateado
    public function getPrecioFormateadoAttribute()
    {
        return '$' . number_format($this->Precio, 2);
    }

    // Scope para productos con stock
    public function scopeConStock($query)
    {
        return $query->where('Cantidad', '>', 0);
    }

    // Scope para productos bajos en stock
    public function scopeBajoStock($query)
    {
        return $query->where('Cantidad', '<=', DB::raw('Cantidad_minima'));
    }
}