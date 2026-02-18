<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Empleado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteVentasController extends Controller
{
    // Vista principal con filtros
    public function index()
    {
        $empleados = Empleado::all();
        
        // Estadísticas rápidas
        $estadisticas = [
            'ventas_hoy' => Venta::whereDate('Fecha', today())->count(),
            'ventas_mes' => Venta::whereMonth('Fecha', now()->month)
                                ->whereYear('Fecha', now()->year)
                                ->count(),
            'total_mes' => Venta::whereMonth('Fecha', now()->month)
                                ->whereYear('Fecha', now()->year)
                                ->sum('Total'),
            'venta_promedio' => Venta::whereMonth('Fecha', now()->month)
                                    ->whereYear('Fecha', now()->year)
                                    ->avg('Total'),
        ];
        
        return view('reportes.ventas.index', compact('empleados', 'estadisticas'));
    }
    
    // GENERAR UN SOLO PDF COMPLETO DE VENTAS
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
        
        // 1. Ventas filtradas
        $query = Venta::with(['empleado', 'detalleVentas.producto']);
        
        // Filtrar por fechas
        $query->whereDate('Fecha', '>=', $request->fecha_inicio)
              ->whereDate('Fecha', '<=', $request->fecha_fin);
        
        // Filtrar por empleado
        if ($request->filled('empleado_id')) {
            $query->where('Empleado_idEmpleado', $request->empleado_id);
        }
        
        // Ordenar
        $orden = $request->orden ?? 'fecha_desc';
        switch ($orden) {
            case 'fecha_asc':
                $query->orderBy('Fecha', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('Total', 'desc');
                break;
            case 'total_asc':
                $query->orderBy('Total', 'asc');
                break;
            default:
                $query->orderBy('Fecha', 'desc');
        }
        
        $ventas = $query->get();
        
        // 2. Detalle de ventas agregado
        $detalleVentas = DetalleVenta::with(['producto', 'venta.empleado'])
            ->whereHas('venta', function($q) use ($request) {
                $q->whereDate('Fecha', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha', '<=', $request->fecha_fin);
                
                if ($request->filled('empleado_id')) {
                    $q->where('Empleado_idEmpleado', $request->empleado_id);
                }
            })
            ->get();
        
        // 3. Productos más vendidos
        $productosMasVendidos = DetalleVenta::with('producto')
            ->select(
                'Producto',
                DB::raw('SUM(Cantidad) as total_vendido'),
                DB::raw('COUNT(DISTINCT Venta) as veces_vendido'),
                DB::raw('SUM(Cantidad * (SELECT Precio FROM productos WHERE id = detalle_venta.Producto)) as total_ingresos')
            )
            ->whereHas('venta', function($q) use ($request) {
                $q->whereDate('Fecha', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha', '<=', $request->fecha_fin);
            })
            ->groupBy('Producto')
            ->orderBy('total_vendido', 'desc')
            ->take(15)
            ->get()
            ->map(function($item) {
                $item->precio_promedio = $item->total_vendido > 0 ? 
                    $item->total_ingresos / $item->total_vendido : 0;
                return $item;
            });
        
        // 4. Ventas por empleado
        $ventasPorEmpleado = Venta::with('empleado')
            ->select(
                'Empleado_idEmpleado',
                DB::raw('COUNT(*) as total_ventas'),
                DB::raw('SUM(Total) as total_ingresos'),
                DB::raw('AVG(Total) as promedio_venta')
            )
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->orderBy('total_ingresos', 'desc')
            ->get();
        
        // 5. Ventas por día/semana/mes
        $ventasPorPeriodo = Venta::select(
                DB::raw('DATE(Fecha) as fecha'),
                DB::raw('COUNT(*) as cantidad_ventas'),
                DB::raw('SUM(Total) as total_dia'),
                DB::raw('AVG(Total) as promedio_dia')
            )
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy(DB::raw('DATE(Fecha)'))
            ->orderBy('fecha', 'asc')
            ->get();
        
        // 6. Estadísticas generales
        $totalVentas = $ventas->count();
        $totalIngresos = $ventas->sum('Total');
        $ventaPromedio = $totalVentas > 0 ? $totalIngresos / $totalVentas : 0;
        
        // Mejor venta del período
        $mejorVenta = $ventas->sortByDesc('Total')->first();
        
        // Ventas por hora (si tienes hora en la fecha)
        $ventasPorHora = [];
        for ($hora = 0; $hora < 24; $hora++) {
            $ventasPorHora[$hora] = $ventas->filter(function($venta) use ($hora) {
                return Carbon::parse($venta->Fecha)->hour == $hora;
            })->count();
        }
        
        // 7. Resumen ejecutivo
        $resumenEjecutivo = [
            'total_ventas' => $totalVentas,
            'total_ingresos' => $totalIngresos,
            'venta_promedio' => $ventaPromedio,
            'mejor_dia' => $ventasPorPeriodo->sortByDesc('total_dia')->first(),
            'empleado_top' => $ventasPorEmpleado->first(),
            'producto_top' => $productosMasVendidos->first(),
            'dias_periodo' => Carbon::parse($request->fecha_inicio)
                ->diffInDays(Carbon::parse($request->fecha_fin)) + 1,
            'ventas_por_dia' => $totalVentas > 0 ? 
                $totalVentas / (Carbon::parse($request->fecha_inicio)
                ->diffInDays(Carbon::parse($request->fecha_fin)) + 1) : 0,
        ];
        
        // ====================
        // GENERAR PDF ÚNICO
        // ====================
        
        $pdf = Pdf::loadView('reportes.ventas.completo', [
            // Datos generales
            'ventas' => $ventas,
            'detalleVentas' => $detalleVentas,
            'fechaInicio' => $request->fecha_inicio,
            'fechaFin' => $request->fecha_fin,
            'filtros' => $request->all(),
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'empleadoSeleccionado' => $request->filled('empleado_id') ? 
                Empleado::find($request->empleado_id) : null,
            
            // Secciones específicas
            'productosMasVendidos' => $productosMasVendidos,
            'ventasPorEmpleado' => $ventasPorEmpleado,
            'ventasPorPeriodo' => $ventasPorPeriodo,
            'ventasPorHora' => $ventasPorHora,
            
            // Estadísticas
            'totalVentas' => $totalVentas,
            'totalIngresos' => $totalIngresos,
            'ventaPromedio' => $ventaPromedio,
            'mejorVenta' => $mejorVenta,
            
            // Resumen ejecutivo
            'resumenEjecutivo' => $resumenEjecutivo,
        ]);
        
        // Configurar PDF
        $pdf->setPaper('A4', 'portrait');
        
        // Nombre del archivo
        $nombreArchivo = 'reporte_ventas_' . date('Y-m-d') . '.pdf';
        
        // Mostrar en navegador
        return $pdf->stream($nombreArchivo);
    }
}