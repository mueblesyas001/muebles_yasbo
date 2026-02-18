<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respaldo extends Model
{
    use HasFactory;

    protected $table = 'respaldo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Ruta',
        'Fecha',
        'Usuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Usuario', 'idUsuario');
    }
}