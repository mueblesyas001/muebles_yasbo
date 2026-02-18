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
        $query = Producto::with('categoria', 'detalleVentas', 'detalleCompras');
        
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
            'Cantidad_maxima' => 'required|integer|min:0',
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
            'Cantidad_maxima' => 'required|integer|min:0',
            'Categoria' => 'required|exists:categoria,id'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}