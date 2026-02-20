<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Ventas</title>

<style>
@page {
    margin: 2.2cm;
}

body {
    font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
    font-size: 10px;
    color: #2c3e50;
    line-height: 1.4;
}

h1 {
    font-size: 22px;
    text-align: center;
    margin-bottom: 5px;
    color: #1e272e;
    font-weight: 600;
    letter-spacing: 1px;
}

h2 {
    font-size: 14px;
    margin-top: 25px;
    border-bottom: 2px solid #2c3e50;
    padding-bottom: 6px;
    color: #1e272e;
    font-weight: 500;
}

.header-info {
    text-align: center;
    font-size: 9px;
    margin-bottom: 25px;
    color: #7f8c8d;
    padding-bottom: 10px;
    border-bottom: 1px dashed #bdc3c7;
}

/* ===== MÉTRICAS (IGUAL A TU DISEÑO) ===== */
.metrics {
    width: 100%;
    margin-bottom: 25px;
    text-align: center;
}

.metric-box {
    width: 23%;
    display: inline-block;
    background: #f4f6f9;
    padding: 12px 5px;
    margin-right: 1%;
    text-align: center;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.metric-box:last-child {
    margin-right: 0;
}

.metric-title {
    font-size: 8px;
    text-transform: uppercase;
    color: #7f8c8d;
    letter-spacing: 0.5px;
}

.metric-value {
    font-size: 18px;
    font-weight: 600;
    margin-top: 5px;
    color: #2c3e50;
}

/* ===== GRÁFICAS - EXACTAMENTE TUS COLORES ===== */
.chart-container {
    margin-top: 15px;
    margin-bottom: 25px;
}

.bar-row {
    margin-bottom: 15px;
}

.bar-label {
    font-size: 10px;
    font-weight: 500;
    margin-bottom: 4px;
    color: #34495e;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.bar-label .rank-number {
    display: inline-block;
    width: 18px;
    height: 18px;
    background: #ecf0f1;
    color: #2c3e50;
    text-align: center;
    line-height: 18px;
    border-radius: 3px;
    font-size: 9px;
    font-weight: 600;
    margin-right: 6px;
}

.bar-label .rank-1 {
    background: #2c3e50;
    color: white;
}

.bar-label .rank-2 {
    background: #7f8c8d;
    color: white;
}

.bar-label .rank-3 {
    background: #95a5a6;
    color: white;
}

.bar-wrapper {
    width: 100%;
    background: #ecf0f1;
    height: 16px;
    border-radius: 2px;
    overflow: hidden;
}

/* ===== COLORES ESPECÍFICOS QUE PEDISTE ===== */
.bar-fill-blue {
    height: 16px;
    background: #3498db;  /* AZUL PARA EMPLEADOS */
    width: 0%;
    transition: width 0.2s ease;
}

.bar-fill-green {
    height: 16px;
    background: #2ecc71;  /* VERDE PARA PRODUCTOS */
    width: 0%;
    transition: width 0.2s ease;
}

.bar-value {
    font-size: 9px;
    margin-top: 4px;
    color: #555;
    display: flex;
    justify-content: space-between;
}

.bar-value strong {
    color: #2c3e50;
    font-weight: 600;
}

/* ===== TABLAS (MEJORADAS PERO CONSERVANDO ESENCIA) ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    font-size: 9px;
}

th {
    background: #2c3e50;
    color: white;
    padding: 8px 6px;
    font-size: 9px;
    font-weight: 500;
    text-align: left;
}

th:first-child {
    padding-left: 10px;
    border-radius: 4px 0 0 0;
}

th:last-child {
    padding-right: 10px;
    border-radius: 0 4px 0 0;
}

td {
    padding: 7px 6px;
    border-bottom: 1px solid #ecf0f1;
    font-size: 9px;
}

td:first-child {
    padding-left: 10px;
}

td:last-child {
    padding-right: 10px;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background: #f8f9fa;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    background: #ecf0f1;
    color: #2c3e50;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 8px;
    font-weight: 600;
}

.badge-dark {
    background: #2c3e50;
    color: white;
}

.badge-blue {
    background: #3498db;
    color: white;
}

.badge-green {
    background: #2ecc71;
    color: white;
}

/* ===== RESUMEN ===== */
.summary-box {
    margin-top: 25px;
    padding: 15px;
    background: #f4f6f9;
    border-left: 4px solid #2c3e50;
    border-radius: 0 4px 4px 0;
}

.summary-title {
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2c3e50;
}

.summary-grid {
    display: flex;
    gap: 30px;
    font-size: 9px;
}

.summary-item p {
    margin-bottom: 5px;
    color: #34495e;
}

.summary-item strong {
    color: #2c3e50;
    font-weight: 600;
    min-width: 100px;
    display: inline-block;
}

/* ===== FOOTER ===== */
.footer {
    position: fixed;
    bottom: -15px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 8px;
    color: #95a5a6;
    padding-top: 8px;
    border-top: 1px solid #ecf0f1;
}

.page-number:before {
    content: "Página " counter(page);
}

/* ===== UTILIDADES ===== */
.truncate {
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.separator {
    height: 1px;
    background: #ecf0f1;
    margin: 15px 0;
}
</style>
</head>

<body>

<h1>REPORTE DE VENTAS</h1>

<div class="header-info">
    Periodo: {{ date('d/m/Y', strtotime($fechaInicio)) }} — {{ date('d/m/Y', strtotime($fechaFin)) }} 
    <span style="margin:0 8px;">|</span> 
    Generado: {{ $fechaGeneracion }}
    @if($empleadoSeleccionado ?? false)
    <span class="badge-dark" style="margin-left: 8px; padding:2px 8px;">{{ $empleadoSeleccionado->nombre_completo ?? $empleadoSeleccionado->Nombre }}</span>
    @endif
</div>

<!-- ===== MÉTRICAS ===== -->
<div class="metrics">
    <div class="metric-box">
        <div class="metric-title">Ventas</div>
        <div class="metric-value">{{ number_format($totalVentas) }}</div>
    </div>

    <div class="metric-box">
        <div class="metric-title">Ingresos</div>
        <div class="metric-value">${{ number_format($totalIngresos, 0) }}</div>
    </div>

    <div class="metric-box">
        <div class="metric-title">Promedio</div>
        <div class="metric-value">${{ number_format($ventaPromedio, 2) }}</div>
    </div>

    <div class="metric-box">
        <div class="metric-title">Ventas/Día</div>
        <div class="metric-value">{{ number_format($resumenEjecutivo['ventas_por_dia'], 1) }}</div>
    </div>
</div>

<!-- ===== SPOTLIGHT (DESTACADOS) ===== -->
@if(($mejorVenta ?? false) || ($resumenEjecutivo['empleado_top'] ?? false))
<div style="display: flex; gap: 15px; margin-bottom: 25px;">
    @if($mejorVenta ?? false)
    <div style="flex:1; background:#f4f6f9; padding:12px 15px; border-left:4px solid #3498db;">
        <div style="font-size:7px; text-transform:uppercase; color:#7f8c8d;">Mejor Venta</div>
        <div style="font-size:16px; font-weight:600; color:#2c3e50;">${{ number_format($mejorVenta->Total, 2) }}</div>
        <div style="font-size:8px; color:#95a5a6;">Venta #{{ $mejorVenta->id }} · {{ date('d/m/Y', strtotime($mejorVenta->Fecha)) }}</div>
    </div>
    @endif
    
    @if($resumenEjecutivo['empleado_top'] ?? false)
    @php
        $empleado = $resumenEjecutivo['empleado_top']->empleado;
        $nombreCompleto = $empleado ? trim(($empleado->Nombre ?? '') . ' ' . ($empleado->ApPaterno ?? '')) : '—';
    @endphp
    <div style="flex:1; background:#f4f6f9; padding:12px 15px; border-left:4px solid #2ecc71;">
        <div style="font-size:7px; text-transform:uppercase; color:#7f8c8d;">Mejor Desempeño</div>
        <div style="font-size:14px; font-weight:600; color:#2c3e50;" class="truncate">{{ $nombreCompleto }}</div>
        <div style="font-size:8px; color:#95a5a6;">{{ $resumenEjecutivo['empleado_top']->total_ventas }} ventas · ${{ number_format($resumenEjecutivo['empleado_top']->total_ingresos, 0) }}</div>
    </div>
    @endif
</div>
@endif

<!-- ============================= -->
<!-- ===== GRÁFICA EMPLEADOS ===== -->
<!-- ===== COLOR AZUL #3498db ===== -->
<!-- ============================= -->

<h2>Rendimiento por Empleado</h2>

<div class="chart-container">
@php
$maxVentas = $datosGraficaEmpleados->max('ventas') ?: 1;
$totalIngresosEmpleados = $datosGraficaEmpleados->sum('ingresos');
$totalVentasEmpleados = $datosGraficaEmpleados->sum('ventas');
@endphp

@foreach($datosGraficaEmpleados as $index => $empleado)
@php
$porcentaje = ($empleado['ventas'] / $maxVentas) * 100;
$promedio = $empleado['ventas'] > 0 ? $empleado['ingresos'] / $empleado['ventas'] : 0;
@endphp

<div class="bar-row">
    <div class="bar-label">
        <span>
            <span class="rank-number {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">{{ $index + 1 }}</span>
            {{ $empleado['nombre'] }}
        </span>
        <span class="badge-blue" style="font-size:8px;">{{ $empleado['ventas'] }} ventas</span>
    </div>
    <div class="bar-wrapper">
        <div class="bar-fill-blue" style="width: {{ $porcentaje }}%;"></div>
    </div>
    <div class="bar-value">
        <span>💵 <strong>${{ number_format($empleado['ingresos'], 2) }}</strong></span>
        <span>📊 Prom. <strong>${{ number_format($promedio, 2) }}</strong></span>
    </div>
</div>

@endforeach

<div class="bar-value" style="margin-top: 10px; padding-top: 8px; border-top: 2px solid #ecf0f1;">
    <span><strong>TOTALES:</strong> {{ $totalVentasEmpleados }} ventas</span>
    <span><strong>${{ number_format($totalIngresosEmpleados, 2) }}</strong></span>
</div>
</div>

<!-- ============================= -->
<!-- ===== GRÁFICA PRODUCTOS ===== -->
<!-- ===== COLOR VERDE #2ecc71 ===== -->
<!-- ============================= -->

<h2>Top Productos</h2>

<div class="chart-container">
@php
$maxCantidad = $datosGraficaProductos->max('cantidad') ?: 1;
$totalUnidades = $datosGraficaProductos->sum('cantidad');
$totalIngresosProductos = $datosGraficaProductos->sum('ingresos');
@endphp

@foreach($datosGraficaProductos as $index => $producto)
@php
$porcentaje = ($producto['cantidad'] / $maxCantidad) * 100;
$precioPromedio = $producto['cantidad'] > 0 ? $producto['ingresos'] / $producto['cantidad'] : 0;
@endphp

<div class="bar-row">
    <div class="bar-label">
        <span>
            <span class="rank-number {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">{{ $index + 1 }}</span>
            {{ $producto['nombre'] }}
        </span>
        <span class="badge-green" style="font-size:8px;">{{ $producto['cantidad'] }} unid.</span>
    </div>
    <div class="bar-wrapper">
        <div class="bar-fill-green" style="width: {{ $porcentaje }}%;"></div>
    </div>
    <div class="bar-value">
        <span>💵 <strong>${{ number_format($producto['ingresos'], 2) }}</strong></span>
        <span>🏷️ <strong>${{ number_format($precioPromedio, 2) }}</strong> c/u</span>
    </div>
</div>

@endforeach

<div class="bar-value" style="margin-top: 10px; padding-top: 8px; border-top: 2px solid #ecf0f1;">
    <span><strong>TOTALES:</strong> {{ $totalUnidades }} unidades</span>
    <span><strong>${{ number_format($totalIngresosProductos, 2) }}</strong></span>
</div>
</div>

<!-- ============================= -->
<!-- ===== TABLA EMPLEADOS ===== -->
<!-- ============================= -->

@if($ventasPorEmpleado->count() > 0)
<h2>Detalle por Colaborador</h2>

<table>
<thead>
<tr>
    <th>Nombre</th>
    <th class="text-right">Ventas</th>
    <th class="text-right">Ingresos</th>
    <th class="text-right">Promedio</th>
    <th class="text-right">%</th>
</tr>
</thead>
<tbody>
@foreach($ventasPorEmpleado as $index => $ventaEmpleado)
@php
$porcentaje = $totalIngresos > 0 ? ($ventaEmpleado->total_ingresos / $totalIngresos) * 100 : 0;
$empleado = $ventaEmpleado->empleado;
$nombreEmpleado = $empleado 
    ? trim(($empleado->Nombre ?? '') . ' ' . ($empleado->ApPaterno ?? ''))
    : '—';
@endphp
<tr>
    <td>
        @if($index == 0)
            <span class="badge-dark" style="margin-right:5px;">TOP</span>
        @endif
        {{ $nombreEmpleado }}
    </td>
    <td class="text-right">{{ $ventaEmpleado->total_ventas }}</td>
    <td class="text-right">${{ number_format($ventaEmpleado->total_ingresos, 2) }}</td>
    <td class="text-right">${{ number_format($ventaEmpleado->promedio_venta, 2) }}</td>
    <td class="text-right">{{ number_format($porcentaje, 1) }}%</td>
</tr>
@endforeach
</tbody>
</table>
@endif

<!-- ============================= -->
<!-- ===== TABLA VENTAS ===== -->
<!-- ============================= -->

@if($ventas->count() > 0)
<h2>Últimas Transacciones</h2>

<table>
<thead>
<tr>
    <th>Folio</th>
    <th>Fecha</th>
    <th>Empleado</th>
    <th class="text-right">Total</th>
    <th class="text-center">Items</th>
</tr>
</thead>
<tbody>
@foreach($ventas->take(12) as $venta)
<tr>
    <td>#{{ str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}</td>
    <td>{{ date('d/m', strtotime($venta->Fecha)) }}</td>
    <td class="truncate">
        {{ $venta->empleado 
            ? trim(($venta->empleado->Nombre ?? '') . ' ' . ($venta->empleado->ApPaterno ?? ''))
            : '—' }}
    </td>
    <td class="text-right">${{ number_format($venta->Total, 2) }}</td>
    <td class="text-center">{{ $venta->detalleVentas->count() }}</td>
</tr>
@endforeach
</tbody>
</table>
@endif

<!-- ============================= -->
<!-- ===== RESUMEN EJECUTIVO ===== -->
<!-- ============================= -->

<div class="summary-box">
    <div class="summary-title">RESUMEN EJECUTIVO</div>
    <div class="summary-grid">
        <div class="summary-item">
            <p><strong>Período analizado:</strong> {{ $resumenEjecutivo['dias_periodo'] }} días</p>
            <p><strong>Días con ventas:</strong> {{ $resumenEjecutivo['dias_con_ventas'] }}</p>
            <p><strong>Días sin ventas:</strong> {{ $resumenEjecutivo['dias_sin_ventas'] }}</p>
        </div>
        <div class="summary-item">
            <p><strong>Ingreso promedio diario:</strong> ${{ number_format($resumenEjecutivo['ingresos_por_dia'], 2) }}</p>
            @if($productosMasVendidos->first() ?? false)
            <p><strong>Producto más vendido:</strong> {{ $productosMasVendidos->first()->producto->Nombre ?? '—' }}</p>
            @endif
        </div>
    </div>
</div>

<div class="footer">
    CONFIDENCIAL · <span class="page-number"></span> · {{ $fechaGeneracion }}
</div>

</body>
</html>