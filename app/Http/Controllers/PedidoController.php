<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PedidoController extends Controller
{
    public function index(Request $request){
        // Consulta base con relaciones
        $query = Pedido::with(['cliente', 'empleado', 'detallePedidos.producto']);
        
        // ========== APLICAR FILTROS ==========
        
        // 1. Filtro por ID de pedido (exacto)
        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }
        
        // 2. Filtro por fecha específica
        if ($request->filled('fecha')) {
            $query->whereDate('Fecha_entrega', $request->input('fecha'));
        }
        
        // 3. Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('Fecha_entrega', '>=', $request->input('fecha_desde'));
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('Fecha_entrega', '<=', $request->input('fecha_hasta'));
        }
        
        // 4. Filtro por cliente
        if ($request->filled('cliente_id')) {
            $query->where('Cliente_idCliente', $request->input('cliente_id'));
        }
        
        // 5. Filtro por empleado
        if ($request->filled('empleado_id')) {
            $query->where('Empleado_idEmpleado', $request->input('empleado_id'));
        }
        
        // 6. Filtro por estado
        if ($request->filled('estado')) {
            $query->where('Estado', $request->input('estado'));
        }
        
        // 7. Filtro por monto mínimo
        if ($request->filled('monto_min')) {
            $query->where('Total', '>=', $request->input('monto_min'));
        }
        
        // 8. Filtro por monto máximo
        if ($request->filled('monto_max')) {
            $query->where('Total', '<=', $request->input('monto_max'));
        }
        
        // 9. Filtro por producto (búsqueda en nombre del producto)
        if ($request->filled('producto')) {
            $productoNombre = $request->input('producto');
            $query->whereHas('detallePedidos.producto', function($q) use ($productoNombre) {
                $q->where('Nombre', 'like', "%{$productoNombre}%");
            });
        }
        
        // ========== ORDENAMIENTO ==========
        $sortBy = $request->input('sort_by', 'Fecha_entrega');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Validar campos de ordenamiento
        $allowedSorts = ['id', 'Fecha_entrega', 'Total', 'Estado'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Fecha_entrega';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // ========== OBTENER DATOS ==========
        
        // Datos para estadísticas (sin paginar, con filtros)
        $pedidosFiltrados = $query->get();
        
        // Datos para tabla (paginar con filtros)
        $pedidosPaginated = $query->paginate(15)->withQueryString();
        
        // Clientes para dropdown
        $clientes = Cliente::orderBy('Nombre')->get();
        
        // Empleados para dropdown
        $empleados = Empleado::orderBy('Nombre')->get();
        
        return view('pedidos.index', compact('pedidosFiltrados', 'pedidosPaginated', 'clientes', 'empleados'));
    }

    public function create(){
        $clientes = Cliente::where('estado', 1)  // Solo clientes activos
                    ->orderBy('Nombre')
                    ->get();
        
        $empleados = Empleado::orderBy('Nombre')  // Si también quieres empleados activos
                    ->where('estado', 1)          // Agrega esta línea si aplica
                    ->get();
        
        $productos = Producto::where('Cantidad', '>', 0)  // Stock disponible
                    ->where('estado', 1)                  // Solo productos activos
                    ->orderBy('Nombre')
                    ->get();
        
        return view('pedidos.create', compact('clientes', 'empleados', 'productos'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'Cliente_idCliente' => 'required|exists:clientes,id',
                'Empleado_idEmpleado' => 'required|exists:empleados,id',
                'Fecha_entrega' => 'required|date',
                'Hora_entrega' => 'required',
                'Lugar_entrega' => 'required',
                'Prioridad' => 'required|in:Baja,Media,Alta,Urgente',
                'comentario' => 'nullable|string|max:500',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            $total = 0;
            $detallesPedido = [];

            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);
                
                // Verificar stock disponible
                if ($producto->Cantidad < $productoData['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->Nombre}. Disponible: {$producto->Cantidad}, Solicitado: {$productoData['cantidad']}");
                }
                
                $subtotal = $producto->Precio * $productoData['cantidad'];
                $total += $subtotal;

                $detallesPedido[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $productoData['cantidad'],
                    'PrecioUnitario' => $producto->Precio
                ];
            }

            // Crear el pedido con estado "En proceso"
            $pedido = Pedido::create([
                'Fecha_entrega' => $request->Fecha_entrega,
                'Hora_entrega' => $request->Hora_entrega,
                'Lugar_entrega' => $request->Lugar_entrega,
                'Estado' => 'En proceso', // AHORA ES "En proceso"
                'Prioridad' => $request->Prioridad,
                'Total' => $total,
                'Cliente_idCliente' => $request->Cliente_idCliente,
                'Empleado_idEmpleado' => $request->Empleado_idEmpleado,
                'comentario' => $request->comentario
            ]);

            // Crear detalles del pedido
            foreach ($detallesPedido as $detalle) {
                DetallePedido::create([
                    'Producto' => $detalle['Producto'],
                    'Pedido' => $pedido->id,
                    'Cantidad' => $detalle['Cantidad'],
                    'PrecioUnitario' => $detalle['PrecioUnitario']
                ]);
            }

            // **DESCONTAR STOCK INMEDIATAMENTE** (porque el pedido ya está en proceso)
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                $producto->decrement('Cantidad', $detalle['Cantidad']);
                Log::info("Stock descontado para producto ID {$detalle['Producto']}: {$detalle['Cantidad']} unidades (Pedido #{$pedido->id})");
            }

            DB::commit();

            return redirect()->route('pedidos.index')
                        ->with('success', '✅ Pedido #' . $pedido->id . ' creado exitosamente. Stock reservado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear pedido: ' . $e->getMessage());
            return back()->with('error', '❌ Error al crear el pedido: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id){
        $pedido = Pedido::with('detallePedidos.producto')->findOrFail($id);
        
        // Validar que se pueda editar
        if (!in_array($pedido->Estado, ['En proceso', 'Pendiente'])) {
            return redirect()->route('pedidos.index')
                ->with('error', '❌ No se puede editar un pedido ' . $pedido->Estado);
        }
        
        // Solo clientes activos
        $clientes = Cliente::where('estado', 1)
                    ->orderBy('Nombre')
                    ->get();
        
        // Empleados activos
        $empleados = Empleado::where('estado', 1)
                    ->orderBy('Nombre')
                    ->get();
        
        // Productos activos con la condición especial para el pedido actual
        $productos = Producto::where('estado', 1)
                    ->where(function($query) use ($pedido) {
                        $query->where('Cantidad', '>', 0)  // Con stock disponible
                            ->orWhereHas('detallePedidos', function($subquery) use ($pedido) {
                                $subquery->where('Pedido', $pedido->id);  // O que están en el pedido actual
                            });
                    })
                    ->orderBy('Nombre')
                    ->get();

        return view('pedidos.edit', compact('pedido', 'clientes', 'empleados', 'productos'));
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Cliente_idCliente' => 'required|exists:clientes,id',
            'Empleado_idEmpleado' => 'required|exists:empleados,id',
            'Fecha_entrega' => 'required|date',
            'Hora_entrega' => 'required',
            'Lugar_entrega' => 'required|string|max:200',
            'Prioridad' => 'required|string|in:Baja,Media,Alta,Urgente',
            'comentario' => 'nullable|string|max:500',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1|max:1000',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $pedido = Pedido::with('detallePedidos')->findOrFail($id);

            // Validar que se pueda actualizar
            if (!in_array($pedido->Estado, ['En proceso', 'Pendiente'])) {
                throw new \Exception('No se puede actualizar un pedido ' . $pedido->Estado);
            }

            // Guardar el estado actual
            $estadoActual = $pedido->Estado;

            // ===== RESTAURAR STOCK ANTIGUO =====
            foreach ($pedido->detallePedidos as $detalleAntiguo) {
                $producto = Producto::find($detalleAntiguo->Producto);
                if ($producto) {
                    $producto->increment('Cantidad', $detalleAntiguo->Cantidad);
                    Log::info("Stock restaurado para producto {$producto->Nombre}: {$detalleAntiguo->Cantidad} unidades");
                }
            }

            // Eliminar los detalles antiguos
            $pedido->detallePedidos()->delete();

            $total = 0;
            $detallesPedido = [];

            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);
                
                // Verificar stock disponible
                if ($producto->Cantidad < $productoData['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->Nombre}. Disponible: {$producto->Cantidad}");
                }
                
                $subtotal = $productoData['precio_unitario'] * $productoData['cantidad'];
                $total += $subtotal;

                $detallesPedido[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $productoData['cantidad'],
                    'PrecioUnitario' => $productoData['precio_unitario']
                ];
            }

            // Actualizar el pedido (MANTENIENDO EL ESTADO ACTUAL)
            $pedido->update([
                'Fecha_entrega' => $request->Fecha_entrega,
                'Hora_entrega' => $request->Hora_entrega,
                'Lugar_entrega' => $request->Lugar_entrega,
                'Prioridad' => $request->Prioridad,
                'Total' => $total,
                'Cliente_idCliente' => $request->Cliente_idCliente,
                'Empleado_idEmpleado' => $request->Empleado_idEmpleado,
                'comentario' => $request->comentario
            ]);

            // Crear nuevos detalles del pedido
            foreach ($detallesPedido as $detalle) {
                DetallePedido::create([
                    'Producto' => $detalle['Producto'],
                    'Pedido' => $pedido->id,
                    'Cantidad' => $detalle['Cantidad'],
                    'PrecioUnitario' => $detalle['PrecioUnitario']
                ]);
            }

            // ===== DESCONTAR NUEVO STOCK =====
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                $producto->decrement('Cantidad', $detalle['Cantidad']);
                Log::info("Stock descontado para producto {$producto->Nombre}: {$detalle['Cantidad']} unidades");
            }

            DB::commit();

            return redirect()->route('pedidos.index')
                           ->with('success', '✅ Pedido #' . $pedido->id . ' actualizado exitosamente. Stock ajustado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar pedido: ' . $e->getMessage());
            return back()->with('error', '❌ Error al actualizar el pedido: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Método para cambiar el estado del pedido
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'Estado' => 'required|string|in:En proceso,Completado,Cancelado'
        ]);

        try {
            DB::beginTransaction();

            $pedido = Pedido::with('detallePedidos.producto')->findOrFail($id);
            $estadoAnterior = $pedido->Estado;
            $nuevoEstado = $request->Estado;
            
            $venta = null;

            // Validaciones
            if ($estadoAnterior === 'Cancelado' && $nuevoEstado !== 'Cancelado') {
                throw new \Exception('No se puede modificar un pedido cancelado.');
            }

            if ($estadoAnterior === 'Completado' && $nuevoEstado !== 'Completado') {
                throw new \Exception('No se puede modificar un pedido completado.');
            }

            // Actualizar el estado
            $pedido->update(['Estado' => $nuevoEstado]);

            $detalles = $pedido->detallePedidos;
            
            // CASO: COMPLETAR PEDIDO
            if ($nuevoEstado === 'Completado' && $estadoAnterior !== 'Completado') {
                // Verificar stock (por si acaso)
                foreach ($detalles as $detalle) {
                    $producto = Producto::find($detalle->Producto);
                    if ($producto && $producto->Cantidad < 0) {
                        throw new \Exception("Error de inventario para {$producto->Nombre}");
                    }
                }
                
                // Crear venta automáticamente
                $venta = $this->crearVentaDesdePedido($pedido);
            }
            
            // CASO: CANCELAR PEDIDO
            elseif ($nuevoEstado === 'Cancelado' && $estadoAnterior !== 'Cancelado') {
                
                // Restaurar el stock
                foreach ($detalles as $detalle) {
                    $producto = Producto::find($detalle->Producto);
                    if ($producto) {
                        $producto->increment('Cantidad', $detalle->Cantidad);
                        Log::info("Stock restaurado para producto {$producto->Nombre}: {$detalle->Cantidad} unidades (pedido cancelado)");
                    }
                }
            }

            DB::commit();

            // Mensajes personalizados
            $mensaje = '';
            switch($nuevoEstado) {
                case 'Completado':
                    $mensaje = '✅ Pedido #' . $pedido->id . ' completado exitosamente.';
                    if ($venta) {
                        $mensaje .= ' Se generó la venta #' . $venta->id . ' por $' . number_format($pedido->Total, 2);
                    }
                    break;
                    
                case 'Cancelado':
                    $mensaje = '⛔ Pedido #' . $pedido->id . ' ha sido cancelado. Stock liberado.';
                    break;
                    
                case 'En proceso':
                    $mensaje = '⚙️ Pedido #' . $pedido->id . ' ahora está en proceso.';
                    break;
            }

            return redirect()->route('pedidos.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cambiar estado del pedido: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Crear una venta automáticamente cuando un pedido se completa
     */
    private function crearVentaDesdePedido($pedido)
    {
        try {
            // Crear la venta
            $fechaHoraActual = Carbon::now('America/Mexico_City');
            
            Log::info('Creando venta para pedido ID: ' . $pedido->id . ' - Total: $' . $pedido->Total);
            
            $venta = Venta::create([
                'Fecha' => $fechaHoraActual,
                'Total' => $pedido->Total,
                'Empleado_idEmpleado' => $pedido->Empleado_idEmpleado
            ]);

            Log::info('Venta creada con ID: ' . $venta->id);

            // Crear los detalles de la venta
            foreach ($pedido->detallePedidos as $detalle) {
                DetalleVenta::create([
                    'Producto' => $detalle->Producto,
                    'Venta' => $venta->id,
                    'Cantidad' => $detalle->Cantidad
                ]);
                
                Log::info("Detalle de venta creado: Producto {$detalle->Producto}, Cantidad {$detalle->Cantidad}");
            }

            return $venta;

        } catch (\Exception $e) {
            Log::error('Error al crear venta desde pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedido::with('detallePedidos')->findOrFail($id);

            // Validaciones
            if ($pedido->Estado === 'Completado') {
                return back()->with('error', '❌ No se puede eliminar un pedido completado.');
            }

            if ($pedido->Estado === 'Cancelado') {
                return back()->with('error', '❌ No se puede eliminar un pedido cancelado.');
            }

            // Restaurar stock si está en proceso
            if ($pedido->Estado === 'En proceso') {
                foreach ($pedido->detallePedidos as $detalle) {
                    $producto = Producto::find($detalle->Producto);
                    if ($producto) {
                        $producto->increment('Cantidad', $detalle->Cantidad);
                    }
                }
            }

            // Eliminar detalles y pedido
            $pedido->detallePedidos()->delete();
            $pedido->delete();

            DB::commit();

            return redirect()->route('pedidos.index')
                           ->with('success', '✅ Pedido eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar pedido: ' . $e->getMessage());
            return back()->with('error', '❌ Error al eliminar el pedido: ' . $e->getMessage());
        }
    }

    public function getDetalles($id){
        try {
            $pedido = Pedido::with(['detallePedidos.producto'])->findOrFail($id);
            
            $detalles = $pedido->detallePedidos->map(function ($detalle) {
                $subtotal = $detalle->Cantidad * $detalle->PrecioUnitario;
                
                return [
                    'id' => $detalle->id,
                    'Producto' => $detalle->Producto,
                    'Cantidad' => $detalle->Cantidad,
                    'PrecioUnitario' => '$' . number_format($detalle->PrecioUnitario, 2),
                    'Subtotal' => '$' . number_format($subtotal, 2),
                    'producto' => [
                        'id' => $detalle->producto->id ?? null,
                        'Nombre' => $detalle->producto->Nombre ?? 'Producto no encontrado',
                        'Descripcion' => $detalle->producto->Descripcion ?? '',
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'detalles' => $detalles,
                'comentario' => $pedido->comentario
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }
}