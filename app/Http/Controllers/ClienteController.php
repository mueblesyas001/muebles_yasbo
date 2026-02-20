<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(){
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
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
            'Correo' => 'required|email|max:100',
            'Telefono' => 'required|string|max:10',
            'Direccion' => 'required|string|max:200',
            'Sexo' => 'required|string|max:10'
        ]);

        Cliente::create($request->all());

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
            'Correo' => 'required|email|max:100',
            'Telefono' => 'required|string|max:10',
            'Direccion' => 'required|string|max:200',
            'Sexo' => 'required|string|max:10'
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    public function verificarPedidos($id){
        $cliente = Cliente::withCount('pedidos')->findOrFail($id);
        
        return response()->json([
            'tienePedidos' => $cliente->pedidos_count > 0,
            'cantidadPedidos' => $cliente->pedidos_count
        ]);
    }
}
