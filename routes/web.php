<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\RespaldoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReporteInventarioController;
use App\Http\Controllers\ReporteVentasController;
use App\Http\Controllers\ReportePedidosController;
use App\Http\Controllers\ReporteRentabilidadController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerifyCodeController;
use App\Http\Controllers\Auth\ResetPasswordController;
/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN (FORTIFY)
|--------------------------------------------------------------------------
*/
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| RUTAS PARA INVITADOS (NO AUTENTICADOS)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // LOGIN
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Paso 1: Solicitar código
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Paso 2: Verificar código
    Route::get('password/verify-code', [VerifyCodeController::class, 'showVerifyForm'])->name('password.verify.code.form');
    Route::post('password/verify-code', [VerifyCodeController::class, 'verifyCode'])->name('password.verify.code');
    Route::post('password/resend-code', [VerifyCodeController::class, 'resendCode'])->name('password.resend.code');

    // Paso 3: Crear nueva contraseña
    Route::get('password/reset-form', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    // Ruta para password.reset que necesita Fortify
    Route::get('reset-password/{token}', function($token) {
        // Redirige a tu sistema personalizado
        return redirect()->route('password.reset.form', ['token' => $token]);
    })->name('password.reset');
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (AUTENTICADOS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'edit'])->name('edit');
        Route::put('/', [PerfilController::class, 'update'])->name('update');
        Route::get('/cambiar-password', [PerfilController::class, 'showChangePasswordForm'])
            ->name('cambiar-password');
        Route::put('/cambiar-password', [PerfilController::class, 'changePassword'])
            ->name('actualizar-password');
    });

    /*
    |--------------------------------------------------------------------------
    | CRUDs
    |--------------------------------------------------------------------------
    */
    Route::resource('personal', EmpleadoController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('ventas', VentaController::class);
    Route::resource('compras', CompraController::class);
    Route::prefix('personal')->name('personal.')->group(function () {
        Route::post('/{id}/usuario', [EmpleadoController::class, 'storeUser'])
            ->name('usuario.store');
        
        Route::delete('/usuario/{usuario}', [EmpleadoController::class, 'destroyUser'])
            ->name('usuario.destroy');
    });
    Route::resource('pedidos', PedidoController::class);
    Route::resource('calendario', CalendarioController::class);
    // Rutas para respaldos
    Route::prefix('respaldos')->group(function () {
        Route::get('/', [RespaldoController::class, 'index'])->name('respaldos.index');
        Route::get('/create', [RespaldoController::class, 'create'])->name('respaldos.create');
        Route::post('/', [RespaldoController::class, 'store'])->name('respaldos.store');
        Route::get('/{id}', [RespaldoController::class, 'show'])->name('respaldos.show');
        Route::get('/{id}/edit', [RespaldoController::class, 'edit'])->name('respaldos.edit');
        Route::put('/{id}', [RespaldoController::class, 'update'])->name('respaldos.update');
        Route::delete('/{id}', [RespaldoController::class, 'destroy'])->name('respaldos.destroy');
        
        // Rutas específicas
        Route::get('/descargar/{id}', [RespaldoController::class, 'descargar'])->name('respaldos.descargar');
        Route::get('/info/{id}', [RespaldoController::class, 'info'])->name('respaldos.info');
        Route::get('/verificar/{id}', [RespaldoController::class, 'verificarEstado'])->name('respaldos.verificar');
        Route::get('/forzar-descarga/{id}', [RespaldoController::class, 'forzarDescarga'])->name('respaldos.forzar-descarga');
        
        // Ruta para mostrar el formulario de restauración desde archivo
        Route::get('/restaurar/desde-archivo', [RespaldoController::class, 'restaurarDesdeArchivoForm'])->name('respaldos.restaurar.form');

        // Ruta para procesar la restauración desde archivo
        Route::post('/restaurar/desde-archivo', [RespaldoController::class, 'restaurarDesdeArchivo'])->name('respaldos.restaurar');
        // Utilitarios
        Route::post('/generar-manual', [RespaldoController::class, 'generarManual'])->name('respaldos.generar-manual');
        Route::get('/verificar-conexion', [RespaldoController::class, 'verificarConexion'])->name('respaldos.verificar-conexion');
        Route::post('/store-simple', [RespaldoController::class, 'storeSimple'])->name('respaldos.store-simple');
    });
    // Restaurar desde archivo SQL subido
    Route::post('/respaldos/restaurar-archivo', [App\Http\Controllers\RespaldoController::class, 'restaurarDesdeArchivo'])
        ->name('respaldos.restaurar-archivo');
    // Rutas para el calendario
    Route::get('/calendario/pedido/{id}', [CalendarioController::class, 'obtenerDetallePedido'])->name('calendario.pedido.detalle');
    Route::get('/calendario/eventos', [CalendarioController::class, 'eventosJson'])->name('calendario.eventos');
    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    */

    Route::prefix('reportes-inventario')->name('reportes.inventario.')->group(function () {
        Route::get('/', [ReporteInventarioController::class, 'index'])->name('index');
        Route::get('/generar', [ReporteInventarioController::class, 'generarReporte'])->name('generar');
    });

    Route::prefix('reportes-ventas')->name('reportes.ventas.')->group(function () {
        Route::get('/', [ReporteVentasController::class, 'index'])->name('index');
        Route::get('/generar', [ReporteVentasController::class, 'generarReporteCompleto'])->name('generar');
    });
   
    Route::prefix('reportes-pedidos')->name('reportes.pedidos.')->group(function () {
        Route::get('/', [ReportePedidosController::class, 'index'])->name('index');
        Route::get('/generar', [ReportePedidosController::class, 'generarReporteCompleto'])->name('generar');
    });

    

    Route::prefix('reportes-rentabilidad')->name('reportes.rentabilidad.')->group(function () {
        Route::get('/', [ReporteRentabilidadController::class, 'index'])->name('index');
        Route::get('/generar', [ReporteRentabilidadController::class, 'generarReporteCompleto'])->name('generar');
    });

    Route::resource('calendario', CalendarioController::class);
    Route::resource('reportes', ReporteController::class);

    Route::post('reportes/ventas', [ReporteController::class, 'generarReporteVentas'])->name('reportes.ventas');
    Route::post('reportes/compras', [ReporteController::class, 'generarReporteCompras'])->name('reportes.compras');
    Route::post('reportes/inventario', [ReporteController::class, 'generarReporteInventario'])->name('reportes.inventario');
    Route::post('reportes/rentabilidad', [ReporteController::class, 'generarReporteRentabilidad'])->name('reportes.rentabilidad');
});

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});
