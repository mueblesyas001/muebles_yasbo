<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Empleado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportePedidosController extends Controller
{
    // Vista principal con filtros
    public function index()
    {
        $clientes = Cliente::all();
        $empleados = Empleado::all();
        
        // Estadísticas rápidas
        $estadisticas = [
            'pedidos_hoy' => Pedido::whereDate('Fecha_entrega', today())->count(),
            'pedidos_pendientes' => Pedido::where('Estado', 'Pendiente')->count(),
            'pedidos_entregados' => Pedido::where('Estado', 'Entregado')->count(),
            'total_pedidos_mes' => Pedido::whereMonth('Fecha_entrega', now()->month)
                                      ->whereYear('Fecha_entrega', now()->year)
                                      ->count(),
            'total_ingresos_mes' => Pedido::whereMonth('Fecha_entrega', now()->month)
                                        ->whereYear('Fecha_entrega', now()->year)
                                        ->sum('Total'),
        ];
        
        return view('reportes.pedidos.index', compact('clientes', 'empleados', 'estadisticas'));
    }
    
    // GENERAR UN SOLO PDF COMPLETO DE PEDIDOS
    public function generarReporteCompleto(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // ====================
        // OBTENER DATOS
        // ====================
        
        // 1. Pedidos filtrados
        $query = Pedido::with(['cliente', 'empleado', 'detallePedidos.producto']);
        
        // Filtrar por fechas de entrega
        $query->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
              ->whereDate('Fecha_entrega', '<=', $request->fecha_fin);
        
        // Filtros adicionales
        if ($request->filled('cliente_id')) {
            $query->where('Cliente_idCliente', $request->cliente_id);
        }
        
        if ($request->filled('empleado_id')) {
            $query->where('Empleado_idEmpleado', $request->empleado_id);
        }
        
        if ($request->filled('estado')) {
            $query->where('Estado', $request->estado);
        }
        
        if ($request->filled('prioridad')) {
            $query->where('Prioridad', $request->prioridad);
        }
        
        // Ordenar
        $orden = $request->orden ?? 'fecha_desc';
        switch ($orden) {
            case 'fecha_asc':
                $query->orderBy('Fecha_entrega', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('Total', 'desc');
                break;
            case 'total_asc':
                $query->orderBy('Total', 'asc');
                break;
            case 'prioridad':
                $query->orderByRaw("FIELD(Prioridad, 'Alta', 'Media', 'Baja')");
                break;
            default:
                $query->orderBy('Fecha_entrega', 'desc');
        }
        
        $pedidos = $query->get();
        
        // 2. Detalle de pedidos agregado
        $detallePedidos = DetallePedido::with(['producto', 'pedido.cliente', 'pedido.empleado'])
            ->whereHas('pedido', function($q) use ($request) {
                $q->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha_entrega', '<=', $request->fecha_fin);
                
                if ($request->filled('cliente_id')) {
                    $q->where('Cliente_idCliente', $request->cliente_id);
                }
                
                if ($request->filled('empleado_id')) {
                    $q->where('Empleado_idEmpleado', $request->empleado_id);
                }
                
                if ($request->filled('estado')) {
                    $q->where('Estado', $request->estado);
                }
            })
            ->get();
        
        // 3. Productos más solicitados en pedidos
        $productosMasSolicitados = DetallePedido::with('producto')
            ->select(
                'Producto',
                DB::raw('SUM(Cantidad) as total_solicitado'),
                DB::raw('COUNT(DISTINCT Pedido) as veces_solicitado'),
                DB::raw('AVG(PrecioUnitario) as precio_promedio'),
                DB::raw('SUM(Cantidad * PrecioUnitario) as total_valor')
            )
            ->whereHas('pedido', function($q) use ($request) {
                $q->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha_entrega', '<=', $request->fecha_fin);
            })
            ->groupBy('Producto')
            ->orderBy('total_solicitado', 'desc')
            ->take(15)
            ->get();
        
        // 4. Pedidos por cliente
        $pedidosPorCliente = Pedido::with('cliente')
            ->select(
                'Cliente_idCliente',
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(Total) as total_ingresos'),
                DB::raw('AVG(Total) as promedio_pedido'),
                DB::raw('MAX(Total) as pedido_mas_alto')
            )
            ->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_entrega', '<=', $request->fecha_fin)
            ->groupBy('Cliente_idCliente')
            ->orderBy('total_ingresos', 'desc')
            ->get();
        
        // 5. Pedidos por empleado
        $pedidosPorEmpleado = Pedido::with('empleado')
            ->select(
                'Empleado_idEmpleado',
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(Total) as total_ingresos'),
                DB::raw('AVG(Total) as promedio_pedido'),
                DB::raw('COUNT(CASE WHEN Estado = "Entregado" THEN 1 END) as entregados'),
                DB::raw('COUNT(CASE WHEN Estado = "Pendiente" THEN 1 END) as pendientes')
            )
            ->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_entrega', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->orderBy('total_ingresos', 'desc')
            ->get();
        
        // 6. Pedidos por estado
        $pedidosPorEstado = Pedido::select(
                'Estado',
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(Total) as total'),
                DB::raw('AVG(Total) as promedio')
            )
            ->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_entrega', '<=', $request->fecha_fin)
            ->groupBy('Estado')
            ->orderBy('cantidad', 'desc')
            ->get();
        
        // 7. Pedidos por prioridad
        $pedidosPorPrioridad = Pedido::select(
                'Prioridad',
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(Total) as total'),
                DB::raw('AVG(Total) as promedio')
            )
            ->whereDate('Fecha_entrega', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_entrega', '<=', $request->fecha_fin)
            ->groupBy('Prioridad')
            ->orderByRaw("FIELD(Prioridad, 'Alta', 'Media', 'Baja')")
            ->get();
        
        // 8. Estadísticas generales
        $totalPedidos = $pedidos->count();
        $totalIngresos = $pedidos->sum('Total');
        $pedidoPromedio = $totalPedidos > 0 ? $totalIngresos / $totalPedidos : 0;
        
        // Pedido más alto del período
        $pedidoMasAlto = $pedidos->sortByDesc('Total')->first();
        
        // Productos únicos solicitados
        $productosUnicos = $detallePedidos->groupBy('Producto')->count();
        
        // 9. Resumen ejecutivo
        $resumenEjecutivo = [
            'total_pedidos' => $totalPedidos,
            'total_ingresos' => $totalIngresos,
            'pedido_promedio' => $pedidoPromedio,
            'cliente_top' => $pedidosPorCliente->first(),
            'empleado_top' => $pedidosPorEmpleado->first(),
            'producto_top' => $productosMasSolicitados->first(),
            'dias_periodo' => Carbon::parse($request->fecha_inicio)
                ->diffInDays(Carbon::parse($request->fecha_fin)) + 1,
            'pedidos_por_dia' => $totalPedidos > 0 ? 
                $totalPedidos / (Carbon::parse($request->fecha_inicio)
                ->diffInDays(Carbon::parse($request->fecha_fin)) + 1) : 0,
            'pendientes_por_entregar' => $pedidos->where('Estado', 'Pendiente')->count(),
            'valor_pendientes' => $pedidos->where('Estado', 'Pendiente')->sum('Total'),
        ];
        
        // 10. Productos con mayor valor en pedidos
        $productosValorPedidos = $productosMasSolicitados->sortByDesc('total_valor')->take(10);
        
        // ====================
        // GENERAR PDF ÚNICO
        // ====================
        
        $pdf = Pdf::loadView('reportes.pedidos.completo', [
            // Datos generales
            'pedidos' => $pedidos,
            'detallePedidos' => $detallePedidos,
            'fechaInicio' => $request->fecha_inicio,
            'fechaFin' => $request->fecha_fin,
            'filtros' => $request->all(),
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'clienteSeleccionado' => $request->filled('cliente_id') ? 
                Cliente::find($request->cliente_id) : null,
            'empleadoSeleccionado' => $request->filled('empleado_id') ? 
                Empleado::find($request->empleado_id) : null,
            
            // Secciones específicas
            'productosMasSolicitados' => $productosMasSolicitados,
            'productosValorPedidos' => $productosValorPedidos,
            'pedidosPorCliente' => $pedidosPorCliente,
            'pedidosPorEmpleado' => $pedidosPorEmpleado,
            'pedidosPorEstado' => $pedidosPorEstado,
            'pedidosPorPrioridad' => $pedidosPorPrioridad,
            
            // Estadísticas
            'totalPedidos' => $totalPedidos,
            'totalIngresos' => $totalIngresos,
            'pedidoPromedio' => $pedidoPromedio,
            'pedidoMasAlto' => $pedidoMasAlto,
            'productosUnicos' => $productosUnicos,
            
            // Resumen ejecutivo
            'resumenEjecutivo' => $resumenEjecutivo,
        ]);
        
        // Configurar PDF
        $pdf->setPaper('A4', 'portrait');
        
        // Nombre del archivo
        $nombreArchivo = 'reporte_pedidos_' . date('Y-m-d') . '.pdf';
        
        // Mostrar en navegador
        return $pdf->stream($nombreArchivo);
    }
}