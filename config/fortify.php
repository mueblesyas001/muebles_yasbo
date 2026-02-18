<?php

use Laravel\Fortify\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | Habilitar rutas de Fortify
    |--------------------------------------------------------------------------
    |
    | Aquí puedes habilitar o deshabilitar las rutas que Fortify proporciona
    | para la autenticación de usuarios.
    |
    */

    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'correo', 
    'email' => 'correo',

    'home' => '/dashboard',

    /*
    |--------------------------------------------------------------------------
    | Vistas
    |--------------------------------------------------------------------------
    |
    | Puedes dejar que Fortify use sus propias vistas o desactivarlas
    | si estás usando tus propias (como ya haces tú con auth.login)
    |
    */

    'views' => false,

    /*
    |--------------------------------------------------------------------------
    | Características disponibles
    |--------------------------------------------------------------------------
    |
    | Aquí decides qué características usarás. Puedes deshabilitar las que no
    | necesites (registro, verificación, etc.)
    |
    */

    'features' => [
        Features::registration(), 
        //Features::resetPasswords(), 
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
    ],
];
