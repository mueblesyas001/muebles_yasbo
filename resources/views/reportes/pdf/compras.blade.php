<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Compras | Documento Ejecutivo</title>
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
        
        h3 {
            font-size: 11pt;
            font-weight: 400;
            color: #2c3e50;
            margin: 25px 0 15px 0;
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
        .legend-dot.info { background: #3498db; }
        
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
        
        .bar-fill.accent {
            background: #e67e22;
        }
        
        .bar-fill.info {
            background: #3498db;
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
        
        .table-compact td {
            padding: 6px 8px;
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
        
        .badge-info {
            background: #3498db;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: #27ae60;
            color: #ffffff;
            padding: 3px 8px;
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
        
        .font-mono {
            font-family: 'Courier New', monospace;
            font-size: 8pt;
        }
        
        .col-fecha { width: 12%; }
        .col-proveedor { width: 20%; }
        .col-factura { width: 12%; }
        .col-productos { width: 10%; }
        .col-total { width: 12%; }
        .col-detalle { width: 34%; }
    </style>
</head>
<body>
    <!-- ===== PÁGINA 1: RESUMEN EJECUTIVO Y MÉTRICAS ===== -->
    <div class="page-content">
        <!-- ===== REFERENCIA ===== -->
        <div class="reference">
            <span>REPORTE DE COMPRAS</span> · {{ $fechaGeneracion }} · CMP-{{ date('Ymd') }}
        </div>
        
        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>COMPRAS</h1>
            <div class="subtitle">REGISTRO DE ADQUISICIONES</div>
            <div class="periodo">
                <strong>{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong>
                @if(isset($proveedorSeleccionado) && $proveedorSeleccionado)
                    <span style="margin-left: 12px;" class="badge-dark">{{ $proveedorSeleccionado->nombre_completo ?? 'Proveedor' }}</span>
                @endif
            </div>
        </div>
        
        <!-- ===== MÉTRICAS CLAVE ===== -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Total Compras</div>
                <div class="metric-value">{{ number_format($totalCompras) }}</div>
                <div class="metric-desc">transacciones</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Total Egresos</div>
                <div class="metric-value">${{ number_format($totalEgresos, 0) }}</div>
                <div class="metric-desc">inversión total</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Compra Promedio</div>
                <div class="metric-value">${{ number_format($compraPromedio, 0) }}</div>
                <div class="metric-desc">por transacción</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Días del período</div>
                <div class="metric-value">{{ $diasPeriodo ?? 0 }}</div>
                <div class="metric-desc">días analizados</div>
            </div>
        </div>
        
        <!-- ===== SPOTLIGHT ===== -->
        <div class="spotlight">
            @php
                $compraMayor = $compras->sortByDesc('Total')->first();
                $proveedorTop = $comprasPorProveedor->first();
            @endphp
            
            @if($compraMayor)
            <div class="spotlight-card">
                <div class="spotlight-label">Compra más alta</div>
                <div class="spotlight-value">${{ number_format($compraMayor->Total, 0) }}</div>
                <div class="spotlight-detail">
                    {{ $compraMayor->proveedor->nombre_completo ?? 'Proveedor' }} · 
                    {{ \Carbon\Carbon::parse($compraMayor->Fecha_compra)->format('d/m/Y') }}
                </div>
            </div>
            @endif
            
            @if($proveedorTop)
            <div class="spotlight-card">
                <div class="spotlight-label">Principal proveedor</div>
                <div class="spotlight-value truncate">{{ $proveedorTop->proveedor->nombre_completo ?? 'Proveedor' }}</div>
                <div class="spotlight-detail">
                    {{ $proveedorTop->total_compras }} compras · 
                    ${{ number_format($proveedorTop->total_egresos, 0) }}
                </div>
            </div>
            @endif
        </div>
        
        <!-- ===== DÍA PICO ===== -->
        @if(isset($diaPico) && $diaPico)
        <div style="margin-top: 15px; padding: 15px; background: #f5f6fa; border-left: 3px solid #3498db;">
            <div style="font-size: 7pt; color: #7f8c8d; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                DÍA DE MAYOR ACTIVIDAD
            </div>
            <div style="font-size: 14pt; font-weight: 200;">
                {{ \Carbon\Carbon::parse($diaPico->fecha)->format('d/m/Y') }}
            </div>
            <div style="font-size: 9pt; color: #34495e;">
                {{ $diaPico->cantidad }} compras · ${{ number_format($diaPico->total, 0) }}
            </div>
        </div>
        @endif
    </div>
    
    <!-- ===== PÁGINA 2: DASHBOARD DE COMPRAS ===== -->
    <div class="dashboard-page">
        <div class="page-content">
            <div class="dashboard-container">
                <div class="dashboard-title">ANÁLISIS DE COMPRAS</div>
                <div class="dashboard-subtitle">
                    Distribución por proveedor y productos más adquiridos
                </div>
                
                <div class="dashboard-grid">
                    <!-- GRÁFICA 1: COMPRAS POR PROVEEDOR -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Compras por Proveedor</h3>
                            <div class="legend">
                                <div class="legend-item">
                                    <span class="legend-dot primary"></span>
                                    <span>Monto</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot secondary"></span>
                                    <span>Transacciones</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content">
                            @php
                                $topProveedores = $comprasPorProveedor->take(6);
                                $maxMonto = $topProveedores->max('total_egresos') ?: 1;
                            @endphp
                            
                            @forelse($topProveedores as $proveedor)
                            @php
                                $porcentajeMonto = ($proveedor->total_egresos / $maxMonto) * 100;
                            @endphp
                            <div class="chart-row">
                                <div class="chart-label">{{ $proveedor->proveedor->nombre_completo ?? 'Proveedor' }}</div>
                                <div class="chart-bars">
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width: {{ $porcentajeMonto }}%;"></div>
                                    </div>
                                </div>
                                <div class="chart-value">
                                    ${{ number_format($proveedor->total_egresos, 0) }} <small>({{ $proveedor->total_compras }})</small>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; color: #95a5a6; padding: 50px;">Sin datos por proveedor</div>
                            @endforelse
                        </div>
                        
                        <div class="chart-footer">
                            <div class="total-stats">
                                <span>Proveedores activos: <strong>{{ $comprasPorProveedor->count() }}</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- GRÁFICA 2: PRODUCTOS MÁS COMPRADOS -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Productos más adquiridos</h3>
                            <div class="legend">
                                <div class="legend-item">
                                    <span class="legend-dot accent"></span>
                                    <span>Cantidad</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot info"></span>
                                    <span>Costo</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content">
                            @php
                                $topProductos = $productosMasComprados->take(6);
                                $maxCantidad = $topProductos->max('total_comprado') ?: 1;
                            @endphp
                            
                            @forelse($topProductos as $producto)
                            @php
                                $porcentajeCantidad = ($producto->total_comprado / $maxCantidad) * 100;
                                $nombreProducto = $producto->producto->Nombre ?? 'Producto';
                            @endphp
                            <div class="chart-row">
                                <div class="chart-label truncate">{{ $nombreProducto }}</div>
                                <div class="chart-bars">
                                    <div class="bar-bg">
                                        <div class="bar-fill" style="width: {{ $porcentajeCantidad }}%; background: #e67e22;"></div>
                                    </div>
                                </div>
                                <div class="chart-value">
                                    {{ $producto->total_comprado }} <small>unid.</small>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; color: #95a5a6; padding: 50px;">Sin datos de productos</div>
                            @endforelse
                        </div>
                        
                        <div class="chart-footer">
                            <div class="total-stats">
                                <span>Productos distintos: <strong>{{ $productosMasComprados->count() }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== PÁGINA 3: LISTADO DE COMPRAS ===== -->
    <div class="page-content">
        <h2>COMPRAS REALIZADAS</h2>
        
        @if($compras->isEmpty())
        <div style="text-align: center; padding: 50px; color: #95a5a6; border: 1px solid #ecf0f1;">
            No hay compras en el período seleccionado
        </div>
        @else
        
        @foreach($compras as $compra)
        <div style="margin-bottom: 25px; border: 1px solid #ecf0f1;">
            <div style="background: #f5f6fa; padding: 12px 15px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 10pt; font-weight: 400;">Compra #{{ $compra->id }}</span>
                    <span style="margin-left: 15px; font-size: 8pt; color: #7f8c8d;">
                        {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div>
                    <span class="badge-info">{{ $compra->proveedor->nombre_completo ?? 'Proveedor' }}</span>
                    <span style="margin-left: 10px; font-weight: 400;">Total: ${{ number_format($compra->Total, 0) }}</span>
                </div>
            </div>
            
            <div style="padding: 15px;">
                <table style="font-size: 8pt; width: 100%;">
                    <thead>
                        <tr style="background: none; border-bottom: 1px solid #ecf0f1;">
                            <th style="padding: 8px 5px; width: 50%;">Producto</th>
                            <th style="padding: 8px 5px; text-align: right; width: 15%;">Cantidad</th>
                            <th style="padding: 8px 5px; text-align: right; width: 15%;">Precio Unit.</th>
                            <th style="padding: 8px 5px; text-align: right; width: 20%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $detallesCompra = $detalleCompras->where('Compra_idCompra', $compra->id);
                        @endphp
                        @forelse($detallesCompra as $detalle)
                        <tr>
                            <td style="padding: 6px 5px;">{{ $detalle->producto->Nombre ?? 'Producto' }}</td>
                            <td style="padding: 6px 5px; text-align: right;">{{ $detalle->Cantidad }}</td>
                            <td style="padding: 6px 5px; text-align: right;">${{ number_format($detalle->Precio_unitario, 0) }}</td>
                            <td style="padding: 6px 5px; text-align: right;">${{ number_format($detalle->Cantidad * $detalle->Precio_unitario, 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 15px; color: #95a5a6;">
                                Sin detalles disponibles
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        @endif
        
        <!-- ===== SÍNTESIS ===== -->
        <div class="summary">
            <div style="margin-bottom: 15px; font-size: 11pt; font-weight: 400;">RESUMEN DE COMPRAS</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <p><strong>Total compras:</strong> {{ $totalCompras }}</p>
                    <p><strong>Total invertido:</strong> ${{ number_format($totalEgresos, 0) }}</p>
                    <p><strong>Promedio por compra:</strong> ${{ number_format($compraPromedio, 0) }}</p>
                </div>
                <div class="summary-item">
                    <p><strong>Proveedores distintos:</strong> {{ $comprasPorProveedor->count() }}</p>
                    <p><strong>Productos adquiridos:</strong> {{ $productosMasComprados->count() }}</p>
                    <p><strong>Días con compras:</strong> {{ $comprasPorDia->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== FOOTER ===== -->
    <htmlpagefooter name="footer" class="footer">
        <span class="page-number"></span> · {{ $fechaGeneracion }} · REPORTE DE COMPRAS
    </htmlpagefooter>
</body>
</html>