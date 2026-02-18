<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function index()
    {
        $detalleVentas = DetalleVenta::with(['venta', 'producto'])->get();
        return view('detalle-ventas.index', compact('detalleVentas'));
    }

    public function create()
    {
        $ventas = Venta::all();
        $productos = Producto::all();
        return view('detalle-ventas.create', compact('ventas', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Producto' => 'required|exists:productos,id',
            'Venta' => 'required|exists:ventas,id',
            'Cantidad' => 'required|integer|min:1'
        ]);

        DetalleVenta::create($request->all());

        return redirect()->route('detalle-ventas.index')
            ->with('success', 'Detalle de venta creado exitosamente.');
    }

    public function edit($id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $ventas = Venta::all();
        $productos = Producto::all();
        return view('detalle-ventas.edit', compact('detalleVenta', 'ventas', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Producto' => 'required|exists:productos,idProducto',
            'Venta' => 'required|exists:ventas,id',
            'Cantidad' => 'required|integer|min:1'
        ]);

        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->update($request->all());

        return redirect()->route('detalle-ventas.index')
            ->with('success', 'Detalle de venta actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->delete();

        return redirect()->route('detalle-ventas.index')
            ->with('success', 'Detalle de venta eliminado exitosamente.');
    }
}