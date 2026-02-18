<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use App\Models\Compra;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetalleCompraController extends Controller
{
    public function index()
    {
        $detalleCompras = DetalleCompra::with(['compra', 'producto'])->get();
        return view('detalle-compras.index', compact('detalleCompras'));
    }

    public function create()
    {
        $compras = Compra::all();
        $productos = Producto::all();
        return view('detalle-compras.create', compact('compras', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Producto' => 'required|exists:productos,idProducto',
            'Compra_idCompra' => 'required|exists:compras,idCompra',
            'Cantidad' => 'required|integer|min:1'
        ]);

        DetalleCompra::create($request->all());

        return redirect()->route('detalle-compras.index')
            ->with('success', 'Detalle de compra creado exitosamente.');
    }

    public function edit($id)
    {
        $detalleCompra = DetalleCompra::findOrFail($id);
        $compras = Compra::all();
        $productos = Producto::all();
        return view('detalle-compras.edit', compact('detalleCompra', 'compras', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Producto' => 'required|exists:productos,idProducto',
            'Compra_idCompra' => 'required|exists:compras,idCompra',
            'Cantidad' => 'required|integer|min:1'
        ]);

        $detalleCompra = DetalleCompra::findOrFail($id);
        $detalleCompra->update($request->all());

        return redirect()->route('detalle-compras.index')
            ->with('success', 'Detalle de compra actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $detalleCompra = DetalleCompra::findOrFail($id);
        $detalleCompra->delete();

        return redirect()->route('detalle-compras.index')
            ->with('success', 'Detalle de compra eliminado exitosamente.');
    }
}