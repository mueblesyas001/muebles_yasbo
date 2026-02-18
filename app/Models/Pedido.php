<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'pedidos';
    
    protected $fillable = [
        'Fecha_entrega',
        'Hora_entrega', 
        'Lugar_entrega',
        'Estado',
        'Prioridad',
        'Total',
        'Cliente_idCliente',
        'Empleado_idEmpleado'
    ];

    public $timestamps = false;

    // RELACIONES CORREGIDAS
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Cliente_idCliente', 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'Empleado_idEmpleado', 'id');
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class, 'Pedido', 'id');
    }
}