<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\DetalleVenta;
use App\Models\DetallePedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteRentabilidadController extends Controller
{
    // Vista principal con filtros
    public function index()
    {
        $categorias = \App\Models\Categoria::all();
        
        // Estadísticas rápidas de rentabilidad
        $estadisticas = [
            'total_productos' => Producto::count(),
            'productos_con_ventas' => Producto::has('detalleVentas')->count(),
            'productos_sin_ventas' => Producto::doesntHave('detalleVentas')->count(),
            'productos_con_pedidos' => Producto::has('detallePedidos')->count(),
        ];
        
        return view('reportes.rentabilidad.index', compact('categorias', 'estadisticas'));
    }
    
    // GENERAR UN SOLO PDF COMPLETO DE RENTABILIDAD
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
        
        // 1. Productos con sus ventas y pedidos en el período
        $query = Producto::with(['categoria', 'detalleVentas.venta', 'detallePedidos.pedido']);
        
        // Filtrar por categoría
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        $productos = $query->get();
        
        // 2. Calcular rentabilidad para cada producto
        $productosConRentabilidad = $productos->map(function($producto) use ($request) {
            // Ventas del período
            $ventasPeriodo = $producto->detalleVentas->filter(function($detalle) use ($request) {
                return $detalle->venta && 
                       $detalle->venta->Fecha >= $request->fecha_inicio && 
                       $detalle->venta->Fecha <= $request->fecha_fin;
            });
            
            $cantidadVendida = $ventasPeriodo->sum('Cantidad');
            $ingresosVentas = $ventasPeriodo->sum(function($detalle) {
                return $detalle->Cantidad * $producto->Precio;
            });
            
            // Pedidos del período
            $pedidosPeriodo = $producto->detallePedidos->filter(function($detalle) use ($request) {
                return $detalle->pedido && 
                       $detalle->pedido->Fecha_entrega >= $request->fecha_inicio && 
                       $detalle->pedido->Fecha_entrega <= $request->fecha_fin;
            });
            
            $cantidadPedida = $pedidosPeriodo->sum('Cantidad');
            $ingresosPedidos = $pedidosPeriodo->sum(function($detalle) {
                return $detalle->Cantidad * $detalle->PrecioUnitario;
            });
            
            // Suponiendo que tienes un campo 'costo' en Producto
            // Si no lo tienes, puedes usar un porcentaje fijo o calcularlo de otra manera
            $costoUnitario = $producto->costo ?? ($producto->Precio * 0.6); // 60% del precio si no hay costo
            
            // Cálculos de rentabilidad
            $costoTotalVentas = $cantidadVendida * $costoUnitario;
            $costoTotalPedidos = $cantidadPedida * $costoUnitario;
            
            $gananciaVentas = $ingresosVentas - $costoTotalVentas;
            $gananciaPedidos = $ingresosPedidos - $costoTotalPedidos;
            
            $gananciaTotal = $gananciaVentas + $gananciaPedidos;
            $ingresosTotales = $ingresosVentas + $ingresosPedidos;
            
            // Margen de ganancia porcentual
            $margenGanancia = $ingresosTotales > 0 ? ($gananciaTotal / $ingresosTotales) * 100 : 0;
            
            // Retorno sobre inversión (ROI)
            $inversionTotal = ($cantidadVendida + $cantidadPedida) * $costoUnitario;
            $roi = $inversionTotal > 0 ? ($gananciaTotal / $inversionTotal) * 100 : 0;
            
            // Rotación de inventario
            $rotacionInventario = $producto->Cantidad > 0 ? 
                ($cantidadVendida + $cantidadPedida) / $producto->Cantidad : 0;
            
            return (object) [
                'id' => $producto->id,
                'nombre' => $producto->Nombre,
                'categoria' => $producto->categoria->nombre ?? 'Sin categoría',
                'precio_venta' => $producto->Precio,
                'costo_unitario' => $costoUnitario,
                'stock_actual' => $producto->Cantidad,
                'stock_minimo' => $producto->Cantidad_minima,
                
                // Ventas
                'cantidad_vendida' => $cantidadVendida,
                'ingresos_ventas' => $ingresosVentas,
                'costo_ventas' => $costoTotalVentas,
                'ganancia_ventas' => $gananciaVentas,
                
                // Pedidos
                'cantidad_pedida' => $cantidadPedida,
                'ingresos_pedidos' => $ingresosPedidos,
                'costo_pedidos' => $costoTotalPedidos,
                'ganancia_pedidos' => $gananciaPedidos,
                
                // Totales
                'cantidad_total' => $cantidadVendida + $cantidadPedida,
                'ingresos_totales' => $ingresosTotales,
                'costo_total' => $costoTotalVentas + $costoTotalPedidos,
                'ganancia_total' => $gananciaTotal,
                
                // Indicadores
                'margen_ganancia' => $margenGanancia,
                'roi' => $roi,
                'rotacion_inventario' => $rotacionInventario,
                'rentabilidad_categoria' => $this->categorizarRentabilidad($margenGanancia),
            ];
        });
        
        // 3. Productos más rentables (por margen de ganancia)
        $productosMasRentables = $productosConRentabilidad
            ->where('margen_ganancia', '>', 0)
            ->sortByDesc('margen_ganancia')
            ->take(15);
        
        // 4. Productos menos rentables (pérdidas o bajo margen)
        $productosMenosRentables = $productosConRentabilidad
            ->sortBy('margen_ganancia')
            ->take(15);
        
        // 5. Productos por ROI (Retorno sobre inversión)
        $productosPorROI = $productosConRentabilidad
            ->where('roi', '>', 0)
            ->sortByDesc('roi')
            ->take(10);
        
        // 6. Productos por rotación (más vendidos/pedidos)
        $productosPorRotacion = $productosConRentabilidad
            ->where('cantidad_total', '>', 0)
            ->sortByDesc('cantidad_total')
            ->take(10);
        
        // 7. Rentabilidad por categoría
        $rentabilidadPorCategoria = $productosConRentabilidad
            ->groupBy('categoria')
            ->map(function($productosCategoria) {
                return (object) [
                    'categoria' => $productosCategoria->first()->categoria,
                    'cantidad_productos' => $productosCategoria->count(),
                    'ingresos_totales' => $productosCategoria->sum('ingresos_totales'),
                    'ganancia_total' => $productosCategoria->sum('ganancia_total'),
                    'margen_promedio' => $productosCategoria->avg('margen_ganancia'),
                    'productos_alta_rentabilidad' => $productosCategoria->where('rentabilidad_categoria', 'Alta')->count(),
                    'productos_media_rentabilidad' => $productosCategoria->where('rentabilidad_categoria', 'Media')->count(),
                    'productos_baja_rentabilidad' => $productosCategoria->where('rentabilidad_categoria', 'Baja')->count(),
                ];
            })
            ->sortByDesc('margen_promedio');
        
        // 8. Estadísticas generales
        $totalIngresos = $productosConRentabilidad->sum('ingresos_totales');
        $totalGanancia = $productosConRentabilidad->sum('ganancia_total');
        $totalCosto = $productosConRentabilidad->sum('costo_total');
        $margenGeneral = $totalIngresos > 0 ? ($totalGanancia / $totalIngresos) * 100 : 0;
        
        // Productos con pérdidas
        $productosConPerdidas = $productosConRentabilidad->where('ganancia_total', '<', 0);
        
        // Productos sin movimiento
        $productosSinMovimiento = $productosConRentabilidad->where('cantidad_total', 0);
        
        // 9. Resumen ejecutivo
        $resumenEjecutivo = [
            'total_productos_analizados' => $productosConRentabilidad->count(),
            'total_ingresos' => $totalIngresos,
            'total_ganancia' => $totalGanancia,
            'total_costo' => $totalCosto,
            'margen_general' => $margenGeneral,
            'productos_alta_rentabilidad' => $productosConRentabilidad->where('rentabilidad_categoria', 'Alta')->count(),
            'productos_media_rentabilidad' => $productosConRentabilidad->where('rentabilidad_categoria', 'Media')->count(),
            'productos_baja_rentabilidad' => $productosConRentabilidad->where('rentabilidad_categoria', 'Baja')->count(),
            'productos_con_perdidas' => $productosConPerdidas->count(),
            'valor_perdidas' => $productosConPerdidas->sum('ganancia_total'),
            'productos_sin_movimiento' => $productosSinMovimiento->count(),
            'valor_inventario_inmovilizado' => $productosSinMovimiento->sum(function($p) {
                return $p->stock_actual * $p->costo_unitario;
            }),
            'producto_mas_rentable' => $productosMasRentables->first(),
            'producto_menos_rentable' => $productosMenosRentables->first(),
            'categoria_mas_rentable' => $rentabilidadPorCategoria->first(),
        ];
        
        // 10. Recomendaciones basadas en análisis
        $recomendaciones = $this->generarRecomendaciones($productosConRentabilidad, $resumenEjecutivo);
        
        // ====================
        // GENERAR PDF ÚNICO
        // ====================
        
        $pdf = Pdf::loadView('reportes.rentabilidad.completo', [
            // Datos generales
            'productosConRentabilidad' => $productosConRentabilidad,
            'fechaInicio' => $request->fecha_inicio,
            'fechaFin' => $request->fecha_fin,
            'filtros' => $request->all(),
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'categoriaSeleccionada' => $request->filled('categoria_id') ? 
                \App\Models\Categoria::find($request->categoria_id) : null,
            
            // Secciones específicas
            'productosMasRentables' => $productosMasRentables,
            'productosMenosRentables' => $productosMenosRentables,
            'productosPorROI' => $productosPorROI,
            'productosPorRotacion' => $productosPorRotacion,
            'rentabilidadPorCategoria' => $rentabilidadPorCategoria,
            
            // Estadísticas
            'totalIngresos' => $totalIngresos,
            'totalGanancia' => $totalGanancia,
            'totalCosto' => $totalCosto,
            'margenGeneral' => $margenGeneral,
            'productosConPerdidas' => $productosConPerdidas,
            'productosSinMovimiento' => $productosSinMovimiento,
            
            // Resumen ejecutivo
            'resumenEjecutivo' => $resumenEjecutivo,
            'recomendaciones' => $recomendaciones,
        ]);
        
        // Configurar PDF
        $pdf->setPaper('A4', 'landscape');
        
        // Nombre del archivo
        $nombreArchivo = 'reporte_rentabilidad_' . date('Y-m-d') . '.pdf';
        
        // Mostrar en navegador
        return $pdf->stream($nombreArchivo);
    }
    
    // Método para categorizar rentabilidad
    private function categorizarRentabilidad($margen)
    {
        if ($margen > 40) return 'Alta';
        if ($margen > 20) return 'Media';
        if ($margen > 0) return 'Baja';
        if ($margen == 0) return 'Sin ganancia';
        return 'Pérdida';
    }
    
    // Método para generar recomendaciones automáticas
    private function generarRecomendaciones($productos, $resumen)
    {
        $recomendaciones = [];
        
        // Recomendaciones basadas en productos con pérdidas
        if ($resumen['productos_con_perdidas'] > 0) {
            $recomendaciones[] = [
                'tipo' => '⚠️',
                'titulo' => 'Productos con pérdidas',
                'descripcion' => $resumen['productos_con_perdidas'] . ' productos generan pérdidas. Revisar precios o costos.',
                'accion' => 'Revisar estructura de costos y precios de venta'
            ];
        }
        
        // Recomendaciones basadas en productos sin movimiento
        if ($resumen['productos_sin_movimiento'] > 0) {
            $recomendaciones[] = [
                'tipo' => '📦',
                'titulo' => 'Inventario inmovilizado',
                'descripcion' => $resumen['productos_sin_movimiento'] . ' productos sin ventas ni pedidos.',
                'accion' => 'Considerar promociones o descuentos para liberar inventario'
            ];
        }
        
        // Recomendaciones basadas en margen general
        if ($resumen['margen_general'] < 20) {
            $recomendaciones[] = [
                'tipo' => '📉',
                'titulo' => 'Margen general bajo',
                'descripcion' => 'Margen de ganancia general del ' . number_format($resumen['margen_general'], 1) . '%.',
                'accion' => 'Focalizar en productos de alta rentabilidad'
            ];
        }
        
        // Recomendaciones basadas en productos de alta rentabilidad
        if ($resumen['productos_alta_rentabilidad'] > 0) {
            $recomendaciones[] = [
                'tipo' => '🚀',
                'titulo' => 'Productos estrellas',
                'descripcion' => $resumen['productos_alta_rentabilidad'] . ' productos con margen superior al 40%.',
                'accion' => 'Promover estos productos y aumentar su disponibilidad'
            ];
        }
        
        return $recomendaciones;
    }
}