<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Inventario | Documento Ejecutivo</title>
    <style>
        /* ===== ESTILOS CORPORATIVOS ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            margin: 2.2cm auto 2cm auto;
            size: A4;
            footer: html_footer;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #2c3e50;
            background: #ffffff;
            font-weight: 300;
            display: flex;
            justify-content: center;
        }
        
        .page-content {
            width: 16.5cm;
            max-width: 100%;
            margin: 0 auto;
        }
        
        h1 {
            font-size: 26pt;
            font-weight: 200;
            letter-spacing: 1px;
            color: #1a1a1a;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-align: center;
        }
        
        h2 {
            font-size: 14pt;
            font-weight: 400;
            color: #000000;
            margin: 30px 0 15px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #d0d0d0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header .periodo {
            margin-top: 15px;
            color: #4a4a4a;
            font-size: 10pt;
            border-top: 1px solid #eaeaea;
            border-bottom: 1px solid #eaeaea;
            padding: 10px 0;
        }
        
        .reference {
            text-align: right;
            font-size: 8pt;
            color: #888888;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        /* ===== MÉTRICAS ===== */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin: 25px 0;
        }
        
        .metric-card {
            border: 1px solid #e8e8e8;
            padding: 18px 5px;
            text-align: center;
        }
        
        .metric-label {
            font-size: 7.5pt;
            color: #888888;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        
        .metric-value {
            font-size: 18pt;
            font-weight: 200;
            color: #000000;
        }
        
        .metric-desc {
            font-size: 7pt;
            color: #aaaaaa;
            margin-top: 5px;
        }
        
        /* ===== TABLAS ===== */
        .table-wrapper {
            margin: 15px 0 25px 0;
            border: 1px solid #eaeaea;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        
        th {
            background: #f5f5f5;
            color: #1a1a1a;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 7pt;
            padding: 10px 4px;
            border-bottom: 2px solid #cccccc;
            text-align: left;
        }
        
        td {
            padding: 8px 4px;
            border-bottom: 1px solid #efefef;
            color: #333333;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 6pt;
            background: #f0f0f0;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #aaaaaa;
            padding-top: 6px;
            border-top: 1px solid #eeeeee;
            width: 16.5cm;
            margin: 0 auto;
        }
        
        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="page-content">
        <!-- REFERENCIA -->
        <div class="reference">
            <span>REPORTE DE INVENTARIO</span> · {{ $fechaGeneracion }}
        </div>
        
        <!-- HEADER -->
        <div class="header">
            <h1>INVENTARIO</h1>
            <div class="periodo">
                <strong>Total de productos:</strong> {{ $totalProductos }}
                @if($categoriaSeleccionada)
                    · <span class="badge">Categoría: {{ $categoriaSeleccionada->nombre }}</span>
                @endif
            </div>
        </div>
        
        <!-- MÉTRICAS CLAVE -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Stock Total</div>
                <div class="metric-value">{{ number_format($totalStock) }}</div>
                <div class="metric-desc">unidades</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Valor Inventario</div>
                <div class="metric-value">${{ number_format($totalInventario, 2) }}</div>
                <div class="metric-desc">total</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Stock Bajo</div>
                <div class="metric-value">{{ $productosBajoStock->count() }}</div>
                <div class="metric-desc">{{ number_format($porcentajeBajoStock ?? 0, 1) }}%</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Sin Stock</div>
                <div class="metric-value">{{ $productosSinStock->count() }}</div>
                <div class="metric-desc">{{ number_format($porcentajeSinStock ?? 0, 1) }}%</div>
            </div>
        </div>
        
        <!-- TABLA DE PRODUCTOS -->
        <h2>LISTADO DE PRODUCTOS</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Mínimo</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Valor</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    @php
                        $valorProducto = $producto->Cantidad * $producto->Precio;
                        $estado = '';
                        $badgeClass = '';
                        
                        if ($producto->Cantidad <= 0) {
                            $estado = 'AGOTADO';
                            $badgeClass = 'badge-danger';
                        } elseif ($producto->Cantidad <= $producto->Cantidad_minima) {
                            $estado = 'STOCK BAJO';
                            $badgeClass = 'badge-warning';
                        } else {
                            $estado = 'NORMAL';
                            $badgeClass = 'badge-success';
                        }
                    @endphp
                    <tr>
                        <td class="truncate">{{ $producto->Nombre }}</td>
                        <td>{{ $producto->categoria->Nombre ?? 'Sin categoría' }}</td>
                        <td class="text-right">{{ number_format($producto->Cantidad) }}</td>
                        <td class="text-right">{{ number_format($producto->Cantidad_minima) }}</td>
                        <td class="text-right">${{ number_format($producto->Precio, 2) }}</td>
                        <td class="text-right">${{ number_format($valorProducto, 2) }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $estado }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 30px;">No hay productos para mostrar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PRODUCTOS MÁS VALIOSOS -->
        @if($productosMasValiosos->isNotEmpty())
        <h2>PRODUCTOS MÁS VALIOSOS</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosMasValiosos as $index => $producto)
                    @php
                        $valorProducto = $producto->Cantidad * $producto->Precio;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $producto->Nombre }}</td>
                        <td class="text-right">{{ number_format($producto->Cantidad) }}</td>
                        <td class="text-right">${{ number_format($producto->Precio, 2) }}</td>
                        <td class="text-right">${{ number_format($valorProducto, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- INVENTARIO POR CATEGORÍA -->
        @if($inventarioPorCategoria->isNotEmpty())
        <h2>INVENTARIO POR CATEGORÍA</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th class="text-right">Productos</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventarioPorCategoria as $categoria)
                    <tr>
                        <td>{{ $categoria->categoria_nombre }}</td>
                        <td class="text-right">{{ $categoria->total_productos }}</td>
                        <td class="text-right">{{ number_format($categoria->total_stock) }}</td>
                        <td class="text-right">${{ number_format($categoria->valor_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- ANÁLISIS ABC -->
        @if(!empty($productosABC) && $productosABC['total_A'] > 0)
        <h2>ANÁLISIS ABC</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin: 15px 0;">
            <div style="background: #f0f0f0; padding: 15px; text-align: center;">
                <div style="font-size: 12pt; font-weight: bold; color: #000;">A</div>
                <div style="font-size: 9pt;">{{ $productosABC['total_A'] }} productos</div>
                <div style="font-size: 8pt;">${{ number_format($productosABC['valor_A'], 2) }}</div>
            </div>
            <div style="background: #f0f0f0; padding: 15px; text-align: center;">
                <div style="font-size: 12pt; font-weight: bold; color: #000;">B</div>
                <div style="font-size: 9pt;">{{ $productosABC['total_B'] }} productos</div>
                <div style="font-size: 8pt;">${{ number_format($productosABC['valor_B'], 2) }}</div>
            </div>
            <div style="background: #f0f0f0; padding: 15px; text-align: center;">
                <div style="font-size: 12pt; font-weight: bold; color: #000;">C</div>
                <div style="font-size: 9pt;">{{ $productosABC['total_C'] }} productos</div>
                <div style="font-size: 8pt;">${{ number_format($productosABC['valor_C'], 2) }}</div>
            </div>
        </div>
        @endif
        
        <!-- RESUMEN -->
        <div style="margin-top: 30px; padding: 15px; background: #f5f5f5;">
            <p><strong>Valor promedio por producto:</strong> ${{ number_format($valorPromedioProducto ?? 0, 2) }}</p>
            <p><strong>Stock promedio:</strong> {{ number_format($totalStock / max($totalProductos, 1), 1) }} unidades</p>
            <p><strong>Categoría más valiosa:</strong> {{ $categoriaMasValiosa->categoria_nombre ?? 'N/A' }}</p>
        </div>
    </div>
    
    <!-- FOOTER -->
    <htmlpagefooter name="footer" class="footer">
        <span class="page-number"></span> · {{ $fechaGeneracion }} · CONFIDENCIAL
    </htmlpagefooter>
</body>
</html>