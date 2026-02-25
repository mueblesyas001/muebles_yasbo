<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class CategoriaController extends Controller{
    public function index(Request $request){
        $query = Categoria::with('proveedor')->withCount('productos');
        
        // Filtro por nombre/descripción
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('Descripcion', 'LIKE', "%{$search}%");
            });
        }
        
        // FILTRO POR PROVEEDOR - CORREGIDO: usar 'Proveedor' que es el nombre real del campo
        if ($request->filled('proveedor_id')) {
            $query->where('Proveedor', $request->proveedor_id);
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'Nombre');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validar que el campo de ordenamiento existe
        $allowedSorts = ['Nombre', 'id', 'productos_count'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Nombre';
        }
        
        // Si ordenamos por productos_count, necesitamos un orderBy especial
        if ($sortBy === 'productos_count') {
            $query->orderBy('productos_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $categorias = $query->paginate(10);
        $proveedores = Proveedor::all();
        
        return view('categorias.index', compact('categorias', 'proveedores'));
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

    public function destroy($id){
        try {
            $categoria = Categoria::findOrFail($id);
            
            // Verificar si tiene productos asociados
            if ($categoria->productos()->count() > 0) {
                return redirect()->route('categorias.index')
                    ->with('foreign_key_error', 'No se puede eliminar la categoría "' . $categoria->Nombre . '" porque tiene ' . $categoria->productos()->count() . ' productos asociados.')
                    ->with('categoria_nombre', $categoria->Nombre)
                    ->with('productos_count', $categoria->productos()->count());
            }
            
            $categoria->delete();
            
            return redirect()->route('categorias.index')
                ->with('success', 'Categoría eliminada correctamente.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar error de foreign key
            if ($e->errorInfo[1] == 1451) {
                return redirect()->route('categorias.index')
                    ->with('foreign_key_error', 'No se puede eliminar la categoría porque tiene productos asociados.')
                    ->with('categoria_nombre', $categoria->Nombre ?? '')
                    ->with('productos_count', $categoria->productos()->count() ?? 0);
            }
            
            return redirect()->route('categorias.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}