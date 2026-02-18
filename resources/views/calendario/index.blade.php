@extends('layouts.app')

@section('title', 'Calendario de Pedidos')

@section('content')
<!-- Contenedor principal -->
<div class="calendar-container">
    <!-- Header SIMPLE (no fijo, no sticky) -->
    <div class="bg-white border-bottom shadow-sm py-3">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-header bg-primary text-white rounded-3 p-3">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Calendario de Entregas</h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-1"></i>Gestor de pedidos por fecha de entrega
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="btn-group btn-group-lg shadow-sm">
                        <a href="{{ url()->current() }}?mes={{ $mesAnterior }}&anio={{ $anioMesAnterior }}" 
                           class="btn btn-outline-primary border-end-0 rounded-start-3">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <span class="btn btn-primary px-4 fw-bold border-0" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        ">
                            <i class="fas fa-calendar me-2"></i>
                            {{ \Carbon\Carbon::create($anio, $mes, 1)->translatedFormat('F Y') }}
                        </span>
                        <a href="{{ url()->current() }}?mes={{ $mesSiguiente }}&anio={{ $anioMesSiguiente }}" 
                           class="btn btn-outline-primary border-start-0 rounded-end-3">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-lg rounded-3 shadow-sm">
                        <i class="fas fa-calendar-day me-2"></i>Hoy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container-fluid px-4 py-4">
        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-group">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-stat bg-primary bg-opacity-10 text-primary rounded-2 p-3 me-3">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 fw-bold display-6">{{ $estadisticas['total_pedidos'] ?? 0 }}</h2>
                                    <small class="text-muted">Total de Pedidos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm rounded-3 mx-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-stat bg-danger bg-opacity-10 text-danger rounded-2 p-3 me-3">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 fw-bold display-6">{{ $estadisticas['alta_prioridad'] ?? 0 }}</h2>
                                    <small class="text-muted">Alta Prioridad</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm rounded-3 mx-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-stat bg-warning bg-opacity-10 text-warning rounded-2 p-3 me-3">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 fw-bold display-6">{{ $estadisticas['pendientes'] ?? 0 }}</h2>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-stat bg-success bg-opacity-10 text-success rounded-2 p-3 me-3">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 fw-bold display-6">{{ $estadisticas['media_prioridad'] ?? 0 }}</h2>
                                    <small class="text-muted">Media Prioridad</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario Mejorado -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Días de la semana con estilo mejorado -->
                    <div class="calendar-header-days" style="
                        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                        border-bottom: 2px solid #dee2e6;
                    ">
                        <div class="row g-0 text-center">
                            @foreach(['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB'] as $dia)
                                <div class="col p-3">
                                    <div class="text-uppercase fw-bold text-dark" style="font-size: 0.9rem; letter-spacing: 1px;">
                                        {{ $dia }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Semanas del mes -->
                    <div class="calendar-body">
                        @foreach($calendario as $semana)
                            <div class="calendar-week">
                                @foreach($semana as $dia)
                                    @if($dia['tipo'] === 'vacio')
                                        <div class="calendar-day empty-day"></div>
                                    @else
                                        @php
                                            $fecha = \Carbon\Carbon::parse($dia['fecha'] ?? now()->format('Y-m-d'));
                                            $esFinSemana = $fecha->isWeekend();
                                        @endphp
                                        
                                        <div class="calendar-day {{ $dia['esHoy'] ?? false ? 'today' : '' }} {{ $esFinSemana ? 'weekend' : '' }}">
                                            <!-- Encabezado del día -->
                                            <div class="day-header">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="day-number {{ $dia['esHoy'] ?? false ? 'today-badge' : '' }}">
                                                        {{ $dia['numero'] ?? '?' }}
                                                    </span>
                                                    @if(isset($dia['pedidos']) && count($dia['pedidos']) > 0)
                                                        <span class="pedidos-count rounded-pill">
                                                            {{ count($dia['pedidos']) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Lista de pedidos -->
                                            <div class="pedidos-container">
                                                @if(isset($dia['pedidos']) && count($dia['pedidos']) > 0)
                                                    @foreach($dia['pedidos'] as $index => $pedido)
                                                        @if($index < 3)
                                                            @php
                                                                $prioridad = strtolower($pedido['prioridad'] ?? 'normal');
                                                                $estado = strtolower($pedido['estado'] ?? 'pendiente');
                                                                
                                                                if ($prioridad == 'alta') {
                                                                    $bgColor = 'linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%)';
                                                                    $borderColor = '#dc3545';
                                                                } elseif ($prioridad == 'media') {
                                                                    $bgColor = 'linear-gradient(135deg, #ffd93d 0%, #ffb347 100%)';
                                                                    $borderColor = '#ffc107';
                                                                } elseif ($prioridad == 'baja') {
                                                                    $bgColor = 'linear-gradient(135deg, #6bcf7f 0%, #4ea752 100%)';
                                                                    $borderColor = '#198754';
                                                                } else {
                                                                    $bgColor = 'linear-gradient(135deg, #6c8eff 0%, #4d73fe 100%)';
                                                                    $borderColor = '#0d6efd';
                                                                }
                                                                
                                                                if ($estado == 'pendiente') $estadoColor = '#ffc107';
                                                                elseif ($estado == 'confirmado') $estadoColor = '#0dcaf0';
                                                                elseif (in_array($estado, ['en proceso', 'en_proceso'])) $estadoColor = '#0d6efd';
                                                                elseif (in_array($estado, ['completado', 'entregado'])) $estadoColor = '#198754';
                                                                elseif ($estado == 'cancelado') $estadoColor = '#dc3545';
                                                                else $estadoColor = '#6c757d';
                                                                
                                                                $pedidoId = $pedido['id'] ?? 0;
                                                                $horaEntrega = $pedido['hora_entrega'] ?? '00:00';
                                                                $clienteNombre = $pedido['cliente_nombre'] ?? 'Cliente no especificado';
                                                                
                                                                $total = $pedido['total'] ?? 0;
                                                                $total = is_numeric($total) ? floatval($total) : 0;
                                                            @endphp
                                                            
                                                            <div class="pedido-card" 
                                                                 onclick="verDetallePedido({{ $pedidoId }})"
                                                                 style="--bg-color: {{ $bgColor }}; --border-color: {{ $borderColor }};"
                                                                 data-pedido-id="{{ $pedidoId }}">
                                                                <div class="pedido-content">
                                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                                        <div class="pedido-id">
                                                                            <i class="fas fa-hashtag me-1"></i>
                                                                            <strong>{{ $pedidoId }}</strong>
                                                                        </div>
                                                                        <div class="pedido-hora">
                                                                            <i class="fas fa-clock me-1"></i>
                                                                            {{ substr($horaEntrega, 0, 5) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="pedido-cliente" title="{{ $clienteNombre }}">
                                                                            <i class="fas fa-user me-1"></i>
                                                                            {{ \Illuminate\Support\Str::limit($clienteNombre, 12, '...') }}
                                                                        </div>
                                                                        <div class="pedido-total">
                                                                            <i class="fas fa-dollar-sign me-1"></i>
                                                                            {{ number_format($total, 2) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="pedido-footer mt-2">
                                                                        <span class="estado-badge" style="background: {{ $estadoColor }};">
                                                                            {{ ucfirst($estado) }}
                                                                        </span>
                                                                        <span class="prioridad-badge" style="border-color: {{ $borderColor }};">
                                                                            {{ ucfirst($prioridad) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    
                                                    @if(count($dia['pedidos']) > 3)
                                                        <div class="more-pedidos text-center mt-2">
                                                            <button class="btn btn-sm btn-outline-primary" 
                                                                    onclick="verPedidosDia('{{ $dia['fecha'] ?? '' }}')">
                                                                <i class="fas fa-plus me-1"></i>
                                                                +{{ count($dia['pedidos']) - 3 }} más
                                                            </button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-center py-3">
                                                        <i class="fas fa-calendar-day text-muted fa-lg"></i>
                                                        <small class="d-block text-muted mt-1">Sin pedidos</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral mejorado -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="card-title mb-3 fw-bold">
                            <i class="fas fa-list-check me-2"></i>Pedidos del Día de Hoy
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $hoy = \Carbon\Carbon::today()->format('Y-m-d');
                            $pedidosHoy = collect($calendario)
                                ->flatten(1)
                                ->filter(function($dia) use ($hoy) {
                                    return isset($dia['fecha']) && $dia['fecha'] == $hoy;
                                })
                                ->first()['pedidos'] ?? [];
                        @endphp
                        
                        @if(count($pedidosHoy) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Hora Entrega</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedidosHoy as $pedido)
                                            @php
                                                $total = $pedido['total'] ?? 0;
                                                $total = is_numeric($total) ? floatval($total) : 0;
                                            @endphp
                                            <tr>
                                                <td><strong>#{{ $pedido['id'] ?? 'N/A' }}</strong></td>
                                                <td>{{ $pedido['cliente_nombre'] ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ isset($pedido['hora_entrega']) ? substr($pedido['hora_entrega'], 0, 5) : '--:--' }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($total, 2) }}</td>
                                                <td>
                                                    @php
                                                        $estado = strtolower($pedido['estado'] ?? 'pendiente');
                                                        if ($estado == 'pendiente') $color = 'warning';
                                                        elseif ($estado == 'confirmado') $color = 'info';
                                                        elseif (in_array($estado, ['en proceso', 'en_proceso'])) $color = 'primary';
                                                        elseif (in_array($estado, ['completado', 'entregado'])) $color = 'success';
                                                        elseif ($estado == 'cancelado') $color = 'danger';
                                                        else $color = 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">
                                                        {{ ucfirst($estado) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="verDetallePedido({{ $pedido['id'] ?? 0 }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay pedidos programados para hoy</h5>
                                <p class="text-muted">¡Es un día tranquilo!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-3 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>Leyenda del Calendario
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);"></div>
                                <div>
                                    <small class="fw-bold d-block">Alta Prioridad</small>
                                    <small class="text-muted">Requiere atención inmediata</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: linear-gradient(135deg, #ffd93d 0%, #ffb347 100%);"></div>
                                <div>
                                    <small class="fw-bold d-block">Media Prioridad</small>
                                    <small class="text-muted">Entregar en el día</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: linear-gradient(135deg, #6bcf7f 0%, #4ea752 100%);"></div>
                                <div>
                                    <small class="fw-bold d-block">Baja Prioridad</small>
                                    <small class="text-muted">Flexible en tiempo</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: linear-gradient(135deg, #6c8eff 0%, #4d73fe 100%);"></div>
                                <div>
                                    <small class="fw-bold d-block">Prioridad Normal</small>
                                    <small class="text-muted">Entrega estándar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<div class="modal fade" id="modalDetallePedido" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header" style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-bottom: none;
                border-radius: 1rem 1rem 0 0;
                padding: 1.5rem 2rem;
            ">
                <div class="d-flex align-items-center">
                    <div class="modal-icon me-3">
                        <i class="fas fa-box-open text-white fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0 fw-bold">
                            Detalles del Pedido #<span id="pedidoId"></span>
                        </h5>
                        <small class="text-white-80">Información completa del pedido</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detallePedidoContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando detalles del pedido...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPedidosDia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-calendar-day me-2"></i>
                    Pedidos del <span id="fechaDia"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="pedidosDiaContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<style>
/* ====== RESET COMPLETO DEL NAVBAR ====== */
/* Eliminar cualquier efecto del navbar en esta vista */
body {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Si el navbar tiene fixed-top, lo convertimos a static */
.navbar.fixed-top {
    position: static !important;
    top: auto !important;
    z-index: auto !important;
}

/* Asegurar que no haya elementos superpuestos */
.calendar-container {
    position: relative;
    z-index: 1;
}

/* Header normal */
.bg-white.border-bottom.shadow-sm {
    position: static;
    top: auto;
    margin-top: 0;
    z-index: 1;
}

/* ====== ESTILOS DEL CALENDARIO ====== */
.icon-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.icon-stat {
    transition: transform 0.3s ease;
}

.icon-stat:hover {
    transform: scale(1.1);
}

/* Calendario */
.calendar-header-days {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    position: sticky;
    top: 0; /* Se pega al top del contenedor */
    z-index: 10;
}

.calendar-week {
    display: flex;
    min-height: 180px;
    border-bottom: 1px solid #e9ecef;
}

.calendar-week:last-child {
    border-bottom: none;
}

.calendar-day {
    flex: 1;
    padding: 1rem;
    border-right: 1px solid #e9ecef;
    background: white;
    transition: all 0.3s ease;
    position: relative;
    min-height: 180px;
}

.calendar-day:hover {
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: inset 0 0 0 1px #dee2e6, 0 4px 12px rgba(0,0,0,0.05);
    z-index: 1;
}

.calendar-day.empty-day {
    background: #f8f9fa;
}

.calendar-day.today {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
    border-left: 3px solid #ffc107;
}

.calendar-day.weekend {
    background: #f8f9fa;
}

.calendar-day:last-child {
    border-right: none;
}

/* Encabezado del día */
.day-header {
    margin-bottom: 0.75rem;
}

.day-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #495057;
    display: inline-block;
    width: 32px;
    height: 32px;
    text-align: center;
    line-height: 32px;
    border-radius: 50%;
}

.day-number.today-badge {
    background: linear-gradient(135deg, #ffc107 0%, #ffb347 100%);
    color: #212529;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.pedidos-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    min-width: 24px;
    text-align: center;
}

/* Tarjetas de pedidos */
.pedidos-container {
    max-height: 140px;
    overflow-y: auto;
    padding-right: 5px;
}

.pedidos-container::-webkit-scrollbar {
    width: 4px;
}

.pedidos-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.pedidos-container::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 2px;
}

.pedido-card {
    background: white;
    border-left: 4px solid;
    border-left-color: var(--border-color, #0d6efd);
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.pedido-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--bg-color, linear-gradient(135deg, #6c8eff 0%, #4d73fe 100%));
    opacity: 0.1;
    z-index: 0;
}

.pedido-card:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.pedido-content {
    position: relative;
    z-index: 1;
}

.pedido-id {
    font-size: 0.85rem;
    font-weight: 600;
    color: #212529;
}

.pedido-hora {
    font-size: 0.75rem;
    color: #6c757d;
    background: rgba(108, 117, 125, 0.1);
    padding: 0.1rem 0.5rem;
    border-radius: 12px;
}

.pedido-cliente {
    font-size: 0.8rem;
    color: #495057;
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pedido-total {
    font-size: 0.85rem;
    font-weight: 600;
    color: #198754;
}

.pedido-footer {
    display: flex;
    gap: 0.5rem;
}

.estado-badge, .prioridad-badge {
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    color: white;
}

.prioridad-badge {
    background: transparent;
    border: 1px solid;
    border-color: var(--border-color, #0d6efd);
    color: var(--border-color, #0d6efd);
}

.more-pedidos .btn {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
}

/* Leyenda */
.leyenda-color {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Table */
.table-hover tbody tr:hover {
    background: rgba(13, 110, 253, 0.05);
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-week {
        min-height: 150px;
    }
    
    .calendar-day {
        min-height: 150px;
        padding: 0.5rem;
    }
    
    .pedido-card {
        padding: 0.5rem;
    }
    
    .pedido-id, .pedido-cliente, .pedido-total {
        font-size: 0.7rem;
    }
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.calendar-day {
    animation: fadeInUp 0.5s ease-out;
}

/* Efecto de brillo en hover */
.pedido-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.7s;
}

.pedido-card:hover::after {
    left: 100%;
}

/* Días de fin de semana */
.calendar-day.weekend {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Efecto de resaltado para día actual */
.calendar-day.today {
    animation: pulseToday 2s infinite;
}

@keyframes pulseToday {
    0%, 100% {
        box-shadow: inset 0 0 0 1px #ffc107;
    }
    50% {
        box-shadow: inset 0 0 0 1px #ffc107, 0 0 15px rgba(255, 193, 7, 0.2);
    }
}
</style>

@push('scripts')
<script>
// SOLUCIÓN DEFINITIVA PARA EL NAVBAR
document.addEventListener('DOMContentLoaded', function() {
    // 1. Identificar el navbar
    const navbar = document.querySelector('.navbar');
    
    if (navbar) {
        console.log('Navbar encontrado:', navbar);
        
        // 2. Quitar clases que causen problemas
        navbar.classList.remove('fixed-top');
        navbar.classList.remove('sticky-top');
        
        // 3. Aplicar estilos seguros
        navbar.style.position = 'static';
        navbar.style.top = 'auto';
        navbar.style.zIndex = '1';
        
        // 4. Asegurar que el body no tenga padding
        document.body.style.paddingTop = '0';
        document.body.style.marginTop = '0';
        
        // 5. Verificar si hay otros elementos fixed
        document.querySelectorAll('*').forEach(el => {
            const style = window.getComputedStyle(el);
            if (style.position === 'fixed') {
                console.log('Elemento fixed encontrado:', el);
                el.style.position = 'relative';
            }
        });
    }
    
    // 6. Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// FUNCIÓN MEJORADA CON ESTILOS Y SOLUCIÓN DE DATOS
// FUNCIÓN SIMPLIFICADA QUE FUNCIONA MEJOR
function verDetallePedido(pedidoId) {
    if (!pedidoId || pedidoId === 0) {
        showNotification('ID de pedido inválido', 'error');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetallePedido'));
    document.getElementById('pedidoId').textContent = pedidoId;
    
    // Mostrar loading simple
    document.getElementById('detallePedidoContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-4 text-muted fs-5">Cargando detalles del pedido...</p>
        </div>
    `;
    
    modal.show();

    // URL usando la ruta de Laravel
    const url = "{{ route('calendario.pedido.detalle', ':id') }}".replace(':id', pedidoId);
    
    console.log('Solicitando detalles del pedido:', url);
    
    // Realizar la petición
    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);
        
        if (data.success) {
            // Agregar los estilos CSS directamente
            const styles = `
            <style>
                .detalle-completo {
                    padding: 20px;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-radius: 12px;
                }
                
                .seccion-pedido, .seccion-cliente, .seccion-productos, .seccion-notas {
                    background: white;
                    padding: 25px;
                    margin-bottom: 20px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                    border-left: 5px solid;
                }
                
                .seccion-pedido {
                    border-left-color: #667eea;
                }
                
                .seccion-cliente {
                    border-left-color: #4facfe;
                }
                
                .seccion-productos {
                    border-left-color: #43e97b;
                }
                
                .seccion-notas {
                    border-left-color: #ff9a9e;
                }
                
                .titulo-seccion {
                    color: #495057;
                    font-weight: 600;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #dee2e6;
                    font-size: 1.1rem;
                }
                
                .info-item {
                    margin-bottom: 15px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                    transition: all 0.3s ease;
                }
                
                .info-item:hover {
                    background: #e9ecef;
                    transform: translateX(5px);
                }
                
                .info-label {
                    display: block;
                    font-size: 0.85rem;
                    color: #6c757d;
                    font-weight: 500;
                    margin-bottom: 5px;
                }
                
                .info-value {
                    display: block;
                    font-size: 1rem;
                    color: #212529;
                    font-weight: 600;
                }
                
                .tabla-productos {
                    width: 100%;
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                
                .tabla-productos thead {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }
                
                .tabla-productos th {
                    border: none;
                    padding: 15px;
                    font-weight: 600;
                    font-size: 0.9rem;
                }
                
                .tabla-productos tbody tr {
                    border-bottom: 1px solid #e9ecef;
                    transition: background-color 0.3s ease;
                }
                
                .tabla-productos tbody tr:hover {
                    background-color: rgba(102, 126, 234, 0.05);
                }
                
                .tabla-productos td {
                    padding: 15px;
                    vertical-align: middle;
                }
                
                .total-row {
                    background: #f8f9fa;
                    font-size: 1.1rem;
                }
                
                .total-pedido {
                    color: #198754;
                    font-size: 1.2rem;
                }
                
                .observacion-box {
                    background: #fff3cd;
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid #ffc107;
                    font-style: italic;
                }
                
                .estado-badge, .prioridad-badge {
                    padding: 8px 16px;
                    font-size: 0.9rem;
                    border-radius: 20px;
                    font-weight: 600;
                }
                
                .badge.bg-danger { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important; }
                .badge.bg-warning { background: linear-gradient(135deg, #ffd93d 0%, #ffb347 100%) !important; }
                .badge.bg-success { background: linear-gradient(135deg, #6bcf7f 0%, #4ea752 100%) !important; }
                .badge.bg-primary { background: linear-gradient(135deg, #6c8eff 0%, #4d73fe 100%) !important; }
                .badge.bg-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important; }
                
                @media (max-width: 768px) {
                    .detalle-completo {
                        padding: 10px;
                    }
                    
                    .seccion-pedido, .seccion-cliente, .seccion-productos, .seccion-notas {
                        padding: 15px;
                    }
                    
                    .tabla-productos th, 
                    .tabla-productos td {
                        padding: 8px;
                        font-size: 0.85rem;
                    }
                }
            </style>
            `;
            
            // Insertar los estilos y el contenido
            document.getElementById('detallePedidoContent').innerHTML = styles + data.html;
            
        } else {
            document.getElementById('detallePedidoContent').innerHTML = `
                <div class="alert alert-danger m-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">¡Error!</h5>
                            <p class="mb-0">${data.error}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        document.getElementById('detallePedidoContent').innerHTML = `
            <div class="alert alert-danger m-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading">Error de conexión</h5>
                        <p class="mb-0">No se pudo conectar con el servidor. Verifica:</p>
                        <ul class="mt-2 mb-0">
                            <li>Que tengas conexión a internet</li>
                            <li>Que el servidor esté funcionando</li>
                            <li>Que la ruta exista: ${url}</li>
                        </ul>
                    </div>
                </div>
            </div>
        `;
    });
}
// Función para mostrar error en modal
function showErrorModal(message) {
    document.getElementById('detallePedidoContent').innerHTML = `
        <div class="error-container p-5 text-center">
            <div class="error-icon mb-4">
                <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
            </div>
            <h4 class="text-danger fw-bold mb-3">¡Error!</h4>
            <p class="text-muted mb-4">${message}</p>
            <button class="btn btn-primary" onclick="verDetallePedido(${document.getElementById('pedidoId').textContent})">
                <i class="fas fa-redo me-2"></i>Reintentar
            </button>
        </div>
    `;
}

// Función para agregar estilos personalizados
function addCustomStyles() {
    const style = document.createElement('style');
    style.textContent = `
        /* Estilos personalizados para detalles del pedido */
        .detalle-pedido-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 500px;
        }
        
        .card {
            border-radius: 12px;
            border: none;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header.bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-header.bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-header.bg-gradient-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border-radius: 12px 12px 0 0 !important;
        }
        
        .table-details {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .table-details th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 0.75rem;
        }
        
        .table-details td {
            padding: 0.75rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table-details tr:last-child td {
            border-bottom: none;
        }
        
        .products-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .products-table thead {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
        }
        
        .products-table th {
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .products-table tbody tr {
            transition: background-color 0.3s ease;
        }
        
        .products-table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        .products-table td {
            padding: 1rem;
            border-color: #e9ecef;
        }
        
        .products-table tfoot {
            background: #f8f9fa;
            font-weight: bold;
        }
        
        .detail-label {
            color: #6c757d;
            font-weight: 600;
            width: 40%;
        }
        
        .detail-value {
            color: #212529;
            font-weight: 500;
        }
        
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #dee2e6;
        }
        
        .badge {
            padding: 0.5em 1em;
            font-size: 0.85em;
            border-radius: 20px;
        }
        
        .loading-container {
            animation: fadeIn 0.5s ease-out;
        }
        
        .error-container {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .detalle-pedido-container {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .products-table th,
            .products-table td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    `;
    
    // Solo agregar si no existe ya
    if (!document.getElementById('pedido-detalle-styles')) {
        style.id = 'pedido-detalle-styles';
        document.head.appendChild(style);
    }
}

// Función para ver pedidos del día
function verPedidosDia(fecha) {
    if (!fecha) {
        showNotification('Fecha no especificada', 'error');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('modalPedidosDia'));
    const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('fechaDia').textContent = fechaFormateada;
    
    document.getElementById('pedidosDiaContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando pedidos del día...</p>
        </div>
    `;
    
    modal.show();

    setTimeout(() => {
        document.getElementById('pedidosDiaContent').innerHTML = `
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>${fechaFormateada}</strong>
            </div>
            <div class="text-center py-4">
                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Funcionalidad en desarrollo</h5>
                <p class="text-muted">Pronto podrás ver todos los pedidos de esta fecha aquí.</p>
            </div>
        `;
    }, 1000);
}

// Funciones adicionales
function imprimirDetalles() {
    window.print();
}

function marcarComoCompletado(pedidoId) {
    if (confirm('¿Marcar este pedido como completado?')) {
        showNotification('Pedido marcado como completado', 'success');
        // Aquí iría la lógica para actualizar el estado del pedido
    }
}

function editarPedido(pedidoId) {
    showNotification('Redirigiendo a edición del pedido...', 'info');
    // Aquí iría la lógica para redirigir a la edición del pedido
    // window.location.href = `/pedidos/${pedidoId}/edit`;
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 10px;
        animation: slideInRight 0.3s ease-out;
    `;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 
                               type === 'error' ? 'exclamation-circle' : 
                               type === 'warning' ? 'exclamation-triangle' : 'info-circle'} 
               me-2 fa-lg"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Agregar estilos de animación
const animationStyle = document.createElement('style');
animationStyle.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(animationStyle);

// Efecto hover en tarjetas de pedido
document.querySelectorAll('.pedido-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(5px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});
</script>
@endpush
@endsection