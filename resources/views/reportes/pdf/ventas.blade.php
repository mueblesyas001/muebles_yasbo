<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>
    <style>
        /* ===== RESET Y ESTILOS BASE ===== */
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
            background: #2c3e50;
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
        
        .header-info div {
            display: flex;
            gap: 5px;
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
        
        .stat-card:nth-child(1) { border-left-color: #3498db; }
        .stat-card:nth-child(2) { border-left-color: #2ecc71; }
        .stat-card:nth-child(3) { border-left-color: #f39c12; }
        .stat-card:nth-child(4) { border-left-color: #e74c3c; }
        
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
            border-bottom: 2px solid #3498db;
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
        
        .grafica-header {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .grafica-header h3 {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
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
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .table-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 15px;
            page-break-inside: avoid;
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
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background: #f8f9fa;
        }
        
        .rank {
            display: inline-block;
            width: 22px;
            height: 22px;
            background: #3498db;
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
        
        .category {
            color: #7f8c8d;
            font-size: 9px;
        }
        
        .amount {
            font-weight: 600;
            color: #27ae60;
        }
        
        .featured-card {
            background: #f8f9fa;
            border: 1px solid #3498db;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 0 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        
        .featured-info h4 {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 3px;
            font-weight: normal;
        }
        
        .featured-info p {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .featured-badge {
            background: #3498db;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 0 25px 20px;
            page-break-inside: avoid;
        }
        
        .metric-card {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        
        .metric-title {
            font-size: 11px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .metric-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 10px;
        }
        
        .metric-label {
            color: #7f8c8d;
        }
        
        .metric-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .metric-value.success { color: #27ae60; }
        .metric-value.warning { color: #f39c12; }
        .metric-value.danger { color: #e74c3c; }
        
        hr {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 6px 0;
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
        
        .footer-info {
            display: flex;
            gap: 20px;
        }
        
        .text-success { color: #27ae60; }
        .text-warning { color: #f39c12; }
        .text-danger { color: #e74c3c; }
        .text-center { text-align: center; }
        
        .page-break {
            page-break-after: always;
        }
        
        .keep-together {
            page-break-inside: avoid;
        }
        
        .graficas-wrapper {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="reporte">
        <div class="header">
            <h1>REPORTE DE VENTAS</h1>
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
                <div class="stat-value">{{ $totalVentas }}</div>
                <div class="stat-desc">Transacciones realizadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Ingresos</div>
                <div class="stat-value">${{ number_format($totalIngresos, 2) }}</div>
                <div class="stat-desc">Ventas brutas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Venta Promedio</div>
                <div class="stat-value">${{ number_format($ventaPromedio, 2) }}</div>
                <div class="stat-desc">Por transacción</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Días con Ventas</div>
                <div class="stat-value">{{ $resumenEjecutivo['dias_con_ventas'] ?? 0 }}/{{ $resumenEjecutivo['dias_periodo'] ?? 0 }}</div>
                <div class="stat-desc">{{ $resumenEjecutivo['dias_sin_ventas'] ?? 0 }} días sin ventas</div>
            </div>
        </div>
        
        @if(!empty($graficas))
        <div class="graficas-wrapper">
            <div class="graficas-section">
                <div class="section-title">
                    <h2>ANÁLISIS GRÁFICO</h2>
                </div>
                
                <div class="graficas-grid">
                    @if(isset($graficas['diaria']))
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Ventas Diarias</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['diaria'] }}" alt="Ventas Diarias">
                        </div>
                    </div>
                    @endif

                    @if(isset($graficas['empleados']))
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Ventas por Vendedor</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['empleados'] }}" alt="Ventas por Vendedor">
                        </div>
                    </div>
                    @endif
                </div>

                @if(isset($graficas['productos']))
                <div style="margin-top: 20px;">
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Productos Más Vendidos</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['productos'] }}" alt="Productos más vendidos">
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="page-break"></div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>PRODUCTOS MÁS VENDIDOS</h2>
                <span class="section-badge">Top {{ $productosMasVendidos->count() }}</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">Producto</th>
                            <th width="20%">Categoría</th>
                            <th width="15%">Cantidad</th>
                            <th width="20%">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasVendidos as $index => $producto)
                        @php
                            $categoria = $producto->producto->categoria->Nombre ?? 'Sin categoría';
                            
                            $rankClass = '';
                            if($index == 0) $rankClass = 'rank-1';
                            elseif($index == 1) $rankClass = 'rank-2';
                            elseif($index == 2) $rankClass = 'rank-3';
                        @endphp
                        <tr>
                            <td><span class="rank {{ $rankClass }}">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $producto->producto->Nombre ?? 'N/A' }}</span>
                                @if($producto->producto)
                                <div class="category">ID: {{ $producto->producto->id }}</div>
                                @endif
                            </td>
                            <td>{{ $categoria }}</td>
                            <td>{{ number_format($producto->total_vendido) }} und.</td>
                            <td class="amount">${{ number_format($producto->total_ingresos ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px;">
                                No hay productos vendidos en este período
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($productosMasVendidos->isNotEmpty())
        @php $top = $productosMasVendidos->first(); @endphp
        <div class="featured-card">
            <div class="featured-info">
                <h4>PRODUCTO DESTACADO</h4>
                <p>{{ $top->producto->Nombre ?? 'N/A' }}</p>
            </div>
            <div class="featured-badge">
                {{ number_format($top->total_vendido) }} unidades
            </div>
        </div>
        @endif
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>RENDIMIENTO POR VENDEDOR</h2>
                <span class="section-badge">{{ $ventasPorEmpleado->count() }} vendedores</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Vendedor</th>
                            <th width="15%">Ventas</th>
                            <th width="25%">Total</th>
                            <th width="20%">Ticket Prom.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventasPorEmpleado as $index => $venta)
                        @php
                            $numVentas = \App\Models\Venta::where('Empleado_idEmpleado', $venta->empleado->id ?? 0)
                                ->whereDate('Fecha', '>=', $fechaInicio)
                                ->whereDate('Fecha', '<=', $fechaFin)
                                ->count();
                            $ticketPromedio = $numVentas > 0 ? $venta->total / $numVentas : 0;
                        @endphp
                        <tr>
                            <td><span class="rank">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $venta->empleado->Nombre ?? 'Sin vendedor' }} {{ $venta->empleado->ApPaterno ?? '' }}</span>
                                @if($venta->empleado && isset($venta->empleado->cargo))
                                <div class="category">{{ $venta->empleado->cargo }}</div>
                                @endif
                            </td>
                            <td>{{ $numVentas }}</td>
                            <td class="amount">${{ number_format($venta->total ?? 0, 2) }}</td>
                            <td>${{ number_format($ticketPromedio, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px;">
                                No hay ventas en este período
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-title">MÉTRICAS DE TIEMPO</div>
                <div class="metric-row">
                    <span class="metric-label">Días del período:</span>
                    <span class="metric-value">{{ $resumenEjecutivo['dias_periodo'] ?? 0 }}</span>
                </div>
                <hr>
                <div class="metric-row">
                    <span class="metric-label">Días con ventas:</span>
                    <span class="metric-value success">{{ $resumenEjecutivo['dias_con_ventas'] ?? 0 }}</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Días sin ventas:</span>
                    <span class="metric-value danger">{{ $resumenEjecutivo['dias_sin_ventas'] ?? 0 }}</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-title">PROMEDIOS</div>
                <div class="metric-row">
                    <span class="metric-label">Venta diaria:</span>
                    <span class="metric-value">${{ number_format($totalIngresos / max($resumenEjecutivo['dias_periodo'] ?? 1, 1), 2) }}</span>
                </div>
                <hr>
                <div class="metric-row">
                    <span class="metric-label">Por día con ventas:</span>
                    <span class="metric-value success">${{ number_format($totalIngresos / max($resumenEjecutivo['dias_con_ventas'] ?? 1, 1), 2) }}</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Por transacción:</span>
                    <span class="metric-value">${{ number_format($ventaPromedio, 2) }}</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-title">RANKINGS</div>
                <div class="metric-row">
                    <span class="metric-label">Mejor vendedor:</span>
                    <span class="metric-value">{{ $ventasPorEmpleado->first()->empleado->Nombre ?? 'N/A' }}</span>
                </div>
                <hr>
                <div class="metric-row">
                    <span class="metric-label">Mejor producto:</span>
                    <span class="metric-value success">{{ $productosMasVendidos->first()->producto->Nombre ?? 'N/A' }}</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Total productos:</span>
                    <span class="metric-value">{{ $productosMasVendidos->count() }}</span>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-info">
                <span>Reporte de Ventas</span>
                <span>{{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</span>
            </div>
            <div>
                Generado: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>