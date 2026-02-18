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
        
        if ($request->filled('empresa')) {
            $query->where('Empresa_asociada', $request->empresa);
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
        
        // Obtener todos los proveedores sin filtros para estadísticas
        $proveedoresTodos = Proveedor::with('compras')->get();
        
        // Paginación
        $proveedoresPaginated = $query->paginate(10)->appends($request->query());
        
        // Proveedores filtrados sin paginación para estadísticas
        $proveedoresFiltrados = $query->get();
        
        return view('proveedores.index', compact(
            'proveedoresPaginated',
            'proveedoresFiltrados',
            'proveedoresTodos',
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
            'ApMaterno' => 'required|string|max:255', // Cambiado a required
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
            'ApMaterno' => 'required|string|max:255', // Cambiado a required
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
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}