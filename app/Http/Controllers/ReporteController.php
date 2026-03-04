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
        $pedidosPendientes = Pedido::where('Estado', 'pendiente')->count();
        
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
     * 1. REPORTE DE VENTAS
     * ===========================================
     */
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
        
        // ===== DATOS PARA GRÁFICAS =====
        
        // 5. GRÁFICA 1: VENTAS POR EMPLEADO
        $datosGraficaEmpleados = [];
        
        $ventasPorEmpleado = Venta::with('empleado')
            ->select('Empleado_idEmpleado', DB::raw('SUM(Total) as total'))
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->get();
        
        foreach($ventasPorEmpleado as $venta) {
            $nombre = 'Sin vendedor';
            if($venta->empleado) {
                $nombre = trim($venta->empleado->Nombre . ' ' . ($venta->empleado->ApPaterno ?? ''));
            }
            $datosGraficaEmpleados[] = [
                'nombre' => $nombre,
                'total' => floatval($venta->total)
            ];
        }
        
        // 6. GRÁFICA 2: PRODUCTOS MÁS VENDIDOS
        $datosGraficaProductos = [];
        
        $productosTop = DetalleVenta::with('producto')
            ->select('Producto', DB::raw('SUM(Cantidad) as total_cantidad'))
            ->whereHas('venta', function($q) use ($request) {
                $q->whereDate('Fecha', '>=', $request->fecha_inicio)
                ->whereDate('Fecha', '<=', $request->fecha_fin);
            })
            ->groupBy('Producto')
            ->orderBy('total_cantidad', 'desc')
            ->limit(5)
            ->get();
        
        foreach($productosTop as $detalle) {
            if($detalle->producto) {
                $datosGraficaProductos[] = [
                    'nombre' => $detalle->producto->Nombre,
                    'cantidad' => intval($detalle->total_cantidad)
                ];
            }
        }
        
        // 7. GRÁFICA 3: VENTAS POR DÍA
        $datosGraficaDiaria = [];
        
        $ventasPorDia = Venta::select(
                DB::raw('DATE(Fecha) as fecha'),
                DB::raw('SUM(Total) as total')
            )
            ->whereDate('Fecha', '>=', $request->fecha_inicio)
            ->whereDate('Fecha', '<=', $request->fecha_fin)
            ->groupBy(DB::raw('DATE(Fecha)'))
            ->orderBy('fecha')
            ->get();
        
        foreach($ventasPorDia as $dia) {
            $datosGraficaDiaria[] = [
                'fecha' => date('d/m', strtotime($dia->fecha)),
                'total' => floatval($dia->total)
            ];
        }
        
        // 8. RESUMEN EJECUTIVO
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasPeriodo = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        
        $resumenEjecutivo = [
            'dias_periodo' => $diasPeriodo,
            'dias_con_ventas' => $ventasPorDia->count(),
            'dias_sin_ventas' => $diasPeriodo - $ventasPorDia->count(),
        ];
        
        // 9. GENERAR PDF
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
                'datosGraficaEmpleados' => $datosGraficaEmpleados,
                'datosGraficaProductos' => $datosGraficaProductos,
                'datosGraficaDiaria' => $datosGraficaDiaria,
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
     * 2. REPORTE DE COMPRAS
     * ===========================================
     */
    public function generarReporteCompras(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
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
        
        // DATOS PARA GRÁFICAS
        $datosGraficaProveedores = [];
        foreach($comprasPorProveedor as $item) {
            $nombreCompleto = $item->proveedor ? $item->proveedor->nombre_completo : 'Proveedor';
            
            $datosGraficaProveedores[] = [
                'nombre' => $nombreCompleto,
                'monto' => floatval($item->total_egresos)
            ];
        }
        
        $datosGraficaProductosComprados = [];
        foreach($productosMasComprados as $item) {
            $nombreProducto = 'Producto';
            if ($item->producto) {
                $nombreProducto = $item->producto->Nombre ?? $item->producto->nombre ?? 'Producto';
            }
            
            $datosGraficaProductosComprados[] = [
                'nombre' => $nombreProducto,
                'cantidad' => intval($item->total_comprado)
            ];
        }
        
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasTotales = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        
        $datosGraficaComprasDiarias = [];
        $diasAMostrar = min($diasTotales, 15);
        for ($i = 0; $i < $diasAMostrar; $i++) {
            $fechaActual = $fechaInicioObj->copy()->addDays($i);
            $fechaStr = $fechaActual->format('Y-m-d');
            
            $compraDia = $comprasPorDia->firstWhere('fecha', $fechaStr);
            
            $datosGraficaComprasDiarias[] = [
                'fecha' => $fechaActual->format('d/m'),
                'total' => floatval($compraDia->total ?? 0)
            ];
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
                'datosGraficaProveedores' => $datosGraficaProveedores,
                'datosGraficaProductosComprados' => $datosGraficaProductosComprados,
                'datosGraficaComprasDiarias' => $datosGraficaComprasDiarias,
                'diaPico' => $diaPico,
                'proveedorPrincipal' => $proveedorPrincipal,
                'diasPeriodo' => $diasTotales,
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
     * 3. REPORTE DE PEDIDOS
     * ===========================================
     */
    public function generarReportePedidos(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
        
        // OBTENER PEDIDOS CON TODAS LAS RELACIONES
        $query = Pedido::with(['cliente', 'empleado', 'detallePedidos.producto']);
        
        $query->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
              ->whereDate('Fecha_pedido', '<=', $request->fecha_fin);
        
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('Estado', $request->estado);
        }
        
        // Filtro por empleado (vendedor)
        if ($request->filled('empleado_id')) {
            $query->where('Empleado_idEmpleado', $request->empleado_id);
        }
        
        // Ordenar
        $orden = $request->orden ?? 'fecha_desc';
        if ($orden == 'fecha_desc') $query->orderBy('Fecha_pedido', 'desc');
        elseif ($orden == 'fecha_asc') $query->orderBy('Fecha_pedido', 'asc');
        elseif ($orden == 'estado') $query->orderBy('Estado')->orderBy('Fecha_pedido', 'desc');
        
        $pedidos = $query->get();
        
        // ESTADÍSTICAS GENERALES
        $totalPedidos = $pedidos->count();
        
        // Conteo por estado
        $pedidosPendientes = $pedidos->where('Estado', 'pendiente')->count();
        $pedidosEnProceso = $pedidos->where('Estado', 'en proceso')->count();
        $pedidosEntregados = $pedidos->where('Estado', 'entregado')->count();
        $pedidosCancelados = $pedidos->where('Estado', 'cancelado')->count();
        
        // Valor estimado de pedidos (basado en productos)
        $valorTotalPedidos = 0;
        foreach ($pedidos as $pedido) {
            foreach ($pedido->detallePedidos as $detalle) {
                $valorTotalPedidos += ($detalle->Cantidad * ($detalle->producto->Precio ?? 0));
            }
        }
        
        // Tiempo promedio de entrega (para pedidos entregados)
        $tiempoPromedioEntrega = null;
        $pedidosEntregadosCollection = $pedidos->where('Estado', 'entregado');
        if ($pedidosEntregadosCollection->count() > 0) {
            $diasTotales = 0;
            foreach ($pedidosEntregadosCollection as $pedido) {
                if ($pedido->Fecha_entrega) {
                    $fechaPedido = Carbon::parse($pedido->Fecha_pedido);
                    $fechaEntrega = Carbon::parse($pedido->Fecha_entrega);
                    $diasTotales += $fechaEntrega->diffInDays($fechaPedido);
                }
            }
            $tiempoPromedioEntrega = round($diasTotales / $pedidosEntregadosCollection->count(), 1);
        }
        
        // Cliente con más pedidos
        $clienteTop = Pedido::with('cliente')
            ->select('Cliente_idCliente', DB::raw('COUNT(*) as total_pedidos'))
            ->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_pedido', '<=', $request->fecha_fin)
            ->groupBy('Cliente_idCliente')
            ->orderBy('total_pedidos', 'desc')
            ->first();
        
        // Vendedor con más pedidos
        $vendedorTop = Pedido::with('empleado')
            ->select('Empleado_idEmpleado', DB::raw('COUNT(*) as total_pedidos'))
            ->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_pedido', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->orderBy('total_pedidos', 'desc')
            ->first();
        
        // PEDIDOS POR DÍA
        $pedidosPorDia = Pedido::select(
                DB::raw('DATE(Fecha_pedido) as fecha'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(CASE WHEN Estado = "pendiente" THEN 1 ELSE 0 END) as pendientes'),
                DB::raw('SUM(CASE WHEN Estado = "en proceso" THEN 1 ELSE 0 END) as en_proceso'),
                DB::raw('SUM(CASE WHEN Estado = "entregado" THEN 1 ELSE 0 END) as entregados'),
                DB::raw('SUM(CASE WHEN Estado = "cancelado" THEN 1 ELSE 0 END) as cancelados')
            )
            ->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_pedido', '<=', $request->fecha_fin)
            ->groupBy(DB::raw('DATE(Fecha_pedido)'))
            ->orderBy('fecha', 'asc')
            ->get();
        
        // PEDIDOS POR VENDEDOR
        $pedidosPorVendedor = Pedido::with('empleado')
            ->select(
                'Empleado_idEmpleado', 
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(CASE WHEN Estado = "pendiente" THEN 1 ELSE 0 END) as pendientes'),
                DB::raw('SUM(CASE WHEN Estado = "en proceso" THEN 1 ELSE 0 END) as en_proceso'),
                DB::raw('SUM(CASE WHEN Estado = "entregado" THEN 1 ELSE 0 END) as entregados')
            )
            ->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_pedido', '<=', $request->fecha_fin)
            ->groupBy('Empleado_idEmpleado')
            ->orderBy('total_pedidos', 'desc')
            ->get();
        
        // PEDIDOS POR CLIENTE
        $pedidosPorCliente = Pedido::with('cliente')
            ->select('Cliente_idCliente', DB::raw('COUNT(*) as total_pedidos'))
            ->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
            ->whereDate('Fecha_pedido', '<=', $request->fecha_fin)
            ->groupBy('Cliente_idCliente')
            ->orderBy('total_pedidos', 'desc')
            ->take(10)
            ->get();
        
        // PRODUCTOS MÁS PEDIDOS
        $productosMasPedidos = DetallePedido::with('producto')
            ->select(
                'Producto_idProducto',
                DB::raw('SUM(Cantidad) as total_cantidad'),
                DB::raw('COUNT(DISTINCT Pedido_idPedido) as veces_pedido')
            )
            ->whereHas('pedido', function($q) use ($request) {
                $q->whereDate('Fecha_pedido', '>=', $request->fecha_inicio)
                  ->whereDate('Fecha_pedido', '<=', $request->fecha_fin);
            })
            ->groupBy('Producto_idProducto')
            ->orderBy('total_cantidad', 'desc')
            ->take(10)
            ->get();
        
        // DATOS PARA GRÁFICAS
        $datosGraficaEstados = [
            ['estado' => 'Pendientes', 'cantidad' => $pedidosPendientes],
            ['estado' => 'En Proceso', 'cantidad' => $pedidosEnProceso],
            ['estado' => 'Entregados', 'cantidad' => $pedidosEntregados],
            ['estado' => 'Cancelados', 'cantidad' => $pedidosCancelados]
        ];
        
        $fechaInicioObj = Carbon::parse($request->fecha_inicio);
        $fechaFinObj = Carbon::parse($request->fecha_fin);
        $diasTotales = $fechaInicioObj->diffInDays($fechaFinObj) + 1;
        
        $datosGraficaPedidosDiarios = [];
        $diasAMostrar = min($diasTotales, 15);
        for ($i = 0; $i < $diasAMostrar; $i++) {
            $fechaActual = $fechaInicioObj->copy()->addDays($i);
            $fechaStr = $fechaActual->format('Y-m-d');
            
            $pedidoDia = $pedidosPorDia->firstWhere('fecha', $fechaStr);
            
            $datosGraficaPedidosDiarios[] = [
                'fecha' => $fechaActual->format('d/m'),
                'total' => intval($pedidoDia->cantidad ?? 0)
            ];
        }
        
        $datosGraficaVendedores = [];
        foreach($pedidosPorVendedor as $item) {
            $nombreCompleto = 'Sin vendedor';
            if ($item->empleado) {
                $nombreCompleto = trim(($item->empleado->Nombre ?? '') . ' ' . 
                                      ($item->empleado->ApPaterno ?? '') . ' ' . 
                                      ($item->empleado->ApMaterno ?? ''));
                $nombreCompleto = $nombreCompleto ?: 'Vendedor';
            }
            
            $datosGraficaVendedores[] = [
                'nombre' => $nombreCompleto,
                'total' => intval($item->total_pedidos)
            ];
        }
        
        $datosGraficaProductosPedidos = [];
        foreach($productosMasPedidos as $item) {
            $datosGraficaProductosPedidos[] = [
                'nombre' => $item->producto->Nombre ?? 'Producto',
                'cantidad' => intval($item->total_cantidad)
            ];
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.pedidos', [
                'pedidos' => $pedidos,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'filtros' => $request->all(),
                'estadoSeleccionado' => $request->estado,
                'empleadoSeleccionado' => $request->filled('empleado_id') ? Empleado::find($request->empleado_id) : null,
                
                // Estadísticas
                'totalPedidos' => $totalPedidos,
                'pedidosPendientes' => $pedidosPendientes,
                'pedidosEnProceso' => $pedidosEnProceso,
                'pedidosEntregados' => $pedidosEntregados,
                'pedidosCancelados' => $pedidosCancelados,
                'valorTotalPedidos' => $valorTotalPedidos,
                'tiempoPromedioEntrega' => $tiempoPromedioEntrega,
                'clienteTop' => $clienteTop,
                'vendedorTop' => $vendedorTop,
                
                // Datos detallados
                'pedidosPorDia' => $pedidosPorDia,
                'pedidosPorVendedor' => $pedidosPorVendedor,
                'pedidosPorCliente' => $pedidosPorCliente,
                'productosMasPedidos' => $productosMasPedidos,
                
                // Datos para gráficas
                'datosGraficaEstados' => $datosGraficaEstados,
                'datosGraficaPedidosDiarios' => $datosGraficaPedidosDiarios,
                'datosGraficaVendedores' => $datosGraficaVendedores,
                'datosGraficaProductosPedidos' => $datosGraficaProductosPedidos,
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
     * 4. REPORTE DE INVENTARIO
     * ===========================================
     */
    public function generarReporteInventario(Request $request)
    {
        // OBTENER PRODUCTOS
        $query = Producto::query();
        $query->with('categoria');
        
        // Filtrar por categoría si se especifica
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        // Filtrar por nivel de stock
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
        
        // MOVIMIENTOS DE INVENTARIO (ENTRADAS Y SALIDAS DEL PERÍODO)
        $fechaInicio = $request->fecha_inicio ?? Carbon::now()->startOfMonth();
        $fechaFin = $request->fecha_fin ?? Carbon::now()->endOfMonth();
        
        // Entradas (compras)
        $entradas = DetalleCompra::with('producto', 'compra')
            ->whereHas('compra', function($q) use ($fechaInicio, $fechaFin) {
                $q->whereDate('Fecha_compra', '>=', $fechaInicio)
                  ->whereDate('Fecha_compra', '<=', $fechaFin);
            })
            ->get()
            ->groupBy('Producto')
            ->map(function($items) {
                return [
                    'total_cantidad' => $items->sum('Cantidad'),
                    'total_costo' => $items->sum(function($item) {
                        return $item->Cantidad * $item->Precio_unitario;
                    }),
                    'veces' => $items->count(),
                    'producto' => $items->first()->producto,
                ];
            })
            ->sortByDesc('total_cantidad');
        
        // Salidas (ventas)
        $salidas = DetalleVenta::with('producto', 'venta')
            ->whereHas('venta', function($q) use ($fechaInicio, $fechaFin) {
                $q->whereDate('Fecha', '>=', $fechaInicio)
                  ->whereDate('Fecha', '<=', $fechaFin);
            })
            ->get()
            ->groupBy('Producto')
            ->map(function($items) {
                return [
                    'total_cantidad' => $items->sum('Cantidad'),
                    'total_ingresos' => $items->sum(function($item) {
                        return $item->Cantidad * ($item->producto->Precio ?? 0);
                    }),
                    'veces' => $items->count(),
                    'producto' => $items->first()->producto,
                ];
            })
            ->sortByDesc('total_cantidad');
        
        // Productos con mayor rotación
        $rotacionProductos = collect();
        $productosConMovimiento = $entradas->keys()->merge($salidas->keys())->unique();
        
        foreach ($productosConMovimiento as $productoId) {
            $producto = Producto::find($productoId);
            if (!$producto) continue;
            
            $entrada = $entradas->get($productoId, ['total_cantidad' => 0]);
            $salida = $salidas->get($productoId, ['total_cantidad' => 0]);
            
            $stockPromedio = ($producto->Cantidad + ($producto->Cantidad + $entrada['total_cantidad'] - $salida['total_cantidad'])) / 2;
            $rotacion = $stockPromedio > 0 ? $salida['total_cantidad'] / $stockPromedio : 0;
            
            $rotacionProductos->push([
                'producto' => $producto,
                'entradas' => $entrada['total_cantidad'],
                'salidas' => $salida['total_cantidad'],
                'stock_actual' => $producto->Cantidad,
                'rotacion' => round($rotacion, 2),
            ]);
        }
        
        $productosMayorRotacion = $rotacionProductos->sortByDesc('rotacion')->take(10);
        
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
        
        // Calcular estadísticas adicionales
        $porcentajeBajoStock = $totalProductos > 0 ? ($productosBajoStock->count() / $totalProductos) * 100 : 0;
        $porcentajeSinStock = $totalProductos > 0 ? ($productosSinStock->count() / $totalProductos) * 100 : 0;
        $porcentajeSaludable = $totalProductos > 0 ? ($productosStockSaludable->count() / $totalProductos) * 100 : 0;
        $valorPromedioProducto = $totalProductos > 0 ? $totalInventario / $totalProductos : 0;
        
        // DATOS PARA GRÁFICAS
        $datosGraficaStockNiveles = [
            ['nivel' => 'Stock Saludable', 'cantidad' => $productosStockSaludable->count()],
            ['nivel' => 'Stock Bajo', 'cantidad' => $productosBajoStock->count()],
            ['nivel' => 'Sin Stock', 'cantidad' => $productosSinStock->count()]
        ];
        
        $datosGraficaCategorias = [];
        foreach($inventarioPorCategoria as $categoria) {
            $datosGraficaCategorias[] = [
                'nombre' => $categoria->categoria_nombre,
                'valor' => floatval($categoria->valor_total)
            ];
        }
        
        $datosGraficaProductosValiosos = [];
        foreach($productosMasValiosos as $producto) {
            $datosGraficaProductosValiosos[] = [
                'nombre' => $producto->Nombre,
                'valor' => floatval($producto->Cantidad * $producto->Precio)
            ];
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.inventario', [
                'productos' => $productos,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'filtros' => $request->all(),
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
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
                'entradas' => $entradas,
                'salidas' => $salidas,
                'productosMayorRotacion' => $productosMayorRotacion,
                'porcentajeBajoStock' => $porcentajeBajoStock,
                'porcentajeSinStock' => $porcentajeSinStock,
                'porcentajeSaludable' => $porcentajeSaludable,
                'valorPromedioProducto' => $valorPromedioProducto,
                
                // Datos para gráficas
                'datosGraficaStockNiveles' => $datosGraficaStockNiveles,
                'datosGraficaCategorias' => $datosGraficaCategorias,
                'datosGraficaProductosValiosos' => $datosGraficaProductosValiosos,
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
        
        // OBTENER PRODUCTOS
        $query = Producto::query();
        $query->with('categoria');
        
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        $productos = $query->orderBy('Nombre', 'asc')->get();
        
        // Calcular costo promedio ponderado de compras
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
            
            // Obtener costo real de compras o estimar
            $compraInfo = $comprasProducto->get($producto->id);
            $costo = $compraInfo ? $compraInfo->precio_promedio : ($precioVenta * 0.7); // Fallback a 70%
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
                'stock' => $producto->Cantidad,
                'total_vendido' => $totalVendido,
                'total_ventas' => $totalVentas,
                'costo_total' => $costoTotal,
                'ganancia_total' => $gananciaTotal,
                'margen_ganancia' => round($margenGanancia, 2),
                'ganancia_unidad' => $precioVenta - $costo,
                'valor_inventario' => $producto->Cantidad * $costo,
                'valor_venta_potencial' => $producto->Cantidad * $precioVenta,
            ]);
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
        $datosGraficaRentabilidad = [];
        foreach($productosMasRentables as $item) {
            $datosGraficaRentabilidad[] = [
                'nombre' => $item['nombre'],
                'ganancia' => floatval($item['ganancia_total'])
            ];
        }
        
        $datosGraficaCategorias = [];
        foreach($analisisCategorias as $key => $item) {
            $datosGraficaCategorias[] = [
                'nombre' => $key,
                'ventas' => floatval($item['total_ventas']),
                'ganancia' => floatval($item['total_ganancia'])
            ];
        }
        
        $datosGraficaComparativa = [
            ['concepto' => 'Ventas', 'valor' => floatval($totalVentasGeneral)],
            ['concepto' => 'Costo', 'valor' => floatval($totalCostoGeneral)],
            ['concepto' => 'Ganancia', 'valor' => floatval($totalGananciaGeneral)]
        ];
        
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
        
        // Obtener todos los empleados (vendedores)
        $empleados = Empleado::all();
        
        $datosVendedores = collect();
        
        foreach ($empleados as $empleado) {
            // Ventas del empleado en el período
            $ventas = Venta::where('Empleado_idEmpleado', $empleado->id)
                ->whereDate('Fecha', '>=', $request->fecha_inicio)
                ->whereDate('Fecha', '<=', $request->fecha_fin)
                ->get();
            
            if ($ventas->isEmpty() && !$request->filled('incluir_sin_ventas')) {
                continue; // Omitir vendedores sin ventas si no se pide incluirlos
            }
            
            // Detalles de ventas para este vendedor
            $detallesVentas = DetalleVenta::with('producto')
                ->whereHas('venta', function($q) use ($empleado, $request) {
                    $q->where('Empleado_idEmpleado', $empleado->id)
                      ->whereDate('Fecha', '>=', $request->fecha_inicio)
                      ->whereDate('Fecha', '<=', $request->fecha_fin);
                })
                ->get();
            
            // Productos vendidos (agrupados)
            $productosVendidos = $detallesVentas->groupBy('Producto')
                ->map(function($items) {
                    $producto = $items->first()->producto;
                    $cantidadTotal = $items->sum('Cantidad');
                    $totalVentas = $cantidadTotal * ($producto->Precio ?? 0);
                    
                    return [
                        'producto' => $producto,
                        'cantidad' => $cantidadTotal,
                        'total' => $totalVentas,
                    ];
                })
                ->sortByDesc('cantidad')
                ->take(5); // Top 5 productos por vendedor
            
            $totalVentas = $ventas->sum('Total');
            $totalProductosVendidos = $detallesVentas->sum('Cantidad');
            $numVentas = $ventas->count();
            $productosUnicos = $detallesVentas->groupBy('Producto')->count();
            
            // Ticket promedio
            $ticketPromedio = $numVentas > 0 ? $totalVentas / $numVentas : 0;
            
            // Mejor día de ventas
            $mejorDia = $ventas->groupBy(function($venta) {
                return Carbon::parse($venta->Fecha)->format('Y-m-d');
            })->map(function($ventasDia) {
                return [
                    'fecha' => $ventasDia->first()->Fecha,
                    'total' => $ventasDia->sum('Total'),
                    'cantidad' => $ventasDia->count(),
                ];
            })->sortByDesc('total')->first();
            
            $datosVendedores->push([
                'empleado' => $empleado,
                'nombre_completo' => trim($empleado->Nombre . ' ' . $empleado->ApPaterno . ' ' . $empleado->ApMaterno),
                'num_ventas' => $numVentas,
                'total_ventas' => $totalVentas,
                'total_productos' => $totalProductosVendidos,
                'productos_unicos' => $productosUnicos,
                'ticket_promedio' => $ticketPromedio,
                'productos_destacados' => $productosVendidos,
                'mejor_dia' => $mejorDia,
                'ventas' => $ventas,
            ]);
        }
        
        // Ordenar por total de ventas
        $datosVendedores = $datosVendedores->sortByDesc('total_ventas')->values();
        
        // Estadísticas globales
        $totalGeneralVentas = $datosVendedores->sum('total_ventas');
        $totalGeneralProductos = $datosVendedores->sum('total_productos');
        $promedioPorVendedor = $datosVendedores->count() > 0 ? $totalGeneralVentas / $datosVendedores->count() : 0;
        
        // Vendedor del mes (top)
        $vendedorTop = $datosVendedores->first();
        
        // DATOS PARA GRÁFICAS
        $datosGraficaVentas = [];
        foreach($datosVendedores as $item) {
            $datosGraficaVentas[] = [
                'nombre' => $item['nombre_completo'],
                'total' => floatval($item['total_ventas'])
            ];
        }
        
        $datosGraficaProductosVendidos = [];
        foreach($datosVendedores as $item) {
            $datosGraficaProductosVendidos[] = [
                'nombre' => $item['nombre_completo'],
                'total' => intval($item['total_productos'])
            ];
        }
        
        $datosGraficaTickets = [];
        foreach($datosVendedores as $item) {
            $datosGraficaTickets[] = [
                'nombre' => $item['nombre_completo'],
                'total' => floatval($item['ticket_promedio'])
            ];
        }
        
        // GENERAR PDF
        try {
            $pdf = Pdf::loadView('reportes.pdf.ventas_vendedor', [
                'datosVendedores' => $datosVendedores,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => $request->fecha_fin,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'filtros' => $request->all(),
                'totalGeneralVentas' => $totalGeneralVentas,
                'totalGeneralProductos' => $totalGeneralProductos,
                'promedioPorVendedor' => $promedioPorVendedor,
                'vendedorTop' => $vendedorTop,
                'totalVendedores' => $datosVendedores->count(),
                
                // Datos para gráficas
                'datosGraficaVentas' => $datosGraficaVentas,
                'datosGraficaProductosVendidos' => $datosGraficaProductosVendidos,
                'datosGraficaTickets' => $datosGraficaTickets,
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
     * MÉTODOS AUXILIARES
     * ===========================================
     */

    /**
     * Análisis ABC para inventario
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
     * Obtener el campo de stock
     */
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
    
    /**
     * Obtener el campo de precio
     */
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
    
    /**
     * Obtener el campo de categoría
     */
    private function getCategoryField()
    {
        if (Schema::hasColumn('productos', 'categoria_id')) {
            return 'categoria_id';
        } elseif (Schema::hasColumn('productos', 'Categoria')) {
            return 'Categoria';
        }
        return null;
    }
    
    /**
     * Obtener el campo de stock mínimo
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
     * Obtener el campo de stock máximo
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