<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Inventario | Documento Ejecutivo</title>
    <style>
        /* ===== ESTILO CORPORATIVO DE ALTA GAMA ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            margin: 2.5cm 2.2cm 2.2cm 2.2cm;
            size: A4;
            footer: html_footer;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', 'Segoe UI', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #2c3e50;
            background: #ffffff;
            font-weight: 300;
        }
        
        /* ===== CONTENEDOR PRINCIPAL ===== */
        .page-content {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
        }
        
        /* ===== TIPOGRAFÍA ELEGANTE ===== */
        h1 {
            font-size: 28pt;
            font-weight: 200;
            letter-spacing: 2px;
            color: #1e272e;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-align: center;
        }
        
        h2 {
            font-size: 14pt;
            font-weight: 400;
            color: #1e272e;
            margin: 35px 0 20px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #dcdde1;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        /* ===== ENCABEZADO MINIMALISTA ===== */
        .header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 25%;
            width: 50%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #b2bec3, transparent);
        }
        
        .header h1 {
            color: #1e272e;
        }
        
        .header .subtitle {
            color: #7f8c8d;
            font-size: 10pt;
            font-weight: 300;
            margin-top: 3px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        
        .header .periodo {
            margin-top: 20px;
            color: #636e72;
            font-size: 10pt;
            font-weight: 300;
            padding: 8px 0;
        }
        
        .header .periodo strong {
            color: #2c3e50;
            font-weight: 400;
        }
        
        /* ===== REFERENCIA ===== */
        .reference {
            text-align: right;
            font-size: 8pt;
            color: #95a5a6;
            margin-bottom: 25px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ecf0f1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* ===== MÉTRICAS CLAVE ===== */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 30px 0 30px 0;
        }
        
        .metric-card {
            background: #ffffff;
            border: 1px solid #ecf0f1;
            padding: 20px 10px 15px 10px;
            text-align: center;
        }
        
        .metric-label {
            font-size: 7.5pt;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .metric-value {
            font-size: 20pt;
            font-weight: 200;
            color: #2c3e50;
            line-height: 1.2;
        }
        
        .metric-desc {
            font-size: 7pt;
            color: #b2bec3;
            margin-top: 5px;
            text-transform: uppercase;
        }
        
        /* ===== PÁGINA COMPLETA PARA DASHBOARD ===== */
        .dashboard-page {
            page-break-before: always;
            page-break-after: always;
            margin-top: 0.5cm;
        }
        
        .dashboard-container {
            background: #ffffff;
            border: 1px solid #ecf0f1;
            padding: 30px 25px;
            min-height: 22cm;
            display: flex;
            flex-direction: column;
        }
        
        .dashboard-title {
            font-size: 18pt;
            font-weight: 200;
            color: #2c3e50;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-align: center;
        }
        
        .dashboard-subtitle {
            font-size: 11pt;
            color: #7f8c8d;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 300;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 35px;
            flex: 1;
        }
        
        .chart-card {
            background: #ffffff;
            border: 1px solid #ecf0f1;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .chart-header h3 {
            font-size: 12pt;
            font-weight: 400;
            color: #2c3e50;
            margin: 0;
        }
        
        .legend {
            display: flex;
            gap: 20px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            font-size: 8pt;
            color: #7f8c8d;
        }
        
        .legend-dot {
            width: 10px;
            height: 10px;
            margin-right: 6px;
        }
        
        .legend-dot.primary { background: #2c3e50; }
        .legend-dot.secondary { background: #95a5a6; }
        .legend-dot.accent { background: #e67e22; }
        .legend-dot.danger { background: #c0392b; }
        
        /* ===== BARRAS GRANDES Y CLARAS ===== */
        .chart-content {
            flex: 1;
        }
        
        .chart-row {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }
        
        .chart-label {
            width: 30%;
            font-size: 9pt;
            color: #34495e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 15px;
        }
        
        .chart-bars {
            width: 45%;
            position: relative;
        }
        
        .bar-bg {
            height: 32px;
            background: #f5f6fa;
            position: relative;
        }
        
        .bar-fill {
            height: 32px;
            background: #2c3e50;
            position: absolute;
            left: 0;
            top: 0;
        }
        
        .bar-fill.secondary {
            background: #95a5a6;
            height: 12px;
            bottom: 0;
            top: auto;
        }
        
        .chart-value {
            width: 25%;
            font-size: 10pt;
            color: #2c3e50;
            text-align: right;
            padding-left: 15px;
            font-weight: 400;
        }
        
        .chart-value small {
            font-size: 8pt;
            color: #95a5a6;
            margin-left: 5px;
        }
        
        .dual-indicator {
            margin-top: 5px;
            height: 12px;
            background: #f5f6fa;
            position: relative;
        }
        
        .dual-indicator .fill {
            height: 12px;
            background: #95a5a6;
            position: absolute;
            left: 0;
        }
        
        .chart-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            text-align: right;
        }
        
        .total-stats {
            background: #f5f6fa;
            padding: 12px 20px;
            display: inline-block;
            font-size: 9pt;
        }
        
        .total-stats strong {
            font-weight: 500;
            color: #2c3e50;
            margin: 0 5px;
        }
        
        /* ===== ALERTAS Y ESTADOS ===== */
        .alert-card {
            margin: 25px 0;
            border: 1px solid #ecf0f1;
        }
        
        .alert-header {
            background: #f5f6fa;
            padding: 15px 20px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 11pt;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .alert-content {
            padding: 20px;
        }
        
        .alert-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .alert-item {
            text-align: center;
        }
        
        .alert-item .label {
            font-size: 8pt;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .alert-item .value {
            font-size: 18pt;
            font-weight: 200;
            color: #2c3e50;
        }
        
        .alert-item .value.warning { color: #e67e22; }
        .alert-item .value.danger { color: #c0392b; }
        
        /* ===== SPOTLIGHT ===== */
        .spotlight {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0 20px 0;
        }
        
        .spotlight-card {
            background: #f5f6fa;
            padding: 18px 15px;
            border-left: 3px solid #2c3e50;
        }
        
        .spotlight-label {
            font-size: 7pt;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .spotlight-value {
            font-size: 16pt;
            font-weight: 200;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .spotlight-detail {
            font-size: 8pt;
            color: #95a5a6;
        }
        
        /* ===== TABLAS ===== */
        .table-container {
            margin: 20px 0 30px 0;
            border: 1px solid #ecf0f1;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        
        th {
            background: #f5f6fa;
            color: #2c3e50;
            font-weight: 400;
            text-transform: uppercase;
            font-size: 7pt;
            letter-spacing: 1px;
            padding: 12px 8px;
            border-bottom: 1px solid #dcdde1;
            text-align: left;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ecf0f1;
            color: #34495e;
        }
        
        /* Estados de stock */
        .stock-status {
            display: inline-block;
            padding: 3px 8px;
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 2px;
        }
        
        .stock-critical {
            background: #fdeded;
            color: #c0392b;
            border-left: 2px solid #c0392b;
        }
        
        .stock-low {
            background: #fff4e6;
            color: #e67e22;
            border-left: 2px solid #e67e22;
        }
        
        .stock-medium {
            background: #f5f6fa;
            color: #7f8c8d;
            border-left: 2px solid #7f8c8d;
        }
        
        .stock-optimal {
            background: #e8f5e9;
            color: #27ae60;
            border-left: 2px solid #27ae60;
        }
        
        .rank {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            background: #f5f6fa;
            color: #2c3e50;
            font-size: 8pt;
        }
        
        .rank-1 { background: #2c3e50; color: #ffffff; }
        .rank-2 { background: #7f8c8d; color: #ffffff; }
        .rank-3 { background: #b2bec3; color: #ffffff; }
        
        .badge-dark {
            background: #2c3e50;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-warning {
            background: #e67e22;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-danger {
            background: #c0392b;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .micro-bar {
            display: inline-block;
            width: 40px;
            height: 2px;
            background: #dfe6e9;
            margin-left: 6px;
            vertical-align: middle;
        }
        
        .micro-fill {
            height: 100%;
            background: #2c3e50;
        }
        
        .summary {
            background: #f5f6fa;
            padding: 22px;
            margin: 30px 0 20px 0;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .summary-item {
            border-left: 2px solid #b2bec3;
            padding-left: 15px;
        }
        
        .summary-item p {
            margin-bottom: 8px;
            color: #34495e;
        }
        
        .footer {
            position: fixed;
            bottom: -25px;
            left: 2.2cm;
            right: 2.2cm;
            text-align: center;
            font-size: 7.5pt;
            color: #95a5a6;
            padding-top: 8px;
            border-top: 1px solid #ecf0f1;
        }
        
        .page-number:before {
            content: counter(page);
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        .col-rank { width: 8%; }
        .col-producto { width: 35%; }
        .col-stock { width: 12%; }
        .col-min { width: 10%; }
        .col-max { width: 10%; }
        .col-precio { width: 15%; }
        .col-valor { width: 15%; }
        .col-estado { width: 10%; }
    </style>
</head>
<body>
    <!-- ===== PÁGINA 1: RESUMEN EJECUTIVO Y MÉTRICAS ===== -->
    <div class="page-content">
        <!-- ===== REFERENCIA ===== -->
        <div class="reference">
            <span>REPORTE EJECUTIVO</span> · {{ $fechaGeneracion }} · INV-{{ date('Ymd') }}
        </div>
        
        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>INVENTARIO</h1>
            <div class="subtitle">CONTROL DE STOCK</div>
            <div class="periodo">
                <strong>CIERRE AL {{ date('d/m/Y', strtotime($fechaGeneracion)) }}</strong>
                @if(isset($categoriaSeleccionada) && $categoriaSeleccionada)
                    <span style="margin-left: 12px;" class="badge-dark">{{ $categoriaSeleccionada->nombre ?? 'Categoría' }}</span>
                @endif
            </div>
        </div>
        
        <!-- ===== MÉTRICAS CLAVE ===== -->
        @php
            $totalProductos = $productos->count();
            $totalValorInventario = $productos->sum(function($producto) use ($stockField, $precioField) {
                return $producto->$stockField * $producto->$precioField;
            });
            $productosBajoStock = $productos->filter(function($producto) use ($stockField, $minField) {
                $min = $producto->$minField ?? 5;
                return $producto->$stockField <= $min;
            })->count();
            $productosSinStock = $productos->filter(function($producto) use ($stockField) {
                return $producto->$stockField <= 0;
            })->count();
        @endphp
        
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Total Productos</div>
                <div class="metric-value">{{ number_format($totalProductos) }}</div>
                <div class="metric-desc">SKUs activos</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Valor Inventario</div>
                <div class="metric-value">${{ number_format($totalValorInventario, 0) }}</div>
                <div class="metric-desc">costo total</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Stock Promedio</div>
                <div class="metric-value">{{ number_format($productos->avg($stockField), 1) }}</div>
                <div class="metric-desc">unidades/ producto</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Bajo Stock</div>
                <div class="metric-value" style="color: {{ $productosBajoStock > 0 ? '#e67e22' : '#2c3e50' }};">{{ $productosBajoStock }}</div>
                <div class="metric-desc">productos críticos</div>
            </div>
        </div>
        
        <!-- ===== ALERTAS DE STOCK ===== -->
        @if($productosBajoStock > 0)
        <div class="alert-card">
            <div class="alert-header">
                <span style="color: #e67e22;">⚠️ ALERTAS DE INVENTARIO</span>
            </div>
            <div class="alert-content">
                <div class="alert-grid">
                    <div class="alert-item">
                        <div class="label">Stock Crítico</div>
                        <div class="value danger">{{ $productosSinStock }}</div>
                        <div style="font-size: 7pt; color: #95a5a6;">productos agotados</div>
                    </div>
                    <div class="alert-item">
                        <div class="label">Bajo Mínimo</div>
                        <div class="value warning">{{ $productosBajoStock - $productosSinStock }}</div>
                        <div style="font-size: 7pt; color: #95a5a6;">requieren reposición</div>
                    </div>
                    <div class="alert-item">
                        <div class="label">Stock Óptimo</div>
                        <div class="value">{{ $totalProductos - $productosBajoStock }}</div>
                        <div style="font-size: 7pt; color: #95a5a6;">nivel adecuado</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- ===== SPOTLIGHT ===== -->
        <div class="spotlight">
            @php
                $productoMasValioso = $productos->sortByDesc(function($producto) use ($stockField, $precioField) {
                    return $producto->$stockField * $producto->$precioField;
                })->first();
                
                $productoMayorStock = $productos->sortByDesc($stockField)->first();
            @endphp
            
            @if($productoMasValioso)
            <div class="spotlight-card">
                <div class="spotlight-label">Producto más valioso</div>
                <div class="spotlight-value truncate">{{ $productoMasValioso->nombre }}</div>
                <div class="spotlight-detail">
                    ${{ number_format($productoMasValioso->$stockField * $productoMasValioso->$precioField, 0) }} · 
                    {{ $productoMasValioso->$stockField }} unidades
                </div>
            </div>
            @endif
            
            @if($productoMayorStock)
            <div class="spotlight-card">
                <div class="spotlight-label">Mayor volumen</div>
                <div class="spotlight-value truncate">{{ $productoMayorStock->nombre }}</div>
                <div class="spotlight-detail">{{ $productoMayorStock->$stockField }} unidades en stock</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- ===== PÁGINA 2: DASHBOARD DE INVENTARIO ===== -->
    <div class="dashboard-page">
        <div class="page-content">
            <div class="dashboard-container">
                <div class="dashboard-title">ANÁLISIS DE INVENTARIO</div>
                <div class="dashboard-subtitle">
                    Distribución y valorización del stock
                </div>
                
                <div class="dashboard-grid">
                    <!-- GRÁFICA 1: PRODUCTOS POR RANGO DE STOCK -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Distribución por Nivel</h3>
                            <div class="legend">
                                <div class="legend-item">
                                    <span class="legend-dot primary"></span>
                                    <span>Stock Actual</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot secondary"></span>
                                    <span>Stock Máx</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content">
                            @php
                                $topProductosStock = $productos->sortByDesc($stockField)->take(8);
                                $maxStock = $topProductosStock->max($stockField) ?: 1;
                            @endphp
                            
                            @forelse($topProductosStock as $producto)
                            @php
                                $stockActual = $producto->$stockField;
                                $stockMaximo = $producto->$maxField ?? ($stockActual * 2);
                                $porcentajeActual = ($stockActual / $maxStock) * 100;
                                $porcentajeMaximo = ($stockMaximo / $maxStock) * 100;
                            @endphp
                            <div class="chart-row">
                                <div class="chart-label">{{ $producto->nombre }}</div>
                                <div class="chart-bars">
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width: {{ $porcentajeActual }}%;"></div>
                                    </div>
                                    <div class="dual-indicator">
                                        <div class="fill" style="width: {{ $porcentajeMaximo }}%; background: #95a5a6;"></div>
                                    </div>
                                </div>
                                <div class="chart-value">
                                    {{ $stockActual }} <small>unid.</small>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; color: #95a5a6; padding: 50px;">Sin datos de inventario</div>
                            @endforelse
                        </div>
                        
                        <div class="chart-footer">
                            <div class="total-stats">
                                <span>Total unidades: <strong>{{ $productos->sum($stockField) }}</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- GRÁFICA 2: VALORIZACIÓN POR CATEGORÍA -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Valor por Categoría</h3>
                            <div class="legend">
                                <div class="legend-item">
                                    <span class="legend-dot primary"></span>
                                    <span>Valor Inventario</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot accent"></span>
                                    <span>% del Total</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content">
                            @php
                                $categoriasValor = $productos->groupBy(function($producto) {
                                    return $producto->categoria->nombre ?? 'Sin categoría';
                                })->map(function($items) use ($stockField, $precioField) {
                                    return $items->sum(function($producto) use ($stockField, $precioField) {
                                        return $producto->$stockField * $producto->$precioField;
                                    });
                                })->sortDesc()->take(5);
                                
                                $maxValorCategoria = $categoriasValor->max() ?: 1;
                            @endphp
                            
                            @forelse($categoriasValor as $nombre => $valor)
                            @php
                                $porcentajeValor = ($valor / $maxValorCategoria) * 100;
                            @endphp
                            <div class="chart-row">
                                <div class="chart-label">{{ $nombre }}</div>
                                <div class="chart-bars">
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width: {{ $porcentajeValor }}%; background: #e67e22;"></div>
                                    </div>
                                </div>
                                <div class="chart-value">
                                    ${{ number_format($valor, 0) }}
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; color: #95a5a6; padding: 50px;">Sin datos por categoría</div>
                            @endforelse
                        </div>
                        
                        <div class="chart-footer">
                            <div class="total-stats">
                                <span>Valor total: <strong>${{ number_format($categoriasValor->sum(), 0) }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== PÁGINA 3: TABLA DE INVENTARIO ===== -->
    <div class="page-content">
        <h2>INVENTARIO DETALLADO</h2>
        
        @if(isset($nivelStock) && $nivelStock)
        <div style="margin-bottom: 15px;">
            <span class="badge-dark">Filtro: {{ $nivelStock }}</span>
        </div>
        @endif
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-rank">#</th>
                        <th class="col-producto">Producto</th>
                        <th class="col-stock text-right">Stock</th>
                        <th class="col-min text-right">Mínimo</th>
                        <th class="col-max text-right">Máximo</th>
                        <th class="col-precio text-right">Precio</th>
                        <th class="col-valor text-right">Valor Total</th>
                        <th class="col-estado">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $index => $producto)
                    @php
                        $stockActual = $producto->$stockField;
                        $minimo = $producto->$minField ?? 5;
                        $maximo = $producto->$maxField ?? ($stockActual * 2);
                        $valorTotal = $stockActual * $producto->$precioField;
                        
                        if($stockActual <= 0) {
                            $estado = 'Agotado';
                            $estadoClass = 'stock-critical';
                        } elseif($stockActual <= $minimo) {
                            $estado = 'Crítico';
                            $estadoClass = 'stock-critical';
                        } elseif($stockActual <= $minimo * 2) {
                            $estado = 'Bajo';
                            $estadoClass = 'stock-low';
                        } elseif($stockActual <= $maximo) {
                            $estado = 'Óptimo';
                            $estadoClass = 'stock-optimal';
                        } else {
                            $estado = 'Excedente';
                            $estadoClass = 'stock-medium';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">
                            <span class="rank {{ $index < 3 ? 'rank-' . ($index + 1) : '' }}">{{ $index + 1 }}</span>
                        </td>
                        <td class="truncate">{{ $producto->nombre }}</td>
                        <td class="text-right"><strong>{{ number_format($stockActual) }}</strong></td>
                        <td class="text-right">{{ number_format($minimo) }}</td>
                        <td class="text-right">{{ number_format($maximo) }}</td>
                        <td class="text-right">${{ number_format($producto->$precioField, 0) }}</td>
                        <td class="text-right">${{ number_format($valorTotal, 0) }}</td>
                        <td>
                            <span class="stock-status {{ $estadoClass }}">{{ $estado }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: #95a5a6;">
                            No hay productos para mostrar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- ===== SÍNTESIS ===== -->
        <div class="summary">
            <div style="margin-bottom: 15px; font-size: 11pt; font-weight: 400;">RESUMEN DE INVENTARIO</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <p><strong>Total SKUs activos:</strong> {{ $totalProductos }}</p>
                    <p><strong>Unidades en stock:</strong> {{ number_format($productos->sum($stockField)) }}</p>
                    <p><strong>Valor promedio x SKU:</strong> ${{ number_format($totalValorInventario / max($totalProductos, 1), 0) }}</p>
                </div>
                <div class="summary-item">
                    <p><strong>Productos agotados:</strong> {{ $productosSinStock }}</p>
                    <p><strong>Productos bajo mínimo:</strong> {{ $productosBajoStock - $productosSinStock }}</p>
                    <p><strong>Rotación estimada:</strong> {{ $resumenEjecutivo['rotacion_estimada'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== FOOTER ===== -->
    <htmlpagefooter name="footer" class="footer">
        <span class="page-number"></span> · {{ $fechaGeneracion }} · CONFIDENCIAL
    </htmlpagefooter>
</body>
</html>