<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        
        $usuario = Auth::user();
        $empleado = null;
        $nombreEmpleado = 'Invitado';

        if ($usuario) {
            if ($usuario->empleado) {
                $empleado = $usuario->empleado;
                $nombreEmpleado = trim($empleado->Nombre . ' ' . $empleado->ApPaterno . ' ' . $empleado->ApMaterno);
            }
        }

        $ventas_hoy = Venta::whereDate('Fecha', $hoy)->sum('Total') ?? 0;
        $ventas_mes = Venta::where('Fecha', '>=', $inicioMes)->sum('Total') ?? 0;
        
        $pedidos_hoy = Pedido::whereDate('created_at', $hoy)->count();
        $pedidos_pendientes = Pedido::where('Estado', 'pendiente')->count();
        
        $productos_bajo_stock = Producto::where('Cantidad', '<=', DB::raw('Cantidad_minima'))->count();
        
        $top_productos = DB::table('productos')
            ->leftJoin('detalle_venta', 'productos.id', '=', 'detalle_venta.Producto')
            ->select(
                'productos.id',
                'productos.Nombre',
                'productos.Descripcion', 
                'productos.Precio',
                'productos.Cantidad',
                'productos.Cantidad_minima',
                'productos.Cantidad_maxima',
                'productos.Categoria',
                DB::raw('COALESCE(SUM(detalle_venta.Cantidad), 0) as total_vendido')
            )
            ->groupBy(
                'productos.id',
                'productos.Nombre',
                'productos.Descripcion',
                'productos.Precio', 
                'productos.Cantidad',
                'productos.Cantidad_minima',
                'productos.Cantidad_maxima',
                'productos.Categoria'
            )
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        return view('dashboard', [
            // Información del usuario
            'nombre_empleado' => $nombreEmpleado,
            'empleado' => $empleado,
            
            // Totales básicos
            'total_usuarios' => User::count(),
            'total_empleados' => Empleado::count(),
            'total_clientes' => Cliente::count(),
            'total_productos' => Producto::count(),
            'total_pedidos' => Pedido::count(),
            'total_ventas' => Venta::count(),
            'total_proveedores' => Proveedor::count(),
            
            // Datos del día
            'ventas_hoy' => $ventas_hoy,
            'ventas_mes' => $ventas_mes,
            'pedidos_hoy' => $pedidos_hoy,
            'pedidos_pendientes' => $pedidos_pendientes,
            'productos_bajo_stock' => $productos_bajo_stock,
            
            // Productos destacados
            'top_productos' => $top_productos,
            
            // Para las tarjetas
            'ganancias_hoy' => $ventas_hoy * 0.25,
        ]);
    }
}