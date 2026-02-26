@extends('layouts.app')

@section('content')
<div id="personal-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                        Administra todos los empleados del sistema
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
                <a href="{{ route('personal.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-user-plus me-2"></i> Nuevo Empleado
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

    <!-- Tarjetas de Estadísticas Mejoradas - Mostrando todos los empleados -->
    <div class="row g-4 mb-4">
        @php
            $totalEmpleados = $empleados->total();
            $activos = $empleados->where('estado', 1)->count();
            $inactivos = $empleados->where('estado', 0)->count();
            $conUsuario = $empleados->filter(function($e) { 
                return $e->usuario !== null; 
            })->count();
            
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
                    'titulo' => 'Empleados Activos',
                    'valor' => $activos,
                    'icono' => 'fas fa-user-check',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Actualmente activos'
                ],
                [
                    'titulo' => 'Con Usuario',
                    'valor' => $conUsuario,
                    'icono' => 'fas fa-user-shield',
                    'color' => '#3b82f6',
                    'gradiente' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                    'descripcion' => 'Tienen acceso'
                ],
                [
                    'titulo' => 'Inactivos',
                    'valor' => $inactivos,
                    'icono' => 'fas fa-archive',
                    'color' => '#6b7280',
                    'gradiente' => 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)',
                    'descripcion' => 'Desactivados'
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

    <!-- Panel de Búsqueda y Filtros Mejorado -->
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
                <!-- ID -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-hashtag me-1" style="color: #667eea;"></i>
                        ID
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
                               value="{{ request('nombre') }}"
                               style="box-shadow: none;">
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
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-briefcase me-1" style="color: #667eea;"></i>
                        Cargo
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user-tie text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="cargo">
                            <option value="" {{ request('cargo') == '' ? 'selected' : '' }}>Todos</option>
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

                <!-- Área -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-layer-group me-1" style="color: #667eea;"></i>
                        Área
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-building text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="area">
                            <option value="" {{ request('area') == '' ? 'selected' : '' }}>Todas</option>
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

                <!-- Estado del empleado -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-flag me-1" style="color: #667eea;"></i>
                        Estado Empleado
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user-check text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="estado_empleado">
                            <option value="" {{ request('estado_empleado') == '' ? 'selected' : '' }}>Todos</option>
                            <option value="activos" {{ request('estado_empleado') == 'activos' ? 'selected' : '' }}>Activos</option>
                            <option value="inactivos" {{ request('estado_empleado') == 'inactivos' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                        @if(request('estado_empleado'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('estado_empleado')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Estado Usuario -->
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
                            <option value="" {{ request('estado_usuario') == '' ? 'selected' : '' }}>Todos</option>
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

                <!-- Rol -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-user-tag me-1" style="color: #667eea;"></i>
                        Rol
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-tag text-primary"></i>
                        </span>
                        <select class="form-select border-0 bg-light" name="rol">
                            <option value="" {{ request('rol') == '' ? 'selected' : '' }}>Todos</option>
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
                        <option value="estado" {{ request('sort_by') == 'estado' ? 'selected' : '' }}>Estado</option>
                    </select>
                </div>

                <!-- Dirección de orden -->
                <div class="col-md-1">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                        Dir.
                    </label>
                    <select class="form-select border-0 bg-light" name="sort_order">
                        <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Asc</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div class="col-md-12">
                    <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                        @php
                            $filtrosActivos = collect(request()->all())
                                ->filter(function($value, $key) {
                                    return in_array($key, ['id', 'nombre', 'cargo', 'area', 'estado_empleado', 'estado_usuario', 'rol']) 
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
        if(request('estado_empleado') == 'activos') $filtrosActivosLista[] = ['Estado Empleado', 'Activos', 'estado_empleado'];
        if(request('estado_empleado') == 'inactivos') $filtrosActivosLista[] = ['Estado Empleado', 'Inactivos', 'estado_empleado'];
        if(request('estado_usuario') == 'con_usuario') $filtrosActivosLista[] = ['Estado Usuario', 'Con Usuario', 'estado_usuario'];
        if(request('estado_usuario') == 'sin_usuario') $filtrosActivosLista[] = ['Estado Usuario', 'Sin Usuario', 'estado_usuario'];
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
            <a href="{{ route('personal.index') }}" class="btn btn-sm btn-outline-secondary ms-2" style="border-radius: 50px;">
                <i class="fas fa-times me-1"></i> Limpiar todos
            </a>
        </div>
    </div>
    @endif

    <!-- Tabla de empleados Mejorada - Mostrando TODOS los empleados -->
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
                    Mostrando {{ $empleados->firstItem() ?? 0 }} - {{ $empleados->lastItem() ?? 0 }} de {{ $empleados->total() }} empleado(s)
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
            <table class="table table-hover mb-0" id="personalTable">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="60px"></th>
                        <th class="py-3">Empleado</th>
                        <th class="py-3">Contacto</th>
                        <th class="py-3">Información</th>
                        <th class="py-3">Cargo/Área</th>
                        <th class="py-3">Usuario</th>
                        <th class="py-3">Pedidos</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $empleado)
                    @php
                        $nombreCompleto = $empleado->Nombre . ' ' . $empleado->ApPaterno . ($empleado->ApMaterno ? ' ' . $empleado->ApMaterno : '');
                        $tieneUsuario = $empleado->usuario !== null;
                        $usuarioActivo = $tieneUsuario && $empleado->usuario->estado == 1;
                        $tienePedidos = $empleado->pedidos_count > 0;
                        $cantidadPedidos = $empleado->pedidos_count ?? 0;
                        
                        // Iniciales para el avatar
                        $iniciales = strtoupper(substr($empleado->Nombre, 0, 1)) . strtoupper(substr($empleado->ApPaterno, 0, 1));
                    @endphp
                    <tr class="align-middle empleado-row {{ $empleado->estado == 0 ? 'table-secondary' : '' }}" 
                        data-id="{{ $empleado->id }}"
                        data-nombre="{{ strtolower($nombreCompleto) }}"
                        data-cargo="{{ strtolower($empleado->Cargo) }}"
                        data-area="{{ strtolower($empleado->Area_trabajo) }}"
                        data-tiene-usuario="{{ $tieneUsuario ? '1' : '0' }}"
                        data-rol="{{ $tieneUsuario ? strtolower($empleado->usuario->rol) : '' }}"
                        data-estado="{{ $empleado->estado }}">
                        
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand-empleado" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detallesEmpleado{{ $empleado->id }}"
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
                                <div class="empleado-avatar empleado-avatar-md me-3" style="
                                    width: 48px;
                                    height: 48px;
                                    background: {{ $empleado->estado == 1 ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)' }};
                                    border-radius: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-weight: 600;
                                    font-size: 1.2rem;
                                    box-shadow: 0 5px 15px {{ $empleado->estado == 1 ? 'rgba(102, 126, 234, 0.3)' : 'rgba(156, 163, 175, 0.3)' }};
                                ">
                                    {{ $iniciales }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $nombreCompleto }}</h6>
                                    <small class="text-muted">ID: #{{ str_pad($empleado->id, 5, '0', STR_PAD_LEFT) }}</small>
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
                                    <span class="small text-truncate" style="max-width: 150px;" 
                                          data-bs-toggle="tooltip" title="{{ $empleado->usuario->correo }}">
                                        {{ $empleado->usuario->correo }}
                                    </span>
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
                                @if($usuarioActivo)
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
                                    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-user-slash me-1"></i>
                                    Usuario inactivo
                                </span>
                                @endif
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
                                    background: #f3f4f6;
                                    color: #4b5563;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Sin pedidos
                                </span>
                            @endif
                        </td>

                        <!-- Estado del Empleado -->
                        <td>
                            @if($empleado->estado == 1)
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Activo
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                @if($empleado->estado == 1) {{-- Activo --}}
                                    <a href="{{ route('personal.edit', $empleado->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                       title="Editar empleado">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if(!$tieneUsuario)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                onclick="setCreateUsuario({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}')"
                                                title="Crear usuario">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="setDesactivarEmpleado({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}', {{ $tieneUsuario ? 'true' : 'false' }})"
                                            title="Desactivar empleado">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                @else {{-- Inactivo --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="activarEmpleado({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}')"
                                            title="Activar empleado">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    
                                    <span class="btn btn-sm btn-outline-secondary disabled" 
                                          style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5; cursor: not-allowed;"
                                          title="No se puede editar un empleado inactivo">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles del empleado -->
                    <tr class="detalle-empleado-row">
                        <td colspan="9" class="p-0 border-0">
                            <div class="collapse" id="detallesEmpleado{{ $empleado->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <!-- Badge de estado en detalles -->
                                    <div class="mb-3 text-end">
                                        @if($empleado->estado == 1)
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Empleado Activo
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Empleado Inactivo
                                            </span>
                                        @endif
                                    </div>
                                    
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
                                                    <span class="text-muted">ID Empleado:</span>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">#{{ str_pad($empleado->id, 5, '0', STR_PAD_LEFT) }}</span>
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

                                        <!-- Contacto -->
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
                                                    <span class="badge px-3 py-2" style="
                                                        background: 
                                                            @if($empleado->usuario->rol == 'Administración') linear-gradient(135deg, #ef4444 0%, #dc2626 100%)
                                                            @elseif($empleado->usuario->rol == 'Almacén') linear-gradient(135deg, #f59e0b 0%, #d97706 100%)
                                                            @elseif($empleado->usuario->rol == 'Logística') linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)
                                                            @else linear-gradient(135deg, #6b7280 0%, #4b5563 100%) @endif;
                                                        color: white;
                                                        border-radius: 50px;
                                                        font-size: 0.75rem;
                                                    ">
                                                        {{ $empleado->usuario->rol }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información de Pedidos -->
                                    <div class="row g-4 mt-2">
                                        <div class="col-12">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-shopping-cart me-2 text-primary"></i>
                                                    Historial de Pedidos
                                                </h6>
                                                
                                                @if($tienePedidos)
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="pedidos-stats p-3 bg-light rounded-3 flex-grow-1">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <span class="text-muted">Total de pedidos realizados:</span>
                                                            <span class="badge px-3 py-2" style="
                                                                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                                                color: white;
                                                                border-radius: 50px;
                                                                font-size: 1rem;
                                                            ">
                                                                {{ $cantidadPedidos }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="alert alert-secondary bg-light border-0 mb-0" style="border-radius: 12px;">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Este empleado no ha realizado ningún pedido hasta el momento.
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botones de acción en detalles -->
                                    <div class="mt-4 d-flex gap-2 justify-content-end">
                                        @if($empleado->estado == 1)
                                            <a href="{{ route('personal.edit', $empleado->id) }}" 
                                               class="btn btn-outline-primary btn-sm px-4"
                                               style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                <i class="fas fa-edit me-1"></i> Editar Empleado
                                            </a>
                                            
                                            @if(!$tieneUsuario)
                                                <button type="button" 
                                                        class="btn btn-outline-success btn-sm px-4"
                                                        style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                        onclick="setCreateUsuario({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}')">
                                                    <i class="fas fa-user-plus me-1"></i> Crear Usuario
                                                </button>
                                            @endif
                                            
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm px-4"
                                                    style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                    onclick="setDesactivarEmpleado({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}', {{ $tieneUsuario ? 'true' : 'false' }})">
                                                <i class="fas fa-power-off me-1"></i> Desactivar Empleado
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-outline-success btn-sm px-4"
                                                    style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                    onclick="activarEmpleado({{ $empleado->id }}, '{{ addslashes($nombreCompleto) }}')">
                                                <i class="fas fa-check-circle me-1"></i> Activar Empleado
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
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

        <!-- PAGINACIÓN -->
        @if($empleados instanceof \Illuminate\Pagination\LengthAwarePaginator && $empleados->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $empleados->appends(request()->query())->links() }}
        </div>
        @endif

        <div class="card-footer bg-white border-0 py-3 px-4" style="border-top: 1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $empleados->firstItem() ?? 0 }} - {{ $empleados->lastItem() ?? 0 }} de {{ $empleados->total() }} empleado(s)
                </div>
                <div class="text-muted small">
                    @if(request('sort_by') == 'Nombre')
                        Ordenados por: <strong>Nombre</strong>
                    @elseif(request('sort_by') == 'Cargo')
                        Ordenados por: <strong>Cargo</strong>
                    @elseif(request('sort_by') == 'Area_trabajo')
                        Ordenados por: <strong>Área</strong>
                    @elseif(request('sort_by') == 'estado')
                        Ordenados por: <strong>Estado</strong>
                    @else
                        Ordenados por: <strong>ID</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE DESACTIVACIÓN MEJORADO -->
<div class="modal fade" id="desactivarEmpleadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-danger text-white" style="
                background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold" id="desactivarModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                    Confirmar Desactivación
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
                        <i class="fas fa-power-off fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="desactivarModalTitle"></h5>
                <p class="text-muted mb-4" id="empleadoNombreDesactivar"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Empleado a desactivar:</span>
                            <span class="fw-bold" id="empleadoNombreDesactivarDisplay"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert border-0 text-start" id="desactivarAlertMessage" style="
                    background: #fff3cd;
                    border-left: 4px solid #ffc107;
                    border-radius: 12px;
                ">
                    <!-- Se llena dinámicamente -->
                </div>
                
                <div class="mt-3 small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Puedes reactivarlo en cualquier momento
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="desactivarEmpleadoForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4" id="confirmDesactivarBtn" style="border-radius: 50px;">
                        <i class="fas fa-power-off me-2"></i>Sí, desactivar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ACTIVACIÓN -->
<div class="modal fade" id="activarEmpleadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-success text-white" style="
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-circle me-2 fa-lg"></i>
                    Confirmar Activación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body text-center p-4">
                <div class="activate-icon-wrapper mb-4">
                    <div class="activate-icon-circle" style="
                        width: 80px;
                        height: 80px;
                        background: rgba(16, 185, 129, 0.1);
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="activarModalTitle"></h5>
                <p class="text-muted mb-4" id="empleadoNombreActivar"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Empleado a activar:</span>
                            <span class="fw-bold" id="empleadoNombreActivarDisplay"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-info-circle fs-4 me-3 text-success"></i>
                    <div class="text-start">
                        <strong class="text-success">¡Información!</strong>
                        <p class="mb-0 text-muted small">Al activar este empleado, estará disponible nuevamente en el sistema.</p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="activarEmpleadoForm" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 50px;">
                        <i class="fas fa-check-circle me-2"></i>Sí, activar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE CREACIÓN DE USUARIO MEJORADO -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-success text-white" style="
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-plus me-2 fa-lg"></i>
                    Crear Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form id="createUserForm" method="POST" action="">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="user-icon-wrapper mb-3">
                            <div class="user-icon-circle" style="
                                width: 70px;
                                height: 70px;
                                background: rgba(16, 185, 129, 0.1);
                                border-radius: 50%;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <i class="fas fa-user-plus fa-3x text-success"></i>
                            </div>
                        </div>
                        <h6 class="fw-bold">Asignar Usuario a Empleado</h6>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">Empleado</label>
                        <div class="p-3 bg-light rounded-3" id="empleadoNombre" style="font-weight: 500; border-radius: 12px;"></div>
                        <input type="hidden" id="empleadoId" name="empleado_id">
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold text-muted small">
                            <i class="fas fa-envelope me-1" style="color: #667eea;"></i>
                            Correo Electrónico
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" class="form-control border-0 bg-light" id="correo" 
                                   name="correo" placeholder="ejemplo@empresa.com" required
                                   style="box-shadow: none;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label fw-semibold text-muted small">
                            <i class="fas fa-user-tag me-1" style="color: #667eea;"></i>
                            Rol
                        </label>
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
                        <label for="contrasena" class="form-label fw-semibold text-muted small">
                            <i class="fas fa-lock me-1" style="color: #667eea;"></i>
                            Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light" id="contrasena" 
                                   name="contrasena" placeholder="Mínimo 6 caracteres" required
                                   style="box-shadow: none;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contrasena_confirmation" class="form-label fw-semibold text-muted small">
                            <i class="fas fa-lock me-1" style="color: #667eea;"></i>
                            Confirmar Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control border-0 bg-light" id="contrasena_confirmation" 
                                   name="contrasena_confirmation" placeholder="Repite la contraseña" required
                                   style="box-shadow: none;">
                        </div>
                    </div>
                    
                    <div class="alert alert-info bg-opacity-10 border-0 d-flex align-items-center mt-3" role="alert" style="border-radius: 12px;">
                        <i class="fas fa-info-circle fs-4 me-3 text-info"></i>
                        <div class="text-start">
                            <strong class="text-info">¡Importante!</strong>
                            <p class="mb-0 text-muted small">El usuario podrá acceder al sistema inmediatamente después de su creación.</p>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 50px;">
                        <i class="fas fa-user-plus me-2"></i>Crear Usuario
                    </button>
                </div>
            </form>
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
    
    // Configurar auto-submit de filtros
    setupFilterAutoSubmit();
    
    // Configurar botón de refrescar
    setupRefreshButton();
    
    // Configurar limpieza de modales
    setupModalCleanup();
    
    // Validación del formulario de creación de usuario
    setupUserFormValidation();
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setupExpandButtons() {
    document.querySelectorAll('.btn-expand-empleado').forEach(button => {
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
        });
    });
}

function setupFilterAutoSubmit() {
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"], select[name="cargo"], select[name="area"], select[name="estado_empleado"], select[name="estado_usuario"], select[name="rol"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filtrosForm').submit();
        });
    });
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
    const desactivarModal = document.getElementById('desactivarEmpleadoModal');
    if (desactivarModal) {
        desactivarModal.addEventListener('hidden.bs.modal', function() {
            forceCleanupModals();
        });
    }
    
    const activarModal = document.getElementById('activarEmpleadoModal');
    if (activarModal) {
        activarModal.addEventListener('hidden.bs.modal', function() {
            forceCleanupModals();
        });
    }
    
    const createUserModal = document.getElementById('createUserModal');
    if (createUserModal) {
        createUserModal.addEventListener('hidden.bs.modal', function() {
            forceCleanupModals();
            document.getElementById('createUserForm').reset();
        });
    }
}

function forceCleanupModals() {
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.documentElement.style.overflow = '';
}

function setupUserFormValidation() {
    const createUserForm = document.getElementById('createUserForm');
    if (createUserForm) {
        createUserForm.addEventListener('submit', function(e) {
            const contrasena = document.getElementById('contrasena').value;
            const confirmacion = document.getElementById('contrasena_confirmation').value;
            
            if (contrasena !== confirmacion) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (contrasena.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });
    }
}

function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

function setDesactivarEmpleado(empleadoId, empleadoNombre, tieneUsuario) {
    try {
        forceCleanupModals();
        
        const modalTitle = document.getElementById('desactivarModalTitle');
        const empleadoNombreElem = document.getElementById('empleadoNombreDesactivar');
        const empleadoNombreDisplay = document.getElementById('empleadoNombreDesactivarDisplay');
        const alertMessage = document.getElementById('desactivarAlertMessage');
        
        empleadoNombreElem.innerHTML = `<strong>"${empleadoNombre}"</strong>`;
        empleadoNombreDisplay.textContent = empleadoNombre;
        
        if (tieneUsuario) {
            modalTitle.textContent = '¿Desactivar empleado y su usuario?';
            alertMessage.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                <strong class="text-warning-emphasis">Se desactivarán:</strong>
                <ul class="mt-2 mb-0">
                    <li>✓ El empleado (cambiará a estado inactivo)</li>
                    <li>✓ La cuenta de usuario asociada (no podrá iniciar sesión)</li>
                </ul>
                <div class="mt-2 fw-semibold text-danger">⚠️ El empleado perderá acceso al sistema</div>
            `;
        } else {
            modalTitle.textContent = '¿Desactivar empleado?';
            alertMessage.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                <strong class="text-warning-emphasis">Se desactivará:</strong>
                <ul class="mt-2 mb-0">
                    <li>✓ El empleado (cambiará a estado inactivo)</li>
                </ul>
                <div class="mt-2 text-muted small">(No tiene usuario asociado)</div>
            `;
        }
        
        document.getElementById('desactivarEmpleadoForm').action = `/personal/${empleadoId}`;
        
        setTimeout(() => {
            const desactivarModal = new bootstrap.Modal(document.getElementById('desactivarEmpleadoModal'));
            desactivarModal.show();
        }, 50);
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al preparar la desactivación. Por favor, recarga la página.');
    }
}

function activarEmpleado(empleadoId, empleadoNombre) {
    try {
        forceCleanupModals();
        
        document.getElementById('activarModalTitle').textContent = `¿Activar "${empleadoNombre}"?`;
        document.getElementById('empleadoNombreActivar').innerHTML = `<strong>"${empleadoNombre}"</strong>`;
        document.getElementById('empleadoNombreActivarDisplay').textContent = empleadoNombre;
        
        document.getElementById('activarEmpleadoForm').action = `/personal/${empleadoId}/activar`;
        
        setTimeout(() => {
            const activarModal = new bootstrap.Modal(document.getElementById('activarEmpleadoModal'));
            activarModal.show();
        }, 50);
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al preparar la activación. Por favor, recarga la página.');
    }
}

function setCreateUsuario(empleadoId, empleadoNombre) {
    try {
        forceCleanupModals();
        
        document.getElementById('empleadoNombre').textContent = empleadoNombre;
        document.getElementById('empleadoId').value = empleadoId;
        document.getElementById('createUserForm').action = `/personal/usuario/${empleadoId}`;
        
        setTimeout(() => {
            const createModal = new bootstrap.Modal(document.getElementById('createUserModal'));
            createModal.show();
        }, 50);
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al preparar la creación de usuario. Por favor, recarga la página.');
    }
}

// Estilos dinámicos para animaciones
const spinStyle = document.createElement('style');
spinStyle.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spin {
        animation: spin 0.5s linear infinite;
    }
    
    .stat-card:hover .stat-decoration {
        transform: scale(1.2);
    }
    
    .btn-expand-empleado:hover {
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
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #personal-page .collapse.show {
        animation: slideDown 0.3s ease;
    }
    
    .delete-icon-circle, .activate-icon-circle, .user-icon-circle {
        animation: pulseIcon 2s infinite;
    }
    
    @keyframes pulseIcon {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    }
    
    .table-secondary {
        background-color: rgba(156, 163, 175, 0.05) !important;
    }
    
    .table-secondary:hover {
        background-color: rgba(156, 163, 175, 0.1) !important;
    }
`;
document.head.appendChild(spinStyle);
</script>
@endpush

<style>
#personal-page .empleado-avatar {
    width: 48px;
    height: 48px;
}

#personal-page .empleado-avatar-md {
    width: 40px;
    height: 40px;
}

#personal-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#personal-page .table tbody tr {
    transition: all 0.2s ease;
}

#personal-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

#personal-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#personal-page .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

#personal-page .detail-card {
    transition: all 0.3s ease;
}

#personal-page .detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}

/* Estilos para la paginación */
.pagination {
    margin-bottom: 0;
    justify-content: center;
}

.page-link {
    border: none;
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border-radius: 8px;
    color: #4b5563;
    transition: all 0.2s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.page-item.disabled .page-link {
    color: #9ca3af;
    pointer-events: none;
    background: #f3f4f6;
}

/* Responsive */
@media (max-width: 768px) {
    #personal-page .btn-expand-empleado {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #personal-page .empleado-avatar, 
    #personal-page .empleado-avatar-md {
        width: 32px;
        height: 32px;
    }
    
    #personal-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #personal-page .detalle-empleado-row .row {
        flex-direction: column;
    }
}

/* Tooltips */
.text-truncate[data-bs-toggle="tooltip"] {
    cursor: help;
}
</style>
@endsection