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
        
        // FILTRO POR ESTADO
        if ($request->filled('estado')) {
            if ($request->estado === 'activas') {
                $query->activas();
            } elseif ($request->estado === 'inactivas') {
                $query->inactivas();
            }
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'Nombre');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validar que el campo de ordenamiento existe
        $allowedSorts = ['Nombre', 'id', 'productos_count', 'estado'];
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

    public function create(){
        $proveedores = Proveedor::where('estado', 1)->get(); // Solo proveedores activos
        return view('categorias.create', compact('proveedores'));
    }

    public function store(Request $request){
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'required|string|max:200',
            'Proveedor' => 'required|exists:proveedores,id',
            'estado' => 'sometimes|boolean'
        ]);

        $data = $request->all();
        if (!isset($data['estado'])) {
            $data['estado'] = 1;
        }

        Categoria::create($data);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit($id){
        $proveedores = Proveedor::where('estado', 1)->get(); // Solo proveedores activos
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'required|string|max:200',
            'Proveedor' => 'required|exists:proveedores,id',
            'estado' => 'sometimes|boolean'
        ]);

        $categoria = Categoria::findOrFail($id);
        $data = $request->all();
        
        // Si no se envía el estado en el formulario, mantener el valor actual
        if (!isset($data['estado'])) {
            $data['estado'] = $categoria->estado;
        }
        
        $categoria->update($data);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id){
        try {
            $categoria = Categoria::findOrFail($id);
            
            $categoria->estado = 0;
            $categoria->save();
            
            return redirect()->route('categorias.index')
                ->with('success', 'Categoría "' . $categoria->Nombre . '" desactivada correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')
                ->with('error', 'Error al desactivar la categoría: ' . $e->getMessage());
        }
    }


    public function toggleEstado($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->estado = !$categoria->estado;
            $categoria->save();
            
            $estado = $categoria->isActive() ? 'activada' : 'desactivada';
            
            return redirect()->route('categorias.index')
                ->with('success', "Categoría '{$categoria->Nombre}' {$estado} correctamente.");
                
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')
                ->with('error', 'Error al cambiar el estado de la categoría: ' . $e->getMessage());
        }
    }

    public function activar($id){
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->estado = 1;
            $categoria->save();
            
            return redirect()->route('categorias.index')
                ->with('success', 'Categoría "' . $categoria->Nombre . '" activada correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')
                ->with('error', 'Error al activar la categoría: ' . $e->getMessage());
        }
    }
}