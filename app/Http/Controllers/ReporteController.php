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

class ReporteController extends Controller{
    /**
     * Vista principal del dashboard de reportes
     */
    public function index(){
        $totalProductos = Producto::count();
        
        $stockField = $this->getStockField();
        $totalStock = Producto::sum($stockField);
        
        // Calcular valor de inventario según los campos disponibles
        $valorInventario = $this->calculateInventoryValue();
        // Usar 'Fecha' y 'Total' según tu modelo Venta
        $ventasMes = Venta::whereMonth('Fecha', Carbon::now()->month)
            ->whereYear('Fecha', Carbon::now()->year)
            ->sum('Total');
        
        // Usar 'Fecha_compra' y 'Total' según tu modelo Compra
        $comprasMes = Compra::whereMonth('Fecha_compra', Carbon::now()->month)
            ->whereYear('Fecha_compra', Carbon::now()->year)
            ->sum('Total');
        
        // Ganancia aproximada
        $gananciaMes = $ventasMes - $comprasMes;
        
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
            'empleados',
            'categorias',
            'proveedores'
        ));
    }

    public function generarReporteVentas(Request $request){
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
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
                
                if ($request->filled('empleado_id')) {
                    $q->where('Empleado_idEmpleado', $request->empleado_id);
                }
            })
            ->get();
        
        // Calcular subtotales usando los accessors del modelo
        foreach ($detalleVentas as $detalle) {
            $detalle->subtotal = $detalle->subtotal;
            $detalle->precio_unitario = $detalle->precio_unitario;
        }
        
        // 3. ESTADÍSTICAS BÁSICAS
        $totalVentas = $ventas->count();
        $totalIngresos = $ventas->sum('Total');
        $ventaPromedio = $totalVentas > 0 ? $totalIngresos / $totalVentas : 0;
        
        // Mejor venta del período
        $mejorVenta = $ventas->sortByDesc('Total')->first();
        
        // 4. PRODUCTOS MÁS VENDIDOS
        $productosMasVendidos = collect();
        
        if ($detalleVentas->isNotEmpty()) {
            $productosAgrupados = $detalleVentas->groupBy('Producto');
            
            foreach ($productosAgrupados as $productoId => $detalles) {
                $producto = Producto::find($productoId);
                if ($producto) {
                    $totalCantidad = $detalles->sum('Cantidad');
                    $vecesVendido = $detalles->count();
                    $totalIngresosProducto = $totalCantidad * ($producto->Precio ?? 0);
                    
                    $item = new \stdClass();
                    $item->producto = $producto;
                    $item->total_vendido = $totalCantidad;
                    $item->veces_vendido = $vecesVendido;
                    $item->total_ingresos = $totalIngresosProducto;
                    $item->precio_promedio = $producto->Precio ?? 0;
                    
                    $productosMasVendidos->push($item);
                }
            }
            
            $productosMasVendidos = $productosMasVendidos->sortByDesc('total_vendido')->take(15);
        }
        
        // 5. VENTAS POR EMPLEADO
        $ventasPorEmpleado = Venta::with('empleado')
            ->select('Empleado_idEmpleado', 
                DB::raw('COUNT(*) as total_ventas'),
                DB::raw('SUM(Total) as total_ingresos'),
                DB::raw('AVG(Total) as promedio_venta')
            )
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->orderBy('total_ingresos', 'desc')
            ->get();
        
        // 5.1 DATOS PARA GRÁFICA DE VENTAS POR EMPLEADO
        $datosGraficaEmpleados = $ventasPorEmpleado->map(function($item) {
            $empleado = $item->empleado;
            $nombreCompleto = $empleado 
                ? trim(($empleado->Nombre ?? '') . ' ' . ($empleado->ApPaterno ?? '') . ' ' . ($empleado->ApMaterno ?? ''))
                : 'Empleado eliminado';
            $nombreCompleto = $nombreCompleto ?: 'Sin nombre';
            
            return [
                'nombre' => $nombreCompleto,
                'ventas' => $item->total_ventas,
                'ingresos' => $item->total_ingresos,
            ];
        })->values();
        
        // 5.2 DATOS PARA GRÁFICA DE PRODUCTOS MÁS VENDIDOS
        $datosGraficaProductos = $productosMasVendidos->take(8)->map(function($item) {
            return [
                'nombre' => $item->producto->Nombre ?? 'Producto',
                'cantidad' => $item->total_vendido,
                'ingresos' => $item->total_ingresos,
            ];
        })->values();
        
        // 6. VENTAS POR DÍA
        $ventasPorDia = Venta::select(
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
        
        // 7. CALCULAR DÍAS DEL PERÍODO
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasPeriodo = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        
        // Empleado top
        $empleadoTop = $ventasPorEmpleado->first();
        
        // 8. CONSTRUIR RESUMEN EJECUTIVO
        $resumenEjecutivo = [
            'dias_periodo' => $diasPeriodo,
            'ventas_por_dia' => $diasPeriodo > 0 ? round($totalVentas / $diasPeriodo, 1) : 0,
            'ingresos_por_dia' => $diasPeriodo > 0 ? $totalIngresos / $diasPeriodo : 0,
            'empleado_top' => $empleadoTop,
            'mejor_dia' => $ventasPorDia->sortByDesc('total_dia')->first(),
            'peor_dia' => $ventasPorDia->sortBy('total_dia')->first(),
            'dias_con_ventas' => $ventasPorDia->count(),
            'dias_sin_ventas' => $diasPeriodo - $ventasPorDia->count(),
        ];
        
        // 9. GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.ventas', [
                'ventas' => $ventas,
                'detalleVentas' => $detalleVentas,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'empleadoSeleccionado' => $request->filled('empleado_id') ? Empleado::find($request->empleado_id) : null,
                'totalVentas' => $totalVentas,
                'totalIngresos' => $totalIngresos,
                'ventaPromedio' => $ventaPromedio,
                'productosMasVendidos' => $productosMasVendidos,
                'ventasPorEmpleado' => $ventasPorEmpleado,
                'ventasPorPeriodo' => $ventasPorDia,
                'ventasPorDia' => $ventasPorDia,
                'mejorVenta' => $mejorVenta,
                'resumenEjecutivo' => $resumenEjecutivo,
                'datosGraficaEmpleados' => $datosGraficaEmpleados,
                'datosGraficaProductos' => $datosGraficaProductos,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_ventas_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * ===========================================
     * 2. REPORTE DE COMPRAS - CORREGIDO
     * ===========================================
     */
   public function generarReporteCompras(Request $request){
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        \Log::info('=== ' . __METHOD__ . ' ===');
        \Log::info('Request:', $request->all());
        \Log::info('Vista existe? ' . (view()->exists('reportes.pdf.compras') ? 'SI' : 'NO'));
        
        // OBTENER COMPRAS CON TODOS LOS DATOS
        $query = Compra::with(['proveedor', 'detalleCompras.producto']);
        
        $query->whereDate('Fecha_compra', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_compra', '<=', $request->fecha_fin);
        
        if ($request->filled('proveedor_id')) {
            $query->where('Proveedor_idProveedor', $request->proveedor_id);
        }
        
        // Ordenar
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
        
        // Calcular subtotales para cada detalle
        foreach ($detalleCompras as $detalle) {
            $detalle->subtotal = $detalle->Cantidad * $detalle->Precio_unitario;
        }
        
        // ESTADÍSTICAS
        $totalCompras = $compras->count();
        $totalEgresos = $compras->sum('Total');
        $compraPromedio = $totalCompras > 0 ? $totalEgresos / $totalCompras : 0;
        
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
        
        // Compras por proveedor - CARGAR CON EL ACCESOR
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
        
        // ===== DATOS PARA GRÁFICAS =====
        
        // 1. DATOS PARA GRÁFICA DE COMPRAS POR PROVEEDOR - USANDO EL ACCESOR
        $datosGraficaProveedores = collect();
        foreach($comprasPorProveedor as $item) {
            $nombreCompleto = $item->proveedor ? $item->proveedor->nombre_completo : 'Proveedor';
            
            $datosGraficaProveedores->push([
                'nombre' => $nombreCompleto,
                'compras' => $item->total_compras,
                'monto' => $item->total_egresos
            ]);
        }
        
        // 2. DATOS PARA GRÁFICA DE PRODUCTOS MÁS COMPRADOS
        $datosGraficaProductos = collect();
        foreach($productosMasComprados as $item) {
            $nombreProducto = 'Producto';
            if ($item->producto) {
                $nombreProducto = $item->producto->Nombre ?? $item->producto->nombre ?? 'Producto';
            }
            
            $datosGraficaProductos->push([
                'nombre' => $nombreProducto,
                'cantidad' => $item->total_comprado,
                'costo' => $item->costo_total ?? 0
            ]);
        }
        
        // 3. DATOS PARA GRÁFICA DE COMPRAS POR DÍA
        $datosGraficaDiaria = collect();
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasTotales = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        
        // Crear un array con todos los días del período (máximo 15 días para la gráfica)
        $diasAMostrar = min($diasTotales, 15);
        for ($i = 0; $i < $diasAMostrar; $i++) {
            $fechaActual = $fechaInicioObj->copy()->addDays($i);
            $fechaStr = $fechaActual->format('Y-m-d');
            
            $compraDia = $comprasPorDia->firstWhere('fecha', $fechaStr);
            
            $datosGraficaDiaria->push([
                'fecha' => $fechaActual->format('d/m'),
                'cantidad' => $compraDia->cantidad ?? 0,
                'total' => $compraDia->total ?? 0
            ]);
        }
        
        // 4. DÍA PICO
        $diaPico = $comprasPorDia->sortByDesc('total')->first();
        
        // 5. PROVEEDOR PRINCIPAL CON NOMBRE COMPLETO (usando el accesor)
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
                'productosMasComprados' => $productosMasComprados,
                'comprasPorProveedor' => $comprasPorProveedor,
                'comprasPorDia' => $comprasPorDia,
                'datosGraficaProveedores' => $datosGraficaProveedores,
                'datosGraficaProductos' => $datosGraficaProductos,
                'datosGraficaDiaria' => $datosGraficaDiaria,
                'diaPico' => $diaPico,
                'proveedorPrincipal' => $proveedorPrincipal,
                'diasPeriodo' => $diasTotales,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_compras_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte compras: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * ===========================================
     * 3. REPORTE DE INVENTARIO
     * ===========================================
     */
    public function generarReporteInventario(Request $request)  {
        // Determinar campos disponibles según el modelo
        $stockField = 'Cantidad'; // Campo correcto en tu modelo
        $precioField = 'Precio'; // Campo correcto en tu modelo
        $categoriaField = 'Categoria'; // Campo correcto en tu modelo (foreign key)
        $stockMinField = 'Cantidad_minima'; // Campo correcto en tu modelo
        
        // OBTENER PRODUCTOS
        $query = Producto::query();
        
        // Cargar relación con categoría USANDO EL NOMBRE CORRECTO DE LA RELACIÓN
        $query->with('categoria');
        
        // Filtrar por categoría si se especifica
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        // Filtrar por nivel de stock
        if ($request->filled('nivel_stock')) {
            if ($request->nivel_stock == 'bajo') {
                // Productos con stock menor o igual al mínimo
                $query->whereRaw("Cantidad <= Cantidad_minima AND Cantidad > 0");
            } elseif ($request->nivel_stock == 'medio') {
                // Productos con stock entre mínimo y 50
                $query->whereRaw("Cantidad > Cantidad_minima AND Cantidad <= 50");
            } elseif ($request->nivel_stock == 'alto') {
                // Productos con stock mayor a 50
                $query->where('Cantidad', '>', 50);
            } elseif ($request->nivel_stock == 'agotado') {
                // Productos sin stock
                $query->where('Cantidad', '<=', 0);
            }
        }
        
        // Ordenar
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
            return back()->with('warning', 'No hay productos en el inventario');
        }
        
        // CÁLCULOS ESTADÍSTICOS
        $totalProductos = $productos->count();
        $totalStock = $productos->sum('Cantidad');
        
        $totalInventario = $productos->sum(function($producto) {
            return $producto->Cantidad * $producto->Precio;
        });
        
        // Productos bajo stock (Cantidad <= Cantidad_minima Y > 0)
        $productosBajoStock = $productos->filter(function($producto) {
            return $producto->Cantidad <= $producto->Cantidad_minima && $producto->Cantidad > 0;
        });
        
        // Productos sin stock (Cantidad <= 0)
        $productosSinStock = $productos->filter(function($producto) {
            return $producto->Cantidad <= 0;
        });
        
        // Productos con stock saludable (Cantidad > Cantidad_minima)
        $productosStockSaludable = $productos->filter(function($producto) {
            return $producto->Cantidad > $producto->Cantidad_minima && $producto->Cantidad > 0;
        });
        
        // Productos más valiosos (por valor de inventario)
        $productosMasValiosos = $productos->sortByDesc(function($producto) {
            return $producto->Cantidad * $producto->Precio;
        })->take(10);
        
        // INVENTARIO POR CATEGORÍA
        $inventarioPorCategoria = collect();
        
        // Obtener todas las categorías
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
        
        // Agregar productos sin categoría si existen
        $productosSinCategoria = $productos->whereNull('Categoria')->where('Categoria', 0);
        if ($productosSinCategoria->isNotEmpty()) {
            $totalProductosCat = $productosSinCategoria->count();
            $totalStockCat = $productosSinCategoria->sum('Cantidad');
            $valorTotalCat = $productosSinCategoria->sum(function($p) {
                return $p->Cantidad * $p->Precio;
            });
            
            $item = new \stdClass();
            $item->categoria_id = null;
            $item->categoria_nombre = 'Sin categoría';
            $item->categoria = null;
            $item->total_productos = $totalProductosCat;
            $item->total_stock = $totalStockCat;
            $item->valor_total = $valorTotalCat;
            
            $inventarioPorCategoria->push($item);
        }
        
        // Ordenar por valor total (de mayor a menor)
        $inventarioPorCategoria = $inventarioPorCategoria->sortByDesc('valor_total')->values();
        
        // Análisis ABC
        $productosABC = $this->analisisABC($productos);
        
        // Categoría más valiosa
        $categoriaMasValiosa = $inventarioPorCategoria->first();
        
        // Calcular estadísticas adicionales para el dashboard
        $porcentajeBajoStock = $totalProductos > 0 ? ($productosBajoStock->count() / $totalProductos) * 100 : 0;
        $porcentajeSinStock = $totalProductos > 0 ? ($productosSinStock->count() / $totalProductos) * 100 : 0;
        $porcentajeSaludable = $totalProductos > 0 ? ($productosStockSaludable->count() / $totalProductos) * 100 : 0;
        $valorPromedioProducto = $totalProductos > 0 ? $totalInventario / $totalProductos : 0;
        
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
                'categoriaMasValiosa' => $categoriaMasValiosa,
                'stockField' => 'Cantidad',
                'precioField' => 'Precio',
                'stockMinField' => 'Cantidad_minima',
                // Estadísticas adicionales para la vista
                'porcentajeBajoStock' => $porcentajeBajoStock,
                'porcentajeSinStock' => $porcentajeSinStock,
                'porcentajeSaludable' => $porcentajeSaludable,
                'valorPromedioProducto' => $valorPromedioProducto,
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            $nombreArchivo = 'reporte_inventario_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $this->enviarPDFaNuevaPestana($pdf, $nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error en reporte inventario: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

/**
 * Análisis ABC para inventario (CORREGIDO)
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
        
        // Calcular valor de inventario para cada producto
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
        
        // Ordenar por valor de inventario (descendente)
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
    /**
     * ===========================================
     * 4. REPORTE DE RENTABILIDAD
     * ===========================================
     */
    public function generarReporteRentabilidad(Request $request){
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // Determinar campos disponibles - Usar los nombres correctos del modelo
        $stockField = 'Cantidad';
        $precioField = 'Precio';
        $categoriaField = 'Categoria';
        
        // OBTENER PRODUCTOS
        $query = Producto::query();
        $query->with('categoria');
        
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        $productos = $query->orderBy('Nombre', 'asc')->get();
        
        // Obtener ventas del período para cada producto
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
        
        foreach ($productos as $producto) {
            $venta = $ventasPorProducto->get($producto->id);
            $totalVendido = $venta ? $venta->total_vendido : 0;
            $precioVenta = $producto->Precio ?? 0;
            $totalVentas = $totalVendido * $precioVenta;
            
            // Calcular costo (asumimos 70% del precio de venta como ejemplo)
            $costo = $precioVenta * 0.7;
            $costoTotal = $totalVendido * $costo;
            $gananciaTotal = $totalVentas - $costoTotal;
            $margenGanancia = $totalVentas > 0 ? ($gananciaTotal / $totalVentas) * 100 : 0;
            
            // Acumuladores
            $totalVentasGeneral += $totalVentas;
            $totalGananciaGeneral += $gananciaTotal;
            $totalCostoGeneral += $costoTotal;
            
            $productosProcesados->push([
                'id' => $producto->id,
                'nombre' => $producto->Nombre,
                'categoria' => $producto->categoria->Nombre ?? 'Sin categoría',
                'precio_venta' => $precioVenta,
                'costo' => $costo,
                'stock' => $producto->$stockField,
                'total_vendido' => $totalVendido,
                'total_ventas' => $totalVentas,
                'costo_total' => $costoTotal,
                'ganancia_total' => $gananciaTotal,
                'margen_ganancia' => round($margenGanancia, 2),
                'ganancia_unidad' => $precioVenta - $costo,
                'valor_inventario' => $producto->$stockField * $costo,
                'valor_venta_potencial' => $producto->$stockField * $precioVenta,
            ]);
        }
        
        // Filtrar por empleado si se especifica
        $empleadoSeleccionado = null;
        if ($request->filled('empleado_id')) {
            $empleadoSeleccionado = Empleado::find($request->empleado_id);
        }
        
        // Productos con ventas
        $productosConVentas = $productosProcesados->where('total_vendido', '>', 0);
        
        // Productos más rentables
        $productosMasRentables = $productosConVentas
            ->sortByDesc('margen_ganancia')
            ->take(10);
        
        // Productos menos rentables
        $productosMenosRentables = $productosConVentas
            ->sortBy('margen_ganancia')
            ->take(10);
        
        // Productos sin ventas
        $productosSinVentas = $productosProcesados->where('total_vendido', 0);
        
        // Análisis por categoría
        $analisisCategorias = $productosProcesados->groupBy('categoria')
            ->map(function($productosCategoria) {
                $totalVentas = $productosCategoria->sum('total_ventas');
                $totalGanancia = $productosCategoria->sum('ganancia_total');
                
                return [
                    'total_ventas' => $totalVentas,
                    'total_ganancia' => $totalGanancia,
                    'margen_promedio' => $totalVentas > 0 ? ($totalGanancia / $totalVentas) * 100 : 0,
                    'cantidad_productos' => $productosCategoria->count(),
                    'productos_vendidos' => $productosCategoria->where('total_vendido', '>', 0)->count(),
                    'valor_inventario' => $productosCategoria->sum('valor_inventario'),
                    'valor_venta_potencial' => $productosCategoria->sum('valor_venta_potencial'),
                ];
            })
            ->sortByDesc('total_ganancia');
        
        // DATOS PARA GRÁFICAS
        $datosGraficaRentabilidad = $productosMasRentables->map(function($item) {
            return [
                'nombre' => $item['nombre'],
                'ventas' => $item['total_ventas'],
                'costo' => $item['costo_total'],
                'ganancia' => $item['ganancia_total'],
            ];
        })->values();
        
        $datosGraficaCategorias = $analisisCategorias->map(function($item, $key) {
            return [
                'categoria' => $key,
                'ventas' => $item['total_ventas'],
                'ganancia' => $item['total_ganancia'],
            ];
        })->values()->take(8);
        
        $datosGraficaComparativa = collect([
            ['concepto' => 'Ventas Totales', 'valor' => $totalVentasGeneral],
            ['concepto' => 'Costo Total', 'valor' => $totalCostoGeneral],
            ['concepto' => 'Ganancia Total', 'valor' => $totalGananciaGeneral],
        ]);
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.rentabilidad', [
                'productos' => $productosProcesados,
                'productosMasRentables' => $productosMasRentables,
                'productosMenosRentables' => $productosMenosRentables,
                'productosSinVentas' => $productosSinVentas,
                'analisisCategorias' => $analisisCategorias,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'categoriaSeleccionada' => $request->filled('categoria_id') ? Categoria::find($request->categoria_id) : null,
                'empleadoSeleccionado' => $empleadoSeleccionado,
                'totalGanancia' => $totalGananciaGeneral,
                'totalVentas' => $totalVentasGeneral,
                'totalCosto' => $totalCostoGeneral,
                'margenPromedio' => $totalVentasGeneral > 0 ? round(($totalGananciaGeneral / $totalVentasGeneral) * 100, 2) : 0,
                'filtros' => $request->all(),
                'datosGraficaRentabilidad' => $datosGraficaRentabilidad,
                'datosGraficaCategorias' => $datosGraficaCategorias,
                'datosGraficaComparativa' => $datosGraficaComparativa,
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
     * Obtener el costo de un producto (corregido)
     */
    private function getProductCost($producto){
        
        if (isset($producto->Precio) && $producto->Precio > 0) {
            // Si quieres estimar el costo como 70% del precio de venta
            return $producto->Precio * 0.7;
        }
        
        return 0;
    }

    private function getStockField(){
        if (Schema::hasColumn('productos', 'stock')) {
            return 'stock';
        } elseif (Schema::hasColumn('productos', 'cantidad')) {
            return 'cantidad';
        } elseif (Schema::hasColumn('productos', 'Cantidad')) {
            return 'Cantidad';
        }
        return 'stock';
    }
    
    /**
     * Determinar el campo de precio
     */
    private function getPriceField(){
        if (Schema::hasColumn('productos', 'precio_compra')) {
            return 'precio_compra';
        } elseif (Schema::hasColumn('productos', 'precio')) {
            return 'precio';
        } elseif (Schema::hasColumn('productos', 'Precio')) {
            return 'Precio';
        }
        return 'precio_compra';
    }
    
    /**
     * Determinar el campo de categoría
     */
    private function getCategoryField(){
        if (Schema::hasColumn('productos', 'categoria_id')) {
            return 'categoria_id';
        } elseif (Schema::hasColumn('productos', 'Categoria')) {
            return 'Categoria';
        }
        return null;
    }
    
    /**
     * Determinar el campo de stock mínimo
     */
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
    
    /**
     * Determinar el campo de stock máximo
     */
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
    
    /**
     * Calcular valor del inventario
     */
    private function calculateInventoryValue()
    {
        $stockField = $this->getStockField();
        $priceField = $this->getPriceField();
        
        return Producto::sum(DB::raw("$stockField * $priceField"));
    }
    
    /**
     * Obtener estadísticas de inventario
     */
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