<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request){
        $categorias = Categoria::all();
        
        // IMPORTANTE: No filtramos por estado, traemos TODOS los productos
        $query = Producto::with('categoria');
        
        // Aplicar filtros de búsqueda
        if ($request->filled('nombre')) {
            $search = $request->nombre;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                ->orWhere('Descripcion', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        if ($request->filled('precio_min')) {
            $query->where('Precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('Precio', '<=', $request->precio_max);
        }
        
        if ($request->filled('stock_min')) {
            $query->where('Cantidad', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('Cantidad', '<=', $request->stock_max);
        }
        
        if ($request->filled('estado_stock')) {
            switch ($request->estado_stock) {
                case 'en_stock':
                    $query->where('Cantidad', '>', 0);
                    break;
                case 'agotado':
                    $query->where('Cantidad', '=', 0);
                    break;
                case 'bajo_stock':
                    $query->whereRaw('Cantidad <= Cantidad_minima AND Cantidad > 0');
                    break;
            }
        }
        
        // FILTRO POR ESTADO (activos/inactivos) - AHORA SÍ LO INCLUIMOS
        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->where('estado', 1);
            } elseif ($request->estado === 'inactivos') {
                $query->where('estado', 0);
            }
            // Si no se especifica, NO FILTRAMOS, mostramos TODOS
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'Nombre');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['Nombre', 'Precio', 'Cantidad', 'id', 'Categoria', 'estado'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Nombre';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // CLONAMOS EL QUERY PARA ESTADÍSTICAS (TAMBIÉN SIN FILTRO DE ESTADO)
        $queryForStats = clone $query;
        $productosFiltrados = $queryForStats->get();
        
        // Calcular estadísticas
        $stats = [
            'total' => $productosFiltrados->count(),
            'activos' => $productosFiltrados->where('estado', 1)->count(),
            'inactivos' => $productosFiltrados->where('estado', 0)->count(),
            'en_stock' => $productosFiltrados->where('Cantidad', '>', 0)->count(),
            'agotados' => $productosFiltrados->where('Cantidad', '=', 0)->count(),
            'bajo_stock' => $productosFiltrados->filter(function($producto) {
                return $producto->Cantidad > 0 && $producto->Cantidad <= $producto->Cantidad_minima;
            })->count(),
            'total_unidades' => $productosFiltrados->sum('Cantidad')
        ];
        
        // PAGINACIÓN - SIN FILTRO DE ESTADO (TODOS LOS PRODUCTOS)
        $productosPaginated = $query->paginate(10)->appends($request->except('page'));
        
        return view('productos.index', compact(
            'categorias',
            'productosPaginated',
            'productosFiltrados',
            'stats'
        ));
    }

    public function create(){
        $categorias = Categoria::where('estado', 1)->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:200',
            'Precio' => 'required|numeric|min:0',
            'Cantidad' => 'required|integer|min:0',
            'Cantidad_minima' => 'required|integer|min:0',
            'Cantidad_maxima' => 'required|integer|min:0|gte:Cantidad_minima',
            'Categoria' => 'required|exists:categoria,id',
            'estado' => 'sometimes|boolean'
        ]);

        $data = $request->all();
        
        // Si no se envía el estado, por defecto será 1 (activo)
        if (!isset($data['estado'])) {
            $data['estado'] = 1;
        }

        Producto::create($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto "' . $request->Nombre . '" creado exitosamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('estado', 1)->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:200',
            'Precio' => 'required|numeric|min:0',
            'Cantidad' => 'required|integer|min:0',
            'Cantidad_minima' => 'required|integer|min:0',
            'Cantidad_maxima' => 'required|integer|min:0|gte:Cantidad_minima',
            'Categoria' => 'required|exists:categoria,id',
            'estado' => 'sometimes|boolean'
        ]);

        $producto = Producto::findOrFail($id);
        $data = $request->all();
        
        // Si no se envía el estado en el formulario, mantener el valor actual
        if (!isset($data['estado'])) {
            $data['estado'] = $producto->estado;
        }
        
        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto "' . $producto->Nombre . '" actualizado exitosamente.');
    }

    /**
     * Desactivar producto (cambia estado a 0) - SIN VERIFICAR RELACIONES
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            
            // Cambiar el estado a 0 (inactivo) en lugar de eliminar
            $producto->estado = 0;
            $producto->save();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto "' . $producto->Nombre . '" desactivado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Error al desactivar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si un producto puede ser desactivado (para mostrar información, pero no bloquea)
     */
    public function verificarAntesDeDesactivar($id)
    {
        try {
            $producto = Producto::with(['detalleVentas', 'detalleCompras', 'detallePedidos.pedido'])->findOrFail($id);
            
            $ventasCount = $producto->detalleVentas()->count();
            $comprasCount = $producto->detalleCompras()->count();
            
            $pedidosActivosCount = $producto->detallePedidos()
                ->whereHas('pedido', function($query) {
                    $query->whereIn('Estado', ['Pendiente', 'En proceso', 'Completado']);
                })
                ->count();
            
            $detalles = [];
            $motivos = [
                'ventas' => $ventasCount,
                'compras' => $comprasCount,
                'pedidos' => $pedidosActivosCount
            ];
            
            if ($ventasCount > 0) {
                $detalles[] = "Está registrado en {$ventasCount} venta(s)";
            }
            if ($comprasCount > 0) {
                $detalles[] = "Está registrado en {$comprasCount} compra(s)";
            }
            if ($pedidosActivosCount > 0) {
                $detalles[] = "Está incluido en {$pedidosActivosCount} pedido(s) activo(s)";
            }
            
            return response()->json([
                'success' => true,
                'tieneRelaciones' => ($ventasCount > 0 || $comprasCount > 0 || $pedidosActivosCount > 0),
                'motivos' => $motivos,
                'detalles' => $detalles,
                'nombre' => $producto->Nombre
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activar producto
     */
    public function activar($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->estado = 1;
            $producto->save();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto "' . $producto->Nombre . '" activado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Error al activar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar el estado de un producto (Activar/Desactivar)
     */
    public function toggleEstado($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->estado = !$producto->estado;
            $producto->save();
            
            $accion = $producto->estado ? 'activado' : 'desactivado';
            
            return redirect()->route('productos.index')
                ->with('success', "Producto '{$producto->Nombre}' {$accion} correctamente.");
                
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Error al cambiar el estado del producto: ' . $e->getMessage());
        }
    }
}