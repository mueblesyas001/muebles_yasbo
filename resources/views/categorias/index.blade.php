@extends('layouts.app')

@section('content')
<div id="categorias-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                    <i class="fas fa-tags fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Categorías
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-bolt me-1 text-warning"></i>
                        Administra las categorías de productos del sistema
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
                <a href="{{ route('categorias.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-plus me-2"></i> Nueva Categoría
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
            // Obtener todas las categorías para las estadísticas (sin paginación)
            $todasCategorias = App\Models\Categoria::withCount('productos')->get();
            $categoriasActivas = $todasCategorias->where('estado', 1);
            $categoriasInactivas = $todasCategorias->where('estado', 0);
            
            $stats = [
                [
                    'titulo' => 'Total Categorías',
                    'valor' => $todasCategorias->count(),
                    'icono' => 'fas fa-tags',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => 'Registradas en el sistema'
                ],
                [
                    'titulo' => 'Categorías Activas',
                    'valor' => $categoriasActivas->count(),
                    'icono' => 'fas fa-check-circle',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Disponibles para usar'
                ],
                [
                    'titulo' => 'Categorías Inactivas',
                    'valor' => $categoriasInactivas->count(),
                    'icono' => 'fas fa-times-circle',
                    'color' => '#9ca3af',
                    'gradiente' => 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)',
                    'descripcion' => 'Desactivadas'
                ],
                [
                    'titulo' => 'Con Productos',
                    'valor' => $todasCategorias->where('productos_count', '>', 0)->count(),
                    'icono' => 'fas fa-boxes',
                    'color' => '#f59e0b',
                    'gradiente' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'descripcion' => 'Productos asociados'
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
                <p class="text-muted small mb-0">Encuentra categorías específicas usando los siguientes filtros</p>
            </div>
        </div>

        <form id="filtrosForm" method="GET" action="{{ route('categorias.index') }}">
            <div class="row g-3">
                <!-- Buscar Categoría -->
                <div class="col-md-4">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-search me-1" style="color: #667eea;"></i>
                        Buscar Categoría
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" 
                               id="searchInput" 
                               class="form-control border-0 bg-light" 
                               name="search"
                               placeholder="Nombre o descripción..."
                               value="{{ request('search') }}"
                               style="box-shadow: none;">
                        @if(request('search'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('search')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-truck me-1" style="color: #667eea;"></i>
                        Proveedor
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user-tie text-primary"></i>
                        </span>
                        <select id="filterProveedor" class="form-select border-0 bg-light" name="proveedor_id">
                            <option value="" {{ request('proveedor_id') == '' ? 'selected' : '' }}>Todos los proveedores</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno ?? '' }}
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

                <!-- Filtro por Estado -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-flag me-1" style="color: #667eea;"></i>
                        Estado
                    </label>
                    <select id="filterEstado" class="form-select border-0 bg-light" name="estado">
                        <option value="" {{ request('estado') == '' ? 'selected' : '' }}>Todos</option>
                        <option value="activas" {{ request('estado') == 'activas' ? 'selected' : '' }}>Activas</option>
                        <option value="inactivas" {{ request('estado') == 'inactivas' ? 'selected' : '' }}>Inactivas</option>
                    </select>
                </div>

                <!-- Ordenamiento -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                        Ordenar por
                    </label>
                    <select id="sortBy" class="form-select border-0 bg-light" name="sort_by">
                        <option value="Nombre" {{ request('sort_by', 'Nombre') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                        <option value="productos_count" {{ request('sort_by') == 'productos_count' ? 'selected' : '' }}>Productos</option>
                        <option value="estado" {{ request('sort_by') == 'estado' ? 'selected' : '' }}>Estado</option>
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                    </select>
                </div>

                <!-- Dirección de orden -->
                <div class="col-md-1">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                        Dir.
                    </label>
                    <select id="sortOrder" class="form-select border-0 bg-light" name="sort_order">
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
                                    return in_array($key, ['search', 'proveedor_id', 'estado']) && !empty($value);
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
                            <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary px-4" style="
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
        if(request('search')) $filtrosActivosLista[] = ['Búsqueda', request('search'), 'search'];
        if(request('proveedor_id')) {
            $proveedor = $proveedores->firstWhere('id', request('proveedor_id'));
            $filtrosActivosLista[] = ['Proveedor', $proveedor ? ($proveedor->Nombre ?? '') . ' ' . ($proveedor->ApPaterno ?? '') . ' ' . ($proveedor->ApMaterno ?? '') : 'No encontrado', 'proveedor_id'];
        }
        if(request('estado')) {
            $estado = request('estado') == 'activas' ? 'Activas' : 'Inactivas';
            $filtrosActivosLista[] = ['Estado', $estado, 'estado'];
        }
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

    <!-- Tabla de categorías Mejorada -->
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
                    Lista de Categorías
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Mostrando {{ $categorias->firstItem() ?? 0 }} - {{ $categorias->lastItem() ?? 0 }} de {{ $categorias->total() }} categoría(s)
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
                    Orden: {{ request('sort_by', 'Nombre') }}
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="categoriasTable">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="60px"></th>
                        <th class="py-3">Categoría</th>
                        <th class="py-3">Descripción</th>
                        <th class="py-3">Proveedor</th>
                        <th class="py-3">Productos</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    @php
                        $tieneProductos = ($categoria->productos_count ?? 0) > 0;
                    @endphp
                    <tr class="align-middle categoria-row {{ $categoria->estado == 0 ? 'table-secondary' : '' }}" 
                        data-nombre="{{ strtolower($categoria->Nombre) }}" 
                        data-descripcion="{{ strtolower($categoria->Descripcion ?? '') }}"
                        data-proveedor-id="{{ $categoria->Proveedor ?? '0' }}"
                        data-productos="{{ $categoria->productos_count ?? 0 }}"
                        data-estado="{{ $categoria->estado }}">
                        
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand-categoria" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detallesCategoria{{ $categoria->id }}"
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
                        
                        <!-- Categoría -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="categoria-avatar categoria-avatar-md me-3" style="
                                    width: 48px;
                                    height: 48px;
                                    background: {{ $categoria->estado == 1 ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)' }};
                                    border-radius: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-size: 1.2rem;
                                    box-shadow: 0 5px 15px {{ $categoria->estado == 1 ? 'rgba(102, 126, 234, 0.3)' : 'rgba(156, 163, 175, 0.3)' }};
                                ">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        {{ $categoria->Nombre }}
                                    </h6>
                                    <small class="text-muted">ID: #{{ str_pad($categoria->id, 5, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Descripción -->
                        <td>
                            @if($categoria->Descripcion)
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      data-bs-toggle="tooltip" title="{{ $categoria->Descripcion }}">
                                    {{ $categoria->Descripcion }}
                                </span>
                            @else
                                <span class="text-muted small">Sin descripción</span>
                            @endif
                        </td>

                        <!-- Proveedor -->
                        <td>
                            @if($categoria->proveedor)
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
                                        <span class="fw-medium">{{ $categoria->proveedor->Nombre }} {{ $categoria->proveedor->ApPaterno }} {{ $categoria->proveedor->ApMaterno ?? '' }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: #fef3c7;
                                    color: #92400e;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-user-slash me-1"></i>
                                    Sin proveedor
                                </span>
                            @endif
                        </td>

                        <!-- Productos -->
                        <td>
                            @if($tieneProductos)
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-boxes me-1"></i>
                                    {{ $categoria->productos_count }} productos
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: #f3f4f6;
                                    color: #4b5563;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-box-open me-1"></i>
                                    Sin productos
                                </span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td>
                            @if($categoria->estado == 1)
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Activa
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Inactiva
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                @if($categoria->estado == 1) {{-- Activa --}}
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                       title="Editar categoría">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="setDeleteCategoria({{ $categoria->id }}, '{{ addslashes($categoria->Nombre) }}')"
                                            title="Desactivar categoría">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else {{-- Inactiva --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="activarCategoria({{ $categoria->id }}, '{{ addslashes($categoria->Nombre) }}')"
                                            title="Activar categoría">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    
                                    <span class="btn btn-sm btn-outline-secondary disabled" 
                                          style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5; cursor: not-allowed;"
                                          title="No se puede editar o eliminar una categoría inactiva">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles de la categoría -->
                    <tr class="detalle-categoria-row">
                        <td colspan="7" class="p-0 border-0">
                            <div class="collapse" id="detallesCategoria{{ $categoria->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <!-- Badge de estado en detalles -->
                                    <div class="mb-3 text-end">
                                        @if($categoria->estado == 1)
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Categoría Activa
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Categoría Inactiva
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="row g-4">
                                        <!-- Información detallada -->
                                        <div class="col-md-8">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                                    Información de la Categoría
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Nombre:</span>
                                                            <span class="fw-medium">{{ $categoria->Nombre }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">ID Categoría:</span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">#{{ $categoria->id }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Total Productos:</span>
                                                            <span class="badge {{ $tieneProductos ? 'bg-warning' : 'bg-secondary' }} bg-opacity-10 {{ $tieneProductos ? 'text-warning' : 'text-secondary' }} px-3 py-1">
                                                                <i class="fas fa-boxes me-1"></i>{{ $categoria->productos_count ?? 0 }}
                                                            </span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Estado:</span>
                                                            @if($categoria->estado == 1)
                                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1">
                                                                    <i class="fas fa-check-circle me-1"></i>Activa
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1">
                                                                    <i class="fas fa-times-circle me-1"></i>Inactiva
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <span class="text-muted d-block mb-2">Descripción:</span>
                                                    @if($categoria->Descripcion)
                                                    <p class="mb-0 p-3 bg-light rounded-3">{{ $categoria->Descripcion }}</p>
                                                    @else
                                                    <p class="mb-0 p-3 bg-light rounded-3 text-muted">No se ha registrado una descripción</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Información del proveedor y acciones -->
                                        <div class="col-md-4">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-truck me-2 text-primary"></i>
                                                    Proveedor Asociado
                                                </h6>
                                                
                                                @if($categoria->proveedor)
                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="proveedor-avatar me-2" style="
                                                            width: 40px;
                                                            height: 40px;
                                                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                            border-radius: 12px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: white;
                                                            font-size: 1rem;
                                                        ">
                                                            <i class="fas fa-truck"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $categoria->proveedor->Nombre }} {{ $categoria->proveedor->ApPaterno }} {{ $categoria->proveedor->ApMaterno ?? '' }}</div>
                                                            <small class="text-muted">ID: #{{ $categoria->proveedor->id }}</small>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Teléfono:</span>
                                                        <span class="fw-medium">{{ $categoria->proveedor->Telefono ?? 'No disponible' }}</span>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between">
                                                        <span class="text-muted">Correo:</span>
                                                        <span class="fw-medium">{{ $categoria->proveedor->Correo ?? 'No disponible' }}</span>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="alert alert-warning mb-3" style="
                                                    background: #fef3c7;
                                                    border: none;
                                                    border-radius: 12px;
                                                ">
                                                    <i class="fas fa-exclamation-triangle me-2" style="color: #856404;"></i>
                                                    Esta categoría no tiene proveedor asignado.
                                                </div>
                                                @endif
                                                
                                                <hr style="margin: 1rem 0; border-color: #e5e7eb;">
                                                
                                                <!-- Botones de acción en detalles -->
                                                <div class="d-grid gap-2">
                                                    @if($categoria->estado == 1)
                                                        <a href="{{ route('categorias.edit', $categoria->id) }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                            <i class="fas fa-edit me-1"></i> Editar categoría
                                                        </a>
                                                        
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="setDeleteCategoria({{ $categoria->id }}, '{{ addslashes($categoria->Nombre) }}')">
                                                            <i class="fas fa-trash me-1"></i> Desactivar categoría
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-success btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="activarCategoria({{ $categoria->id }}, '{{ addslashes($categoria->Nombre) }}')">
                                                            <i class="fas fa-check-circle me-1"></i> Activar categoría
                                                        </button>
                                                        
                                                        <span class="btn btn-outline-secondary btn-sm disabled" 
                                                              style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5; cursor: not-allowed;">
                                                            <i class="fas fa-lock me-1"></i> No editable
                                                        </span>
                                                    @endif
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
                                <i class="fas fa-tags fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay categorías registradas</h5>
                                <p class="text-muted mb-4">
                                    @if(count($filtrosActivosLista) > 0)
                                        No se encontraron categorías con los filtros aplicados.
                                    @else
                                        Comienza registrando la primera categoría en el sistema.
                                    @endif
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if(count($filtrosActivosLista) > 0)
                                    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Limpiar Filtros
                                    </a>
                                    @endif
                                    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Registrar Categoría
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
        @if($categorias instanceof \Illuminate\Pagination\LengthAwarePaginator && $categorias->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $categorias->appends(request()->query())->links() }}
        </div>
        @endif

        <div class="card-footer bg-white border-0 py-3 px-4" style="border-top: 1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $categorias->firstItem() ?? 0 }} - {{ $categorias->lastItem() ?? 0 }} de {{ $categorias->total() }} categoría(s)
                </div>
                <div class="text-muted small">
                    @if(request('sort_by') == 'productos_count')
                        Ordenadas por: <strong>Número de Productos</strong>
                    @elseif(request('sort_by') == 'estado')
                        Ordenadas por: <strong>Estado</strong>
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

<!-- MODAL DE DESACTIVACIÓN -->
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
                        <i class="fas fa-trash-alt fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deleteCategoriaNombreDisplay"></h5>
                <p class="text-muted mb-4" id="deleteCategoriaId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Categoría a desactivar:</span>
                            <span class="fw-bold" id="deleteCategoriaNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción desactivará la categoría, pero podrás activarla nuevamente en cualquier momento.</p>
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
                        <i class="fas fa-trash me-2"></i>Sí, desactivar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ACTIVACIÓN -->
<div class="modal fade" id="activarModal" tabindex="-1" aria-labelledby="activarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-success text-white" style="
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold" id="activarModalLabel">
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
                
                <h5 class="fw-bold mb-3" id="activarCategoriaNombreDisplay"></h5>
                <p class="text-muted mb-4" id="activarCategoriaId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Categoría a activar:</span>
                            <span class="fw-bold" id="activarCategoriaNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-info-circle fs-4 me-3 text-success"></i>
                    <div class="text-start">
                        <strong class="text-success">¡Información!</strong>
                        <p class="mb-0 text-muted small">Al activar esta categoría, estará disponible para ser utilizada nuevamente en productos.</p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="activarForm" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success px-4" id="confirmActivarBtn" style="border-radius: 50px;">
                        <i class="fas fa-check-circle me-2"></i>Sí, activar
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
    
    // Configurar auto-submit de filtros
    setupFilterAutoSubmit();
    
    // Configurar botón de refrescar
    setupRefreshButton();
    
    // Configurar limpieza de modales
    setupModalCleanup();
    
    // Actualizar textos del modal de eliminación
    updateDeleteModalTexts();
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setupExpandButtons() {
    document.querySelectorAll('.btn-expand-categoria').forEach(button => {
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
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"], select[name="estado"]').forEach(select => {
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
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', function() {
            forceCleanupModals();
        });
    }
    
    const activarModal = document.getElementById('activarModal');
    if (activarModal) {
        activarModal.addEventListener('hidden.bs.modal', function() {
            forceCleanupModals();
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

function updateDeleteModalTexts() {
    const deleteModalLabel = document.getElementById('deleteModalLabel');
    if (deleteModalLabel) {
        deleteModalLabel.innerHTML = '<i class="fas fa-exclamation-triangle me-2 fa-lg"></i> Confirmar Desactivación';
    }
    
    const deleteBtnText = document.getElementById('confirmDeleteBtn');
    if (deleteBtnText) {
        deleteBtnText.innerHTML = '<i class="fas fa-trash me-2"></i>Sí, desactivar';
    }
    
    const deleteAlertText = document.querySelector('#deleteModal .alert-danger strong');
    if (deleteAlertText) {
        deleteAlertText.textContent = '¡Atención!';
        const deleteAlertP = document.querySelector('#deleteModal .alert-danger p');
        if (deleteAlertP) {
            deleteAlertP.textContent = 'Esta acción desactivará la categoría, pero podrás activarla nuevamente en cualquier momento.';
        }
    }
}

function setDeleteCategoria(categoriaId, nombreCategoria) {
    try {
        forceCleanupModals();
        
        document.getElementById('deleteCategoriaNombre').textContent = nombreCategoria;
        document.getElementById('deleteCategoriaNombreDisplay').textContent = `¿Desactivar "${nombreCategoria}"?`;
        document.getElementById('deleteCategoriaId').innerHTML = `<small class="text-muted">ID: #${categoriaId}</small>`;
        
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.action = `/categorias/${categoriaId}`;
        }
        
        setTimeout(() => {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }, 50);
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al preparar la desactivación. Por favor, recarga la página.');
    }
}

function activarCategoria(categoriaId, nombreCategoria) {
    try {
        forceCleanupModals();
        
        document.getElementById('activarCategoriaNombre').textContent = nombreCategoria;
        document.getElementById('activarCategoriaNombreDisplay').textContent = `¿Activar "${nombreCategoria}"?`;
        document.getElementById('activarCategoriaId').innerHTML = `<small class="text-muted">ID: #${categoriaId}</small>`;
        
        const activarForm = document.getElementById('activarForm');
        if (activarForm) {
            activarForm.action = `/categorias/${categoriaId}/activar`;
        }
        
        setTimeout(() => {
            const activarModal = new bootstrap.Modal(document.getElementById('activarModal'));
            activarModal.show();
        }, 50);
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al preparar la activación. Por favor, recarga la página.');
    }
}

function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
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
    
    .table-secondary {
        background-color: rgba(156, 163, 175, 0.05) !important;
    }
    
    .table-secondary:hover {
        background-color: rgba(156, 163, 175, 0.1) !important;
    }
    
    .stat-card:hover .stat-decoration {
        transform: scale(1.2);
    }
    
    .btn-expand-categoria:hover {
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
    
    .delete-icon-circle, .activate-icon-circle {
        animation: pulseIcon 2s infinite;
    }
    
    @keyframes pulseIcon {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(spinStyle);
</script>
@endpush

<style>
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

#categorias-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#categorias-page .table tbody tr {
    transition: all 0.2s ease;
}

#categorias-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
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

#categorias-page .detalle-categoria-row {
    background-color: #f8fafc;
}

#categorias-page .collapse {
    transition: all 0.3s ease;
}

#categorias-page .collapsing {
    transition: height 0.35s ease;
}

#categorias-page .form-control:focus,
#categorias-page .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

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
    
    #categorias-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #categorias-page .detalle-categoria-row .row {
        flex-direction: column;
    }
    
    #categorias-page .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

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

#categorias-page .text-truncate[data-bs-toggle="tooltip"] {
    cursor: help;
}

.border-start.border-4 {
    border-left-width: 4px !important;
}

/* Hover effects para botones de acción */
#categorias-page .btn-outline-primary:hover,
#categorias-page .btn-outline-success:hover,
#categorias-page .btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
</style>
@endsection