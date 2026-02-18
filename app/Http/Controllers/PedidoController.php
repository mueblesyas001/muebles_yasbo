<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
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

    public function create()
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        $empleados = Empleado::orderBy('Nombre')->get();
        $productos = Producto::where('Cantidad', '>', 0)->orderBy('Nombre')->get();
        
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
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            $total = 0;
            $detallesPedido = [];

            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);
                $subtotal = $producto->Precio * $productoData['cantidad'];
                $total += $subtotal;

                $detallesPedido[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $productoData['cantidad'],
                    'PrecioUnitario' => $producto->Precio
                ];
            }

            $pedido = Pedido::create([
                'Fecha_entrega' => $request->Fecha_entrega,
                'Hora_entrega' => $request->Hora_entrega,
                'Lugar_entrega' => $request->Lugar_entrega,
                'Estado' => 'Pendiente',
                'Prioridad' => $request->Prioridad,
                'Total' => $total,
                'Cliente_idCliente' => $request->Cliente_idCliente,
                'Empleado_idEmpleado' => $request->Empleado_idEmpleado
            ]);

            foreach ($detallesPedido as $detalle) {
                DetallePedido::create([
                    'Producto' => $detalle['Producto'],
                    'Pedido' => $pedido->id, // Cambiado de idPedido a id
                    'Cantidad' => $detalle['Cantidad'],
                    'PrecioUnitario' => $detalle['PrecioUnitario']
                ]);
            }

            DB::commit();

            return redirect()->route('pedidos.index')
                        ->with('success', 'Pedido creado exitosamente. ID: ' . $pedido->id); // Cambiado de idPedido a id

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el pedido: ' . $e->getMessage())->withInput();
        }
    }

        public function edit($id){
            $pedido = Pedido::with('detallePedidos.producto')->findOrFail($id);
            $clientes = Cliente::orderBy('Nombre')->get();
            $empleados = Empleado::orderBy('Nombre')->get();
            $productos = Producto::where('Cantidad', '>', 0)
                            ->orWhereHas('detallePedidos', function($query) use ($pedido) {
                                $query->where('Pedido', $pedido->id); // Cambiado de idPedido a id
                            })
                            ->orderBy('Nombre')
                            ->get();

            return view('pedidos.edit', compact('pedido', 'clientes', 'empleados', 'productos'));
        }

    public function update(Request $request, $id){
    $validated = $request->validate([
        'Cliente_idCliente' => 'required|exists:clientes,id',
        'Empleado_idEmpleado' => 'required|exists:empleados,id',
        'Fecha_entrega' => 'required|date',
        'Hora_entrega' => 'required',
        'Lugar_entrega' => 'required|string|max:200',
        'Prioridad' => 'required|string|in:Baja,Media,Alta,Urgente',
        'Estado' => 'required|string|in:Pendiente,En proceso,Completado,Cancelado',
        'productos' => 'required|array|min:1',
        'productos.*.id' => 'required|exists:productos,id',
        'productos.*.cantidad' => 'required|integer|min:1|max:1000',
        'productos.*.precio_unitario' => 'required|numeric|min:0'
    ]);

    try {
        DB::beginTransaction();

        $pedido = Pedido::with('detallePedidos')->findOrFail($id);

        // Obtener el estado anterior antes de cualquier cambio
        $estadoAnterior = $pedido->Estado;
        
        // REGLAS DE VALIDACIÓN DE ESTADO CORREGIDAS:
        
        // 1. Si el pedido está Cancelado, solo permitir mantener Cancelado
        if ($estadoAnterior === 'Cancelado' && $request->Estado !== 'Cancelado') {
            throw new \Exception('No se puede modificar un pedido cancelado. Solo puede mantenerse como Cancelado.');
        }
        
        // 2. Si el pedido está Completado, solo permitir mantener Completado o cambiar a Cancelado
        if ($estadoAnterior === 'Completado' && !in_array($request->Estado, ['Completado', 'Cancelado'])) {
            throw new \Exception('No se puede reabrir un pedido completado. Solo puede mantenerse como Completado o cambiarse a Cancelado.');
        }
        
        // 3. Si se intenta cambiar a Cancelado y está Completado, no permitir
        if ($estadoAnterior === 'Completado' && $request->Estado === 'Cancelado') {
            throw new \Exception('No se puede cancelar un pedido ya completado.');
        }

        // Guardar los detalles antiguos para restaurar stock si es necesario
        $detallesAntiguos = $pedido->detallePedidos->toArray();

        // Eliminar los detalles actuales
        $pedido->detallePedidos()->delete();

        $total = 0;
        $detallesPedido = [];

        foreach ($request->productos as $productoData) {
            $producto = Producto::findOrFail($productoData['id']);
            
            // Usar el precio_unitario enviado desde el formulario
            $precioUnitario = $productoData['precio_unitario'];
            $cantidad = $productoData['cantidad'];
            $subtotal = $precioUnitario * $cantidad;
            $total += $subtotal;

            $detallesPedido[] = [
                'Producto' => $productoData['id'],
                'Cantidad' => $cantidad,
                'PrecioUnitario' => $precioUnitario,
                'Subtotal' => $subtotal
            ];
        }

        // Actualizar el pedido CON EL ESTADO
        $pedido->update([
            'Fecha_entrega' => $request->Fecha_entrega,
            'Hora_entrega' => $request->Hora_entrega,
            'Lugar_entrega' => $request->Lugar_entrega,
            'Prioridad' => $request->Prioridad,
            'Estado' => $request->Estado,
            'Total' => $total,
            'Cliente_idCliente' => $request->Cliente_idCliente,
            'Empleado_idEmpleado' => $request->Empleado_idEmpleado
        ]);

        // Crear nuevos detalles del pedido
        foreach ($detallesPedido as $detalle) {
            DetallePedido::create([
                'Producto' => $detalle['Producto'],
                'Pedido' => $pedido->id,
                'Cantidad' => $detalle['Cantidad'],
                'PrecioUnitario' => $detalle['PrecioUnitario'],
                'Subtotal' => $detalle['Subtotal']
            ]);
        }
        
        // LÓGICA DE GESTIÓN DE STOCK MEJORADA:
        
        // Caso 1: Si se marca como Completado (desde cualquier estado excepto Completado)
        if ($request->Estado === 'Completado' && $estadoAnterior !== 'Completado') {
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                if ($producto) {
                    // Restar la cantidad del stock
                    $producto->decrement('Cantidad', $detalle['Cantidad']);
                }
            }
        }
        
        // Caso 2: Si se cancela y estaba En proceso
        elseif ($request->Estado === 'Cancelado' && $estadoAnterior === 'En proceso') {
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                if ($producto) {
                    // Restaurar el stock que se había reservado
                    $producto->increment('Cantidad', $detalle['Cantidad']);
                }
            }
        }
        
        // Caso 3: Si se cambia de Completado a Cancelado
        elseif ($request->Estado === 'Cancelado' && $estadoAnterior === 'Completado') {
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                if ($producto) {
                    // Restaurar el stock que se había descontado al completar
                    $producto->increment('Cantidad', $detalle['Cantidad']);
                }
            }
        }
        
        // Caso 4: Si se cambia de Pendiente a En proceso (reservar stock)
        elseif ($request->Estado === 'En proceso' && $estadoAnterior === 'Pendiente') {
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                if ($producto) {
                    // Reservar stock (reducir cantidad disponible)
                    $producto->decrement('Cantidad', $detalle['Cantidad']);
                }
            }
        }
        
        // Caso 5: Si se cambia de En proceso a Pendiente (liberar stock)
        elseif ($request->Estado === 'Pendiente' && $estadoAnterior === 'En proceso') {
            foreach ($detallesPedido as $detalle) {
                $producto = Producto::find($detalle['Producto']);
                if ($producto) {
                    // Liberar stock que estaba reservado
                    $producto->increment('Cantidad', $detalle['Cantidad']);
                }
            }
        }

        DB::commit();

        return redirect()->route('pedidos.index')
                       ->with('success', 'Pedido actualizado exitosamente. Estado: ' . $request->Estado);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar el pedido: ' . $e->getMessage())->withInput();
    }
}

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedido::with('detallePedidos')->findOrFail($id);

            if ($pedido->Estado === 'Completado') {
                return back()->with('error', 'No se puede eliminar un pedido completado.');
            }

            if ($pedido->Estado === 'Cancelado') {
                return back()->with('error', 'No se puede eliminar un pedido cancelado.');
            }

            $pedido->detallePedidos()->delete();
            $pedido->delete();

            DB::commit();

            return redirect()->route('pedidos.index')
                           ->with('success', 'Pedido eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
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
                'detalles' => $detalles
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }
}