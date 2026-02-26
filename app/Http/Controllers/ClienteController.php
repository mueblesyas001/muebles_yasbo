<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request){
        $query = Cliente::withCount('pedidos'); // Si tienes relación con pedidos
        
        // Filtro por nombre, apellidos, correo, etc.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('ApPaterno', 'LIKE', "%{$search}%")
                  ->orWhere('ApMaterno', 'LIKE', "%{$search}%")
                  ->orWhere('Correo', 'LIKE', "%{$search}%")
                  ->orWhere('Telefono', 'LIKE', "%{$search}%");
            });
        }
        
        // FILTRO POR SEXO
        if ($request->filled('sexo')) {
            $query->where('Sexo', $request->sexo);
        }
        
        // FILTRO POR ESTADO
        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->activos(); // Asumiendo que tienes este scope
            } elseif ($request->estado === 'inactivos') {
                $query->inactivos(); // Asumiendo que tienes este scope
            }
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'Nombre');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validar que el campo de ordenamiento existe
        $allowedSorts = ['Nombre', 'ApPaterno', 'Correo', 'Telefono', 'Sexo', 'id', 'estado'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Nombre';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        $clientes = $query->paginate(10);
        
        // Obtener sexos únicos para el filtro (si lo necesitas)
        $sexos = Cliente::select('Sexo')->distinct()->pluck('Sexo');
        
        return view('clientes.index', compact('clientes', 'sexos'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request){
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'ApPaterno' => 'required|string|max:100',
            'ApMaterno' => 'nullable|string|max:100',
            'Correo' => 'required|email|max:100|unique:clientes,Correo', // Añadí unique
            'Telefono' => 'required|string|max:10',
            'Direccion' => 'required|string|max:200',
            'Sexo' => 'required|string|max:10',
            'estado' => 'sometimes|boolean'
        ]);

        $data = $request->all();
        // Si no se envía el estado, por defecto será 1 (activo)
        if (!isset($data['estado'])) {
            $data['estado'] = 1;
        }

        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'ApPaterno' => 'required|string|max:100',
            'ApMaterno' => 'nullable|string|max:100',
            'Correo' => 'required|email|max:100|unique:clientes,Correo,' . $id, // Ignorar el actual
            'Telefono' => 'required|string|max:10',
            'Direccion' => 'required|string|max:200',
            'Sexo' => 'required|string|max:10',
            'estado' => 'sometimes|boolean'
        ]);

        $cliente = Cliente::findOrFail($id);
        $data = $request->all();
        
        // Si no se envía el estado en el formulario, mantener el valor actual
        if (!isset($data['estado'])) {
            $data['estado'] = $cliente->estado;
        }
        
        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            
            $cliente->estado = 0;
            $cliente->save();
            
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente "' . $cliente->Nombre . ' ' . $cliente->ApPaterno . '" desactivado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al desactivar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar el estado de un cliente (Activar/Desactivar)
     */
    public function toggleEstado($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->estado = !$cliente->estado;
            $cliente->save();
            
            $estado = $cliente->estado ? 'activado' : 'desactivado';
            
            return redirect()->route('clientes.index')
                ->with('success', "Cliente '{$cliente->Nombre} {$cliente->ApPaterno}' {$estado} correctamente.");
                
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al cambiar el estado del cliente: ' . $e->getMessage());
        }
    }

    public function activar($id){
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->estado = 1;
            $cliente->save();
            
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente "' . $cliente->Nombre . ' ' . $cliente->ApPaterno . '" activado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al activar el cliente: ' . $e->getMessage());
        }
    }

}