@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">Gestión de Productos</h1>
            <p class="text-muted mb-0">Administra el inventario y catálogo de productos del sistema</p>
        </div>
        <div>
            <a href="{{ route('productos.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Producto
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

    <!-- Estadísticas de productos (con filtros aplicados) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Productos Filtrados</h6>
                            <h3 class="mb-0">{{ $productosFiltrados->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-boxes text-primary fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        @if(request()->anyFilled(['id', 'nombre', 'categoria_id', 'precio_min', 'precio_max', 'stock_min', 'stock_max']))
                        Con filtros aplicados
                        @else
                        Todos los productos
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
                            <h6 class="text-muted mb-2">En Stock</h6>
                            <h3 class="mb-0 text-success">
                                {{ $productosFiltrados->where('Cantidad', '>', 0)->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $productosFiltrados->where('Cantidad', '>', 0)->sum('Cantidad') }} unidades
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Agotados</h6>
                            <h3 class="mb-0 text-danger">
                                {{ $productosFiltrados->where('Cantidad', '=', 0)->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        Requieren reposición
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Bajo Stock</h6>
                            <h3 class="mb-0 text-warning">
                                {{ $productosFiltrados->where('Cantidad', '<=', DB::raw('Cantidad_minima'))->where('Cantidad', '>', 0)->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        Alerta de inventario
                    </small>
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
            <form id="filtrosForm" method="GET" action="{{ route('productos.index') }}">
                <div class="row g-3">
                    <!-- ID de Producto -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">
                            <i class="fas fa-hashtag me-1"></i> ID Producto
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
                                   aria-label="ID Producto"
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

                    <!-- Nombre del Producto -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-tag me-1"></i> Nombre
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre" 
                                   placeholder="Buscar producto..." 
                                   value="{{ request('nombre') }}"
                                   aria-label="Nombre producto">
                            @if(request('nombre'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('nombre')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-folder me-1"></i> Categoría
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-list"></i>
                            </span>
                            <select class="form-select" name="categoria_id" aria-label="Categoría">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->Nombre }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('categoria_id'))
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="clearFilter('categoria_id')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Rango de Precio -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-dollar-sign me-1"></i> Precio
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="precio_min" 
                                           placeholder="Mínimo" 
                                           value="{{ request('precio_min') }}"
                                           step="0.01"
                                           min="0"
                                           aria-label="Precio mínimo">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="precio_max" 
                                           placeholder="Máximo" 
                                           value="{{ request('precio_max') }}"
                                           step="0.01"
                                           min="0"
                                           aria-label="Precio máximo">
                                    @if(request('precio_min') || request('precio_max'))
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="clearFilters(['precio_min', 'precio_max'])">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rango de Stock -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-boxes me-1"></i> Stock Disponible
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-arrow-up"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="stock_min" 
                                           placeholder="Mínimo" 
                                           value="{{ request('stock_min') }}"
                                           min="0"
                                           aria-label="Stock mínimo">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-arrow-down"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="stock_max" 
                                           placeholder="Máximo" 
                                           value="{{ request('stock_max') }}"
                                           min="0"
                                           aria-label="Stock máximo">
                                    @if(request('stock_min') || request('stock_max'))
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="clearFilters(['stock_min', 'stock_max'])">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Stock -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="fas fa-chart-bar me-1"></i> Estado de Stock
                        </label>
                        <select class="form-select" name="estado_stock" aria-label="Estado stock">
                            <option value="">Todos los estados</option>
                            <option value="en_stock" {{ request('estado_stock') == 'en_stock' ? 'selected' : '' }}>En Stock</option>
                            <option value="agotado" {{ request('estado_stock') == 'agotado' ? 'selected' : '' }}>Agotado</option>
                            <option value="bajo_stock" {{ request('estado_stock') == 'bajo_stock' ? 'selected' : '' }}>Bajo Stock</option>
                        </select>
                    </div>

                    <!-- Ordenamiento -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort me-1"></i> Ordenar por
                        </label>
                        <select class="form-select" name="sort_by" aria-label="Ordenar por">
                            <option value="Nombre" {{ request('sort_by', 'Nombre') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="Precio" {{ request('sort_by') == 'Precio' ? 'selected' : '' }}>Precio</option>
                            <option value="Cantidad" {{ request('sort_by') == 'Cantidad' ? 'selected' : '' }}>Stock</option>
                            <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        </select>
                    </div>

                    <!-- Dirección de orden -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="fas fa-sort-amount-down me-1"></i> Dirección
                        </label>
                        <select class="form-select" name="sort_order" aria-label="Dirección orden">
                            <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                            <div class="text-muted small">
                                @php
                                    $filtrosActivos = collect(request()->all())
                                        ->filter(function($value, $key) {
                                            return in_array($key, ['id', 'nombre', 'categoria_id', 'precio_min', 'precio_max', 'stock_min', 'stock_max', 'estado_stock']) 
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
                                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
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
        if(request('id')) $filtrosActivos[] = ['ID Producto', request('id')];
        if(request('nombre')) $filtrosActivos[] = ['Nombre', request('nombre')];
        if(request('categoria_id')) {
            $categoria = $categorias->firstWhere('id', request('categoria_id'));
            $filtrosActivos[] = ['Categoría', $categoria ? $categoria->Nombre : 'No encontrada'];
        }
        if(request('precio_min')) $filtrosActivos[] = ['Precio Mín', '$' . number_format(request('precio_min'), 2)];
        if(request('precio_max')) $filtrosActivos[] = ['Precio Máx', '$' . number_format(request('precio_max'), 2)];
        if(request('stock_min')) $filtrosActivos[] = ['Stock Mín', request('stock_min')];
        if(request('stock_max')) $filtrosActivos[] = ['Stock Máx', request('stock_max')];
        if(request('estado_stock')) {
            $estados = [
                'en_stock' => 'En Stock',
                'agotado' => 'Agotado',
                'bajo_stock' => 'Bajo Stock'
            ];
            $filtrosActivos[] = ['Estado', $estados[request('estado_stock')] ?? request('estado_stock')];
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
                                onclick="clearFilter('{{ 
                                    $filtro[0] == 'ID Producto' ? 'id' : 
                                    ($filtro[0] == 'Nombre' ? 'nombre' : 
                                    ($filtro[0] == 'Categoría' ? 'categoria_id' : 
                                    ($filtro[0] == 'Precio Mín' ? 'precio_min' : 
                                    ($filtro[0] == 'Precio Máx' ? 'precio_max' : 
                                    ($filtro[0] == 'Stock Mín' ? 'stock_min' : 
                                    ($filtro[0] == 'Stock Máx' ? 'stock_max' : 'estado_stock')))))) 
                                }}')">
                        </button>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de productos (con paginación) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-boxes me-2 text-primary"></i>
                    Catálogo de Productos
                    @if(count($filtrosActivos) > 0)
                    <span class="badge bg-primary ms-2">Filtrados</span>
                    @endif
                </h5>
                <small class="text-muted">
                    @if($productosPaginated->total() > 0)
                    Mostrando {{ $productosPaginated->firstItem() ?? 0 }}-{{ $productosPaginated->lastItem() ?? 0 }} de {{ $productosPaginated->total() }} producto(s)
                    @else
                    No hay productos que mostrar
                    @endif
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">
                    Ordenado por: 
                    <span class="badge bg-light text-dark">
                        {{ 
                            request('sort_by', 'Nombre') == 'Nombre' ? 'Nombre' : 
                            (request('sort_by') == 'Precio' ? 'Precio' : 
                            (request('sort_by') == 'Cantidad' ? 'Stock' : 'ID')) 
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
                            <th class="py-3">Producto</th>
                            <th class="py-3">Categoría</th>
                            <th class="py-3">Precio</th>
                            <th class="py-3">Stock</th>
                            <th class="py-3">Estado</th>
                            <th class="text-end py-3 pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosPaginated as $producto)
                        @php
                            $categoriaNombre = $producto->categoria ? $producto->categoria->Nombre : 'Sin categoría';
                            
                            // Determinar estado del stock
                            $estadoStock = '';
                            $estadoColor = '';
                            $estadoIcon = '';
                            
                            if($producto->Cantidad == 0) {
                                $estadoStock = 'Agotado';
                                $estadoColor = 'danger';
                                $estadoIcon = 'fa-times-circle';
                            } elseif($producto->Cantidad <= $producto->Cantidad_minima) {
                                $estadoStock = 'Bajo Stock';
                                $estadoColor = 'warning';
                                $estadoIcon = 'fa-exclamation-triangle';
                            } else {
                                $estadoStock = 'En Stock';
                                $estadoColor = 'success';
                                $estadoIcon = 'fa-check-circle';
                            }
                            
                            // Calcular porcentaje de stock
                            $porcentajeStock = $producto->Cantidad_maxima > 0 ? 
                                min(100, ($producto->Cantidad / $producto->Cantidad_maxima) * 100) : 0;
                        @endphp
                        <tr class="align-middle">
                            <!-- Botón para expandir -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary btn-expand" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesProducto{{ $producto->id }}" 
                                        aria-expanded="false"
                                        aria-controls="detallesProducto{{ $producto->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-box text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $producto->Nombre }}</h6>
                                        <small class="text-muted">
                                            ID: {{ $producto->id }}
                                        </small>
                                        @if($producto->Descripcion)
                                        <div class="small text-muted mt-1">
                                            {{ Str::limit($producto->Descripcion, 50) }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-folder text-secondary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $categoriaNombre }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-dollar-sign text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">${{ number_format($producto->Precio, 2) }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $estadoColor }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $porcentajeStock }}%"
                                                 aria-valuenow="{{ $producto->Cantidad }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $producto->Cantidad_maxima }}">
                                            </div>
                                        </div>
                                        <div class="ms-2 fw-bold">{{ $producto->Cantidad }}</div>
                                    </div>
                                    <small class="text-muted">
                                        Min: {{ $producto->Cantidad_minima }} | Max: {{ $producto->Cantidad_maxima }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $estadoColor }} bg-opacity-10 text-{{ $estadoColor }} border border-{{ $estadoColor }} border-opacity-25 px-3 py-2">
                                    <i class="fas {{ $estadoIcon }} me-1"></i>{{ $estadoStock }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('productos.edit', $producto->id) }}" 
                                       class="btn btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            onclick="setDeleteProducto({{ $producto->id }}, '{{ $producto->Nombre }}')"
                                            title="Eliminar producto">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles del producto -->
                        <tr class="detalle-producto-row">
                            <td colspan="7" class="p-0 border-0">
                                <div class="collapse" id="detallesProducto{{ $producto->id }}">
                                    <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                        <div class="row">
                                            <!-- Información del producto -->
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-3 text-primary">
                                                    <i class="fas fa-info-circle me-2"></i>Información del Producto
                                                </h6>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted">Descripción:</label>
                                                    <p class="mb-0">{{ $producto->Descripcion ?? 'Sin descripción' }}</p>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="form-label small text-muted">Precio:</label>
                                                        <h5 class="text-success fw-bold">${{ number_format($producto->Precio, 2) }}</h5>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small text-muted">Categoría:</label>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                                {{ $categoriaNombre }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <label class="form-label small text-muted">Límites de Stock:</label>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="text-center">
                                                            <small class="text-muted d-block">Mínimo</small>
                                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                                {{ $producto->Cantidad_minima }}
                                                            </span>
                                                        </div>
                                                        <div class="text-center">
                                                            <small class="text-muted d-block">Actual</small>
                                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                                {{ $producto->Cantidad }}
                                                            </span>
                                                        </div>
                                                        <div class="text-center">
                                                            <small class="text-muted d-block">Máximo</small>
                                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                                {{ $producto->Cantidad_maxima }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Estadísticas y acciones -->
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="fas fa-chart-pie me-2"></i>Estadísticas
                                                        </h6>
                                                        
                                                        <!-- Gráfico de stock -->
                                                        <div class="mb-4">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <small class="text-muted">Nivel de Stock</small>
                                                                <small class="fw-bold">{{ number_format($porcentajeStock, 1) }}%</small>
                                                            </div>
                                                            <div class="progress" style="height: 10px;">
                                                                <div class="progress-bar bg-{{ $estadoColor }}" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $porcentajeStock }}%"
                                                                     aria-valuenow="{{ $producto->Cantidad }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="{{ $producto->Cantidad_maxima }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Información de ventas y compras -->
                                                        <div class="mb-3">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="text-center">
                                                                        <small class="text-muted d-block">Ventas</small>
                                                                        <span class="fw-bold text-primary">
                                                                            {{ $producto->detalleVentas->count() }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="text-center">
                                                                        <small class="text-muted d-block">Compras</small>
                                                                        <span class="fw-bold text-success">
                                                                            {{ $producto->detalleCompras->count() }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <!-- Estado actual -->
                                                        <div class="mb-3">
                                                            <label class="form-label small text-muted">Estado:</label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-{{ $estadoColor }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <i class="fas {{ $estadoIcon }} text-{{ $estadoColor }}"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $estadoStock }}</div>
                                                                    @if($estadoStock == 'Bajo Stock')
                                                                    <small class="text-{{ $estadoColor }}">
                                                                        Alerta: Stock por debajo del mínimo
                                                                    </small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-4">
                                                            <div class="d-grid gap-2">
                                                                <a href="{{ route('productos.edit', $producto->id) }}" 
                                                                   class="btn btn-outline-primary btn-sm">
                                                                    <i class="fas fa-edit me-1"></i> Editar Producto
                                                                </a>
                                                                <button type="button" 
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#deleteModal"
                                                                        onclick="setDeleteProducto({{ $producto->id }}, '{{ $producto->Nombre }}')">
                                                                    <i class="fas fa-trash me-1"></i> Eliminar Producto
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
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                                    <h4 class="text-muted fw-bold mb-3">No hay productos registrados</h4>
                                    <p class="text-muted mb-4">
                                        @if(count($filtrosActivos) > 0)
                                        No se encontraron productos con los filtros aplicados.
                                        @else
                                        Comienza registrando el primer producto en el sistema.
                                        @endif
                                    </p>
                                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-redo me-1"></i> Limpiar Filtros
                                    </a>
                                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i> Registrar Producto
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
                    @if($productosPaginated->total() > 0)
                    Mostrando {{ $productosPaginated->firstItem() ?? 0 }}-{{ $productosPaginated->lastItem() ?? 0 }} de {{ $productosPaginated->total() }} producto(s)
                    @else
                    No hay productos que mostrar
                    @endif
                </div>
                @if($productosPaginated->hasPages())
                <div>
                    {{ $productosPaginated->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación - ACTUALIZADO -->
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
                    <i class="fas fa-shopping-bag fa-4x text-danger mb-3"></i>
                </div>
                <p class="fs-6">
                    Estás a punto de eliminar el pedido:
                </p>
                <h5 class="text-danger fw-bold mb-2" id="deletePedidoId"></h5>
                <p class="text-muted mb-3">
                    Valor total: <strong id="deletePedidoTotal"></strong>
                </p>
                <p class="text-danger small mb-0">
                    <strong>Esta acción no se puede deshacer.</strong>
                </p>
                <div class="alert alert-warning mt-3 text-start small">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <strong>Nota:</strong> Al eliminar este pedido, también se eliminarán todos los productos asociados y se actualizarán los niveles de inventario.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash me-1"></i> Eliminar Pedido
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

    // Validación de precios
    const precioMin = document.querySelector('input[name="precio_min"]');
    const precioMax = document.querySelector('input[name="precio_max"]');
    
    if (precioMin && precioMax) {
        const form = document.getElementById('filtrosForm');
        form.addEventListener('submit', function(e) {
            if (precioMin.value && precioMax.value && parseFloat(precioMin.value) > parseFloat(precioMax.value)) {
                e.preventDefault();
                alert('El precio mínimo no puede ser mayor al precio máximo.');
                precioMin.focus();
                return false;
            }
        });
    }

    // Validación de stock
    const stockMin = document.querySelector('input[name="stock_min"]');
    const stockMax = document.querySelector('input[name="stock_max"]');
    
    if (stockMin && stockMax) {
        const form = document.getElementById('filtrosForm');
        form.addEventListener('submit', function(e) {
            if (stockMin.value && stockMax.value && parseInt(stockMin.value) > parseInt(stockMax.value)) {
                e.preventDefault();
                alert('El stock mínimo no puede ser mayor al stock máximo.');
                stockMin.focus();
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

// CORRECCIÓN: Función para el modal de eliminación de productos
function setDeleteProducto(productoId, nombre) {
    try {
        // Actualizar el nombre en el modal
        const nombreElement = document.getElementById('deleteProductoNombre');
        if (nombreElement) {
            nombreElement.textContent = nombre;
        } else {
            console.error('Elemento deleteProductoNombre no encontrado');
            return;
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
        const actionUrl = "{{ route('productos.destroy', ':id') }}".replace(':id', productoId);
        
        console.log('URL de eliminación producto:', actionUrl); // Para depuración
        
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
        console.error('Error en setDeleteProducto:', error);
        alert('Error al preparar la eliminación. Por favor, recarga la página.');
    }
}
</script>
@endpush

<style>
/* Estilos adaptados para productos */
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

.btn-expand {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items-center;
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

.detalle-producto-row {
    background-color: #f8fafc;
}

.input-group .btn-outline-danger {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Estilos específicos para barras de progreso */
.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

/* Estado de stock */
.bg-success.bg-opacity-10 {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-danger.bg-opacity-10 {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

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
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}
</style>
@endsection