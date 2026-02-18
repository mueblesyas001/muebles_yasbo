<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base con relaciones
        $query = Venta::with(['empleado', 'detalleVentas.producto.categoria']);
        
        // ========== APLICAR FILTROS ==========
        
        // 1. Filtro por ID de venta (exacto)
        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }
        
        // 2. Filtro por fecha específica
        if ($request->filled('fecha')) {
            $query->whereDate('Fecha', $request->input('fecha'));
        }
        
        // 3. Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('Fecha', '>=', $request->input('fecha_desde'));
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('Fecha', '<=', $request->input('fecha_hasta'));
        }
        
        // 4. Filtro por empleado (usando el nombre correcto del campo)
        if ($request->filled('empleado_id')) {
            $query->where('Empleado_idEmpleado', $request->input('empleado_id'));
        }
        
        // 5. Filtro por monto mínimo
        if ($request->filled('monto_min')) {
            $query->where('Total', '>=', $request->input('monto_min'));
        }
        
        // 6. Filtro por monto máximo
        if ($request->filled('monto_max')) {
            $query->where('Total', '<=', $request->input('monto_max'));
        }
        
        // 7. Filtro por producto (búsqueda en nombre del producto)
        if ($request->filled('producto')) {
            $productoNombre = $request->input('producto');
            $query->whereHas('detalleVentas.producto', function($q) use ($productoNombre) {
                $q->where('Nombre', 'like', "%{$productoNombre}%");
            });
        }
        
        // ========== ORDENAMIENTO ==========
        $sortBy = $request->input('sort_by', 'Fecha');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Validar campos de ordenamiento
        $allowedSorts = ['id', 'Fecha', 'Total'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Fecha';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // ========== OBTENER DATOS ==========
        
        // Datos para estadísticas (sin paginar, con filtros)
        $ventasFiltradas = $query->get();
        
        // Datos para tabla (paginar con filtros)
        $ventasPaginated = $query->paginate(15)->withQueryString();
        
        // Empleados para dropdown
        $empleados = Empleado::orderBy('Nombre')->get();
        
        return view('ventas.index', compact('ventasFiltradas', 'ventasPaginated', 'empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::where('Cantidad', '>', 0)
                            ->orderBy('Nombre')
                            ->get();
        
        $empleados = Empleado::orderBy('Nombre')->get();

        return view('ventas.create', compact('productos', 'empleados'));
    }

    public function store(Request $request)
    {
        // Validación completa
        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $detallesVenta = [];

            // Validar stock y calcular total
            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);

                // Validar stock disponible
                if ($producto->Cantidad < $productoData['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->Nombre}. Stock disponible: {$producto->Cantidad}");
                }

                $subtotal = $producto->Precio * $productoData['cantidad'];
                $total += $subtotal;

                // Preparar detalles para inserción (sin PrecioUnitario y Subtotal)
                $detallesVenta[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $productoData['cantidad']
                ];
            }

            $fechaHoraActual = Carbon::now('America/Mexico_City');

            // Crear la venta
            $venta = Venta::create([
                'Fecha' => $fechaHoraActual, 
                'Total' => $total,
                'Empleado_idEmpleado' => $request->empleado_id
            ]);

            // Crear detalles de venta y actualizar stock
            foreach ($detallesVenta as $detalle) {
                // Crear detalle de venta (sin PrecioUnitario y Subtotal)
                DetalleVenta::create([
                    'Producto' => $detalle['Producto'],
                    'Venta' => $venta->id,
                    'Cantidad' => $detalle['Cantidad']
                ]);

                // Actualizar stock del producto
                Producto::where('id', $detalle['Producto'])
                       ->decrement('Cantidad', $detalle['Cantidad']);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                           ->with('success', 'Venta registrada exitosamente. Total: $' . number_format($total, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la venta: ' . $e->getMessage())
                        ->withInput();
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $venta = Venta::with('detalleVentas.producto')->findOrFail($id);
        $productos = Producto::where('Cantidad', '>', 0)->get();
        $empleados = Empleado::all();

        return view('ventas.edit', compact('venta', 'productos', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $venta = Venta::with('detalleVentas')->findOrFail($id);

            // Restaurar stock de la venta original
            foreach ($venta->detalleVentas as $detalle) {
                Producto::where('id', $detalle->Producto)
                       ->increment('Cantidad', $detalle->Cantidad);
            }

            // Eliminar detalles antiguos
            $venta->detalleVentas()->delete();

            $total = 0;
            $detallesVenta = [];

            // Calcular nuevo total y validar stock
            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);

                if ($producto->Cantidad < $productoData['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->Nombre}. Stock disponible: {$producto->Cantidad}");
                }

                $subtotal = $producto->Precio * $productoData['cantidad'];
                $total += $subtotal;

                $detallesVenta[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $productoData['cantidad']
                ];
            }

            // 🔥 CORRECCIÓN: Actualizar fecha de modificación
            $fechaHoraActual = Carbon::now('America/Mexico_City');

            // Actualizar venta
            $venta->update([
                'Total' => $total,
                'Empleado_idEmpleado' => $request->empleado_id,
                'Fecha' => $fechaHoraActual // Actualizar fecha al modificar
            ]);

            // Crear nuevos detalles y actualizar stock
            foreach ($detallesVenta as $detalle) {
                DetalleVenta::create([
                    'Producto' => $detalle['Producto'],
                    'Venta' => $venta->id,
                    'Cantidad' => $detalle['Cantidad']
                ]);

                Producto::where('id', $detalle['Producto'])
                       ->decrement('Cantidad', $detalle['Cantidad']);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                           ->with('success', 'Venta actualizada exitosamente. Nuevo total: $' . number_format($total, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la venta: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::with('detalleVentas')->findOrFail($id);

            // Restaurar stock de productos
            foreach ($venta->detalleVentas as $detalle) {
                Producto::where('id', $detalle->Producto)
                       ->increment('Cantidad', $detalle->Cantidad);
            }

            // Eliminar detalles de venta
            $venta->detalleVentas()->delete();

            // Eliminar venta
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')
                           ->with('success', 'Venta eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }

    /**
     * MÉTODOS ADICIONALES PARA REPORTES Y ESTADÍSTICAS
     */

    /**
     * Reporte de ventas diarias
     */
    public function reporteDiario(Request $request)
    {
        $fecha = $request->fecha ? Carbon::parse($request->fecha) : Carbon::now('America/Mexico_City');
        
        $ventas = Venta::with(['empleado', 'detalleVentas.producto'])
                      ->whereDate('Fecha', $fecha)
                      ->orderBy('Fecha', 'desc')
                      ->get();

        $totalDia = $ventas->sum('Total');
        $totalProductosVendidos = 0;

        foreach ($ventas as $venta) {
            $totalProductosVendidos += $venta->detalleVentas->sum('Cantidad');
        }

        return view('ventas.reportes.diario', compact('ventas', 'totalDia', 'totalProductosVendidos', 'fecha'));
    }

    /**
     * Reporte de ventas por rango de fechas
     */
    public function reporteRangoFechas(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : Carbon::now('America/Mexico_City')->subDays(7);
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : Carbon::now('America/Mexico_City');

        $ventas = Venta::with(['empleado', 'detalleVentas.producto'])
                      ->whereBetween('Fecha', [$fechaInicio->startOfDay(), $fechaFin->endOfDay()])
                      ->orderBy('Fecha', 'desc')
                      ->get();

        $totalVentas = $ventas->sum('Total');
        $cantidadVentas = $ventas->count();
        $productosVendidos = 0;

        foreach ($ventas as $venta) {
            $productosVendidos += $venta->detalleVentas->sum('Cantidad');
        }

        return view('ventas.reportes.rango-fechas', compact(
            'ventas', 'totalVentas', 'cantidadVentas', 'productosVendidos', 'fechaInicio', 'fechaFin'
        ));
    }

    /**
     * Ventas por empleado
     */
    public function ventasPorEmpleado(Request $request)
    {
        $empleadoId = $request->empleado_id;
        
        $query = Venta::with(['empleado', 'detalleVentas.producto']);
        
        if ($empleadoId) {
            $query->where('Empleado_idEmpleado', $empleadoId);
        }

        $ventas = $query->orderBy('Fecha', 'desc')->paginate(10);
        $empleados = Empleado::all();

        $totalVentas = $ventas->sum('Total');
        $totalVentasCount = $ventas->total();

        return view('ventas.reportes.por-empleado', compact(
            'ventas', 'empleados', 'empleadoId', 'totalVentas', 'totalVentasCount'
        ));
    }

    /**
     * Productos más vendidos
     */
    public function productosMasVendidos(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : Carbon::now('America/Mexico_City')->subMonth();
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : Carbon::now('America/Mexico_City');

        $productosMasVendidos = DetalleVenta::join('productos', 'detalle_venta.Producto', '=', 'productos.id')
            ->join('ventas', 'detalle_venta.Venta', '=', 'ventas.id')
            ->whereBetween('ventas.Fecha', [$fechaInicio->startOfDay(), $fechaFin->endOfDay()])
            ->select(
                'productos.id',
                'productos.Nombre',
                'productos.Precio',
                DB::raw('SUM(detalle_venta.Cantidad) as total_vendido'),
                DB::raw('SUM(detalle_venta.Cantidad * productos.Precio) as total_ingresos')
            )
            ->groupBy('productos.id', 'productos.Nombre', 'productos.Precio')
            ->orderBy('total_vendido', 'desc')
            ->paginate(10);

        return view('ventas.reportes.productos-mas-vendidos', compact('productosMasVendidos', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Dashboard de ventas
     */
    public function dashboard()
    {
        // 🔥 CORRECCIÓN: Usar zona horaria consistente
        $hoy = Carbon::now('America/Mexico_City');
        
        // Ventas de hoy
        $ventasHoy = Venta::whereDate('Fecha', $hoy)->get();
        $totalHoy = $ventasHoy->sum('Total');

        // Ventas de la semana
        $ventasSemana = Venta::whereBetween('Fecha', [$hoy->copy()->startOfWeek(), $hoy->copy()->endOfWeek()])->get();
        $totalSemana = $ventasSemana->sum('Total');

        // Ventas del mes
        $ventasMes = Venta::whereMonth('Fecha', $hoy->month)
                         ->whereYear('Fecha', $hoy->year)
                         ->get();
        $totalMes = $ventasMes->sum('Total');

        // Productos más vendidos del mes
        $productosTop = DetalleVenta::join('productos', 'detalle_venta.Producto', '=', 'productos.id')
            ->join('ventas', 'detalle_venta.Venta', '=', 'ventas.id')
            ->whereMonth('ventas.Fecha', $hoy->month)
            ->whereYear('ventas.Fecha', $hoy->year)
            ->select(
                'productos.Nombre',
                DB::raw('SUM(detalle_venta.Cantidad) as total_vendido')
            )
            ->groupBy('productos.id', 'productos.Nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        // Empleados top del mes
        $empleadosTop = Venta::join('empleados', 'ventas.Empleado_idEmpleado', '=', 'empleados.id')
            ->whereMonth('ventas.Fecha', $hoy->month)
            ->whereYear('ventas.Fecha', $hoy->year)
            ->select(
                'empleados.Nombre',
                DB::raw('COUNT(ventas.id) as total_ventas'),
                DB::raw('SUM(ventas.Total) as total_ingresos')
            )
            ->groupBy('empleados.id', 'empleados.Nombre')
            ->orderBy('total_ingresos', 'desc')
            ->limit(5)
            ->get();

        return view('ventas.dashboard', compact(
            'totalHoy', 'totalSemana', 'totalMes', 'productosTop', 'empleadosTop'
        ));
    }

    /**
     * Obtener información de un producto para AJAX
     */
    public function getProductoInfo($id)
    {
        $producto = Producto::findOrFail($id);

        return response()->json([
            'id' => $producto->id,
            'nombre' => $producto->Nombre,
            'precio' => $producto->Precio,
            'stock' => $producto->Cantidad,
            'success' => true
        ]);
    }

    /**
     * Anular una venta (similar a eliminar pero con estado)
     */
    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::with('detalleVentas')->findOrFail($id);

            // Restaurar stock
            foreach ($venta->detalleVentas as $detalle) {
                Producto::where('id', $detalle->Producto)
                       ->increment('Cantidad', $detalle->Cantidad);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                           ->with('success', 'Venta anulada exitosamente. Stock restaurado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al anular la venta: ' . $e->getMessage());
        }
    }
}