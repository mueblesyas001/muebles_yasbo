<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request){
        // Consulta base con relaciones y conteo de compras
        $query = Proveedor::with('compras')->withCount('compras');
        
        // FILTRO DE BÚSQUEDA UNIFICADO
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('ApPaterno', 'LIKE', "%{$search}%")
                  ->orWhere('ApMaterno', 'LIKE', "%{$search}%")
                  ->orWhere('Empresa_asociada', 'LIKE', "%{$search}%")
                  ->orWhere('Correo', 'LIKE', "%{$search}%")
                  ->orWhere('Telefono', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }
        
        // FILTRO POR EMPRESA
        if ($request->filled('empresa')) {
            $query->where('Empresa_asociada', 'LIKE', '%' . $request->empresa . '%');
        }
        
        // FILTRO POR SEXO
        if ($request->filled('sexo')) {
            $query->where('Sexo', $request->sexo);
        }
        
        // FILTRO POR ESTADO
        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->where('estado', 1);
            } elseif ($request->estado === 'inactivos') {
                $query->where('estado', 0);
            }
        }
        // Si no se especifica estado, se muestran TODOS (activos e inactivos)
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'Nombre');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validar que el campo de ordenamiento existe
        $allowedSorts = ['id', 'Nombre', 'ApPaterno', 'Empresa_asociada', 'Correo', 'Telefono', 'Sexo', 'estado', 'compras_count'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Nombre';
        }
        
        // Si ordenamos por compras_count, necesitamos un orderBy especial
        if ($sortBy === 'compras_count') {
            $query->orderBy('compras_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        // Obtener valores únicos para filtros
        $empresasUnicas = Proveedor::distinct()
            ->whereNotNull('Empresa_asociada')
            ->where('Empresa_asociada', '!=', '')
            ->orderBy('Empresa_asociada')
            ->pluck('Empresa_asociada')
            ->filter()
            ->values()
            ->toArray();
        
        // Obtener sexos únicos para el filtro
        $sexos = Proveedor::select('Sexo')->distinct()->whereNotNull('Sexo')->pluck('Sexo');
        
        // Obtener contadores para las estadísticas (TODOS los proveedores)
        $todosProveedores = Proveedor::withCount('compras')->get();
        
        $totalActivos = Proveedor::where('estado', 1)->count();
        $totalInactivos = Proveedor::where('estado', 0)->count();
        $totalTodos = Proveedor::count();
        
        // Paginación
        $proveedores = $query->paginate(10)->appends($request->except('page'));
        
        return view('proveedores.index', compact(
            'proveedores',
            'empresasUnicas',
            'sexos',
            'todosProveedores', // Para las estadísticas
            'totalActivos',
            'totalInactivos',
            'totalTodos'
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
            'Correo' => 'required|email|max:255|unique:proveedores,Correo',
            'Sexo' => 'required|string|max:255',
            'estado' => 'sometimes|boolean'
        ]);

        $data = $request->all();
        // Si no se envía el estado, por defecto será 1 (activo)
        if (!isset($data['estado'])) {
            $data['estado'] = 1;
        }
        
        Proveedor::create($data);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $request->Nombre . ' ' . $request->ApPaterno . '" creado exitosamente.');
    }

    public function edit($id)
    {
        $proveedor = Proveedor::with('compras')->findOrFail($id);
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
            'Correo' => 'required|email|max:255|unique:proveedores,Correo,' . $id,
            'Sexo' => 'required|string|max:255',
            'estado' => 'sometimes|boolean'
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $data = $request->all();
        
        // Si no se envía el estado en el formulario, mantener el valor actual
        if (!isset($data['estado'])) {
            $data['estado'] = $proveedor->estado;
        }
        
        $proveedor->update($data);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $proveedor->nombreCompleto . '" actualizado exitosamente.');
    }

    public function destroy($id){
        try {
            $proveedor = Proveedor::withCount('compras')->findOrFail($id);
            
            // Obtener el conteo de compras
            $comprasCount = $proveedor->compras_count;
            
            // Cambiar el estado a 0 (inactivo) en lugar de eliminar
            $proveedor->estado = 0;
            $proveedor->save();
            
            if ($comprasCount > 0) {
                return redirect()->route('proveedores.index')
                    ->with('success', 'El proveedor "' . $proveedor->nombreCompleto . '" ha sido desactivado correctamente. Tenía ' . $comprasCount . ' compras asociadas.');
            } else {
                return redirect()->route('proveedores.index')
                    ->with('success', 'El proveedor "' . $proveedor->nombreCompleto . '" ha sido desactivado correctamente.');
            }
                
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al desactivar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar el estado de un proveedor (Activar/Desactivar)
     */
    public function toggleEstado($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            
            // Si va a desactivar, verificar si tiene correo único (opcional)
            if ($proveedor->estado == 1) {
                // No necesita verificación especial para desactivar
                $proveedor->estado = 0;
                $accion = 'desactivado';
            } else {
                // Si va a activar, verificar que el correo no esté en uso por otro activo
                $correoExistente = Proveedor::where('Correo', $proveedor->Correo)
                    ->where('id', '!=', $proveedor->id)
                    ->where('estado', 1)
                    ->exists();
                    
                if ($correoExistente) {
                    return redirect()->route('proveedores.index')
                        ->with('error', 'No se puede activar el proveedor porque el correo "' . $proveedor->Correo . '" ya está siendo utilizado por otro proveedor activo.');
                }
                
                $proveedor->estado = 1;
                $accion = 'activado';
            }
            
            $proveedor->save();
            
            return redirect()->route('proveedores.index')
                ->with('success', "Proveedor '{$proveedor->nombreCompleto}' {$accion} correctamente.");
                
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al cambiar el estado del proveedor: ' . $e->getMessage());
        }
    }

    public function activar($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            
            if ($proveedor->estado == 1) {
                return redirect()->route('proveedores.index')
                    ->with('warning', 'El proveedor "' . $proveedor->nombreCompleto . '" ya está activo.');
            }
            
            // Verificar si el correo ya existe en otro proveedor activo
            $correoExistente = Proveedor::where('Correo', $proveedor->Correo)
                ->where('id', '!=', $proveedor->id)
                ->where('estado', 1)
                ->exists();
                
            if ($correoExistente) {
                return redirect()->route('proveedores.index')
                    ->with('error', 'No se puede activar el proveedor porque el correo "' . $proveedor->Correo . '" ya está siendo utilizado por otro proveedor activo.');
            }
            
            $proveedor->estado = 1;
            $proveedor->save();
            
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor "' . $proveedor->nombreCompleto . '" activado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al activar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar lista de proveedores inactivos (por compatibilidad)
     */
    public function inactivos()
    {
        $proveedores = Proveedor::where('estado', 0)
            ->with('compras')
            ->withCount('compras')
            ->orderBy('Nombre')
            ->paginate(10);
            
        return view('proveedores.inactivos', compact('proveedores'));
    }

    /**
     * Cambiar estado de múltiples proveedores
     */
    public function cambiarEstadoMasivo(Request $request)
    {
        $request->validate([
            'proveedores' => 'required|array',
            'proveedores.*' => 'exists:proveedores,id',
            'accion' => 'required|in:activar,desactivar'
        ]);

        $proveedores = Proveedor::whereIn('id', $request->proveedores);
        
        if ($request->accion === 'activar') {
            // Verificar conflictos de correo antes de activar múltiples
            $proveedoresAActivar = Proveedor::whereIn('id', $request->proveedores)
                ->where('estado', 0)
                ->get();
                
            foreach ($proveedoresAActivar as $proveedor) {
                $correoExistente = Proveedor::where('Correo', $proveedor->Correo)
                    ->where('id', '!=', $proveedor->id)
                    ->where('estado', 1)
                    ->exists();
                    
                if ($correoExistente) {
                    return redirect()->route('proveedores.index')
                        ->with('error', 'No se puede activar el proveedor "' . $proveedor->nombreCompleto . '" porque su correo ya está en uso por otro proveedor activo.');
                }
            }
            
            $proveedores->update(['estado' => 1]);
            $mensaje = 'Proveedores activados exitosamente.';
        } else {
            $proveedores->update(['estado' => 0]);
            $mensaje = 'Proveedores desactivados exitosamente.';
        }

        return redirect()->route('proveedores.index')
            ->with('success', $mensaje);
    }
}