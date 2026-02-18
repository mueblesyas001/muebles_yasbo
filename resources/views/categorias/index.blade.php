@extends('layouts.app')

@section('content')
<div id="categorias-page" class="container-fluid">
    <!-- Encabezado Principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">Gestión de Categorías</h1>
            <p class="text-muted mb-0">Administra las categorías de productos del sistema</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="refreshData" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('categorias.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i> Nueva Categoría
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
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
                            <h6 class="text-muted mb-2">Total Categorías</h6>
                            <h3 class="mb-0">{{ $categorias->count() }}</h3>
                        </div>
                        <div class="categoria-avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-tags text-primary fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Con Proveedor</h6>
                            <h3 class="mb-0 text-success">
                                {{ $categorias->whereNotNull('Proveedor_idProveedor')->count() }}
                            </h3>
                        </div>
                        <div class="categoria-avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-truck text-success fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Con Productos</h6>
                            <h3 class="mb-0 text-info">
                                {{ $categorias->where('productos_count', '>', 0)->count() }}
                            </h3>
                        </div>
                        <div class="categoria-avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-boxes text-info fa-lg"></i>
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
                            <h6 class="text-muted mb-2">Sin Productos</h6>
                            <h3 class="mb-0 text-warning">
                                {{ $categorias->where('productos_count', 0)->count() }}
                            </h3>
                        </div>
                        <div class="categoria-avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-box-open text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Búsqueda y Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-search me-2 text-primary"></i>
                Búsqueda y Filtros
            </h5>
        </div>
        <div class="card-body">
            <form id="filtrosForm" method="GET" action="{{ route('categorias.index') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">
                            <i class="fas fa-search me-1"></i> Buscar Categoría
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control" 
                                   name="search"
                                   placeholder="Nombre o descripción..."
                                   value="{{ request('search') }}"
                                   aria-label="Buscar categoría">
                            @if(request('search'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('search')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-truck me-1"></i> Proveedor
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <select id="filterProveedor" class="form-select" name="proveedor_id" aria-label="Proveedor">
                                <option value="">Todos los proveedores</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idProveedor }}" {{ request('proveedor_id') == $proveedor->idProveedor ? 'selected' : '' }}>
                                        {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('proveedor_id'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('proveedor_id')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort me-1"></i> Ordenar por
                        </label>
                        <select id="sortBy" class="form-select" name="sort_by" aria-label="Ordenar por">
                            <option value="Nombre" {{ request('sort_by', 'Nombre') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="productos_count" {{ request('sort_by') == 'productos_count' ? 'selected' : '' }}>Productos</option>
                            <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort-amount-down me-1"></i> Dirección
                        </label>
                        <select id="sortOrder" class="form-select" name="sort_order" aria-label="Dirección orden">
                            <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                            <div class="text-muted small">
                                @php
                                    $filtrosActivos = collect(request()->all())
                                        ->filter(function($value, $key) {
                                            return in_array($key, ['search', 'proveedor_id']) && !empty($value);
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
                                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
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
        if(request('search')) $filtrosActivos[] = ['Búsqueda', request('search')];
        if(request('proveedor_id')) {
            $proveedor = $proveedores->firstWhere('idProveedor', request('proveedor_id'));
            $filtrosActivos[] = ['Proveedor', $proveedor ? ($proveedor->Nombre ?? '') . ' ' . ($proveedor->ApPaterno ?? '') : 'No encontrado'];
        }
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
                                onclick="clearFilter('{{ $filtro[0] == 'Búsqueda' ? 'search' : 'proveedor_id' }}')">
                        </button>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de categorías -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Lista de Categorías
                    @if(count($filtrosActivos) > 0)
                    <span class="badge bg-primary ms-2">Filtradas</span>
                    @endif
                </h5>
                <small class="text-muted">
                    <span id="totalCount">{{ $categorias->count() }}</span> categoría(s) registrada(s)
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">
                    Ordenado por: 
                    <span class="badge bg-light text-dark" id="sortDisplay">
                        {{ 
                            request('sort_by', 'Nombre') == 'Nombre' ? 'Nombre' : 
                            (request('sort_by') == 'productos_count' ? 'Productos' : 'ID')
                        }} 
                        <i class="fas fa-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="categoriasTable">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3" width="60px"></th>
                            <th class="py-3">Categoría</th>
                            <th class="py-3">Descripción</th>
                            <th class="py-3">Proveedor</th>
                            <th class="py-3">Productos</th>
                            <th class="text-end py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                        @php
                            $nombreProveedor = $categoria->proveedor ? 
                                $categoria->proveedor->Nombre . ' ' . $categoria->proveedor->ApPaterno : 
                                'Sin proveedor';
                        @endphp
                        <tr class="align-middle categoria-row" 
                            data-nombre="{{ strtolower($categoria->Nombre) }}" 
                            data-descripcion="{{ strtolower($categoria->Descripcion ?? '') }}"
                            data-proveedor-id="{{ $categoria->Proveedor_idProveedor ?? '0' }}"
                            data-productos="{{ $categoria->productos_count ?? 0 }}">
                            <!-- Botón para expandir -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary btn-expand-categoria" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesCategoria{{ $categoria->id }}" 
                                        aria-expanded="false"
                                        aria-controls="detallesCategoria{{ $categoria->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="categoria-avatar categoria-avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-tags text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $categoria->Nombre }}</h6>
                                        <small class="text-muted">
                                            ID: #{{ $categoria->id }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($categoria->Descripcion)
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                          data-bs-toggle="tooltip" title="{{ $categoria->Descripcion }}">
                                        {{ $categoria->Descripcion }}
                                    </span>
                                @else
                                    <span class="text-muted">Sin descripción</span>
                                @endif
                            </td>
                            <td>
                                @if($categoria->proveedor)
                                    <div class="d-flex align-items-center">
                                        <div class="categoria-avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-truck text-info fa-sm"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $nombreProveedor }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">
                                        Sin proveedor
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(($categoria->productos_count ?? 0) > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="fas fa-boxes me-1"></i>{{ $categoria->productos_count ?? 0 }} productos
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="fas fa-box-open me-1"></i>Sin productos
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" 
                                       class="btn btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar categoría">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            onclick="setDeleteCategoria({{ $categoria->id }}, '{{ addslashes($categoria->Nombre) }}', {{ $categoria->productos_count ?? 0 }})"
                                            title="Eliminar categoría">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles de la categoría -->
                        <tr class="detalle-categoria-row">
                            <td colspan="6" class="p-0 border-0">
                                <div class="collapse" id="detallesCategoria{{ $categoria->id }}">
                                    <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                        <!-- Advertencia si tiene productos -->
                                        @if(($categoria->productos_count ?? 0) > 0)
                                        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="alert-heading mb-1">Categoría con Productos</h6>
                                                    <p class="mb-0">
                                                        Esta categoría tiene {{ $categoria->productos_count ?? 0 }} productos asociados. 
                                                        Si la eliminas, estos productos quedarán sin categoría.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="row">
                                            <!-- Información detallada -->
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-3 text-primary">
                                                    <i class="fas fa-info-circle me-2"></i>Información de la Categoría
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card bg-white border-0 shadow-sm h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-muted small mb-3">Información Básica</h6>
                                                                <div class="mb-2">
                                                                    <small class="text-muted d-block">Nombre:</small>
                                                                    <span class="fw-semibold">{{ $categoria->Nombre }}</span>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted d-block">ID Categoría:</small>
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary">#{{ $categoria->id }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card bg-white border-0 shadow-sm h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-muted small mb-3">Estadísticas</h6>
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Total Productos:</small>
                                                                    <span class="badge {{ ($categoria->productos_count ?? 0) > 0 ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ ($categoria->productos_count ?? 0) > 0 ? 'text-success' : 'text-secondary' }}">
                                                                        <i class="fas fa-boxes me-1"></i>{{ $categoria->productos_count ?? 0 }} productos
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">Estado:</small>
                                                                    <span class="badge bg-success bg-opacity-10 text-success">
                                                                        Activa
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card bg-white border-0 shadow-sm">
                                                            <div class="card-body">
                                                                <h6 class="text-muted small mb-3">Descripción</h6>
                                                                @if($categoria->Descripcion)
                                                                <p class="mb-0">{{ $categoria->Descripcion }}</p>
                                                                @else
                                                                <p class="mb-0 text-muted">No se ha registrado una descripción</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Información del proveedor y acciones -->
                                            <div class="col-md-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="fas fa-truck me-2"></i>Proveedor Asociado
                                                        </h6>
                                                        
                                                        @if($categoria->proveedor)
                                                        <div class="mb-3">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="categoria-avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <i class="fas fa-truck text-info fa-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $nombreProveedor }}</div>
                                                                    <small class="text-muted">
                                                                        ID: #{{ $categoria->proveedor->idProveedor }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-2">
                                                                <small class="text-muted d-block">Teléfono:</small>
                                                                <span class="fw-medium">{{ $categoria->proveedor->Telefono ?? 'No disponible' }}</span>
                                                            </div>
                                                            
                                                            <div>
                                                                <small class="text-muted d-block">Correo:</small>
                                                                <span class="fw-medium">{{ $categoria->proveedor->Correo ?? 'No disponible' }}</span>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="alert alert-warning mb-3">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Esta categoría no tiene proveedor asignado.
                                                        </div>
                                                        @endif
                                                        
                                                        <hr>
                                                        
                                                        <!-- Botones de acción -->
                                                        <div class="mt-4">
                                                            <a href="{{ route('categorias.edit', $categoria->id) }}" 
                                                               class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                                                <i class="fas fa-edit me-1"></i> Editar categoría
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm w-100"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#deleteModal" 
                                                                    onclick="setDeleteCategoria({{ $categoria->id }}, '{{ addslashes($categoria->Nombre) }}', {{ $categoria->productos_count ?? 0 }})">
                                                                <i class="fas fa-trash me-1"></i> Eliminar categoría
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
                                    <i class="fas fa-tags fa-4x text-muted mb-4"></i>
                                    @if(count($filtrosActivos) > 0)
                                    <h4 class="text-muted fw-bold mb-3">No se encontraron categorías</h4>
                                    <p class="text-muted mb-4">
                                        No hay categorías que coincidan con los filtros aplicados.
                                    </p>
                                    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-redo me-1"></i> Limpiar Filtros
                                    </a>
                                    @else
                                    <h4 class="text-muted fw-bold mb-3">No hay categorías registradas</h4>
                                    <p class="text-muted mb-4">
                                        Comienza registrando la primera categoría en el sistema.
                                    </p>
                                    @endif
                                    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> Registrar Categoría
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
                    Mostrando {{ $categorias->count() }} de {{ $categorias->count() }} categoría(s)
                </div>
                <div class="text-muted small">
                    @if(request('sort_by') == 'productos_count')
                        Ordenadas por: <strong>Número de Productos</strong>
                    @elseif(request('sort_by') == 'id')
                        Ordenadas por: <strong>ID</strong>
                    @else
                        Ordenadas por: <strong>Nombre</strong>
                    @endif
                </div>
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
                    Estás a punto de eliminar la categoría <strong id="deleteCategoriaNombre"></strong>
                </p>
                <div class="alert alert-warning mt-3 mb-0" id="productosWarning" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="productosCountText"></span> productos asociados. Estos productos quedarán sin categoría.
                </div>
                <p class="text-danger small mt-3 mb-0">
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
    document.querySelectorAll('.btn-expand-categoria').forEach(button => {
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
    document.querySelectorAll('.btn-expand-categoria').forEach(button => {
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

    // Botón de refrescar
    document.getElementById('refreshData')?.addEventListener('click', function() {
        this.classList.add('spin');
        setTimeout(() => {
            location.reload();
        }, 500);
    });
});

// Función para limpiar filtro individual
function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// CORRECCIÓN: Función para el modal de eliminación de categorías
function setDeleteCategoria(categoriaId, nombreCategoria, productosCount) {
    try {
        // Actualizar el nombre en el modal
        const nombreElement = document.getElementById('deleteCategoriaNombre');
        if (nombreElement) {
            nombreElement.textContent = nombreCategoria;
        }
        
        // Manejar la advertencia de productos asociados
        const productosWarning = document.getElementById('productosWarning');
        const productosCountText = document.getElementById('productosCountText');
        
        if (productosWarning && productosCountText) {
            if (productosCount > 0) {
                productosWarning.style.display = 'block';
                productosCountText.textContent = 'Esta categoría tiene ' + productosCount + ' productos asociados.';
            } else {
                productosWarning.style.display = 'none';
            }
        }
        
        // Obtener el formulario
        const deleteForm = document.getElementById('deleteForm');
        
        if (!deleteForm) {
            console.error('Formulario de eliminación no encontrado');
            alert('Error: Formulario no encontrado');
            return;
        }
        
        // Construir la URL de eliminación
        // Método 1: Usando route() de Laravel (RECOMENDADO)
        const actionUrl = "{{ route('categorias.destroy', ':id') }}".replace(':id', categoriaId);
        
        console.log('URL de eliminación:', actionUrl); // Para depuración
        
        // Asignar la acción al formulario
        deleteForm.action = actionUrl;
        
        // Mostrar el modal
        const deleteModalElement = document.getElementById('deleteModal');
        if (deleteModalElement) {
            const deleteModal = new bootstrap.Modal(deleteModalElement);
            deleteModal.show();
        } else {
            console.error('Modal de eliminación no encontrado');
            alert('Error: Modal no encontrado');
        }
        
    } catch (error) {
        console.error('Error en setDeleteCategoria:', error);
        alert('Error al preparar la eliminación. Por favor, recarga la página.');
    }
}

// Animación de spin para el botón de refrescar
const spinStyle = document.createElement('style');
spinStyle.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spin {
        animation: spin 0.5s linear infinite;
    }
`;
document.head.appendChild(spinStyle);
</script>
@endpush

<style>
#categorias-page {
    padding-top: 20px;
}

#categorias-page .categoria-avatar {
    width: 48px;
    height: 48px;
}

#categorias-page .categoria-avatar-md {
    width: 40px;
    height: 40px;
}

#categorias-page .categoria-avatar-sm {
    width: 36px;
    height: 36px;
}

#categorias-page .categoria-avatar-xs {
    width: 24px;
    height: 24px;
    font-size: 0.7rem;
}

#categorias-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
}

#categorias-page .table tbody tr {
    transition: all 0.2s ease;
}

#categorias-page .table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

#categorias-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#categorias-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#categorias-page .card {
    border-radius: 12px;
}

#categorias-page .shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

#categorias-page .fw-semibold {
    font-weight: 600;
}

/* Estilos para el botón expandir */
#categorias-page .btn-expand-categoria {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 6px !important;
}

#categorias-page .btn-expand-categoria:hover {
    transform: scale(1.1);
}

#categorias-page .btn-expand-categoria i {
    transition: transform 0.3s ease;
}

/* Estilos para la fila expandible */
#categorias-page .detalle-categoria-row {
    background-color: #f8fafc;
}

#categorias-page .collapse {
    transition: all 0.3s ease;
}

#categorias-page .collapsing {
    transition: height 0.35s ease;
}

/* Estilos para búsqueda */
#categorias-page .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    #categorias-page .btn-expand-categoria {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #categorias-page .categoria-avatar, 
    #categorias-page .categoria-avatar-md, 
    #categorias-page .categoria-avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    #categorias-page .categoria-avatar-xs {
        width: 20px;
        height: 20px;
        font-size: 0.6rem;
    }
    
    #categorias-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #categorias-page .detalle-categoria-row .row {
        flex-direction: column;
    }
    
    #categorias-page .detalle-categoria-row .col-md-8,
    #categorias-page .detalle-categoria-row .col-md-4 {
        width: 100% !important;
        margin-bottom: 1rem;
    }
    
    #categorias-page .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Filtros responsive */
    #categorias-page .card-body .row > div {
        margin-bottom: 1rem;
    }
}

/* Mejorar la legibilidad de los detalles */
#categorias-page .detalle-categoria-row .card-body {
    padding: 1.5rem;
}

#categorias-page .detalle-categoria-row h6 {
    font-size: 0.95rem;
}

/* Animación suave para expandir */
#categorias-page .collapse.show {
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

/* Tooltips para descripciones truncadas */
#categorias-page .text-truncate[data-bs-toggle="tooltip"] {
    cursor: help;
}

/* Badges específicos para categorías */
#categorias-page .badge-warning {
    background-color: rgba(255, 193, 7, 0.1);
    color: #664d03;
    border-color: rgba(255, 193, 7, 0.3);
}

/* Alertas específicas */
#categorias-page .alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-color: rgba(255, 193, 7, 0.3);
    color: #664d03;
}

#categorias-page .alert-warning .alert-heading {
    color: #664d03;
    font-weight: 600;
}

#categorias-page .alert-warning i {
    color: #ffc107;
}
</style>
@endsection