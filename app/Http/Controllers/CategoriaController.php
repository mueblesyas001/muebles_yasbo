<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
   public function index(Request $request){
        // Consulta base con relaciones y conteo de productos
        $query = Categoria::with(['proveedor'])
                        ->withCount('productos'); // Esto agrega productos_count
        
        // ========== APLICAR FILTROS ==========
        
        // 1. Filtro por búsqueda (nombre o descripción)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'like', "%{$search}%")
                ->orWhere('Descripcion', 'like', "%{$search}%");
            });
        }
        
        // 2. Filtro por proveedor
        if ($request->filled('proveedor_id')) {
            $query->where('Proveedor_idProveedor', $request->input('proveedor_id'));
        }
        
        // 3. Filtro por categorías con/sin proveedor
        if ($request->filled('tipo_proveedor')) {
            if ($request->input('tipo_proveedor') === 'con') {
                $query->whereNotNull('Proveedor_idProveedor');
            } elseif ($request->input('tipo_proveedor') === 'sin') {
                $query->whereNull('Proveedor_idProveedor');
            }
        }
        
        // 4. Filtro por productos (categorías con productos)
        if ($request->filled('con_productos')) {
            if ($request->input('con_productos') === 'si') {
                $query->has('productos');
            } elseif ($request->input('con_productos') === 'no') {
                $query->doesntHave('productos');
            }
        }
        
        // ========== ORDENAMIENTO ==========
        $sortBy = $request->input('sort_by', 'Nombre');
        $sortOrder = $request->input('sort_order', 'asc');
        
        // Validar campos de ordenamiento
        $allowedSorts = ['id', 'Nombre', 'created_at', 'productos_count'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Nombre';
        }
        
        // Ordenar por productos_count de manera especial
        if ($sortBy === 'productos_count') {
            $query->orderBy('productos_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        // ========== OBTENER DATOS ==========
        
        // Obtener categorías paginadas (si quieres paginación)
        $categorias = $query->get();
        
        // Obtener estadísticas para los filtros
        $proveedores = Proveedor::all();
        
        // Calcular estadísticas
        $estadisticas = [
            'total' => $categorias->count(),
            'con_proveedor' => $categorias->whereNotNull('Proveedor_idProveedor')->count(),
            'sin_proveedor' => $categorias->whereNull('Proveedor_idProveedor')->count(),
            'con_productos' => $categorias->where('productos_count', '>', 0)->count(),
            'sin_productos' => $categorias->where('productos_count', 0)->count(),
        ];
        
        return view('categorias.index', compact('categorias', 'proveedores', 'estadisticas'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('categorias.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'required|string|max:200',
            'Proveedor' => 'required|exists:proveedores,id'
        ]);

        Categoria::create($request->all());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit($id)
    {
        $proveedores = Proveedor::all();
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'required|string|max:200',
            'Proveedor' => 'required|exists:proveedores,id'
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}