<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos - Pendientes y Completados</title>
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
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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
        
        .filtro-badge {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        
        .filtro-info {
            background: #fff9e6;
            border: 1px solid #f39c12;
            border-radius: 6px;
            padding: 12px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filtro-icono {
            background: #f39c12;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
        }
        
        .filtro-texto {
            flex: 1;
            font-size: 12px;
            color: #856404;
        }
        
        .filtro-texto strong {
            color: #e67e22;
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
        
        .category {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 2px;
        }
        
        .estado-pendiente { color: #f39c12; font-weight: 600; }
        .estado-completado { color: #27ae60; font-weight: 600; }
        
        .estado-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .estado-badge.pendiente {
            background: #fff3e0;
            color: #f39c12;
            border: 1px solid #f39c12;
        }
        
        .estado-badge.completado {
            background: #e8f5e9;
            color: #27ae60;
            border: 1px solid #27ae60;
        }
        
        .pedidos-lista {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .pedido-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            margin-bottom: 8px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .pedido-item:hover {
            background: #f8f9fa;
        }
        
        .pedido-info h4 {
            font-size: 12px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .pedido-detalles {
            display: flex;
            gap: 15px;
            font-size: 10px;
            color: #7f8c8d;
        }
        
        .pedido-total {
            font-weight: 700;
            color: #27ae60;
            font-size: 13px;
        }
        
        .featured-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 2px solid #f39c12;
            border-radius: 8px;
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
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="reporte">
        <div class="header">
            <h1>REPORTE DE PEDIDOS - PENDIENTES Y COMPLETADOS</h1>
            <div class="header-info">
                <div>
                    <strong>Período:</strong> {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}
                </div>
                <div>
                    <strong>Generado:</strong> {{ $fechaGeneracion }}
                </div>
                <div>
                    <span class="filtro-badge">Filtro: Solo Pendientes y Completados</span>
                </div>
            </div>
        </div>
        
        <!-- Información del filtro -->
        <div class="filtro-info">
            <div class="filtro-icono">!</div>
            <div class="filtro-texto">
                <strong>Reporte filtrado:</strong> Mostrando únicamente pedidos con estado <strong>PENDIENTE</strong> y <strong>COMPLETADO</strong>. 
                Los pedidos en proceso y cancelados no se incluyen en este reporte.
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pedidos</div>
                <div class="stat-value">{{ $totalPedidos }}</div>
                <div class="stat-desc">Pendientes + Completados</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Valor Total</div>
                <div class="stat-value">${{ number_format($valorTotalPedidos, 2) }}</div>
                <div class="stat-desc">Monto total filtrado</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pendientes</div>
                <div class="stat-value">{{ $pedidosPendientes }}</div>
                <div class="stat-desc">{{ $totalPedidos > 0 ? round(($pedidosPendientes/$totalPedidos)*100, 1) : 0 }}% del total</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Completados</div>
                <div class="stat-value">{{ $pedidosCompletados ?? 0 }}</div>
                <div class="stat-desc">{{ $totalPedidos > 0 ? round((($pedidosCompletados ?? 0)/$totalPedidos)*100, 1) : 0 }}% del total</div>
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
                            <h3>Distribución Pendientes vs Completados</h3>
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
                <h2>RESUMEN DE ESTADOS</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                <div style="background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center; border: 2px solid #f39c12;">
                    <div style="font-size: 28px; font-weight: 700; color: #f39c12; margin-bottom: 5px;">{{ $pedidosPendientes }}</div>
                    <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase; letter-spacing: 0.5px;">Pedidos Pendientes</div>
                </div>
                <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; text-align: center; border: 2px solid #27ae60;">
                    <div style="font-size: 28px; font-weight: 700; color: #27ae60; margin-bottom: 5px;">{{ $pedidosCompletados ?? 0 }}</div>
                    <div style="font-size: 12px; color: #7f8c8d; text-transform: uppercase; letter-spacing: 0.5px;">Pedidos Completados</div>
                </div>
            </div>
        </div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>LISTA DE PEDIDOS PENDIENTES</h2>
                <span class="section-badge">{{ $pedidosPendientes }} pedidos</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="15%">ID Pedido</th>
                            <th width="30%">Cliente</th>
                            <th width="20%">Vendedor</th>
                            <th width="15%">Fecha</th>
                            <th width="10%">Estado</th>
                            <th width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosPendientesLista ?? [] as $pedido)
                        <tr>
                            <td>#{{ $pedido->id }}</td>
                            <td>
                                <span class="product-name">
                                    {{ $pedido->cliente->Nombre ?? 'N/A' }} {{ $pedido->cliente->ApPaterno ?? '' }}
                                </span>
                            </td>
                            <td>{{ $pedido->empleado->Nombre ?? 'N/A' }} {{ $pedido->empleado->ApPaterno ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pedido->Fecha_pedido)->format('d/m/Y') }}</td>
                            <td><span class="estado-badge pendiente">PENDIENTE</span></td>
                            <td>${{ number_format($pedido->Total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay pedidos pendientes en el período seleccionado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>LISTA DE PEDIDOS COMPLETADOS</h2>
                <span class="section-badge">{{ $pedidosCompletados ?? 0 }} pedidos</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="15%">ID Pedido</th>
                            <th width="30%">Cliente</th>
                            <th width="20%">Vendedor</th>
                            <th width="15%">Fecha</th>
                            <th width="10%">Estado</th>
                            <th width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosCompletadosLista ?? [] as $pedido)
                        <tr>
                            <td>#{{ $pedido->id }}</td>
                            <td>
                                <span class="product-name">
                                    {{ $pedido->cliente->Nombre ?? 'N/A' }} {{ $pedido->cliente->ApPaterno ?? '' }}
                                </span>
                            </td>
                            <td>{{ $pedido->empleado->Nombre ?? 'N/A' }} {{ $pedido->empleado->ApPaterno ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pedido->Fecha_pedido)->format('d/m/Y') }}</td>
                            <td><span class="estado-badge completado">COMPLETADO</span></td>
                            <td>${{ number_format($pedido->Total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay pedidos completados en el período seleccionado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                            <th width="45%">Vendedor</th>
                            <th width="15%">Pendientes</th>
                            <th width="15%">Completados</th>
                            <th width="20%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosPorVendedor as $index => $vendedor)
                        @php
                            $pendientes = $vendedor->pedidos_pendientes ?? 0;
                            $completados = $vendedor->pedidos_completados ?? 0;
                            $totalVendedor = $pendientes + $completados;
                        @endphp
                        <tr>
                            <td><span class="rank">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $vendedor->empleado->Nombre ?? 'Sin vendedor' }} {{ $vendedor->empleado->ApPaterno ?? '' }}</span>
                            </td>
                            <td><span style="color: #f39c12; font-weight: 600;">{{ $pendientes }}</span></td>
                            <td><span style="color: #27ae60; font-weight: 600;">{{ $completados }}</span></td>
                            <td><strong>{{ $totalVendedor }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay pedidos</td>
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
                <h4 style="font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">CLIENTE DESTACADO</h4>
                <p style="font-size: 16px; font-weight: 700; color: #2c3e50;">
                    {{ $clienteTop->cliente->Nombre ?? 'N/A' }} {{ $clienteTop->cliente->ApPaterno ?? '' }}
                </p>
            </div>
            <div class="featured-badge">
                {{ $clienteTop->total_pedidos }} pedidos
            </div>
        </div>
        @endif
        
        <div class="footer">
            <div class="footer-info">
                <span>Reporte de Pedidos - Pendientes y Completados</span>
                <span>{{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</span>
            </div>
            <div>
                Generado: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>