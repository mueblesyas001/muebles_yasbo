<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request){
        // Consulta base con relaciones
        $query = Proveedor::with('compras');
        
        // Aplicar filtros
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->filled('nombre')) {
            $query->where(function($q) use ($request) {
                $q->where('Nombre', 'LIKE', '%' . $request->nombre . '%')
                  ->orWhere('ApPaterno', 'LIKE', '%' . $request->nombre . '%')
                  ->orWhere('ApMaterno', 'LIKE', '%' . $request->nombre . '%');
            });
        }
        
        // CORRECCIÓN: Aquí debe ser 'empresa' que viene del formulario
        if ($request->filled('empresa')) {
            $query->where('Empresa_asociada', 'LIKE', '%' . $request->empresa . '%');
        }
        
        if ($request->filled('correo')) {
            $query->where('Correo', 'LIKE', '%' . $request->correo . '%');
        }
        
        if ($request->filled('telefono')) {
            $query->where('Telefono', 'LIKE', '%' . $request->telefono . '%');
        }
        
        if ($request->filled('sexo')) {
            $query->where('Sexo', $request->sexo);
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortColumns = ['id', 'Nombre', 'Empresa_asociada', 'Correo', 'Telefono', 'Sexo'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Obtener valores únicos para filtros
        $empresasUnicas = Proveedor::distinct()
            ->whereNotNull('Empresa_asociada')
            ->where('Empresa_asociada', '!=', '')
            ->orderBy('Empresa_asociada')
            ->pluck('Empresa_asociada')
            ->filter()
            ->values()
            ->toArray();
        
        // Paginación
        $proveedoresPaginated = $query->paginate(10)->appends($request->query());
        
        // Obtener proveedores filtrados sin paginación para estadísticas
        // USAMOS LA MISMA CONSULTA PERO SIN PAGINAR
        $proveedoresFiltrados = $query->get();
        
        return view('proveedores.index', compact(
            'proveedoresPaginated',
            'proveedoresFiltrados',
            'empresasUnicas'
        ));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'ApPaterno' => 'required|string|max:255',
            'ApMaterno' => 'required|string|max:255',
            'Telefono' => 'required|string|max:10',
            'Empresa_asociada' => 'required|string|max:255',
            'Correo' => 'required|email|max:255',
            'Sexo' => 'required|string|max:255'
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'ApPaterno' => 'required|string|max:255',
            'ApMaterno' => 'required|string|max:255',
            'Telefono' => 'required|string|max:10',
            'Empresa_asociada' => 'required|string|max:255',
            'Correo' => 'required|email|max:255',
            'Sexo' => 'required|string|max:255'
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            
            // Verificar si tiene compras asociadas
            if ($proveedor->compras()->count() > 0) {
                return redirect()->route('proveedores.index')
                    ->with('foreign_key_error', [
                        'mensaje' => 'No se puede eliminar el proveedor "' . $proveedor->Nombre . ' ' . $proveedor->ApPaterno . '" porque tiene ' . $proveedor->compras()->count() . ' compras asociadas.',
                        'proveedor_nombre' => $proveedor->Nombre . ' ' . $proveedor->ApPaterno,
                        'proveedor_id' => $proveedor->id,
                        'compras_count' => $proveedor->compras()->count()
                    ]);
            }
            
            $proveedor->delete();
            
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }

    public function verificarCompras($id)
    {
        $proveedor = Proveedor::withCount('compras')->findOrFail($id);
        
        return response()->json([
            'tieneCompras' => $proveedor->compras_count > 0,
            'comprasCount' => $proveedor->compras_count
        ]);
    }
}