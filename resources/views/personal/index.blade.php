@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">Gestión de Personal</h1>
            <p class="text-muted mb-0">Administra empleados y los usuarios asociados al sistema</p>
        </div>
        <div>
            <a href="{{ route('personal.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Nuevo Empleado
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

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Empleados</h6>
                            <h3 class="mb-0">{{ $empleados->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-primary fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Con Usuario</h6>
                            <h3 class="mb-0">{{ $empleados->where('usuario', '!=', null)->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-check text-success fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Sin Usuario</h6>
                            <h3 class="mb-0">{{ $empleados->where('usuario', null)->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-times text-warning fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Administradores</h6>
                            <h3 class="mb-0">{{ $empleados->where('usuario.rol', 'Administración')->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-shield text-danger fa-lg"></i>
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
            <form id="filtrosForm" method="GET" action="{{ route('personal.index') }}">
                <div class="row g-3">
                    <!-- ID de Empleado -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-hashtag me-1"></i> ID de Empleado
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
                                   aria-label="ID de Empleado"
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

                    <!-- Nombre -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-user me-1"></i> Nombre
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-signature"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre" 
                                   placeholder="Buscar por nombre" 
                                   value="{{ request('nombre') }}"
                                   aria-label="Nombre">
                            @if(request('nombre'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('nombre')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Cargo -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-briefcase me-1"></i> Cargo
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <select class="form-select" name="cargo" aria-label="Cargo">
                                <option value="">Todos los cargos</option>
                                @foreach($cargosUnicos as $cargo)
                                <option value="{{ $cargo }}" {{ request('cargo') == $cargo ? 'selected' : '' }}>
                                    {{ $cargo }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('cargo'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('cargo')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Área de Trabajo -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-layer-group me-1"></i> Área
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-building"></i>
                            </span>
                            <select class="form-select" name="area" aria-label="Área de trabajo">
                                <option value="">Todas las áreas</option>
                                @foreach($areasUnicas as $area)
                                <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>
                                    {{ $area }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('area'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('area')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Estado de Usuario -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-user-check me-1"></i> Estado Usuario
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user-circle"></i>
                            </span>
                            <select class="form-select" name="estado_usuario" aria-label="Estado de usuario">
                                <option value="">Todos</option>
                                <option value="con_usuario" {{ request('estado_usuario') == 'con_usuario' ? 'selected' : '' }}>Con Usuario</option>
                                <option value="sin_usuario" {{ request('estado_usuario') == 'sin_usuario' ? 'selected' : '' }}>Sin Usuario</option>
                            </select>
                            @if(request('estado_usuario'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('estado_usuario')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Rol de Usuario -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-user-tag me-1"></i> Rol
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-tag"></i>
                            </span>
                            <select class="form-select" name="rol" aria-label="Rol de usuario">
                                <option value="">Todos los roles</option>
                                <option value="Administración" {{ request('rol') == 'Administración' ? 'selected' : '' }}>Administración</option>
                                <option value="Almacén" {{ request('rol') == 'Almacén' ? 'selected' : '' }}>Almacén</option>
                                <option value="Logística" {{ request('rol') == 'Logística' ? 'selected' : '' }}>Logística</option>
                            </select>
                            @if(request('rol'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('rol')">
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
                            <option value="id" {{ request('sort_by', 'id') == 'id' ? 'selected' : '' }}>ID</option>
                            <option value="Nombre" {{ request('sort_by') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="Cargo" {{ request('sort_by') == 'Cargo' ? 'selected' : '' }}>Cargo</option>
                            <option value="Area_trabajo" {{ request('sort_by') == 'Area_trabajo' ? 'selected' : '' }}>Área</option>
                        </select>
                    </div>

                    <!-- Dirección de orden -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort-amount-down me-1"></i> Dirección
                        </label>
                        <select class="form-select" name="sort_order" aria-label="Dirección orden">
                            <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                            <div class="text-muted small">
                                @php
                                    $filtrosActivos = collect(request()->all())
                                        ->filter(function($value, $key) {
                                            return in_array($key, ['id', 'nombre', 'cargo', 'area', 'estado_usuario', 'rol']) 
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
                                <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary">
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
        if(request('id')) $filtrosActivos[] = ['ID Empleado', request('id')];
        if(request('nombre')) $filtrosActivos[] = ['Nombre', request('nombre')];
        if(request('cargo')) $filtrosActivos[] = ['Cargo', request('cargo')];
        if(request('area')) $filtrosActivos[] = ['Área', request('area')];
        if(request('estado_usuario') == 'con_usuario') $filtrosActivos[] = ['Estado', 'Con Usuario'];
        if(request('estado_usuario') == 'sin_usuario') $filtrosActivos[] = ['Estado', 'Sin Usuario'];
        if(request('rol')) $filtrosActivos[] = ['Rol', request('rol')];
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
                                    $filtro[0] == 'ID Empleado' ? 'id' : 
                                    ($filtro[0] == 'Nombre' ? 'nombre' : 
                                    ($filtro[0] == 'Cargo' ? 'cargo' : 
                                    ($filtro[0] == 'Área' ? 'area' : 
                                    ($filtro[0] == 'Estado' ? 'estado_usuario' : 'rol')))) 
                                }}')">
                        </button>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de empleados (con paginación) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Lista de Empleados
                    @if(count($filtrosActivos) > 0)
                    <span class="badge bg-primary ms-2">Filtrados</span>
                    @endif
                </h5>
                <small class="text-muted">
                    @if($empleadosPaginated->total() > 0)
                    Mostrando {{ $empleadosPaginated->firstItem() ?? 0 }}-{{ $empleadosPaginated->lastItem() ?? 0 }} de {{ $empleadosPaginated->total() }} empleado(s)
                    @else
                    No hay empleados que mostrar
                    @endif
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">
                    Ordenado por: 
                    <span class="badge bg-light text-dark">
                        {{ 
                            request('sort_by', 'id') == 'id' ? 'ID' : 
                            (request('sort_by') == 'Nombre' ? 'Nombre' : 
                            (request('sort_by') == 'Cargo' ? 'Cargo' : 'Área')) 
                        }} 
                        <i class="fas fa-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
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
                            <th class="py-3">Empleado</th>
                            <th class="py-3">Contacto</th>
                            <th class="py-3">Información</th>
                            <th class="py-3">Cargo/Área</th>
                            <th class="py-3">Usuario</th>
                            <th class="text-end py-3 pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleadosPaginated as $empleado)
                        <tr class="align-middle">
                            <!-- Botón para expandir -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary btn-expand" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesEmpleado{{ $empleado->id }}" 
                                        aria-expanded="false"
                                        aria-controls="detallesEmpleado{{ $empleado->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user-tie text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}</h6>
                                        <small class="text-muted">ID: {{ $empleado->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    <span class="telefono">{{ $empleado->Telefono }}</span>
                                </div>
                                @if($empleado->usuario)
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <span class="small text-truncate">{{ $empleado->usuario->correo }}</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="mb-1">
                                    <small class="text-muted">Nacimiento:</small>
                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($empleado->Fecha_nacimiento)->format('d/m/Y') }}</div>
                                </div>
                                <div>
                                    <small class="text-muted">Sexo:</small>
                                    <div class="fw-medium">
                                        @if($empleado->Sexo == 'M')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                                Masculino
                                            </span>
                                        @elseif($empleado->Sexo == 'F')
                                            <span class="badge bg-pink bg-opacity-10 text-pink border border-pink border-opacity-25 px-2 py-1">
                                                Femenino
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                                                Otro
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                                        <i class="fas fa-briefcase me-1"></i>{{ $empleado->Cargo }}
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2">
                                        <i class="fas fa-layer-group me-1"></i>{{ $empleado->Area_trabajo }}
                                    </span>
                                </div>
                            </td>

                            <!-- Usuario asociado -->
                            @if($empleado->usuario)
                                <td>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <a href="mailto:{{ $empleado->usuario->correo }}" class="text-decoration-none text-truncate">
                                            {{ $empleado->usuario->correo }}
                                        </a>
                                    </div>
                                    <div>
                                        <span class="badge 
                                            @if($empleado->usuario->rol == 'Administración') 
                                                bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2
                                            @elseif($empleado->usuario->rol == 'Almacén') 
                                                bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2
                                            @elseif($empleado->usuario->rol == 'Logística') 
                                                bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2
                                            @else 
                                                bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2
                                            @endif">
                                            <i class="fas fa-user-tag me-1"></i>{{ $empleado->usuario->rol }}
                                        </span>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                                            <i class="fas fa-user-slash me-1"></i>Sin usuario
                                        </span>
                                    </div>
                                </td>
                            @endif

                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    
                                    <!-- Botón Editar -->
                                    <a href="{{ route('personal.edit', $empleado->id) }}" 
                                       class="btn btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar empleado">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Botón Eliminar Empleado (y usuario) -->
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteEmpleadoModal" 
                                            onclick="setDeleteEmpleado({{ $empleado->id }}, '{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}')"
                                            title="Eliminar empleado y su usuario">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    
                                    @if($empleado->usuario)
                                        <!-- Botón Eliminar solo usuario -->
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteUsuarioModal" 
                                                onclick="setDeleteUsuario({{ $empleado->usuario->id }}, '{{ $empleado->Nombre }} {{ $empleado->ApPaterno }}')"
                                                title="Eliminar solo el usuario">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    @else
                                        <!-- Botón para crear usuario -->
                                        <button type="button" 
                                                class="btn btn-outline-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#createUserModal"
                                                onclick="setCreateUsuario({{ $empleado->id }}, '{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}')"
                                                title="Crear usuario para este empleado">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles del empleado -->
                        <tr class="detalle-empleado-row">
                            <td colspan="7" class="p-0 border-0">
                                <div class="collapse" id="detallesEmpleado{{ $empleado->id }}">
                                    <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                        <div class="row">
                                            <!-- Información personal -->
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-3 text-primary">
                                                    <i class="fas fa-id-card me-2"></i>Información Personal
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Nombre completo:</label>
                                                        <div class="fw-medium">{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Fecha de nacimiento:</label>
                                                        <div class="fw-medium">{{ \Carbon\Carbon::parse($empleado->Fecha_nacimiento)->format('d/m/Y') }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Teléfono:</label>
                                                        <div class="fw-medium">{{ $empleado->Telefono }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Sexo:</label>
                                                        <div class="fw-medium">
                                                            @if($empleado->Sexo == 'M')
                                                                <span class="badge bg-info bg-opacity-10 text-info">Masculino</span>
                                                            @elseif($empleado->Sexo == 'F')
                                                                <span class="badge bg-pink bg-opacity-10 text-pink">Femenino</span>
                                                            @else
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Otro</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Información del usuario -->
                                                <h6 class="fw-bold mb-3 text-primary mt-4">
                                                    <i class="fas fa-user-circle me-2"></i>Información de Usuario
                                                </h6>
                                                
                                                @if($empleado->usuario)
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Correo de usuario:</label>
                                                        <div class="fw-medium">
                                                            <a href="mailto:{{ $empleado->usuario->correo }}" class="text-decoration-none">
                                                                {{ $empleado->usuario->correo }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Rol:</label>
                                                        <div class="fw-medium">
                                                            <span class="badge 
                                                                @if($empleado->usuario->rol == 'Administración') 
                                                                    bg-danger text-white px-3 py-1
                                                                @elseif($empleado->usuario->rol == 'Almacén') 
                                                                    bg-warning text-dark px-3 py-1
                                                                @elseif($empleado->usuario->rol == 'Logística') 
                                                                    bg-info text-white px-3 py-1
                                                                @else 
                                                                    bg-secondary text-white px-3 py-1
                                                                @endif">
                                                                <i class="fas fa-user-tag me-1"></i>{{ $empleado->usuario->rol }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Este empleado no tiene usuario asignado. No podrá acceder al sistema.
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Resumen laboral -->
                                            <div class="col-md-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="fas fa-briefcase me-2"></i>Información Laboral
                                                        </h6>
                                                        
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Cargo:</span>
                                                                <span class="fw-bold text-primary">{{ $empleado->Cargo }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Área:</span>
                                                                <span class="fw-bold">{{ $empleado->Area_trabajo }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">ID Empleado:</span>
                                                                <span class="fw-bold">#{{ $empleado->id }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="mt-3">
                                                            <h6 class="fw-bold mb-2 text-muted small">Estado del Sistema:</h6>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm 
                                                                    @if($empleado->usuario) 
                                                                        bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2
                                                                    @else
                                                                        bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2
                                                                    @endif">
                                                                    <i class="fas 
                                                                        @if($empleado->usuario) 
                                                                            fa-user-check text-success
                                                                        @else
                                                                            fa-user-slash text-warning
                                                                        @endif fa-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">
                                                                        @if($empleado->usuario)
                                                                            Usuario activo
                                                                        @else
                                                                            Sin acceso al sistema
                                                                        @endif
                                                                    </div>
                                                                    <small class="text-muted">
                                                                        @if($empleado->usuario)
                                                                            {{ $empleado->usuario->rol }}
                                                                        @else
                                                                            Requiere creación de usuario
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Botones de acción -->
                                                        <div class="mt-4">
                                                            <div class="d-grid gap-2">
                                                                
                                                                <a href="{{ route('personal.edit', $empleado->id) }}" 
                                                                   class="btn btn-outline-primary btn-sm">
                                                                    <i class="fas fa-edit me-1"></i> Editar Empleado
                                                                </a>
                                                                
                                                                <!-- Botón eliminar empleado (desde detalles) -->
                                                                <button type="button" 
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#deleteEmpleadoModal" 
                                                                        onclick="setDeleteEmpleado({{ $empleado->id }}, '{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}')">
                                                                    <i class="fas fa-trash me-1"></i> Eliminar Empleado
                                                                </button>
                                                                
                                                                @if($empleado->usuario)
                                                                <button type="button" 
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#deleteUsuarioModal" 
                                                                        onclick="setDeleteUsuario({{ $empleado->usuario->id }}, '{{ $empleado->Nombre }} {{ $empleado->ApPaterno }}')">
                                                                    <i class="fas fa-user-minus me-1"></i> Eliminar Usuario
                                                                </button>
                                                                @else
                                                                <button type="button" 
                                                                        class="btn btn-outline-success btn-sm"
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#createUserModal"
                                                                        onclick="setCreateUsuario({{ $empleado->id }}, '{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}')">
                                                                    <i class="fas fa-user-plus me-1"></i> Crear Usuario
                                                                </button>
                                                                @endif
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
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-user-lock fa-4x text-muted mb-4"></i>
                                    <h4 class="text-muted fw-bold mb-3">No hay empleados registrados</h4>
                                    <p class="text-muted mb-4">
                                        @if(count($filtrosActivos) > 0)
                                        No se encontraron empleados con los filtros aplicados.
                                        @else
                                        Comienza agregando el primer empleado al sistema.
                                        @endif
                                    </p>
                                    <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-redo me-1"></i> Limpiar Filtros
                                    </a>
                                    <a href="{{ route('personal.create') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Agregar Empleado
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
                    @if($empleadosPaginated->total() > 0)
                    Mostrando {{ $empleadosPaginated->firstItem() ?? 0 }}-{{ $empleadosPaginated->lastItem() ?? 0 }} de {{ $empleadosPaginated->total() }} empleado(s)
                    @else
                    No hay empleados que mostrar
                    @endif
                </div>
                @if($empleadosPaginated->hasPages())
                <div>
                    {{ $empleadosPaginated->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación de EMPLEADO (con usuario) -->
<div class="modal fade" id="deleteEmpleadoModal" tabindex="-1" aria-labelledby="deleteEmpleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white position-relative">
                <h5 class="modal-title fw-bold" id="deleteEmpleadoModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Eliminación de Empleado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                </div>
                <p class="fs-6">
                    Estás a punto de eliminar al empleado: 
                    <strong id="empleadoNombreEliminarCompleto"></strong>
                </p>
                <p class="text-danger small mb-0">
                    <strong>Esta acción es IRREVERSIBLE y eliminará:</strong>
                    <ul class="text-start text-danger small mt-2">
                        <li>Los datos del empleado</li>
                        <li>El usuario asociado (si existe)</li>
                        <li>Todos los registros relacionados</li>
                    </ul>
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <form id="deleteEmpleadoForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash me-1"></i> Eliminar Todo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación de USUARIO (solo usuario) -->
<div class="modal fade" id="deleteUsuarioModal" tabindex="-1" aria-labelledby="deleteUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark position-relative">
                <h5 class="modal-title fw-bold" id="deleteUsuarioModalLabel">
                    <i class="fas fa-user-times me-2"></i>
                    Confirmar Eliminación de Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-user-slash fa-4x text-warning mb-3"></i>
                </div>
                <p class="fs-6">
                    Estás a punto de eliminar el usuario del empleado: 
                    <strong id="empleadoNombreEliminar"></strong>
                </p>
                <p class="text-warning small mb-0">
                    <strong>Nota:</strong> Esta acción solo eliminará el acceso al sistema, 
                    pero <strong>NO</strong> eliminará los datos del empleado.
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <form id="deleteUsuarioForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="fas fa-user-minus me-1"></i> Eliminar Usuario
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white position-relative">
                <h5 class="modal-title fw-bold" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    Crear Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createUserForm" method="POST" action="{{ route('personal.usuario.store', ['id' => 'empleado_id_placeholder']) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Empleado:</label>
                        <div class="form-control bg-light border-0 fw-semibold" id="empleadoNombre" style="height: auto; min-height: 38px;">
                            <!-- El nombre se insertará aquí -->
                        </div>
                        <input type="hidden" id="empleadoId" name="empleado_id">
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico *</label>
                        <input type="email" class="form-control" id="correo" name="correo" 
                               placeholder="ejemplo@empresa.com" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label fw-semibold">Rol *</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccione un rol</option>
                            <option value="Administración">Administración</option>
                            <option value="Almacén">Almacén</option>
                            <option value="Logística">Logística</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contrasena" class="form-label fw-semibold">Contraseña *</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" 
                               placeholder="Mínimo 6 caracteres" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contrasena_confirmation" class="form-label fw-semibold">Confirmar Contraseña *</label>
                        <input type="password" class="form-control" id="contrasena_confirmation" 
                               name="contrasena_confirmation" placeholder="Repite la contraseña" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus me-1"></i> Crear Usuario
                    </button>
                </div>
            </form>
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

    // Auto-submit en ordenamiento
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filtrosForm').submit();
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
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (contrasena.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
            
            // Mostrar indicador de carga
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creando...';
            submitBtn.disabled = true;
        });
    }

    // Limpiar modal cuando se cierre
    const createUserModal = document.getElementById('createUserModal');
    if (createUserModal) {
        createUserModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('createUserForm');
            if (form) {
                form.reset();
            }
        });
    }
});

// Función para limpiar filtro individual
function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Función para el modal de eliminación de EMPLEADO (con usuario)
function setDeleteEmpleado(empleadoId, empleadoNombre) {
    document.getElementById('empleadoNombreEliminarCompleto').textContent = empleadoNombre;
    
    const deleteForm = document.getElementById('deleteEmpleadoForm');
    deleteForm.action = "{{ route('personal.destroy', ':id') }}".replace(':id', empleadoId);
}

// Función para el modal de eliminación de USUARIO (solo usuario)
function setDeleteUsuario(usuarioId, empleadoNombre) {
    document.getElementById('empleadoNombreEliminar').textContent = empleadoNombre;
    
    const deleteForm = document.getElementById('deleteUsuarioForm');
    deleteForm.action = "{{ route('personal.usuario.destroy', ':id') }}".replace(':id', usuarioId);
}

// Función para el modal de creación de usuario
function setCreateUsuario(empleadoId, empleadoNombre) {
    document.getElementById('empleadoNombre').textContent = empleadoNombre;
    document.getElementById('empleadoId').value = empleadoId;
    
    const createUserForm = document.getElementById('createUserForm');
    const actionUrl = "{{ route('personal.usuario.store', ':id') }}".replace(':id', empleadoId);
    createUserForm.action = actionUrl;
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

.bg-pink {
    background-color: #e83e8c !important;
}

.text-pink {
    color: #e83e8c !important;
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
.detalle-empleado-row {
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
    
    .detalle-empleado-row .row {
        flex-direction: column;
    }
    
    .detalle-empleado-row .col-md-8,
    .detalle-empleado-row .col-md-4 {
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
.detalle-empleado-row .card-body {
    padding: 1.5rem;
}

.detalle-empleado-row h6 {
    font-size: 0.95rem;
}

.detalle-empleado-row .table-sm th,
.detalle-empleado-row .table-sm td {
    padding: 0.5rem;
    font-size: 0.85rem;
}

/* Ajustes específicos para empleados */
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

.bg-warning.bg-opacity-10 {
    background-color: rgba(241, 196, 15, 0.1) !important;
}

.bg-danger.bg-opacity-10 {
    background-color: rgba(231, 76, 60, 0.1) !important;
}

/* Indicadores de filtros */
.alert-info .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* Hover en filas de detalles */
.detalle-empleado-row .table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.7);
}
</style>
@endsection