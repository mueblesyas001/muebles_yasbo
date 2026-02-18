<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Ventas | Documento Ejecutivo</title>
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
        
        /* ===== RESTO DE ESTILOS (TABLAS, SPOTLIGHT, ETC) ===== */
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
        }
        
        .col-rank { width: 8%; }
        .col-producto { width: 40%; }
        .col-unidades { width: 12%; }
        .col-ventas { width: 12%; }
        .col-ingresos { width: 15%; }
        .col-folio { width: 10%; }
        .col-fecha { width: 15%; }
        .col-empleado { width: 30%; }
        .col-monto { width: 18%; }
        .col-items { width: 12%; }
    </style>
</head>
<body>
    <!-- ===== PÁGINA 1: RESUMEN EJECUTIVO Y MÉTRICAS ===== -->
    <div class="page-content">
        <!-- ===== REFERENCIA ===== -->
        <div class="reference">
            <span>REPORTE EJECUTIVO</span> · {{ $fechaGeneracion }} · VTS-{{ date('Ymd') }}
        </div>
        
        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>VENTAS</h1>
            <div class="subtitle">INFORME DE GESTIÓN</div>
            <div class="periodo">
                <strong>{{ date('d/m/Y', strtotime($fechaInicio)) }}</strong> — <strong>{{ date('d/m/Y', strtotime($fechaFin)) }}</strong>
                @if($empleadoSeleccionado)
                    <span style="margin-left: 12px;" class="badge-dark">{{ $empleadoSeleccionado->nombre_completo ?? $empleadoSeleccionado->Nombre }}</span>
                @endif
            </div>
        </div>
        
        <!-- ===== MÉTRICAS CLAVE ===== -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Ventas</div>
                <div class="metric-value">{{ number_format($totalVentas) }}</div>
                <div class="metric-desc">transacciones</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Ingresos</div>
                <div class="metric-value">${{ number_format($totalIngresos, 0) }}</div>
                <div class="metric-desc">totales</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Ticket Prom.</div>
                <div class="metric-value">${{ number_format($ventaPromedio, 0) }}</div>
                <div class="metric-desc">por venta</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Frecuencia</div>
                <div class="metric-value">{{ number_format($resumenEjecutivo['ventas_por_dia'], 1) }}</div>
                <div class="metric-desc">ventas/día</div>
            </div>
        </div>
        
        <!-- ===== SPOTLIGHT ===== -->
        <div class="spotlight">
            @if($mejorVenta)
            <div class="spotlight-card">
                <div class="spotlight-label">Mejor venta</div>
                <div class="spotlight-value">${{ number_format($mejorVenta->Total, 2) }}</div>
                <div class="spotlight-detail">Venta #{{ $mejorVenta->id }} · {{ date('d/m/Y', strtotime($mejorVenta->Fecha)) }}</div>
            </div>
            @endif
            
            @if($resumenEjecutivo['empleado_top'] && $resumenEjecutivo['empleado_top']->empleado)
            @php
                $empleado = $resumenEjecutivo['empleado_top']->empleado;
                $nombreCompleto = trim($empleado->Nombre . ' ' . ($empleado->ApPaterno ?? '') . ' ' . ($empleado->ApMaterno ?? ''));
            @endphp
            <div class="spotlight-card">
                <div class="spotlight-label">Mejor desempeño</div>
                <div class="spotlight-value truncate">{{ $nombreCompleto }}</div>
                <div class="spotlight-detail">{{ $resumenEjecutivo['empleado_top']->total_ventas }} ventas · ${{ number_format($resumenEjecutivo['empleado_top']->total_ingresos, 0) }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- ===== PÁGINA 2: DASHBOARD COMPLETO DE GRÁFICAS ===== -->
    <div class="dashboard-page">
        <div class="page-content">
            <div class="dashboard-container">
                <div class="dashboard-title">ANÁLISIS DE RENDIMIENTO</div>
                <div class="dashboard-subtitle">
                    {{ date('d/m/Y', strtotime($fechaInicio)) }} — {{ date('d/m/Y', strtotime($fechaFin)) }}
                </div>
                
                <div class="dashboard-grid">
                    <!-- GRÁFICA 1: VENTAS POR EMPLEADO (COMPLETA) -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Rendimiento por Colaborador</h3>
                            <div class="legend">
                                <div class="legend-item">
                                    <span class="legend-dot primary"></span>
                                    <span>Ventas</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot secondary"></span>
                                    <span>Ingresos</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content">
                            @forelse($datosGraficaEmpleados as $empleado)
                            @php
                                $maxVentas = $datosGraficaEmpleados->max('ventas');
                                $maxIngresos = $datosGraficaEmpleados->max('ingresos');
                                $porcentajeVentas = $maxVentas > 0 ? ($empleado['ventas'] / $maxVentas) * 100 : 0;
                                $porcentajeIngresos = $maxIngresos > 0 ? ($empleado['ingresos'] / $maxIngresos) * 100 : 0;
                            @endphp
                            <div class="chart-row">
                                <div class="chart-label">{{ $empleado['nombre'] }}</div>
                                <div class="chart-bars">
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width: {{ $porcentajeVentas }}%;"></div>
                                    </div>
                                    <div class="dual-indicator">
                                        <div class="fill" style="width: {{ $porcentajeIngresos }}%;"></div>
                                    </div>
                                </div>
                                <div class="chart-value">
                                    {{ $empleado['ventas'] }} <small>ventas</small>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; color: #95a5a6; padding: 50px;">Sin datos de empleados</div>
                            @endforelse
                        </div>
                        
                        <div class="chart-footer">
                            <div class="total-stats">
                                <span>Total: <strong>{{ $datosGraficaEmpleados->sum('ventas') }}</strong> ventas · <strong>${{ number_format($datosGraficaEmpleados->sum('ingresos'), 0) }}</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- GRÁFICA 2: PRODUCTOS MÁS VENDIDOS (COMPLETA) -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Top Productos</h3>
                            <div class="legend">
                                <div class="legend-item">
                                    <span class="legend-dot primary"></span>
                                    <span>Unidades</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot secondary"></span>
                                    <span>Ingresos</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content">
                            @forelse($datosGraficaProductos as $producto)
                            @php
                                $maxCantidad = $datosGraficaProductos->max('cantidad');
                                $maxIngresosProd = $datosGraficaProductos->max('ingresos');
                                $porcentajeCantidad = $maxCantidad > 0 ? ($producto['cantidad'] / $maxCantidad) * 100 : 0;
                                $porcentajeIngresosProd = $maxIngresosProd > 0 ? ($producto['ingresos'] / $maxIngresosProd) * 100 : 0;
                            @endphp
                            <div class="chart-row">
                                <div class="chart-label">{{ $producto['nombre'] }}</div>
                                <div class="chart-bars">
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width: {{ $porcentajeCantidad }}%;"></div>
                                    </div>
                                    <div class="dual-indicator">
                                        <div class="fill" style="width: {{ $porcentajeIngresosProd }}%;"></div>
                                    </div>
                                </div>
                                <div class="chart-value">
                                    {{ $producto['cantidad'] }} <small>unid.</small>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; color: #95a5a6; padding: 50px;">Sin datos de productos</div>
                            @endforelse
                        </div>
                        
                        <div class="chart-footer">
                            <div class="total-stats">
                                <span>Total: <strong>{{ $datosGraficaProductos->sum('cantidad') }}</strong> unidades · <strong>${{ number_format($datosGraficaProductos->sum('ingresos'), 0) }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== PÁGINA 3: TABLAS Y DETALLE ===== -->
    <div class="page-content">
        <!-- ===== TOP PRODUCTOS ===== -->
        @if($productosMasVendidos->count() > 0)
        <h2>PRODUCTOS</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-rank">#</th>
                        <th class="col-producto">Producto</th>
                        <th class="col-unidades text-right">Unidades</th>
                        <th class="col-ventas text-right">Ventas</th>
                        <th class="col-ingresos text-right">Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosMasVendidos->take(10) as $index => $item)
                    <tr>
                        <td class="text-center">
                            <span class="rank {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">{{ $index + 1 }}</span>
                        </td>
                        <td class="truncate">{{ $item->producto->Nombre ?? 'Producto eliminado' }}</td>
                        <td class="text-right">{{ number_format($item->total_vendido) }}</td>
                        <td class="text-right">{{ $item->veces_vendido }}</td>
                        <td class="text-right">${{ number_format($item->total_ingresos, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- ===== RENDIMIENTO POR EMPLEADO ===== -->
        @if($ventasPorEmpleado->count() > 0)
        <h2>COLABORADORES</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 35%;">Nombre</th>
                        <th style="width: 13%;" class="text-right">Ventas</th>
                        <th style="width: 20%;" class="text-right">Ingresos</th>
                        <th style="width: 15%;" class="text-right">Promedio</th>
                        <th style="width: 17%;" class="text-right">Contribución</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventasPorEmpleado as $index => $ventaEmpleado)
                    @php
                        $porcentaje = $totalIngresos > 0 ? ($ventaEmpleado->total_ingresos / $totalIngresos) * 100 : 0;
                        $empleado = $ventaEmpleado->empleado;
                        $nombreEmpleado = $empleado 
                            ? trim(($empleado->Nombre ?? '') . ' ' . ($empleado->ApPaterno ?? '') . ' ' . ($empleado->ApMaterno ?? ''))
                            : '—';
                    @endphp
                    <tr>
                        <td class="truncate">
                            @if($index == 0)
                                <span class="badge-dark" style="margin-right: 5px;">TOP</span>
                            @endif
                            {{ $nombreEmpleado }}
                        </td>
                        <td class="text-right">{{ $ventaEmpleado->total_ventas }}</td>
                        <td class="text-right">${{ number_format($ventaEmpleado->total_ingresos, 2) }}</td>
                        <td class="text-right">${{ number_format($ventaEmpleado->promedio_venta, 2) }}</td>
                        <td class="text-right">
                            {{ number_format($porcentaje, 1) }}%
                            <span class="micro-bar">
                                <span class="micro-fill" style="width: {{ $porcentaje }}%;"></span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- ===== TRANSACCIONES ===== -->
        @if($ventas->count() > 0)
        <h2>TRANSACCIONES</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-folio">Folio</th>
                        <th class="col-fecha">Fecha</th>
                        <th class="col-empleado">Empleado</th>
                        <th class="col-monto text-right">Monto</th>
                        <th class="col-items text-center">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas->take(12) as $venta)
                    @php
                        $nombreEmpleado = $venta->empleado 
                            ? trim(($venta->empleado->Nombre ?? '') . ' ' . ($venta->empleado->ApPaterno ?? '') . ' ' . ($venta->empleado->ApMaterno ?? ''))
                            : '—';
                    @endphp
                    <tr>
                        <td>#{{ str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ date('d/m', strtotime($venta->Fecha)) }}</td>
                        <td class="truncate">{{ $nombreEmpleado }}</td>
                        <td class="text-right">${{ number_format($venta->Total, 2) }}</td>
                        <td class="text-center">{{ $venta->detalleVentas->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- ===== DETALLE ===== -->
        @if($detalleVentas->count() > 0)
        <h2>DETALLE</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 35%;">Producto</th>
                        <th style="width: 15%;" class="text-right">Precio</th>
                        <th style="width: 12%;" class="text-right">Cantidad</th>
                        <th style="width: 20%;" class="text-right">Subtotal</th>
                        <th style="width: 18%;" class="text-center">Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detalleVentas->take(15) as $detalle)
                    <tr>
                        <td class="truncate">{{ $detalle->producto->Nombre ?? '—' }}</td>
                        <td class="text-right">${{ number_format($detalle->producto->Precio ?? 0, 2) }}</td>
                        <td class="text-right">{{ $detalle->Cantidad }}</td>
                        <td class="text-right">${{ number_format(($detalle->producto->Precio ?? 0) * $detalle->Cantidad, 2) }}</td>
                        <td class="text-center">#{{ str_pad($detalle->Venta, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- ===== SÍNTESIS ===== -->
        <div class="summary">
            <div style="margin-bottom: 15px; font-size: 11pt; font-weight: 400;">RESUMEN EJECUTIVO</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <p><strong>Período analizado:</strong> {{ $resumenEjecutivo['dias_periodo'] }} días</p>
                    <p><strong>Días con ventas:</strong> {{ $resumenEjecutivo['dias_con_ventas'] }}</p>
                    <p><strong>Días sin ventas:</strong> {{ $resumenEjecutivo['dias_sin_ventas'] }}</p>
                </div>
                <div class="summary-item">
                    <p><strong>Ingreso promedio diario:</strong> ${{ number_format($resumenEjecutivo['ingresos_por_dia'], 2) }}</p>
                    <p><strong>Producto más vendido:</strong> <span class="truncate">{{ $productosMasVendidos->first()->producto->Nombre ?? '—' }}</span></p>
                    <p><strong>Contribución top producto:</strong> {{ number_format(($productosMasVendidos->first()->total_ingresos / $totalIngresos) * 100, 1) }}%</p>
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