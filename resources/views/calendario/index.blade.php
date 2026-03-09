@extends('layouts.app')

@section('title', 'Calendario de Pedidos')

@section('content')
<!-- Contenedor principal -->
<div class="calendar-container">
    <!-- Header mejorado -->
    <div class="bg-white border-bottom shadow-sm py-3">
        <div class="container-fluid px-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
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
                <div class="d-flex gap-3 align-items-center flex-wrap">
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
        <!-- ESTADÍSTICAS - ACTUALIZADAS CON LOS ESTADOS CORRECTOS -->
        <div class="row mb-4 g-3">
            <!-- Total de Pedidos -->
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary rounded-2 p-3 me-3">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['total_pedidos'] ?? 0 }}</h2>
                                <small class="text-muted">Total Pedidos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alta Prioridad -->
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-danger bg-opacity-10 text-danger rounded-2 p-3 me-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['alta_prioridad'] ?? 0 }}</h2>
                                <small class="text-muted">Alta</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Prioridad -->
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning rounded-2 p-3 me-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['media_prioridad'] ?? 0 }}</h2>
                                <small class="text-muted">Media</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baja Prioridad -->
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success bg-opacity-10 text-success rounded-2 p-3 me-3">
                                <i class="fas fa-arrow-down fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['baja_prioridad'] ?? 0 }}</h2>
                                <small class="text-muted">Baja</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prioridad Normal -->
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-info bg-opacity-10 text-info rounded-2 p-3 me-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['normal_prioridad'] ?? 0 }}</h2>
                                <small class="text-muted">Normal</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Proceso (REEMPLAZA A PENDIENTES) -->
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary rounded-2 p-3 me-3">
                                <i class="fas fa-spinner fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['en_proceso'] ?? 0 }}</h2>
                                <small class="text-muted">En Proceso</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEGUNDA FILA DE ESTADÍSTICAS - Cancelados y Completados -->
        <div class="row mb-4 g-3">
            <!-- Cancelados (NUEVO) -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-danger bg-opacity-10 text-danger rounded-2 p-3 me-3">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['cancelados'] ?? 0 }}</h2>
                                <small class="text-muted">Cancelados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completados (NUEVO) -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success bg-opacity-10 text-success rounded-2 p-3 me-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold fs-1">{{ $estadisticas['completados'] ?? 0 }}</h2>
                                <small class="text-muted">Completados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Días de la semana -->
                    <div class="calendar-header-days">
                        <div class="row g-0 text-center">
                            @foreach(['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB'] as $dia)
                                <div class="col p-3">
                                    <div class="text-uppercase fw-bold text-dark">
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
                                            $totalPedidosDia = count($dia['pedidos'] ?? []);
                                        @endphp
                                        
                                        <div class="calendar-day {{ $dia['esHoy'] ?? false ? 'today' : '' }} {{ $esFinSemana ? 'weekend' : '' }}">
                                            <!-- Encabezado del día -->
                                            <div class="day-header">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="day-number {{ $dia['esHoy'] ?? false ? 'today-badge' : '' }}">
                                                        {{ $dia['numero'] ?? '?' }}
                                                    </span>
                                                    @if($totalPedidosDia > 0)
                                                        <span class="pedidos-count rounded-pill">
                                                            {{ $totalPedidosDia }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Lista de pedidos -->
                                            <div class="pedidos-container">
                                                @if($totalPedidosDia > 0)
                                                    @foreach($dia['pedidos'] as $index => $pedido)
                                                        @if($index < 3)
                                                            @php
                                                                $prioridad = strtolower($pedido['prioridad'] ?? 'normal');
                                                                $estado = strtolower($pedido['estado'] ?? 'en proceso');
                                                                
                                                                // Color según prioridad
                                                                switch($prioridad) {
                                                                    case 'alta':
                                                                        $colorBorde = '#dc3545';
                                                                        $colorFondo = 'rgba(220, 53, 69, 0.1)';
                                                                        $colorTexto = '#dc3545';
                                                                        break;
                                                                    case 'media':
                                                                        $colorBorde = '#ffc107';
                                                                        $colorFondo = 'rgba(255, 193, 7, 0.1)';
                                                                        $colorTexto = '#856404';
                                                                        break;
                                                                    case 'baja':
                                                                        $colorBorde = '#198754';
                                                                        $colorFondo = 'rgba(25, 135, 84, 0.1)';
                                                                        $colorTexto = '#198754';
                                                                        break;
                                                                    default: // normal
                                                                        $colorBorde = '#0d6efd';
                                                                        $colorFondo = 'rgba(13, 110, 253, 0.1)';
                                                                        $colorTexto = '#0d6efd';
                                                                        break;
                                                                }
                                                                
                                                                // Color según estado (para el badge)
                                                                switch($estado) {
                                                                    case 'en proceso':
                                                                    case 'en_proceso':
                                                                        $estadoColor = '#0d6efd'; // azul
                                                                        $estadoTexto = 'En Proceso';
                                                                        break;
                                                                    case 'cancelado':
                                                                        $estadoColor = '#dc3545'; // rojo
                                                                        $estadoTexto = 'Cancelado';
                                                                        break;
                                                                    case 'completado':
                                                                        $estadoColor = '#198754'; // verde
                                                                        $estadoTexto = 'Completado';
                                                                        break;
                                                                    default:
                                                                        $estadoColor = '#6c757d'; // gris
                                                                        $estadoTexto = ucfirst($estado);
                                                                }
                                                                
                                                                $pedidoId = $pedido['id'] ?? 0;
                                                                $horaEntrega = $pedido['hora_entrega'] ?? '00:00';
                                                                $clienteNombre = $pedido['cliente_nombre'] ?? 'Cliente';
                                                                
                                                                $total = $pedido['total'] ?? 0;
                                                                if (is_string($total)) {
                                                                    $total = floatval(str_replace(',', '', $total));
                                                                }
                                                                
                                                                $tieneComentario = !empty($pedido['comentario']);
                                                            @endphp
                                                            
                                                            <div class="pedido-card" 
                                                                 onclick="abrirModalPersonalizado({{ $pedidoId }})"
                                                                 style="border-left-color: {{ $colorBorde }}; background-color: {{ $colorFondo }};">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <small style="color: {{ $colorBorde }};"><strong>#{{ $pedidoId }}</strong></small>
                                                                    <small class="badge" style="background-color: {{ $colorBorde }}; color: white;">{{ substr($horaEntrega, 0, 5) }}</small>
                                                                </div>
                                                                <div class="small text-truncate">
                                                                    <i class="fas fa-user me-1" style="color: {{ $colorBorde }};"></i>
                                                                    {{ $clienteNombre }}
                                                                </div>
                                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                                    <small class="badge" style="background-color: {{ $estadoColor }}; color: white;">{{ $estadoTexto }}</small>
                                                                    <small class="fw-bold" style="color: {{ $colorBorde }};">${{ number_format($total, 2) }}</small>
                                                                </div>
                                                                @if($tieneComentario)
                                                                    <small class="text-warning"><i class="fas fa-comment-dots"></i> Nota</small>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    
                                                    @if($totalPedidosDia > 3)
                                                        <div class="text-center mt-2">
                                                            <button class="btn btn-sm w-100" 
                                                                    onclick="verPedidosDiaPersonalizado('{{ $dia['fecha'] ?? '' }}')"
                                                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                                                <i class="fas fa-plus-circle me-1"></i>
                                                                Ver {{ $totalPedidosDia - 3 }} más
                                                            </button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-center py-3">
                                                        <small class="text-muted">Sin pedidos</small>
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

        <!-- Panel inferior -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-list-check me-2 text-primary"></i>Pedidos de Hoy
                        </h5>
                    </div>
                    <div class="card-body p-4">
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
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Hora</th>
                                            <th>Total</th>
                                            <th>Prioridad</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedidosHoy as $pedido)
                                            @php
                                                $prioridad = strtolower($pedido['prioridad'] ?? 'normal');
                                                $estado = strtolower($pedido['estado'] ?? 'en proceso');
                                                
                                                $prioridadClass = [
                                                    'alta' => 'danger',
                                                    'media' => 'warning',
                                                    'baja' => 'success',
                                                    'normal' => 'info'
                                                ][$prioridad] ?? 'secondary';
                                                
                                                // ACTUALIZADO: Clases para los estados correctos
                                                $estadoClass = [
                                                    'en proceso' => 'primary',
                                                    'en_proceso' => 'primary',
                                                    'cancelado' => 'danger',
                                                    'completado' => 'success'
                                                ][$estado] ?? 'secondary';
                                                
                                                // Texto formateado del estado
                                                $estadoTexto = match($estado) {
                                                    'en proceso', 'en_proceso' => 'En Proceso',
                                                    'cancelado' => 'Cancelado',
                                                    'completado' => 'Completado',
                                                    default => ucfirst($estado)
                                                };
                                                
                                                $totalPedido = $pedido['total'] ?? 0;
                                                if (is_string($totalPedido)) {
                                                    $totalPedido = floatval(str_replace(',', '', $totalPedido));
                                                }
                                            @endphp
                                            <tr>
                                                <td><strong>#{{ $pedido['id'] ?? '' }}</strong></td>
                                                <td>{{ $pedido['cliente_nombre'] ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ substr($pedido['hora_entrega'] ?? '--:--', 0, 5) }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($totalPedido, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $prioridadClass }}">
                                                        {{ ucfirst($prioridad) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $estadoClass }}">
                                                        {{ $estadoTexto }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="abrirModalPersonalizado({{ $pedido['id'] ?? 0 }})">
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
                                <i class="fas fa-calendar-check fa-4x text-muted opacity-50 mb-3"></i>
                                <h5 class="text-muted">No hay pedidos programados para hoy</h5>
                                <p class="text-muted small">¡Disfruta el día!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Leyenda
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            <!-- Prioridades -->
                            <h6 class="fw-bold text-muted mb-2">Prioridades</h6>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #dc3545;"></div>
                                <div>
                                    <span class="fw-bold d-block">Alta Prioridad</span>
                                    <small class="text-muted">Requiere atención inmediata</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #ffc107;"></div>
                                <div>
                                    <span class="fw-bold d-block">Media Prioridad</span>
                                    <small class="text-muted">Entregar en el día</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #198754;"></div>
                                <div>
                                    <span class="fw-bold d-block">Baja Prioridad</span>
                                    <small class="text-muted">Flexible en tiempo</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #0d6efd;"></div>
                                <div>
                                    <span class="fw-bold d-block">Prioridad Normal</span>
                                    <small class="text-muted">Entrega estándar</small>
                                </div>
                            </div>
                            
                            <!-- Estados (ACTUALIZADOS) -->
                            <h6 class="fw-bold text-muted mb-2 mt-3">Estados</h6>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #0d6efd;"></div>
                                <div>
                                    <span class="fw-bold d-block">En Proceso</span>
                                    <small class="text-muted">Pedido en preparación</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #dc3545;"></div>
                                <div>
                                    <span class="fw-bold d-block">Cancelado</span>
                                    <small class="text-muted">Pedido cancelado</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="leyenda-color me-3" style="background: #198754;"></div>
                                <div>
                                    <span class="fw-bold d-block">Completado</span>
                                    <small class="text-muted">Pedido entregado</small>
                                </div>
                            </div>
                            
                            <!-- Comentarios -->
                            <div class="d-flex align-items-center mt-2 pt-2 border-top">
                                <div class="leyenda-color me-3 d-flex align-items-center justify-content-center" 
                                     style="background: #ffc10720; border: 1px dashed #ffc107;">
                                    <i class="fas fa-comment-dots text-warning"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block">Con comentarios</span>
                                    <small class="text-muted">Pedidos con notas especiales</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PERSONALIZADO MEJORADO - MÁS ANCHO Y MENOS ALTO -->
<div id="modalPersonalizado" class="modal-personalizado" style="display: none;">
    <div class="modal-personalizado-overlay" onclick="cerrarModalPersonalizado()"></div>
    <div class="modal-personalizado-contenido modal-personalizado-ancho">
        <div class="modal-personalizado-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="modal-personalizado-titulo" id="modalPersonalizadoTitulo">
                <i class="fas fa-box-open me-2"></i>Detalles del Pedido
            </h5>
            <button class="modal-personalizado-cerrar" onclick="cerrarModalPersonalizado()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-personalizado-body" id="modalPersonalizadoBody">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando detalles del pedido...</p>
            </div>
        </div>
        <div class="modal-personalizado-footer">
            <button class="btn btn-light border me-2" onclick="cerrarModalPersonalizado()">
                <i class="fas fa-times me-1"></i>Cerrar
            </button>
        </div>
    </div>
</div>

<!-- MODAL PERSONALIZADO PARA PEDIDOS DEL DÍA - MEJORADO -->
<div id="modalDiaPersonalizado" class="modal-personalizado" style="display: none;">
    <div class="modal-personalizado-overlay" onclick="cerrarModalDiaPersonalizado()"></div>
    <div class="modal-personalizado-contenido modal-personalizado-ancho-medio">
        <div class="modal-personalizado-header" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h5 class="modal-personalizado-titulo">
                <i class="fas fa-calendar-day me-2"></i>Pedidos del Día
            </h5>
            <button class="modal-personalizado-cerrar" onclick="cerrarModalDiaPersonalizado()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-personalizado-body" id="modalDiaPersonalizadoBody">
            <div class="text-center py-4">
                <p>Cargando...</p>
            </div>
        </div>
        <div class="modal-personalizado-footer">
            <button class="btn btn-secondary" onclick="cerrarModalDiaPersonalizado()">
                <i class="fas fa-times me-1"></i>Cerrar
            </button>
        </div>
    </div>
</div>

<style>
/* Estilos básicos del calendario (se mantienen igual) */
.calendar-header-days {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.calendar-week {
    display: flex;
    min-height: 200px;
    border-bottom: 1px solid #dee2e6;
}

.calendar-day {
    flex: 1;
    padding: 10px;
    border-right: 1px solid #dee2e6;
    background: white;
}

.calendar-day.empty-day {
    background: #f8f9fa;
}

.calendar-day.today {
    background: #fff3cd;
    border-left: 3px solid #ffc107;
}

.calendar-day.weekend {
    background: #f8f9fa;
}

.day-number {
    font-size: 1.2rem;
    font-weight: bold;
}

.day-number.today-badge {
    background: #ffc107;
    padding: 2px 8px;
    border-radius: 50%;
    color: #000;
}

.pedidos-count {
    background: #6f42c1;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.pedido-card {
    border-left: 4px solid;
    border-radius: 4px;
    padding: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    font-size: 0.85rem;
}

.pedido-card:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.stats-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.leyenda-color {
    width: 24px;
    height: 24px;
    border-radius: 4px;
}

/* ===== MODAL PERSONALIZADO MEJORADO ===== */
.modal-personalizado {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-personalizado-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(3px);
}

.modal-personalizado-contenido {
    position: relative;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    z-index: 10000;
    animation: modalAbrir 0.3s ease-out;
}

/* Clases para diferentes anchos */
.modal-personalizado-ancho {
    width: 95%;
    max-width: 1200px;  /* Más ancho */
    max-height: 80vh;    /* Menos alto (80% del viewport height) */
}

.modal-personalizado-ancho-medio {
    width: 90%;
    max-width: 800px;
    max-height: 70vh;
}

@keyframes modalAbrir {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-personalizado-header {
    padding: 1.25rem 1.5rem;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-personalizado-titulo {
    margin: 0;
    color: white;
    font-weight: 700;
    font-size: 1.35rem;
    display: flex;
    align-items: center;
}

.modal-personalizado-cerrar {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s;
}

.modal-personalizado-cerrar:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-personalizado-body {
    padding: 1.5rem;
    overflow-y: auto;
    max-height: calc(70vh - 130px); /* Ajustado para el nuevo alto */
    background-color: white;
}

.modal-personalizado-footer {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background-color: #f8fafc;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
    text-align: right;
}

/* Estilos para el contenido del detalle */
.seccion-pedido, .seccion-cliente, .seccion-productos, .seccion-notas {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e9ecef;
    border-left: 4px solid;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.seccion-pedido { border-left-color: #667eea; }
.seccion-cliente { border-left-color: #4facfe; }
.seccion-productos { border-left-color: #43e97b; }
.seccion-notas { border-left-color: #ff9a9e; }

.titulo-seccion {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.titulo-seccion i {
    margin-right: 0.75rem;
    font-size: 1.2rem;
}

.info-item {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    border: 1px solid #e9ecef;
}

.info-item:hover {
    background: #f1f5f9;
}

.info-label {
    color: #64748b;
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: #0f172a;
    font-size: 1.1rem;
    font-weight: 600;
}

.tabla-productos {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.tabla-productos thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.tabla-productos th {
    color: white;
    padding: 1rem;
    font-weight: 600;
    font-size: 0.95rem;
    text-align: left;
}

.tabla-productos td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}

.tabla-productos tbody tr:hover {
    background-color: #f1f5f9;
}

.total-row {
    background-color: #f1f5f9;
    font-weight: 700;
}

.total-row td {
    font-size: 1.1rem;
}

.total-pedido {
    color: #059669;
    font-weight: 700;
}

.observacion-box {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    padding: 1.25rem;
    border-radius: 8px;
    color: #92400e;
}

.observacion-box i {
    color: #f59e0b;
    margin-right: 0.5rem;
}

/* Responsive */
@media (max-width: 992px) {
    .modal-personalizado-ancho {
        max-width: 95%;
        max-height: 85vh;
    }
    
    .modal-personalizado-body {
        max-height: calc(75vh - 130px);
    }
}

@media (max-width: 768px) {
    .calendar-week {
        min-height: 150px;
    }
    
    .pedido-card {
        font-size: 0.75rem;
        padding: 4px;
    }
    
    .modal-personalizado-contenido {
        width: 95%;
        max-height: 90vh;
    }
    
    .modal-personalizado-body {
        padding: 1rem;
    }
    
    .seccion-pedido, .seccion-cliente, .seccion-productos, .seccion-notas {
        padding: 1rem;
    }
    
    .tabla-productos th,
    .tabla-productos td {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
}
</style>

@push('scripts')
<script>
// Función para abrir el modal personalizado de detalle de pedido
function abrirModalPersonalizado(pedidoId) {
    if (!pedidoId) {
        alert('ID de pedido inválido');
        return;
    }
    
    // Mostrar el modal
    const modal = document.getElementById('modalPersonalizado');
    modal.style.display = 'flex';
    
    // Actualizar título
    document.getElementById('modalPersonalizadoTitulo').innerHTML = `<i class="fas fa-box-open me-2"></i>Detalles del Pedido #${pedidoId}`;
    
    // Mostrar contenido de carga
    document.getElementById('modalPersonalizadoBody').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Cargando detalles del pedido #${pedidoId}...</p>
        </div>
    `;
    
    // Hacer la petición AJAX
    const url = "{{ route('calendario.pedido.detalle', ':id') }}".replace(':id', pedidoId);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalPersonalizadoBody').innerHTML = data.html;
            } else {
                document.getElementById('modalPersonalizadoBody').innerHTML = `
                    <div class="alert alert-danger m-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">¡Error!</h5>
                                <p class="mb-0">${data.error || 'Error al cargar el pedido'}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('modalPersonalizadoBody').innerHTML = `
                <div class="alert alert-danger m-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">Error de conexión</h5>
                            <p class="mb-0">No se pudo conectar con el servidor.</p>
                            <button class="btn btn-danger mt-3" onclick="abrirModalPersonalizado(${pedidoId})">
                                <i class="fas fa-sync-alt me-2"></i>Reintentar
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
}

// Función para cerrar el modal personalizado
function cerrarModalPersonalizado() {
    const modal = document.getElementById('modalPersonalizado');
    modal.style.display = 'none';
}

// Función para abrir el modal personalizado de pedidos del día
function verPedidosDiaPersonalizado(fecha) {
    if (!fecha) return;
    
    const modal = document.getElementById('modalDiaPersonalizado');
    modal.style.display = 'flex';
    
    document.getElementById('modalDiaPersonalizadoBody').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Cargando pedidos del día...</p>
        </div>
    `;
    
    // Simular carga (reemplazar con tu lógica real)
    setTimeout(() => {
        const fechaObj = new Date(fecha + 'T12:00:00');
        const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        document.getElementById('modalDiaPersonalizadoBody').innerHTML = `
            <div class="alert alert-info">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-calendar-day fa-2x me-3"></i>
                    <div>
                        <h6 class="mb-1">${fechaFormateada}</h6>
                        <small class="text-muted">Funcionalidad en desarrollo</small>
                    </div>
                </div>
                <p class="mb-0 text-center py-4">Pronto podrás ver todos los pedidos de esta fecha aquí.</p>
            </div>
        `;
    }, 1000);
}

// Función para cerrar el modal de pedidos del día
function cerrarModalDiaPersonalizado() {
    const modal = document.getElementById('modalDiaPersonalizado');
    modal.style.display = 'none';
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalPersonalizado();
        cerrarModalDiaPersonalizado();
    }
});
</script>
@endpush
@endsection