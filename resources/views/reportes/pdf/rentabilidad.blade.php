<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Rentabilidad | Análisis Financiero</title>
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
            margin: 35px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #2c3e50;
        }
        
        h3 {
            font-size: 11pt;
            font-weight: 400;
            color: #34495e;
            margin: 20px 0 10px 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 25%;
            width: 50%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #7f8c8d, transparent);
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
        
        /* ===== MÉTRICAS CLAVE ===== */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin: 25px 0 30px 0;
        }
        
        .metric-card {
            background: #ffffff;
            border: 1px solid #e8e8e8;
            padding: 18px 5px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .metric-label {
            font-size: 7.5pt;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        
        .metric-value {
            font-size: 18pt;
            font-weight: 200;
            color: #2c3e50;
            line-height: 1.2;
        }
        
        .metric-desc {
            font-size: 7pt;
            color: #95a5a6;
            margin-top: 5px;
        }
        
        /* ===== GRÁFICAS ===== */
        .dashboard-section {
            margin: 30px 0;
            background: #ffffff;
            border: 1px solid #ecf0f1;
            padding: 20px 18px;
        }
        
        .dashboard-title {
            font-size: 12pt;
            font-weight: 400;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ecf0f1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .chart-card {
            background: #f9f9f9;
            padding: 15px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .chart-header h4 {
            font-size: 10pt;
            font-weight: 400;
            color: #2c3e50;
        }
        
        .legend {
            display: flex;
            gap: 12px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            font-size: 7pt;
            color: #7f8c8d;
        }
        
        .legend-dot {
            width: 10px;
            height: 10px;
            margin-right: 4px;
        }
        
        .legend-dot.primary { background: #2c3e50; }
        .legend-dot.success { background: #27ae60; }
        .legend-dot.danger { background: #e74c3c; }
        .legend-dot.warning { background: #f39c12; }
        
        .chart-row {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .chart-label {
            width: 30%;
            font-size: 8.5pt;
            color: #34495e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 10px;
        }
        
        .chart-bars {
            width: 50%;
            position: relative;
        }
        
        .bar-container {
            height: 24px;
            background: #ecf0f1;
            position: relative;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .bar-fill {
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
        }
        
        .bar-fill.primary { background: #2c3e50; }
        .bar-fill.success { background: #27ae60; }
        .bar-fill.danger { background: #e74c3c; }
        .bar-fill.warning { background: #f39c12; }
        
        .chart-value {
            width: 20%;
            font-size: 8.5pt;
            text-align: right;
            padding-left: 8px;
            font-weight: 400;
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
            background: #2c3e50;
            color: white;
            font-weight: 400;
            text-transform: uppercase;
            font-size: 7pt;
            letter-spacing: 0.5px;
            padding: 10px 6px;
            text-align: left;
        }
        
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #efefef;
            color: #34495e;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 6.5pt;
            border-radius: 12px;
        }
        
        .badge-success {
            background: #27ae60;
            color: white;
        }
        
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        
        .badge-danger {
            background: #e74c3c;
            color: white;
        }
        
        /* ===== GRÁFICA DE BARRAS DOBLES ===== */
        .double-bar {
            display: flex;
            height: 30px;
            width: 100%;
            background: #ecf0f1;
            border-radius: 3px;
            overflow: hidden;
            margin: 5px 0;
        }
        
        .double-bar .bar-ventas {
            height: 100%;
            background: #2c3e50;
        }
        
        .double-bar .bar-costo {
            height: 100%;
            background: #95a5a6;
        }
        
        .double-bar .bar-ganancia {
            height: 100%;
            background: #27ae60;
        }
        
        /* ===== COMPARATIVA ===== */
        .comparison-card {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ecf0f1;
            margin: 15px 0;
        }
        
        .comparison-item {
            text-align: center;
        }
        
        .comparison-label {
            font-size: 8pt;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .comparison-value {
            font-size: 16pt;
            font-weight: 200;
            color: #2c3e50;
        }
        
        .divider {
            height: 1px;
            background: #ecf0f1;
            margin: 25px 0;
        }
        
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #95a5a6;
            padding-top: 6px;
            border-top: 1px solid #ecf0f1;
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
            <span>REPORTE DE RENTABILIDAD</span> · {{ $fechaGeneracion }}
        </div>
        
        <!-- HEADER -->
        <div class="header">
            <h1>RENTABILIDAD</h1>
            <div class="periodo">
                <strong>{{ date('d/m/Y', strtotime($fechaInicio)) }}</strong> — <strong>{{ date('d/m/Y', strtotime($fechaFin)) }}</strong>
                @if($categoriaSeleccionada)
                    · <span style="background: #f0f0f0; padding: 2px 8px;">{{ $categoriaSeleccionada->Nombre }}</span>
                @endif
            </div>
        </div>
        
        <!-- MÉTRICAS CLAVE -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Ventas Totales</div>
                <div class="metric-value">${{ number_format($totalVentas, 2) }}</div>
                <div class="metric-desc">ingresos brutos</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Costo Total</div>
                <div class="metric-value">${{ number_format($totalCosto, 2) }}</div>
                <div class="metric-desc">costo de ventas</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Ganancia Total</div>
                <div class="metric-value">${{ number_format($totalGanancia, 2) }}</div>
                <div class="metric-desc">utilidad bruta</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Margen Promedio</div>
                <div class="metric-value">{{ $margenPromedio }}%</div>
                <div class="metric-desc">rentabilidad</div>
            </div>
        </div>
        
        <!-- GRÁFICA COMPARATIVA VENTAS VS COSTO VS GANANCIA -->
        <div class="dashboard-section">
            <div class="dashboard-title">ANÁLISIS COMPARATIVO</div>
            <div class="comparison-card">
                @foreach($datosGraficaComparativa as $item)
                <div class="comparison-item">
                    <div class="comparison-label">{{ $item['concepto'] }}</div>
                    <div class="comparison-value">${{ number_format($item['valor'], 2) }}</div>
                </div>
                @endforeach
            </div>
            
            <div style="margin-top: 20px;">
                <div style="display: flex; gap: 20px; justify-content: center;">
                    <div class="legend-item"><span class="legend-dot primary"></span> Ventas: ${{ number_format($totalVentas, 2) }}</div>
                    <div class="legend-item"><span class="legend-dot warning"></span> Costo: ${{ number_format($totalCosto, 2) }}</div>
                    <div class="legend-item"><span class="legend-dot success"></span> Ganancia: ${{ number_format($totalGanancia, 2) }}</div>
                </div>
            </div>
        </div>
        
        <!-- GRÁFICAS DE RENTABILIDAD POR PRODUCTO -->
        <div class="dashboard-section">
            <div class="dashboard-title">TOP 10 PRODUCTOS MÁS RENTABLES</div>
            
            @if($datosGraficaRentabilidad->count() > 0)
            <div class="chart-card">
                <div class="chart-header">
                    <h4>Ventas vs Costo vs Ganancia</h4>
                    <div class="legend">
                        <div class="legend-item"><span class="legend-dot primary"></span> Ventas</div>
                        <div class="legend-item"><span class="legend-dot warning"></span> Costo</div>
                        <div class="legend-item"><span class="legend-dot success"></span> Ganancia</div>
                    </div>
                </div>
                
                @foreach($datosGraficaRentabilidad as $producto)
                @php
                    $maxValor = $datosGraficaRentabilidad->max('ventas');
                    $porcentajeVentas = $maxValor > 0 ? ($producto['ventas'] / $maxValor) * 100 : 0;
                    $porcentajeCosto = $maxValor > 0 ? ($producto['costo'] / $maxValor) * 100 : 0;
                    $porcentajeGanancia = $maxValor > 0 ? ($producto['ganancia'] / $maxValor) * 100 : 0;
                @endphp
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 8.5pt; margin-bottom: 3px;">{{ $producto['nombre'] }}</div>
                    <div class="double-bar">
                        <div class="bar-ventas" style="width: {{ $porcentajeVentas }}%;"></div>
                    </div>
                    <div class="double-bar" style="margin-top: 2px;">
                        <div class="bar-costo" style="width: {{ $porcentajeCosto }}%;"></div>
                    </div>
                    <div class="double-bar" style="margin-top: 2px;">
                        <div class="bar-ganancia" style="width: {{ $porcentajeGanancia }}%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 7pt; color: #7f8c8d; margin-top: 2px;">
                        <span>V: ${{ number_format($producto['ventas'], 2) }}</span>
                        <span>C: ${{ number_format($producto['costo'], 2) }}</span>
                        <span>G: ${{ number_format($producto['ganancia'], 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="text-align: center; color: #95a5a6; padding: 30px;">No hay datos de productos rentables</p>
            @endif
        </div>
        
        <!-- GRÁFICA POR CATEGORÍAS -->
        @if($datosGraficaCategorias->count() > 0)
        <div class="dashboard-section">
            <div class="dashboard-title">RENTABILIDAD POR CATEGORÍA</div>
            
            <div class="charts-grid">
                @foreach($datosGraficaCategorias as $categoria)
                @php
                    $maxValorCat = $datosGraficaCategorias->max('ventas');
                    $porcentajeVentasCat = $maxValorCat > 0 ? ($categoria['ventas'] / $maxValorCat) * 100 : 0;
                    $porcentajeGananciaCat = $maxValorCat > 0 ? ($categoria['ganancia'] / $maxValorCat) * 100 : 0;
                @endphp
                <div class="chart-card">
                    <h4 style="margin-bottom: 10px;">{{ $categoria['categoria'] }}</h4>
                    <div class="chart-row">
                        <div class="chart-label">Ventas</div>
                        <div class="chart-bars">
                            <div class="bar-container">
                                <div class="bar-fill primary" style="width: {{ $porcentajeVentasCat }}%;"></div>
                            </div>
                        </div>
                        <div class="chart-value">${{ number_format($categoria['ventas'], 2) }}</div>
                    </div>
                    <div class="chart-row">
                        <div class="chart-label">Ganancia</div>
                        <div class="chart-bars">
                            <div class="bar-container">
                                <div class="bar-fill success" style="width: {{ $porcentajeGananciaCat }}%;"></div>
                            </div>
                        </div>
                        <div class="chart-value">${{ number_format($categoria['ganancia'], 2) }}</div>
                    </div>
                    <div style="margin-top: 8px; font-size: 8pt; color: #7f8c8d;">
                        Margen: {{ number_format(($categoria['ganancia'] / max($categoria['ventas'], 1)) * 100, 1) }}%
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- TABLA DE PRODUCTOS MÁS RENTABLES -->
        @if($productosMasRentables->count() > 0)
        <h2>PRODUCTOS MÁS RENTABLES</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-right">Vendido</th>
                        <th class="text-right">Ventas</th>
                        <th class="text-right">Costo</th>
                        <th class="text-right">Ganancia</th>
                        <th class="text-right">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosMasRentables as $producto)
                    <tr>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>{{ $producto['categoria'] }}</td>
                        <td class="text-right">{{ $producto['total_vendido'] }}</td>
                        <td class="text-right">${{ number_format($producto['total_ventas'], 2) }}</td>
                        <td class="text-right">${{ number_format($producto['costo_total'], 2) }}</td>
                        <td class="text-right">${{ number_format($producto['ganancia_total'], 2) }}</td>
                        <td class="text-right">
                            <span class="badge {{ $producto['margen_ganancia'] > 50 ? 'badge-success' : ($producto['margen_ganancia'] > 20 ? 'badge-warning' : 'badge-danger') }}">
                                {{ $producto['margen_ganancia'] }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- TABLA DE PRODUCTOS SIN VENTAS -->
        @if($productosSinVentas->count() > 0)
        <h2>PRODUCTOS SIN VENTAS</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Valor en Inventario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosSinVentas->take(15) as $producto)
                    <tr>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>{{ $producto['categoria'] }}</td>
                        <td class="text-right">{{ $producto['stock'] }}</td>
                        <td class="text-right">${{ number_format($producto['precio_venta'], 2) }}</td>
                        <td class="text-right">${{ number_format($producto['valor_inventario'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($productosSinVentas->count() > 15)
            <div style="padding: 8px; text-align: center; font-size: 8pt; color: #7f8c8d;">
                ... y {{ $productosSinVentas->count() - 15 }} productos más sin ventas
            </div>
            @endif
        </div>
        @endif
        
        <!-- ANÁLISIS POR CATEGORÍA -->
        @if($analisisCategorias->count() > 0)
        <h2>ANÁLISIS POR CATEGORÍA</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th class="text-right">Productos</th>
                        <th class="text-right">Vendidos</th>
                        <th class="text-right">Ventas</th>
                        <th class="text-right">Ganancia</th>
                        <th class="text-right">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analisisCategorias as $categoria => $datos)
                    <tr>
                        <td><strong>{{ $categoria }}</strong></td>
                        <td class="text-right">{{ $datos['cantidad_productos'] }}</td>
                        <td class="text-right">{{ $datos['productos_vendidos'] }}</td>
                        <td class="text-right">${{ number_format($datos['total_ventas'], 2) }}</td>
                        <td class="text-right">${{ number_format($datos['total_ganancia'], 2) }}</td>
                        <td class="text-right">{{ number_format($datos['margen_promedio'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    
    <!-- FOOTER -->
    <htmlpagefooter name="footer" class="footer">
        <span class="page-number"></span> · {{ $fechaGeneracion }} · CONFIDENCIAL
    </htmlpagefooter>
</body>
</html>