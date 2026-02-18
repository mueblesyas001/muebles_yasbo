<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla (opcional si sigue convención)
    protected $table = 'proveedores';

    // Especificar la clave primaria
    protected $primaryKey = 'id';

    // Indicar que la clave primaria es auto-incrementable
    public $incrementing = true;

    // Especificar el tipo de la clave primaria
    protected $keyType = 'int';

    public $timestamps = false;
    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'Nombre',
        'ApPaterno',
        'ApMaterno',
        'Telefono',
        'Empresa_asociada',
        'Correo',
        'Sexo'
    ];

    // Relación con Compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'Proveedor_idProveedor', 'id');
    }
   
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->Nombre ?? '';
        $apPaterno = $this->ApPaterno ?? '';
        $apMaterno = $this->ApMaterno ?? '';
        
        $nombreCompleto = trim("$nombre $apPaterno $apMaterno");
        
        return !empty($nombreCompleto) ? $nombreCompleto : 'Proveedor';
    }
}