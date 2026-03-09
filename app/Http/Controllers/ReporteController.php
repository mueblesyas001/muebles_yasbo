<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Compra;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\DetalleCompra;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Helpers\GraficaHelper;

class ReporteController extends Controller
{
    /**
     * Vista principal del dashboard de reportes
     */
    public function index(){
        $totalProductos = Producto::count();
        
        $stockField = $this->getStockField();
        $totalStock = Producto::sum($stockField);
        
        // Calcular valor de inventario según los campos disponibles
        $valorInventario = $this->calculateInventoryValue();
        
        // Ventas del mes
        $ventasMes = Venta::whereMonth('Fecha', Carbon::now()->month)
            ->whereYear('Fecha', Carbon::now()->year)
            ->sum('Total');
        
        // Compras del mes
        $comprasMes = Compra::whereMonth('Fecha_compra', Carbon::now()->month)
            ->whereYear('Fecha_compra', Carbon::now()->year)
            ->sum('Total');
        
        // Ganancia aproximada
        $gananciaMes = $ventasMes - $comprasMes;
        
        // Pedidos pendientes
        $pedidosPendientes = Pedido::where('Estado', 'En proceso')->count();
        
        // Obtener datos para los filtros de reportes
        $empleados = Empleado::all();
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
                
        return view('reportes.index', compact(
            'totalProductos',
            'totalStock', 
            'valorInventario',
            'ventasMes',
            'comprasMes',
            'gananciaMes',
            'pedidosPendientes',
            'empleados',
            'categorias',
            'proveedores'
        ));
    }

    /**
     * ===========================================
     * 1. REPORTE DE VENTAS (CON GRÁFICAS GD)
     * ===========================================
     */
    public function generarReporteVentas(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // Verificar si hay ventas en el período seleccionado
        $totalVentasPeriodo = Venta::whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->count();
        
        // Si no hay ventas, mostrar mensaje amigable
        if ($totalVentasPeriodo == 0) {
            return back()->with('warning', 'No hay ventas en el período seleccionado. Por favor, elige otro rango de fechas.');
        }
        
        // 1. OBTENER VENTAS
        $ventas = Venta::with('empleado')
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->orderBy('Fecha', 'desc')
            ->get();
        
        // 2. OBTENER DETALLES DE VENTAS
        $detalleVentas = DetalleVenta::with(['producto', 'venta.empleado'])
            ->whereHas('venta', function($q) use ($request) {
                $q->whereDate('Fecha', '>=', $request->fecha_inicio)
                ->whereDate('Fecha', '<=', $request->fecha_fin);
            })
            ->get();
        
        // 3. ESTADÍSTICAS BÁSICAS
        $totalVentas = $ventas->count();
        $totalIngresos = $ventas->sum('Total');
        $ventaPromedio = $totalVentas > 0 ? $totalIngresos / $totalVentas : 0;
        
        // 4. PRODUCTOS MÁS VENDIDOS
        $productosMasVendidos = collect();
        
        if ($detalleVentas->isNotEmpty()) {
            $productosAgrupados = $detalleVentas->groupBy('Producto');
            
            foreach ($productosAgrupados as $productoId => $detalles) {
                $producto = Producto::find($productoId);
                if ($producto) {
                    $totalCantidad = $detalles->sum('Cantidad');
                    $totalIngresosProducto = $totalCantidad * ($producto->Precio ?? 0);
                    
                    $item = new \stdClass();
                    $item->producto = $producto;
                    $item->total_vendido = $totalCantidad;
                    $item->total_ingresos = $totalIngresosProducto;
                    
                    $productosMasVendidos->push($item);
                }
            }
            
            $productosMasVendidos = $productosMasVendidos->sortByDesc('total_vendido')->take(10);
        }
        
        // 5. VENTAS POR EMPLEADO
        $ventasPorEmpleado = Venta::with('empleado')
            ->select('Empleado_idEmpleado', DB::raw('SUM(Total) as total'))
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->get();
        
        // 6. VENTAS POR DÍA
        $ventasPorDia = Venta::select(
                DB::raw('DATE(Fecha) as fecha'),
                DB::raw('SUM(Total) as total')
            )
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy(DB::raw('DATE(Fecha)'))
            ->orderBy('fecha')
            ->get();
        
        // 7. PREPARAR DATOS PARA GRÁFICAS CON GD
        $datosChartDiario = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($ventasPorDia as $dia) {
            $datosChartDiario['labels'][] = date('d/m', strtotime($dia->fecha));
            $datosChartDiario['data'][] = floatval($dia->total);
        }
        
        $datosChartEmpleados = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($ventasPorEmpleado as $venta) {
            $nombre = 'Sin vendedor';
            if($venta->empleado) {
                $nombre = trim($venta->empleado->Nombre . ' ' . ($venta->empleado->ApPaterno ?? ''));
            }
            $datosChartEmpleados['labels'][] = $nombre;
            $datosChartEmpleados['data'][] = floatval($venta->total);
        }
        
        $datosChartProductos = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($productosMasVendidos->take(5) as $item) {
            $datosChartProductos['labels'][] = $item->producto->Nombre ?? 'Producto';
            $datosChartProductos['data'][] = intval($item->total_vendido);
        }
        
        // 8. GENERAR GRÁFICAS CON GD (CON VALIDACIÓN)
        $graficas = [];
        
        if (!empty($datosChartDiario['labels']) && array_sum($datosChartDiario['data']) > 0) {
            try {
                $graficas['diaria'] = GraficaHelper::generarLinea($datosChartDiario, 'Ventas Diarias');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de ventas diarias: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartEmpleados['labels']) && array_sum($datosChartEmpleados['data']) > 0) {
            try {
                $graficas['empleados'] = GraficaHelper::generarBarra($datosChartEmpleados, 'Ventas por Vendedor');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de ventas por empleado: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartProductos['labels']) && array_sum($datosChartProductos['data']) > 0) {
            try {
                $graficas['productos'] = GraficaHelper::generarBarra($datosChartProductos, 'Productos más vendidos');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de productos más vendidos: ' . $e->getMessage());
            }
        }
        
        // 9. RESUMEN EJECUTIVO
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasPeriodo = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        
        $resumenEjecutivo = [
            'dias_periodo' => $diasPeriodo,
            'dias_con_ventas' => $ventasPorDia->count(),
            'dias_sin_ventas' => $diasPeriodo - $ventasPorDia->count(),
        ];
        
        // 10. GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.ventas', [
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'totalVentas' => $totalVentas,
                'totalIngresos' => $totalIngresos,
                'ventaPromedio' => $ventaPromedio,
                'resumenEjecutivo' => $resumenEjecutivo,
                'productosMasVendidos' => $productosMasVendidos,
                'ventasPorEmpleado' => $ventasPorEmpleado,
                'ventasPorDia' => $ventasPorDia,
                'graficas' => $graficas,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_ventas_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('ERROR AL GENERAR PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * ===========================================
     * 2. REPORTE DE COMPRAS (CON GRÁFICAS GD)
     * ===========================================
     */
    public function generarReporteCompras(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // Verificar si hay compras en el período seleccionado
        $totalComprasPeriodo = Compra::whereDate('Fecha_compra', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_compra', '<=', $request->fecha_fin)
            ->count();
        
        // Si no hay compras, mostrar mensaje amigable
        if ($totalComprasPeriodo == 0) {
            return back()->with('warning', 'No hay compras en el período seleccionado. Por favor, elige otro rango de fechas.');
        }
        
        // OBTENER COMPRAS
        $query = Compra::with(['proveedor', 'detalleCompras.producto']);
        
        $query->whereDate('Fecha_compra', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_compra', '<=', $request->fecha_fin);
        
        if ($request->filled('proveedor_id')) {
            $query->where('Proveedor_idProveedor', $request->proveedor_id);
        }
        
        $orden = $request->orden ?? 'fecha_desc';
        if ($orden == 'fecha_desc') $query->orderBy('Fecha_compra', 'desc');
        elseif ($orden == 'fecha_asc') $query->orderBy('Fecha_compra', 'asc');
        elseif ($orden == 'total_desc') $query->orderBy('Total', 'desc');
        elseif ($orden == 'total_asc') $query->orderBy('Total', 'asc');
        
        $compras = $query->get();
        
        // DETALLE DE COMPRAS
        $detalleCompras = DetalleCompra::with(['producto', 'compra.proveedor'])
            ->whereHas('compra', function($q) use ($request) {
                $q->whereDate('Fecha_compra', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha_compra', '<=', $request->fecha_fin);
                
                if ($request->filled('proveedor_id')) {
                    $q->where('Proveedor_idProveedor', $request->proveedor_id);
                }
            })
            ->get();
        
        foreach ($detalleCompras as $detalle) {
            $detalle->subtotal = $detalle->Cantidad * $detalle->Precio_unitario;
        }
        
        // ESTADÍSTICAS
        $totalCompras = $compras->count();
        $totalEgresos = $compras->sum('Total');
        $compraPromedio = $totalCompras > 0 ? $totalEgresos / $totalCompras : 0;
        $totalProveedores = $compras->groupBy('Proveedor_idProveedor')->count();
        
        // Productos más comprados
        $productosMasComprados = DetalleCompra::with('producto')
            ->select(
                'Producto', 
                DB::raw('SUM(Cantidad) as total_comprado'), 
                DB::raw('COUNT(DISTINCT Compra_idCompra) as veces_comprado'),
                DB::raw('SUM(Cantidad * Precio_unitario) as costo_total')
            )
            ->whereHas('compra', function($q) use ($request) {
                $q->whereDate('Fecha_compra', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha_compra', '<=', $request->fecha_fin);
                
                if ($request->filled('proveedor_id')) {
                    $q->where('Proveedor_idProveedor', $request->proveedor_id);
                }
            })
            ->groupBy('Producto')
            ->orderBy('total_comprado', 'desc')
            ->take(10)
            ->get();
        
        foreach ($productosMasComprados as $item) {
            $item->total_costo = $item->costo_total ?? 0;
        }
        
        // Compras por proveedor
        $comprasPorProveedor = Compra::with('proveedor')
            ->select('Proveedor_idProveedor', 
                DB::raw('COUNT(*) as total_compras'),
                DB::raw('SUM(Total) as total_egresos'),
                DB::raw('AVG(Total) as promedio_compra')
            )
            ->whereDate('Fecha_compra', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_compra', '<=', $request->fecha_fin)
            ->when($request->filled('proveedor_id'), function($q) use ($request) {
                $q->where('Proveedor_idProveedor', $request->proveedor_id);
            })
            ->groupBy('Proveedor_idProveedor')
            ->orderBy('total_egresos', 'desc')
            ->get();
        
        // Compras por día
        $comprasPorDia = Compra::select(
                DB::raw('DATE(Fecha_compra) as fecha'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(Total) as total')
            )
            ->whereDate('Fecha_compra', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_compra', '<=', $request->fecha_fin)
            ->when($request->filled('proveedor_id'), function($q) use ($request) {
                $q->where('Proveedor_idProveedor', $request->proveedor_id);
            })
            ->groupBy(DB::raw('DATE(Fecha_compra)'))
            ->orderBy('fecha', 'asc')
            ->get();
        
        // PREPARAR DATOS PARA GRÁFICAS GD
        $datosChartProveedores = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($comprasPorProveedor as $item) {
            $nombreCompleto = $item->proveedor ? $item->proveedor->nombre_completo : 'Proveedor';
            $datosChartProveedores['labels'][] = $nombreCompleto;
            $datosChartProveedores['data'][] = floatval($item->total_egresos);
        }
        
        $datosChartProductosComprados = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($productosMasComprados->take(5) as $item) {
            $nombreProducto = 'Producto';
            if ($item->producto) {
                $nombreProducto = $item->producto->Nombre ?? $item->producto->nombre ?? 'Producto';
            }
            $datosChartProductosComprados['labels'][] = $nombreProducto;
            $datosChartProductosComprados['data'][] = intval($item->total_comprado);
        }
        
        $datosChartComprasDiarias = [
            'labels' => [],
            'data' => []
        ];
        
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasTotales = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        $diasAMostrar = min($diasTotales, 15);
        
        for ($i = 0; $i < $diasAMostrar; $i++) {
            $fechaActual = $fechaInicioObj->copy()->addDays($i);
            $fechaStr = $fechaActual->format('Y-m-d');
            $compraDia = $comprasPorDia->firstWhere('fecha', $fechaStr);
            $datosChartComprasDiarias['labels'][] = $fechaActual->format('d/m');
            $datosChartComprasDiarias['data'][] = floatval($compraDia->total ?? 0);
        }
        
        // GENERAR GRÁFICAS CON GD (CON VALIDACIÓN)
        $graficas = [];
        
        if (!empty($datosChartProveedores['labels']) && array_sum($datosChartProveedores['data']) > 0) {
            try {
                $graficas['proveedores'] = GraficaHelper::generarBarra($datosChartProveedores, 'Compras por Proveedor');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de compras por proveedor: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartProductosComprados['labels']) && array_sum($datosChartProductosComprados['data']) > 0) {
            try {
                $graficas['productos'] = GraficaHelper::generarBarra($datosChartProductosComprados, 'Productos más comprados');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de productos más comprados: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartComprasDiarias['labels']) && array_sum($datosChartComprasDiarias['data']) > 0) {
            try {
                $graficas['diaria'] = GraficaHelper::generarLinea($datosChartComprasDiarias, 'Compras Diarias');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de compras diarias: ' . $e->getMessage());
            }
        }
        
        $diaPico = $comprasPorDia->sortByDesc('total')->first();
        $proveedorPrincipal = $comprasPorProveedor->first();
        if ($proveedorPrincipal && $proveedorPrincipal->proveedor) {
            $proveedorPrincipal->nombre_completo = $proveedorPrincipal->proveedor->nombre_completo;
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.compras', [
                'compras' => $compras,
                'detalleCompras' => $detalleCompras,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'proveedorSeleccionado' => $request->filled('proveedor_id') ? Proveedor::find($request->proveedor_id) : null,
                'totalCompras' => $totalCompras,
                'totalEgresos' => $totalEgresos,
                'compraPromedio' => $compraPromedio,
                'totalProveedores' => $totalProveedores,
                'productosMasComprados' => $productosMasComprados,
                'comprasPorProveedor' => $comprasPorProveedor,
                'comprasPorDia' => $comprasPorDia,
                'diaPico' => $diaPico,
                'proveedorPrincipal' => $proveedorPrincipal,
                'diasPeriodo' => $diasTotales,
                'graficas' => $graficas,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_compras_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte compras: ' . $e->getMessage());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }
    
    
    /**
     * ===========================================
     * 4. REPORTE DE INVENTARIO
     * ===========================================
     */
    public function generarReporteInventario(Request $request)
    {
        // OBTENER PRODUCTOS
        $query = Producto::query();
        $query->with('categoria');
        
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        if ($request->filled('nivel_stock')) {
            if ($request->nivel_stock == 'bajo') {
                $query->whereRaw("Cantidad <= Cantidad_minima AND Cantidad > 0");
            } elseif ($request->nivel_stock == 'medio') {
                $query->whereRaw("Cantidad > Cantidad_minima AND Cantidad <= 50");
            } elseif ($request->nivel_stock == 'alto') {
                $query->where('Cantidad', '>', 50);
            } elseif ($request->nivel_stock == 'agotado') {
                $query->where('Cantidad', '<=', 0);
            }
        }
        
        $ordenStock = $request->orden_stock ?? 'nombre';
        if ($ordenStock == 'nombre') {
            $query->orderBy('Nombre', 'asc');
        } elseif ($ordenStock == 'stock_bajo') {
            $query->orderBy('Cantidad', 'asc');
        } elseif ($ordenStock == 'stock_alto') {
            $query->orderBy('Cantidad', 'desc');
        } elseif ($ordenStock == 'valor') {
            $query->orderByDesc(DB::raw("Cantidad * Precio"));
        }
        
        $productos = $query->get();
        
        // Verificar si hay productos
        if ($productos->isEmpty()) {
            return back()->with('warning', 'No hay productos en el inventario con los filtros seleccionados.');
        }
        
        // CÁLCULOS
        $totalProductos = $productos->count();
        $totalStock = $productos->sum('Cantidad');
        $totalInventario = $productos->sum(function($producto) {
            return $producto->Cantidad * $producto->Precio;
        });
        
        $productosBajoStock = $productos->filter(function($producto) {
            return $producto->Cantidad <= $producto->Cantidad_minima && $producto->Cantidad > 0;
        });
        
        $productosSinStock = $productos->filter(function($producto) {
            return $producto->Cantidad <= 0;
        });
        
        $productosStockSaludable = $productos->filter(function($producto) {
            return $producto->Cantidad > $producto->Cantidad_minima && $producto->Cantidad > 0;
        });
        
        $productosMasValiosos = $productos->sortByDesc(function($producto) {
            return $producto->Cantidad * $producto->Precio;
        })->take(10);
        
        // INVENTARIO POR CATEGORÍA
        $inventarioPorCategoria = collect();
        $categorias = Categoria::all();
        
        foreach ($categorias as $categoria) {
            $productosCategoria = $productos->where('Categoria', $categoria->id);
            
            if ($productosCategoria->isNotEmpty()) {
                $totalProductosCat = $productosCategoria->count();
                $totalStockCat = $productosCategoria->sum('Cantidad');
                $valorTotalCat = $productosCategoria->sum(function($p) {
                    return $p->Cantidad * $p->Precio;
                });
                
                $item = new \stdClass();
                $item->categoria_id = $categoria->id;
                $item->categoria_nombre = $categoria->Nombre;
                $item->categoria = $categoria;
                $item->total_productos = $totalProductosCat;
                $item->total_stock = $totalStockCat;
                $item->valor_total = $valorTotalCat;
                
                $inventarioPorCategoria->push($item);
            }
        }
        
        $inventarioPorCategoria = $inventarioPorCategoria->sortByDesc('valor_total')->values();
        
        // Análisis ABC
        $productosABC = $this->analisisABC($productos);
        
        $porcentajeBajoStock = $totalProductos > 0 ? ($productosBajoStock->count() / $totalProductos) * 100 : 0;
        $porcentajeSinStock = $totalProductos > 0 ? ($productosSinStock->count() / $totalProductos) * 100 : 0;
        $porcentajeSaludable = $totalProductos > 0 ? ($productosStockSaludable->count() / $totalProductos) * 100 : 0;
        
        // PREPARAR DATOS PARA GRÁFICAS GD
        $datosChartNivelesStock = [
            'labels' => ['Stock Saludable', 'Stock Bajo', 'Sin Stock'],
            'data' => [
                $productosStockSaludable->count(),
                $productosBajoStock->count(),
                $productosSinStock->count()
            ]
        ];
        
        $datosChartCategorias = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($inventarioPorCategoria as $categoria) {
            $datosChartCategorias['labels'][] = $categoria->categoria_nombre;
            $datosChartCategorias['data'][] = floatval($categoria->valor_total);
        }
        
        $datosChartProductosValiosos = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($productosMasValiosos as $producto) {
            $datosChartProductosValiosos['labels'][] = $producto->Nombre;
            $datosChartProductosValiosos['data'][] = floatval($producto->Cantidad * $producto->Precio);
        }
        
        $datosChartABC = [
            'labels' => ['A (80%)', 'B (15%)', 'C (5%)'],
            'data' => [
                $productosABC['valor_A'] ?? 0,
                $productosABC['valor_B'] ?? 0,
                $productosABC['valor_C'] ?? 0
            ]
        ];
        
        // GENERAR GRÁFICAS CON GD (CON VALIDACIÓN)
        $graficas = [];
        
        if ($totalProductos > 0 && array_sum($datosChartNivelesStock['data']) > 0) {
            try {
                $graficas['niveles'] = GraficaHelper::generarBarra($datosChartNivelesStock, 'Niveles de Stock');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de niveles de stock: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartCategorias['labels']) && array_sum($datosChartCategorias['data']) > 0) {
            try {
                $graficas['categorias'] = GraficaHelper::generarBarra($datosChartCategorias, 'Valor por Categoría');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de valor por categoría: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartProductosValiosos['labels']) && array_sum($datosChartProductosValiosos['data']) > 0) {
            try {
                $graficas['valiosos'] = GraficaHelper::generarBarra($datosChartProductosValiosos, 'Productos más valiosos');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de productos más valiosos: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartABC['data']) && array_sum($datosChartABC['data']) > 0) {
            try {
                $graficas['abc'] = GraficaHelper::generarBarra($datosChartABC, 'Análisis ABC');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de análisis ABC: ' . $e->getMessage());
            }
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.inventario', [
                'productos' => $productos,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'filtros' => $request->all(),
                'categoriaSeleccionada' => $request->filled('categoria_id') ? Categoria::find($request->categoria_id) : null,
                'totalProductos' => $totalProductos,
                'totalStock' => $totalStock,
                'totalInventario' => $totalInventario,
                'productosBajoStock' => $productosBajoStock,
                'productosSinStock' => $productosSinStock,
                'productosStockSaludable' => $productosStockSaludable,
                'productosMasValiosos' => $productosMasValiosos,
                'inventarioPorCategoria' => $inventarioPorCategoria,
                'productosABC' => $productosABC,
                'porcentajeBajoStock' => $porcentajeBajoStock,
                'porcentajeSinStock' => $porcentajeSinStock,
                'porcentajeSaludable' => $porcentajeSaludable,
                'graficas' => $graficas,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_inventario_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte inventario: ' . $e->getMessage());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * ===========================================
     * 5. REPORTE DE RENTABILIDAD
     * ===========================================
     */
    public function generarReporteRentabilidad(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // Verificar si hay ventas en el período seleccionado
        $totalVentasPeriodo = Venta::whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->count();
        
        // Si no hay ventas, mostrar mensaje amigable
        if ($totalVentasPeriodo == 0) {
            return back()->with('warning', 'No hay ventas en el período seleccionado. Por favor, elige otro rango de fechas para calcular rentabilidad.');
        }
        
        // OBTENER PRODUCTOS
        $query = Producto::query();
        $query->with('categoria');
        
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        $productos = $query->orderBy('Nombre', 'asc')->get();
        
        // Calcular costo promedio
        $comprasProducto = DetalleCompra::select(
                'Producto',
                DB::raw('SUM(Cantidad) as total_comprado'),
                DB::raw('AVG(Precio_unitario) as precio_promedio')
            )
            ->whereHas('compra', function($q) use ($request) {
                $q->whereDate('Fecha_compra', '<=', $request->fecha_fin);
            })
            ->groupBy('Producto')
            ->get()
            ->keyBy('Producto');
        
        // Obtener ventas
        $ventasPorProducto = DetalleVenta::select('Producto', DB::raw('SUM(Cantidad) as total_vendido'))
            ->whereHas('venta', function($q) use ($request) {
                $q->whereDate('Fecha', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha', '<=', $request->fecha_fin);
            })
            ->groupBy('Producto')
            ->get()
            ->keyBy('Producto');
        
        // Procesar productos
        $productosProcesados = collect();
        $totalVentasGeneral = 0;
        $totalGananciaGeneral = 0;
        $totalCostoGeneral = 0;
        $productosConVentas = false;
        
        foreach ($productos as $producto) {
            $venta = $ventasPorProducto->get($producto->id);
            $totalVendido = $venta ? $venta->total_vendido : 0;
            $precioVenta = $producto->Precio ?? 0;
            $totalVentas = $totalVendido * $precioVenta;
            
            if ($totalVendido > 0) {
                $productosConVentas = true;
            }
            
            $compraInfo = $comprasProducto->get($producto->id);
            $costo = $compraInfo ? $compraInfo->precio_promedio : ($precioVenta * 0.7);
            $costoTotal = $totalVendido * $costo;
            $gananciaTotal = $totalVentas - $costoTotal;
            $margenGanancia = $totalVentas > 0 ? ($gananciaTotal / $totalVentas) * 100 : 0;
            
            $totalVentasGeneral += $totalVentas;
            $totalGananciaGeneral += $gananciaTotal;
            $totalCostoGeneral += $costoTotal;
            
            $productosProcesados->push([
                'nombre' => $producto->Nombre,
                'categoria' => $producto->categoria->Nombre ?? 'Sin categoría',
                'total_vendido' => $totalVendido,
                'total_ventas' => $totalVentas,
                'costo_total' => $costoTotal,
                'ganancia_total' => $gananciaTotal,
                'margen_ganancia' => round($margenGanancia, 2),
            ]);
        }
        
        // Si no hay productos con ventas, mostrar mensaje
        if (!$productosConVentas) {
            return back()->with('warning', 'No hay productos vendidos en el período seleccionado. Por favor, elige otro rango de fechas.');
        }
        
        $productosConVentas = $productosProcesados->where('total_vendido', '>', 0);
        $productosMasRentables = $productosConVentas->sortByDesc('margen_ganancia')->take(10);
        $productosMasGanancia = $productosConVentas->sortByDesc('ganancia_total')->take(10);
        
        // PREPARAR DATOS PARA GRÁFICAS GD
        $datosChartComparativa = [
            'labels' => ['Ventas', 'Costo', 'Ganancia'],
            'data' => [
                floatval($totalVentasGeneral),
                floatval($totalCostoGeneral),
                floatval($totalGananciaGeneral)
            ]
        ];
        
        $datosChartMargenes = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($productosMasRentables as $item) {
            $datosChartMargenes['labels'][] = $item['nombre'];
            $datosChartMargenes['data'][] = floatval($item['margen_ganancia']);
        }
        
        $datosChartGanancias = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($productosMasGanancia as $item) {
            $datosChartGanancias['labels'][] = $item['nombre'];
            $datosChartGanancias['data'][] = floatval($item['ganancia_total']);
        }
        
        // GENERAR GRÁFICAS CON GD (CON VALIDACIÓN)
        $graficas = [];
        
        if ($totalVentasGeneral > 0 && array_sum($datosChartComparativa['data']) > 0) {
            try {
                $graficas['comparativa'] = GraficaHelper::generarBarra($datosChartComparativa, 'Ventas vs Costo vs Ganancia');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica comparativa: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartMargenes['labels']) && array_sum($datosChartMargenes['data']) > 0) {
            try {
                $graficas['margenes'] = GraficaHelper::generarBarra($datosChartMargenes, 'Productos con mayor margen (%)');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de márgenes: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartGanancias['labels']) && array_sum($datosChartGanancias['data']) > 0) {
            try {
                $graficas['ganancias'] = GraficaHelper::generarBarra($datosChartGanancias, 'Productos con mayor ganancia ($)');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de ganancias: ' . $e->getMessage());
            }
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.rentabilidad', [
                'productos' => $productosProcesados,
                'productosMasRentables' => $productosMasRentables,
                'productosMasGanancia' => $productosMasGanancia,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'totalGanancia' => $totalGananciaGeneral,
                'totalVentas' => $totalVentasGeneral,
                'totalCosto' => $totalCostoGeneral,
                'margenPromedio' => $totalVentasGeneral > 0 ? round(($totalGananciaGeneral / $totalVentasGeneral) * 100, 2) : 0,
                'graficas' => $graficas,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_rentabilidad_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte rentabilidad: ' . $e->getMessage());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * ===========================================
     * 6. REPORTE DE VENTAS POR VENDEDOR
     * ===========================================
     */
    public function generarReporteVentasVendedor(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // Verificar si hay ventas en el período seleccionado
        $totalVentasPeriodo = Venta::whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->count();
        
        // Si no hay ventas, mostrar mensaje amigable
        if ($totalVentasPeriodo == 0) {
            return back()->with('warning', 'No hay ventas en el período seleccionado. Por favor, elige otro rango de fechas.');
        }
        
        $empleados = Empleado::all();
        $datosVendedores = collect();
        $hayVentas = false;
        
        foreach ($empleados as $empleado) {
            $ventas = Venta::where('Empleado_idEmpleado', $empleado->id)
                ->whereDate('Fecha', '>=', $request->fecha_inicio)
                ->whereDate('Fecha', '<=', $request->fecha_fin)
                ->get();
            
            if ($ventas->isEmpty() && !$request->filled('incluir_sin_ventas')) {
                continue;
            }
            
            if ($ventas->isNotEmpty()) {
                $hayVentas = true;
            }
            
            $detallesVentas = DetalleVenta::with('producto')
                ->whereHas('venta', function($q) use ($empleado, $request) {
                    $q->where('Empleado_idEmpleado', $empleado->id)
                      ->whereDate('Fecha', '>=', $request->fecha_inicio)
                      ->whereDate('Fecha', '<=', $request->fecha_fin);
                })
                ->get();
            
            $totalVentas = $ventas->sum('Total');
            $totalProductosVendidos = $detallesVentas->sum('Cantidad');
            $numVentas = $ventas->count();
            $ticketPromedio = $numVentas > 0 ? $totalVentas / $numVentas : 0;
            
            $datosVendedores->push([
                'nombre_completo' => trim($empleado->Nombre . ' ' . $empleado->ApPaterno . ' ' . $empleado->ApMaterno),
                'num_ventas' => $numVentas,
                'total_ventas' => $totalVentas,
                'total_productos' => $totalProductosVendidos,
                'ticket_promedio' => $ticketPromedio,
            ]);
        }
        
        // Si no hay vendedores con ventas, mostrar mensaje
        if (!$hayVentas && !$request->filled('incluir_sin_ventas')) {
            return back()->with('warning', 'No hay vendedores con ventas en el período seleccionado.');
        }
        
        $datosVendedores = $datosVendedores->sortByDesc('total_ventas')->values();
        
        $totalGeneralVentas = $datosVendedores->sum('total_ventas');
        $totalGeneralProductos = $datosVendedores->sum('total_productos');
        $promedioPorVendedor = $datosVendedores->count() > 0 ? $totalGeneralVentas / $datosVendedores->count() : 0;
        
        // PREPARAR DATOS PARA GRÁFICAS GD
        $datosChartVentasVendedor = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($datosVendedores as $item) {
            $datosChartVentasVendedor['labels'][] = $item['nombre_completo'];
            $datosChartVentasVendedor['data'][] = floatval($item['total_ventas']);
        }
        
        $datosChartProductosVendedor = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($datosVendedores as $item) {
            $datosChartProductosVendedor['labels'][] = $item['nombre_completo'];
            $datosChartProductosVendedor['data'][] = intval($item['total_productos']);
        }
        
        $datosChartTickets = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($datosVendedores as $item) {
            $datosChartTickets['labels'][] = $item['nombre_completo'];
            $datosChartTickets['data'][] = floatval($item['ticket_promedio']);
        }
        
        $datosChartNumVentas = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($datosVendedores as $item) {
            $datosChartNumVentas['labels'][] = $item['nombre_completo'];
            $datosChartNumVentas['data'][] = intval($item['num_ventas']);
        }
        
        // GENERAR GRÁFICAS CON GD (CON VALIDACIÓN)
        $graficas = [];
        
        if (!empty($datosChartVentasVendedor['labels']) && array_sum($datosChartVentasVendedor['data']) > 0) {
            try {
                $graficas['ventas'] = GraficaHelper::generarBarra($datosChartVentasVendedor, 'Ventas por Vendedor ($)');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de ventas por vendedor: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartProductosVendedor['labels']) && array_sum($datosChartProductosVendedor['data']) > 0) {
            try {
                $graficas['productos'] = GraficaHelper::generarBarra($datosChartProductosVendedor, 'Productos vendidos por Vendedor');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de productos por vendedor: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartTickets['labels']) && array_sum($datosChartTickets['data']) > 0) {
            try {
                $graficas['tickets'] = GraficaHelper::generarBarra($datosChartTickets, 'Ticket Promedio por Vendedor ($)');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de ticket promedio: ' . $e->getMessage());
            }
        }
        
        if (!empty($datosChartNumVentas['labels']) && array_sum($datosChartNumVentas['data']) > 0) {
            try {
                $graficas['num_ventas'] = GraficaHelper::generarBarra($datosChartNumVentas, 'Número de Ventas por Vendedor');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica de número de ventas: ' . $e->getMessage());
            }
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.ventas_vendedor', [
                'datosVendedores' => $datosVendedores,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'totalGeneralVentas' => $totalGeneralVentas,
                'totalGeneralProductos' => $totalGeneralProductos,
                'promedioPorVendedor' => $promedioPorVendedor,
                'totalVendedores' => $datosVendedores->count(),
                'graficas' => $graficas,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_vendedores_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte ventas vendedor: ' . $e->getMessage());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * ===========================================
     * 3. REPORTE DE PEDIDOS - VERSIÓN FINAL CORREGIDA
     * ===========================================
     */
    public function generarReportePedidos(Request $request){
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        $fechaColumn = 'Fecha_entrega';
        
        // Verificar si hay pedidos
        $totalPedidosPeriodo = Pedido::whereDate($fechaColumn, '>=', $request->fecha_inicio)
            ->whereDate($fechaColumn, '<=', $request->fecha_fin)
            ->count();
        
        if ($totalPedidosPeriodo == 0) {
            return back()->with('warning', 'No hay pedidos en el período seleccionado.');
        }
        
        // OBTENER PEDIDOS
        $query = Pedido::with(['cliente', 'empleado', 'detallePedidos.producto.categoria']);
        
        $query->whereDate($fechaColumn, '>=', $request->fecha_inicio)
            ->whereDate($fechaColumn, '<=', $request->fecha_fin);
        
        if ($request->filled('estado')) {
            $query->where('Estado', $request->estado);
        }
        
        if ($request->filled('empleado_id')) {
            $query->where('Empleado_idEmpleado', $request->empleado_id);
        }
        
        $orden = $request->orden ?? 'fecha_desc';
        if ($orden == 'fecha_desc') $query->orderBy($fechaColumn, 'desc');
        elseif ($orden == 'fecha_asc') $query->orderBy($fechaColumn, 'asc');
        elseif ($orden == 'estado') $query->orderBy('Estado')->orderBy($fechaColumn, 'desc');
        
        $pedidos = $query->get();
        
        // ===========================================
        // CORRECCIÓN - ESTADOS EN SINGULAR
        // ===========================================
        $totalPedidos = $pedidos->count();
        
        // Estados CORRECTOS según la BD:
        // "En proceso", "Completado", "Cancelado"
        $pedidosEnProceso = $pedidos->where('Estado', 'En proceso')->count();
        $pedidosCompletados = $pedidos->where('Estado', 'Completado')->count(); // SIN 's'
        $pedidosCancelados = $pedidos->where('Estado', 'Cancelado')->count();   // SIN 's'
        
        // Log para verificar
        \Log::info('=== CONTEO DE PEDIDOS ===');
        \Log::info('Total pedidos: ' . $totalPedidos);
        \Log::info('En proceso: ' . $pedidosEnProceso);
        \Log::info('Completado: ' . $pedidosCompletados);
        \Log::info('Cancelado: ' . $pedidosCancelados);
        
        // Verificar que la suma coincida
        $suma = $pedidosEnProceso + $pedidosCompletados + $pedidosCancelados;
        \Log::info('Suma total: ' . $suma);
        if ($suma != $totalPedidos) {
            \Log::warning('¡LA SUMA NO COINCIDE! Faltan ' . ($totalPedidos - $suma) . ' pedidos');
            
            // Mostrar los estados que no están siendo contados
            $estadosEncontrados = $pedidos->pluck('Estado')->unique();
            \Log::info('Estados encontrados en BD:', $estadosEncontrados->toArray());
        }
        
        $valorTotalPedidos = $pedidos->sum('Total');
        
        // Cliente con más pedidos
        $clienteTop = Pedido::with('cliente')
            ->select('Cliente_idCliente', DB::raw('COUNT(*) as total_pedidos'))
            ->whereDate($fechaColumn, '>=', $request->fecha_inicio)
            ->whereDate($fechaColumn, '<=', $request->fecha_fin)
            ->groupBy('Cliente_idCliente')
            ->orderBy('total_pedidos', 'desc')
            ->first();
        
        // PEDIDOS POR VENDEDOR
        $pedidosPorVendedor = collect();
        $vendedores = Empleado::all();
        
        foreach ($vendedores as $vendedor) {
            $pedidosVendedor = $pedidos->where('Empleado_idEmpleado', $vendedor->id);
            
            if ($pedidosVendedor->isNotEmpty()) {
                $enProceso = $pedidosVendedor->where('Estado', 'En proceso')->count();
                $completados = $pedidosVendedor->where('Estado', 'Completado')->count(); // SIN 's'
                $cancelados = $pedidosVendedor->where('Estado', 'Cancelado')->count();   // SIN 's'
                
                $item = new \stdClass();
                $item->empleado = $vendedor;
                $item->pedidos_en_proceso = $enProceso;
                $item->pedidos_completados = $completados;
                $item->pedidos_cancelados = $cancelados;
                
                $pedidosPorVendedor->push($item);
            }
        }
        
        $pedidosPorVendedor = $pedidosPorVendedor->sortByDesc(function($item) {
            return $item->pedidos_en_proceso + $item->pedidos_completados + $item->pedidos_cancelados;
        })->values();
        
        // PEDIDOS POR DÍA
        $pedidosPorDia = Pedido::select(
                DB::raw("DATE($fechaColumn) as fecha"),
                DB::raw('COUNT(*) as cantidad')
            )
            ->whereDate($fechaColumn, '>=', $request->fecha_inicio)
            ->whereDate($fechaColumn, '<=', $request->fecha_fin)
            ->groupBy(DB::raw("DATE($fechaColumn)"))
            ->orderBy('fecha', 'asc')
            ->get();
        
        // PRODUCTOS MÁS PEDIDOS
        $productosMasPedidos = DetallePedido::with('producto.categoria')
            ->select('Producto', DB::raw('SUM(Cantidad) as total_cantidad'))
            ->whereHas('pedido', function($q) use ($request, $fechaColumn) {
                $q->whereDate($fechaColumn, '>=', $request->fecha_inicio)
                ->whereDate($fechaColumn, '<=', $request->fecha_fin);
            })
            ->groupBy('Producto')
            ->orderBy('total_cantidad', 'desc')
            ->take(10)
            ->get();
        
        // PREPARAR LISTAS PARA LA VISTA
        $pedidosEnProcesoLista = $pedidos->where('Estado', 'En proceso');
        $pedidosCompletadosLista = $pedidos->where('Estado', 'Completado'); // SIN 's'
        $pedidosCanceladosLista = $pedidos->where('Estado', 'Cancelado');   // SIN 's'
        
        // Calcular porcentajes
        $pedidosEnProcesoPorcentaje = $totalPedidos > 0 ? ($pedidosEnProceso / $totalPedidos) * 100 : 0;
        $pedidosCompletadosPorcentaje = $totalPedidos > 0 ? ($pedidosCompletados / $totalPedidos) * 100 : 0;
        $pedidosCanceladosPorcentaje = $totalPedidos > 0 ? ($pedidosCancelados / $totalPedidos) * 100 : 0;
        
        // PREPARAR GRÁFICAS
        $datosChartEstados = [
            'labels' => ['En proceso', 'Completado', 'Cancelado'], // Ahora en singular
            'data' => [$pedidosEnProceso, $pedidosCompletados, $pedidosCancelados]
        ];
        
        $graficas = [];
        if ($totalPedidos > 0 && array_sum($datosChartEstados['data']) > 0) {
            try {
                $graficas['estados'] = GraficaHelper::generarBarra($datosChartEstados, 'Distribución de Estados');
            } catch (\Exception $e) {
                \Log::warning('No se pudo generar gráfica: ' . $e->getMessage());
            }
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.pedidos', [
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'totalPedidos' => $totalPedidos,
                'valorTotalPedidos' => $valorTotalPedidos,
                'pedidosEnProceso' => $pedidosEnProceso,
                'pedidosCompletados' => $pedidosCompletados,
                'pedidosCancelados' => $pedidosCancelados,
                'pedidosEnProcesoPorcentaje' => $pedidosEnProcesoPorcentaje,
                'pedidosCompletadosPorcentaje' => $pedidosCompletadosPorcentaje,
                'pedidosCanceladosPorcentaje' => $pedidosCanceladosPorcentaje,
                'clienteTop' => $clienteTop,
                'pedidosPorVendedor' => $pedidosPorVendedor,
                'productosMasPedidos' => $productosMasPedidos,
                'pedidosEnProcesoLista' => $pedidosEnProcesoLista,
                'pedidosCompletadosLista' => $pedidosCompletadosLista,
                'pedidosCanceladosLista' => $pedidosCanceladosLista,
                'graficas' => $graficas,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_pedidos_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte pedidos: ' . $e->getMessage());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * ===========================================
     * MÉTODOS AUXILIARES
     * ===========================================
     */

    private function analisisABC($productos)
    {
        if ($productos->isEmpty()) {
            return [
                'A' => [], 'B' => [], 'C' => [], 
                'total_A' => 0, 'total_B' => 0, 'total_C' => 0, 
                'valor_A' => 0, 'valor_B' => 0, 'valor_C' => 0
            ];
        }
        
        $productosConValor = $productos->map(function($producto) {
            $valor = $producto->Cantidad * $producto->Precio;
            return [
                'id' => $producto->id,
                'nombre' => $producto->Nombre,
                'stock' => $producto->Cantidad,
                'precio' => $producto->Precio,
                'valor_inventario' => $valor,
                'producto' => $producto
            ];
        });
        
        $productosOrdenados = $productosConValor->sortByDesc('valor_inventario')->values();
        $totalValor = $productosOrdenados->sum('valor_inventario');
        
        if ($totalValor == 0) {
            return [
                'A' => [], 'B' => [], 'C' => [], 
                'total_A' => 0, 'total_B' => 0, 'total_C' => 0, 
                'valor_A' => 0, 'valor_B' => 0, 'valor_C' => 0
            ];
        }
        
        $acumulado = 0;
        $categoriaA = [];
        $categoriaB = [];
        $categoriaC = [];
        $valorA = 0;
        $valorB = 0;
        $valorC = 0;
        
        foreach ($productosOrdenados as $item) {
            $porcentaje = ($item['valor_inventario'] / $totalValor) * 100;
            $acumulado += $porcentaje;
            
            if ($acumulado <= 80) {
                $categoriaA[] = $item;
                $valorA += $item['valor_inventario'];
            } elseif ($acumulado <= 95) {
                $categoriaB[] = $item;
                $valorB += $item['valor_inventario'];
            } else {
                $categoriaC[] = $item;
                $valorC += $item['valor_inventario'];
            }
        }
        
        return [
            'A' => $categoriaA,
            'B' => $categoriaB,
            'C' => $categoriaC,
            'total_A' => count($categoriaA),
            'total_B' => count($categoriaB),
            'total_C' => count($categoriaC),
            'valor_A' => $valorA,
            'valor_B' => $valorB,
            'valor_C' => $valorC,
        ];
    }

    private function getStockField()
    {
        if (Schema::hasColumn('productos', 'stock')) {
            return 'stock';
        } elseif (Schema::hasColumn('productos', 'cantidad')) {
            return 'cantidad';
        } elseif (Schema::hasColumn('productos', 'Cantidad')) {
            return 'Cantidad';
        }
        return 'stock';
    }
    
    private function getPriceField()
    {
        if (Schema::hasColumn('productos', 'precio_compra')) {
            return 'precio_compra';
        } elseif (Schema::hasColumn('productos', 'precio')) {
            return 'precio';
        } elseif (Schema::hasColumn('productos', 'Precio')) {
            return 'Precio';
        }
        return 'precio_compra';
    }
    
    private function getCategoryField()
    {
        if (Schema::hasColumn('productos', 'categoria_id')) {
            return 'categoria_id';
        } elseif (Schema::hasColumn('productos', 'Categoria')) {
            return 'Categoria';
        }
        return null;
    }
    
    private function getStockMinField()
    {
        if (Schema::hasColumn('productos', 'stock_minimo')) {
            return 'stock_minimo';
        } elseif (Schema::hasColumn('productos', 'cantidad_minima')) {
            return 'cantidad_minima';
        } elseif (Schema::hasColumn('productos', 'Cantidad_minima')) {
            return 'Cantidad_minima';
        }
        return null;
    }
    
    private function getStockMaxField()
    {
        if (Schema::hasColumn('productos', 'stock_maximo')) {
            return 'stock_maximo';
        } elseif (Schema::hasColumn('productos', 'cantidad_maxima')) {
            return 'cantidad_maxima';
        } elseif (Schema::hasColumn('productos', 'Cantidad_maxima')) {
            return 'Cantidad_maxima';
        }
        return null;
    }
    
    private function calculateInventoryValue()
    {
        $stockField = $this->getStockField();
        $priceField = $this->getPriceField();
        
        return Producto::sum(DB::raw("$stockField * $priceField"));
    }
    
    private function getInventoryStatistics()
    {
        $stockField = $this->getStockField();
        $priceField = $this->getPriceField();
        $stockMinField = $this->getStockMinField();
        
        $totalProductos = Producto::count();
        $totalStock = Producto::sum($stockField);
        $valorInventario = $this->calculateInventoryValue();
        
        if ($stockMinField) {
            $productosBajoStock = Producto::whereRaw("$stockField <= $stockMinField")->count();
        } else {
            $productosBajoStock = Producto::where($stockField, '<=', 10)->count();
        }
        
        $productosAgotados = Producto::where($stockField, '<=', 0)->count();
        
        return [
            'total_productos' => $totalProductos,
            'total_stock' => $totalStock,
            'valor_inventario' => $valorInventario,
            'productos_bajo_stock' => $productosBajoStock,
            'productos_agotados' => $productosAgotados,
        ];
    }

    /**
     * Envía el PDF para que se abra en una nueva pestaña
     */
    private function enviarPDFaNuevaPestana($pdf, $nombreArchivo)
    {
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $nombreArchivo . '"')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate')
            ->header('Pragma', 'public');
    }
}