<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(){
        return view('auth.emails.password');
    }

    public function sendResetLinkEmail(Request $request){
        // Validación
               $request->validate([
            'correo' => 'required|email',  
        ], [
            'correo.required' => 'El campo correo electrónico es obligatorio.',
            'correo.email' => 'Por favor ingresa un correo electrónico válido.',
        ]); 
    

        // Limitar intentos 
        $throttleKey = 'password-reset:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'correo' => "Demasiados intentos. Por favor espere {$seconds} segundos."
            ]);
        }

        RateLimiter::hit($throttleKey);

        try {
            // Obtener el correo
            $email = $request->input('correo');
            \Log::info('Intentando enviar código de recuperación a: ' . $email);
            
            // Buscar usuario
            $usuario = Usuario::where('correo', $email)->first();
            
            if (!$usuario) {
                \Log::warning('Usuario no encontrado con correo: ' . $email);
                return back()->withErrors([
                    'correo' => 'Este correo electrónico no está registrado en nuestro sistema.'
                ]);
            }
            
            // Generar código de 6 dígitos
            $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Eliminar códigos antiguos para este email
            DB::table('password_resets')
                ->where('email', $email)
                ->delete();
            
            // Insertar nuevo código
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $codigo, // Guardamos el código como token
                'created_at' => now()
            ]);
            
            \Log::info('Código generado para ' . $email . ': ' . $codigo);
            
            // Crear URL para la página de verificación de código
            $verifyUrl = url('/password/verify-code?email=' . urlencode($email));
            
            // HTML DEL EMAIL CON CÓDIGO
            $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Código de Verificación - Muebles Yasbo</title>
                <style>
                    /* Estilos base */
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
                        padding: 20px;
                        margin: 0;
                    }
                    
                    .email-wrapper {
                        max-width: 650px;
                        margin: 0 auto;
                        background: white;
                        border-radius: 20px;
                        overflow: hidden;
                        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
                    }
                    
                    /* Encabezado con gradiente */
                    .header {
                        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
                        padding: 40px 30px;
                        text-align: center;
                        color: white;
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .header::before {
                        content: \'\';
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
                        animation: shimmer 3s infinite linear;
                    }
                    
                    @keyframes shimmer {
                        0% { transform: translateX(-100%); }
                        100% { transform: translateX(100%); }
                    }
                    
                    .brand-name {
                        font-size: 2.5rem;
                        font-weight: 800;
                        margin-bottom: 10px;
                        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                    }
                    
                    .brand-subtitle {
                        font-size: 1.1rem;
                        opacity: 0.9;
                        font-weight: 300;
                    }
                    
                    /* Contenido principal */
                    .content {
                        padding: 40px 30px;
                    }
                    
                    .greeting {
                        color: #4361ee;
                        font-size: 1.6rem;
                        margin-bottom: 20px;
                        font-weight: 700;
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    }
                    
                    .message {
                        color: #555;
                        font-size: 1rem;
                        margin-bottom: 25px;
                        line-height: 1.7;
                    }
                    
                    .highlight {
                        color: #4361ee;
                        font-weight: 600;
                    }
                    
                    /* CÓDIGO DE VERIFICACIÓN */
                    .code-container {
                        text-align: center;
                        margin: 40px 0;
                        padding: 30px;
                        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
                        border-radius: 15px;
                        border: 2px dashed #4361ee;
                    }
                    
                    .code-label {
                        color: #4361ee;
                        font-size: 1.2rem;
                        margin-bottom: 15px;
                        font-weight: 600;
                    }
                    
                    .code-display {
                        font-size: 3.5rem;
                        font-weight: 800;
                        color: #3a0ca3;
                        letter-spacing: 10px;
                        background: white;
                        padding: 20px;
                        border-radius: 10px;
                        margin: 20px auto;
                        display: inline-block;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                        border: 3px solid #4361ee;
                    }
                    
                    .code-expiry {
                        color: #666;
                        font-size: 0.95rem;
                        margin-top: 15px;
                    }
                    
                    /* Botón */
                    .button-container {
                        text-align: center;
                        margin: 30px 0;
                    }
                    
                    .verify-button {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 15px;
                        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
                        color: white;
                        text-decoration: none;
                        padding: 18px 45px;
                        border-radius: 12px;
                        font-size: 1.2rem;
                        font-weight: 700;
                        letter-spacing: 0.5px;
                        transition: all 0.3s ease;
                        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
                        border: none;
                        cursor: pointer;
                    }
                    
                    .verify-button:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.5);
                        background: linear-gradient(135deg, #3a56d4 0%, #2a0a91 100%);
                    }
                    
                    /* Pasos */
                    .steps-container {
                        background: linear-gradient(to right, #eef2ff, #f8fafc);
                        padding: 25px;
                        border-radius: 12px;
                        margin: 30px 0;
                    }
                    
                    .step {
                        display: flex;
                        align-items: center;
                        margin-bottom: 15px;
                        padding: 15px;
                        background: white;
                        border-radius: 10px;
                        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
                    }
                    
                    .step-number {
                        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
                        color: white;
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 800;
                        font-size: 1.1rem;
                        margin-right: 15px;
                        flex-shrink: 0;
                    }
                    
                    .step-content h4 {
                        color: #333;
                        margin-bottom: 5px;
                        font-size: 1rem;
                    }
                    
                    .step-content p {
                        color: #666;
                        font-size: 0.9rem;
                        margin: 0;
                    }
                    
                    /* Seguridad */
                    .security-note {
                        background: #fff3cd;
                        border-left: 5px solid #ffc107;
                        padding: 20px;
                        border-radius: 10px;
                        margin: 25px 0;
                        color: #856404;
                    }
                    
                    /* Pie de página */
                    .footer {
                        background: linear-gradient(to right, #f8f9fa, #f1f3f5);
                        padding: 30px;
                        text-align: center;
                        border-top: 1px solid rgba(0, 0, 0, 0.05);
                    }
                    
                    .copyright {
                        color: #adb5bd;
                        font-size: 0.85rem;
                        margin-top: 15px;
                    }
                    
                    /* Responsive */
                    @media (max-width: 600px) {
                        .code-display {
                            font-size: 2.5rem;
                            letter-spacing: 5px;
                            padding: 15px;
                        }
                        
                        .content {
                            padding: 25px 20px;
                        }
                    }
                </style>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            </head>
            <body>
                <div class="email-wrapper">
                    <!-- Encabezado -->
                    <div class="header">
                        <h1 class="brand-name">MUEBLES YASBO</h1>
                        <p class="brand-subtitle">Código de Verificación</p>
                    </div>
                    
                    <!-- Contenido principal -->
                    <div class="content">
                        <h2 class="greeting">
                            <i class="fas fa-shield-alt"></i>
                            <span>Código de Seguridad</span>
                        </h2>
                        
                        <p class="message">
                            Hola, <span class="highlight">' . htmlspecialchars($usuario->empleado->Nombre ?? 'Usuario') . '</span>. 
                            Has solicitado restablecer tu contraseña en el 
                            <span class="highlight">Sistema Administrativo Muebles Yasbo</span>.
                        </p>
                        
                        <p class="message">
                            Usa el siguiente código de 6 dígitos para verificar tu identidad:
                        </p>
                        
                        <!-- CÓDIGO -->
                        <div class="code-container">
                            <div class="code-label">
                                <i class="fas fa-key"></i> TU CÓDIGO DE VERIFICACIÓN
                            </div>
                            <div class="code-display">' . $codigo . '</div>
                            <div class="code-expiry">
                                <i class="fas fa-clock"></i> Válido por 15 minutos
                            </div>
                        </div>
                        
                        <!-- Botón para ir a verificar -->
                        <div class="button-container">
                            <a href="' . $verifyUrl . '" class="verify-button">
                                <i class="fas fa-check-circle"></i>
                                <span>IR A VERIFICAR CÓDIGO</span>
                            </a>
                        </div>
                        
                        <!-- Pasos -->
                        <div class="steps-container">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h4>Copia el código</h4>
                                    <p>Anota o copia el código de 6 dígitos mostrado arriba.</p>
                                </div>
                            </div>
                            
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h4>Ingresa el código</h4>
                                    <p>Ve a la página de verificación e ingresa el código.</p>
                                </div>
                            </div>
                            
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h4>Crea nueva contraseña</h4>
                                    <p>Una vez verificado, podrás crear tu nueva contraseña.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nota de seguridad -->
                        <div class="security-note">
                            <strong><i class="fas fa-exclamation-triangle"></i> IMPORTANTE:</strong><br>
                            • No compartas este código con nadie.<br>
                            • El personal de Muebles Yasbo nunca te pedirá este código.<br>
                            • Si no solicitaste este código, ignora este mensaje.
                        </div>
                        
                        <p class="message">
                            <strong><i class="fas fa-question-circle"></i> ¿Problemas con el código?</strong><br>
                            Si el código no funciona o ha expirado, solicita uno nuevo desde la página de recuperación.
                        </p>
                    </div>
                    
                    <!-- Pie de página -->
                    <div class="footer">
                        <div class="copyright">
                            <p>© ' . date('Y') . ' Muebles Yasbo. Todos los derechos reservados.</p>
                            <p>Este es un correo automático, por favor no responder directamente.</p>
                            <p><small>ID de solicitud: ' . Str::random(8) . ' | ' . date('d/m/Y H:i:s') . '</small></p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
            
            // Enviar email
            Mail::html($html, function($message) use ($email, $usuario) {
                $message->to($email)
                        ->subject('🔢 Tu Código de Verificación - Muebles Yasbo')
                        ->from('noreply@mueblesyasbo.com', 'Muebles Yasbo - Sistema Administrativo');
            });
            
            \Log::info('Email con código enviado exitosamente a: ' . $email);

            RateLimiter::clear($throttleKey);
            
            // Redirigir a la página de verificación de código
            return redirect()->route('password.verify.code.form', ['email' => $email])
                ->with([
                    'status' => '¡Código enviado!',
                    'success' => 'Hemos enviado un código de 6 dígitos a tu correo. Revisa tu bandeja de entrada.'
                ]);

        } catch (\Exception $e) {
            \Log::error('ERROR al enviar código: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->withErrors([
                'correo' => 'Error al enviar el código. Por favor, intenta nuevamente.'
            ]);
        }
    }
}