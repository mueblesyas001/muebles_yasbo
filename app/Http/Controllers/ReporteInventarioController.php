<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteInventarioController extends Controller
{
    // En el método index()
    public function index()
    {
        $categorias = Categoria::all();
        
        // Calcular estadísticas para mostrar en la vista
        $estadisticas = [
            'total_productos' => Producto::count(),
            'total_stock' => Producto::sum('Cantidad'),
            'valor_inventario' => Producto::sum(DB::raw('Precio * Cantidad')),
            'productos_bajo_stock' => Producto::whereRaw('Cantidad <= Cantidad_minima')->count(),
            'productos_agotados' => Producto::where('Cantidad', 0)->count(),
        ];
        
        return view('reportes.inventario.index', compact('categorias', 'estadisticas'));
    }
    
    // Reporte de inventario general
    public function generarReporte(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);
        
        $query = Producto::with('categoria');
        
        // Obtener categoría seleccionada si existe
        $categoriaSeleccionada = null;
        if ($request->filled('categoria_id')) {
            $query->where('Categoria', $request->categoria_id);
            $categoriaSeleccionada = Categoria::find($request->categoria_id);
        }
        
        // NOTA: Si tu tabla productos NO tiene created_at, quita estos filtros
        // Filtro por rango de fechas (si los productos tienen fecha de creación)
        // Comentar o eliminar si no tienes created_at en productos
        /*
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        */
        
        // Ordenar por stock (ranking)
        if ($request->orden_stock == 'alto') {
            $query->orderBy('Cantidad', 'desc');
        } elseif ($request->orden_stock == 'bajo') {
            $query->orderBy('Cantidad', 'asc');
        } elseif ($request->orden_stock == 'bajo_stock') {
            $query->orderByRaw('Cantidad <= Cantidad_minima desc, Cantidad asc');
        } else {
            $query->orderBy('Nombre', 'asc');
        }
        
        $productos = $query->get();
        
        // DEBUG: Verificar si hay productos
        // dd($productos->count(), $productos->first());
        
        // Cálculos estadísticos básicos
        $totalInventario = $productos->sum(function($producto) {
            return $producto->Precio * $producto->Cantidad;
        });
        
        $totalProductos = $productos->count();
        $totalStock = $productos->sum('Cantidad');
        
        $productosBajoStock = $productos->filter(function($producto) {
            return $producto->Cantidad <= $producto->Cantidad_minima;
        });
        
        $productosSinStock = $productos->where('Cantidad', 0)->count();
        
        // Productos más valiosos (por valor total en inventario)
        $productosValiosos = $productos->sortByDesc(function($producto) {
            return $producto->Precio * $producto->Cantidad;
        })->take(10);
        
        // INVENTARIO POR CATEGORÍA - CORREGIDO
        // Primero obtenemos los datos crudos
        $inventarioPorCategoriaRaw = Producto::select('Categoria', 
                DB::raw('COUNT(*) as total_productos'),
                DB::raw('SUM(Cantidad) as total_stock'),
                DB::raw('SUM(Precio * Cantidad) as valor_total')
            )
            ->groupBy('Categoria')
            ->get();
        
        // Luego cargamos las categorías relacionadas
        $inventarioPorCategoria = $inventarioPorCategoriaRaw->map(function($item) {
            // Buscar la categoría por ID
            $categoria = Categoria::find($item->Categoria);
            $item->categoria_nombre = $categoria ? $categoria->Nombre : 'Sin categoría';
            $item->categoria = $categoria; // Agregar el objeto completo
            return $item;
        });
        
        $valorTotalPorCategoria = $inventarioPorCategoria->sum('valor_total');
        
        // PRODUCTOS PARA REABASTECER
        $productosParaReabastecer = $productosBajoStock->map(function($producto) {
            // Calcular cantidad necesaria para alcanzar el mínimo
            $producto->cantidad_necesaria_min = max(0, $producto->Cantidad_minima - $producto->Cantidad);
            
            // Sugerir un reabastecimiento del 150% del mínimo o al menos 10 unidades
            $sugerido = max($producto->Cantidad_minima * 1.5, $producto->Cantidad_minima + 10);
            $producto->cantidad_sugerida = ceil($sugerido);
            $producto->cantidad_necesaria = max(0, ceil($sugerido - $producto->Cantidad));
            $producto->costo_reabastecimiento = $producto->cantidad_necesaria * $producto->Precio;
            
            return $producto;
        });
        
        // RESUMEN EJECUTIVO
        $categoriaMasValiosa = $inventarioPorCategoria->sortByDesc('valor_total')->first();
        
        $resumenEjecutivo = [
            'valor_total_inventario' => $totalInventario,
            'inversion_promedio_producto' => $totalProductos > 0 ? $totalInventario / $totalProductos : 0,
            'porcentaje_bajo_stock' => $totalProductos > 0 ? ($productosBajoStock->count() / $totalProductos) * 100 : 0,
            'porcentaje_agotados' => $totalProductos > 0 ? ($productosSinStock / $totalProductos) * 100 : 0,
            'categoria_mas_valiosa' => $categoriaMasValiosa,
            'categoria_mas_valiosa_nombre' => $categoriaMasValiosa ? $categoriaMasValiosa->categoria_nombre : 'N/A',
        ];
        
        // DEBUG: Verificar datos antes de enviar a la vista
        // dd([
        //     'totalProductos' => $totalProductos,
        //     'totalInventario' => $totalInventario,
        //     'productosCount' => $productos->count(),
        //     'inventarioPorCategoria' => $inventarioPorCategoria->toArray(),
        // ]);
        
        // Generar PDF
        $pdf = Pdf::loadView('reportes.inventario.completo', [
            'productos' => $productos,
            'fechaInicio' => $request->fecha_inicio,
            'fechaFin' => $request->fecha_fin,
            'categoriaSeleccionada' => $categoriaSeleccionada,
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'totalInventario' => $totalInventario,
            'totalProductos' => $totalProductos,
            'totalStock' => $totalStock,
            'productosBajoStock' => $productosBajoStock,
            'productosSinStock' => $productosSinStock,
            'productosValiosos' => $productosValiosos,
            'inventarioPorCategoria' => $inventarioPorCategoria,
            'valorTotalPorCategoria' => $valorTotalPorCategoria,
            'productosParaReabastecer' => $productosParaReabastecer,
            'resumenEjecutivo' => $resumenEjecutivo,
        ]);
        
        $nombreArchivo = 'inventario_' . date('Y-m-d') . '.pdf';
        
        return $pdf->stream($nombreArchivo);
    }
    
    // Reporte de valor de inventario por categoría
    public function reportePorCategoria()
    {
        // Obtener inventario agrupado por categoría
        $inventarioPorCategoriaRaw = Producto::select('Categoria', 
                DB::raw('COUNT(*) as total_productos'),
                DB::raw('SUM(Cantidad) as total_stock'),
                DB::raw('SUM(Precio * Cantidad) as valor_total')
            )
            ->groupBy('Categoria')
            ->get();
        
        // Cargar nombres de categorías
        $inventarioPorCategoria = $inventarioPorCategoriaRaw->map(function($item) {
            $categoria = Categoria::find($item->Categoria);
            $item->categoria_nombre = $categoria ? $categoria->Nombre : 'Sin categoría';
            return $item;
        });
        
        // Calcular total general
        $valorTotalInventario = $inventarioPorCategoria->sum('valor_total');
        $totalProductos = $inventarioPorCategoria->sum('total_productos');
        
        $pdf = Pdf::loadView('reportes.inventario.por-categoria', [
            'inventarioPorCategoria' => $inventarioPorCategoria,
            'valorTotalInventario' => $valorTotalInventario,
            'totalProductos' => $totalProductos,
            'fecha' => now()->format('d/m/Y H:i:s'),
        ]);
        
        return $pdf->stream('inventario_por_categoria.pdf');
    }
    
    // Reporte de tendencias de stock
    public function reporteTendencias(Request $request)
    {
        $productos = Producto::with('categoria')
            ->orderBy('Cantidad', 'asc')
            ->get();
        
        // Productos que necesitan atención
        $necesitanAtencion = $productos->filter(function($producto) {
            return $producto->Cantidad <= $producto->Cantidad_minima;
        });
        
        // Productos sobrestock
        $sobrestock = $productos->filter(function($producto) {
            return $producto->Cantidad >= $producto->Cantidad_maxima && $producto->Cantidad_maxima > 0;
        });
        
        $pdf = Pdf::loadView('reportes.inventario.tendencias', [
            'productos' => $productos,
            'necesitanAtencion' => $necesitanAtencion,
            'sobrestock' => $sobrestock,
            'fecha' => now()->format('d/m/Y H:i:s'),
        ]);
        
        return $pdf->stream('tendencias_inventario.pdf');
    }
    
    public function reporteEjecutivo()
    {
        $totalProductos = Producto::count();
        $totalStock = Producto::sum('Cantidad');
        $valorInventario = Producto::sum(DB::raw('Precio * Cantidad'));
        
        $productosBajoStock = Producto::whereRaw('Cantidad <= Cantidad_minima')->count();
        $productosSinStock = Producto::where('Cantidad', 0)->count();
        
        $categoriasConProductos = Categoria::withCount('productos')->get();
        
        $pdf = Pdf::loadView('reportes.inventario.ejecutivo', [
            'totalProductos' => $totalProductos,
            'totalStock' => $totalStock,
            'valorInventario' => $valorInventario,
            'productosBajoStock' => $productosBajoStock,
            'productosSinStock' => $productosSinStock,
            'categoriasConProductos' => $categoriasConProductos,
            'fecha' => now()->format('d/m/Y H:i:s'),
        ]);
        
        return $pdf->stream('reporte_ejecutivo_inventario.pdf');
    }
}