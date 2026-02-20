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

    @if(session('foreign_key_error'))
        <div class="alert alert-modern alert-warning d-flex align-items-center mb-4" role="alert" style="
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: none;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
        ">
            <div class="alert-icon me-3">
                <i class="fas fa-exclamation-triangle fa-2x" style="color: #856404;"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1" style="color: #856404;">Producto Protegido</h6>
                <p class="mb-0" style="color: #856404;">{{ is_array(session('foreign_key_error')) ? session('foreign_key_error')['mensaje'] : session('foreign_key_error') }}</p>
                @if(session('ventas_count') || session('compras_count') || session('pedidos_count'))
                <div class="mt-2 small">
                    <strong style="color: #856404;">Detalles:</strong>
                    <ul class="mb-0 mt-1">
                        @if(session('ventas_count'))
                        <li style="color: #856404;">Ventas: {{ session('ventas_count') }}</li>
                        @endif
                        @if(session('compras_count'))
                        <li style="color: #856404;">Compras: {{ session('compras_count') }}</li>
                        @endif
                        @if(session('pedidos_count'))
                        <li style="color: #856404;">Pedidos: {{ session('pedidos_count') }}</li>
                        @endif
                    </ul>
                </div>
                @endif
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
                <p class="text-muted small mb-0">Encuentra productos específicos usando los siguientes filtros</p>
            </div>
        </div>

        <div class="row g-3">
            <!-- Búsqueda por nombre -->
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
                           id="searchInput" 
                           class="form-control border-0 bg-light" 
                           placeholder="Nombre o descripción...">
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
                    <select id="filterCategoria" class="form-select border-0 bg-light">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filtro por estado de stock -->
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-chart-bar me-1" style="color: #667eea;"></i>
                    Estado de Stock
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-boxes text-primary"></i>
                    </span>
                    <select id="filterEstadoStock" class="form-select border-0 bg-light">
                        <option value="">Todos los estados</option>
                        <option value="en_stock">En Stock</option>
                        <option value="agotado">Agotado</option>
                        <option value="bajo_stock">Bajo Stock</option>
                    </select>
                </div>
            </div>

            <!-- Ordenamiento -->
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                    Ordenar por
                </label>
                <select id="sortBy" class="form-select border-0 bg-light">
                    <option value="Nombre">Nombre</option>
                    <option value="Precio">Precio</option>
                    <option value="Cantidad">Stock</option>
                    <option value="id">ID</option>
                </select>
            </div>

            <!-- Dirección de orden -->
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                    Dirección
                </label>
                <select id="sortOrder" class="form-select border-0 bg-light">
                    <option value="asc">Ascendente</option>
                    <option value="desc">Descendente</option>
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="col-md-4">
                <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                    <div class="text-muted small">
                        <span id="filterCount">0</span> filtro(s) activo(s)
                    </div>
                    <div class="btn-group">
                        <button type="button" id="applyFilters" class="btn btn-primary px-4" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border: none;
                            border-radius: 12px 0 0 12px;
                        ">
                            <i class="fas fa-filter me-1"></i> Aplicar
                        </button>
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary px-4" style="
                            border-radius: 0 12px 12px 0;
                            border: 1px solid #e5e7eb;
                        ">
                            <i class="fas fa-redo me-1"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <span id="totalCount">{{ $productosPaginated->total() }}</span> producto(s) registrado(s)
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
                    Orden: <span id="sortDisplay">Nombre</span>
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
                        $estadoColor = '';
                        $estadoGradiente = '';
                        $estadoIcon = '';
                        
                        if($producto->Cantidad == 0) {
                            $estadoStock = 'Agotado';
                            $estadoColor = 'danger';
                            $estadoGradiente = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                            $estadoIcon = 'fa-times-circle';
                        } elseif($producto->Cantidad <= $producto->Cantidad_minima) {
                            $estadoStock = 'Bajo Stock';
                            $estadoColor = 'warning';
                            $estadoGradiente = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                            $estadoIcon = 'fa-exclamation-triangle';
                        } else {
                            $estadoStock = 'En Stock';
                            $estadoColor = 'success';
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
                    <tr class="align-middle producto-row {{ $tieneRelaciones ? 'producto-protegido' : '' }}" 
                        data-nombre="{{ strtolower($producto->Nombre) }}" 
                        data-descripcion="{{ strtolower($producto->Descripcion ?? '') }}"
                        data-categoria-id="{{ $producto->Categoria ?? '' }}"
                        data-categoria="{{ strtolower($categoriaNombre) }}"
                        data-precio="{{ $producto->Precio }}"
                        data-stock="{{ $producto->Cantidad }}"
                        data-estado-stock="{{ $estadoStock }}">
                        
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
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $producto->Nombre }}</h6>
                                    <small class="text-muted">ID: #{{ str_pad($producto->id, 5, '0', STR_PAD_LEFT) }}</small>
                                    @if($tieneRelaciones)
                                    <span class="badge ms-2 px-2 py-1" style="
                                        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                        color: white;
                                        border-radius: 50px;
                                        font-size: 0.65rem;
                                    ">
                                        <i class="fas fa-link me-1"></i>Protegido
                                    </span>
                                    @endif
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
                                <span class="fw-medium">{{ $categoriaNombre }}</span>
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
                                    <h6 class="mb-0 fw-bold" style="color: #10b981;">${{ number_format($producto->Precio, 2) }}</h6>
                                </div>
                            </div>
                        </td>

                        <!-- Stock -->
                        <td>
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px; background: #e5e7eb; border-radius: 4px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $porcentajeStock }}%; background: {{ $estadoGradiente }}; border-radius: 4px;"
                                             aria-valuenow="{{ $producto->Cantidad }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $producto->Cantidad_maxima }}">
                                        </div>
                                    </div>
                                    <span class="fw-bold">{{ $producto->Cantidad }}</span>
                                </div>
                                <small class="text-muted">
                                    Min: {{ $producto->Cantidad_minima }} | Max: {{ $producto->Cantidad_maxima }}
                                </small>
                            </div>
                        </td>

                        <!-- Estado -->
                        <td>
                            <span class="badge px-3 py-2" style="
                                background: {{ $estadoGradiente }};
                                color: white;
                                border-radius: 50px;
                                font-size: 0.75rem;
                            ">
                                <i class="fas {{ $estadoIcon }} me-1"></i>{{ $estadoStock }}
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('productos.edit', $producto->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                   title="Editar producto">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($tieneRelaciones)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="mostrarErrorRelaciones({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}', {{ $producto->detalleVentas->count() }}, {{ $producto->detalleCompras->count() }}, {{ $producto->detallePedidos->count() }})"
                                            title="No se puede eliminar: tiene registros asociados">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="verificarRelacionesYeliminar({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}')"
                                            title="Eliminar producto">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles del producto -->
                    <tr class="detalle-producto-row">
                        <td colspan="7" class="p-0 border-0">
                            <div class="collapse" id="detallesProducto{{ $producto->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <!-- Advertencia si tiene relaciones -->
                                    @if($tieneRelaciones)
                                    <div class="alert alert-warning mb-4" style="
                                        background: linear-gradient(135deg, #fef3c7 0%, #ffe69c 100%);
                                        border: none;
                                        border-radius: 16px;
                                        padding: 1rem;
                                    ">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-exclamation-triangle fa-2x" style="color: #856404;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1" style="color: #856404;">⛔ Producto Protegido</h6>
                                                <p class="mb-0" style="color: #856404;">
                                                    Este producto tiene registros asociados:
                                                    @if($tieneVentas)<span class="badge ms-2" style="background: #3b82f6; color: white;">{{ $producto->detalleVentas->count() }} venta(s)</span>@endif
                                                    @if($tieneCompras)<span class="badge ms-2" style="background: #10b981; color: white;">{{ $producto->detalleCompras->count() }} compra(s)</span>@endif
                                                    @if($tienePedidos)<span class="badge ms-2" style="background: #3b82f6; color: white;">{{ $producto->detallePedidos->count() }} pedido(s)</span>@endif
                                                </p>
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
                                                            <span class="fw-medium">{{ $producto->Nombre }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">ID Producto:</span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">#{{ $producto->id }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Categoría:</span>
                                                            <span class="fw-medium">{{ $categoriaNombre }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Precio:</span>
                                                            <span class="fw-bold" style="color: #10b981;">${{ number_format($producto->Precio, 2) }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Stock Actual:</span>
                                                            <span class="fw-medium">{{ $producto->Cantidad }} unidades</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Stock Mínimo:</span>
                                                            <span>{{ $producto->Cantidad_minima }} unidades</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Stock Máximo:</span>
                                                            <span>{{ $producto->Cantidad_maxima }} unidades</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <span class="text-muted d-block mb-2">Descripción:</span>
                                                    @if($producto->Descripcion)
                                                    <p class="mb-0 p-3 bg-light rounded-3">{{ $producto->Descripcion }}</p>
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
                                                        <span class="fw-medium">{{ $producto->Cantidad }} unidades</span>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Estado:</span>
                                                        <span class="badge px-3 py-1" style="background: {{ $estadoGradiente }}; color: white;">
                                                            {{ $estadoStock }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Información de relaciones -->
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
                                                
                                                <!-- Botones de acción -->
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('productos.edit', $producto->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                        <i class="fas fa-edit me-1"></i> Editar producto
                                                    </a>
                                                    
                                                    @if($tieneRelaciones)
                                                        <button type="button" 
                                                                class="btn btn-outline-warning btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="mostrarErrorRelaciones({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}', {{ $producto->detalleVentas->count() }}, {{ $producto->detalleCompras->count() }}, {{ $producto->detallePedidos->count() }})">
                                                            <i class="fas fa-lock me-1"></i> Ver detalles
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="verificarRelacionesYeliminar({{ $producto->id }}, '{{ addslashes($producto->Nombre) }}')">
                                                            <i class="fas fa-trash me-1"></i> Eliminar producto
                                                        </button>
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
                                    Comienza registrando el primer producto en el sistema.
                                </p>
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

        <!-- Paginación -->
        @if($productosPaginated->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center" style="border-top: 1px solid #e5e7eb;">
            <div class="text-muted small">
                Página {{ $productosPaginated->currentPage() }} de {{ $productosPaginated->lastPage() }}
            </div>
            <div>
                {{ $productosPaginated->appends(request()->query())->links() }}
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
                        <i class="fas fa-box fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deleteProductoNombreDisplay"></h5>
                <p class="text-muted mb-4" id="deleteProductoId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Producto a eliminar:</span>
                            <span class="fw-bold" id="deleteProductoNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción es irreversible y eliminará permanentemente el producto del sistema.</p>
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
                        <i class="fas fa-trash me-2"></i>Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ERROR MEJORADO - Relaciones -->
<div class="modal fade" id="foreignKeyErrorModal" tabindex="-1" aria-labelledby="foreignKeyErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-warning text-white" style="
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                border: none;
                padding: 1.5rem;
            ">
                <h5 class="modal-title fw-bold" id="foreignKeyErrorModalLabel">
                    <i class="fas fa-lock me-2 fa-lg"></i>
                    Producto Protegido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body text-center p-4">
                <div class="error-icon-wrapper mb-4">
                    <div class="error-icon-circle" style="
                        width: 90px;
                        height: 90px;
                        background: rgba(255, 193, 7, 0.1);
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        <i class="fas fa-link fa-4x text-warning"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="errorProductoNombre"></h5>
                
                <div class="card border-warning border-2 mb-4" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-3">
                            <span class="badge p-2" style="font-size: 1rem; background: #667eea; color: white;">
                                <i class="fas fa-shopping-cart me-2"></i>
                                <span id="errorVentasCount">0</span> ventas
                            </span>
                            <span class="badge p-2" style="font-size: 1rem; background: #10b981; color: white;">
                                <i class="fas fa-truck me-2"></i>
                                <span id="errorComprasCount">0</span> compras
                            </span>
                            <span class="badge p-2" style="font-size: 1rem; background: #3b82f6; color: white;">
                                <i class="fas fa-clipboard-list me-2"></i>
                                <span id="errorPedidosCount">0</span> pedidos
                            </span>
                        </div>
                        <p class="text-muted mb-0">
                            Este producto no puede ser eliminado porque tiene registros asociados en el sistema.
                        </p>
                    </div>
                </div>
                
                <div class="alert alert-info bg-opacity-10 border-0 text-start" style="border-radius: 12px;">
                    <h6 class="fw-bold text-info mb-2">
                        <i class="fas fa-lightbulb me-2"></i>¿Cómo solucionarlo?
                    </h6>
                    <ol class="text-muted small mb-0 ps-3">
                        <li class="mb-1">Elimina las ventas que incluyen este producto</li>
                        <li class="mb-1">Elimina las compras que incluyen este producto</li>
                        <li class="mb-1">Elimina los pedidos que incluyen este producto</li>
                        <li>Luego podrás eliminar el producto</li>
                    </ol>
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <div class="btn-group" role="group" id="relacionesButtons">
                    <a href="{{ route('ventas.index') }}" class="btn btn-primary" style="border-radius: 50px 0 0 50px;" id="verVentasBtn" target="_blank">
                        <i class="fas fa-shopping-cart me-2"></i>Ventas
                    </a>
                    <a href="{{ route('compras.index') }}" class="btn btn-success" style="border-radius: 0;" id="verComprasBtn" target="_blank">
                        <i class="fas fa-truck me-2"></i>Compras
                    </a>
                    <a href="{{ route('pedidos.index') }}" class="btn btn-info" style="border-radius: 0 50px 50px 0;" id="verPedidosBtn" target="_blank">
                        <i class="fas fa-clipboard-list me-2"></i>Pedidos
                    </a>
                </div>
                <button type="button" class="btn btn-light px-5 mt-2" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-check me-2"></i>Entendido
                </button>
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
                    <span class="visually-hidden">Verificando...</span>
                </div>
                <h6 class="fw-bold mb-2">Verificando relaciones</h6>
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
    setupFilters();
    setupRefreshButton();
    setupModalCleanup();
    
    @if(session('foreign_key_error'))
        @php
            $errorData = is_array(session('foreign_key_error')) 
                ? session('foreign_key_error') 
                : ['producto_nombre' => session('producto_nombre') ?? 'el producto', 
                   'ventas_count' => session('ventas_count') ?? 0, 
                   'compras_count' => session('compras_count') ?? 0, 
                   'pedidos_count' => session('pedidos_count') ?? 0,
                   'producto_id' => session('producto_id') ?? 0];
        @endphp
        setTimeout(function() {
            mostrarErrorRelaciones(
                '{{ $errorData["producto_nombre"] }}', 
                {{ $errorData["ventas_count"] }},
                {{ $errorData["compras_count"] }},
                {{ $errorData["pedidos_count"] }},
                {{ $errorData["producto_id"] }}
            );
        }, 100);
    @endif
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
            
            // Cerrar otros acordeones
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

function setupFilters() {
    const searchInput = document.getElementById('searchInput');
    const filterCategoria = document.getElementById('filterCategoria');
    const filterEstadoStock = document.getElementById('filterEstadoStock');
    const sortBy = document.getElementById('sortBy');
    const sortOrder = document.getElementById('sortOrder');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    const productosRows = document.querySelectorAll('.producto-row');
    const visibleCount = document.getElementById('visibleCount');
    const filterCount = document.getElementById('filterCount');
    const sortDisplay = document.getElementById('sortDisplay');

    function updateFilterCount() {
        let count = 0;
        if (searchInput.value.trim()) count++;
        if (filterCategoria.value) count++;
        if (filterEstadoStock.value) count++;
        filterCount.textContent = count;
    }

    function applyTableFilters() {
        const searchText = searchInput.value.toLowerCase();
        const categoriaValue = filterCategoria.value;
        const estadoStockValue = filterEstadoStock.value;
        let visibleRows = 0;

        productosRows.forEach(row => {
            const nombre = row.dataset.nombre;
            const descripcion = row.dataset.descripcion || '';
            const categoriaId = row.dataset.categoriaId;
            const estadoStock = row.dataset.estadoStock?.toLowerCase().replace(' ', '_') || '';

            const matchesSearch = searchText === '' || 
                nombre.includes(searchText) || 
                descripcion.includes(searchText);
            const matchesCategoria = categoriaValue === '' || categoriaId === categoriaValue;
            const matchesEstadoStock = estadoStockValue === '' || estadoStock === estadoStockValue;

            if (matchesSearch && matchesCategoria && matchesEstadoStock) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        visibleCount.textContent = visibleRows;
        updateFilterCount();
        updateSortDisplay();
    }

    function updateSortDisplay() {
        const sortText = sortBy.options[sortBy.selectedIndex].text;
        const orderIcon = sortOrder.value === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down';
        sortDisplay.innerHTML = `${sortText} <i class="fas ${orderIcon} ms-1"></i>`;
    }

    function sortTable() {
        const sortColumn = sortBy.value;
        const order = sortOrder.value;

        const rowsArray = Array.from(productosRows);
        
        rowsArray.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortColumn) {
                case 'Nombre':
                    aValue = a.dataset.nombre;
                    bValue = b.dataset.nombre;
                    break;
                case 'Precio':
                    aValue = parseFloat(a.dataset.precio);
                    bValue = parseFloat(b.dataset.precio);
                    break;
                case 'Cantidad':
                    aValue = parseInt(a.dataset.stock);
                    bValue = parseInt(b.dataset.stock);
                    break;
                default: // ID
                    aValue = parseInt(a.dataset.id);
                    bValue = parseInt(b.dataset.id);
            }

            if (order === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        const tbody = document.querySelector('#productosTable tbody');
        rowsArray.forEach(row => {
            tbody.appendChild(row);
            const detallesRow = row.nextElementSibling;
            if (detallesRow && detallesRow.classList.contains('detalle-producto-row')) {
                tbody.appendChild(detallesRow);
            }
        });
    }

    applyFilters.addEventListener('click', function() {
        applyTableFilters();
        sortTable();
    });

    resetFilters.addEventListener('click', function() {
        searchInput.value = '';
        filterCategoria.value = '';
        filterEstadoStock.value = '';
        sortBy.value = 'Nombre';
        sortOrder.value = 'asc';
        applyTableFilters();
        sortTable();
    });

    sortBy.addEventListener('change', sortTable);
    sortOrder.addEventListener('change', sortTable);
    searchInput.addEventListener('input', applyTableFilters);
    filterCategoria.addEventListener('change', applyTableFilters);
    filterEstadoStock.addEventListener('change', applyTableFilters);

    applyTableFilters();
    updateSortDisplay();
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
    
    const errorModal = document.getElementById('foreignKeyErrorModal');
    if (errorModal) {
        errorModal.addEventListener('hidden.bs.modal', forceCleanupModals);
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

async function verificarRelacionesYeliminar(productoId, nombreProducto) {
    showLoadingModal();
    
    try {
        const response = await fetch(`/productos/${productoId}/relaciones`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        
        const data = await response.json();
        
        await hideLoadingModal();
        await new Promise(resolve => setTimeout(resolve, 100));
        
        if (data.tieneRelaciones) {
            mostrarErrorRelaciones(
                nombreProducto, 
                data.ventasCount, 
                data.comprasCount, 
                data.pedidosCount, 
                productoId
            );
        } else {
            mostrarModalEliminacion(productoId, nombreProducto);
        }
        
    } catch (error) {
        console.error('Error:', error);
        
        await hideLoadingModal();
        await new Promise(resolve => setTimeout(resolve, 100));
        
        mostrarModalEliminacion(productoId, nombreProducto);
    }
}

function mostrarModalEliminacion(productoId, nombreProducto) {
    try {
        forceCleanupModals();
        
        document.getElementById('deleteProductoNombre').textContent = nombreProducto;
        document.getElementById('deleteProductoNombreDisplay').textContent = `¿Eliminar "${nombreProducto}"?`;
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
        alert('Error al preparar la eliminación. Por favor, recarga la página.');
    }
}

function mostrarErrorRelaciones(nombreProducto, ventasCount, comprasCount, pedidosCount, productoId) {
    try {
        forceCleanupModals();
        
        const existingModal = document.getElementById('foreignKeyErrorModal');
        const modalInstance = bootstrap.Modal.getInstance(existingModal);
        if (modalInstance) {
            modalInstance.hide();
            forceCleanupModals();
        }
        
        setTimeout(() => {
            document.getElementById('errorProductoNombre').innerHTML = `
                <span class="text-warning">${nombreProducto}</span>
                <small class="d-block text-muted mt-1">Producto con registros asociados</small>
            `;
            document.getElementById('errorVentasCount').textContent = ventasCount;
            document.getElementById('errorComprasCount').textContent = comprasCount;
            document.getElementById('errorPedidosCount').textContent = pedidosCount;
            
            const verVentasBtn = document.getElementById('verVentasBtn');
            const verComprasBtn = document.getElementById('verComprasBtn');
            const verPedidosBtn = document.getElementById('verPedidosBtn');
            
            if (verVentasBtn && ventasCount > 0) {
                verVentasBtn.href = `/ventas?producto_id=${productoId}`;
            }
            if (verComprasBtn && comprasCount > 0) {
                verComprasBtn.href = `/compras?producto_id=${productoId}`;
            }
            if (verPedidosBtn && pedidosCount > 0) {
                verPedidosBtn.href = `/pedidos?producto_id=${productoId}`;
            }
            
            const errorModal = new bootstrap.Modal(existingModal, {
                backdrop: 'static',
                keyboard: true
            });
            
            errorModal.show();
        }, 50);
        
    } catch (error) {
        console.error('Error:', error);
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
    
    [title] {
        cursor: help;
    }
    
    .stat-card:hover .stat-decoration {
        transform: scale(1.2);
    }
    
    .btn-expand-producto:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: transparent !important;
    }
    
    .producto-protegido {
        background-color: rgba(255, 193, 7, 0.02);
    }
    
    .producto-protegido:hover {
        background-color: rgba(255, 193, 7, 0.08) !important;
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
    
    #loadingModal .modal-content {
        border-radius: 20px !important;
    }
    
    #loadingModal .spinner-border {
        width: 3.5rem;
        height: 3.5rem;
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

@media (max-width: 768px) {
    #productos-page .btn-expand-producto {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #productos-page .producto-avatar {
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
    
    .modal-footer .btn-group {
        width: 100%;
        flex-direction: column;
    }
    
    .modal-footer .btn-group .btn {
        border-radius: 50px !important;
        margin: 0.25rem 0 !important;
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

/* Hover effects para botones */
#productos-page .btn-outline-primary:hover,
#productos-page .btn-outline-danger:hover,
#productos-page .btn-outline-success:hover,
#productos-page .btn-outline-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Paginación */
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