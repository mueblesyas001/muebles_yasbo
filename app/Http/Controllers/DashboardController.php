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
use App\Models\DetallePedido;
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
        
        // Obtener pedidos pendientes con cliente y detalles (¡CORREGIDO!)
        $pedidos_pendientes_lista = Pedido::with(['cliente', 'detallePedidos.producto'])
            ->where('Estado', 'pendiente')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        
        $productos_bajo_stock = Producto::where('Cantidad', '<=', DB::raw('Cantidad_minima'))->count();
        
        // PRODUCTOS BAJO STOCK
        $productos_bajo_stock_lista = Producto::where('Cantidad', '<=', DB::raw('Cantidad_minima'))
            ->orderByRaw('(Cantidad / Cantidad_minima) ASC')
            ->limit(10)
            ->get();
        
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

        // GENERAR ALERTAS
        $alertas = collect();
        
        // Alerta de pedidos pendientes
        if ($pedidos_pendientes > 0) {
            $alertas->push([
                'tipo' => 'warning',
                'icono' => 'fa-clock',
                'titulo' => 'Pedidos Pendientes',
                'mensaje' => "Tienes <strong>{$pedidos_pendientes}</strong> pedido(s) pendiente(s) por procesar.",
                'accion' => route('pedidos.index', ['estado' => 'pendiente']),
                'btn_texto' => 'Ver pedidos',
                'btn_icono' => 'fa-arrow-right'
            ]);
        }

        // Alerta de productos bajos en stock
        if ($productos_bajo_stock > 0) {
            $alertas->push([
                'tipo' => 'danger',
                'icono' => 'fa-exclamation-triangle',
                'titulo' => 'Stock Crítico',
                'mensaje' => "<strong>{$productos_bajo_stock}</strong> producto(s) tienen stock por debajo del mínimo.",
                'accion' => route('productos.index', ['bajo_stock' => 1]),
                'btn_texto' => 'Revisar stock',
                'btn_icono' => 'fa-box'
            ]);
        }

        // Alerta de pedidos del día
        if ($pedidos_hoy > 0) {
            $alertas->push([
                'tipo' => 'info',
                'icono' => 'fa-calendar-day',
                'titulo' => 'Pedidos de Hoy',
                'mensaje' => "Hoy tienes <strong>{$pedidos_hoy}</strong> pedido(s) registrados.",
                'accion' => route('pedidos.index', ['fecha' => Carbon::today()->format('Y-m-d')]),
                'btn_texto' => 'Ver pedidos hoy',
                'btn_icono' => 'fa-eye'
            ]);
        }

        // Alerta de ventas del día
        if ($ventas_hoy > 0) {
            $alertas->push([
                'tipo' => 'success',
                'icono' => 'fa-chart-line',
                'titulo' => 'Ventas del Día',
                'mensaje' => "Las ventas de hoy ascienden a <strong>$" . number_format($ventas_hoy, 2) . "</strong>",
                'accion' => route('ventas.index', ['fecha' => Carbon::today()->format('Y-m-d')]),
                'btn_texto' => 'Ver ventas',
                'btn_icono' => 'fa-coins'
            ]);
        }

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
            
            // Listas detalladas
            'pedidos_pendientes_lista' => $pedidos_pendientes_lista,
            'productos_bajo_stock_lista' => $productos_bajo_stock_lista,
            
            // Productos destacados
            'top_productos' => $top_productos,
            
            // Para las tarjetas
            'ganancias_hoy' => $ventas_hoy * 0.25,
            
            // Alertas
            'alertas' => $alertas,
        ]);
    }
}