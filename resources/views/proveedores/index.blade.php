@extends('layouts.app')

@section('content')
<div id="proveedores-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                    <i class="fas fa-truck fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Proveedores
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-bolt me-1 text-warning"></i>
                        Administra el registro y seguimiento de todos los proveedores del sistema
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
                <a href="{{ route('proveedores.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-plus-circle me-2"></i> Nuevo Proveedor
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
            // Obtener todos los proveedores para las estadísticas
            $todosProveedores = App\Models\Proveedor::withCount('compras')->get();
            $proveedoresActivos = $todosProveedores->where('estado', 1);
            $proveedoresInactivos = $todosProveedores->where('estado', 0);
            
            $stats = [
                [
                    'titulo' => 'Total Proveedores',
                    'valor' => $todosProveedores->count(),
                    'icono' => 'fas fa-truck',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => 'Registrados en el sistema'
                ],
                [
                    'titulo' => 'Proveedores Activos',
                    'valor' => $proveedoresActivos->count(),
                    'icono' => 'fas fa-check-circle',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Disponibles para usar'
                ],
                [
                    'titulo' => 'Proveedores Inactivos',
                    'valor' => $proveedoresInactivos->count(),
                    'icono' => 'fas fa-times-circle',
                    'color' => '#9ca3af',
                    'gradiente' => 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)',
                    'descripcion' => 'Desactivados'
                ],
                [
                    'titulo' => 'Con Compras',
                    'valor' => $todosProveedores->where('compras_count', '>', 0)->count(),
                    'icono' => 'fas fa-shopping-cart',
                    'color' => '#f59e0b',
                    'gradiente' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'descripcion' => 'Compras asociadas'
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
                <p class="text-muted small mb-0">Encuentra proveedores específicos usando los siguientes filtros</p>
            </div>
        </div>

        <form id="filtrosForm" method="GET" action="{{ route('proveedores.index') }}">
            <div class="row g-3">
                <!-- Buscar Proveedor -->
                <div class="col-md-4">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-search me-1" style="color: #667eea;"></i>
                        Buscar Proveedor
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" 
                               id="searchInput" 
                               class="form-control border-0 bg-light" 
                               name="search"
                               placeholder="Nombre, empresa, correo..."
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

                <!-- Empresa -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-building me-1" style="color: #667eea;"></i>
                        Empresa
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-industry text-primary"></i>
                        </span>
                        <select id="filterEmpresa" class="form-select border-0 bg-light" name="empresa">
                            <option value="" {{ request('empresa') == '' ? 'selected' : '' }}>Todas las empresas</option>
                            @if(is_array($empresasUnicas) && count($empresasUnicas) > 0)
                                @foreach($empresasUnicas as $empresa)
                                    @if(!empty($empresa) && is_string($empresa))
                                        <option value="{{ $empresa }}" {{ request('empresa') == $empresa ? 'selected' : '' }}>
                                            {{ $empresa }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @if(request('empresa'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('empresa')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Sexo -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-venus-mars me-1" style="color: #667eea;"></i>
                        Sexo
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user text-primary"></i>
                        </span>
                        <select id="filterSexo" class="form-select border-0 bg-light" name="sexo">
                            <option value="" {{ request('sexo') == '' ? 'selected' : '' }}>Todos</option>
                            <option value="Masculino" {{ request('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ request('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @if(request('sexo'))
                        <button type="button" 
                                class="btn btn-outline-danger border-0" 
                                onclick="clearFilter('sexo')">
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
                        <option value="activos" {{ request('estado') == 'activos' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivos" {{ request('estado') == 'inactivos' ? 'selected' : '' }}>Inactivos</option>
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
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="Empresa_asociada" {{ request('sort_by') == 'Empresa_asociada' ? 'selected' : '' }}>Empresa</option>
                        <option value="Correo" {{ request('sort_by') == 'Correo' ? 'selected' : '' }}>Correo</option>
                        <option value="estado" {{ request('sort_by') == 'estado' ? 'selected' : '' }}>Estado</option>
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
                                    return in_array($key, ['search', 'empresa', 'sexo', 'estado']) && !empty($value);
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
                            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary px-4" style="
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
        if(request('empresa')) $filtrosActivosLista[] = ['Empresa', request('empresa'), 'empresa'];
        if(request('sexo')) $filtrosActivosLista[] = ['Sexo', request('sexo'), 'sexo'];
        if(request('estado')) {
            $estado = request('estado') == 'activos' ? 'Activos' : 'Inactivos';
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

    <!-- Tabla de proveedores Mejorada -->
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
                    Lista de Proveedores
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Mostrando {{ $proveedores->firstItem() ?? 0 }} - {{ $proveedores->lastItem() ?? 0 }} de {{ $proveedores->total() }} proveedor(es)
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
            <table class="table table-hover mb-0" id="proveedoresTable">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="60px"></th>
                        <th class="py-3">Proveedor</th>
                        <th class="py-3">Empresa</th>
                        <th class="py-3">Contacto</th>
                        <th class="py-3">Sexo</th>
                        <th class="py-3">Compras</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $proveedor)
                    @php
                        $nombreCompleto = $proveedor->Nombre . ' ' . $proveedor->ApPaterno . ($proveedor->ApMaterno ? ' ' . $proveedor->ApMaterno : '');
                        $tieneCompras = ($proveedor->compras_count ?? 0) > 0;
                        
                        $sexoGradiente = $proveedor->Sexo == 'Masculino' 
                            ? 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)' 
                            : 'linear-gradient(135deg, #ec4899 0%, #db2777 100%)';
                    @endphp
                    <tr class="align-middle proveedor-row {{ $proveedor->estado == 0 ? 'table-secondary' : '' }}" 
                        data-nombre="{{ strtolower($nombreCompleto) }}" 
                        data-correo="{{ strtolower($proveedor->Correo) }}" 
                        data-telefono="{{ $proveedor->Telefono ?? '' }}"
                        data-empresa="{{ strtolower($proveedor->Empresa_asociada) }}"
                        data-sexo="{{ $proveedor->Sexo ?? '' }}"
                        data-compras="{{ $proveedor->compras_count ?? 0 }}"
                        data-estado="{{ $proveedor->estado }}">
                        
                        <!-- Botón expandir -->
                        <td class="ps-4">
                            <button class="btn btn-sm btn-expand-proveedor" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detallesProveedor{{ $proveedor->id }}"
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
                        
                        <!-- Proveedor -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="proveedor-avatar me-3" style="
                                    width: 48px;
                                    height: 48px;
                                    background: {{ $sexoGradiente }};
                                    border-radius: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-weight: 600;
                                    font-size: 1.2rem;
                                    box-shadow: 0 5px 15px {{ $proveedor->Sexo == 'Masculino' ? 'rgba(59, 130, 246, 0.3)' : 'rgba(236, 72, 153, 0.3)' }};
                                    {{ $proveedor->estado == 0 ? 'opacity: 0.7;' : '' }}
                                ">
                                    {{ strtoupper(substr($proveedor->Nombre, 0, 1)) }}{{ strtoupper(substr($proveedor->ApPaterno, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $nombreCompleto }}</h6>
                                    <small class="text-muted">ID: #{{ str_pad($proveedor->id, 5, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Empresa -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="empresa-avatar me-2" style="
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
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <span class="fw-medium {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ Str::limit($proveedor->Empresa_asociada, 25) }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Contacto -->
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-envelope me-2" style="color: #667eea; width: 16px;"></i>
                                    <span class="small {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ Str::limit($proveedor->Correo, 25) }}</span>
                                </span>
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-phone-alt me-2" style="color: #10b981; width: 16px;"></i>
                                    <span class="small {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $proveedor->Telefono }}</span>
                                </span>
                            </div>
                        </td>

                        <!-- Sexo -->
                        <td>
                            <span class="badge px-3 py-2" style="
                                background: {{ $sexoGradiente }};
                                color: white;
                                border-radius: 50px;
                                font-size: 0.75rem;
                                {{ $proveedor->estado == 0 ? 'opacity: 0.7;' : '' }}
                            ">
                                <i class="fas fa-{{ $proveedor->Sexo == 'Masculino' ? 'mars' : 'venus' }} me-1"></i>
                                {{ $proveedor->Sexo }}
                            </span>
                        </td>

                        <!-- Compras -->
                        <td>
                            @if($tieneCompras)
                                <span class="badge px-3 py-2" style="
                                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                    color: white;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    {{ $proveedor->compras_count }} compras
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="
                                    background: #f3f4f6;
                                    color: #4b5563;
                                    border-radius: 50px;
                                    font-size: 0.75rem;
                                ">
                                    <i class="fas fa-box-open me-1"></i>
                                    Sin compras
                                </span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td>
                            @if($proveedor->estado == 1)
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
                                @if($proveedor->estado == 1) {{-- Activo --}}
                                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                       title="Editar proveedor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="setDeleteProveedor({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}')"
                                            title="Desactivar proveedor">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else {{-- Inactivo --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="activarProveedor({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}')"
                                            title="Activar proveedor">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    
                                    <span class="btn btn-sm btn-outline-secondary disabled" 
                                          style="border-radius: 10px; border: 1px solid #e5e7eb; opacity: 0.5; cursor: not-allowed;"
                                          title="No se puede editar o eliminar un proveedor inactivo">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles del proveedor -->
                    <tr class="detalle-proveedor-row">
                        <td colspan="8" class="p-0 border-0">
                            <div class="collapse" id="detallesProveedor{{ $proveedor->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                    <!-- Badge de estado en detalles -->
                                    <div class="mb-3 text-end">
                                        @if($proveedor->estado == 1)
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Proveedor Activo
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="
                                                background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
                                                color: white;
                                                border-radius: 50px;
                                                font-size: 0.85rem;
                                            ">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Proveedor Inactivo
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Advertencia si tiene compras -->
                                    @if($tieneCompras)
                                    <div class="alert alert-warning mb-4" style="
                                        background: linear-gradient(135deg, #fef3c7 0%, #ffe69c 100%);
                                        border: none;
                                        border-radius: 16px;
                                        padding: 1rem;
                                    ">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="fas fa-shopping-cart fa-2x" style="color: #856404;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1" style="color: #856404;">📦 Proveedor con Compras</h6>
                                                <p class="mb-0" style="color: #856404;">
                                                    Este proveedor tiene <span class="badge bg-warning">{{ $proveedor->compras_count }} compra(s)</span> asociadas.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row g-4">
                                        <!-- Información del proveedor -->
                                        <div class="col-md-8">
                                            <div class="detail-card p-3" style="
                                                background: white;
                                                border-radius: 16px;
                                                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                            ">
                                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                                    Información del Proveedor
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Nombre completo:</span>
                                                            <span class="fw-medium {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $nombreCompleto }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">ID Proveedor:</span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">#{{ $proveedor->id }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Sexo:</span>
                                                            <span class="badge px-3 py-1" style="background: {{ $sexoGradiente }}; color: white;">
                                                                <i class="fas fa-{{ $proveedor->Sexo == 'Masculino' ? 'mars' : 'venus' }} me-1"></i>
                                                                {{ $proveedor->Sexo }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Correo:</span>
                                                            <span class="fw-medium {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $proveedor->Correo }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Teléfono:</span>
                                                            <span class="fw-medium {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $proveedor->Telefono }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between">
                                                            <span class="text-muted">Compras:</span>
                                                            <span class="badge {{ $tieneCompras ? 'bg-warning' : 'bg-secondary' }} bg-opacity-10 {{ $tieneCompras ? 'text-warning' : 'text-secondary' }} px-3 py-1">
                                                                <i class="fas fa-shopping-cart me-1"></i>{{ $proveedor->compras_count ?? 0 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <span class="text-muted d-block mb-2">Empresa Asociada:</span>
                                                    <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                        <div class="empresa-avatar me-2" style="
                                                            width: 32px;
                                                            height: 32px;
                                                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                            border-radius: 10px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: white;
                                                        ">
                                                            <i class="fas fa-building"></i>
                                                        </div>
                                                        <span class="fw-medium {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $proveedor->Empresa_asociada }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Compras asociadas (si las hay) -->
                                                @if($tieneCompras && $proveedor->compras)
                                                <div class="mt-4">
                                                    <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                                                        Compras Realizadas ({{ $proveedor->compras->count() }})
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr class="text-muted small">
                                                                    <th>ID Compra</th>
                                                                    <th class="text-center">Fecha</th>
                                                                    <th class="text-end">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($proveedor->compras->take(5) as $compra)
                                                                <tr>
                                                                    <td>
                                                                        <span class="fw-medium">#{{ str_pad($compra->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y') }}
                                                                    </td>
                                                                    <td class="text-end fw-bold" style="color: #10b981;">
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
                                                </div>
                                                @endif
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
                                                        <span class="text-muted">ID Proveedor:</span>
                                                        <span class="fw-medium">#{{ str_pad($proveedor->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Compras realizadas:</span>
                                                        <span class="fw-medium">{{ $proveedor->compras_count ?? 0 }}</span>
                                                    </div>
                                                    
                                                    @if($tieneCompras)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Total comprado:</span>
                                                        <span class="fw-bold" style="color: #10b981;">
                                                            ${{ number_format($proveedor->compras->sum('Total'), 2) }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Estado:</span>
                                                        @if($proveedor->estado == 1)
                                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1">
                                                                <i class="fas fa-check-circle me-1"></i>Activo
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1">
                                                                <i class="fas fa-times-circle me-1"></i>Inactivo
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <hr style="margin: 1rem 0; border-color: #e5e7eb;">
                                                
                                                <div class="mb-3">
                                                    <h6 class="fw-bold mb-2 text-muted small">Información de Contacto:</h6>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="contact-icon me-2" style="
                                                            width: 32px;
                                                            height: 32px;
                                                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                                            border-radius: 8px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: white;
                                                            font-size: 0.9rem;
                                                        ">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                        <span class="small {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $proveedor->Correo }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="contact-icon me-2" style="
                                                            width: 32px;
                                                            height: 32px;
                                                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                            border-radius: 8px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            color: white;
                                                            font-size: 0.9rem;
                                                        ">
                                                            <i class="fas fa-phone-alt"></i>
                                                        </div>
                                                        <span class="small {{ $proveedor->estado == 0 ? 'text-muted' : '' }}">{{ $proveedor->Telefono }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Botones de acción en detalles -->
                                                <div class="d-grid gap-2">
                                                    @if($proveedor->estado == 1)
                                                        <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                            <i class="fas fa-edit me-1"></i> Editar proveedor
                                                        </a>
                                                        
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="setDeleteProveedor({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}')">
                                                            <i class="fas fa-trash me-1"></i> Desactivar proveedor
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-success btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="activarProveedor({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}')">
                                                            <i class="fas fa-check-circle me-1"></i> Activar proveedor
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
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state py-5">
                                <i class="fas fa-truck fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay proveedores registrados</h5>
                                <p class="text-muted mb-4">
                                    @if(count($filtrosActivosLista) > 0)
                                        No se encontraron proveedores con los filtros aplicados.
                                    @else
                                        Comienza registrando el primer proveedor en el sistema.
                                    @endif
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if(count($filtrosActivosLista) > 0)
                                    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Limpiar Filtros
                                    </a>
                                    @endif
                                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Registrar Proveedor
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
        @if($proveedores instanceof \Illuminate\Pagination\LengthAwarePaginator && $proveedores->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $proveedores->appends(request()->query())->links() }}
        </div>
        @endif

        <div class="card-footer bg-white border-0 py-3 px-4" style="border-top: 1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $proveedores->firstItem() ?? 0 }} - {{ $proveedores->lastItem() ?? 0 }} de {{ $proveedores->total() }} proveedor(es)
                </div>
                <div class="text-muted small">
                    @if(request('sort_by') == 'id')
                        Ordenados por: <strong>ID</strong>
                    @elseif(request('sort_by') == 'Empresa_asociada')
                        Ordenados por: <strong>Empresa</strong>
                    @elseif(request('sort_by') == 'Correo')
                        Ordenados por: <strong>Correo</strong>
                    @elseif(request('sort_by') == 'estado')
                        Ordenados por: <strong>Estado</strong>
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
                        <i class="fas fa-truck fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deleteProveedorNombreDisplay"></h5>
                <p class="text-muted mb-4" id="deleteProveedorId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Proveedor a desactivar:</span>
                            <span class="fw-bold" id="deleteProveedorNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción desactivará el proveedor, pero podrás activarlo nuevamente en cualquier momento.</p>
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
                        <i class="fas fa-truck fa-3x text-success"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="activarProveedorNombreDisplay"></h5>
                <p class="text-muted mb-4" id="activarProveedorId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Proveedor a activar:</span>
                            <span class="fw-bold" id="activarProveedorNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-info-circle fs-4 me-3 text-success"></i>
                    <div class="text-start">
                        <strong class="text-success">¡Información!</strong>
                        <p class="mb-0 text-muted small">Al activar este proveedor, estará disponible para realizar compras nuevamente.</p>
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

<!-- Modal de Carga -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Verificando...</span>
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
    document.querySelectorAll('.btn-expand-proveedor').forEach(button => {
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

function setupFilterAutoSubmit() {
    document.querySelectorAll('select[name="sort_by"], select[name="sort_order"], select[name="estado"], select[name="empresa"], select[name="sexo"]').forEach(select => {
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
    
    const loadingModal = document.getElementById('loadingModal');
    if (loadingModal) {
        loadingModal.addEventListener('hidden.bs.modal', function() {
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
            deleteAlertP.textContent = 'Esta acción desactivará el proveedor, pero podrás activarlo nuevamente en cualquier momento.';
        }
    }
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

function setDeleteProveedor(proveedorId, nombreCompleto) {
    try {
        forceCleanupModals();
        
        document.getElementById('deleteProveedorNombre').textContent = nombreCompleto;
        document.getElementById('deleteProveedorNombreDisplay').textContent = `¿Desactivar "${nombreCompleto}"?`;
        document.getElementById('deleteProveedorId').innerHTML = `<small class="text-muted">ID: #${proveedorId}</small>`;
        
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.action = `/proveedores/${proveedorId}`;
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

function activarProveedor(proveedorId, nombreCompleto) {
    try {
        forceCleanupModals();
        
        document.getElementById('activarProveedorNombre').textContent = nombreCompleto;
        document.getElementById('activarProveedorNombreDisplay').textContent = `¿Activar "${nombreCompleto}"?`;
        document.getElementById('activarProveedorId').innerHTML = `<small class="text-muted">ID: #${proveedorId}</small>`;
        
        const activarForm = document.getElementById('activarForm');
        if (activarForm) {
            activarForm.action = `/proveedores/${proveedorId}/activar`;
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
    
    .btn-expand-proveedor:hover {
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
#proveedores-page {
    padding-top: 20px;
}

#proveedores-page .proveedor-avatar {
    width: 48px;
    height: 48px;
}

#proveedores-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#proveedores-page .table tbody tr {
    transition: all 0.2s ease;
}

#proveedores-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

#proveedores-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#proveedores-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#proveedores-page .card {
    border-radius: 12px;
}

#proveedores-page .fw-semibold {
    font-weight: 600;
}

#proveedores-page .detalle-proveedor-row {
    background-color: #f8fafc;
}

#proveedores-page .collapse {
    transition: all 0.3s ease;
}

#proveedores-page .collapsing {
    transition: height 0.35s ease;
}

#proveedores-page .form-control:focus,
#proveedores-page .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

@media (max-width: 768px) {
    #proveedores-page .btn-expand-proveedor {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #proveedores-page .proveedor-avatar {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }
    
    #proveedores-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #proveedores-page .detalle-proveedor-row .row {
        flex-direction: column;
    }
    
    #proveedores-page .btn-group .btn {
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

#proveedores-page .collapse.show {
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
#proveedores-page .btn-outline-primary:hover,
#proveedores-page .btn-outline-danger:hover,
#proveedores-page .btn-outline-success:hover {
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