<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base con relaciones
        $query = Compra::with(['proveedor', 'detalleCompras.producto']);
        
        // ========== APLICAR FILTROS ==========
        
        // 1. Filtro por ID de compra (exacto)
        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }
        
        // 2. Filtro por fecha específica
        if ($request->filled('fecha')) {
            $query->whereDate('Fecha_compra', $request->input('fecha'));
        }
        
        // 3. Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('Fecha_compra', '>=', $request->input('fecha_desde'));
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('Fecha_compra', '<=', $request->input('fecha_hasta'));
        }
        
        // 4. Filtro por proveedor
        if ($request->filled('proveedor_id')) {
            $query->where('Proveedor_idProveedor', $request->input('proveedor_id'));
        }
        
        // 5. Filtro por monto mínimo
        if ($request->filled('monto_min')) {
            $query->where('Total', '>=', $request->input('monto_min'));
        }
        
        // 6. Filtro por monto máximo
        if ($request->filled('monto_max')) {
            $query->where('Total', '<=', $request->input('monto_max'));
        }
        
        // 7. Filtro por producto (búsqueda en nombre del producto)
        if ($request->filled('producto')) {
            $productoNombre = $request->input('producto');
            $query->whereHas('detalleCompras.producto', function($q) use ($productoNombre) {
                $q->where('Nombre', 'like', "%{$productoNombre}%");
            });
        }
        
        // ========== ORDENAMIENTO ==========
        $sortBy = $request->input('sort_by', 'Fecha_compra');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Validar campos de ordenamiento
        $allowedSorts = ['id', 'Fecha_compra', 'Total'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'Fecha_compra';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // ========== OBTENER DATOS ==========
        
        // Datos para estadísticas (sin paginar, con filtros)
        $comprasFiltradas = $query->get();
        
        // Datos para tabla (paginar con filtros)
        $comprasPaginated = $query->paginate(15)->withQueryString();
        
        // Proveedores para dropdown
        $proveedores = Proveedor::orderBy('Nombre')->get();
        
        return view('compras.index', compact('comprasFiltradas', 'comprasPaginated', 'proveedores'));
    }
    
    public function getDetalles($id)
    {
        try {
            Log::info("🎯 Iniciando getDetalles para compra ID: {$id}");
            
            $compra = Compra::with(['detalleCompras.producto'])->findOrFail($id);

            $detalles = $compra->detalleCompras->map(function($detalle) {
                Log::info("📋 Detalle encontrado:", [
                    'producto_id' => $detalle->Producto,
                    'producto_nombre' => $detalle->producto ? $detalle->producto->Nombre : 'No encontrado',
                    'cantidad' => $detalle->Cantidad,
                    'precio_unitario' => $detalle->Precio_unitario,
                    'subtotal' => $detalle->Subtotal
                ]);
                
                return [
                    'producto_id' => $detalle->Producto,
                    'producto_nombre' => $detalle->producto ? $detalle->producto->Nombre : 'Producto no encontrado',
                    'cantidad' => $detalle->Cantidad,
                    'precio_unitario' => $detalle->Precio_unitario,
                    'subtotal' => $detalle->Subtotal
                ];
            });

            return response()->json([
                'success' => true,
                'detalles' => $detalles,
                'total_detalles' => $detalles->count()
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Error en getDetalles: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar detalles: ' . $e->getMessage(),
                'error_details' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function create()
    {
        try {
            $proveedores = Proveedor::all();
            $productos = Producto::with('categoria')->get();
            $categorias = Categoria::all(); 

            return view('compras.create', compact('proveedores', 'productos', 'categorias'));
        } catch (\Exception $e) {
            Log::error("Error en create compra: " . $e->getMessage());
            return back()->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validar entrada
            $validated = $request->validate([
                'Proveedor_idProveedor' => 'required|exists:proveedores,id',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1|max:10000',
                'productos.*.precio_unitario' => 'required|numeric|min:0'
            ]);

            Log::info("📝 Iniciando store compra");
            Log::info("Proveedor ID: " . $request->Proveedor_idProveedor);
            Log::info("Productos recibidos: " . count($request->productos));

            DB::beginTransaction();

            $total = 0;
            $detallesCompra = [];

            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);

                $precioUnitario = $productoData['precio_unitario'];
                $cantidad = $productoData['cantidad'];
                $subtotal = $precioUnitario * $cantidad;
                $total += $subtotal;

                Log::info("Producto procesado:", [
                    'id' => $producto->id,
                    'nombre' => $producto->Nombre,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal
                ]);

                $detallesCompra[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $cantidad,
                    'Precio_unitario' => $precioUnitario,
                    'Subtotal' => $subtotal
                ];
            }

            $fechaHoraActual = Carbon::now('America/Mexico_City');

            Log::info("Creando compra:", [
                'fecha' => $fechaHoraActual,
                'total' => $total,
                'proveedor_id' => $request->Proveedor_idProveedor
            ]);

            $compra = Compra::create([
                'Fecha_compra' => $fechaHoraActual,
                'Total' => $total,
                'Proveedor_idProveedor' => $request->Proveedor_idProveedor
            ]);

            Log::info("Compra creada ID: " . $compra->id);

            foreach ($detallesCompra as $detalle) {
                Log::info("Creando detalle:", $detalle);
                
                DetalleCompra::create([
                    'Producto' => $detalle['Producto'],
                    'Compra_idCompra' => $compra->id,
                    'Cantidad' => $detalle['Cantidad'],
                    'Precio_unitario' => $detalle['Precio_unitario'],
                    'Subtotal' => $detalle['Subtotal']
                ]);

                // Actualizar stock del producto
                Producto::where('id', $detalle['Producto'])
                       ->increment('Cantidad', $detalle['Cantidad']);
                
                Log::info("Stock actualizado para producto ID: " . $detalle['Producto']);
            }

            DB::commit();

            Log::info("✅ Compra creada exitosamente - ID: {$compra->id}, Total: {$total}");
            
            return redirect()->route('compras.index')
                           ->with('success', 'Compra registrada exitosamente. Stock actualizado. Total: $' . number_format($total, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error al crear compra: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            return back()->with('error', 'Error al registrar la compra: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $compra = Compra::with(['proveedor', 'detalleCompras.producto'])->findOrFail($id);
            return view('compras.show', compact('compra'));
        } catch (\Exception $e) {
            Log::error("Error en show compra: " . $e->getMessage());
            return back()->with('error', 'Compra no encontrada: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $compra = Compra::with(['proveedor', 'detalleCompras.producto'])->findOrFail($id);
            $proveedores = Proveedor::orderBy('Nombre')->get();
            $productos = Producto::orderBy('Nombre')->get();

            return view('compras.edit', compact('compra', 'proveedores', 'productos'));
        } catch (\Exception $e) {
            Log::error("Error en edit compra: " . $e->getMessage());
            return back()->with('error', 'Error al cargar formulario de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar entrada
            $validated = $request->validate([
                'Proveedor_idProveedor' => 'required|exists:proveedores,id',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1|max:10000',
                'productos.*.precio_unitario' => 'required|numeric|min:0'
            ]);

            Log::info("📝 Iniciando update compra ID: {$id}");

            DB::beginTransaction();

            $compra = Compra::with('detalleCompras')->findOrFail($id);
            
            Log::info("Compra encontrada - Stock antiguo a restaurar");

            // ❌ RESTAURAR STOCK - Primero quitamos el stock de la compra anterior
            foreach ($compra->detalleCompras as $detalle) {
                Producto::where('id', $detalle->Producto)
                       ->decrement('Cantidad', $detalle->Cantidad);
                Log::info("Stock restaurado para producto ID: {$detalle->Producto}, cantidad: -{$detalle->Cantidad}");
            }

            // Eliminar detalles antiguos
            $compra->detalleCompras()->delete();
            Log::info("Detalles antiguos eliminados");

            $total = 0;
            $detallesCompra = [];

            // Calcular nuevo total
            foreach ($request->productos as $productoData) {
                $precioUnitario = $productoData['precio_unitario'];
                $cantidad = $productoData['cantidad'];
                $subtotal = $precioUnitario * $cantidad;
                $total += $subtotal;

                $detallesCompra[] = [
                    'Producto' => $productoData['id'],
                    'Cantidad' => $cantidad,
                    'Precio_unitario' => $precioUnitario,
                    'Subtotal' => $subtotal
                ];
                
                Log::info("Producto procesado para nuevo detalle:", [
                    'id' => $productoData['id'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal
                ]);
            }

            // Actualizar compra con fecha de modificación
            $fechaHoraActual = Carbon::now('America/Mexico_City');

            $compra->update([
                'Total' => $total,
                'Proveedor_idProveedor' => $request->Proveedor_idProveedor,
                'Fecha_compra' => $fechaHoraActual
            ]);

            Log::info("Compra actualizada - Nuevo total: {$total}");

            // Crear nuevos detalles
            foreach ($detallesCompra as $detalle) {
                DetalleCompra::create([
                    'Producto' => $detalle['Producto'],
                    'Compra_idCompra' => $compra->id,
                    'Cantidad' => $detalle['Cantidad'],
                    'Precio_unitario' => $detalle['Precio_unitario'],
                    'Subtotal' => $detalle['Subtotal']
                ]);

                // ✅ AUMENTAR STOCK - Agregamos el nuevo stock
                Producto::where('id', $detalle['Producto'])
                       ->increment('Cantidad', $detalle['Cantidad']);
                
                Log::info("Stock incrementado para producto ID: {$detalle['Producto']}, cantidad: +{$detalle['Cantidad']}");
            }

            DB::commit();

            Log::info("✅ Compra actualizada exitosamente - ID: {$compra->id}, Nuevo Total: {$total}");

            return redirect()->route('compras.index')
                           ->with('success', 'Compra actualizada exitosamente. Stock reajustado. Nuevo total: $' . number_format($total, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error al actualizar compra: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            return back()->with('error', 'Error al actualizar la compra: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info("🗑️ Iniciando destroy compra ID: {$id}");

            DB::beginTransaction();

            $compra = Compra::with('detalleCompras')->findOrFail($id);

            // ❌ RESTAURAR STOCK - Quitamos el stock porque eliminamos la compra
            foreach ($compra->detalleCompras as $detalle) {
                Producto::where('id', $detalle->Producto)
                       ->decrement('Cantidad', $detalle->Cantidad);
                Log::info("Stock restaurado para producto ID: {$detalle->Producto}, cantidad: -{$detalle->Cantidad}");
            }

            // Eliminar detalles de compra
            $compra->detalleCompras()->delete();
            Log::info("Detalles de compra eliminados");

            // Eliminar compra
            $compra->delete();
            Log::info("Compra eliminada");

            DB::commit();

            Log::info("✅ Compra eliminada exitosamente - ID: {$id}");

            return redirect()->route('compras.index')
                           ->with('success', 'Compra eliminada exitosamente. Stock restaurado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error al eliminar compra: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            return back()->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }

    public function reporteDiario(Request $request)
    {
        try {
            $fecha = $request->fecha ? Carbon::parse($request->fecha) : Carbon::now('America/Mexico_City');
            
            $compras = Compra::with(['proveedor', 'detalleCompras.producto'])
                            ->whereDate('Fecha_compra', $fecha)
                            ->orderBy('Fecha_compra', 'desc')
                            ->get();

            $totalDia = $compras->sum('Total');
            $totalProductosComprados = 0;
            $totalUnidadesCompradas = 0;

            foreach ($compras as $compra) {
                $totalProductosComprados += $compra->detalleCompras->count();
                $totalUnidadesCompradas += $compra->detalleCompras->sum('Cantidad');
            }

            return view('compras.reportes.diario', compact('compras', 'totalDia', 'totalProductosComprados', 'totalUnidadesCompradas', 'fecha'));
        } catch (\Exception $e) {
            Log::error("Error en reporte diario: " . $e->getMessage());
            return back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    public function crearProducto(Request $request)
    {
        try {
            $validated = $request->validate([
                'Nombre' => 'required|string|max:255',
                'Precio' => 'required|numeric|min:0',
                'Categoria' => 'required|exists:categorias,id',
                'Cantidad_minima' => 'required|integer|min:0',
                'Cantidad_maxima' => 'required|integer|min:0'
            ]);

            Log::info("Creando nuevo producto:", $request->all());

            $producto = Producto::create([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
                'Cantidad' => 0,
                'Cantidad_minima' => $request->Cantidad_minima,
                'Cantidad_maxima' => $request->Cantidad_maxima,
                'Categoria' => $request->Categoria
            ]);

            Log::info("Producto creado ID: " . $producto->id);

            return response()->json([
                'success' => true,
                'producto' => $producto,
                'message' => 'Producto creado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error("Error al crear producto: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de un producto para AJAX
     */
    public function getProductoInfo($id)
    {
        try {
            $producto = Producto::with('categoria')->findOrFail($id);

            return response()->json([
                'id' => $producto->id,
                'nombre' => $producto->Nombre,
                'precio' => $producto->Precio,
                'stock' => $producto->Cantidad,
                'categoria' => $producto->categoria ? $producto->categoria->Nombre : 'Sin categoría',
                'categoria_id' => $producto->categoria ? $producto->categoria->id : null,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error("Error en getProductoInfo: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }

    /**
     * Anular una compra (similar a eliminar pero con estado)
     */
    public function anular($id)
    {
        try {
            Log::info("Iniciando anulación compra ID: {$id}");

            DB::beginTransaction();

            $compra = Compra::with('detalleCompras')->findOrFail($id);

            // ❌ RESTAURAR STOCK - Quitamos el stock porque anulamos la compra
            foreach ($compra->detalleCompras as $detalle) {
                Producto::where('id', $detalle->Producto)
                       ->decrement('Cantidad', $detalle->Cantidad);
                Log::info("Stock restaurado para producto ID: {$detalle->Producto}, cantidad: -{$detalle->Cantidad}");
            }

            DB::commit();

            Log::info("✅ Compra anulada exitosamente - ID: {$id}");

            return redirect()->route('compras.index')
                           ->with('success', 'Compra anulada exitosamente. Stock restaurado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error al anular compra: " . $e->getMessage());
            return back()->with('error', 'Error al anular la compra: ' . $e->getMessage());
        }
    }

    /**
     * Método de diagnóstico para la base de datos con los nuevos campos
     */
    public function diagnosticarCompras()
    {
        try {
            Log::info("=== DIAGNÓSTICO COMPLETO CON NUEVOS CAMPOS ===");
            
            // Verificar compras
            $compras = Compra::all();
            Log::info("Total compras en BD: " . $compras->count());
            
            foreach($compras as $compra) {
                Log::info("Compra ID: {$compra->id}");
                Log::info("  - Fecha: {$compra->Fecha_compra}");
                Log::info("  - Total: {$compra->Total}");
                Log::info("  - Proveedor ID: {$compra->Proveedor_idProveedor}");
                
                // Verificar detalles directamente con DB
                $detalles = DB::table('detalle_compra')
                              ->where('Compra_idCompra', $compra->id)
                              ->get();
                
                Log::info("  - Detalles directos DB: " . $detalles->count());
                
                foreach($detalles as $detalle) {
                    Log::info("    * Producto: {$detalle->Producto}, Cantidad: {$detalle->Cantidad}, Precio Unitario: {$detalle->Precio_unitario}, Subtotal: {$detalle->Subtotal}");
                    
                    // Verificar si el producto existe
                    $producto = DB::table('productos')->where('id', $detalle->Producto)->first();
                    Log::info("      Producto existe: " . ($producto ? 'SÍ - ' . $producto->Nombre : 'NO'));
                }
                
                // Verificar con Eloquent
                $detallesEloquent = $compra->detalleCompras;
                Log::info("  - Detalles Eloquent: " . $detallesEloquent->count());
            }
            
            // Verificar estructura de tablas
            Log::info("=== ESTRUCTURA DE TABLAS CON NUEVOS CAMPOS ===");
            $tablas = ['compras', 'detalle_compra', 'productos', 'proveedores'];
            foreach($tablas as $tabla) {
                if(Schema::hasTable($tabla)) {
                    $columnas = Schema::getColumnListing($tabla);
                    Log::info("Tabla {$tabla}: " . implode(', ', $columnas));
                    
                    // Contar registros
                    $count = DB::table($tabla)->count();
                    Log::info("  Registros: {$count}");
                } else {
                    Log::info("Tabla {$tabla}: NO EXISTE");
                }
            }
            
            return response()->json(['message' => 'Diagnóstico completado - revisa logs']);
        } catch (\Exception $e) {
            Log::error("Error en diagnóstico: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Obtener todas las compras para API/JSON
     */
    public function getComprasJson()
    {
        try {
            $compras = Compra::with(['proveedor', 'detalleCompras.producto'])
                           ->orderBy('Fecha_compra', 'desc')
                           ->get();
            
            return response()->json([
                'success' => true,
                'data' => $compras,
                'total' => $compras->count()
            ]);
        } catch (\Exception $e) {
            Log::error("Error en getComprasJson: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener compras'
            ], 500);
        }
    }
}