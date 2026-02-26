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
use App\Http\Controllers\GraficaController;
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

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('password/verify-code', [VerifyCodeController::class, 'showVerifyForm'])->name('password.verify.code.form');
    Route::post('password/verify-code', [VerifyCodeController::class, 'verifyCode'])->name('password.verify.code');
    Route::post('password/resend-code', [VerifyCodeController::class, 'resendCode'])->name('password.resend.code');

    Route::get('password/reset-form', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::get('reset-password/{token}', function($token) {
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
    
    // NUEVA RUTA: Personal inactivos (debe ir ANTES de resource para evitar conflictos)
    Route::prefix('personal')->name('personal.')->group(function () {
        // Ruta para empleados inactivos
        Route::get('/inactivos', [EmpleadoController::class, 'inactivos'])->name('inactivos');
        
        // Ruta para activar empleado (nueva)
        Route::put('/{id}/activate', [EmpleadoController::class, 'activate'])->name('activate');
        
        // Ruta para activar usuario (nueva)
        Route::put('/usuario/{usuario}/activate', [EmpleadoController::class, 'activateUser'])->name('usuario.activate');
        
        // Rutas existentes para usuarios
        Route::post('/{id}/usuario', [EmpleadoController::class, 'storeUser'])->name('usuario.store');
        Route::delete('/usuario/{usuario}', [EmpleadoController::class, 'destroyUser'])->name('usuario.destroy');
    });
    
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('ventas', VentaController::class);
    Route::resource('compras', CompraController::class);
    
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
    Route::get('/clientes/{cliente}/pedidos', [ClienteController::class, 'verificarPedidos'])->name('clientes.pedidos');

    Route::resource('calendario', CalendarioController::class);
    Route::resource('reportes', ReporteController::class);

    Route::post('reportes/ventas', [ReporteController::class, 'generarReporteVentas'])->name('reportes.ventas');
    Route::post('reportes/compras', [ReporteController::class, 'generarReporteCompras'])->name('reportes.compras');
    Route::post('reportes/inventario', [ReporteController::class, 'generarReporteInventario'])->name('reportes.inventario');
    Route::post('reportes/rentabilidad', [ReporteController::class, 'generarReporteRentabilidad'])->name('reportes.rentabilidad');
    Route::post('/pedidos/{id}/cambiar-estado', [App\Http\Controllers\PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
    Route::get('grafica/empleados', [App\Http\Controllers\GraficaController::class, 'graficaEmpleados'])->name('grafica.empleados');
    Route::get('grafica/productos', [App\Http\Controllers\GraficaController::class, 'graficaProductos'])->name('grafica.productos');
    Route::get('grafica/tendencia', [App\Http\Controllers\GraficaController::class, 'graficaTendencia'])->name('grafica.tendencia');
    Route::patch('/categorias/{id}/activar', [CategoriaController::class, 'activar'])->name('categorias.activar');
    Route::patch('/clientes/{id}/activar', [ClienteController::class, 'activar'])->name('clientes.activar');
    Route::patch('/personal/{id}/activar', [EmpleadoController::class, 'activar'])->name('personal.activar');
    Route::patch('/productos/{id}/activar', [ProductoController::class, 'activar'])->name('productos.activar');
    Route::patch('/proveedores/{id}/activar', [ProveedorController::class, 'activar'])->name('proveedores.activar');

});

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});