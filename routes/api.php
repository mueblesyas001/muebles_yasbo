<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas de tu API. Estas rutas son cargadas
| por el RouteServiceProvider y todas ellas serán asignadas al grupo
| de middleware "api". ¡Crea algo increíble!
|
*/

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});
