<?php

namespace App\Actions\Fortify;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Carbon\Carbon;

class ResetUserPassword implements ResetsUserPasswords
{
    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function reset($user, array $input)
    {
        // Validación personalizada
        Validator::make($input, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        // Buscar el token en la tabla - usa 'correo' si tu tabla tiene ese campo
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $user->correo) // Si tu tabla usa 'email'
            // ->where('correo', $user->correo) // Si tu tabla usa 'correo'
            ->where('token', $input['token'])
            ->first();

        if (!$tokenData) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'correo' => ['El token no es válido.'],
            ]);
        }

        // Verificar expiración (5 minutos)
        $createdAt = Carbon::parse($tokenData->created_at);
        $now = Carbon::now();
        
        if ($createdAt->diffInMinutes($now) > 5) {
            DB::table('password_reset_tokens')
                ->where('token', $input['token'])
                ->delete();
            
            throw \Illuminate\Validation\ValidationException::withMessages([
                'correo' => ['El token ha expirado. Solicita un nuevo enlace.'],
            ]);
        }

        // Actualizar contraseña - usa 'contrasena' según tu modelo
        $user->forceFill([
            'contrasena' => Hash::make($input['password']),
        ])->save();

        // Eliminar token usado
        DB::table('password_reset_tokens')
            ->where('email', $user->correo) // Si tu tabla usa 'email'
            // ->where('correo', $user->correo) // Si tu tabla usa 'correo'
            ->delete();
    }
}