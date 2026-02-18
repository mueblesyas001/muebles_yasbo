<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    public function index()
    {
        $detallePedidos = DetallePedido::with(['pedido', 'producto'])->get();
        return view('detalle-pedidos.index', compact('detallePedidos'));
    }

    public function create()
    {
        $pedidos = Pedido::all();
        $productos = Producto::all();
        return view('detalle-pedidos.create', compact('pedidos', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Producto' => 'required|exists:productos,idProducto',
            'Pedido' => 'required|exists:pedidos,idPedido',
            'Cantidad' => 'required|integer|min:1'
        ]);

        DetallePedido::create($request->all());

        return redirect()->route('detalle-pedidos.index')
            ->with('success', 'Detalle de pedido creado exitosamente.');
    }

    public function edit($id)
    {
        $detallePedido = DetallePedido::findOrFail($id);
        $pedidos = Pedido::all();
        $productos = Producto::all();
        return view('detalle-pedidos.edit', compact('detallePedido', 'pedidos', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Producto' => 'required|exists:productos,idProducto',
            'Pedido' => 'required|exists:pedidos,idPedido',
            'Cantidad' => 'required|integer|min:1'
        ]);

        $detallePedido = DetallePedido::findOrFail($id);
        $detallePedido->update($request->all());

        return redirect()->route('detalle-pedidos.index')
            ->with('success', 'Detalle de pedido actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $detallePedido = DetallePedido::findOrFail($id);
        $detallePedido->delete();

        return redirect()->route('detalle-pedidos.index')
            ->with('success', 'Detalle de pedido eliminado exitosamente.');
    }
}