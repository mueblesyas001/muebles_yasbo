@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">Gestión de Pedidos</h1>
            <p class="text-muted mb-0">Administra el registro y seguimiento de todos los pedidos del sistema</p>
        </div>
        <div>
            <a href="{{ route('pedidos.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Pedido
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas de pedidos (con filtros aplicados) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pedidos Filtrados</h6>
                            <h3 class="mb-0">{{ $pedidosFiltrados->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-clipboard-list text-primary fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        @if(request()->anyFilled(['id', 'fecha_desde', 'fecha_hasta', 'cliente_id', 'empleado_id', 'estado', 'monto_min', 'monto_max', 'producto']))
                        Con filtros aplicados
                        @else
                        Todos los pedidos
                        @endif
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pedidos Hoy</h6>
                            <h3 class="mb-0">
                                {{ $pedidosFiltrados->filter(function($pedido) {
                                    return \Carbon\Carbon::parse($pedido->Fecha_entrega)->isToday();
                                })->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-bolt text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Ingreso Total</h6>
                            <h3 class="mb-0 text-primary">${{ number_format($pedidosFiltrados->sum('Total'), 2) }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-dollar-sign text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Promedio Pedido</h6>
                            <h3 class="mb-0">
                                ${{ $pedidosFiltrados->count() > 0 ? number_format($pedidosFiltrados->avg('Total'), 2) : '0.00' }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros y Búsqueda -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-filter me-2 text-primary"></i>
                Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form id="filtrosForm" method="GET" action="{{ route('pedidos.index') }}">
                <div class="row g-3">
                    <!-- ID de Pedido -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-hashtag me-1"></i> ID de Pedido
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-id-badge"></i>
                            </span>
                            <input type="number" 
                                   class="form-control" 
                                   name="id" 
                                   placeholder="Ej: 123" 
                                   value="{{ request('id') }}"
                                   aria-label="ID de Pedido"
                                   min="1">
                            @if(request('id'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('id')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="col-md-6">
                        <label class="form-label small text-muted">
                            <i class="fas fa-calendar-range me-1"></i> Rango de Fechas Entrega
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control" 
                                           name="fecha_desde" 
                                           value="{{ request('fecha_desde') }}"
                                           placeholder="Desde"
                                           aria-label="Fecha desde">
                                </div>
                                
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-minus"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control" 
                                           name="fecha_hasta" 
                                           value="{{ request('fecha_hasta') }}"
                                           placeholder="Hasta"
                                           aria-label="Fecha hasta">
                                    @if(request('fecha_desde') || request('fecha_hasta'))
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="clearFilters(['fecha_desde', 'fecha_hasta'])">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Fecha Específica -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-calendar me-1"></i> Fecha Específica
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" 
                                   class="form-control" 
                                   name="fecha" 
                                   value="{{ request('fecha') }}"
                                   aria-label="Fecha específica">
                            @if(request('fecha'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('fecha')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-user me-1"></i> Cliente
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-users"></i>
                            </span>
                            <select class="form-select" name="cliente_id" aria-label="Cliente">
                                <option value="">Todos los clientes</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->Nombre ?? '' }} {{ $cliente->ApPaterno ?? '' }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('cliente_id'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('cliente_id')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Empleado -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-user-tie me-1"></i> Empleado
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user-check"></i>
                            </span>
                            <select class="form-select" name="empleado_id" aria-label="Empleado">
                                <option value="">Todos los empleados</option>
                                @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->Nombre ?? '' }} {{ $empleado->ApPaterno ?? '' }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('empleado_id'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('empleado_id')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-tasks me-1"></i> Estado
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-flag"></i>
                            </span>
                            <select class="form-select" name="estado" aria-label="Estado">
                                <option value="">Todos los estados</option>
                                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="En proceso" {{ request('estado') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                <option value="Completado" {{ request('estado') == 'Completado' ? 'selected' : '' }}>Completado</option>
                                <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @if(request('estado'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('estado')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Rango de Monto -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-dollar-sign me-1"></i> Monto Total
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="monto_min" 
                                           placeholder="Mínimo" 
                                           value="{{ request('monto_min') }}"
                                           step="0.01"
                                           min="0"
                                           aria-label="Monto mínimo">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="monto_max" 
                                           placeholder="Máximo" 
                                           value="{{ request('monto_max') }}"
                                           step="0.01"
                                           min="0"
                                           aria-label="Monto máximo">
                                    @if(request('monto_min') || request('monto_max'))
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="clearFilters(['monto_min', 'monto_max'])">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Producto -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-box me-1"></i> Producto
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="producto" 
                                   placeholder="Nombre del producto" 
                                   value="{{ request('producto') }}"
                                   aria-label="Producto">
                            @if(request('producto'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('producto')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Ordenamiento -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort me-1"></i> Ordenar por
                        </label>
                        <select class="form-select" name="sort_by" aria-label="Ordenar por">
                            <option value="Fecha_entrega" {{ request('sort_by', 'Fecha_entrega') == 'Fecha_entrega' ? 'selected' : '' }}>Fecha</option>
                            <option value="Total" {{ request('sort_by') == 'Total' ? 'selected' : '' }}>Monto</option>
                            <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                            <option value="Estado" {{ request('sort_by') == 'Estado' ? 'selected' : '' }}>Estado</option>
                        </select>
                    </div>

                    <!-- Dirección de orden -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort-amount-down me-1"></i> Dirección
                        </label>
                        <select class="form-select" name="sort_order" aria-label="Dirección orden">
                            <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descendente</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                            <div class="text-muted small">
                                @php
                                    $filtrosActivos = collect(request()->all())
                                        ->filter(function($value, $key) {
                                            return in_array($key, ['id', 'fecha', 'fecha_desde', 'fecha_hasta', 'cliente_id', 'empleado_id', 'estado', 'monto_min', 'monto_max', 'producto']) 
                                                   && !empty($value);
                                        })
                                        ->count();
                                @endphp
                                @if($filtrosActivos > 0)
                                <span class="badge bg-info text-white">
                                    <i class="fas fa-filter me-1"></i>
                                    {{ $filtrosActivos }} filtro(s) activo(s)
                                </span>
                                @endif
                            </div>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-search me-1"></i> Aplicar Filtros
                                </button>
                                <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-1"></i> Limpiar Todo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Indicadores de Filtros Activos -->
    @php
        $filtrosActivos = [];
        if(request('id')) $filtrosActivos[] = ['ID Pedido', request('id')];
        if(request('fecha')) $filtrosActivos[] = ['Fecha', \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y')];
        if(request('fecha_desde')) $filtrosActivos[] = ['Desde', \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y')];
        if(request('fecha_hasta')) $filtrosActivos[] = ['Hasta', \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y')];
        if(request('cliente_id')) {
            $cliente = $clientes->firstWhere('id', request('cliente_id'));
            $filtrosActivos[] = ['Cliente', $cliente ? ($cliente->Nombre ?? '') . ' ' . ($cliente->ApPaterno ?? '') : 'No encontrado'];
        }
        if(request('empleado_id')) {
            $empleado = $empleados->firstWhere('id', request('empleado_id'));
            $filtrosActivos[] = ['Empleado', $empleado ? ($empleado->Nombre ?? '') . ' ' . ($empleado->ApPaterno ?? '') : 'No encontrado'];
        }
        if(request('estado')) $filtrosActivos[] = ['Estado', request('estado')];
        if(request('monto_min')) $filtrosActivos[] = ['Monto Mín', '$' . number_format(request('monto_min'), 2)];
        if(request('monto_max')) $filtrosActivos[] = ['Monto Máx', '$' . number_format(request('monto_max'), 2)];
        if(request('producto')) $filtrosActivos[] = ['Producto', request('producto')];
    @endphp
    
    @if(count($filtrosActivos) > 0)
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>Filtros aplicados:</strong>
                <div class="mt-1 d-flex flex-wrap gap-2">
                    @foreach($filtrosActivos as $filtro)
                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 d-flex align-items-center">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $filtro[0] }}: {{ $filtro[1] }}
                        <button type="button" 
                                class="btn-close btn-close-sm ms-2" 
                                onclick="clearFilter('{{ 
                                    $filtro[0] == 'ID Pedido' ? 'id' : 
                                    ($filtro[0] == 'Fecha' ? 'fecha' : 
                                    ($filtro[0] == 'Desde' ? 'fecha_desde' : 
                                    ($filtro[0] == 'Hasta' ? 'fecha_hasta' : 
                                    ($filtro[0] == 'Cliente' ? 'cliente_id' : 
                                    ($filtro[0] == 'Empleado' ? 'empleado_id' : 
                                    ($filtro[0] == 'Estado' ? 'estado' : 
                                    ($filtro[0] == 'Monto Mín' ? 'monto_min' : 
                                    ($filtro[0] == 'Monto Máx' ? 'monto_max' : 'producto')))))))) 
                                }}')">
                        </button>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de pedidos (con paginación) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Historial de Pedidos
                    @if(count($filtrosActivos) > 0)
                    <span class="badge bg-primary ms-2">Filtradas</span>
                    @endif
                </h5>
                <small class="text-muted">
                    @if($pedidosPaginated->total() > 0)
                    Mostrando {{ $pedidosPaginated->firstItem() ?? 0 }}-{{ $pedidosPaginated->lastItem() ?? 0 }} de {{ $pedidosPaginated->total() }} pedido(s)
                    @else
                    No hay pedidos que mostrar
                    @endif
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">
                    Ordenado por: 
                    <span class="badge bg-light text-dark">
                        {{ 
                            request('sort_by', 'Fecha_entrega') == 'Fecha_entrega' ? 'Fecha' : 
                            (request('sort_by') == 'Total' ? 'Monto' : 
                            (request('sort_by') == 'Estado' ? 'Estado' : 'ID'))
                        }} 
                        <i class="fas fa-arrow-{{ request('sort_order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3" width="50px"></th>
                            <th class="py-3">Pedido</th>
                            <th class="py-3">Fecha y Hora Entrega</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Productos</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3">Total</th>
                            <th class="text-end py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosPaginated as $pedido)
                        @php
                            $cantidadProductos = $pedido->detallePedidos->count();
                            $totalUnidades = $pedido->detallePedidos->sum('Cantidad');
                            
                            // Obtener nombres completos
                            $clienteNombre = $pedido->cliente->Nombre ?? 'Sin cliente';
                            $clienteApellido = $pedido->cliente->ApPaterno ?? '';
                            $clienteCompleto = trim("$clienteNombre $clienteApellido");
                            
                            $empleadoNombre = $pedido->empleado->Nombre ?? 'Sin empleado';
                            $empleadoApellido = $pedido->empleado->ApPaterno ?? '';
                            $empleadoCompleto = trim("$empleadoNombre $empleadoApellido");
                            
                            // Clases para estado
                            $estadoClass = [
                                'Pendiente' => 'warning',
                                'En proceso' => 'info',
                                'Completado' => 'success',
                                'Cancelado' => 'danger'
                            ][$pedido->Estado] ?? 'secondary';
                            
                            // Determinar si el pedido es editable
                            $esEditable = !in_array($pedido->Estado, ['Cancelado', 'Completado']);
                            
                            // Determinar si el pedido se puede eliminar
                            $esEliminable = !in_array($pedido->Estado, ['Cancelado', 'Completado']);
                        @endphp
                        <tr class="align-middle">
                            <!-- Botón para expandir -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary btn-expand" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesPedido{{ $pedido->id }}" 
                                        aria-expanded="false"
                                        aria-controls="detallesPedido{{ $pedido->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-clipboard-list text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Pedido #{{ $pedido->id }}</h6>
                                        <small class="text-muted">
                                            {{ $totalUnidades }} unidad(es)
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">
                                    {{ \Carbon\Carbon::parse($pedido->Fecha_entrega)->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $pedido->Hora_entrega }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $clienteCompleto }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                        <i class="fas fa-boxes me-1"></i>{{ $cantidadProductos }} productos
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ $totalUnidades }} unidad(es)
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $estadoClass }} bg-opacity-10 text-{{ $estadoClass }} border border-{{ $estadoClass }} border-opacity-25 px-3 py-2 rounded-pill">
                                    {{ $pedido->Estado }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-dollar-sign text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-success">${{ number_format($pedido->Total, 2) }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($esEditable)
                                        <a href="{{ route('pedidos.edit', $pedido->id) }}" 
                                           class="btn btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar pedido">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <!-- Botón deshabilitado con tooltip explicativo -->
                                        <button type="button" 
                                                class="btn btn-outline-secondary" 
                                                disabled
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top"
                                                data-bs-html="true"
                                                title="<div class='text-start'>
                                                    <strong>Pedido {{ $pedido->Estado }}</strong><br>
                                                    <small>No se puede modificar un pedido {{ strtolower($pedido->Estado) }}.</small>
                                                </div>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    
                                    @if($esEliminable)
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                onclick="setDeletePedido({{ $pedido->id }}, '{{ number_format($pedido->Total, 2) }}', '{{ $pedido->Estado }}')"
                                                title="Eliminar pedido">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <!-- Botón deshabilitado con tooltip explicativo -->
                                        <button type="button" 
                                                class="btn btn-outline-secondary" 
                                                disabled
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top"
                                                data-bs-html="true"
                                                title="<div class='text-start'>
                                                    <strong>Pedido {{ $pedido->Estado }}</strong><br>
                                                    <small>No se puede eliminar un pedido {{ strtolower($pedido->Estado) }}.</small>
                                                </div>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles del pedido -->
                        <tr class="detalle-pedido-row">
                            <td colspan="8" class="p-0 border-0">
                                <div class="collapse" id="detallesPedido{{ $pedido->id }}">
                                    <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                        <!-- Añadir advertencia si el pedido no es editable -->
                                        @if(!$esEditable)
                                        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="alert-heading mb-1">Pedido {{ $pedido->Estado }}</h6>
                                                    <p class="mb-0">
                                                        @if($pedido->Estado === 'Cancelado')
                                                        Este pedido ha sido cancelado. No se pueden realizar modificaciones.
                                                        @elseif($pedido->Estado === 'Completado')
                                                        Este pedido ha sido completado. Solo puede mantenerse como Completado o cambiarse a Cancelado.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="row">
                                            <!-- Detalles de los productos -->
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-3 text-primary">
                                                    <i class="fas fa-boxes me-2"></i>Productos del Pedido
                                                </h6>
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless">
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
                                                            <tr class="border-bottom border-light">
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-xs bg-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                            <i class="fas fa-box text-primary fa-xs"></i>
                                                                        </div>
                                                                        <div>
                                                                            <div class="fw-medium">{{ $detalle->producto->Nombre ?? 'Producto no disponible' }}</div>
                                                                            <small class="text-muted">Código: {{ $detalle->Producto ?? 'N/A' }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center fw-semibold">
                                                                    ${{ number_format($detalle->PrecioUnitario, 2) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                                                        {{ $detalle->Cantidad }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end fw-bold text-success">
                                                                    ${{ number_format($detalle->Subtotal, 2) }}
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-3">
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
                                            
                                            <!-- Resumen del pedido -->
                                            <div class="col-md-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="fas fa-chart-pie me-2"></i>Resumen del Pedido
                                                        </h6>
                                                        
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Subtotal:</span>
                                                                <span class="fw-medium">${{ number_format($pedido->Total, 2) }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Productos:</span>
                                                                <span class="fw-medium">{{ $cantidadProductos }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Unidades:</span>
                                                                <span class="fw-medium">{{ $totalUnidades }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Fecha Entrega:</span>
                                                                <span class="fw-medium">{{ \Carbon\Carbon::parse($pedido->Fecha_entrega)->format('d/m/Y') }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Hora Entrega:</span>
                                                                <span class="fw-medium">{{ $pedido->Hora_entrega }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Lugar Entrega:</span>
                                                                <span class="fw-medium">{{ $pedido->Lugar_entrega }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Estado:</span>
                                                                <span class="badge bg-{{ $estadoClass }} bg-opacity-10 text-{{ $estadoClass }}">
                                                                    {{ $pedido->Estado }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="fw-bold">Total:</span>
                                                            <h5 class="mb-0 fw-bold text-success">
                                                                ${{ number_format($pedido->Total, 2) }}
                                                            </h5>
                                                        </div>
                                                        
                                                        <!-- Información del cliente -->
                                                        <hr>
                                                        <div class="mt-3">
                                                            <h6 class="fw-bold mb-2 text-muted small">Cliente:</h6>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <i class="fas fa-user text-info fa-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $clienteCompleto }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Información del empleado -->
                                                        <div class="mt-3">
                                                            <h6 class="fw-bold mb-2 text-muted small">Empleado:</h6>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <i class="fas fa-user-tie text-secondary fa-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $empleadoCompleto }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Botones de acción -->
                                                        <div class="mt-4">
                                                            @if($esEditable)
                                                                <a href="{{ route('pedidos.edit', $pedido->id) }}" 
                                                                   class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                                                    <i class="fas fa-edit me-1"></i> Editar pedido
                                                                </a>
                                                            @else
                                                                <button type="button" 
                                                                        class="btn btn-outline-secondary btn-sm w-100 mb-2"
                                                                        disabled
                                                                        data-bs-toggle="tooltip" 
                                                                        data-bs-placement="top"
                                                                        data-bs-html="true"
                                                                        title="<div class='text-start'>
                                                                            <strong>Pedido {{ $pedido->Estado }}</strong><br>
                                                                            <small>No se puede modificar un pedido {{ strtolower($pedido->Estado) }}.</small>
                                                                        </div>">
                                                                    <i class="fas fa-edit me-1"></i> Editar pedido
                                                                </button>
                                                            @endif
                                                            
                                                            @if($esEliminable)
                                                                <button type="button" 
                                                                        class="btn btn-outline-danger btn-sm w-100"
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#deleteModal" 
                                                                        onclick="setDeletePedido({{ $pedido->id }}, '{{ number_format($pedido->Total, 2) }}', '{{ $pedido->Estado }}')">
                                                                    <i class="fas fa-trash me-1"></i> Eliminar pedido
                                                                </button>
                                                            @else
                                                                <button type="button" 
                                                                        class="btn btn-outline-secondary btn-sm w-100"
                                                                        disabled
                                                                        data-bs-toggle="tooltip" 
                                                                        data-bs-placement="top"
                                                                        data-bs-html="true"
                                                                        title="<div class='text-start'>
                                                                            <strong>Pedido {{ $pedido->Estado }}</strong><br>
                                                                            <small>No se puede eliminar un pedido {{ strtolower($pedido->Estado) }}.</small>
                                                                        </div>">
                                                                    <i class="fas fa-trash me-1"></i> Eliminar pedido
                                                                </button>
                                                            @endif
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
                            <td colspan="8" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                                    <h4 class="text-muted fw-bold mb-3">No hay pedidos registrados</h4>
                                    <p class="text-muted mb-4">
                                        @if(count($filtrosActivos) > 0)
                                        No se encontraron pedidos con los filtros aplicados.
                                        @else
                                        Comienza registrando el primer pedido en el sistema.
                                        @endif
                                    </p>
                                    <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-redo me-1"></i> Limpiar Filtros
                                    </a>
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
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    @if($pedidosPaginated->total() > 0)
                    Mostrando {{ $pedidosPaginated->firstItem() ?? 0 }}-{{ $pedidosPaginated->lastItem() ?? 0 }} de {{ $pedidosPaginated->total() }} pedido(s)
                    @else
                    No hay pedidos que mostrar
                    @endif
                </div>
                @if($pedidosPaginated->hasPages())
                <div>
                    {{ $pedidosPaginated->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white position-relative">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                </div>
                <p class="fs-6">
                    Estás a punto de eliminar el pedido <strong id="deletePedidoId"></strong>
                </p>
                <p class="text-danger small mb-0">
                    Valor total: <strong id="deletePedidoTotal"></strong><br>
                    <strong>Esta acción no se puede deshacer.</strong>
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Rotar la flecha del botón expandir al hacer clic
    document.querySelectorAll('.btn-expand').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Rotar el icono
            if (icon) {
                if (isExpanded) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(180deg)';
                }
                icon.style.transition = 'transform 0.3s ease';
            }
            
            // Cambiar clase del botón
            if (isExpanded) {
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-secondary');
            } else {
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
            }
        });
    });

    // Cerrar detalles cuando se hace clic en otro botón
    document.querySelectorAll('.btn-expand').forEach(button => {
        button.addEventListener('click', function(e) {
            const targetId = this.getAttribute('data-bs-target');
            
            // Cerrar otros acordeones abiertos
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                if (collapse.id !== targetId.replace('#', '')) {
                    const collapseButton = document.querySelector(`[data-bs-target="#${collapse.id}"]`);
                    if (collapseButton) {
                        collapseButton.click();
                    }
                }
            });
        });
    });

    // Validación de montos
    const montoMin = document.querySelector('input[name="monto_min"]');
    const montoMax = document.querySelector('input[name="monto_max"]');
    
    if (montoMin && montoMax) {
        const form = document.getElementById('filtrosForm');
        form.addEventListener('submit', function(e) {
            if (montoMin.value && montoMax.value && parseFloat(montoMin.value) > parseFloat(montoMax.value)) {
                e.preventDefault();
                alert('El monto mínimo no puede ser mayor al monto máximo.');
                montoMin.focus();
                return false;
            }
        });
    }

    // Validación de rango de fechas
    const fechaDesde = document.querySelector('input[name="fecha_desde"]');
    const fechaHasta = document.querySelector('input[name="fecha_hasta"]');
    
    if (fechaDesde && fechaHasta) {
        const form = document.getElementById('filtrosForm');
        form.addEventListener('submit', function(e) {
            if (fechaDesde.value && fechaHasta.value && new Date(fechaDesde.value) > new Date(fechaHasta.value)) {
                e.preventDefault();
                alert('La fecha "Desde" no puede ser mayor que la fecha "Hasta".');
                fechaDesde.focus();
                return false;
            }
        });
    }

    // Auto-submit en ordenamiento
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filtrosForm').submit();
        });
    });
});

// Función para limpiar filtro individual
function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Función para limpiar múltiples filtros
function clearFilters(filterNames) {
    const url = new URL(window.location.href);
    filterNames.forEach(filter => {
        url.searchParams.delete(filter);
    });
    window.location.href = url.toString();
}

// Función para el modal de eliminación
function setDeletePedido(pedidoId, total, estado) {
    // Verificar si el pedido se puede eliminar
    if (estado === 'Cancelado' || estado === 'Completado') {
        // Mostrar SweetAlert explicando por qué no se puede eliminar
        Swal.fire({
            icon: 'warning',
            title: 'Pedido ' + estado,
            html: `
                <div class="text-start">
                    <p>No se puede eliminar un pedido <strong>${estado}</strong>.</p>
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Los pedidos ${estado.toLowerCase()} solo pueden mantenerse en ese estado.
                        </small>
                    </div>
                </div>
            `,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3498db'
        });
        return;
    }
    
    document.getElementById('deletePedidoId').textContent = '#' + pedidoId;
    document.getElementById('deletePedidoTotal').textContent = '$' + total;
    
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = "{{ route('pedidos.destroy', ':id') }}".replace(':id', pedidoId);
    
    // Mostrar el modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush

<style>
.avatar {
    width: 48px;
    height: 48px;
}

.avatar-md {
    width: 40px;
    height: 40px;
}

.avatar-sm {
    width: 36px;
    height: 36px;
}

.avatar-xs {
    width: 24px;
    height: 24px;
    font-size: 0.7rem;
}

.table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

.badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

.card {
    border-radius: 12px;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.fw-semibold {
    font-weight: 600;
}

/* Estilos para el botón expandir */
.btn-expand {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 6px !important;
}

.btn-expand:hover {
    transform: scale(1.1);
}

.btn-expand i {
    transition: transform 0.3s ease;
}

/* Estilos para la fila expandible */
.detalle-pedido-row {
    background-color: #f8fafc;
}

.collapse {
    transition: all 0.3s ease;
}

.collapsing {
    transition: height 0.35s ease;
}

/* Estilos para la tabla de detalles */
.table.table-borderless tbody tr:last-child {
    border-bottom: none !important;
}

.bg-light.bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

/* Animación suave para expandir */
.collapse.show {
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

/* Estilos para campos de filtro */
.input-group .btn-outline-danger {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

/* Estilos para búsqueda */
.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Indicador de búsqueda activa */
.badge.bg-info {
    background-color: #0dcaf0 !important;
}

/* Mejorar la apariencia de los filtros activos */
.alert-info .badge {
    background-color: rgba(13, 202, 240, 0.2) !important;
    border: 1px solid #0dcaf0;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-expand {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .avatar, .avatar-md, .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .avatar-xs {
        width: 20px;
        height: 20px;
        font-size: 0.6rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .detalle-pedido-row .row {
        flex-direction: column;
    }
    
    .detalle-pedido-row .col-md-8,
    .detalle-pedido-row .col-md-4 {
        width: 100% !important;
        margin-bottom: 1rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Filtros responsive */
    .card-body .row > div {
        margin-bottom: 1rem;
    }
}

/* Mejorar la legibilidad de los detalles */
.detalle-pedido-row .card-body {
    padding: 1.5rem;
}

.detalle-pedido-row h6 {
    font-size: 0.95rem;
}

.detalle-pedido-row .table-sm th,
.detalle-pedido-row .table-sm td {
    padding: 0.5rem;
    font-size: 0.85rem;
}

/* Ajustes específicos para pedidos */
.text-primary {
    color: #3498db !important;
}

.text-success {
    color: #2ecc71 !important;
}

.bg-primary.bg-opacity-10 {
    background-color: rgba(52, 152, 219, 0.1) !important;
}

.bg-success.bg-opacity-10 {
    background-color: rgba(46, 204, 113, 0.1) !important;
}

.bg-info.bg-opacity-10 {
    background-color: rgba(101, 150, 157, 0.1) !important;
}

/* Indicadores de filtros */
.alert-info .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* Hover en filas de detalles */
.detalle-pedido-row .table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.7);
}

/* Estilos para botones deshabilitados con tooltip */
.btn-outline-secondary:disabled {
    cursor: not-allowed;
    opacity: 0.6;
    background-color: #f8f9fa;
}

/* Tooltip personalizado */
.tooltip-inner {
    max-width: 300px;
    text-align: left;
    padding: 0.75rem 1rem;
}

.tooltip-inner h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #dc3545;
}

.tooltip-inner small {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Alertas en los detalles del pedido */
.alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-color: rgba(255, 193, 7, 0.3);
    color: #664d03;
}

.alert-warning .alert-heading {
    color: #664d03;
    font-weight: 600;
}

.alert-warning i {
    color: #ffc107;
}

/* Animación para alertas */
.alert {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mejorar la visibilidad de los estados */
.estado-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}
</style>
@endsection