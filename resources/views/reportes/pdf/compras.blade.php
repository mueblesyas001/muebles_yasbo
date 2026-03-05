<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Compras</title>
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
        
        /* ===== HEADER ===== */
        .header {
            background: #c0392b;
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
            flex-wrap: wrap;
        }
        
        .header-info div {
            display: flex;
            gap: 5px;
        }
        
        /* ===== TARJETAS DE RESUMEN ===== */
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
        
        .stat-card:nth-child(1) { border-left-color: #c0392b; }
        .stat-card:nth-child(2) { border-left-color: #e67e22; }
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
        
        /* ===== SECCIONES ===== */
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
            border-bottom: 2px solid #c0392b;
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
        
        /* ===== GRÁFICAS (SOLO LAS QUE QUEDAN) ===== */
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
        
        /* ===== TABLAS ===== */
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
            background: #c0392b;
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
            color: #c0392b;
        }
        
        /* ===== TARJETA DESTACADA ===== */
        .featured-card {
            background: #fdedec;
            border: 1px solid #c0392b;
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
            background: #c0392b;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        
        /* ===== MÉTRICAS ADICIONALES ===== */
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
        .metric-value.warning { color: #e67e22; }
        .metric-value.danger { color: #c0392b; }
        
        hr {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 6px 0;
        }
        
        /* ===== PIE DE PÁGINA ===== */
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
        
        /* ===== UTILIDADES ===== */
        .text-success { color: #27ae60; }
        .text-warning { color: #e67e22; }
        .text-danger { color: #c0392b; }
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
        <!-- HEADER -->
        <div class="header">
            <h1>REPORTE DE COMPRAS</h1>
            <div class="header-info">
                <div>
                    <strong>Período:</strong> {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}
                </div>
                <div>
                    <strong>Generado:</strong> {{ $fechaGeneracion }}
                </div>
                @if(isset($proveedorSeleccionado) && $proveedorSeleccionado)
                <div>
                    <strong>Proveedor filtrado:</strong> {{ $proveedorSeleccionado->nombre_completo ?? 'N/A' }}
                </div>
                @endif
            </div>
        </div>
        
        <!-- TARJETAS DE RESUMEN -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total de Compras</div>
                <div class="stat-value">{{ $totalCompras }}</div>
                <div class="stat-desc">Número de compras realizadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Invertido</div>
                <div class="stat-value">${{ number_format($totalEgresos, 2) }}</div>
                <div class="stat-desc">Dinero total gastado</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Compra Promedio</div>
                <div class="stat-value">${{ number_format($compraPromedio, 2) }}</div>
                <div class="stat-desc">Gasto promedio por compra</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Proveedores</div>
                <div class="stat-value">{{ $totalProveedores }}</div>
                <div class="stat-desc">Proveedores diferentes</div>
            </div>
        </div>
        
        <!-- GRÁFICAS (SOLO PROVEEDORES Y PRODUCTOS) -->
        @if(!empty($graficas))
        <div class="graficas-wrapper">
            <div class="graficas-section">
                <div class="section-title">
                    <h2>ANÁLISIS GRÁFICO</h2>
                </div>
                
                <div class="graficas-grid">
                    @if(isset($graficas['proveedores']))
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Distribución por Proveedor</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['proveedores'] }}" alt="Compras por Proveedor">
                        </div>
                    </div>
                    @endif

                    @if(isset($graficas['productos']))
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Productos más comprados</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['productos'] }}" alt="Productos más comprados">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        
        <!-- SALTO DE PÁGINA DESPUÉS DE GRÁFICAS -->
        <div class="page-break"></div>
        
        <!-- SECCIÓN 1: PRODUCTOS MÁS COMPRADOS -->
        <div class="section keep-together">
            <div class="section-title">
                <h2>PRODUCTOS MÁS COMPRADOS</h2>
                <span class="section-badge">Lo que más hemos comprado</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Producto</th>
                            <th width="15%">Categoría</th>
                            <th width="15%">Cantidad</th>
                            <th width="15%">Veces comprado</th>
                            <th width="15%">Total invertido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasComprados as $index => $producto)
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
                            <td>{{ number_format($producto->total_comprado) }} unidades</td>
                            <td>{{ $producto->veces_comprado }} veces</td>
                            <td class="amount">${{ number_format($producto->total_costo ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 20px;">
                                No hay compras en este período
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- SECCIÓN 2: PROVEEDORES -->
        <div class="section keep-together">
            <div class="section-title">
                <h2>PROVEEDORES</h2>
                <span class="section-badge">A quién le compramos</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">Proveedor</th>
                            <th width="15%">Compras</th>
                            <th width="20%">Total comprado</th>
                            <th width="20%">Promedio por compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comprasPorProveedor as $index => $proveedor)
                        <tr>
                            <td><span class="rank">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $proveedor->proveedor->nombre_completo ?? 'Proveedor sin nombre' }}</span>
                                @if($proveedor->proveedor && isset($proveedor->proveedor->telefono))
                                <div class="category">Tel: {{ $proveedor->proveedor->telefono }}</div>
                                @endif
                            </td>
                            <td>{{ $proveedor->total_compras }} compras</td>
                            <td class="amount">${{ number_format($proveedor->total_egresos ?? 0, 2) }}</td>
                            <td>${{ number_format($proveedor->promedio_compra ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px;">
                                No hay compras en este período
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- PROVEEDOR DESTACADO -->
        @if(isset($proveedorPrincipal) && $proveedorPrincipal)
        <div class="featured-card">
            <div class="featured-info">
                <h4>PROVEEDOR PRINCIPAL</h4>
                <p>{{ $proveedorPrincipal->nombre_completo ?? 'N/A' }}</p>
            </div>
            <div class="featured-badge">
                ${{ number_format($proveedorPrincipal->total_egresos ?? 0, 2) }}
            </div>
        </div>
        @endif
        
        <!-- SECCIÓN 3: COMPRAS POR DÍA (SOLO TABLA, SIN GRÁFICA) -->
        @if($comprasPorDia->isNotEmpty())
        <div class="section keep-together">
            <div class="section-title">
                <h2>COMPRAS POR DÍA</h2>
                <span class="section-badge">Actividad diaria</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="40%">Fecha</th>
                            <th width="30%">Cantidad de compras</th>
                            <th width="30%">Total del día</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comprasPorDia as $dia)
                        <tr>
                            <td><strong>{{ date('d/m/Y', strtotime($dia->fecha)) }}</strong></td>
                            <td>{{ $dia->cantidad }} compras</td>
                            <td class="amount">${{ number_format($dia->total ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(isset($diaPico) && $diaPico)
            <div style="margin-top: 10px; padding: 10px; background: #fdedec; border-radius: 6px; text-align: center;">
                <strong>Día con más compras:</strong> {{ date('d/m/Y', strtotime($diaPico->fecha)) }} 
                con ${{ number_format($diaPico->total ?? 0, 2) }} ({{ $diaPico->cantidad }} compras)
            </div>
            @endif
        </div>
        @endif
        
        <!-- MÉTRICAS ADICIONALES -->
        <div class="metrics-grid keep-together">
            <div class="metric-card">
                <div class="metric-title">RESUMEN DE TIEMPO</div>
                <div class="metric-row">
                    <span class="metric-label">Días del período:</span>
                    <span class="metric-value">{{ $diasPeriodo ?? 0 }}</span>
                </div>
                <hr>
                <div class="metric-row">
                    <span class="metric-label">Días con compras:</span>
                    <span class="metric-value success">{{ $comprasPorDia->count() ?? 0 }}</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Días sin compras:</span>
                    <span class="metric-value danger">{{ ($diasPeriodo ?? 0) - ($comprasPorDia->count() ?? 0) }}</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-title">PROMEDIOS</div>
                <div class="metric-row">
                    <span class="metric-label">Gasto diario:</span>
                    <span class="metric-value">${{ number_format($totalEgresos / max($diasPeriodo ?? 1, 1), 2) }}</span>
                </div>
                <hr>
                <div class="metric-row">
                    <span class="metric-label">Por día con compras:</span>
                    <span class="metric-value success">${{ number_format($totalEgresos / max($comprasPorDia->count() ?? 1, 1), 2) }}</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Por proveedor:</span>
                    <span class="metric-value">${{ number_format($totalEgresos / max($totalProveedores ?? 1, 1), 2) }}</span>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-title">PRODUCTO DESTACADO</div>
                <div class="metric-row">
                    <span class="metric-label">Producto top:</span>
                    <span class="metric-value success">{{ $productosMasComprados->first()->producto->Nombre ?? 'N/A' }}</span>
                </div>
                <hr>
                <div class="metric-row">
                    <span class="metric-label">Cantidad comprada:</span>
                    <span class="metric-value">{{ $productosMasComprados->first()->total_comprado ?? 0 }} unidades</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Total invertido:</span>
                    <span class="metric-value amount">${{ number_format($productosMasComprados->first()->total_costo ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- PIE DE PÁGINA -->
        <div class="footer">
            <div class="footer-info">
                <span>Reporte de Compras</span>
                <span>{{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</span>
            </div>
            <div>
                Generado: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>