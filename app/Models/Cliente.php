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
        'Sexo'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'Cliente_idCliente', 'id');
    }
}