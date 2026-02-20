@extends('layouts.app')

@section('content')
<div id="respaldos-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
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
                    <i class="fas fa-database fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Gestión de Respaldos
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-shield-alt me-1" style="color: #10b981;"></i>
                        Administra los respaldos de seguridad de la base de datos del sistema
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route('respaldos.create') }}" class="btn btn-primary" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    border-radius: 14px;
                    padding: 12px 28px;
                    font-weight: 600;
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-plus-circle me-2"></i> Nuevo Respaldo
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

    <!-- LEYENDA DE RESTAURACIÓN EXITOSA MEJORADA -->
    @if(session('restauracion_exitosa'))
    <div class="alert alert-modern alert-success mb-4 p-0" style="
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        overflow: hidden;
    ">
        <div class="d-flex flex-column flex-lg-row">
            <div class="success-icon p-4 d-flex align-items-center justify-content-center" style="
                background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
                color: white;
            ">
                <i class="fas fa-check-circle fa-3x"></i>
            </div>
            <div class="flex-grow-1 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-2" style="color: #155724;">
                            <i class="fas fa-database me-2"></i>✅ ¡RESTAURACIÓN COMPLETADA EXITOSAMENTE!
                        </h3>
                        <p class="mb-3 fs-5" style="color: #155724;">{{ session('restauracion_exitosa') }}</p>
                        
                        <div class="restore-details p-3 rounded-3" style="
                            background: rgba(255, 255, 255, 0.5);
                            border-left: 5px solid #28a745;
                        ">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-badge me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #28a74520;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                        </div>
                                        <span class="fw-medium">✅ La base de datos ha sido restaurada correctamente con todos tus datos.</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-badge me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #28a74520;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-file-archive" style="color: #28a745;"></i>
                                        </div>
                                        <span class="fw-medium">📁 Archivo restaurado: <strong>{{ session('archivo_restaurado') ?? 'Respaldo' }}</strong></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-badge me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #28a74520;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-clock" style="color: #28a745;"></i>
                                        </div>
                                        <span class="fw-medium">⏱️ Fecha y hora: <strong>{{ session('fecha_restauracion') ?? \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                                    <div class="vr d-none d-md-block" style="height: 60px; background: #28a745;"></div>
                                    <div class="d-flex flex-column ms-md-3">
                                        <span class="badge p-3 mb-2" style="
                                            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
                                            color: white;
                                            border-radius: 50px;
                                            font-size: 1rem;
                                        ">
                                            <i class="fas fa-check-circle me-1"></i> Éxito
                                        </span>
                                        <span class="text-muted small">Restauración verificada</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- LEYENDA DE CIERRE DE SESIÓN MEJORADA -->
    @if(session('sesion_cerrada'))
    <div class="alert alert-modern alert-warning mb-4 p-0" style="
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.2);
        overflow: hidden;
    ">
        <div class="d-flex flex-column flex-lg-row">
            <div class="warning-icon p-4 d-flex align-items-center justify-content-center" style="
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                color: white;
            ">
                <i class="fas fa-shield-alt fa-3x"></i>
            </div>
            <div class="flex-grow-1 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-2" style="color: #856404;">
                            <i class="fas fa-user-lock me-2"></i>🔐 ¡SESIÓN CERRADA AUTOMÁTICAMENTE!
                        </h3>
                        <p class="mb-3 fs-5" style="color: #856404;">{{ session('sesion_cerrada') }}</p>
                        
                        <div class="session-details p-3 rounded-3" style="
                            background: rgba(255, 255, 255, 0.5);
                            border-left: 5px solid #ffc107;
                        ">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-badge me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #ffc10720;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-check-circle" style="color: #ffc107;"></i>
                                        </div>
                                        <span class="fw-medium">✅ <span class="text-success">LA RESTAURACIÓN FUE EXITOSA</span> - Todos tus datos fueron recuperados correctamente.</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-badge me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #ffc10720;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-redo-alt" style="color: #ffc107;"></i>
                                        </div>
                                        <span class="fw-medium">🔄 Por seguridad, tu sesión fue cerrada automáticamente después de la restauración.</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-badge me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #ffc10720;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-database" style="color: #ffc107;"></i>
                                        </div>
                                        <span class="fw-medium">🗄️ Base de datos restaurada desde: <strong>{{ session('archivo_restaurado') ?? 'respaldo' }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                                    <div class="vr d-none d-md-block" style="height: 80px; background: #ffc107;"></div>
                                    <div class="d-flex flex-column ms-md-3">
                                        <a href="{{ route('login') }}" class="btn btn-warning btn-lg shadow-sm mb-2" style="border-radius: 50px;">
                                            <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                        </a>
                                        <a href="{{ route('respaldos.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 50px;">
                                            <i class="fas fa-database me-1"></i> Ver Respaldos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Botón para restaurar desde archivo SQL - MEJORADO -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="restore-card" style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 24px;
                padding: 2rem;
                box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            ">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="restore-icon" style="
                            width: 70px;
                            height: 70px;
                            background: rgba(255, 255, 255, 0.2);
                            border-radius: 18px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 2rem;
                        ">
                            <i class="fas fa-redo-alt"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-1">Restaurar Base de Datos</h4>
                            <p class="text-white-50 mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Importa un archivo SQL para restaurar la base de datos
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#restaurarModal" style="
                        border-radius: 50px;
                        padding: 1rem 2rem;
                        font-weight: 600;
                        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                    ">
                        <i class="fas fa-upload me-2"></i> Seleccionar Archivo SQL
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas Mejoradas -->
    <div class="row g-4 mb-4">
        @php
            $totalRespaldos = $respaldos->count();
            $disponibles = $respaldos->filter(function($r) { return file_exists($r->Ruta); })->count();
            $noEncontrados = $totalRespaldos - $disponibles;
            
            $totalSize = 0;
            foreach($respaldos as $respaldo) {
                if(file_exists($respaldo->Ruta)) {
                    $totalSize += filesize($respaldo->Ruta);
                }
            }
            
            $ultimoRespaldo = $respaldos->first();
            
            $stats = [
                [
                    'titulo' => 'Total Respaldos',
                    'valor' => $totalRespaldos,
                    'subvalor' => $controller->formatearTamaño($totalSize),
                    'icono' => 'fas fa-database',
                    'color' => '#667eea',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'descripcion' => 'En el sistema'
                ],
                [
                    'titulo' => 'Disponibles',
                    'valor' => $disponibles,
                    'icono' => 'fas fa-check-circle',
                    'color' => '#10b981',
                    'gradiente' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'descripcion' => 'Archivos accesibles'
                ],
                [
                    'titulo' => 'No Encontrados',
                    'valor' => $noEncontrados,
                    'icono' => 'fas fa-times-circle',
                    'color' => '#ef4444',
                    'gradiente' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                    'descripcion' => 'Requieren atención'
                ],
                [
                    'titulo' => 'Último Respaldo',
                    'valor' => $ultimoRespaldo ? \Carbon\Carbon::parse($ultimoRespaldo->Fecha)->format('d/m/Y') : 'N/A',
                    'subvalor' => $ultimoRespaldo ? \Carbon\Carbon::parse($ultimoRespaldo->Fecha)->diffForHumans() : 'Sin respaldos',
                    'icono' => 'fas fa-clock',
                    'color' => '#3b82f6',
                    'gradiente' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                    'descripcion' => 'Fecha del último'
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
                
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #1f2937;">{{ $stat['valor'] }}</h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ $stat['titulo'] }}</p>
                @if(isset($stat['subvalor']))
                <small class="text-muted">{{ $stat['subvalor'] }}</small>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Panel de búsqueda y filtros MEJORADO -->
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
                <h5 class="fw-bold mb-1" style="color: #1f2937;">Búsqueda y Filtros</h5>
                <p class="text-muted small mb-0">Encuentra respaldos específicos usando los siguientes filtros</p>
            </div>
        </div>

        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-search me-1" style="color: #667eea;"></i>
                    Buscar Respaldo
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-0 bg-light" 
                           id="searchInput" 
                           placeholder="Buscar por nombre, descripción o archivo..."
                           onkeyup="filtrarTabla()">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-check-circle me-1" style="color: #667eea;"></i>
                    Estado
                </label>
                <select class="form-select border-0 bg-light" id="estadoFilter" onchange="filtrarTabla()">
                    <option value="">Todos los estados</option>
                    <option value="disponible">✅ Disponibles</option>
                    <option value="no_encontrado">❌ No encontrados</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">
                    <i class="fas fa-sort me-1" style="color: #667eea;"></i>
                    Ordenar por
                </label>
                <select class="form-select border-0 bg-light" id="ordenFilter" onchange="filtrarTabla()">
                    <option value="fecha_desc">📅 Más recientes primero</option>
                    <option value="fecha_asc">📅 Más antiguos primero</option>
                    <option value="nombre_asc">📝 Nombre (A-Z)</option>
                    <option value="nombre_desc">📝 Nombre (Z-A)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla de respaldos MEJORADA -->
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
                    <i class="fas fa-list me-2 text-primary"></i>
                    Lista de Respaldos
                </h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ $respaldos->count() }} respaldo(s) registrado(s) | 
                    <i class="fas fa-archive ms-2 me-1"></i>Haz clic en las filas para ver detalles
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tablaRespaldos">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="py-3 ps-4" width="50px"></th>
                        <th class="py-3">Nombre</th>
                        <th class="py-3">Descripción</th>
                        <th class="py-3">Archivo</th>
                        <th class="py-3">Fecha</th>
                        <th class="py-3">Usuario</th>
                        <th class="py-3">Tamaño</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3 pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($respaldos as $respaldo)
                        @php
                            $archivoExiste = file_exists($respaldo->Ruta);
                            $tamaño = $archivoExiste ? $controller->formatearTamaño(filesize($respaldo->Ruta)) : 'No disponible';
                            
                            $estadoColor = $archivoExiste ? 'success' : 'danger';
                            $estadoGradiente = $archivoExiste 
                                ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' 
                                : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                            $estadoIcon = $archivoExiste ? 'fa-check-circle' : 'fa-times-circle';
                            $estadoTexto = $archivoExiste ? 'Disponible' : 'No encontrado';
                            
                            $usuarioCorreo = null;
                            if(isset($usar_join) && $usar_join) {
                                $usuarioCorreo = $respaldo->usuario_correo ?? null;
                            } else {
                                $usuario = isset($usuarios[$respaldo->Usuario]) ? $usuarios[$respaldo->Usuario] : null;
                                $usuarioCorreo = $usuario ? $usuario->correo : null;
                            }
                        @endphp
                        <tr class="align-middle respaldo-row" data-estado="{{ $archivoExiste ? 'disponible' : 'no_encontrado' }}">
                            <!-- Botón expandir -->
                            <td class="ps-4">
                                <button class="btn btn-sm btn-expand-respaldo" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesRespaldo{{ $respaldo->id }}"
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
                            
                            <!-- Nombre -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="respaldo-avatar me-3" style="
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
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $respaldo->Nombre }}</h6>
                                        <small class="text-muted">ID: #{{ str_pad($respaldo->id, 5, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Descripción -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-badge me-2" style="
                                        width: 32px;
                                        height: 32px;
                                        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
                                        border-radius: 8px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                    ">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <span>{{ Str::limit($respaldo->Descripcion ?? 'Sin descripción', 35) }}</span>
                                </div>
                            </td>

                            <!-- Archivo -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-badge me-2" style="
                                        width: 32px;
                                        height: 32px;
                                        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                                        border-radius: 8px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                    ">
                                        <i class="fas fa-file-archive"></i>
                                    </div>
                                    <code class="small">{{ basename($respaldo->Ruta) }}</code>
                                </div>
                            </td>

                            <!-- Fecha -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-badge me-2" style="
                                        width: 32px;
                                        height: 32px;
                                        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                        border-radius: 8px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                    ">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('H:i') }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Usuario -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-badge me-2" style="
                                        width: 32px;
                                        height: 32px;
                                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                        border-radius: 8px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                    ">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ Str::limit($usuarioCorreo ?? 'Usuario no encontrado', 20) }}</span>
                                        <small class="text-muted d-block">ID: {{ $respaldo->Usuario }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Tamaño -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-badge me-2" style="
                                        width: 32px;
                                        height: 32px;
                                        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
                                        border-radius: 8px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                    ">
                                        <i class="fas fa-weight-hanging"></i>
                                    </div>
                                    <span class="fw-bold">{{ $tamaño }}</span>
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
                                    <i class="fas {{ $estadoIcon }} me-1"></i>{{ $estadoTexto }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    @if($archivoExiste)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning" 
                                                style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                                onclick="restaurarRespaldo({{ $respaldo->id }}, '{{ addslashes($respaldo->Nombre) }}')"
                                                title="Restaurar base de datos">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                        <a href="{{ route('respaldos.descargar', $respaldo->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                           title="Descargar respaldo">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="border-radius: 10px; border: 1px solid #e5e7eb;"
                                            onclick="confirmarEliminacion({{ $respaldo->id }})"
                                            title="Eliminar respaldo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles del respaldo -->
                        <tr class="detalle-respaldo-row">
                            <td colspan="9" class="p-0 border-0">
                                <div class="collapse" id="detallesRespaldo{{ $respaldo->id }}">
                                    <div class="p-4" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                        <div class="row g-4">
                                            <!-- Información del respaldo -->
                                            <div class="col-md-7">
                                                <div class="detail-card p-3" style="
                                                    background: white;
                                                    border-radius: 16px;
                                                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                                ">
                                                    <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                        <i class="fas fa-info-circle me-2 text-primary"></i>
                                                        Información del Respaldo
                                                    </h6>
                                                    
                                                    <div class="mb-3">
                                                        <span class="text-muted d-block mb-2">Descripción completa:</span>
                                                        <div class="p-3 bg-light rounded-3">
                                                            {{ $respaldo->Descripcion ?? 'Sin descripción' }}
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <div class="detail-item d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Fecha creación:</span>
                                                                <span class="fw-medium">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('d/m/Y H:i:s') }}</span>
                                                            </div>
                                                            <div class="detail-item d-flex justify-content-between">
                                                                <span class="text-muted">Usuario:</span>
                                                                <div class="d-flex align-items-center gap-1">
                                                                    <span class="fw-medium">{{ $usuarioCorreo ?? 'N/A' }}</span>
                                                                    <small class="text-muted">(ID: {{ $respaldo->Usuario }})</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="detail-item d-flex justify-content-between mb-2">
                                                                <span class="text-muted">Tamaño:</span>
                                                                <span class="fw-bold">{{ $tamaño }}</span>
                                                            </div>
                                                            <div class="detail-item d-flex justify-content-between">
                                                                <span class="text-muted">Estado:</span>
                                                                <span class="badge px-3 py-1" style="background: {{ $estadoGradiente }}; color: white;">
                                                                    <i class="fas {{ $estadoIcon }} me-1"></i>{{ $estadoTexto }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <span class="text-muted d-block mb-2">Ruta del archivo:</span>
                                                        <div class="bg-dark text-light p-3 rounded-3 small font-monospace">
                                                            {{ $respaldo->Ruta }}
                                                        </div>
                                                        @if(!$archivoExiste)
                                                        <div class="alert alert-danger mt-2 py-2 small">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Alerta: Archivo físico no encontrado en el servidor
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Estadísticas y acciones -->
                                            <div class="col-md-5">
                                                <div class="detail-card p-3" style="
                                                    background: white;
                                                    border-radius: 16px;
                                                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                                                ">
                                                    <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                                                        Estadísticas
                                                    </h6>
                                                    
                                                    <div class="mb-4">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-muted">Tamaño del archivo:</span>
                                                            <span class="fw-bold">{{ $tamaño }}</span>
                                                        </div>
                                                        <div class="progress" style="height: 10px; background: #e5e7eb;">
                                                            @php
                                                                $maxSize = 100 * 1024 * 1024; // 100MB como referencia
                                                                $fileSize = $archivoExiste ? filesize($respaldo->Ruta) : 0;
                                                                $porcentaje = min(100, ($fileSize / $maxSize) * 100);
                                                            @endphp
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: {{ $porcentaje }}%; background: {{ $estadoGradiente }}; border-radius: 10px;"
                                                                 aria-valuenow="{{ $fileSize }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="{{ $maxSize }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <h6 class="text-muted small mb-2">Acciones disponibles:</h6>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @if($archivoExiste)
                                                            <button type="button" 
                                                                    class="btn btn-outline-warning btn-sm"
                                                                    style="border-radius: 50px;"
                                                                    onclick="restaurarRespaldo({{ $respaldo->id }}, '{{ addslashes($respaldo->Nombre) }}')">
                                                                <i class="fas fa-redo me-1"></i> Restaurar
                                                            </button>
                                                            <a href="{{ route('respaldos.descargar', $respaldo->id) }}" 
                                                               class="btn btn-outline-primary btn-sm"
                                                               style="border-radius: 50px;">
                                                                <i class="fas fa-download me-1"></i> Descargar
                                                            </a>
                                                            @endif
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    style="border-radius: 50px;"
                                                                    onclick="confirmarEliminacion({{ $respaldo->id }})">
                                                                <i class="fas fa-trash me-1"></i> Eliminar
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <hr style="margin: 1rem 0; border-color: #e5e7eb;">
                                                    
                                                    <div class="d-grid gap-2">
                                                        @if($archivoExiste)
                                                        <button type="button" 
                                                                class="btn btn-warning w-100"
                                                                style="border-radius: 50px;"
                                                                onclick="restaurarRespaldo({{ $respaldo->id }}, '{{ addslashes($respaldo->Nombre) }}')">
                                                            <i class="fas fa-redo me-2"></i> Restaurar Base de Datos
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
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state py-5">
                                    <i class="fas fa-database fa-4x mb-3" style="color: #9ca3af;"></i>
                                    <h5 class="fw-bold mb-2">No hay respaldos registrados</h5>
                                    <p class="text-muted mb-4">Crea tu primer respaldo para empezar a proteger tus datos</p>
                                    <a href="{{ route('respaldos.create') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plus-circle me-2"></i> Crear Primer Respaldo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DE RESTAURACIÓN DESDE ARCHIVO SQL MEJORADO -->
<div class="modal fade" id="restaurarModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <div class="modal-header bg-gradient-warning text-white" style="
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                border: none;
                padding: 1.5rem;
            ">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon" style="
                        width: 50px;
                        height: 50px;
                        background: rgba(255, 255, 255, 0.2);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                    ">
                        <i class="fas fa-redo-alt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1">Restaurar Base de Datos</h5>
                        <p class="text-white-50 mb-0 small">Importar archivo SQL para restaurar datos</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <!-- Elementos decorativos -->
                <div class="position-absolute" style="top: -20px; right: -20px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                <div class="position-absolute" style="bottom: -30px; left: -30px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%;"></div>
            </div>
            
            <form id="restaurarForm" action="{{ route('respaldos.restaurar-archivo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <!-- Advertencia crítica -->
                    <div class="alert alert-danger mb-4" style="
                        border-radius: 16px;
                        border-left: 5px solid #dc3545;
                    ">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle fa-2x me-3" style="color: #dc3545;"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-2" style="color: #721c24;">¡ADVERTENCIA CRÍTICA!</h5>
                                <p class="mb-0" style="color: #721c24;">
                                    Esta acción <strong>ELIMINARÁ</strong> toda la base de datos actual y <strong>CREARÁ UNA NUEVA</strong> con los datos del archivo SQL seleccionado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de archivo -->
                    <div class="file-upload-area mb-4 p-4" style="
                        background: #f8fafc;
                        border-radius: 16px;
                        border: 2px dashed #667eea;
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    " onclick="document.getElementById('sqlFile').click()">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #667eea;"></i>
                        <h6 class="fw-bold mb-1">Seleccionar Archivo SQL</h6>
                        <p class="small text-muted mb-2">Haz clic para elegir un archivo o arrastra aquí</p>
                        <span class="badge px-3 py-2" style="background: #e5e7eb; color: #4b5563;">
                            Formatos: .sql, .gz, .zip
                        </span>
                        <input type="file" 
                               class="d-none" 
                               id="sqlFile" 
                               name="sql_file" 
                               accept=".sql,.gz,.zip"
                               required>
                    </div>

                    <!-- Información del archivo (dinámica) -->
                    <div id="fileInfo" class="d-none mb-4">
                        <div class="card border-0" style="background: #f8fafc; border-radius: 16px;">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3" style="color: #1f2937;">
                                    <i class="fas fa-file-code me-2 text-primary"></i>
                                    Información del Archivo
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="detail-item d-flex justify-content-between">
                                            <span class="text-muted">Nombre:</span>
                                            <span class="fw-medium" id="fileName"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item d-flex justify-content-between">
                                            <span class="text-muted">Tamaño:</span>
                                            <span class="fw-medium" id="fileSize"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="detail-item d-flex justify-content-between">
                                            <span class="text-muted">Ruta:</span>
                                            <span class="text-muted small font-monospace" id="filePath"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opciones de restauración -->
                    <div class="options-card mb-4 p-3" style="
                        background: #f8fafc;
                        border-radius: 16px;
                    ">
                        <h6 class="fw-bold mb-3" style="color: #1f2937;">
                            <i class="fas fa-cogs me-2 text-primary"></i>
                            Opciones de Restauración
                        </h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="dropDatabase" name="drop_database" checked>
                            <label class="form-check-label fw-medium" for="dropDatabase">
                                Eliminar base de datos actual antes de restaurar
                            </label>
                            <div class="form-text small text-muted ms-4">
                                Esta opción es necesaria para una restauración completa
                            </div>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="createDatabase" name="create_database" checked>
                            <label class="form-check-label fw-medium" for="createDatabase">
                                Crear nueva base de datos si no existe
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="backupCurrent" name="backup_current" checked>
                            <label class="form-check-label fw-medium" for="backupCurrent">
                                Crear respaldo de la base de datos actual antes de restaurar
                            </label>
                            <div class="form-text small text-muted ms-4">
                                Recomendado: Crea un respaldo de seguridad de los datos actuales
                            </div>
                        </div>
                    </div>

                    <!-- Confirmación final -->
                    <div class="confirm-card p-3" style="
                        background: #f8fafc;
                        border-radius: 16px;
                        border-left: 5px solid #ffc107;
                    ">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-shield-alt fa-2x me-3" style="color: #ffc107;"></i>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-2">Confirmación Final</h6>
                                <p class="small text-muted mb-2">Para proceder con la restauración, escriba <code>CONFIRMAR</code> en el campo de abajo:</p>
                                <input type="text" 
                                       class="form-control" 
                                       id="confirmText" 
                                       name="confirmacion"
                                       placeholder="Escriba CONFIRMAR aquí"
                                       oninput="validarConfirmacionRestaurar()"
                                       required>
                                <div id="confirmError" class="text-danger small mt-1 d-none">
                                    Debe escribir exactamente "CONFIRMAR"
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning px-4" id="submitRestaurar" disabled style="border-radius: 50px;">
                        <i class="fas fa-redo me-2"></i> Restaurar Base de Datos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario oculto para restaurar respaldo existente -->
<form id="restaurarFormulario" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="confirmacion" value="CONFIRMAR">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Token CSRF global
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    setupExpandButtons();
    
    // Mostrar información del archivo cuando se selecciona
    const sqlFileInput = document.getElementById('sqlFile');
    if (sqlFileInput) {
        sqlFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const filePath = document.getElementById('filePath');
            
            if (file) {
                // Validar extensiones
                const validExtensions = ['.sql', '.gz', '.zip'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                
                if (!validExtensions.includes(fileExtension)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato inválido',
                        text: 'Solo se permiten archivos .sql, .gz o .zip',
                        confirmButtonColor: '#dc3545'
                    });
                    e.target.value = '';
                    fileInfo.classList.add('d-none');
                    return;
                }
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePath.textContent = file.name;
                fileInfo.classList.remove('d-none');
            } else {
                fileInfo.classList.add('d-none');
            }
        });
    }
    
    // Validar formulario de restauración
    const restaurarForm = document.getElementById('restaurarForm');
    if (restaurarForm) {
        restaurarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('sqlFile');
            const confirmText = document.getElementById('confirmText').value;
            
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un archivo SQL',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            if (confirmText !== 'CONFIRMAR') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe escribir "CONFIRMAR" para confirmar',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            Swal.fire({
                title: '¿Restaurar Base de Datos?',
                html: `
                    <div class="text-start">
                        <div class="alert alert-danger border-danger border-opacity-50 mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>¡ADVERTENCIA FINAL!</strong>
                            <div class="mt-2">
                                Esta acción realizará lo siguiente:
                                <ul class="mb-2 mt-2">
                                    <li>Eliminará la base de datos actual</li>
                                    <li>Creará una nueva base de datos</li>
                                    <li>Importará datos desde el archivo SQL</li>
                                    <li>Podría tomar varios minutos</li>
                                </ul>
                            </div>
                        </div>
                        <p class="mb-0"><strong>¿Está completamente seguro de continuar?</strong></p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-redo me-2"></i> Sí, Restaurar',
                cancelButtonText: '<i class="fas fa-times me-2"></i> Cancelar',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                customClass: { popup: 'shadow-lg' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Restaurando Base de Datos',
                        html: `
                            <div class="text-center">
                                <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;"></div>
                                <h5 class="text-warning mb-2">Restauración en progreso</h5>
                                <p class="mb-2">Esto puede tomar varios minutos...</p>
                                <small class="text-muted">No cierre esta ventana</small>
                            </div>
                        `,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        backdrop: 'rgba(0,0,0,0.7)'
                    });
                    this.submit();
                }
            });
        });
    }
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function setupExpandButtons() {
    document.querySelectorAll('.btn-expand-respaldo').forEach(button => {
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

function validarConfirmacionRestaurar() {
    const input = document.getElementById('confirmText');
    const error = document.getElementById('confirmError');
    const submitBtn = document.getElementById('submitRestaurar');
    
    if (input && error && submitBtn) {
        if (input.value === 'CONFIRMAR') {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            error.classList.add('d-none');
            submitBtn.disabled = false;
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            error.classList.remove('d-none');
            submitBtn.disabled = true;
        }
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function filtrarTabla() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const estadoFilter = document.getElementById('estadoFilter').value;
    const ordenFilter = document.getElementById('ordenFilter').value;
    
    const rows = Array.from(document.querySelectorAll('#tablaRespaldos tbody tr.respaldo-row'));
    
    // Filtrar
    rows.forEach(row => {
        const texto = row.textContent.toLowerCase();
        const estado = row.getAttribute('data-estado');
        
        const matchSearch = searchInput === '' || texto.includes(searchInput);
        const matchEstado = estadoFilter === '' || estado === estadoFilter;
        
        row.style.display = (matchSearch && matchEstado) ? '' : 'none';
        
        const detallesRow = row.nextElementSibling;
        if (detallesRow && detallesRow.classList.contains('detalle-respaldo-row')) {
            detallesRow.style.display = (matchSearch && matchEstado) ? '' : 'none';
        }
    });
    
    // Ordenar (simplificado - recargar página con parámetros)
    if (ordenFilter !== 'fecha_desc' && ordenFilter !== 'fecha_asc' && 
        ordenFilter !== 'nombre_asc' && ordenFilter !== 'nombre_desc') {
        return;
    }
    
    const visibleRows = rows.filter(row => row.style.display !== 'none');
    
    visibleRows.sort((a, b) => {
        let aVal, bVal;
        
        if (ordenFilter.includes('fecha')) {
            const aFecha = a.querySelector('td:nth-child(5) .fw-medium')?.textContent || '';
            const bFecha = b.querySelector('td:nth-child(5) .fw-medium')?.textContent || '';
            const [aD, aM, aY] = aFecha.split('/');
            const [bD, bM, bY] = bFecha.split('/');
            aVal = new Date(`${aY}-${aM}-${aD}`).getTime();
            bVal = new Date(`${bY}-${bM}-${bD}`).getTime();
        } else {
            aVal = a.querySelector('td:nth-child(1) .fw-bold')?.textContent || '';
            bVal = b.querySelector('td:nth-child(1) .fw-bold')?.textContent || '';
        }
        
        const isAsc = ordenFilter.includes('asc');
        return isAsc ? aVal - bVal : bVal - aVal;
    });
    
    const tbody = document.querySelector('#tablaRespaldos tbody');
    visibleRows.forEach(row => {
        tbody.appendChild(row);
        const detallesRow = row.nextElementSibling;
        if (detallesRow && detallesRow.classList.contains('detalle-respaldo-row')) {
            tbody.appendChild(detallesRow);
        }
    });
}

function restaurarRespaldo(id, nombre) {
    Swal.fire({
        title: '¿Restaurar Base de Datos?',
        html: `
            <div class="text-start">
                <p>¿Está seguro de restaurar la base de datos desde este respaldo?</p>
                <div class="alert alert-danger border-danger border-opacity-50">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>ADVERTENCIA:</strong> Esta acción eliminará la base de datos actual y restaurará los datos del respaldo.
                </div>
                <div class="mt-3">
                    <strong>Respaldo seleccionado:</strong>
                    <div class="bg-light p-2 rounded mt-1">
                        <strong>${nombre}</strong>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-bold">Confirmación:</label>
                    <input type="text" id="confirmRestoreInput" class="form-control" placeholder="Escriba CONFIRMAR aquí">
                    <small class="text-muted">Debe escribir exactamente "CONFIRMAR" para proceder</small>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-redo me-2"></i> Sí, Restaurar',
        cancelButtonText: '<i class="fas fa-times me-2"></i> Cancelar',
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        preConfirm: () => {
            const confirmText = document.getElementById('confirmRestoreInput').value;
            if (confirmText !== 'CONFIRMAR') {
                Swal.showValidationMessage('Debe escribir exactamente "CONFIRMAR"');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/respaldos/restaurar/${id}`;
            form.style.display = 'none';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            const confirmInput = document.createElement('input');
            confirmInput.type = 'hidden';
            confirmInput.name = 'confirmacion';
            confirmInput.value = 'CONFIRMAR';
            form.appendChild(confirmInput);
            
            document.body.appendChild(form);
            
            Swal.fire({
                title: 'Restaurando Base de Datos',
                html: `
                    <div class="text-center">
                        <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;"></div>
                        <h5 class="text-warning">Restauración en progreso</h5>
                        <p>Esto puede tomar varios minutos...</p>
                        <small class="text-muted">No cierre esta ventana</small>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                backdrop: 'rgba(0,0,0,0.7)'
            });
            
            form.submit();
        }
    });
}

function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar Respaldo?',
        text: '¿Está seguro de eliminar este respaldo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, Eliminar',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarRespaldo(id);
        }
    });
}

function eliminarRespaldo(id) {
    Swal.fire({
        title: 'Eliminando Respaldo',
        html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>',
        allowOutsideClick: false,
        showConfirmButton: false
    });
    
    fetch(`/respaldos/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Eliminado!',
                text: data.message || 'Respaldo eliminado exitosamente',
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK'
            }).then(() => window.location.reload());
        } else {
            throw new Error(data.message || 'Error al eliminar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: error.message || 'No se pudo eliminar el respaldo',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Agregar estilos de animación
const style = document.createElement('style');
style.textContent = `
    .stat-card:hover .stat-decoration {
        transform: scale(1.2);
    }
    
    .btn-expand-respaldo:hover {
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
    
    .file-upload-area:hover {
        background: #f1f5f9 !important;
        border-color: #764ba2 !important;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    @keyframes pulseIcon {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .delete-icon-circle {
        animation: pulseIcon 2s infinite;
    }
`;
document.head.appendChild(style);
</script>
@endpush

<style>
#respaldos-page {
    padding-top: 20px;
}

#respaldos-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
    background: #f8fafc;
}

#respaldos-page .table tbody tr {
    transition: all 0.2s ease;
}

#respaldos-page .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

#respaldos-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#respaldos-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#respaldos-page .card {
    border-radius: 12px;
}

#respaldos-page .detalle-respaldo-row {
    background-color: #f8fafc;
}

#respaldos-page .collapse {
    transition: all 0.3s ease;
}

#respaldos-page .collapsing {
    transition: height 0.35s ease;
}

#respaldos-page .form-control:focus,
#respaldos-page .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

@media (max-width: 768px) {
    #respaldos-page .btn-expand-respaldo {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #respaldos-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #respaldos-page .detalle-respaldo-row .row {
        flex-direction: column;
    }
    
    #respaldos-page .btn-group .btn {
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

#respaldos-page .collapse.show {
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
#respaldos-page .btn-outline-primary:hover,
#respaldos-page .btn-outline-danger:hover,
#respaldos-page .btn-outline-warning:hover,
#respaldos-page .btn-outline-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
</style>
@endsection