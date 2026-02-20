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
                <h6 class="alert-heading fw-bold mb-1" style="color: #856404;">Proveedor Protegido</h6>
                <p class="mb-0" style="color: #856404;">{{ is_array(session('foreign_key_error')) ? session('foreign_key_error')['mensaje'] : session('foreign_key_error') }}</p>
                @if(session('compras_count'))
                <div class="mt-2 small">
                    <strong style="color: #856404;">Detalles:</strong>
                    <ul class="mb-0 mt-1">
                        <li style="color: #856404;">Compras: {{ session('compras_count') }}</li>
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
            $totalProveedores = $proveedoresFiltrados->count();
            $empresasUnicasCount = $proveedoresFiltrados->unique('Empresa_asociada')->count();
            $masculinoCount = $proveedoresFiltrados->where('Sexo', 'Masculino')->count();
            $femeninoCount = $proveedoresFiltrados->where('Sexo', 'Femenino')->count();
            
            $stats = [
                [
                    'titulo' => 'Total Proveedores',
                    'valor' => $totalProveedores,
                    'icono' => 'fas fa-truck',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => request()->anyFilled(['nombre', 'empresa', 'sexo']) ? 'Con filtros aplicados' : 'Todos los proveedores'
                ],
                [
                    'titulo' => 'Empresas',
                    'valor' => $empresasUnicasCount,
                    'icono' => 'fas fa-building',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Empresas asociadas'
                ],
                [
                    'titulo' => 'Masculino',
                    'valor' => $masculinoCount,
                    'icono' => 'fas fa-mars',
                    'color' => '#3b82f6',
                    'gradiente' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                    'descripcion' => 'Proveedores hombres'
                ],
                [
                    'titulo' => 'Femenino',
                    'valor' => $femeninoCount,
                    'icono' => 'fas fa-venus',
                    'color' => '#ec4899',
                    'gradiente' => 'linear-gradient(135deg, #ec4899 0%, #db2777 100%)',
                    'descripcion' => 'Proveedoras mujeres'
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
                
                @if($stat['titulo'] != 'Total Proveedores')
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
                <p class="text-muted small mb-0">Encuentra proveedores específicos usando los siguientes filtros</p>
            </div>
        </div>

        <form id="filtrosForm" method="GET" action="{{ route('proveedores.index') }}">
            <div class="row g-3">
                <!-- Búsqueda por nombre -->
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
                               name="nombre"
                               class="form-control border-0 bg-light" 
                               placeholder="Nombre, apellidos..."
                               value="{{ request('nombre') }}">
                    </div>
                </div>

                <!-- Filtro por empresa -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-building me-1" style="color: #667eea;"></i>
                        Empresa
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-industry text-primary"></i>
                        </span>
                        <select name="empresa" class="form-select border-0 bg-light">
                            <option value="">Todas las empresas</option>
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
                    </div>
                </div>

                <!-- Filtro por sexo -->
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-venus-mars me-1" style="color: #667eea;"></i>
                        Sexo
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">
                            <i class="fas fa-user text-primary"></i>
                        </span>
                        <select name="sexo" class="form-select border-0 bg-light">
                            <option value="">Todos</option>
                            <option value="Masculino" {{ request('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ request('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>
                </div>

                <!-- Ordenamiento -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                        Ordenar por
                    </label>
                    <select name="sort_by" class="form-select border-0 bg-light">
                        <option value="id" {{ request('sort_by', 'id') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="Nombre" {{ request('sort_by') == 'Nombre' ? 'selected' : '' }}>Nombre</option>
                        <option value="Empresa_asociada" {{ request('sort_by') == 'Empresa_asociada' ? 'selected' : '' }}>Empresa</option>
                        <option value="Correo" {{ request('sort_by') == 'Correo' ? 'selected' : '' }}>Correo</option>
                    </select>
                </div>

                <!-- Dirección de orden -->
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-semibold">
                        <i class="fas fa-sort-amount-down me-1" style="color: #667eea;"></i>
                        Dirección
                    </label>
                    <select name="sort_order" class="form-select border-0 bg-light">
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
                                    return in_array($key, ['nombre', 'empresa', 'sexo']) 
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
        if(request('nombre')) $filtrosActivosLista[] = ['Nombre', request('nombre'), 'nombre'];
        if(request('empresa')) $filtrosActivosLista[] = ['Empresa', request('empresa'), 'empresa'];
        if(request('sexo')) $filtrosActivosLista[] = ['Sexo', request('sexo'), 'sexo'];
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
                <a href="{{ route('proveedores.index', array_merge(request()->except($filtro[2]), ['page' => 1])) }}" 
                   class="btn-close btn-close-sm ms-2" 
                   style="font-size: 0.6rem;">
                </a>
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
                    <span id="totalCount">{{ $proveedoresPaginated->total() }}</span> proveedor(es) registrado(s)
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
                    Orden: 
                    {{ 
                        request('sort_by', 'id') == 'id' ? 'ID' : 
                        (request('sort_by') == 'Nombre' ? 'Nombre' : 
                        (request('sort_by') == 'Empresa_asociada' ? 'Empresa' : 'Correo')) 
                    }}
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
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedoresPaginated as $proveedor)
                    @php
                        $nombreCompleto = $proveedor->Nombre . ' ' . $proveedor->ApPaterno . ($proveedor->ApMaterno ? ' ' . $proveedor->ApMaterno : '');
                        $tieneCompras = $proveedor->compras && $proveedor->compras->count() > 0;
                        $comprasCount = $tieneCompras ? $proveedor->compras->count() : 0;
                        
                        $sexoGradiente = $proveedor->Sexo == 'Masculino' 
                            ? 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)' 
                            : 'linear-gradient(135deg, #ec4899 0%, #db2777 100%)';
                    @endphp
                    <tr class="align-middle proveedor-row {{ $tieneCompras ? 'proveedor-protegido' : '' }}" 
                        data-nombre="{{ strtolower($nombreCompleto) }}" 
                        data-correo="{{ strtolower($proveedor->Correo) }}" 
                        data-telefono="{{ $proveedor->Telefono ?? '' }}"
                        data-empresa="{{ strtolower($proveedor->Empresa_asociada) }}"
                        data-sexo="{{ $proveedor->Sexo ?? '' }}"
                        data-id="{{ $proveedor->id }}">
                        
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
                                ">
                                    {{ strtoupper(substr($proveedor->Nombre, 0, 1)) }}{{ strtoupper(substr($proveedor->ApPaterno, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $nombreCompleto }}</h6>
                                    <small class="text-muted">ID: #{{ str_pad($proveedor->id, 5, '0', STR_PAD_LEFT) }}</small>
                                    @if($tieneCompras)
                                    <span class="badge ms-2 px-2 py-1" style="
                                        background: {{ $sexoGradiente }};
                                        color: white;
                                        border-radius: 50px;
                                        font-size: 0.65rem;
                                    ">
                                        <i class="fas fa-shopping-cart me-1"></i>{{ $comprasCount }}
                                    </span>
                                    @endif
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
                                    <span class="fw-medium">{{ Str::limit($proveedor->Empresa_asociada, 25) }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Contacto -->
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-envelope me-2" style="color: #667eea; width: 16px;"></i>
                                    <span class="small">{{ Str::limit($proveedor->Correo, 25) }}</span>
                                </span>
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-phone-alt me-2" style="color: #10b981; width: 16px;"></i>
                                    <span class="small">{{ $proveedor->Telefono }}</span>
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
                            ">
                                <i class="fas fa-{{ $proveedor->Sexo == 'Masculino' ? 'mars' : 'venus' }} me-1"></i>
                                {{ $proveedor->Sexo }}
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                   title="Editar proveedor">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($tieneCompras)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="mostrarErrorCompras({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}', {{ $comprasCount }})"
                                            title="No se puede eliminar: tiene compras asociadas">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="verificarComprasYeliminar({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}')"
                                            title="Eliminar proveedor">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Fila expandible con detalles del proveedor -->
                    <tr class="detalle-proveedor-row">
                        <td colspan="6" class="p-0 border-0">
                            <div class="collapse" id="detallesProveedor{{ $proveedor->id }}">
                                <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
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
                                                <i class="fas fa-exclamation-triangle fa-2x" style="color: #856404;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1" style="color: #856404;">⛔ Proveedor Protegido</h6>
                                                <p class="mb-0" style="color: #856404;">
                                                    Este proveedor tiene <span class="badge bg-warning">{{ $comprasCount }} compra(s)</span> asociadas y no puede ser eliminado.
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
                                                    Información Completa del Proveedor
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Nombre completo:</span>
                                                            <span class="fw-medium">{{ $nombreCompleto }}</span>
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
                                                            <span class="fw-medium">{{ $proveedor->Correo }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Teléfono:</span>
                                                            <span class="fw-medium">{{ $proveedor->Telefono }}</span>
                                                        </div>
                                                        <div class="detail-item d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Compras:</span>
                                                            <span class="fw-medium">{{ $comprasCount }}</span>
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
                                                        <span class="fw-medium">{{ $proveedor->Empresa_asociada }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Compras asociadas (si las hay) -->
                                                @if($tieneCompras)
                                                <div class="mt-4">
                                                    <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                                                        Compras Realizadas ({{ $comprasCount }})
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
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">ID Proveedor:</span>
                                                        <span class="fw-medium">#{{ str_pad($proveedor->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                    
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Compras realizadas:</span>
                                                        <span class="fw-medium">{{ $comprasCount }}</span>
                                                    </div>
                                                    
                                                    @if($tieneCompras)
                                                    <div class="detail-item d-flex justify-content-between mb-2">
                                                        <span class="text-muted">Total comprado:</span>
                                                        <span class="fw-bold" style="color: #10b981;">
                                                            ${{ number_format($proveedor->compras->sum('Total'), 2) }}
                                                        </span>
                                                    </div>
                                                    @endif
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
                                                        <span class="small">{{ $proveedor->Correo }}</span>
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
                                                        <span class="small">{{ $proveedor->Telefono }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Botones de acción -->
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       style="border-radius: 10px; border: 1px solid #e5e7eb;">
                                                        <i class="fas fa-edit me-1"></i> Editar Proveedor
                                                    </a>
                                                    
                                                    @if($tieneCompras)
                                                        <button type="button" 
                                                                class="btn btn-outline-warning btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="mostrarErrorCompras({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}', {{ $comprasCount }})">
                                                            <i class="fas fa-lock me-1"></i> Ver detalles
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm"
                                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                                onclick="verificarComprasYeliminar({{ $proveedor->id }}, '{{ addslashes($nombreCompleto) }}')">
                                                            <i class="fas fa-trash me-1"></i> Eliminar Proveedor
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
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state py-5">
                                <i class="fas fa-truck fa-4x mb-3" style="color: #9ca3af;"></i>
                                <h5 class="fw-bold mb-2">No hay proveedores registrados</h5>
                                <p class="text-muted mb-4">
                                    Comienza registrando el primer proveedor en el sistema.
                                </p>
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

        <!-- Paginación -->
        @if($proveedoresPaginated->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center" style="border-top: 1px solid #e5e7eb;">
            <div class="text-muted small">
                Página {{ $proveedoresPaginated->currentPage() }} de {{ $proveedoresPaginated->lastPage() }}
            </div>
            <div>
                {{ $proveedoresPaginated->appends(request()->query())->links() }}
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
                        <i class="fas fa-truck fa-3x text-danger"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="deleteProveedorNombreDisplay"></h5>
                <p class="text-muted mb-4" id="deleteProveedorId" style="font-size: 0.9rem;"></p>
                
                <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Proveedor a eliminar:</span>
                            <span class="fw-bold" id="deleteProveedorNombre"></span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                    <div class="text-start">
                        <strong class="text-danger">¡Atención!</strong>
                        <p class="mb-0 text-muted small">Esta acción es irreversible y eliminará permanentemente el proveedor del sistema.</p>
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

<!-- MODAL DE ERROR MEJORADO - Compras asociadas -->
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
                    Proveedor Protegido
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
                        <i class="fas fa-shopping-cart fa-4x text-warning"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" id="errorProveedorNombre"></h5>
                
                <div class="card border-warning border-2 mb-4" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <span class="badge bg-warning text-dark p-3" style="
                                font-size: 1.2rem;
                                border-radius: 50px;
                            ">
                                <i class="fas fa-shopping-cart me-2"></i>
                                <span id="errorComprasCount"></span> compras asociadas
                            </span>
                        </div>
                        <p class="text-muted mb-0">
                            Este proveedor no puede ser eliminado porque tiene compras registradas en el sistema.
                        </p>
                    </div>
                </div>
                
                <div class="alert alert-info bg-opacity-10 border-0 text-start" style="border-radius: 12px;">
                    <h6 class="fw-bold text-info mb-2">
                        <i class="fas fa-lightbulb me-2"></i>¿Cómo solucionarlo?
                    </h6>
                    <ol class="text-muted small mb-0 ps-3">
                        <li class="mb-1">Primero debes eliminar las compras asociadas</li>
                        <li class="mb-1">Luego podrás eliminar este proveedor</li>
                    </ol>
                </div>
            </div>
            
            <div class="modal-footer justify-content-center border-0 pb-4">
                <a href="{{ route('compras.index') }}" class="btn btn-warning px-5" style="border-radius: 50px;" id="verComprasBtn" target="_blank">
                    <i class="fas fa-eye me-2"></i>Ver compras
                </a>
                <button type="button" class="btn btn-light px-5" data-bs-dismiss="modal" style="border-radius: 50px;">
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
                <h6 class="fw-bold mb-2">Verificando compras</h6>
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
                : ['proveedor_nombre' => session('proveedor_nombre') ?? 'el proveedor', 
                   'compras_count' => session('compras_count') ?? 0,
                   'proveedor_id' => session('proveedor_id') ?? 0];
        @endphp
        setTimeout(function() {
            mostrarErrorCompras(
                '{{ $errorData["proveedor_nombre"] }}', 
                {{ $errorData["compras_count"] }},
                {{ $errorData["proveedor_id"] }}
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

function setupFilters() {
    // Este setup es para los filtros del lado del cliente
    // Los filtros principales se manejan con el formulario GET
    const searchInput = document.getElementById('searchInput');
    const filterEmpresa = document.getElementById('filterEmpresa');
    const filterSexo = document.getElementById('filterSexo');
    const sortBy = document.getElementById('sortBy');
    const sortOrder = document.getElementById('sortOrder');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    const proveedoresRows = document.querySelectorAll('.proveedor-row');
    const visibleCount = document.getElementById('visibleCount');
    const filterCount = document.getElementById('filterCount');
    const sortDisplay = document.getElementById('sortDisplay');
    const totalProveedores = document.getElementById('totalProveedores');
    const totalEmpresas = document.getElementById('totalEmpresas');
    const totalMasculino = document.getElementById('totalMasculino');
    const totalFemenino = document.getElementById('totalFemenino');

    function updateFilterCount() {
        let count = 0;
        if (searchInput.value.trim()) count++;
        if (filterEmpresa.value) count++;
        if (filterSexo.value) count++;
        filterCount.textContent = count;
    }

    function applyTableFilters() {
        const searchText = searchInput.value.toLowerCase();
        const empresaValue = filterEmpresa.value.toLowerCase();
        const sexoValue = filterSexo.value;
        let visibleRows = 0;
        let empresasUnicas = new Set();
        let masculinoCount = 0;
        let femeninoCount = 0;

        proveedoresRows.forEach(row => {
            const nombre = row.dataset.nombre;
            const correo = row.dataset.correo;
            const telefono = row.dataset.telefono;
            const empresa = row.dataset.empresa;
            const sexo = row.dataset.sexo;

            const matchesSearch = searchText === '' || 
                nombre.includes(searchText) || 
                correo.includes(searchText) || 
                telefono.includes(searchText);
            const matchesEmpresa = empresaValue === '' || empresa.includes(empresaValue);
            const matchesSexo = sexoValue === '' || sexo === sexoValue;

            if (matchesSearch && matchesEmpresa && matchesSexo) {
                row.style.display = '';
                visibleRows++;
                empresasUnicas.add(empresa);
                if (sexo === 'Masculino') masculinoCount++;
                if (sexo === 'Femenino') femeninoCount++;
            } else {
                row.style.display = 'none';
            }
        });

        visibleCount.textContent = visibleRows;
        totalProveedores.textContent = visibleRows;
        totalEmpresas.textContent = empresasUnicas.size;
        totalMasculino.textContent = masculinoCount;
        totalFemenino.textContent = femeninoCount;
        
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

        const rowsArray = Array.from(proveedoresRows);
        
        rowsArray.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortColumn) {
                case 'Nombre':
                    aValue = a.dataset.nombre;
                    bValue = b.dataset.nombre;
                    break;
                case 'Empresa':
                    aValue = a.dataset.empresa;
                    bValue = b.dataset.empresa;
                    break;
                case 'Correo':
                    aValue = a.dataset.correo;
                    bValue = b.dataset.correo;
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

        const tbody = document.querySelector('#proveedoresTable tbody');
        rowsArray.forEach(row => {
            tbody.appendChild(row);
            const detallesRow = row.nextElementSibling;
            if (detallesRow && detallesRow.classList.contains('detalle-proveedor-row')) {
                tbody.appendChild(detallesRow);
            }
        });
    }

    if (applyFilters && resetFilters) {
        applyFilters.addEventListener('click', function() {
            applyTableFilters();
            sortTable();
        });

        resetFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterEmpresa.value = '';
            filterSexo.value = '';
            sortBy.value = 'id';
            sortOrder.value = 'asc';
            applyTableFilters();
            sortTable();
        });
    }

    if (sortBy && sortOrder) {
        sortBy.addEventListener('change', sortTable);
        sortOrder.addEventListener('change', sortTable);
    }

    if (searchInput) searchInput.addEventListener('input', applyTableFilters);
    if (filterEmpresa) filterEmpresa.addEventListener('change', applyTableFilters);
    if (filterSexo) filterSexo.addEventListener('change', applyTableFilters);

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

async function verificarComprasYeliminar(proveedorId, nombreProveedor) {
    showLoadingModal();
    
    try {
        const response = await fetch(`/proveedores/${proveedorId}/compras`, {
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
        
        if (data.tieneCompras) {
            mostrarErrorCompras(nombreProveedor, data.comprasCount, proveedorId);
        } else {
            mostrarModalEliminacion(proveedorId, nombreProveedor);
        }
        
    } catch (error) {
        console.error('Error:', error);
        
        await hideLoadingModal();
        await new Promise(resolve => setTimeout(resolve, 100));
        
        mostrarModalEliminacion(proveedorId, nombreProveedor);
    }
}

function mostrarModalEliminacion(proveedorId, nombreProveedor) {
    try {
        forceCleanupModals();
        
        document.getElementById('deleteProveedorNombre').textContent = nombreProveedor;
        document.getElementById('deleteProveedorNombreDisplay').textContent = `¿Eliminar "${nombreProveedor}"?`;
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
        alert('Error al preparar la eliminación. Por favor, recarga la página.');
    }
}

function mostrarErrorCompras(nombreProveedor, comprasCount, proveedorId) {
    try {
        forceCleanupModals();
        
        const existingModal = document.getElementById('foreignKeyErrorModal');
        const modalInstance = bootstrap.Modal.getInstance(existingModal);
        if (modalInstance) {
            modalInstance.hide();
            forceCleanupModals();
        }
        
        setTimeout(() => {
            document.getElementById('errorProveedorNombre').innerHTML = `
                <span class="text-warning">${nombreProveedor}</span>
                <small class="d-block text-muted mt-1">Proveedor con compras asociadas</small>
            `;
            document.getElementById('errorComprasCount').textContent = comprasCount;
            
            const verComprasBtn = document.getElementById('verComprasBtn');
            if (verComprasBtn && proveedorId) {
                verComprasBtn.href = `/compras?proveedor_id=${proveedorId}`;
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

function setDeleteProveedor(proveedorId, nombreProveedor) {
    mostrarModalEliminacion(proveedorId, nombreProveedor);
}

// Estilos para animación de spin
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
    
    .btn-expand-proveedor:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: transparent !important;
    }
    
    .proveedor-protegido {
        background-color: rgba(255, 193, 7, 0.02);
    }
    
    .proveedor-protegido:hover {
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
#proveedores-page .btn-outline-warning:hover {
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