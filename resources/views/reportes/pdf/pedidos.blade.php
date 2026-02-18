<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Completo de Pedidos</title>
    <style>
        /* ESTILOS PRINCIPALES */
        @page { margin: 50px 30px; }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
        }
        
        /* ENCABEZADO */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
        }
        
        .title {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .subtitle {
            color: #7f8c8d;
            font-size: 12px;
        }
        
        /* TARJETAS DE ESTADÍSTICAS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin: 15px 0;
        }
        
        .stat-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 10px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            font-size: 9px;
            color: #6c757d;
            margin-top: 3px;
        }
        
        /* SECCIONES */
        .section {
            margin: 20px 0;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #ecf0f1;
            padding: 6px 12px;
            margin: 15px 0 8px 0;
            border-left: 4px solid #3498db;
            font-weight: bold;
            color: #2c3e50;
            font-size: 12px;
        }
        
        /* TABLAS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 9px;
        }
        
        th {
            background-color: #34495e;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 5px 8px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* COLORES ESPECIALES */
        .prioridad-alta {
            background-color: #ffebee !important;
            color: #c62828;
            font-weight: bold;
        }
        
        .prioridad-media {
            background-color: #fff3e0 !important;
            color: #ef6c00;
        }
        
        .estado-entregado {
            background-color: #e8f5e9 !important;
            color: #2e7d32;
        }
        
        .estado-pendiente {
            background-color: #fff3cd !important;
            color: #856404;
        }
        
        /* FOOTER */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 8px;
            color: #95a5a6;
            border-top: 1px solid #ecf0f1;
            padding-top: 8px;
        }
        
        .page-number:before {
            content: "Página " counter(page) " de " counter(pages);
        }
        
        /* ALERTAS */
        .alert {
            padding: 8px;
            border-radius: 3px;
            margin-bottom: 10px;
            font-size: 9px;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #dc3545;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #28a745;
        }
        
        /* BARRAS DE PROGRESO */
        .progress-bar {
            height: 12px;
            background-color: #eee;
            border-radius: 3px;
            margin: 3px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #3498db;
        }
    </style>
</head>
<body>
    <!-- PORTADA / RESUMEN EJECUTIVO -->
    <div class="header">
        <h1 class="title">📦 REPORTE COMPLETO DE PEDIDOS</h1>
        <div class="subtitle">
            <p>Generado el: {{ $fechaGeneracion }}</p>
            <p>Período: {{ date('d/m/Y', strtotime($fechaInicio)) }} al {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
            @if($clienteSeleccionado)
            <p>Cliente: <strong>{{ $clienteSeleccionado->nombre ?? 'N/A' }}</strong></p>
            @endif
            @if($empleadoSeleccionado)
            <p>Empleado: <strong>{{ $empleadoSeleccionado->nombre ?? 'N/A' }}</strong></p>
            @endif
        </div>
    </div>
    
    <!-- ESTADÍSTICAS PRINCIPALES -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalPedidos) }}</div>
            <div class="stat-label">PEDIDOS TOTALES</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">${{ number_format($totalIngresos, 2) }}</div>
            <div class="stat-label">VALOR TOTAL</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">${{ number_format($pedidoPromedio, 2) }}</div>
            <div class="stat-label">PROMEDIO POR PEDIDO</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ number_format($resumenEjecutivo['pendientes_por_entregar']) }}</div>
            <div class="stat-label">PENDIENTES</div>
        </div>
    </div>
    
    <!-- ALERTAS DESTACADAS -->
    @if($pedidoMasAlto)
    <div class="alert alert-success">
        <strong>🏆 PEDIDO MÁS ALTO:</strong> 
        Pedido #{{ $pedidoMasAlto->id }} por <strong>${{ number_format($pedidoMasAlto->Total, 2) }}</strong>
        el {{ date('d/m/Y', strtotime($pedidoMasAlto->Fecha_entrega)) }} | 
        Cliente: {{ $pedidoMasAlto->cliente->nombre ?? 'N/A' }}
    </div>
    @endif
    
    @if($resumenEjecutivo['pendientes_por_entregar'] > 0)
    <div class="alert alert-warning">
        <strong>⚠️ PEDIDOS PENDIENTES:</strong> 
        {{ $resumenEjecutivo['pendientes_por_entregar'] }} pedidos por valor de 
        <strong>${{ number_format($resumenEjecutivo['valor_pendientes'], 2) }}</strong>
        requieren atención
    </div>
    @endif
    
    <!-- SECCIÓN 1: RESUMEN EJECUTIVO -->
    <div class="section">
        <div class="section-title">📈 RESUMEN EJECUTIVO</div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 10px 0;">
            <div style="background-color: #e3f2fd; padding: 10px; border-radius: 5px;">
                <h4 style="color: #1565c0; font-size: 11px; margin-bottom: 5px;">📊 DESEMPEÑO GENERAL</h4>
                <p style="font-size: 10px; margin: 2px 0;">Período: <strong>{{ $resumenEjecutivo['dias_periodo'] }} días</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Pedidos totales: <strong>{{ number_format($totalPedidos) }}</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Valor total: <strong>${{ number_format($totalIngresos, 2) }}</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Promedio por pedido: <strong>${{ number_format($pedidoPromedio, 2) }}</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Productos únicos: <strong>{{ $productosUnicos }}</strong></p>
            </div>
            
            <div style="background-color: #e8f5e9; padding: 10px; border-radius: 5px;">
                <h4 style="color: #2e7d32; font-size: 11px; margin-bottom: 5px;">👑 DESTACADOS</h4>
                @if($resumenEjecutivo['cliente_top'])
                <p style="font-size: 10px; margin: 2px 0;">Cliente top: <strong>{{ $resumenEjecutivo['cliente_top']->cliente->nombre ?? 'N/A' }}</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Pedidos: <strong>{{ $resumenEjecutivo['cliente_top']->total_pedidos }}</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Valor: <strong>${{ number_format($resumenEjecutivo['cliente_top']->total_ingresos, 2) }}</strong></p>
                @endif
                
                @if($resumenEjecutivo['producto_top'])
                <p style="font-size: 10px; margin: 2px 0;">Producto más solicitado: <strong>{{ $resumenEjecutivo['producto_top']->producto->Nombre ?? 'Producto eliminado' }}</strong></p>
                <p style="font-size: 10px; margin: 2px 0;">Cantidad: <strong>{{ number_format($resumenEjecutivo['producto_top']->total_solicitado) }}</strong></p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 2: PRODUCTOS MÁS SOLICITADOS -->
    @if($productosMasSolicitados->count() > 0)
    <div class="section">
        <div class="section-title">🏆 TOP 15 PRODUCTOS MÁS SOLICITADOS</div>
        
        <table>
            <thead>
                <tr>
                    <th width="10%">RANK</th>
                    <th width="35%">PRODUCTO</th>
                    <th width="15%" class="text-right">CANTIDAD</th>
                    <th width="15%" class="text-right">VEZES PEDIDO</th>
                    <th width="15%" class="text-right">PRECIO PROM.</th>
                    <th width="20%" class="text-right">VALOR TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productosMasSolicitados as $index => $producto)
                <tr class="{{ $index < 3 ? 'prioridad-alta' : '' }}">
                    <td class="text-center">
                        @if($index == 0)🥇
                        @elseif($index == 1)🥈
                        @elseif($index == 2)🥉
                        @else #{{ $index + 1 }}
                        @endif
                    </td>
                    <td>{{ $producto->producto->Nombre ?? 'Producto eliminado' }}</td>
                    <td class="text-right">{{ number_format($producto->total_solicitado) }}</td>
                    <td class="text-right">{{ $producto->veces_solicitado }}</td>
                    <td class="text-right">${{ number_format($producto->precio_promedio, 2) }}</td>
                    <td class="text-right"><strong>${{ number_format($producto->total_valor, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <td colspan="2"><strong>TOTALES:</strong></td>
                    <td class="text-right"><strong>{{ number_format($productosMasSolicitados->sum('total_solicitado')) }}</strong></td>
                    <td class="text-right"><strong>{{ $productosMasSolicitados->sum('veces_solicitado') }}</strong></td>
                    <td></td>
                    <td class="text-right"><strong>${{ number_format($productosMasSolicitados->sum('total_valor'), 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 3: PEDIDOS POR CLIENTE -->
    @if($pedidosPorCliente->count() > 0)
    <div class="section">
        <div class="section-title">👥 PEDIDOS POR CLIENTE</div>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="40%">CLIENTE</th>
                    <th width="15%" class="text-right">PEDIDOS</th>
                    <th width="20%" class="text-right">VALOR TOTAL</th>
                    <th width="20%" class="text-right">PROMEDIO POR PEDIDO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidosPorCliente as $index => $pedidoCliente)
                @php
                    $porcentaje = $totalIngresos > 0 ? 
                        ($pedidoCliente->total_ingresos / $totalIngresos) * 100 : 0;
                @endphp
                <tr class="{{ $index == 0 ? 'estado-entregado' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pedidoCliente->cliente->nombre ?? 'Cliente eliminado' }}</td>
                    <td class="text-right">{{ $pedidoCliente->total_pedidos }}</td>
                    <td class="text-right">
                        <strong>${{ number_format($pedidoCliente->total_ingresos, 2) }}</strong>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min($porcentaje, 100) }}%;"></div>
                        </div>
                        {{ number_format($porcentaje, 1) }}%
                    </td>
                    <td class="text-right">${{ number_format($pedidoCliente->promedio_pedido, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 4: PEDIDOS POR EMPLEADO -->
    @if($pedidosPorEmpleado->count() > 0)
    <div class="section">
        <div class="section-title">👤 PEDIDOS POR EMPLEADO</div>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">EMPLEADO</th>
                    <th width="12%" class="text-right">PEDIDOS</th>
                    <th width="12%" class="text-right">ENTREGADOS</th>
                    <th width="12%" class="text-right">PENDIENTES</th>
                    <th width="24%" class="text-right">VALOR TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidosPorEmpleado as $index => $pedidoEmpleado)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pedidoEmpleado->empleado->nombre ?? 'Empleado eliminado' }}</td>
                    <td class="text-right">{{ $pedidoEmpleado->total_pedidos }}</td>
                    <td class="text-right estado-entregado">{{ $pedidoEmpleado->entregados }}</td>
                    <td class="text-right estado-pendiente">{{ $pedidoEmpleado->pendientes }}</td>
                    <td class="text-right"><strong>${{ number_format($pedidoEmpleado->total_ingresos, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 5: PEDIDOS POR ESTADO -->
    @if($pedidosPorEstado->count() > 0)
    <div class="section">
        <div class="section-title">📊 DISTRIBUCIÓN POR ESTADO</div>
        
        <table>
            <thead>
                <tr>
                    <th width="40%">ESTADO</th>
                    <th width="20%" class="text-right">CANTIDAD</th>
                    <th width="20%" class="text-right">VALOR TOTAL</th>
                    <th width="20%" class="text-right">PROMEDIO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidosPorEstado as $estado)
                <tr class="{{ $estado->Estado == 'Entregado' ? 'estado-entregado' : ($estado->Estado == 'Pendiente' ? 'estado-pendiente' : '') }}">
                    <td>{{ $estado->Estado }}</td>
                    <td class="text-right">{{ $estado->cantidad }}</td>
                    <td class="text-right"><strong>${{ number_format($estado->total, 2) }}</strong></td>
                    <td class="text-right">${{ number_format($estado->promedio, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <td><strong>TOTALES:</strong></td>
                    <td class="text-right"><strong>{{ $pedidosPorEstado->sum('cantidad') }}</strong></td>
                    <td class="text-right"><strong>${{ number_format($pedidosPorEstado->sum('total'), 2) }}</strong></td>
                    <td class="text-right"><strong>${{ number_format($pedidosPorEstado->avg('promedio'), 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 6: PEDIDOS POR PRIORIDAD -->
    @if($pedidosPorPrioridad->count() > 0)
    <div class="section">
        <div class="section-title">⚠️ DISTRIBUCIÓN POR PRIORIDAD</div>
        
        <table>
            <thead>
                <tr>
                    <th width="40%">PRIORIDAD</th>
                    <th width="20%" class="text-right">CANTIDAD</th>
                    <th width="20%" class="text-right">VALOR TOTAL</th>
                    <th width="20%" class="text-right">PROMEDIO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidosPorPrioridad as $prioridad)
                <tr class="{{ $prioridad->Prioridad == 'Alta' ? 'prioridad-alta' : ($prioridad->Prioridad == 'Media' ? 'prioridad-media' : '') }}">
                    <td>{{ $prioridad->Prioridad }}</td>
                    <td class="text-right">{{ $prioridad->cantidad }}</td>
                    <td class="text-right"><strong>${{ number_format($prioridad->total, 2) }}</strong></td>
                    <td class="text-right">${{ number_format($prioridad->promedio, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 7: HISTORIAL DE PEDIDOS -->
    @if($pedidos->count() > 0)
    <div class="section">
        <div class="section-title">📋 HISTORIAL DE PEDIDOS (ÚLTIMOS 15)</div>
        
        <table>
            <thead>
                <tr>
                    <th width="10%">PEDIDO #</th>
                    <th width="20%">FECHA ENTREGA</th>
                    <th width="20%">CLIENTE</th>
                    <th width="15%" class="text-right">TOTAL</th>
                    <th width="15%">ESTADO</th>
                    <th width="10%">PRIORIDAD</th>
                    <th width="10%" class="text-right">PRODUCTOS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos->take(15) as $pedido)
                <tr>
                    <td class="text-center">{{ $pedido->id }}</td>
                    <td>{{ date('d/m/Y', strtotime($pedido->Fecha_entrega)) }}</td>
                    <td>{{ $pedido->cliente->nombre ?? 'N/A' }}</td>
                    <td class="text-right"><strong>${{ number_format($pedido->Total, 2) }}</strong></td>
                    <td class="text-center {{ $pedido->Estado == 'Entregado' ? 'estado-entregado' : ($pedido->Estado == 'Pendiente' ? 'estado-pendiente' : '') }}">
                        {{ $pedido->Estado }}
                    </td>
                    <td class="text-center {{ $pedido->Prioridad == 'Alta' ? 'prioridad-alta' : ($pedido->Prioridad == 'Media' ? 'prioridad-media' : '') }}">
                        {{ $pedido->Prioridad }}
                    </td>
                    <td class="text-right">{{ $pedido->detallePedidos->count() }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <td colspan="3"><strong>SUBTOTAL ({{ min(15, $pedidos->count()) }} pedidos):</strong></td>
                    <td class="text-right"><strong>${{ number_format($pedidos->take(15)->sum('Total'), 2) }}</strong></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><strong>{{ $pedidos->take(15)->sum(function($p) { return $p->detallePedidos->count(); }) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 8: PRODUCTOS CON MAYOR VALOR EN PEDIDOS -->
    @if($productosValorPedidos->count() > 0)
    <div class="section">
        <div class="section-title">💰 TOP 10 PRODUCTOS POR VALOR EN PEDIDOS</div>
        
        <table>
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="50%">PRODUCTO</th>
                    <th width="20%" class="text-right">VALOR TOTAL</th>
                    <th width="20%">% DEL TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productosValorPedidos as $index => $producto)
                @php
                    $porcentaje = $totalIngresos > 0 ? ($producto->total_valor / $totalIngresos) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $producto->producto->Nombre ?? 'Producto eliminado' }}</td>
                    <td class="text-right"><strong>${{ number_format($producto->total_valor, 2) }}</strong></td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min($porcentaje, 100) }}%;"></div>
                        </div>
                        {{ number_format($porcentaje, 1) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- SECCIÓN 9: ANÁLISIS Y RECOMENDACIONES -->
    <div class="section">
        <div class="section-title">💡 ANÁLISIS Y RECOMENDACIONES</div>
        
        <div style="background-color: #e3f2fd; padding: 10px; border-radius: 5px; border: 1px solid #bbdefb;">
            <h4 style="color: #0d47a1; font-size: 11px; margin-bottom: 8px;">📋 PUNTOS CLAVE</h4>
            
            <div style="font-size: 9px;">
                <p style="margin: 3px 0;"><strong>✅ Fortalezas:</strong></p>
                <ul style="margin: 5px 0; padding-left: 15px;">
                    @if($pedidoPromedio > 100)
                    <li>Alto valor promedio por pedido (${{ number_format($pedidoPromedio, 2) }})</li>
                    @endif
                    @if($productosUnicos > 10)
                    <li>Amplia variedad de productos ({{ $productosUnicos }} productos únicos)</li>
                    @endif
                    @if($pedidosPorCliente->count() > 5)
                    <li>{{ $pedidosPorCliente->count() }} clientes activos en el período</li>
                    @endif
                </ul>
                
                <p style="margin: 8px 0 3px 0;"><strong>⚠️ Áreas de mejora:</strong></p>
                <ul style="margin: 5px 0; padding-left: 15px;">
                    @if($resumenEjecutivo['pendientes_por_entregar'] > 0)
                    <li>{{ $resumenEjecutivo['pendientes_por_entregar'] }} pedidos pendientes por entregar</li>
                    @endif
                    @if($pedidosPorEstado->where('Estado', 'Cancelado')->count() > 0)
                    <li>{{ $pedidosPorEstado->where('Estado', 'Cancelado')->first()->cantidad ?? 0 }} pedidos cancelados</li>
                    @endif
                    @if($pedidosPorPrioridad->where('Prioridad', 'Alta')->first())
                    <li>{{ $pedidosPorPrioridad->where('Prioridad', 'Alta')->first()->cantidad ?? 0 }} pedidos de alta prioridad requieren atención especial</li>
                    @endif
                </ul>
                
                <p style="margin: 8px 0 3px 0;"><strong>🎯 Estrategias recomendadas:</strong></p>
                <ul style="margin: 5px 0; padding-left: 15px;">
                    <li>Priorizar pedidos pendientes según prioridad y fecha de entrega</li>
                    <li>Crear paquetes promocionales con productos más solicitados</li>
                    <li>Implementar seguimiento de satisfacción para clientes recurrentes</li>
                    <li>Optimizar inventario basado en productos más solicitados</li>
                    <li>Establecer metas de ventas por empleado basadas en desempeño histórico</li>
                </ul>
                
                <p style="margin: 8px 0 3px 0;"><strong>📈 Objetivos para el próximo período:</strong></p>
                <ul style="margin: 5px 0; padding-left: 15px;">
                    <li>Reducir pedidos pendientes en {{ number_format($resumenEjecutivo['pendientes_por_entregar'] * 0.3, 0) }} unidades</li>
                    <li>Aumentar valor promedio de pedido en ${{ number_format($pedidoPromedio * 0.1, 2) }}</li>
                    <li>Incrementar tasa de entrega a tiempo al 95%</li>
                    <li>Aumentar pedidos de clientes recurrentes en 15%</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="footer">
        <div class="page-number"></div>
        <div>Sistema de Gestión de Pedidos | Reporte generado automáticamente</div>
    </div>
</body>
</html>