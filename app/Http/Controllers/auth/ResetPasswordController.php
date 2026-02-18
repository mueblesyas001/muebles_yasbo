<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;

class ResetPasswordController extends Controller
{
    /**
     * Mostrar formulario para crear nueva contraseña
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        
        if (!$token || !$email) {
            Log::warning('Intento de reset sin token o email');
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Enlace inválido o expirado.']);
        }
        
        // Verificar que el token exista y sea válido
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();
            
        if (!$record) {
            Log::warning('Token inválido para: ' . $email);
            return redirect()->route('password.request')
                ->withErrors(['error' => 'El enlace ha expirado o es inválido. Solicita un nuevo código.']);
        }
        
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }
    
    /**
     * Procesar el restablecimiento de contraseña
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);
        
        // Verificar token
        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
            
        if (!$record) {
            Log::warning('Token inválido en reset para: ' . $request->email);
            return back()->withErrors([
                'token' => 'El token ha expirado. Solicita un nuevo código.'
            ])->withInput();
        }
        
        // Buscar usuario
        $usuario = Usuario::where('correo', $request->email)->first();
        
        if (!$usuario) {
            Log::error('Usuario no encontrado al resetear password: ' . $request->email);
            return back()->withErrors([
                'email' => 'Usuario no encontrado.'
            ])->withInput();
        }
        
        // Actualizar contraseña
        $usuario->update([
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);
        
        // Eliminar token usado
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();
        
        Log::info('Contraseña actualizada exitosamente para: ' . $request->email);
        
        // Redirigir con mensaje de éxito
        return redirect()->route('login')
            ->with('success', '¡Contraseña restablecida exitosamente! Ahora puedes iniciar sesión con tu nueva contraseña.');
    }
}