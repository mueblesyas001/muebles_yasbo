<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Str;

class Usuario extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, CanResetPassword;

    protected $table = 'usuarios';

    protected $fillable = [
        'empleado_id',
        'correo',
        'contrasena',
        'rol',
        'estado'  // Agregado campo estado
    ];

    protected $hidden = [
        'contrasena',
        'remember_token'
    ];

    protected $casts = [
        'contrasena' => 'hashed',
        'estado' => 'boolean', // Para tratar estado como booleano (true = activo, false = inactivo)
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    public function setRememberToken($value): void{
        $this->remember_token = $value ?? Str::random(60);
    }

    public function getEmailForPasswordReset(): string{
        return $this->correo;
    }

    public function respaldos(){
        return $this->hasMany(Respaldo::class, 'Usuario', 'idUsuario');
    }
    
    // Métodos útiles para el campo estado
    public function isActive(): bool
    {
        return $this->estado == 1;
    }
    
    public function isInactive(): bool
    {
        return $this->estado == 0;
    }
    
    public function getEstadoTextAttribute(): string
    {
        return $this->estado ? 'Activo' : 'Inactivo';
    }
    
    public function getEstadoColorAttribute(): string
    {
        return $this->estado ? 'success' : 'danger';
    }
    
    public function getEstadoBadgeAttribute(): string
    {
        return $this->estado 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-danger">Inactivo</span>';
    }
}