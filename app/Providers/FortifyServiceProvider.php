<?php

namespace App\Providers;

use App\Models\Usuario;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.emails.passwords.password');
        });

        // IMPORTANTE: Usar tu controlador personalizado para la vista
        Fortify::resetPasswordView(function ($request) {
            $controller = app(ResetPasswordController::class);
            return $controller->showResetForm($request, $request->route('token'));
        });

        // Aquí pasamos la CLASE como string, no un Closure
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            $usuario = Usuario::where('correo', $request->input('correo'))->first();

            if ($usuario && Hash::check($request->input('password'), $usuario->contrasena)) {
                Log::info('Login ok', ['correo' => $usuario->correo, 'id' => $usuario->id]);
                return $usuario;
            }

            Log::warning('Login fallido', ['correo' => $request->input('correo')]);
            return null;
        });
    }
}