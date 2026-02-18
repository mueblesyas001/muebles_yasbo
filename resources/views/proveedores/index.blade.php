@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">Gestión de Proveedores</h1>
            <p class="text-muted mb-0">Administra el registro y seguimiento de todos los proveedores del sistema</p>
        </div>
        <div>
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Proveedor
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

    <!-- Estadísticas de proveedores -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Proveedores</h6>
                            <h3 class="mb-0">{{ $proveedoresFiltrados->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-truck text-primary fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        @if(request()->anyFilled(['id', 'nombre', 'empresa', 'correo', 'telefono']))
                        Con filtros aplicados
                        @else
                        Todos los proveedores
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
                            <h6 class="text-muted mb-2">Empresas</h6>
                            <h3 class="mb-0">{{ $proveedoresFiltrados->unique('Empresa_asociada')->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-building text-success fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Masculino</h6>
                            <h3 class="mb-0">
                                {{ $proveedoresFiltrados->where('Sexo', 'Masculino')->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-mars text-info fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Femenino</h6>
                            <h3 class="mb-0">
                                {{ $proveedoresFiltrados->where('Sexo', 'Femenino')->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-pink bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-venus text-pink fa-lg"></i>
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
            <form id="filtrosForm" method="GET" action="{{ route('proveedores.index') }}">
                <div class="row g-3">
                    <!-- ID de Proveedor -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-hashtag me-1"></i> ID de Proveedor
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
                                   aria-label="ID de Proveedor"
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

                    <!-- Empresa -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-building me-1"></i> Empresa
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-industry"></i>
                            </span>
                            <select class="form-select" name="empresa" aria-label="Empresa">
                                <option value="">Todas las empresas</option>
                                @foreach($empresasUnicas as $empresa)
                                <option value="{{ $empresa }}" {{ request('empresa') == $empresa ? 'selected' : '' }}>
                                    {{ $empresa }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('empresa'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('empresa')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-envelope me-1"></i> Correo
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-at"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="correo" 
                                   placeholder="Buscar por correo" 
                                   value="{{ request('correo') }}"
                                   aria-label="Correo">
                            @if(request('correo'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('correo')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-phone me-1"></i> Teléfono
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-mobile-alt"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="telefono" 
                                   placeholder="Buscar por teléfono" 
                                   value="{{ request('telefono') }}"
                                   aria-label="Teléfono">
                            @if(request('telefono'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('telefono')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Sexo -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-venus-mars me-1"></i> Sexo
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <select class="form-select" name="sexo" aria-label="Sexo">
                                <option value="">Todos</option>
                                <option value="Masculino" {{ request('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ request('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @if(request('sexo'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('sexo')">
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
                            <option value="Empresa_asociada" {{ request('sort_by') == 'Empresa_asociada' ? 'selected' : '' }}>Empresa</option>
                            <option value="Correo" {{ request('sort_by') == 'Correo' ? 'selected' : '' }}>Correo</option>
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
                                            return in_array($key, ['id', 'nombre', 'empresa', 'correo', 'telefono', 'sexo']) 
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
                                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">
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
        if(request('id')) $filtrosActivos[] = ['ID Proveedor', request('id')];
        if(request('nombre')) $filtrosActivos[] = ['Nombre', request('nombre')];
        if(request('empresa')) $filtrosActivos[] = ['Empresa', request('empresa')];
        if(request('correo')) $filtrosActivos[] = ['Correo', request('correo')];
        if(request('telefono')) $filtrosActivos[] = ['Teléfono', request('telefono')];
        if(request('sexo')) $filtrosActivos[] = ['Sexo', request('sexo')];
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
                                    $filtro[0] == 'ID Proveedor' ? 'id' : 
                                    ($filtro[0] == 'Nombre' ? 'nombre' : 
                                    ($filtro[0] == 'Empresa' ? 'empresa' : 
                                    ($filtro[0] == 'Correo' ? 'correo' : 
                                    ($filtro[0] == 'Teléfono' ? 'telefono' : 'sexo')))) 
                                }}')">
                        </button>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de proveedores (con paginación) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Lista de Proveedores
                    @if(count($filtrosActivos) > 0)
                    <span class="badge bg-primary ms-2">Filtrados</span>
                    @endif
                </h5>
                <small class="text-muted">
                    @if($proveedoresPaginated->total() > 0)
                    Mostrando {{ $proveedoresPaginated->firstItem() ?? 0 }}-{{ $proveedoresPaginated->lastItem() ?? 0 }} de {{ $proveedoresPaginated->total() }} proveedor(es)
                    @else
                    No hay proveedores que mostrar
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
                            (request('sort_by') == 'Empresa_asociada' ? 'Empresa' : 'Correo')) 
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
                            <th class="py-3">Proveedor</th>
                            <th class="py-3">Empresa</th>
                            <th class="py-3">Contacto</th>
                            <th class="py-3">Sexo</th>
                            <th class="text-end py-3 pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedoresPaginated as $proveedor)
                        <tr class="align-middle">
                            <!-- Botón para expandir -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary btn-expand" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesProveedor{{ $proveedor->id }}" 
                                        aria-expanded="false"
                                        aria-controls="detallesProveedor{{ $proveedor->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user-tie text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno ?? '' }}</h6>
                                        <small class="text-muted">ID: {{ $proveedor->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-building text-info"></i>
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ Str::limit($proveedor->Empresa_asociada, 30) }}</span>
                                        <br>
                                        <small class="text-muted">Empresa asociada</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <a href="mailto:{{ $proveedor->Correo }}" class="text-decoration-none text-truncate">
                                        {{ $proveedor->Correo }}
                                    </a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    <a href="tel:{{ $proveedor->Telefono }}" class="text-decoration-none">
                                        {{ $proveedor->Telefono }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $proveedor->Sexo == 'Masculino' ? 'bg-primary' : 'bg-pink' }} bg-opacity-10 text-{{ $proveedor->Sexo == 'Masculino' ? 'primary' : 'pink' }} border border-{{ $proveedor->Sexo == 'Masculino' ? 'primary' : 'pink' }} border-opacity-25 px-3 py-2">
                                    <i class="fas {{ $proveedor->Sexo == 'Masculino' ? 'fa-mars' : 'fa-venus' }} me-1"></i>
                                    {{ $proveedor->Sexo }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                       class="btn btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar proveedor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            onclick="setDeleteProveedor({{ $proveedor->id }}, '{{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }}')"
                                            title="Eliminar proveedor">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles del proveedor -->
                        <tr class="detalle-proveedor-row">
                            <td colspan="6" class="p-0 border-0">
                                <div class="collapse" id="detallesProveedor{{ $proveedor->id }}">
                                    <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                        <div class="row">
                                            <!-- Información del proveedor -->
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-3 text-primary">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Información Completa del Proveedor
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Nombre completo:</label>
                                                        <div class="fw-medium">{{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno ?? '' }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">ID de Proveedor:</label>
                                                        <div class="fw-medium">#{{ $proveedor->id }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Empresa asociada:</label>
                                                        <div class="fw-medium">{{ $proveedor->Empresa_asociada }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Sexo:</label>
                                                        <div class="fw-medium">
                                                            <span class="badge {{ $proveedor->Sexo == 'Masculino' ? 'bg-primary' : 'bg-pink' }} px-3 py-1">
                                                                <i class="fas {{ $proveedor->Sexo == 'Masculino' ? 'fa-mars' : 'fa-venus' }} me-1"></i>
                                                                {{ $proveedor->Sexo }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Correo electrónico:</label>
                                                        <div class="fw-medium">
                                                            <a href="mailto:{{ $proveedor->Correo }}" class="text-decoration-none">
                                                                {{ $proveedor->Correo }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted small">Teléfono:</label>
                                                        <div class="fw-medium">
                                                            <a href="tel:{{ $proveedor->Telefono }}" class="text-decoration-none">
                                                                {{ $proveedor->Telefono }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Compras asociadas (si las hay) -->
                                                @if($proveedor->compras && $proveedor->compras->count() > 0)
                                                <h6 class="fw-bold mb-3 text-primary mt-4">
                                                    <i class="fas fa-shopping-cart me-2"></i>
                                                    Compras Realizadas
                                                </h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless">
                                                        <thead>
                                                            <tr class="text-muted small">
                                                                <th>ID Compra</th>
                                                                <th class="text-center">Fecha</th>
                                                                <th class="text-end">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($proveedor->compras->take(5) as $compra)
                                                            <tr class="border-bottom border-light">
                                                                <td>
                                                                    <span class="fw-medium">Compra #{{ $compra->id }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y') }}
                                                                </td>
                                                                <td class="text-end fw-bold text-success">
                                                                    ${{ number_format($compra->Total, 2) }}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @if($proveedor->compras->count() > 5)
                                                            <tr>
                                                                <td colspan="3" class="text-center py-2">
                                                                    <small class="text-muted">
                                                                        Y {{ $proveedor->compras->count() - 5 }} compra(s) más...
                                                                    </small>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @else
                                                <div class="alert alert-info mt-4">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Este proveedor no tiene compras registradas en el sistema.
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Resumen y acciones -->
                                            <div class="col-md-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="fas fa-chart-pie me-2"></i>Resumen
                                                        </h6>
                                                        
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">ID Proveedor:</span>
                                                                <span class="fw-bold">#{{ $proveedor->id }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Compras realizadas:</span>
                                                                <span class="fw-bold">{{ $proveedor->compras->count() ?? 0 }}</span>
                                                            </div>
                                                            
                                                            @if($proveedor->compras && $proveedor->compras->count() > 0)
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Total comprado:</span>
                                                                <span class="fw-bold text-success">
                                                                    ${{ number_format($proveedor->compras->sum('Total'), 2) }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="mt-3">
                                                            <h6 class="fw-bold mb-2 text-muted small">Información de Contacto:</h6>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <i class="fas fa-envelope text-info fa-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $proveedor->Correo }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <i class="fas fa-phone text-secondary fa-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $proveedor->Telefono }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Botones de acción -->
                                                        <div class="mt-4">
                                                            <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                                               class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                                <i class="fas fa-edit me-1"></i> Editar Proveedor
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm w-100"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#deleteModal" 
                                                                    onclick="setDeleteProveedor({{ $proveedor->id }}, '{{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }}')">
                                                                <i class="fas fa-trash me-1"></i> Eliminar Proveedor
                                                            </button>
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
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-truck fa-4x text-muted mb-4"></i>
                                    <h4 class="text-muted fw-bold mb-3">No hay proveedores registrados</h4>
                                    <p class="text-muted mb-4">
                                        @if(count($filtrosActivos) > 0)
                                        No se encontraron proveedores con los filtros aplicados.
                                        @else
                                        Comienza registrando el primer proveedor en el sistema.
                                        @endif
                                    </p>
                                    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-redo me-1"></i> Limpiar Filtros
                                    </a>
                                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i> Registrar Proveedor
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
                    @if($proveedoresPaginated->total() > 0)
                    Mostrando {{ $proveedoresPaginated->firstItem() ?? 0 }}-{{ $proveedoresPaginated->lastItem() ?? 0 }} de {{ $proveedoresPaginated->total() }} proveedor(es)
                    @else
                    No hay proveedores que mostrar
                    @endif
                </div>
                @if($proveedoresPaginated->hasPages())
                <div>
                    {{ $proveedoresPaginated->appends(request()->query())->links() }}
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
                    Estás a punto de eliminar al proveedor <strong id="deleteProveedorNombre"></strong>
                </p>
                <p class="text-danger small mb-0">
                    <strong>Esta acción no se puede deshacer.</strong><br>
                    Se eliminarán todos los datos asociados a este proveedor.
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

// Función para el modal de eliminación
function setDeleteProveedor(proveedorId, proveedorNombre) {
    document.getElementById('deleteProveedorNombre').textContent = proveedorNombre;
    
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = "{{ route('proveedores.destroy', ':id') }}".replace(':id', proveedorId);
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
.detalle-proveedor-row {
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

/* Colores personalizados */
.bg-pink {
    background-color: #e83e8c !important;
}

.text-pink {
    color: #e83e8c !important;
}

.bg-pink.bg-opacity-10 {
    background-color: rgba(232, 62, 140, 0.1) !important;
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
    
    .detalle-proveedor-row .row {
        flex-direction: column;
    }
    
    .detalle-proveedor-row .col-md-8,
    .detalle-proveedor-row .col-md-4 {
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
.detalle-proveedor-row .card-body {
    padding: 1.5rem;
}

.detalle-proveedor-row h6 {
    font-size: 0.95rem;
}

.detalle-proveedor-row .table-sm th,
.detalle-proveedor-row .table-sm td {
    padding: 0.5rem;
    font-size: 0.85rem;
}

/* Ajustes específicos para proveedores */
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
.detalle-proveedor-row .table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.7);
}
</style>
@endsection