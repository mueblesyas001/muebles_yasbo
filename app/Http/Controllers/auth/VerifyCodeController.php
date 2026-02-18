<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Usuario;
use Illuminate\Support\Str;

class VerifyCodeController extends Controller
{
    /**
     * Mostrar formulario para ingresar el código
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            Log::warning('Intento de verificar código sin email');
            return redirect()->route('password.request')
                ->withErrors(['correo' => 'Correo electrónico no proporcionado.']);
        }
        
        // Verificar que exista un código pendiente para este email
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->first();
            
        if (!$record) {
            Log::warning('No hay código pendiente para: ' . $email);
            return redirect()->route('password.request')
                ->withErrors([
                    'correo' => 'No hay un código pendiente para este email. Solicita uno nuevo.'
                ]);
        }
        
        Log::info('Mostrando formulario de verificación para: ' . $email);
        return view('auth.verify-code', ['email' => $email]);
    }
    
    /**
     * Verificar el código ingresado
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);
        
        $email = $request->email;
        $code = $request->code;
        
        Log::info('Verificando código para: ' . $email . ' - Código: ' . $code);
        
        // Limitar intentos de verificación
        $throttleKey = 'code-verify:' . $request->ip() . ':' . $email;
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'code' => "Demasiados intentos fallidos. Por favor espere {$seconds} segundos."
            ])->withInput();
        }
        
        RateLimiter::hit($throttleKey);
        
        // Buscar el código en la base de datos
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $code)
            ->first();
        
        if (!$record) {
            Log::warning('Código incorrecto para: ' . $email);
            return back()->withErrors([
                'code' => 'El código es incorrecto. Verifica los 6 dígitos.'
            ])->withInput();
        }
        
        // Verificar expiración (15 minutos)
        $createdAt = Carbon::parse($record->created_at);
        $expiresAt = $createdAt->addMinutes(15);
        
        if (Carbon::now()->gt($expiresAt)) {
            DB::table('password_resets')->where('email', $email)->delete();
            Log::warning('Código expirado para: ' . $email);
            
            return back()->withErrors([
                'code' => 'El código ha expirado (válido por 15 minutos). Solicita uno nuevo.'
            ])->withInput();
        }
        
        // Código válido - limpiar intentos
        RateLimiter::clear($throttleKey);
        
        Log::info('Código verificado correctamente para: ' . $email);
        
        // Generar token único para el reset de contraseña
        $resetToken = Str::random(60);
        
        // Actualizar el registro con el token de reset
        DB::table('password_resets')
            ->where('email', $email)
            ->update([
                'token' => $resetToken,
                'created_at' => now()
            ]);
        
        // Redirigir al formulario de nueva contraseña
        return redirect()->route('password.reset.form', [
            'token' => $resetToken,
            'email' => $email
        ])->with('success', '¡Código verificado! Ahora puedes crear tu nueva contraseña.');
    }
    
    /**
     * Reenviar código
     */
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $email = $request->email;
        
        Log::info('Solicitando reenvío de código para: ' . $email);
        
        // Limitar reenvíos
        $throttleKey = 'resend-code:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'code' => "Demasiados intentos de reenvío. Espere {$seconds} segundos."
            ]);
        }
        
        RateLimiter::hit($throttleKey);
        
        try {
            // Verificar que el usuario exista
            $usuario = Usuario::where('correo', $email)->first();
            
            if (!$usuario) {
                Log::warning('Intento de reenviar código a email no registrado: ' . $email);
                return back()->withErrors([
                    'email' => 'Este correo electrónico no está registrado.'
                ]);
            }
            
            // Generar nuevo código
            $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Actualizar o insertar código
            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $codigo,
                    'created_at' => now()
                ]
            );
            
            Log::info('Nuevo código generado para ' . $email . ': ' . $codigo);
            
            // Crear URL para la página de verificación
            $verifyUrl = url('/password/verify-code?email=' . urlencode($email));
            
            // HTML del email (puedes usar el mismo que en ForgotPasswordController)
            $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Nuevo Código - Muebles Yasbo</title>
                <style>
                    /* Estilos similares al email anterior */
                    body { font-family: Arial, sans-serif; }
                    .code { font-size: 32px; font-weight: bold; color: #4361ee; }
                </style>
            </head>
            <body>
                <h2>Nuevo Código de Verificación</h2>
                <p>Hola ' . htmlspecialchars($usuario->empleado->Nombre ?? 'Usuario') . ',</p>
                <p>Has solicitado un nuevo código de verificación.</p>
                <p>Tu nuevo código es:</p>
                <div class="code">' . $codigo . '</div>
                <p>Válido por 15 minutos.</p>
                <p><a href="' . $verifyUrl . '">Ir a verificar código</a></p>
            </body>
            </html>';
            
            // Enviar email
            Mail::html($html, function($message) use ($email, $usuario) {
                $message->to($email)
                        ->subject('🔄 Nuevo Código de Verificación - Muebles Yasbo')
                        ->from('noreply@mueblesyasbo.com', 'Muebles Yasbo');
            });
            
            Log::info('Nuevo código enviado a: ' . $email);
            RateLimiter::clear($throttleKey);
            
            return back()->with([
                'status' => '¡Nuevo código enviado!',
                'success' => 'Hemos enviado un nuevo código de 6 dígitos a tu correo.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al reenviar código: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Error al reenviar el código. Por favor, intenta nuevamente.'
            ]);
        }
    }
}