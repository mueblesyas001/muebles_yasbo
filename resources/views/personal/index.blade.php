@extends('layouts.app')

@section('content')
<div class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Personal
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-bolt me-1 text-warning"></i>
                        Administra empleados y usuarios del sistema
                    </p>
                </div>
            </div>
            <a href="{{ route('personal.create') }}" class="btn btn-primary btn-lg" style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 14px;
                padding: 12px 28px;
                font-weight: 600;
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                transition: all 0.3s ease;
            ">
                <i class="fas fa-user-plus me-2"></i>
                Nuevo Empleado
            </a>
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

    <!-- ALERTA ESPECÍFICA PARA ERROR DE FOREIGN KEY (PEDIDOS) -->
    @if(session('foreign_key_error'))
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
        ">
            <div class="alert-icon me-3">
                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1" style="color: #856404;">⛔ Empleado con pedidos</h6>
                <p class="mb-0" style="color: #856404;">
                    {{ session('foreign_key_error') }}
                    @if(session('empleado_nombre') && session('pedidos_count'))
                        <br>
                        <strong>{{ session('empleado_nombre') }}</strong> tiene 
                        <strong class="text-danger">{{ session('pedidos_count') }} pedido(s)</strong> asociado(s).
                    @endif
                </p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tarjetas de Estadísticas Mejoradas - MODIFICADO: Eliminada tarjeta "Sin Usuario" -->
    <div class="row g-4 mb-4">
        @php
            // Obtener conteo de pedidos para cada empleado (esto deberías pasarlo desde el controlador)
            $totalEmpleados = $empleados->count();
            $conUsuario = $empleados->where('usuario', '!=', null)->count();
            $administradores = $empleados->where('usuario.rol', 'Administración')->count();
            
            $stats = [
                [
                    'titulo' => 'Total Empleados',
                    'valor' => $totalEmpleados,
                    'icono' => 'fas fa-users',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => 'Registrados en el sistema'
                ],
                [
                    'titulo' => 'Con Usuario',
                    'valor' => $conUsuario,
                    'icono' => 'fas fa-user-check',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Acceso al sistema'
                ],
                [
                    'titulo' => 'Administradores',
                    'valor' => $administradores,
                    'icono' => 'fas fa-user-shield',
                    'color' => '#ef4444',
                    'gradiente' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                    'descripcion' => 'Con permisos totales'
                ]
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-6 col-lg-4">
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
                
                <h3 class="fw-bold mb-1" style="font-size: 2.5rem; color: #1f2937;">{{ $stat['valor'] }}</h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ $stat['titulo'] }}</p>
                
                <div class="stat-progress mt-3" style="
                    height: 4px;
                    background: #e5e7eb;
                    border-radius: 2px;
                    overflow: hidden;
                ">
                    @php
                        $porcentaje = $stats[0]['valor'] > 0 ? ($stat['valor'] / $stats[0]['valor']) * 100 : 0;
                    @endphp
                    <div class="stat-progress-bar" style="
                        width: {{ $porcentaje }}%;
                        height: 100%;
                        background: {{ $stat['gradiente'] }};
                        border-radius: 2px;
                        transition: width 1s ease;
                    "></div>
                </div>
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
                <p class="text-muted small mb-0">Encuentra empleados específicos usando los siguientes filtros</p>
            </div>
        </div>

        <form id="filtrosForm" method="GET" action="{{ route('personal.index') }}">
            <div class="row g-3">
                <!-- ID de Empleado -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-hashtag me-1" style="color: #667eea;"></i>
                        ID de Empleado
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
                               style="box-shadow: none;">
                        @if(request('id'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('id')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Nombre -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-user me-1" style="color: #667eea;"></i>
                        Nombre
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-signature text-primary"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-0 bg-light" 
                               name="nombre" 
                               placeholder="Buscar por nombre" 
                               value="{{ request('nombre') }}">
                        @if(request('nombre'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('nombre')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Cargo -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-briefcase me-1" style="color: #667eea;"></i>
                        Cargo
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user-tie text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="cargo">
                            <option value="">Todos los cargos</option>
                            @foreach($cargosUnicos as $cargo)
                            <option value="{{ $cargo }}" {{ request('cargo') == $cargo ? 'selected' : '' }}>
                                {{ $cargo }}
                            </option>
                            @endforeach
                        </select>
                        @if(request('cargo'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('cargo')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Área de Trabajo -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-layer-group me-1" style="color: #667eea;"></i>
                        Área
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-building text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="area">
                            <option value="">Todas las áreas</option>
                            @foreach($areasUnicas as $area)
                            <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>
                                {{ $area }}
                            </option>
                            @endforeach
                        </select>
                        @if(request('area'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('area')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Estado de Usuario -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-user-check me-1" style="color: #667eea;"></i>
                        Estado Usuario
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user-circle text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="estado_usuario">
                            <option value="">Todos</option>
                            <option value="con_usuario" {{ request('estado_usuario') == 'con_usuario' ? 'selected' : '' }}>Con Usuario</option>
                            <option value="sin_usuario" {{ request('estado_usuario') == 'sin_usuario' ? 'selected' : '' }}>Sin Usuario</option>
                        </select>
                        @if(request('estado_usuario'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('estado_usuario')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Rol de Usuario -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-user-tag me-1" style="color: #667eea;"></i>
                        Rol
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-tag text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="rol">
                            <option value="">Todos los roles</option>
                            <option value="Administración" {{ request('rol') == 'Administración' ? 'selected' : '' }}>Administración</option>
                            <option value="Almacén" {{ request('rol') == 'Almacén' ? 'selected' : '' }}>Almacén</option>
                            <option value="Logística" {{ request('rol') == 'Logística' ? 'selected' : '' }}>Logística</option>
                        </select>
                        @if(request('rol'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('rol')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Ordenamiento -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                        Ordenar por
                    </label>
                    <select class="form-select border-0 bg-light" name="sort_by">
                        <option value="id" {{ request('sort_by', 'id') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="Nombre" {{ request('sort_by') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                        <option value="Cargo" {{ request('sort_by') == 'Cargo' ? 'selected' : '' }}>Cargo</option>
                        <option value="Area_trabajo" {{ request('sort_by') == 'Area_trabajo' ? 'selected' : '' }}>Área</option>
                    </select>
                </div>

                <!-- Dirección de orden -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                        Dirección
                    </label>
                    <select class="form-select border-0 bg-light" name="sort_order">
                        <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div class="col-md-4">
                    <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                        @php
                            $filtrosActivos = collect(request()->all())
                                ->filter(function($value, $key) {
                                    return in_array($key, ['id', 'nombre', 'cargo', 'area', 'estado_usuario', 'rol']) 
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
                            <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary px-4" style="
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
        if(request('id')) $filtrosActivosLista[] = ['ID', request('id'), 'id'];
        if(request('nombre')) $filtrosActivosLista[] = ['Nombre', request('nombre'), 'nombre'];
        if(request('cargo')) $filtrosActivosLista[] = ['Cargo', request('cargo'), 'cargo'];
        if(request('area')) $filtrosActivosLista[] = ['Área', request('area'), 'area'];
        if(request('estado_usuario') == 'con_usuario') $filtrosActivosLista[] = ['Estado', 'Con Usuario', 'estado_usuario'];
        if(request('estado_usuario') == 'sin_usuario') $filtrosActivosLista[] = ['Estado', 'Sin Usuario', 'estado_usuario'];
        if(request('rol')) $filtrosActivosLista[] = ['Rol', request('rol'), 'rol'];
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

    <!-- Tabla de empleados Mejorada -->
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
                    Lista de Empleados
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    @if($empleadosPaginated->total() > 0)
                        Mostrando {{ $empleadosPaginated->firstItem() ?? 0 }}-{{ $empleadosPaginated->lastItem() ?? 0 }} de {{ $empleadosPaginated->total() }} empleado(s)
                    @else
                        No hay empleados registrados
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
                    <i class="fas fa-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'up' : 'down' }} me-1"></i>
                    Orden: {{ request('sort_by', 'ID') }}
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="50px"></th>
                        <th class="py-3">Empleado</th>
                        <th class="py-3">Contacto</th>
                        <th class="py-3">Información</th>
                        <th class="py-3">Cargo/Área</th>
                        <th class="py-3">Usuario</th>
                        <th class="py-3">Pedidos</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleadosPaginated as $empleado)
                    @php
                        $nombreCompleto = $empleado->Nombre . ' ' . $empleado->ApPaterno . ($empleado->ApMaterno ? ' ' . $empleado->ApMaterno : '');
                        $tieneUsuario = $empleado->usuario !== null;
                        $tienePedidos = $empleado->pedidos_count > 0;
                        $cantidadPedidos = $empleado->pedidos_count ?? 0;
                    @endphp
                    <tr class="align-middle empleado-row {{ $tienePedidos ? 'empleado-con-pedidos' : '' }}" data-id="{{ $empleado->id }}">
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detalles{{ $empleado->id }}" 
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
                        
                        <!-- Empleado -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="employee-avatar me-3" style="
                                    width: 48px;
                                    height: 48px;
                                    background: {{ $tienePedidos ? 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};
                                    border-radius: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-weight: 600;
                                    font-size: 1.2rem;
                                    box-shadow: 0 5px 15px {{ $tienePedidos ? 'rgba(245, 158, 11, 0.3)' : 'rgba(102, 126, 234, 0.3)' }};
                                ">
                                    {{ strtoupper(substr($empleado->Nombre, 0, 1)) }}{{ strtoupper(substr($empleado->ApPaterno, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $nombreCompleto }}</h6>
                                    <small class="text-muted">ID: #{{ str_pad($empleado->id, 5, '0', STR_PAD_LEFT) }}</small>
                                    @if($tienePedidos)
                                    <span class="badge bg-warning bg-opacity-10 text-warning ms-2">
                                        <i class="fas fa-shopping-cart me-1"></i>{{ $cantidadPedidos }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Contacto -->
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-phone-alt me-2" style="color: #10b981; width: 16px;"></i>
                                    <span class="small">{{ $empleado->Telefono }}</span>
                                </span>
                                @if($tieneUsuario)
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-envelope me-2" style="color: #3b82f6; width: 16px;"></i>
                                    <span class="small text-truncate" style="max-width: 150px;">{{ $empleado->usuario->correo }}</span>
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- Información Personal -->
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2" style="color: #f59e0b; width: 16px;"></i>
                                    <span class="small">{{ \Carbon\Carbon::parse($empleado->Fecha_nacimiento)->age }} años</span>
                                </span>
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-{{ $empleado->Sexo == 'M' ? 'mars' : ($empleado->Sexo == 'F' ? 'venus' : 'transgender-alt') }} me-2" 
                                          style="color: {{ $empleado->Sexo == 'M' ? '#3b82f6' : ($empleado->Sexo == 'F' ? '#ec4899' : '#6b7280') }}; width: 16px;"></i>
                                    <span class="small">
                                        @if($empleado->Sexo == 'M') Masculino
                                        @elseif($empleado->Sexo == 'F') Femenino
                                        @else Otro @endif
                                    </span>
                                </span>
                            </div>
                        </td>

                        <!-- Cargo/Área -->
                        <td>
                            <div class="d-flex flex-column gap-2">
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                    width: fit-content;
                                ">
                                    <i class="fas fa-briefcase me-1"></i>
                                    {{ $empleado->Cargo }}
                                </span>
                                <span class="badge px-3 py-2" style="
                                    background: #f3f4f6;
                                    color: #4b5563;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                    width: fit-content;
                                ">
                                    <i class="fas fa-layer-group me-1"></i>
                                    {{ $empleado->Area_trabajo }}
                                </span>
                            </div>
                        </td>

                        <!-- Usuario -->
                        <td>
                            @if($tieneUsuario)
                            <div class="d-flex flex-column gap-2">
                                <span class="badge px-3 py-2" style="
                                    background: 
                                        @if($empleado->usuario->rol == 'Administración') linear-gradient(135deg, #ef4444 0%, #dc2626 100%)
                                        @elseif($empleado->usuario->rol == 'Almacén') linear-gradient(135deg, #f59e0b 0%, #d97706 100%)
                                        @elseif($empleado->usuario->rol == 'Logística') linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)
                                        @else linear-gradient(135deg, #6b7280 0%, #4b5563 100%) @endif;
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                    width: fit-content;
                                ">
                                    <i class="fas fa-user-tag me-1"></i>
                                    {{ $empleado->usuario->rol }}
                                </span>
                            </div>
                            @else
                            <span class="badge px-3 py-2" style="
                                background: #fef3c7;
                                color: #92400e;
                                border-radius: 50px;
                                font-size: 0.75rem;
                            ">
                                <i class="fas fa-user-slash me-1"></i>
                                Sin usuario
                            </span>
                            @endif
                        </td>

                        <!-- Pedidos -->
                        <td>
                            @if($tienePedidos)
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    {{ $cantidadPedidos }} pedido(s)
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: #e5e7eb;
                                    color: #6b7280;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Sin pedidos
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('personal.edit', $empleado->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                   title="Editar empleado">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(!$tieneUsuario && !$tienePedidos)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="setCreateUsuario({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}')"
                                            title="Crear usuario">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                @endif
                                
                                <!-- Botón de eliminar condicional -->
                                @if($tienePedidos)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="showPedidosErrorModal('{{ addslashes($nombreCompleto) }}', {{ $cantidadPedidos }})"
                                            title="No se puede eliminar: tiene pedidos asociados">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="setDeleteEmpleado({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}', {{ $tieneUsuario ? 'true' : 'false' }})"
                                            title="Eliminar empleado {{ $tieneUsuario ? '(incluye usuario)' : '' }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible -->
                    <tr class="detalle-row">
                        <td colspan="8" class="p-0">
                            <div class="collapse" id="detalles{{ $empleado->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <div class="row g-4">
                                        <!-- Información Personal Detallada -->
                                        <div class="col-md-4">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-id-card me-2 text-primary"></i>
                                                    Información Personal
                                                </h6>
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Nombre completo:</span>
                                                    <span class="fw-medium">{{ $nombreCompleto }}</span>
                                                </div>
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Fecha nacimiento:</span>
                                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($empleado->Fecha_nacimiento)->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Edad:</span>
                                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($empleado->Fecha_nacimiento)->age }} años</span>
                                                </div>
                                                <div class="detail-item d-flex justify-content-between">
                                                    <span class="text-muted">Sexo:</span>
                                                    <span class="fw-medium">
                                                        @if($empleado->Sexo == 'M') Masculino
                                                        @elseif($empleado->Sexo == 'F') Femenino
                                                        @else Otro @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información de Contacto -->
                                        <div class="col-md-4">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-address-card me-2 text-primary"></i>
                                                    Contacto
                                                </h6>
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Teléfono:</span>
                                                    <span class="fw-medium">{{ $empleado->Telefono }}</span>
                                                </div>
                                                @if($tieneUsuario)
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Correo:</span>
                                                    <span class="fw-medium">{{ $empleado->usuario->correo }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Información Laboral -->
                                        <div class="col-md-4">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-briefcase me-2 text-primary"></i>
                                                    Laboral
                                                </h6>
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Cargo:</span>
                                                    <span class="fw-medium">{{ $empleado->Cargo }}</span>
                                                </div>
                                                <div class="detail-item d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Área:</span>
                                                    <span class="fw-medium">{{ $empleado->Area_trabajo }}</span>
                                                </div>
                                                @if($tieneUsuario)
                                                <div class="detail-item d-flex justify-content-between">
                                                    <span class="text-muted">Rol:</span>
                                                    <span class="fw-medium">{{ $empleado->usuario->rol }}</span>
                                                </div>
                                                @endif
                                                @if($tienePedidos)
                                                <div class="detail-item d-flex justify-content-between mt-2 pt-2 border-top">
                                                    <span class="text-muted">Pedidos:</span>
                                                    <span class="fw-medium text-warning">{{ $cantidadPedidos }} pedido(s)</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($tienePedidos)
                                    <div class="alert alert-warning mt-3 mb-0" style="background: #fff3cd; border: none;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            <span class="small">Este empleado tiene <strong>{{ $cantidadPedidos }} pedido(s)</strong> asociado(s) y no puede ser eliminado.</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state py-5">
                                <i class="fas fa-users-slash fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay empleados registrados</h5>
                                <p class="text-muted mb-4">
                                    @if(count($filtrosActivosLista) > 0)
                                        No se encontraron empleados con los filtros aplicados.
                                    @else
                                        Comienza agregando el primer empleado al sistema.
                                    @endif
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if(count($filtrosActivosLista) > 0)
                                    <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Limpiar Filtros
                                    </a>
                                    @endif
                                    <a href="{{ route('personal.create') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Agregar Empleado
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
        @if($empleadosPaginated->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center" style="border-top: 1px solid #e5e7eb;">
            <div class="text-muted small">
                Página {{ $empleadosPaginated->currentPage() }} de {{ $empleadosPaginated->lastPage() }}
            </div>
            <div>
                {{ $empleadosPaginated->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de eliminación de EMPLEADO -->
<div class="modal fade" id="deleteEmpleadoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header bg-danger text-white" style="border: none; padding: 1.5rem;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="modal-icon mb-4">
                    <i class="fas fa-user-slash fa-4x text-danger"></i>
                </div>
                <h6 class="fw-bold mb-3" id="deleteModalTitle"></h6>
                <p class="mb-3" id="empleadoNombreEliminar"></p>
                
                <!-- Mensaje dinámico según si tiene usuario o no -->
                <div class="alert border-0 text-start" id="deleteAlertMessage" style="background: #fef2f2; border-left: 4px solid #dc2626;">
                    <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                    <strong class="text-danger" id="deleteAlertTitle"></strong>
                    <ul class="mt-2 mb-0 small text-danger-emphasis" id="deleteAlertList">
                        <!-- Se llena dinámicamente con JavaScript -->
                    </ul>
                </div>
                
                <div class="mt-3 small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Esta acción es irreversible
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="deleteEmpleadoForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash me-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ERROR POR PEDIDOS -->
<div class="modal fade" id="pedidosErrorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header bg-warning" style="border: none; padding: 1.5rem;">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-lock me-2"></i>
                    Empleado con Pedidos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="modal-icon mb-4">
                    <i class="fas fa-shopping-cart fa-4x text-warning"></i>
                </div>
                <h6 class="fw-bold mb-3" id="pedidosErrorNombre"></h6>
                
                <div class="alert border-0 text-start mb-4" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <strong class="text-warning-emphasis">No se puede eliminar</strong>
                    </div>
                    <p class="mb-2 small" id="pedidosErrorMessage"></p>
                    <div class="mt-3 p-3 bg-white bg-opacity-50 rounded-3">
                        <strong class="d-block mb-2">🔍 ¿Qué hacer?</strong>
                        <ul class="small mb-0 ps-3">
                            <li class="mb-1">Reasigna los pedidos a otro empleado</li>
                            <li class="mb-1">O completa/elimina los pedidos primero</li>
                            <li>Luego podrás eliminar este empleado</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-warning px-5" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de creación de usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header bg-success text-white" style="border: none; padding: 1.5rem;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-plus me-2"></i>
                    Crear Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createUserForm" method="POST" action="">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Empleado</label>
                        <div class="p-3 bg-light rounded-3" id="empleadoNombre" style="font-weight: 500;"></div>
                        <input type="hidden" id="empleadoId" name="empleado_id">
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" class="form-control border-0 bg-light" id="correo" 
                                   name="correo" placeholder="ejemplo@empresa.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label fw-semibold">Rol</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-user-tag text-primary"></i>
                            </span>
                            <select class="form-select border-0 bg-light" id="rol" name="rol" required>
                                <option value="">Seleccione un rol</option>
                                <option value="Administración">Administración</option>
                                <option value="Almacén">Almacén</option>
                                <option value="Logística">Logística</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contrasena" class="form-label fw-semibold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light" id="contrasena" 
                                   name="contrasena" placeholder="Mínimo 6 caracteres" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contrasena_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light" id="contrasena_confirmation" 
                                   name="contrasena_confirmation" placeholder="Repite la contraseña" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-user-plus me-2"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Animaciones */
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

/* Hover effects */
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.stat-card:hover .stat-decoration {
    transform: scale(1.2);
}

.btn-expand:hover {
    background: #667eea !important;
    color: white !important;
    border-color: #667eea !important;
    transform: scale(1.1);
}

.empleado-row:hover {
    background-color: #f8fafc !important;
}

.empleado-con-pedidos {
    background-color: rgba(245, 158, 11, 0.02);
}

.empleado-con-pedidos:hover {
    background-color: rgba(245, 158, 11, 0.08) !important;
}

/* Estilos para badges */
.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Paginación personalizada */
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
    background: #667eea !important;
    color: white !important;
    border-color: #667eea !important;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .table-container {
        border-radius: 16px;
    }
    
    .empleado-row .d-flex {
        flex-wrap: wrap;
    }
    
    .btn-expand {
        width: 28px;
        height: 28px;
    }
}

/* Estilos para los detalles */
.detail-card {
    transition: all 0.3s ease;
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}

.detail-item {
    padding: 0.5rem 0;
    border-bottom: 1px dashed #e5e7eb;
}

.detail-item:last-child {
    border-bottom: none;
}

/* Empty state */
.empty-state {
    animation: fadeIn 0.5s ease;
}

/* Tooltips personalizados */
[data-bs-toggle="tooltip"] {
    cursor: help;
}

/* Mejoras para modales */
.modal-content {
    border: none;
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}

/* Ajustes para inputs en modales */
.modal .form-control:focus,
.modal .form-select:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    border-color: #667eea;
}

/* Animación para filas expandibles */
.collapse.show + .detalle-row {
    animation: slideDown 0.3s ease;
}

/* Estilo para el toast notification */
.toast-notification {
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
    border-left: 4px solid #10b981;
}

.toast-notification.error {
    border-left-color: #ef4444;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manejar botones expandir
    document.querySelectorAll('.btn-expand').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            if (icon) {
                icon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
    });

    // Validación del formulario de creación de usuario
    const createUserForm = document.getElementById('createUserForm');
    if (createUserForm) {
        createUserForm.addEventListener('submit', function(e) {
            const contrasena = document.getElementById('contrasena').value;
            const confirmacion = document.getElementById('contrasena_confirmation').value;
            
            if (contrasena !== confirmacion) {
                e.preventDefault();
                showNotification('Las contraseñas no coinciden', 'error');
                return false;
            }
            
            if (contrasena.length < 6) {
                e.preventDefault();
                showNotification('La contraseña debe tener al menos 6 caracteres', 'error');
                return false;
            }
        });
    }

    // Limpiar modal al cerrar
    const createUserModal = document.getElementById('createUserModal');
    if (createUserModal) {
        createUserModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('createUserForm').reset();
        });
    }
});

// Sistema de notificaciones mejorado
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'toast-notification ' + (type === 'error' ? 'error' : '');
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

// Función para limpiar filtros
function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Función para eliminar empleado (sin pedidos)
function setDeleteEmpleado(empleadoId, empleadoNombre, tieneUsuario) {
    // Actualizar título y mensaje según si tiene usuario o no
    const modalTitle = document.getElementById('deleteModalTitle');
    const deleteAlertTitle = document.getElementById('deleteAlertTitle');
    const deleteAlertList = document.getElementById('deleteAlertList');
    const empleadoNombreElem = document.getElementById('empleadoNombreEliminar');
    
    empleadoNombreElem.innerHTML = `<strong>"${empleadoNombre}"</strong>`;
    
    if (tieneUsuario) {
        modalTitle.textContent = '¿Eliminar empleado y su usuario?';
        deleteAlertTitle.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Se eliminarán:';
        deleteAlertList.innerHTML = `
            <li class="mb-1">✓ Los datos del empleado</li>
            <li class="mb-1">✓ La cuenta de usuario asociada</li>
            <li class="text-danger fw-semibold mt-2">⚠️ El empleado perderá acceso al sistema</li>
        `;
    } else {
        modalTitle.textContent = '¿Eliminar empleado?';
        deleteAlertTitle.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Se eliminarán:';
        deleteAlertList.innerHTML = `
            <li class="mb-1">✓ Los datos del empleado</li>
            <li class="text-muted">(No tiene usuario asociado)</li>
        `;
    }
    
    // Actualizar acción del formulario
    document.getElementById('deleteEmpleadoForm').action = `/personal/${empleadoId}`;
    
    // Mostrar modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteEmpleadoModal'));
    deleteModal.show();
}

// Función para mostrar error de pedidos
function showPedidosErrorModal(empleadoNombre, pedidosCount) {
    document.getElementById('pedidosErrorNombre').innerHTML = `
        <span class="text-warning fw-bold">${empleadoNombre}</span>
        <small class="d-block text-muted mt-1">ID: Empleado con pedidos</small>
    `;
    document.getElementById('pedidosErrorMessage').innerHTML = `
        El empleado <strong>${empleadoNombre}</strong> tiene 
        <strong class="text-warning">${pedidosCount} pedido(s)</strong> asociado(s) y no puede ser eliminado.
        <br><br>
        <span class="d-block mt-2">Para poder eliminar este empleado, primero debes reasignar o eliminar sus pedidos.</span>
    `;
    
    const errorModal = new bootstrap.Modal(document.getElementById('pedidosErrorModal'));
    errorModal.show();
}

// Función para crear usuario
function setCreateUsuario(empleadoId, empleadoNombre) {
    document.getElementById('empleadoNombre').textContent = empleadoNombre;
    document.getElementById('empleadoId').value = empleadoId;
    document.getElementById('createUserForm').action = `/personal/usuario/${empleadoId}`;
    
    // Mostrar modal
    const createModal = new bootstrap.Modal(document.getElementById('createUserModal'));
    createModal.show();
}
</script>
@endsection