<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Rentabilidad</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #2c3e50;
            background: #ffffff;
            margin: 0;
            padding: 15px;
        }
        
        .reporte {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e0e0e0;
        }
        
        .header {
            background: #8e44ad;
            color: white;
            padding: 20px 25px;
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .header-info {
            display: flex;
            gap: 25px;
            font-size: 11px;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            padding: 20px 25px;
            background: white;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid;
        }
        
        .stat-card:nth-child(1) { border-left-color: #8e44ad; }
        .stat-card:nth-child(2) { border-left-color: #e74c3c; }
        .stat-card:nth-child(3) { border-left-color: #27ae60; }
        .stat-card:nth-child(4) { border-left-color: #3498db; }
        
        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .stat-desc {
            font-size: 10px;
            color: #95a5a6;
        }
        
        .section {
            padding: 20px 25px;
            border-top: 1px solid #ecf0f1;
        }
        
        .section-title {
            margin-bottom: 15px;
        }
        
        .section-title h2 {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            padding-bottom: 8px;
            border-bottom: 2px solid #8e44ad;
            display: inline-block;
        }
        
        .section-badge {
            background: #ecf0f1;
            color: #7f8c8d;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            margin-left: 10px;
        }
        
        .graficas-section {
            padding: 20px 25px;
            border-top: 1px solid #ecf0f1;
            page-break-inside: avoid;
        }
        
        .graficas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .grafica-card {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            border: 1px solid #e9ecef;
        }
        
        .grafica-header h3 {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
        }
        
        .grafica-container {
            text-align: center;
            min-height: 200px;
        }
        
        .grafica-container img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        
        .resumen-financiero {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 0 25px 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .resumen-item {
            text-align: center;
        }
        
        .resumen-label {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .resumen-valor {
            font-size: 24px;
            font-weight: 700;
        }
        
        .resumen-valor.ingresos { color: #27ae60; }
        .resumen-valor.costos { color: #e74c3c; }
        .resumen-valor.ganancia { color: #8e44ad; }
        
        .table-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        
        th {
            background: #f1f3f5;
            color: #2c3e50;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .rank {
            display: inline-block;
            width: 22px;
            height: 22px;
            background: #8e44ad;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 22px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .rank-1 { background: #f1c40f; color: #2c3e50; }
        .rank-2 { background: #bdc3c7; }
        .rank-3 { background: #e67e22; }
        
        .product-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .amount {
            font-weight: 600;
            color: #27ae60;
        }
        
        .profit {
            font-weight: 600;
            color: #8e44ad;
        }
        
        .margin-positive {
            background: #e8f5e9;
            color: #27ae60;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .featured-card {
            background: #f8f9fa;
            border: 1px solid #8e44ad;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 0 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .featured-badge {
            background: #8e44ad;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        
        .footer {
            background: #f1f3f5;
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #7f8c8d;
            border-top: 1px solid #dee2e6;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .keep-together {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="reporte">
        <div class="header">
            <h1>REPORTE DE RENTABILIDAD</h1>
            <div class="header-info">
                <div>
                    <strong>Período:</strong> {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}
                </div>
                <div>
                    <strong>Generado:</strong> {{ $fechaGeneracion }}
                </div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Ventas</div>
                <div class="stat-value">${{ number_format($totalVentas, 2) }}</div>
                <div class="stat-desc">Ingresos totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Costos</div>
                <div class="stat-value">${{ number_format($totalCosto, 2) }}</div>
                <div class="stat-desc">Costo de productos</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ganancia Neta</div>
                <div class="stat-value">${{ number_format($totalGanancia, 2) }}</div>
                <div class="stat-desc">Ventas - Costos</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Margen de Ganancia</div>
                <div class="stat-value">{{ $margenPromedio }}%</div>
                <div class="stat-desc">Rentabilidad</div>
            </div>
        </div>
        
        <div class="resumen-financiero">
            <div class="resumen-item">
                <div class="resumen-label">Ingresos</div>
                <div class="resumen-valor ingresos">${{ number_format($totalVentas, 2) }}</div>
                <div>100%</div>
            </div>
            <div class="resumen-item">
                <div class="resumen-label">Costos</div>
                <div class="resumen-valor costos">${{ number_format($totalCosto, 2) }}</div>
                <div>{{ $totalVentas > 0 ? round(($totalCosto/$totalVentas)*100, 1) : 0 }}%</div>
            </div>
            <div class="resumen-item">
                <div class="resumen-label">Ganancia</div>
                <div class="resumen-valor ganancia">${{ number_format($totalGanancia, 2) }}</div>
                <div>{{ $margenPromedio }}%</div>
            </div>
        </div>
        
        @if(!empty($graficas))
        <div class="graficas-section">
            <div class="section-title">
                <h2>ANÁLISIS GRÁFICO</h2>
            </div>
            
            <div class="graficas-grid">
                @if(isset($graficas['comparativa']))
                <div class="grafica-card">
                    <div class="grafica-header">
                        <h3>Ventas vs Costos vs Ganancia</h3>
                    </div>
                    <div class="grafica-container">
                        <img src="{{ $graficas['comparativa'] }}" alt="Comparativa">
                    </div>
                </div>
                @endif

                @if(isset($graficas['margenes']))
                <div class="grafica-card">
                    <div class="grafica-header">
                        <h3>Margen de Ganancia por Producto</h3>
                    </div>
                    <div class="grafica-container">
                        <img src="{{ $graficas['margenes'] }}" alt="Margen de Ganancia">
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="page-break"></div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>PRODUCTOS MÁS RENTABLES</h2>
                <span class="section-badge">Por margen de ganancia</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Vendido</th>
                            <th>Ganancia</th>
                            <th>Margen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasRentables as $index => $item)
                        @php
                            $rankClass = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : ''));
                        @endphp
                        <tr>
                            <td><span class="rank {{ $rankClass }}">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $item['nombre'] }}</span>
                                <div class="category">{{ $item['categoria'] }}</div>
                            </td>
                            <td>{{ $item['total_vendido'] }} und.</td>
                            <td class="profit">${{ number_format($item['ganancia_total'], 2) }}</td>
                            <td><span class="margin-positive">{{ $item['margen_ganancia'] }}%</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No hay productos vendidos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>MAYOR GANANCIA EN VALOR</h2>
                <span class="section-badge">Los que más dinero dejan</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Ventas</th>
                            <th>Costos</th>
                            <th>Ganancia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasGanancia as $index => $item)
                        @php
                            $rankClass = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : ''));
                        @endphp
                        <tr>
                            <td><span class="rank {{ $rankClass }}">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $item['nombre'] }}</span>
                                <div class="category">{{ $item['categoria'] }}</div>
                            </td>
                            <td class="amount">${{ number_format($item['total_ventas'], 2) }}</td>
                            <td>${{ number_format($item['costo_total'], 2) }}</td>
                            <td class="profit">${{ number_format($item['ganancia_total'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No hay productos vendidos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($productosMasRentables->isNotEmpty())
        @php $top = $productosMasRentables->first(); @endphp
        <div class="featured-card">
            <div class="featured-info">
                <h4>PRODUCTO MÁS RENTABLE</h4>
                <p>{{ $top['nombre'] }}</p>
            </div>
            <div class="featured-badge">
                {{ $top['margen_ganancia'] }}% de margen
            </div>
        </div>
        @endif
        
        <div class="footer">
            <div class="footer-info">
                <span>Reporte de Rentabilidad</span>
                <span>{{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</span>
            </div>
            <div>Margen promedio: {{ $margenPromedio }}%</div>
        </div>
    </div>
</body>
</html>