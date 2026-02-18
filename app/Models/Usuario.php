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
        'rol'
    ];

    protected $hidden = [
        'contrasena',
        'remember_token'
    ];

    protected $casts = [
        'contrasena' => 'hashed',
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
}
