@extends('layouts.app')

@section('content')
<div id="compras-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Compras
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-bolt me-1 text-warning"></i>
                        Administra el registro y seguimiento de todas las compras del sistema
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route('compras.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-plus-circle me-2"></i> Nueva Compra
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

    <!-- Tarjetas de Estadísticas Mejoradas -->
    <div class="row g-4 mb-4">
        @php
            $comprasHoy = $comprasFiltradas->filter(function($compra) {
                return \Carbon\Carbon::parse($compra->Fecha_compra)->isToday();
            })->count();
            
            $totalInversion = $comprasFiltradas->sum('Total');
            $promedioCompra = $comprasFiltradas->count() > 0 ? $comprasFiltradas->avg('Total') : 0;
            
            $stats = [
                [
                    'titulo' => 'Compras Filtradas',
                    'valor' => $comprasFiltradas->count(),
                    'icono' => 'fas fa-shopping-cart',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => request()->anyFilled(['id', 'fecha', 'fecha_desde', 'fecha_hasta', 'proveedor_id', 'monto_min', 'monto_max', 'producto']) ? 'Con filtros aplicados' : 'Todas las compras'
                ],
                [
                    'titulo' => 'Compras Hoy',
                    'valor' => $comprasHoy,
                    'icono' => 'fas fa-bolt',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Actividad del día'
                ],
                [
                    'titulo' => 'Inversión Total',
                    'valor' => '$' . number_format($totalInversion, 2),
                    'icono' => 'fas fa-dollar-sign',
                    'color' => '#f59e0b',
                    'gradiente' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'descripcion' => 'Suma total'
                ],
                [
                    'titulo' => 'Promedio Compra',
                    'valor' => '$' . number_format($promedioCompra, 2),
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
                <p class="text-muted small mb-0">Encuentra compras específicas usando los siguientes filtros</p>
            </div>
        </div>

        <form id="filtrosForm" method="GET" action="{{ route('compras.index') }}">
            <div class="row g-3">
                <!-- ID de Compra -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-hashtag me-1" style="color: #667eea;"></i>
                        ID de Compra
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-id-badge text-primary"></i>
                        </span>
                        <input type="number" 
                               class="form-control border-0 bg-light" 
                               name="id" 
                               placeholder="Ej: 123" 
                               value="{{ request('id') }}"
                               min="1">
                        @if(request('id'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('id')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
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
                               class="form-control border-0 bg-light" 
                               name="fecha" 
                               value="{{ request('fecha') }}">
                        @if(request('fecha'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('fecha')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Rango de Fechas -->
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-calendar-range me-1" style="color: #667eea;"></i>
                        Rango de Fechas
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light">
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                </span>
                                <input type="date" 
                                       class="form-control border-0 bg-light" 
                                       name="fecha_desde" 
                                       value="{{ request('fecha_desde') }}"
                                       placeholder="Desde">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light">
                                    <i class="fas fa-calendar-minus text-primary"></i>
                                </span>
                                <input type="date" 
                                       class="form-control border-0 bg-light" 
                                       name="fecha_hasta" 
                                       value="{{ request('fecha_hasta') }}"
                                       placeholder="Hasta">
                                @if(request('fecha_desde') || request('fecha_hasta'))
                                <button type="button" 
                                        class="btn btn-outline-danger border-0" 
                                        onclick="clearFilters(['fecha_desde', 'fecha_hasta'])">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="col-md-4">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-truck me-1" style="color: #667eea;"></i>
                        Proveedor
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user-tie text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="proveedor_id">
                            <option value="">Todos los proveedores</option>
                            @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->Nombre ?? '' }} {{ $proveedor->ApPaterno ?? $proveedor->ApellidoPaterno ?? '' }}
                            </option>
                            @endforeach
                        </select>
                        @if(request('proveedor_id'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('proveedor_id')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
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
                                       class="form-control border-0 bg-light" 
                                       name="monto_min" 
                                       placeholder="Mínimo" 
                                       value="{{ request('monto_min') }}"
                                       step="0.01"
                                       min="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light">$</span>
                                <input type="number" 
                                       class="form-control border-0 bg-light" 
                                       name="monto_max" 
                                       placeholder="Máximo" 
                                       value="{{ request('monto_max') }}"
                                       step="0.01"
                                       min="0">
                                @if(request('monto_min') || request('monto_max'))
                                <button type="button" 
                                        class="btn btn-outline-danger border-0" 
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
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-box me-1" style="color: #667eea;"></i>
                        Producto
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-0 bg-light" 
                               name="producto" 
                               placeholder="Nombre del producto" 
                               value="{{ request('producto') }}">
                        @if(request('producto'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('producto')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Ordenamiento -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                        Ordenar por
                    </label>
                    <select class="form-select border-0 bg-light" name="sort_by">
                        <option value="Fecha_compra" {{ request('sort_by', 'Fecha_compra') == 'Fecha_compra' ? 'selected' : '' }}>Fecha</option>
                        <option value="Total" {{ request('sort_by') == 'Total' ? 'selected' : '' }}>Monto Total</option>
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID de Compra</option>
                    </select>
                </div>

                <!-- Dirección de orden -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                        Dirección
                    </label>
                    <select class="form-select border-0 bg-light" name="sort_order">
                        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descendente</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                        @php
                            $filtrosActivos = collect(request()->all())
                                ->filter(function($value, $key) {
                                    return in_array($key, ['id', 'fecha', 'fecha_desde', 'fecha_hasta', 'proveedor_id', 'monto_min', 'monto_max', 'producto']) 
                                           && !empty($value);
                                })
                                ->count();
                        @endphp
                        
                        @if($filtrosActivos > 0)
                        <span class="badge px-3 py-2" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            color: white;
                            border-radius: 50px;
                            font-size: 0.85rem;
                        ">
                            <i class="fas fa-filter me-1"></i>
                            {{ $filtrosActivos }} filtro(s) activo(s)
                        </span>
                        @endif
                        
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary px-4" style="
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                border: none;
                                border-radius: 12px 0 0 12px;
                            ">
                                <i class="fas fa-search me-1"></i> Aplicar
                            </button>
                            <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary px-4" style="
                                border-radius: 0 12px 12px 0;
                                border: 1px solid #e5e7eb;
                            ">
                                <i class="fas fa-redo me-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Indicadores de Filtros Activos -->
    @php
        $filtrosActivosLista = [];
        if(request('id')) $filtrosActivosLista[] = ['ID Compra', request('id'), 'id'];
        if(request('fecha')) $filtrosActivosLista[] = ['Fecha', \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y'), 'fecha'];
        if(request('fecha_desde')) $filtrosActivosLista[] = ['Desde', \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y'), 'fecha_desde'];
        if(request('fecha_hasta')) $filtrosActivosLista[] = ['Hasta', \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y'), 'fecha_hasta'];
        if(request('proveedor_id')) {
            $proveedor = $proveedores->firstWhere('id', request('proveedor_id'));
            $filtrosActivosLista[] = ['Proveedor', $proveedor ? ($proveedor->Nombre ?? '') . ' ' . ($proveedor->ApPaterno ?? $proveedor->ApellidoPaterno ?? '') : 'No encontrado', 'proveedor_id'];
        }
        if(request('monto_min')) $filtrosActivosLista[] = ['Monto Mín', '$' . number_format(request('monto_min'), 2), 'monto_min'];
        if(request('monto_max')) $filtrosActivosLista[] = ['Monto Máx', '$' . number_format(request('monto_max'), 2), 'monto_max'];
        if(request('producto')) $filtrosActivosLista[] = ['Producto', request('producto'), 'producto'];
    @endphp
    
    @if(count($filtrosActivosLista) > 0)
    <div class="active-filters mb-4" style="
        background: white;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    ">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <span class="text-muted small fw-semibold">
                <i class="fas fa-filter me-1 text-primary"></i>
                Filtros activos:
            </span>
            @foreach($filtrosActivosLista as $filtro)
            <span class="badge d-inline-flex align-items-center gap-2 px-3 py-2" style="
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                color: #4a5568;
                border: 1px solid rgba(102, 126, 234, 0.2);
                border-radius: 50px;
                font-size: 0.85rem;
            ">
                <i class="fas fa-check-circle text-primary"></i>
                {{ $filtro[0] }}: {{ $filtro[1] }}
                <button type="button" class="btn-close btn-close-sm ms-2" 
                        style="font-size: 0.6rem;"
                        onclick="clearFilter('{{ $filtro[2] }}')">
                </button>
            </span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabla de compras Mejorada -->
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
                    Historial de Compras
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    @if($comprasPaginated->total() > 0)
                    Mostrando {{ $comprasPaginated->firstItem() ?? 0 }}-{{ $comprasPaginated->lastItem() ?? 0 }} de {{ $comprasPaginated->total() }} compra(s)
                    @else
                    No hay compras que mostrar
                    @endif
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
                    Orden: {{ request('sort_by', 'Fecha_compra') == 'Fecha_compra' ? 'Fecha' : (request('sort_by') == 'Total' ? 'Monto' : 'ID') }}
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="50px"></th>
                        <th class="py-3">Compra</th>
                        <th class="py-3">Fecha y Hora</th>
                        <th class="py-3">Proveedor</th>
                        <th class="py-3">Productos</th>
                        <th class="py-3">Total</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comprasPaginated as $compra)
                    @php
                        // Obtener nombre completo del proveedor
                        $proveedorNombreCompleto = 'Sin proveedor';
                        if ($compra->proveedor) {
                            $nombre = $compra->proveedor->Nombre ?? '';
                            $apellidoPaterno = $compra->proveedor->ApPaterno ?? $compra->proveedor->ApellidoPaterno ?? '';
                            $apellidoMaterno = $compra->proveedor->ApMaterno ?? $compra->proveedor->ApellidoMaterno ?? '';
                            
                            $nombreCompleto = trim($nombre . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno);
                            $proveedorNombreCompleto = $nombreCompleto ?: $compra->proveedor->Nombre ?? 'Proveedor sin nombre';
                        }
                        
                        $totalProductos = $compra->detalleCompras->count();
                        $totalUnidades = $compra->detalleCompras->sum('Cantidad');
                    @endphp
                    <tr class="align-middle compra-row">
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detallesCompra{{ $compra->id }}"
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
                        
                        <!-- Compra -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="compra-avatar me-3" style="
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
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Compra #{{ str_pad($compra->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-boxes me-1"></i>{{ $totalUnidades }} unidad(es)
                                    </small>
                                </div>
                            </div>
                        </td>

                        <!-- Fecha y Hora -->
                        <td>
                            <div class="fw-medium">
                                {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1" style="color: #f59e0b;"></i>
                                {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('h:i A') }}
                            </small>
                        </td>

                        <!-- Proveedor -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="proveedor-avatar me-2" style="
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
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div>
                                    <span class="fw-medium">{{ Str::limit($proveedorNombreCompleto, 20) }}</span>
                                    @if($compra->proveedor)
                                        <br>
                                        <small class="text-muted">ID: {{ $compra->proveedor->id }}</small>
                                    @endif
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
                                <i class="fas fa-boxes me-1"></i>{{ $totalProductos }} productos
                            </span>
                            <div class="mt-1">
                                <small class="text-muted">{{ $totalUnidades }} unidades</small>
                            </div>
                        </td>

                        <!-- Total -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="total-avatar me-2" style="
                                    width: 32px;
                                    height: 32px;
                                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
                                    <h6 class="mb-0 fw-bold" style="color: #10b981;">${{ number_format($compra->Total, 2) }}</h6>
                                </div>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('compras.edit', $compra->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                   title="Editar compra">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                        onclick="setDeleteCompra({{ $compra->id }}, '{{ number_format($compra->Total, 2) }}')"
                                        title="Eliminar compra">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles de la compra -->
                    <tr class="detalle-compra-row">
                        <td colspan="7" class="p-0 border-0">
                            <div class="collapse" id="detallesCompra{{ $compra->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <div class="row g-4">
                                        <!-- Detalles de los productos -->
                                        <div class="col-md-8">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-boxes me-2 text-primary"></i>
                                                    Productos Comprados
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
                                                            @forelse($compra->detalleCompras as $detalle)
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
                                                                    ${{ number_format($detalle->Precio_unitario, 2) }}
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
                                                                        <p class="mb-0">No hay productos en esta compra</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Resumen de la compra -->
                                        <div class="col-md-4">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                                                    Resumen de Compra
                                                </h6>
                                                
                                                <div class="mb-3">
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Subtotal:</span>
                                                        <span class="fw-medium">${{ number_format($compra->Total, 2) }}</span>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Productos:</span>
                                                        <span class="fw-medium">{{ $totalProductos }}</span>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Unidades:</span>
                                                        <span class="fw-medium">{{ $totalUnidades }}</span>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Fecha:</span>
                                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y') }}</span>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between">
                                                        <span class="text-muted">Hora:</span>
                                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('h:i A') }}</span>
                                                    </div>
                                                </div>
                                                
                                                <hr style="margin: 1rem 0; border-color: #e5e7eb;">
                                                
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="fw-bold">Total:</span>
                                                    <h5 class="mb-0 fw-bold" style="color: #10b981;">
                                                        ${{ number_format($compra->Total, 2) }}
                                                    </h5>
                                                </div>
                                                
                                                <!-- Información del proveedor -->
                                                @if($compra->proveedor)
                                                <hr style="margin: 1rem 0; border-color: #e5e7eb;">
                                                <div class="mt-3">
                                                    <h6 class="fw-bold mb-2 text-muted small">Proveedor:</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2" style="
                                                            width: 36px;
                                                            height: 36px;
                                                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                            border-radius: 10px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: white;
                                                        ">
                                                            <i class="fas fa-truck"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $proveedorNombreCompleto }}</div>
                                                            <small class="text-muted">ID: {{ $compra->proveedor->id ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                <!-- Botones de acción -->
                                                <div class="mt-4 d-grid gap-2">
                                                    <a href="{{ route('compras.edit', $compra->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                        <i class="fas fa-edit me-1"></i> Editar compra
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm"
                                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                            onclick="setDeleteCompra({{ $compra->id }}, '{{ number_format($compra->Total, 2) }}')">
                                                        <i class="fas fa-trash me-1"></i> Eliminar compra
                                                    </button>
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
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state py-5">
                                <i class="fas fa-shopping-cart fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay compras registradas</h5>
                                <p class="text-muted mb-4">
                                    @if(count($filtrosActivosLista) > 0)
                                        No se encontraron compras con los filtros aplicados.
                                    @else
                                        Comienza registrando la primera compra en el sistema.
                                    @endif
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if(count($filtrosActivosLista) > 0)
                                    <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Limpiar Filtros
                                    </a>
                                    @endif
                                    <a href="{{ route('compras.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Registrar Compra
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($comprasPaginated->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center" style="border-top: 1px solid #e5e7eb;">
            <div class="text-muted small">
                Página {{ $comprasPaginated->currentPage() }} de {{ $comprasPaginated->lastPage() }}
            </div>
            <div>
                {{ $comprasPaginated->appends(request()->query())->links() }}
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
                        <i class="fas fa-trash-alt fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deleteCompraDisplay"></h5>
                <p class="text-muted mb-4" id="deleteCompraInfo" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Compra a eliminar:</span>
                            <span class="fw-bold" id="deleteCompraId"></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="text-muted">Valor total:</span>
                            <span class="fw-bold text-danger" id="deleteCompraTotal"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción es irreversible y eliminará permanentemente la compra del sistema.</p>
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
                    <button type="submit" class="btn btn-danger px-4" id="confirmDeleteBtn" style="border-radius: 50px;">
                        <i class="fas fa-trash me-2"></i>Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    initTooltips();
    
    // Configurar eventos de expansión
    setupExpandButtons();
    
    // Validaciones de formulario
    setupFormValidations();
    
    // Auto-submit en ordenamiento
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filtrosForm').submit();
        });
    });
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setupExpandButtons() {
    document.querySelectorAll('.btn-expand').forEach(button => {
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
            
            // Cerrar otros acordeones abiertos
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

function setupFormValidations() {
    const montoMin = document.querySelector('input[name="monto_min"]');
    const montoMax = document.querySelector('input[name="monto_max"]');
    const fechaDesde = document.querySelector('input[name="fecha_desde"]');
    const fechaHasta = document.querySelector('input[name="fecha_hasta"]');
    const form = document.getElementById('filtrosForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validar montos
            if (montoMin && montoMax && montoMin.value && montoMax.value) {
                if (parseFloat(montoMin.value) > parseFloat(montoMax.value)) {
                    e.preventDefault();
                    showNotification('El monto mínimo no puede ser mayor al monto máximo.', 'error');
                    montoMin.focus();
                    return false;
                }
            }
            
            // Validar fechas
            if (fechaDesde && fechaHasta && fechaDesde.value && fechaHasta.value) {
                if (new Date(fechaDesde.value) > new Date(fechaHasta.value)) {
                    e.preventDefault();
                    showNotification('La fecha "Desde" no puede ser mayor que la fecha "Hasta".', 'error');
                    fechaDesde.focus();
                    return false;
                }
            }
        });
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'toast-notification';
    notification.style.cssText = `
        position: fixed;
        top: 30px;
        right: 30px;
        min-width: 350px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 1rem 1.5rem;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        border-left: 4px solid ${type === 'success' ? '#10b981' : '#ef4444'};
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center gap-3">
            <div class="notification-icon">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" 
                   style="color: ${type === 'success' ? '#10b981' : '#ef4444'}; font-size: 1.5rem;"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">${type === 'success' ? '¡Operación exitosa!' : 'Error'}</h6>
                <p class="mb-0 small text-muted">${message}</p>
            </div>
            <button class="btn-close" onclick="this.closest('.toast-notification').remove()"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
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

function setDeleteCompra(compraId, total) {
    try {
        // Actualizar elementos del modal
        document.getElementById('deleteCompraDisplay').textContent = `¿Eliminar Compra #${compraId}?`;
        document.getElementById('deleteCompraInfo').innerHTML = `<small class="text-muted">Esta acción no se puede deshacer</small>`;
        document.getElementById('deleteCompraId').textContent = `#${String(compraId).padStart(5, '0')}`;
        document.getElementById('deleteCompraTotal').textContent = `$${total}`;
        
        // Actualizar acción del formulario
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.action = `/compras/${compraId}`;
        }
        
        // Mostrar modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al preparar la eliminación. Por favor, recarga la página.');
    }
}

// Agregar estilos de animación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .stat-card:hover .stat-decoration {
        transform: scale(1.2);
    }
    
    .btn-expand:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: transparent !important;
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
`;
document.head.appendChild(style);
</script>
@endpush

<style>
#compras-page {
    padding-top: 20px;
}

#compras-page .avatar {
    width: 48px;
    height: 48px;
}

#compras-page .avatar-md {
    width: 40px;
    height: 40px;
}

#compras-page .avatar-sm {
    width: 36px;
    height: 36px;
}

#compras-page .avatar-xs {
    width: 24px;
    height: 24px;
    font-size: 0.7rem;
}

#compras-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#compras-page .table tbody tr {
    transition: all 0.2s ease;
}

#compras-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

#compras-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#compras-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#compras-page .card {
    border-radius: 12px;
}

#compras-page .shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

#compras-page .fw-semibold {
    font-weight: 600;
}

#compras-page .detalle-compra-row {
    background-color: #f8fafc;
}

#compras-page .collapse {
    transition: all 0.3s ease;
}

#compras-page .collapsing {
    transition: height 0.35s ease;
}

#compras-page .form-control:focus,
#compras-page .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

@media (max-width: 768px) {
    #compras-page .btn-expand {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #compras-page .avatar, 
    #compras-page .avatar-md, 
    #compras-page .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    #compras-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #compras-page .detalle-compra-row .row {
        flex-direction: column;
    }
    
    #compras-page .detalle-compra-row .col-md-8,
    #compras-page .detalle-compra-row .col-md-4 {
        width: 100% !important;
        margin-bottom: 1rem;
    }
    
    #compras-page .btn-group .btn {
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

#compras-page .collapse.show {
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

/* Hover effects para botones de acción */
#compras-page .btn-outline-primary:hover,
#compras-page .btn-outline-danger:hover,
#compras-page .btn-outline-warning:hover,
#compras-page .btn-outline-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Tooltips personalizados */
[data-bs-toggle="tooltip"] {
    cursor: help;
}

/* Estilos para la paginación */
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
</style>
@endsection