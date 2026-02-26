@extends('layouts.app')

@section('content')
<div id="productos-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                    <i class="fas fa-boxes fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Productos
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-bolt me-1 text-warning"></i>
                        Administra el inventario y catálogo de productos del sistema
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
                <a href="{{ route('productos.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-plus-circle me-2"></i> Nuevo Producto
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
            $enStockCount = $productosFiltrados->where('Cantidad', '>', 0)->count();
            $agotadosCount = $productosFiltrados->where('Cantidad', 0)->count();
            $bajoStockCount = $productosFiltrados->filter(function($p) {
                return $p->Cantidad > 0 && $p->Cantidad <= $p->Cantidad_minima;
            })->count();
            $totalUnidades = $productosFiltrados->sum('Cantidad');
            
            $stats = [
                [
                    'titulo' => 'Productos Filtrados',
                    'valor' => $productosFiltrados->count(),
                    'icono' => 'fas fa-boxes',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => request()->anyFilled(['id', 'nombre', 'categoria_id', 'precio_min', 'precio_max', 'stock_min', 'stock_max', 'estado_stock']) ? 'Con filtros aplicados' : 'Todos los productos'
                ],
                [
                    'titulo' => 'En Stock',
                    'valor' => $enStockCount,
                    'subvalor' => $totalUnidades . ' unidades',
                    'icono' => 'fas fa-check-circle',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Productos disponibles'
                ],
                [
                    'titulo' => 'Agotados',
                    'valor' => $agotadosCount,
                    'icono' => 'fas fa-times-circle',
                    'color' => '#ef4444',
                    'gradiente' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                    'descripcion' => 'Requieren reposición'
                ],
                [
                    'titulo' => 'Bajo Stock',
                    'valor' => $bajoStockCount,
                    'icono' => 'fas fa-exclamation-triangle',
                    'color' => '#f59e0b',
                    'gradiente' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'descripcion' => 'Alerta de inventario'
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
                @if(isset($stat['subvalor']))
                <small class="text-muted">{{ $stat['subvalor'] }}</small>
                @endif
                
                @if($stat['titulo'] != 'Productos Filtrados')
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
                @endif
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
                <p class="text-muted small mb-0">Encuentra productos específicos usando los siguientes filtros</p>
            </div>
        </div>

        <form id="filtrosForm" method="GET" action="{{ route('productos.index') }}">
            <div class="row g-3">
                <!-- Buscar Producto -->
                <div class="col-md-4">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-search me-1" style="color: #667eea;"></i>
                        Buscar Producto
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-0 bg-light" 
                               name="nombre"
                               placeholder="Nombre o descripción..."
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

                <!-- Filtro por categoría -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-folder me-1" style="color: #667eea;"></i>
                        Categoría
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-list text-primary"></i>
                        </span>
                        <select name="categoria_id" class="form-select border-0 bg-light">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->Nombre }}
                            </option>
                            @endforeach
                        </select>
                        @if(request('categoria_id'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('categoria_id')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Filtro por estado de stock -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-chart-bar me-1" style="color: #667eea;"></i>
                        Estado Stock
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-boxes text-primary"></i>
                        </span>
                        <select name="estado_stock" class="form-select border-0 bg-light">
                            <option value="">Todos</option>
                            <option value="en_stock" {{ request('estado_stock') == 'en_stock' ? 'selected' : '' }}>En Stock</option>
                            <option value="agotado" {{ request('estado_stock') == 'agotado' ? 'selected' : '' }}>Agotado</option>
                            <option value="bajo_stock" {{ request('estado_stock') == 'bajo_stock' ? 'selected' : '' }}>Bajo Stock</option>
                        </select>
                        @if(request('estado_stock'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('estado_stock')">
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
                    <select name="sort_by" class="form-select border-0 bg-light">
                        <option value="Nombre" {{ request('sort_by', 'Nombre') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                        <option value="Precio" {{ request('sort_by') == 'Precio' ? 'selected' : '' }}>Precio</option>
                        <option value="Cantidad" {{ request('sort_by') == 'Cantidad' ? 'selected' : '' }}>Stock</option>
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                    </select>
                </div>

                <!-- Dirección de orden -->
                <div class="col-md-1">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                        Dir.
                    </label>
                    <select name="sort_order" class="form-select border-0 bg-light">
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
                                    return in_array($key, ['nombre', 'categoria_id', 'estado_stock']) 
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
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary px-4" style="
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
        if(request('nombre')) $filtrosActivosLista[] = ['Nombre', request('nombre'), 'nombre'];
        if(request('categoria_id')) {
            $categoria = $categorias->firstWhere('id', request('categoria_id'));
            $filtrosActivosLista[] = ['Categoría', $categoria ? $categoria->Nombre : 'No encontrada', 'categoria_id'];
        }
        if(request('estado_stock')) {
            $estado = request('estado_stock') == 'en_stock' ? 'En Stock' : (request('estado_stock') == 'agotado' ? 'Agotado' : 'Bajo Stock');
            $filtrosActivosLista[] = ['Estado Stock', $estado, 'estado_stock'];
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

    <!-- Tabla de productos Mejorada -->
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
                    <i class="fas fa-boxes me-2 text-primary"></i>
                    Catálogo de Productos
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Mostrando {{ $productosPaginated->firstItem() ?? 0 }} - {{ $productosPaginated->lastItem() ?? 0 }} de {{ $productosPaginated->total() }} producto(s)
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
            <table class="table table-hover mb-0" id="productosTable">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="60px"></th>
                        <th class="py-3">Producto</th>
                        <th class="py-3">Categoría</th>
                        <th class="py-3">Precio</th>
                        <th class="py-3">Stock</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productosPaginated as $producto)
                    @php
                        $categoriaNombre = $producto->categoria ? $producto->categoria->Nombre : 'Sin categoría';
                        
                        $estadoStock = '';
                        $estadoGradiente = '';
                        $estadoIcon = '';
                        
                        if($producto->Cantidad == 0) {
                            $estadoStock = 'Agotado';
                            $estadoGradiente = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                            $estadoIcon = 'fa-times-circle';
                        } elseif($producto->Cantidad <= $producto->Cantidad_minima) {
                            $estadoStock = 'Bajo Stock';
                            $estadoGradiente = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                            $estadoIcon = 'fa-exclamation-triangle';
                        } else {
                            $estadoStock = 'En Stock';
                            $estadoGradiente = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                            $estadoIcon = 'fa-check-circle';
                        }
                        
                        $porcentajeStock = $producto->Cantidad_maxima > 0 ? 
                            min(100, ($producto->Cantidad / $producto->Cantidad_maxima) * 100) : 0;
                        
                        $tieneVentas = $producto->detalleVentas->count() > 0;
                        $tieneCompras = $producto->detalleCompras->count() > 0;
                        $tienePedidos = $producto->detallePedidos->count() > 0;
                        $tieneRelaciones = $tieneVentas || $tieneCompras || $tienePedidos;
                    @endphp
                    <tr class="align-middle producto-row {{ $producto->estado == 0 ? 'table-secondary' : '' }}" 
                        data-nombre="{{ strtolower($producto->Nombre) }}" 
                        data-descripcion="{{ strtolower($producto->Descripcion ?? '') }}"
                        data-categoria-id="{{ $producto->Categoria ?? '' }}"
                        data-precio="{{ $producto->Precio }}"
                        data-stock="{{ $producto->Cantidad }}"
                        data-estado-stock="{{ $estadoStock }}"
                        data-id="{{ $producto->id }}">
                        
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand-producto" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detallesProducto{{ $producto->id }}"
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
                        
                        <!-- Producto -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="producto-avatar me-3" style="
                                    width: 48px;
                                    height: 48px;
                                    background: {{ $producto->estado == 1 ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)' }};
                                    border-radius: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-weight: 600;
                                    font-size: 1.2rem;
                                    box-shadow: 0 5px 15px {{ $producto->estado == 1 ? 'rgba(102, 126, 234, 0.3)' : 'rgba(156, 163, 175, 0.3)' }};
                                ">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ $producto->estado == 0 ? 'text-muted' : '' }}">
                                        {{ $producto->Nombre }}
                                        @if($producto->estado == 0)
                                            <span class="badge bg-secondary ms-2" style="font-size: 0.7rem;">Inactivo</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">ID: #{{ str_pad($producto->id, 5, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                            @if($producto->Descripcion)
                            <small class="text-muted d-block mt-1">{{ Str::limit($producto->Descripcion, 50) }}</small>
                            @endif
                        </td>

                        <!-- Categoría -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="categoria-avatar me-2" style="
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
                                    <i class="fas fa-folder"></i>
                                </div>
                                <span class="fw-medium {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $categoriaNombre }}</span>
                            </div>
                        </td>

                        <!-- Precio -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="precio-avatar me-2" style="
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
                                    <h6 class="mb-0 fw-bold {{ $producto->estado == 0 ? 'text-muted' : '' }}" style="{{ $producto->estado == 1 ? 'color: #10b981;' : '' }}">${{ number_format($producto->Precio, 2) }}</h6>
                                </div>
                            </div>
                        </td>

                        <!-- Stock -->
                        <td>
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px; background: #e5e7eb; border-radius: 4px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $porcentajeStock }}%; background: {{ $estadoGradiente }}; border-radius: 4px; {{ $producto->estado == 0 ? 'opacity: 0.5;' : '' }}"
                                             aria-valuenow="{{ $producto->Cantidad }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $producto->Cantidad_maxima }}">
                                        </div>
                                    </div>
                                    <span class="fw-bold {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Cantidad }}</span>
                                </div>
                                <small class="text-muted">
                                    Min: {{ $producto->Cantidad_minima }} | Max: {{ $producto->Cantidad_maxima }}
                                </small>
                            </div>
                        </td>

                        <!-- Estado -->
                        <td>
                            @if($producto->estado == 1)
                                <span class="badge px-3 py-2" style="
                                    background: {{ $estadoGradiente }};
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas {{ $estadoIcon }} me-1"></i>{{ $estadoStock }}
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-times-circle me-1"></i>Inactivo
                                </span>
                            @endif
                        </td>

                        <!-- Acciones (SIN CANDADOS - IGUAL QUE CATEGORÍAS) -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                @if($producto->estado == 1) {{-- Activo --}}
                                    <a href="{{ route('productos.edit', $producto->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="setDesactivarProducto({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}')"
                                            title="Desactivar producto">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else {{-- Inactivo --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="activarProducto({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}')"
                                            title="Activar producto">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    
                                    <span class="btn btn-sm btn-outline-secondary disabled" 
                                          style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5; cursor: not-allowed;"
                                          title="No se puede editar un producto inactivo">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles del producto -->
                    <tr class="detalle-producto-row">
                        <td colspan="7" class="p-0 border-0">
                            <div class="collapse" id="detallesProducto{{ $producto->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <!-- Badge de estado en detalles -->
                                    <div class="mb-3 text-end">
                                        @if($producto->estado == 1)
                                            <span class="badge px-3 py-2" style="
                                                background: {{ $estadoGradiente }};
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas {{ $estadoIcon }} me-1"></i>
                                                {{ $estadoStock }}
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Advertencia informativa si tiene relaciones (solo informativa, no bloquea) -->
                                    @if($tieneRelaciones)
                                    <div class="alert alert-info mb-4" style="
                                        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
                                        border: none;
                                        border-radius: 16px;
                                        padding: 1rem;
                                    ">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-info-circle fa-2x" style="color: #0284c7;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1" style="color: #0369a1;">📦 Producto con registros</h6>
                                                <p class="mb-0" style="color: #0369a1;">
                                                    Este producto tiene registros asociados:
                                                    @if($tieneVentas)<span class="badge ms-2" style="background: #3b82f6; color: white;">{{ $producto->detalleVentas->count() }} venta(s)</span>@endif
                                                    @if($tieneCompras)<span class="badge ms-2" style="background: #10b981; color: white;">{{ $producto->detalleCompras->count() }} compra(s)</span>@endif
                                                    @if($tienePedidos)<span class="badge ms-2" style="background: #3b82f6; color: white;">{{ $producto->detallePedidos->count() }} pedido(s)</span>@endif
                                                </p>
                                                <small class="d-block mt-1 text-muted">Puedes desactivarlo igualmente, los registros se conservarán.</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
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
                                                    Información del Producto
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Nombre:</span>
                                                            <span class="fw-medium {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Nombre }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">ID Producto:</span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">#{{ $producto->id }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Categoría:</span>
                                                            <span class="fw-medium {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $categoriaNombre }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Precio:</span>
                                                            <span class="fw-bold {{ $producto->estado == 0 ? 'text-muted' : '' }}" style="{{ $producto->estado == 1 ? 'color: #10b981;' : '' }}">${{ number_format($producto->Precio, 2) }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Stock Actual:</span>
                                                            <span class="fw-medium {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Cantidad }} unidades</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Stock Mínimo:</span>
                                                            <span class="{{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Cantidad_minima }} unidades</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Stock Máximo:</span>
                                                            <span class="{{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Cantidad_maxima }} unidades</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <span class="text-muted d-block mb-2">Descripción:</span>
                                                    @if($producto->Descripcion)
                                                    <p class="mb-0 p-3 bg-light rounded-3 {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Descripcion }}</p>
                                                    @else
                                                    <p class="mb-0 p-3 bg-light rounded-3 text-muted">No se ha registrado una descripción</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Resumen y acciones -->
                                        <div class="col-md-4">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                                                    Resumen
                                                </h6>
                                                
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">ID Producto:</span>
                                                        <span class="fw-medium">#{{ str_pad($producto->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Stock:</span>
                                                        <span class="fw-medium {{ $producto->estado == 0 ? 'text-muted' : '' }}">{{ $producto->Cantidad }} unidades</span>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Estado:</span>
                                                        @if($producto->estado == 1)
                                                            <span class="badge px-3 py-1" style="background: {{ $estadoGradiente }}; color: white;">
                                                                {{ $estadoStock }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary px-3 py-1 text-white">
                                                                Inactivo
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Información de relaciones (solo informativa) -->
                                                <div class="mb-3">
                                                    <h6 class="text-muted small mb-2">Registros Asociados</h6>
                                                    <div class="row g-2">
                                                        <div class="col-4">
                                                            <div class="text-center p-2 rounded-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                                                <small class="d-block">Ventas</small>
                                                                <span class="fw-bold">{{ $producto->detalleVentas->count() }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="text-center p-2 rounded-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                                                <small class="d-block">Compras</small>
                                                                <span class="fw-bold">{{ $producto->detalleCompras->count() }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="text-center p-2 rounded-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                                                                <small class="d-block">Pedidos</small>
                                                                <span class="fw-bold">{{ $producto->detallePedidos->count() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <hr style="margin: 1rem 0; border-color: #e5e7eb;">
                                                
                                                <!-- Botones de acción en detalles (SIN CANDADOS) -->
                                                <div class="d-grid gap-2">
                                                    @if($producto->estado == 1)
                                                        <a href="{{ route('productos.edit', $producto->id) }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                            <i class="fas fa-edit me-1"></i> Editar producto
                                                        </a>
                                                        
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="setDesactivarProducto({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}')">
                                                            <i class="fas fa-trash me-1"></i> Desactivar producto
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-success btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="activarProducto({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}')">
                                                            <i class="fas fa-check-circle me-1"></i> Activar producto
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
                                <i class="fas fa-box-open fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay productos registrados</h5>
                                <p class="text-muted mb-4">
                                    @if(count($filtrosActivosLista) > 0)
                                        No se encontraron productos con los filtros aplicados.
                                    @else
                                        Comienza registrando el primer producto en el sistema.
                                    @endif
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if(count($filtrosActivosLista) > 0)
                                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Limpiar Filtros
                                    </a>
                                    @endif
                                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i> Registrar Producto
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
        @if($productosPaginated->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $productosPaginated->appends(request()->query())->links() }}
        </div>
        @endif

        <div class="card-footer bg-white border-0 py-3 px-4" style="border-top: 1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $productosPaginated->firstItem() ?? 0 }} - {{ $productosPaginated->lastItem() ?? 0 }} de {{ $productosPaginated->total() }} producto(s)
                </div>
                <div class="text-muted small">
                    @if(request('sort_by') == 'Precio')
                        Ordenados por: <strong>Precio</strong>
                    @elseif(request('sort_by') == 'Cantidad')
                        Ordenados por: <strong>Stock</strong>
                    @elseif(request('sort_by') == 'id')
                        Ordenados por: <strong>ID</strong>
                    @else
                        Ordenados por: <strong>Nombre</strong>
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
                        <i class="fas fa-box fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deleteProductoNombreDisplay"></h5>
                <p class="text-muted mb-4" id="deleteProductoId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Producto a desactivar:</span>
                            <span class="fw-bold" id="deleteProductoNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción desactivará el producto, pero podrás activarlo nuevamente en cualquier momento.</p>
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
                        <i class="fas fa-box fa-3x text-success"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="activarProductoNombreDisplay"></h5>
                <p class="text-muted mb-4" id="activarProductoId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Producto a activar:</span>
                            <span class="fw-bold" id="activarProductoNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-info-circle fs-4 me-3 text-success"></i>
                    <div class="text-start">
                        <strong class="text-success">¡Información!</strong>
                        <p class="mb-0 text-muted small">Al activar este producto, estará disponible nuevamente en el inventario.</p>
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
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 50px;">
                        <i class="fas fa-check-circle me-2"></i>Sí, activar
                    </button>
                </form>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    setupExpandButtons();
    setupFilterAutoSubmit();
    setupRefreshButton();
    setupModalCleanup();
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setupExpandButtons() {
    document.querySelectorAll('.btn-expand-producto').forEach(button => {
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
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"], select[name="categoria_id"], select[name="estado_stock"]').forEach(select => {
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
        deleteModal.addEventListener('hidden.bs.modal', forceCleanupModals);
    }
    
    const activarModal = document.getElementById('activarModal');
    if (activarModal) {
        activarModal.addEventListener('hidden.bs.modal', forceCleanupModals);
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

function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

function setDesactivarProducto(productoId, nombreProducto) {
    try {
        forceCleanupModals();
        
        document.getElementById('deleteProductoNombre').textContent = nombreProducto;
        document.getElementById('deleteProductoNombreDisplay').textContent = `¿Desactivar "${nombreProducto}"?`;
        document.getElementById('deleteProductoId').innerHTML = `<small class="text-muted">ID: #${productoId}</small>`;
        
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.action = `/productos/${productoId}`;
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

function activarProducto(productoId, nombreProducto) {
    try {
        forceCleanupModals();
        
        document.getElementById('activarProductoNombre').textContent = nombreProducto;
        document.getElementById('activarProductoNombreDisplay').textContent = `¿Activar "${nombreProducto}"?`;
        document.getElementById('activarProductoId').innerHTML = `<small class="text-muted">ID: #${productoId}</small>`;
        
        const activarForm = document.getElementById('activarForm');
        if (activarForm) {
            activarForm.action = `/productos/${productoId}/activar`;
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
    
    .btn-expand-producto:hover {
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
`;
document.head.appendChild(spinStyle);
</script>
@endpush

<style>
#productos-page {
    padding-top: 20px;
}

#productos-page .producto-avatar {
    width: 48px;
    height: 48px;
}

#productos-page .producto-avatar-md {
    width: 40px;
    height: 40px;
}

#productos-page .producto-avatar-sm {
    width: 36px;
    height: 36px;
}

#productos-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#productos-page .table tbody tr {
    transition: all 0.2s ease;
}

#productos-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

#productos-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#productos-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#productos-page .card {
    border-radius: 12px;
}

#productos-page .fw-semibold {
    font-weight: 600;
}

#productos-page .detalle-producto-row {
    background-color: #f8fafc;
}

#productos-page .collapse {
    transition: all 0.3s ease;
}

#productos-page .collapsing {
    transition: height 0.35s ease;
}

#productos-page .form-control:focus,
#productos-page .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

#productos-page .text-truncate[data-bs-toggle="tooltip"] {
    cursor: help;
}

.border-start.border-4 {
    border-left-width: 4px !important;
}

/* Hover effects para botones de acción */
#productos-page .btn-outline-primary:hover,
#productos-page .btn-outline-success:hover,
#productos-page .btn-outline-danger:hover {
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

@media (max-width: 768px) {
    #productos-page .btn-expand-producto {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #productos-page .producto-avatar, 
    #productos-page .producto-avatar-md, 
    #productos-page .producto-avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    #productos-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #productos-page .detalle-producto-row .row {
        flex-direction: column;
    }
    
    #productos-page .btn-group .btn {
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

#productos-page .collapse.show {
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
</style>
@endsection