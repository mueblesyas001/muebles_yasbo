<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las categorías para el select
        $categorias = Categoria::all();
        
        // Iniciar query con eager loading
        $query = Producto::with('categoria', 'detalleVentas', 'detalleCompras', 'detallePedidos');
        
        // Aplicar filtros
        // Filtro por ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        
        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('Nombre', 'like', '%' . $request->nombre . '%');
        }
        
        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
        }
        
        // Filtro por rango de precio
        if ($request->filled('precio_min')) {
            $query->where('Precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('Precio', '<=', $request->precio_max);
        }
        
        // Filtro por rango de stock
        if ($request->filled('stock_min')) {
            $query->where('Cantidad', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('Cantidad', '<=', $request->stock_max);
        }
        
        // Filtro por estado de stock
        if ($request->filled('estado_stock')) {
            switch ($request->estado_stock) {
                case 'en_stock':
                    $query->where('Cantidad', '>', 0);
                    break;
                case 'agotado':
                    $query->where('Cantidad', '=', 0);
                    break;
                case 'bajo_stock':
                    // Usamos whereRaw para comparar Cantidad con Cantidad_minima
                    $query->whereRaw('Cantidad <= Cantidad_minima AND Cantidad > 0');
                    break;
            }
        }
        
        // Ordenamiento
        $sort_by = $request->get('sort_by', 'Nombre');
        $sort_order = $request->get('sort_order', 'asc');
        $query->orderBy($sort_by, $sort_order);
        
        // Obtener productos filtrados (para estadísticas)
        $productosFiltrados = $query->get();
        
        // Calcular estadísticas para la vista
        $enStockCount = $productosFiltrados->where('Cantidad', '>', 0)->count();
        $agotadosCount = $productosFiltrados->where('Cantidad', '=', 0)->count();
        $bajoStockCount = $productosFiltrados->filter(function($producto) {
            return $producto->Cantidad > 0 && $producto->Cantidad <= $producto->Cantidad_minima;
        })->count();
        
        // Paginación (necesitamos una nueva consulta para la paginación)
        $queryForPagination = clone $query;
        $perPage = 10;
        $productosPaginated = $queryForPagination->paginate($perPage)
            ->appends($request->except('page'));
        
        return view('productos.index', compact(
            'categorias',
            'productosFiltrados',
            'productosPaginated',
            'enStockCount',
            'agotadosCount',
            'bajoStockCount'
        ));
    }

    public function create()
    {
        $categorias = Categoria::all();
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
            'Categoria' => 'required|exists:categoria,id'
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
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
            'Categoria' => 'required|exists:categoria,id'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Verificar si un producto puede ser eliminado antes de mostrar el modal
     */
    public function verificarAntesDeEliminar($id)
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
            
            $puedeEliminar = ($ventasCount == 0 && $comprasCount == 0 && $pedidosActivosCount == 0);
            
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
                $detalles[] = "Está incluido en {$pedidosActivosCount} pedido(s)";
            }
            
            return response()->json([
                'success' => true,
                'puede_eliminar' => $puedeEliminar,
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

    public function destroy($id)
    {
        try {
            $producto = Producto::with(['detalleVentas', 'detalleCompras', 'detallePedidos'])->findOrFail($id);
            
            // Verificar si tiene ventas asociadas
            $ventasCount = $producto->detalleVentas()->count();
            if ($ventasCount > 0) {
                return redirect()->route('productos.index')
                    ->with('foreign_key_error', 'No se puede eliminar el producto "' . $producto->Nombre . '" porque está registrado en ' . $ventasCount . ' venta(s).')
                    ->with('producto_nombre', $producto->Nombre)
                    ->with('ventas_count', $ventasCount);
            }
            
            // Verificar si tiene compras asociadas
            $comprasCount = $producto->detalleCompras()->count();
            if ($comprasCount > 0) {
                return redirect()->route('productos.index')
                    ->with('foreign_key_error', 'No se puede eliminar el producto "' . $producto->Nombre . '" porque está registrado en ' . $comprasCount . ' compra(s).')
                    ->with('producto_nombre', $producto->Nombre)
                    ->with('compras_count', $comprasCount);
            }
            
            // Verificar si tiene pedidos asociados
            $pedidosCount = $producto->detallePedidos()->count();
            if ($pedidosCount > 0) {
                return redirect()->route('productos.index')
                    ->with('foreign_key_error', 'No se puede eliminar el producto "' . $producto->Nombre . '" porque está incluido en ' . $pedidosCount . ' pedido(s).')
                    ->with('producto_nombre', $producto->Nombre)
                    ->with('pedidos_count', $pedidosCount);
            }
            
            // Si no tiene relaciones, proceder con la eliminación
            $producto->delete();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto "' . $producto->Nombre . '" eliminado correctamente.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar error de foreign key
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                $producto = Producto::find($id);
                $nombreProducto = $producto ? $producto->Nombre : 'desconocido';
                
                return redirect()->route('productos.index')
                    ->with('foreign_key_error', 'No se puede eliminar el producto "' . $nombreProducto . '" porque tiene registros asociados en el sistema.')
                    ->with('producto_nombre', $nombreProducto);
            }
            
            return redirect()->route('productos.index')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }
}