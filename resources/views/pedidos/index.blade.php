@extends('layouts.app')

@section('content')
<div id="pedidos-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
    <!-- Header con Glassmorphism -->
    <div class="glass-header py-4 px-4 mb-4" style="
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        margin-top: 20px;
    ">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-4">
                <div class="header-icon" style="
                    width: 70px;
                    height: 70px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 18px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-clipboard-list fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Pedidos
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-bolt me-1 text-warning"></i>
                        Administra el registro y seguimiento de todos los pedidos del sistema
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" id="refreshData" class="btn btn-outline-primary" style="
                    border-radius: 14px;
                    padding: 12px 20px;
                    border: 1px solid #e5e7eb;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <a href="{{ route('pedidos.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-plus-circle me-2"></i> Nuevo Pedido
                </a>
            </div>
        </div>
    </div>

    <!-- Alertas Mejoradas -->
    @if(session('success'))
        <div class="alert alert-modern alert-success d-flex align-items-center mb-4" role="alert" style="
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        ">
            <div class="alert-icon me-3">
                <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1" style="color: #155724;">¡Operación Exitosa!</h6>
                <p class="mb-0" style="color: #155724;">{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-modern alert-danger d-flex align-items-center mb-4" role="alert" style="
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        ">
            <div class="alert-icon me-3">
                <i class="fas fa-exclamation-circle fa-2x" style="color: #dc3545;"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1" style="color: #721c24;">¡Error!</h6>
                <p class="mb-0" style="color: #721c24;">{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('foreign_key_error'))
        <div class="alert alert-modern alert-warning d-flex align-items-center mb-4" role="alert" style="
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
        ">
            <div class="alert-icon me-3">
                <i class="fas fa-exclamation-triangle fa-2x" style="color: #856404;"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1" style="color: #856404;">Pedido Protegido</h6>
                <p class="mb-0" style="color: #856404;">{{ is_array(session('foreign_key_error')) ? session('foreign_key_error')['mensaje'] : session('foreign_key_error') }}</p>
                @if(session('venta_asociada'))
                <div class="mt-2 small">
                    <strong style="color: #856404;">Detalles:</strong>
                    <ul class="mb-0 mt-1">
                        <li style="color: #856404;">Venta asociada: #{{ session('venta_id') }}</li>
                    </ul>
                </div>
                @endif
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tarjetas de Estadísticas Mejoradas -->
    <div class="row g-4 mb-4">
        @php
            $pedidosHoy = $pedidosFiltrados->filter(function($pedido) {
                return \Carbon\Carbon::parse($pedido->Fecha_entrega)->isToday();
            })->count();
            
            $totalIngresos = $pedidosFiltrados->sum('Total');
            $promedioPedido = $pedidosFiltrados->count() > 0 ? $pedidosFiltrados->avg('Total') : 0;
            
            $stats = [
                [
                    'titulo' => 'Total Pedidos',
                    'valor' => $pedidosFiltrados->count(),
                    'icono' => 'fas fa-clipboard-list',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => request()->anyFilled(['id', 'fecha_desde', 'fecha_hasta', 'cliente_id', 'empleado_id', 'estado', 'monto_min', 'monto_max', 'producto']) ? 'Con filtros aplicados' : 'Todos los pedidos'
                ],
                [
                    'titulo' => 'Pedidos Hoy',
                    'valor' => $pedidosHoy,
                    'icono' => 'fas fa-bolt',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Actividad del día'
                ],
                [
                    'titulo' => 'Ingreso Total',
                    'valor' => '$' . number_format($totalIngresos, 2),
                    'icono' => 'fas fa-dollar-sign',
                    'color' => '#f59e0b',
                    'gradiente' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'descripcion' => 'Suma total'
                ],
                [
                    'titulo' => 'Promedio Pedido',
                    'valor' => '$' . number_format($promedioPedido, 2),
                    'icono' => 'fas fa-chart-line',
                    'color' => '#3b82f6',
                    'gradiente' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                    'descripcion' => 'Por transacción'
                ]
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-6 col-lg-3">
            <div class="stat-card h-100" style="
                background: white;
                border-radius: 24px;
                padding: 1.5rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                border: 1px solid rgba(0,0,0,0.03);
                transition: all 0.3s ease;
                cursor: default;
                position: relative;
                overflow: hidden;
            ">
                <div class="stat-decoration" style="
                    position: absolute;
                    top: -50%;
                    right: -50%;
                    width: 200px;
                    height: 200px;
                    background: {{ $stat['gradiente'] }};
                    opacity: 0.05;
                    border-radius: 50%;
                    transition: all 0.5s ease;
                "></div>
                
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge" style="
                            background: {{ $stat['gradiente'] }};
                            color: white;
                            padding: 0.5rem 1rem;
                            border-radius: 50px;
                            font-size: 0.75rem;
                            font-weight: 600;
                            letter-spacing: 0.5px;
                        ">
                            {{ $stat['descripcion'] }}
                        </span>
                    </div>
                    <div class="stat-icon" style="
                        width: 50px;
                        height: 50px;
                        background: {{ $stat['gradiente'] }};
                        border-radius: 16px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 1.5rem;
                        box-shadow: 0 8px 20px {{ $stat['color'] }}40;
                    ">
                        <i class="{{ $stat['icono'] }}"></i>
                    </div>
                </div>
                
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #1f2937;">{{ $stat['valor'] }}</h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ $stat['titulo'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Panel de Filtros Mejorado -->
    <div class="filters-panel mb-4" style="
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.03);
    ">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="filters-icon" style="
                width: 45px;
                height: 45px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            ">
                <i class="fas fa-filter"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1" style="color: #1f2937;">Filtros de Búsqueda</h5>
                <p class="text-muted small mb-0">Encuentra pedidos específicos usando los siguientes filtros</p>
            </div>
        </div>

        <div class="row g-3">
            <!-- ID de Pedido -->
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-hashtag me-1" style="color: #667eea;"></i>
                    ID de Pedido
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-id-badge text-primary"></i>
                    </span>
                    <input type="number" 
                           id="filterId"
                           class="form-control border-0 bg-light" 
                           placeholder="Ej: 123" 
                           min="1">
                </div>
            </div>

            <!-- Rango de Fechas -->
            <div class="col-md-6">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-calendar-range me-1" style="color: #667eea;"></i>
                    Rango de Fechas Entrega
                </label>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light">
                                <i class="fas fa-calendar-plus text-primary"></i>
                            </span>
                            <input type="date" 
                                   id="filterFechaDesde"
                                   class="form-control border-0 bg-light" 
                                   placeholder="Desde">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light">
                                <i class="fas fa-calendar-minus text-primary"></i>
                            </span>
                            <input type="date" 
                                   id="filterFechaHasta"
                                   class="form-control border-0 bg-light" 
                                   placeholder="Hasta">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fecha Específica -->
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-calendar me-1" style="color: #667eea;"></i>
                    Fecha Específica
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-calendar-alt text-primary"></i>
                    </span>
                    <input type="date" 
                           id="filterFecha"
                           class="form-control border-0 bg-light">
                </div>
            </div>

            <!-- Cliente -->
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-user me-1" style="color: #667eea;"></i>
                    Cliente
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-users text-primary"></i>
                    </span>
                    <select id="filterCliente" class="form-select border-0 bg-light">
                        <option value="">Todos los clientes</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">
                            {{ $cliente->Nombre ?? '' }} {{ $cliente->ApPaterno ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Empleado -->
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-user-tie me-1" style="color: #667eea;"></i>
                    Empleado
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-user-check text-primary"></i>
                    </span>
                    <select id="filterEmpleado" class="form-select border-0 bg-light">
                        <option value="">Todos los empleados</option>
                        @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">
                            {{ $empleado->Nombre ?? '' }} {{ $empleado->ApPaterno ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Estado -->
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-tasks me-1" style="color: #667eea;"></i>
                    Estado
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-flag text-primary"></i>
                    </span>
                    <select id="filterEstado" class="form-select border-0 bg-light">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Completado">Completado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
            </div>

            <!-- Rango de Monto -->
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-dollar-sign me-1" style="color: #667eea;"></i>
                    Monto Total
                </label>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light">$</span>
                            <input type="number" 
                                   id="filterMontoMin"
                                   class="form-control border-0 bg-light" 
                                   placeholder="Mínimo" 
                                   step="0.01"
                                   min="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light">$</span>
                            <input type="number" 
                                   id="filterMontoMax"
                                   class="form-control border-0 bg-light" 
                                   placeholder="Máximo" 
                                   step="0.01"
                                   min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Producto -->
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-box me-1" style="color: #667eea;"></i>
                    Producto
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="text" 
                           id="filterProducto"
                           class="form-control border-0 bg-light" 
                           placeholder="Nombre del producto">
                </div>
            </div>

            <!-- Ordenamiento -->
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                    Ordenar por
                </label>
                <select id="sortBy" class="form-select border-0 bg-light">
                    <option value="Fecha_entrega">Fecha</option>
                    <option value="Total">Monto</option>
                    <option value="id">ID</option>
                    <option value="Estado">Estado</option>
                </select>
            </div>

            <!-- Dirección de orden -->
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                    Dirección
                </label>
                <select id="sortOrder" class="form-select border-0 bg-light">
                    <option value="desc">Descendente</option>
                    <option value="asc">Ascendente</option>
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="col-md-4">
                <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                    <div class="text-muted small">
                        <span id="filterCount">0</span> filtro(s) activo(s)
                    </div>
                    <div class="btn-group">
                        <button type="button" id="applyFilters" class="btn btn-primary px-4" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border: none;
                            border-radius: 12px 0 0 12px;
                        ">
                            <i class="fas fa-filter me-1"></i> Aplicar
                        </button>
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary px-4" style="
                            border-radius: 0 12px 12px 0;
                            border: 1px solid #e5e7eb;
                        ">
                            <i class="fas fa-redo me-1"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de pedidos Mejorada -->
    <div class="table-container" style="
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
    ">
        <div class="table-header p-4 d-flex justify-content-between align-items-center" style="
            border-bottom: 1px solid #e5e7eb;
            background: white;
        ">
            <div>
                <h5 class="fw-bold mb-1" style="color: #1f2937;">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Historial de Pedidos
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    <span id="totalCount">{{ $pedidosPaginated->total() }}</span> pedido(s) registrado(s)
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge px-3 py-2" style="
                    background: #f3f4f6;
                    color: #4b5563;
                    border-radius: 50px;
                    font-size: 0.85rem;
                ">
                    <i class="fas fa-arrow-{{ request('sort_order', 'desc') == 'asc' ? 'up' : 'down' }} me-1"></i>
                    Orden: <span id="sortDisplay">Fecha</span>
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="pedidosTable">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="50px"></th>
                        <th class="py-3">Pedido</th>
                        <th class="py-3">Fecha y Hora Entrega</th>
                        <th class="py-3">Cliente</th>
                        <th class="py-3">Productos</th>
                        <th class="py-3">Comentario</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3">Total</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidosPaginated as $pedido)
                    @php
                        $cantidadProductos = $pedido->detallePedidos->count();
                        $totalUnidades = $pedido->detallePedidos->sum('Cantidad');
                        
                        $clienteNombre = $pedido->cliente->Nombre ?? 'Sin cliente';
                        $clienteApellido = $pedido->cliente->ApPaterno ?? '';
                        $clienteCompleto = trim("$clienteNombre $clienteApellido");
                        
                        $empleadoNombre = $pedido->empleado->Nombre ?? 'Sin empleado';
                        $empleadoApellido = $pedido->empleado->ApPaterno ?? '';
                        $empleadoCompleto = trim("$empleadoNombre $empleadoApellido");
                        
                        $estadoClass = [
                            'Pendiente' => 'warning',
                            'En proceso' => 'info',
                            'Completado' => 'success',
                            'Cancelado' => 'danger'
                        ][$pedido->Estado] ?? 'secondary';
                        
                        $estadoGradiente = [
                            'Pendiente' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                            'En proceso' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                            'Completado' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                            'Cancelado' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'
                        ][$pedido->Estado] ?? 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
                        
                        $esEditable = $pedido->Estado !== 'Cancelado';
                        $puedeCompletar = in_array($pedido->Estado, ['Pendiente', 'En proceso']);
                        $tieneVentaAsociada = $pedido->venta && $pedido->venta->count() > 0;
                    @endphp
                    <tr class="align-middle pedido-row {{ $tieneVentaAsociada ? 'pedido-con-venta' : '' }}" 
                        data-id="{{ $pedido->id }}"
                        data-fecha="{{ $pedido->Fecha_entrega }}"
                        data-cliente-id="{{ $pedido->Cliente_idCliente }}"
                        data-empleado-id="{{ $pedido->Empleado_idEmpleado }}"
                        data-estado="{{ $pedido->Estado }}"
                        data-monto="{{ $pedido->Total }}"
                        data-producto="{{ strtolower($pedido->detallePedidos->pluck('producto.Nombre')->implode(' ')) }}">
                        
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand-pedido" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detallesPedido{{ $pedido->id }}"
                                    style="
                                        width: 32px;
                                        height: 32px;
                                        border-radius: 8px;
                                        border: 1px solid #e5e7eb;
                                        color: #6b7280;
                                        transition: all 0.3s ease;
                                    ">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </td>
                        
                        <!-- Pedido -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="pedido-avatar me-3" style="
                                    width: 48px;
                                    height: 48px;
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    border-radius: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-weight: 600;
                                    font-size: 1.2rem;
                                    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
                                ">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Pedido #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-boxes me-1"></i>{{ $totalUnidades }} unidad(es)
                                        @if($tieneVentaAsociada)
                                        <span class="badge ms-1 px-2 py-1" style="
                                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                            color: white;
                                            border-radius: 50px;
                                            font-size: 0.65rem;
                                        ">
                                            <i class="fas fa-receipt me-1"></i>Venta
                                        </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </td>

                        <!-- Fecha y Hora -->
                        <td>
                            <div class="fw-medium">
                                {{ \Carbon\Carbon::parse($pedido->Fecha_entrega)->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1" style="color: #f59e0b;"></i>
                                {{ $pedido->Hora_entrega }}
                            </small>
                        </td>

                        <!-- Cliente -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="cliente-avatar me-2" style="
                                    width: 32px;
                                    height: 32px;
                                    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-size: 0.9rem;
                                ">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <span class="fw-medium">{{ Str::limit($clienteCompleto, 20) }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Productos -->
                        <td>
                            <span class="badge px-3 py-2" style="
                                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                                color: white;
                                border-radius: 50px;
                                font-size: 0.75rem;
                            ">
                                <i class="fas fa-boxes me-1"></i>{{ $cantidadProductos }} productos
                            </span>
                            <div class="mt-1">
                                <small class="text-muted">{{ $totalUnidades }} unidades</small>
                            </div>
                        </td>

                        <!-- Comentario -->
                        <td>
                            @if($pedido->comentario)
                                <span class="badge px-2 py-1" style="
                                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.7rem;
                                    cursor: help;
                                " data-bs-toggle="tooltip" 
                                   data-bs-placement="top"
                                   data-bs-html="true"
                                   title="<div class='text-start'><strong>Comentario:</strong><br>{{ $pedido->comentario }}</div>">
                                    <i class="fas fa-comment-dots me-1"></i>
                                    {{ Str::limit($pedido->comentario, 15) }}
                                </span>
                            @else
                                <span class="text-muted small">
                                    <i class="fas fa-minus me-1"></i>
                                    Sin comentarios
                                </span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td>
                            <span class="badge px-3 py-2" style="
                                background: {{ $estadoGradiente }};
                                color: white;
                                border-radius: 50px;
                                font-size: 0.75rem;
                            ">
                                <i class="fas fa-{{ $pedido->Estado == 'Pendiente' ? 'clock' : ($pedido->Estado == 'En proceso' ? 'cog' : ($pedido->Estado == 'Completado' ? 'check-circle' : 'times-circle')) }} me-1"></i>
                                {{ $pedido->Estado }}
                            </span>
                        </td>

                        <!-- Total -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="total-avatar me-2" style="
                                    width: 32px;
                                    height: 32px;
                                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-size: 0.9rem;
                                ">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color: #10b981;">${{ number_format($pedido->Total, 2) }}</h6>
                                </div>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <!-- Botón para completar -->
                                @if($puedeCompletar)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="completarPedido({{ $pedido->id }})"
                                            title="Completar pedido">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5;"
                                            disabled
                                            title="No se puede completar un pedido {{ strtolower($pedido->Estado) }}">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @endif
                                
                                <!-- Botón para editar -->
                                @if($esEditable)
                                    <a href="{{ route('pedidos.edit', $pedido->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                       title="Editar pedido">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5;"
                                            disabled
                                            title="No se puede modificar un pedido cancelado">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                                
                                <!-- Botón para eliminar -->
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                        onclick="verificarYEliminar({{ $pedido->id }}, {{ $pedido->Total }}, '{{ $pedido->Estado }}', {{ $tieneVentaAsociada ? 'true' : 'false' }})"
                                        title="Eliminar pedido">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles del pedido -->
                    <tr class="detalle-pedido-row">
                        <td colspan="9" class="p-0 border-0">
                            <div class="collapse" id="detallesPedido{{ $pedido->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <!-- Advertencias -->
                                    @if($tieneVentaAsociada)
                                    <div class="alert alert-success mb-4" style="
                                        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                                        border: none;
                                        border-radius: 16px;
                                        padding: 1rem;
                                    ">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-receipt fa-2x" style="color: #155724;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1" style="color: #155724;">✅ Pedido con Venta Asociada</h6>
                                                <p class="mb-0" style="color: #155724;">
                                                    Este pedido tiene una venta asociada en el sistema. Si lo eliminas, también se eliminará la venta correspondiente.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(!$esEditable)
                                    <div class="alert alert-warning mb-4" style="
                                        background: linear-gradient(135deg, #fef3c7 0%, #ffe69c 100%);
                                        border: none;
                                        border-radius: 16px;
                                        padding: 1rem;
                                    ">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-exclamation-triangle fa-2x" style="color: #856404;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1" style="color: #856404;">⛔ Pedido Cancelado</h6>
                                                <p class="mb-0" style="color: #856404;">
                                                    Este pedido ha sido cancelado. Puedes eliminarlo pero no modificarlo.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row g-4">
                                        <!-- Detalles de los productos -->
                                        <div class="col-md-7">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-boxes me-2 text-primary"></i>
                                                    Productos del Pedido
                                                </h6>
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr class="text-muted small">
                                                                <th>Producto</th>
                                                                <th class="text-center">Precio Unitario</th>
                                                                <th class="text-center">Cantidad</th>
                                                                <th class="text-end">Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($pedido->detallePedidos as $detalle)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-xs me-2" style="
                                                                            width: 24px;
                                                                            height: 24px;
                                                                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                                                            border-radius: 6px;
                                                                            display: flex;
                                                                            align-items: center;
                                                                            justify-content: center;
                                                                            color: white;
                                                                            font-size: 0.7rem;
                                                                        ">
                                                                            <i class="fas fa-box"></i>
                                                                        </div>
                                                                        <div>
                                                                            <span class="fw-medium">{{ $detalle->producto->Nombre ?? 'Producto no disponible' }}</span>
                                                                            <br>
                                                                            <small class="text-muted">Código: {{ $detalle->Producto ?? 'N/A' }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center fw-semibold">
                                                                    ${{ number_format($detalle->PrecioUnitario, 2) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge px-3 py-1" style="
                                                                        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                                                                        color: white;
                                                                        border-radius: 50px;
                                                                    ">
                                                                        {{ $detalle->Cantidad }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end fw-bold" style="color: #10b981;">
                                                                    ${{ number_format($detalle->Subtotal, 2) }}
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-4">
                                                                    <div class="text-muted">
                                                                        <i class="fas fa-box-open fa-2x mb-2"></i>
                                                                        <p class="mb-0">No hay productos en este pedido</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Columna de comentario completo -->
                                        <div class="col-md-5">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-comment-dots me-2 text-primary"></i>
                                                    Comentarios del Pedido
                                                </h6>
                                                
                                                @if($pedido->comentario)
                                                    <div class="comentario-box p-3 mb-3" style="
                                                        background: #f8fafc;
                                                        border-radius: 12px;
                                                        max-height: 200px;
                                                        overflow-y: auto;
                                                        border-left: 3px solid #667eea;
                                                    ">
                                                        <p class="mb-0" style="white-space: pre-line;">{{ $pedido->comentario }}</p>
                                                    </div>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Este comentario fue registrado al crear/modificar el pedido
                                                    </small>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted mb-0">No hay comentarios para este pedido</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Resumen del pedido -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Subtotal:</span>
                                                            <span class="fw-medium">${{ number_format($pedido->Total, 2) }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Productos:</span>
                                                            <span class="fw-medium">{{ $cantidadProductos }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Unidades:</span>
                                                            <span class="fw-medium">{{ $totalUnidades }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Fecha Entrega:</span>
                                                            <span class="fw-medium">{{ \Carbon\Carbon::parse($pedido->Fecha_entrega)->format('d/m/Y') }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Hora Entrega:</span>
                                                            <span class="fw-medium">{{ $pedido->Hora_entrega }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Lugar Entrega:</span>
                                                            <span class="fw-medium">{{ $pedido->Lugar_entrega }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Cliente:</span>
                                                            <span class="fw-medium">{{ Str::limit($clienteCompleto, 20) }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Empleado:</span>
                                                            <span class="fw-medium">{{ Str::limit($empleadoCompleto, 20) }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Estado:</span>
                                                            <span class="badge px-3 py-1" style="background: {{ $estadoGradiente }}; color: white;">
                                                                {{ $pedido->Estado }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="fw-bold">Total:</span>
                                                            <h5 class="mb-0 fw-bold" style="color: #10b981;">
                                                                ${{ number_format($pedido->Total, 2) }}
                                                            </h5>
                                                        </div>
                                                        <div class="mt-3 d-grid gap-2">
                                                            @if($puedeCompletar)
                                                                <button type="button" 
                                                                        class="btn btn-outline-success btn-sm" 
                                                                        style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                        onclick="completarPedido({{ $pedido->id }})">
                                                                    <i class="fas fa-check-circle me-1"></i> Completar pedido
                                                                </button>
                                                            @endif
                                                            
                                                            @if($esEditable)
                                                                <a href="{{ route('pedidos.edit', $pedido->id) }}" 
                                                                   class="btn btn-outline-primary btn-sm" 
                                                                   style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                                    <i class="fas fa-edit me-1"></i> Editar pedido
                                                                </a>
                                                            @endif
                                                            
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                    onclick="verificarYEliminar({{ $pedido->id }}, {{ $pedido->Total }}, '{{ $pedido->Estado }}', {{ $tieneVentaAsociada ? 'true' : 'false' }})">
                                                                <i class="fas fa-trash me-1"></i> Eliminar pedido
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state py-5">
                                <i class="fas fa-clipboard-list fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay pedidos registrados</h5>
                                <p class="text-muted mb-4">
                                    Comienza registrando el primer pedido en el sistema.
                                </p>
                                <a href="{{ route('pedidos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i> Registrar Pedido
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($pedidosPaginated->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center" style="border-top: 1px solid #e5e7eb;">
            <div class="text-muted small">
                Página {{ $pedidosPaginated->currentPage() }} de {{ $pedidosPaginated->lastPage() }}
            </div>
            <div>
                {{ $pedidosPaginated->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- MODAL DE ELIMINACIÓN MEJORADO -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-danger text-white" style="
                background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper mb-4">
                    <div class="delete-icon-circle" style="
                        width: 80px;
                        height: 80px;
                        background: rgba(220, 53, 69, 0.1);
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deletePedidoNombreDisplay"></h5>
                <p class="text-muted mb-4" id="deletePedidoId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" id="deleteInfoCard" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Total:</span>
                            <span class="fw-bold text-success" id="deletePedidoTotal"></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="text-muted">Estado:</span>
                            <span class="badge" id="deletePedidoEstado"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Advertencia adicional -->
                <div class="alert alert-warning border-0 d-flex align-items-center" id="deleteWarning" style="display: none; border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle fs-4 me-3 text-warning"></i>
                    <div class="text-start">
                        <strong class="text-warning">¡Importante!</strong>
                        <p class="mb-0 text-muted small" id="deleteWarningText"></p>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center mt-3" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción es irreversible y eliminará permanentemente el pedido del sistema.</p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4" style="border-radius: 50px;">
                        <i class="fas fa-trash me-2"></i>Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ADVERTENCIA PARA PEDIDOS CON VENTA -->
<div class="modal fade" id="ventaWarningModal" tabindex="-1" aria-labelledby="ventaWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-warning text-white" style="
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold" id="ventaWarningModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                    Pedido con Venta Asociada
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body text-center p-4">
                <div class="warning-icon-wrapper mb-4">
                    <div class="warning-icon-circle" style="
                        width: 90px;
                        height: 90px;
                        background: rgba(255, 193, 7, 0.1);
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        <i class="fas fa-receipt fa-4x text-warning"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="ventaWarningTitle"></h5>
                <p class="text-muted mb-4" id="ventaWarningText"></p>
                
                <div class="alert alert-warning text-start" style="border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>¿Qué pasará?</strong>
                    <ul class="mt-2 mb-0">
                        <li>Se eliminará el pedido seleccionado</li>
                        <li>Se eliminará la venta asociada automáticamente</li>
                        <li>El stock de los productos se restaurará</li>
                    </ul>
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-warning px-4" id="confirmVentaDelete" style="border-radius: 50px;">
                    <i class="fas fa-trash me-2"></i>Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Carga -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Procesando...</span>
                </div>
                <h6 class="fw-bold mb-2">Procesando solicitud</h6>
                <p class="text-muted small mb-0">Por favor espera un momento...</p>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    setupExpandButtons();
    setupFilters();
    setupRefreshButton();
    setupModalCleanup();
    
    @if(session('foreign_key_error'))
        @php
            $errorData = is_array(session('foreign_key_error')) 
                ? session('foreign_key_error') 
                : ['mensaje' => session('foreign_key_error'), 'venta_id' => session('venta_id') ?? 0];
        @endphp
        setTimeout(function() {
            Swal.fire({
                icon: 'error',
                title: 'No se puede eliminar',
                text: '{{ $errorData["mensaje"] }}',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            });
        }, 100);
    @endif
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setupExpandButtons() {
    document.querySelectorAll('.btn-expand-pedido').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            if (icon) {
                icon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
                icon.style.transition = 'transform 0.3s ease';
            }
            
            if (isExpanded) {
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-secondary');
            } else {
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
            }
            
            // Cerrar otros acordeones
            if (!isExpanded) {
                const targetId = this.getAttribute('data-bs-target');
                document.querySelectorAll('.collapse.show').forEach(collapse => {
                    if (collapse.id !== targetId.replace('#', '')) {
                        const collapseButton = document.querySelector(`[data-bs-target="#${collapse.id}"]`);
                        if (collapseButton) {
                            collapseButton.click();
                        }
                    }
                });
            }
        });
    });
}

function setupFilters() {
    const filterId = document.getElementById('filterId');
    const filterFechaDesde = document.getElementById('filterFechaDesde');
    const filterFechaHasta = document.getElementById('filterFechaHasta');
    const filterFecha = document.getElementById('filterFecha');
    const filterCliente = document.getElementById('filterCliente');
    const filterEmpleado = document.getElementById('filterEmpleado');
    const filterEstado = document.getElementById('filterEstado');
    const filterMontoMin = document.getElementById('filterMontoMin');
    const filterMontoMax = document.getElementById('filterMontoMax');
    const filterProducto = document.getElementById('filterProducto');
    const sortBy = document.getElementById('sortBy');
    const sortOrder = document.getElementById('sortOrder');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    const pedidosRows = document.querySelectorAll('.pedido-row');
    const visibleCount = document.getElementById('visibleCount');
    const filterCount = document.getElementById('filterCount');
    const sortDisplay = document.getElementById('sortDisplay');
    const totalPedidos = document.getElementById('totalPedidos');
    const pedidosHoy = document.getElementById('pedidosHoy');
    const ingresoTotal = document.getElementById('ingresoTotal');
    const promedioPedido = document.getElementById('promedioPedido');

    function updateFilterCount() {
        let count = 0;
        if (filterId.value) count++;
        if (filterFechaDesde.value) count++;
        if (filterFechaHasta.value) count++;
        if (filterFecha.value) count++;
        if (filterCliente.value) count++;
        if (filterEmpleado.value) count++;
        if (filterEstado.value) count++;
        if (filterMontoMin.value) count++;
        if (filterMontoMax.value) count++;
        if (filterProducto.value) count++;
        filterCount.textContent = count;
    }

    function applyTableFilters() {
        const idValue = filterId.value;
        const fechaDesdeValue = filterFechaDesde.value;
        const fechaHastaValue = filterFechaHasta.value;
        const fechaValue = filterFecha.value;
        const clienteValue = filterCliente.value;
        const empleadoValue = filterEmpleado.value;
        const estadoValue = filterEstado.value;
        const montoMinValue = filterMontoMin.value ? parseFloat(filterMontoMin.value) : null;
        const montoMaxValue = filterMontoMax.value ? parseFloat(filterMontoMax.value) : null;
        const productoValue = filterProducto.value.toLowerCase();
        
        let visibleRows = 0;
        let totalIngreso = 0;
        let hoy = new Date().toISOString().split('T')[0];
        let pedidosHoyCount = 0;

        pedidosRows.forEach(row => {
            const rowId = row.dataset.id;
            const rowFecha = row.dataset.fecha;
            const rowClienteId = row.dataset.clienteId;
            const rowEmpleadoId = row.dataset.empleadoId;
            const rowEstado = row.dataset.estado;
            const rowMonto = parseFloat(row.dataset.monto);
            const rowProducto = row.dataset.producto || '';

            const matchesId = !idValue || rowId === idValue;
            const matchesFecha = !fechaValue || rowFecha === fechaValue;
            const matchesFechaDesde = !fechaDesdeValue || rowFecha >= fechaDesdeValue;
            const matchesFechaHasta = !fechaHastaValue || rowFecha <= fechaHastaValue;
            const matchesCliente = !clienteValue || rowClienteId === clienteValue;
            const matchesEmpleado = !empleadoValue || rowEmpleadoId === empleadoValue;
            const matchesEstado = !estadoValue || rowEstado === estadoValue;
            const matchesMontoMin = !montoMinValue || rowMonto >= montoMinValue;
            const matchesMontoMax = !montoMaxValue || rowMonto <= montoMaxValue;
            const matchesProducto = !productoValue || rowProducto.includes(productoValue);

            if (matchesId && matchesFecha && matchesFechaDesde && matchesFechaHasta && 
                matchesCliente && matchesEmpleado && matchesEstado && 
                matchesMontoMin && matchesMontoMax && matchesProducto) {
                row.style.display = '';
                visibleRows++;
                totalIngreso += rowMonto;
                
                if (rowFecha === hoy) {
                    pedidosHoyCount++;
                }
            } else {
                row.style.display = 'none';
            }
        });

        visibleCount.textContent = visibleRows;
        totalPedidos.textContent = visibleRows;
        pedidosHoy.textContent = pedidosHoyCount;
        ingresoTotal.textContent = '$' + totalIngreso.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        promedioPedido.textContent = visibleRows > 0 
            ? '$' + (totalIngreso / visibleRows).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')
            : '$0.00';
        
        updateFilterCount();
        updateSortDisplay();
    }

    function updateSortDisplay() {
        const sortText = sortBy.options[sortBy.selectedIndex].text;
        const orderIcon = sortOrder.value === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down';
        sortDisplay.innerHTML = `${sortText} <i class="fas ${orderIcon} ms-1"></i>`;
    }

    function sortTable() {
        const sortColumn = sortBy.value;
        const order = sortOrder.value;

        const rowsArray = Array.from(pedidosRows);
        
        rowsArray.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortColumn) {
                case 'Fecha_entrega':
                    aValue = a.dataset.fecha;
                    bValue = b.dataset.fecha;
                    break;
                case 'Total':
                    aValue = parseFloat(a.dataset.monto);
                    bValue = parseFloat(b.dataset.monto);
                    break;
                case 'Estado':
                    aValue = a.dataset.estado;
                    bValue = b.dataset.estado;
                    break;
                default: // ID
                    aValue = parseInt(a.dataset.id);
                    bValue = parseInt(b.dataset.id);
            }

            if (order === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        const tbody = document.querySelector('#pedidosTable tbody');
        rowsArray.forEach(row => {
            tbody.appendChild(row);
            const detallesRow = row.nextElementSibling;
            if (detallesRow && detallesRow.classList.contains('detalle-pedido-row')) {
                tbody.appendChild(detallesRow);
            }
        });
    }

    applyFilters.addEventListener('click', function() {
        applyTableFilters();
        sortTable();
    });

    resetFilters.addEventListener('click', function() {
        filterId.value = '';
        filterFechaDesde.value = '';
        filterFechaHasta.value = '';
        filterFecha.value = '';
        filterCliente.value = '';
        filterEmpleado.value = '';
        filterEstado.value = '';
        filterMontoMin.value = '';
        filterMontoMax.value = '';
        filterProducto.value = '';
        sortBy.value = 'Fecha_entrega';
        sortOrder.value = 'desc';
        applyTableFilters();
        sortTable();
    });

    sortBy.addEventListener('change', sortTable);
    sortOrder.addEventListener('change', sortTable);
    
    [filterId, filterFecha, filterCliente, filterEmpleado, filterEstado, filterProducto].forEach(input => {
        if (input) input.addEventListener('change', applyTableFilters);
    });
    
    [filterFechaDesde, filterFechaHasta].forEach(input => {
        if (input) input.addEventListener('change', function() {
            if (filterFecha.value) filterFecha.value = '';
            applyTableFilters();
        });
    });
    
    [filterMontoMin, filterMontoMax].forEach(input => {
        if (input) input.addEventListener('input', applyTableFilters);
    });

    applyTableFilters();
    updateSortDisplay();
}

function setupRefreshButton() {
    const refreshBtn = document.getElementById('refreshData');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.classList.add('spin');
            setTimeout(() => location.reload(), 500);
        });
    }
}

function setupModalCleanup() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', forceCleanupModals);
    }
    
    const warningModal = document.getElementById('ventaWarningModal');
    if (warningModal) {
        warningModal.addEventListener('hidden.bs.modal', forceCleanupModals);
    }
    
    const loadingModal = document.getElementById('loadingModal');
    if (loadingModal) {
        loadingModal.addEventListener('hidden.bs.modal', forceCleanupModals);
    }
}

function forceCleanupModals() {
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.documentElement.style.overflow = '';
}

let loadingModalInstance = null;

function showLoadingModal() {
    const modalElement = document.getElementById('loadingModal');
    if (!modalElement) return;
    
    if (loadingModalInstance) {
        loadingModalInstance.hide();
        loadingModalInstance.dispose();
    }
    
    forceCleanupModals();
    
    loadingModalInstance = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: false
    });
    
    setTimeout(() => {
        if (loadingModalInstance) {
            loadingModalInstance.show();
        }
    }, 10);
}

function hideLoadingModal() {
    return new Promise((resolve) => {
        if (loadingModalInstance) {
            const modalElement = document.getElementById('loadingModal');
            const handler = function() {
                modalElement.removeEventListener('hidden.bs.modal', handler);
                loadingModalInstance = null;
                forceCleanupModals();
                resolve();
            };
            
            modalElement.addEventListener('hidden.bs.modal', handler);
            loadingModalInstance.hide();
        } else {
            forceCleanupModals();
            resolve();
        }
    });
}

function verificarYEliminar(pedidoId, total, estado, tieneVenta) {
    forceCleanupModals();
    
    document.getElementById('deletePedidoId').innerHTML = `<small class="text-muted">ID: #${String(pedidoId).padStart(5, '0')}</small>`;
    document.getElementById('deletePedidoNombreDisplay').textContent = `¿Eliminar Pedido #${pedidoId}?`;
    document.getElementById('deletePedidoTotal').textContent = `$${total}`;
    
    const estadoBadge = document.getElementById('deletePedidoEstado');
    let estadoClass = '';
    let estadoGradiente = '';
    
    switch(estado) {
        case 'Pendiente':
            estadoClass = 'warning';
            estadoGradiente = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
            break;
        case 'En proceso':
            estadoClass = 'info';
            estadoGradiente = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
            break;
        case 'Completado':
            estadoClass = 'success';
            estadoGradiente = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            break;
        case 'Cancelado':
            estadoClass = 'danger';
            estadoGradiente = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
            break;
        default:
            estadoClass = 'secondary';
            estadoGradiente = 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
    }
    
    estadoBadge.style.background = estadoGradiente;
    estadoBadge.style.color = 'white';
    estadoBadge.textContent = estado;
    
    const deleteWarning = document.getElementById('deleteWarning');
    const deleteWarningText = document.getElementById('deleteWarningText');
    
    if (estado === 'Completado') {
        deleteWarning.style.display = 'flex';
        if (tieneVenta) {
            deleteWarningText.innerHTML = 'Este pedido está completado y tiene una venta asociada. Al eliminarlo, también se eliminará la venta y se restaurará el stock.';
        } else {
            deleteWarningText.innerHTML = 'Este pedido está completado. Al eliminarlo, se restaurará el stock de los productos.';
        }
    } else if (estado === 'Cancelado') {
        deleteWarning.style.display = 'flex';
        deleteWarningText.innerHTML = 'Este pedido está cancelado. Puedes eliminarlo sin afectar el stock.';
    } else {
        deleteWarning.style.display = 'none';
    }
    
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/pedidos/${pedidoId}`;
    
    setTimeout(() => {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }, 50);
}

function completarPedido(pedidoId) {
    Swal.fire({
        title: '¿Completar Pedido?',
        html: `
            <div class="text-start">
                <p>¿Está seguro de marcar el pedido <strong>#${pedidoId}</strong> como completado?</p>
                <div class="alert alert-success mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Al completar el pedido:</strong>
                    <ul class="mt-2 mb-0">
                        <li>Se descontará el stock de los productos</li>
                        <li>Se generará automáticamente una venta</li>
                        <li>El pedido pasará a estado "Completado"</li>
                    </ul>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Sí, completar',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingModal();
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pedidos/${pedidoId}/cambiar-estado`;
            
            const token = document.querySelector('meta[name="csrf-token"]')?.content || 
                          document.querySelector('input[name="_token"]')?.value;
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = token;
            
            const estadoInput = document.createElement('input');
            estadoInput.type = 'hidden';
            estadoInput.name = 'Estado';
            estadoInput.value = 'Completado';
            
            form.appendChild(tokenInput);
            form.appendChild(estadoInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

function clearFilters(filterNames) {
    const url = new URL(window.location.href);
    filterNames.forEach(filter => {
        url.searchParams.delete(filter);
    });
    window.location.href = url.toString();
}

const spinStyle = document.createElement('style');
spinStyle.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spin {
        animation: spin 0.5s linear infinite;
    }
    
    [title] {
        cursor: help;
    }
    
    .stat-card:hover .stat-decoration {
        transform: scale(1.2);
    }
    
    .btn-expand-pedido:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: transparent !important;
    }
    
    .pedido-con-venta {
        background-color: rgba(40, 167, 69, 0.02);
    }
    
    .pedido-con-venta:hover {
        background-color: rgba(40, 167, 69, 0.08) !important;
    }
    
    .detail-item {
        padding: 0.5rem 0;
        border-bottom: 1px dashed #e5e7eb;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .empty-state {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes pulseIcon {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .delete-icon-circle {
        animation: pulseIcon 2s infinite;
    }
    
    #loadingModal .modal-content {
        border-radius: 20px !important;
    }
    
    #loadingModal .spinner-border {
        width: 3.5rem;
        height: 3.5rem;
    }
`;
document.head.appendChild(spinStyle);
</script>
@endpush

<style>
#pedidos-page {
    padding-top: 20px;
}

#pedidos-page .pedido-avatar {
    width: 48px;
    height: 48px;
}

#pedidos-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#pedidos-page .table tbody tr {
    transition: all 0.2s ease;
}

#pedidos-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

#pedidos-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#pedidos-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#pedidos-page .card {
    border-radius: 12px;
}

#pedidos-page .fw-semibold {
    font-weight: 600;
}

#pedidos-page .detalle-pedido-row {
    background-color: #f8fafc;
}

#pedidos-page .collapse {
    transition: all 0.3s ease;
}

#pedidos-page .collapsing {
    transition: height 0.35s ease;
}

#pedidos-page .form-control:focus,
#pedidos-page .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

@media (max-width: 768px) {
    #pedidos-page .btn-expand-pedido {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #pedidos-page .pedido-avatar {
        width: 32px;
        height: 32px;
    }
    
    #pedidos-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #pedidos-page .detalle-pedido-row .row {
        flex-direction: column;
    }
    
    #pedidos-page .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .modal-footer .btn {
        width: 100%;
        margin: 0.25rem 0 !important;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}

#pedidos-page .collapse.show {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hover effects para botones */
#pedidos-page .btn-outline-primary:hover,
#pedidos-page .btn-outline-danger:hover,
#pedidos-page .btn-outline-success:hover,
#pedidos-page .btn-outline-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Paginación */
.pagination {
    gap: 5px;
}

.page-link {
    border-radius: 10px !important;
    border: 1px solid #e5e7eb !important;
    color: #4b5563 !important;
    padding: 0.5rem 1rem !important;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border-color: transparent !important;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border: none !important;
}

/* Tooltips personalizados */
.tooltip-inner {
    max-width: 300px;
    text-align: left;
    padding: 0.75rem 1rem;
}

/* Scrollbar para comentarios */
.comentario-box::-webkit-scrollbar {
    width: 6px;
}

.comentario-box::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.comentario-box::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.comentario-box::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection