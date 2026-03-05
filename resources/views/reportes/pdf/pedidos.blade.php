<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos</title>
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
            background: #f39c12;
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
        
        .stat-card:nth-child(1) { border-left-color: #f39c12; }
        .stat-card:nth-child(2) { border-left-color: #3498db; }
        .stat-card:nth-child(3) { border-left-color: #2ecc71; }
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
            border-bottom: 2px solid #f39c12;
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
            background: #f39c12;
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
        
        .estado-pendiente { color: #f39c12; font-weight: 600; }
        .estado-proceso { color: #3498db; font-weight: 600; }
        .estado-entregado { color: #27ae60; font-weight: 600; }
        .estado-cancelado { color: #e74c3c; font-weight: 600; }
        
        .featured-card {
            background: #f8f9fa;
            border: 1px solid #f39c12;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 0 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .featured-badge {
            background: #f39c12;
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
        
        .graficas-wrapper {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="reporte">
        <div class="header">
            <h1>REPORTE DE PEDIDOS</h1>
            <div class="header-info">
                <div>
                    <strong>Período:</strong> {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}
                </div>
                <div>
                    <strong>Generado:</strong> {{ $fechaGeneracion }}
                </div>
                @if(isset($estadoSeleccionado) && $estadoSeleccionado)
                <div>
                    <strong>Estado:</strong> {{ ucfirst($estadoSeleccionado) }}
                </div>
                @endif
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pedidos</div>
                <div class="stat-value">{{ $totalPedidos }}</div>
                <div class="stat-desc">Pedidos registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Valor Total</div>
                <div class="stat-value">${{ number_format($valorTotalPedidos, 2) }}</div>
                <div class="stat-desc">Monto total</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Tiempo Promedio</div>
                <div class="stat-value">{{ $tiempoPromedioEntrega ?? 'N/A' }}</div>
                <div class="stat-desc">Días para entregar</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Entregados</div>
                <div class="stat-value">{{ $pedidosEntregados }}</div>
                <div class="stat-desc">{{ $totalPedidos > 0 ? round(($pedidosEntregados/$totalPedidos)*100, 1) : 0 }}% del total</div>
            </div>
        </div>
        
        @if(!empty($graficas))
        <div class="graficas-wrapper">
            <div class="graficas-section">
                <div class="section-title">
                    <h2>ANÁLISIS GRÁFICO</h2>
                </div>
                
                <div class="graficas-grid">
                    @if(isset($graficas['estados']))
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Distribución por Estado</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['estados'] }}" alt="Estados de Pedidos">
                        </div>
                    </div>
                    @endif

                    @if(isset($graficas['vendedores']))
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Pedidos por Vendedor</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['vendedores'] }}" alt="Pedidos por Vendedor">
                        </div>
                    </div>
                    @endif
                </div>

                @if(isset($graficas['productos']))
                <div style="margin-top: 20px;">
                    <div class="grafica-card">
                        <div class="grafica-header">
                            <h3>Productos más pedidos</h3>
                        </div>
                        <div class="grafica-container">
                            <img src="{{ $graficas['productos'] }}" alt="Productos más pedidos">
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
                <h2>ESTADOS DE PEDIDOS</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
                <div style="background: #fff3e0; padding: 12px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 700; color: #f39c12;">{{ $pedidosPendientes }}</div>
                    <div style="font-size: 10px; color: #7f8c8d;">Pendientes</div>
                </div>
                <div style="background: #e8f0fe; padding: 12px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 700; color: #3498db;">{{ $pedidosEnProceso }}</div>
                    <div style="font-size: 10px; color: #7f8c8d;">En Proceso</div>
                </div>
                <div style="background: #e8f5e9; padding: 12px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 700; color: #27ae60;">{{ $pedidosEntregados }}</div>
                    <div style="font-size: 10px; color: #7f8c8d;">Entregados</div>
                </div>
                <div style="background: #fdeded; padding: 12px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 700; color: #e74c3c;">{{ $pedidosCancelados }}</div>
                    <div style="font-size: 10px; color: #7f8c8d;">Cancelados</div>
                </div>
            </div>
        </div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>PEDIDOS POR VENDEDOR</h2>
                <span class="section-badge">Top {{ $pedidosPorVendedor->count() }}</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="55%">Vendedor</th>
                            <th width="20%">Pedidos</th>
                            <th width="20%">Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosPorVendedor as $index => $vendedor)
                        @php
                            $porcentaje = $totalPedidos > 0 ? round(($vendedor->total_pedidos/$totalPedidos)*100, 1) : 0;
                        @endphp
                        <tr>
                            <td><span class="rank">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $vendedor->empleado->Nombre ?? 'Sin vendedor' }} {{ $vendedor->empleado->ApPaterno ?? '' }}</span>
                            </td>
                            <td>{{ $vendedor->total_pedidos }}</td>
                            <td>{{ $porcentaje }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay pedidos</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>PRODUCTOS MÁS PEDIDOS</h2>
                <span class="section-badge">Top {{ $productosMasPedidos->count() }}</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="70%">Producto</th>
                            <th width="25%">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasPedidos as $index => $item)
                        @php
                            $rankClass = '';
                            if($index == 0) $rankClass = 'rank-1';
                            elseif($index == 1) $rankClass = 'rank-2';
                            elseif($index == 2) $rankClass = 'rank-3';
                        @endphp
                        <tr>
                            <td><span class="rank {{ $rankClass }}">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $item->producto->Nombre ?? 'N/A' }}</span>
                                <div class="category">{{ $item->producto->categoria->Nombre ?? 'Sin categoría' }}</div>
                            </td>
                            <td>{{ $item->total_cantidad }} unidades</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay productos pedidos</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if(isset($clienteTop) && $clienteTop)
        <div class="featured-card">
            <div class="featured-info">
                <h4>CLIENTE DESTACADO</h4>
                <p>{{ $clienteTop->cliente->Nombre ?? 'N/A' }} {{ $clienteTop->cliente->ApPaterno ?? '' }}</p>
            </div>
            <div class="featured-badge">
                {{ $clienteTop->total_pedidos }} pedidos
            </div>
        </div>
        @endif
        
        <div class="footer">
            <div class="footer-info">
                <span>Reporte de Pedidos</span>
                <span>{{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</span>
            </div>
            <div>
                Generado: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>