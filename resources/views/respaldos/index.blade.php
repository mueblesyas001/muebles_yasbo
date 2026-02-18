@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">
                <i class="fas fa-database me-2"></i>Gestión de Respaldos
            </h1>
            <p class="text-muted mb-0">Administra los respaldos de seguridad de la base de datos del sistema</p>
        </div>
        <div>
            <a href="{{ route('respaldos.create') }}" class="btn btn-primary shadow-sm me-2">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Respaldo
            </a>
            <form id="quickBackupForm" method="POST" action="{{ route('respaldos.generar-manual') }}" class="d-inline">
                @csrf
                <button type="button" onclick="generarRespaldoManual()" class="btn btn-success shadow-sm">
                    <i class="fas fa-bolt me-2"></i> Respaldo Rápido
                </button>
            </form>
        </div>
    </div>

    <!-- ========== ALERTAS Y LEYENDAS ========== -->
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

    @if(isset($error))
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>{{ $error }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ========== LEYENDA DE RESTAURACIÓN EXITOSA (SE MUESTRA DESPUÉS DEL LOGIN) ========== -->
    @if(session('restauracion_exitosa'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 border-success border-2 shadow-lg" role="alert">
            <div class="d-flex align-items-center w-100">
                <div class="avatar-md bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="background-color: rgba(25, 135, 84, 0.2) !important; width: 60px; height: 60px;">
                    <i class="fas fa-check-circle text-success fa-3x"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="alert-heading mb-2 text-success fw-bold">
                        <i class="fas fa-database me-2"></i>✅ ¡RESTAURACIÓN COMPLETADA EXITOSAMENTE!
                    </h3>
                    <p class="mb-1 fs-5 fw-medium">{{ session('restauracion_exitosa') }}</p>
                    
                    <div class="mt-3 p-3 bg-light rounded-3 border-start border-success border-5">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-success me-2 fa-lg"></i>
                                    <span class="fw-semibold fs-6">✅ La base de datos ha sido restaurada correctamente con todos tus datos.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-archive text-primary me-2 fa-lg"></i>
                                    <span class="fw-semibold fs-6">📁 Archivo restaurado: <strong class="text-primary">{{ session('archivo_restaurado') ?? 'Respaldo' }}</strong></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-info me-2 fa-lg"></i>
                                    <span class="fw-semibold fs-6">⏱️ Fecha y hora: <strong>{{ session('fecha_restauracion') ?? \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                                <div class="vr d-none d-md-block" style="height: 60px;"></div>
                                <div class="d-flex flex-column ms-md-3">
                                    <span class="badge bg-success bg-opacity-25 text-success p-3 rounded-3 mb-2">
                                        <i class="fas fa-check-circle me-1"></i> Éxito
                                    </span>
                                    <span class="text-muted small">Restauración verificada</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- ========== LEYENDA DE CIERRE DE SESIÓN ========== -->
    @if(session('sesion_cerrada'))
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4 border-warning border-2 shadow-lg" role="alert">
            <div class="d-flex align-items-center w-100">
                <div class="avatar-md bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="background-color: rgba(255, 193, 7, 0.2) !important; width: 60px; height: 60px;">
                    <i class="fas fa-shield-alt text-warning fa-3x"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="alert-heading mb-2 text-warning fw-bold">
                        <i class="fas fa-user-lock me-2"></i>🔐 ¡SESIÓN CERRADA AUTOMÁTICAMENTE!
                    </h3>
                    <p class="mb-1 fs-5 fw-medium">{{ session('sesion_cerrada') }}</p>
                    
                    <div class="mt-3 p-3 bg-light rounded-3 border-start border-warning border-5">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-success me-2 fa-lg"></i>
                                    <span class="fw-semibold fs-6">✅ <span class="text-success">LA RESTAURACIÓN FUE EXITOSA</span> - Todos tus datos fueron recuperados correctamente.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-redo-alt text-primary me-2 fa-lg"></i>
                                    <span class="fw-semibold fs-6">🔄 Por seguridad, tu sesión fue cerrada automáticamente después de la restauración.</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-database text-success me-2 fa-lg"></i>
                                    <span class="fw-semibold fs-6">🗄️ Base de datos restaurada desde: <strong class="text-primary">{{ session('archivo_restaurado') ?? 'respaldo' }}</strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                                <div class="vr d-none d-md-block" style="height: 80px;"></div>
                                <div class="d-flex flex-column ms-md-3">
                                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg shadow-sm mb-2">
                                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                    </a>
                                    <a href="{{ route('respaldos.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-database me-1"></i> Ver Respaldos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <!-- ========== FIN DE LEYENDAS ========== -->

    <!-- Botón para restaurar desde archivo SQL -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <button type="button" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#restaurarModal">
                        <i class="fas fa-redo me-2"></i> Restaurar Base de Datos desde Archivo SQL
                    </button>
                    <p class="text-muted mt-2 mb-0">
                        <small><i class="fas fa-info-circle me-1"></i> Importa un archivo SQL para restaurar la base de datos</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de respaldos -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Respaldos</h6>
                            <h3 class="mb-0">{{ $respaldos->count() }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-database text-primary fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        @php
                            $totalSize = 0;
                            foreach($respaldos as $respaldo) {
                                if(file_exists($respaldo->Ruta)) {
                                    $totalSize += filesize($respaldo->Ruta);
                                }
                            }
                        @endphp
                        {{ $controller->formatearTamaño($totalSize) }} total
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Disponibles</h6>
                            <h3 class="mb-0 text-success">
                                {{ $respaldos->filter(function($r) { return file_exists($r->Ruta); })->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        Archivos accesibles
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">No Encontrados</h6>
                            <h3 class="mb-0 text-danger">
                                {{ $respaldos->filter(function($r) { return !file_exists($r->Ruta); })->count() }}
                            </h3>
                        </div>
                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        Requieren atención
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Último Respaldo</h6>
                            <h5 class="mb-0">
                                @if($respaldos->count() > 0)
                                    {{ \Carbon\Carbon::parse($respaldos->first()->Fecha)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </h5>
                        </div>
                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-clock text-info fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        @if($respaldos->count() > 0)
                            {{ \Carbon\Carbon::parse($respaldos->first()->Fecha)->diffForHumans() }}
                        @else
                            Sin respaldos
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de búsqueda y filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-search me-2 text-primary"></i>
                Búsqueda y Filtros
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="searchInput" 
                               placeholder="Buscar respaldos por nombre, descripción o archivo..."
                               onkeyup="filtrarTabla()">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="estadoFilter" onchange="filtrarTabla()">
                        <option value="">Todos los estados</option>
                        <option value="disponible">Disponibles</option>
                        <option value="no_encontrado">No encontrados</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="ordenFilter" onchange="filtrarTabla()">
                        <option value="fecha_desc">Más recientes primero</option>
                        <option value="fecha_asc">Más antiguos primero</option>
                        <option value="nombre_asc">Nombre (A-Z)</option>
                        <option value="nombre_desc">Nombre (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de respaldos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Lista de Respaldos
                </h5>
                <small class="text-muted">
                    {{ $respaldos->count() }} respaldo(s) registrado(s)
                </small>
            </div>
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Haz clic en las filas para ver detalles
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaRespaldos">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3" width="50px"></th>
                            <th class="py-3">Nombre</th>
                            <th class="py-3">Descripción</th>
                            <th class="py-3">Archivo</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Usuario</th>
                            <th class="py-3">Tamaño</th>
                            <th class="py-3">Estado</th>
                            <th class="text-end py-3 pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($respaldos as $respaldo)
                            @php
                                $archivoExiste = file_exists($respaldo->Ruta);
                                $tamaño = $archivoExiste ? $controller->formatearTamaño(filesize($respaldo->Ruta)) : 'No disponible';
                                
                                // Determinar estado
                                $estadoColor = $archivoExiste ? 'success' : 'danger';
                                $estadoIcon = $archivoExiste ? 'fa-check-circle' : 'fa-times-circle';
                                $estadoTexto = $archivoExiste ? 'Disponible' : 'No encontrado';
                                
                                // Obtener información del usuario
                                $usuarioCorreo = null;
                                if(isset($usar_join) && $usar_join) {
                                    $usuarioCorreo = $respaldo->usuario_correo ?? null;
                                } else {
                                    $usuario = isset($usuarios[$respaldo->Usuario]) ? $usuarios[$respaldo->Usuario] : null;
                                    $usuarioCorreo = $usuario ? $usuario->correo : null;
                                }
                            @endphp
                            <tr class="align-middle" data-estado="{{ $archivoExiste ? 'disponible' : 'no_encontrado' }}">
                                <!-- Botón para expandir -->
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-secondary btn-expand" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#detallesRespaldo{{ $respaldo->id }}" 
                                            aria-expanded="false"
                                            aria-controls="detallesRespaldo{{ $respaldo->id }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-database text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $respaldo->Nombre }}</h6>
                                            <small class="text-muted">
                                                ID: {{ $respaldo->id }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-file-alt text-secondary"></i>
                                        </div>
                                        <div>
                                            <span>{{ Str::limit($respaldo->Descripcion ?? 'Sin descripción', 40) }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-file-archive text-info"></i>
                                        </div>
                                        <div>
                                            <code class="small">{{ basename($respaldo->Ruta) }}</code>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-calendar text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('H:i') }}</small>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-success"></i>
                                        </div>
                                        <div>
                                            <span class="fw-medium">{{ $usuarioCorreo ?? 'Usuario no encontrado' }}</span>
                                            <small class="text-muted d-block">ID: {{ $respaldo->Usuario }}</small>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-purple bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-weight-hanging text-purple"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $tamaño }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="badge bg-{{ $estadoColor }} bg-opacity-10 text-{{ $estadoColor }} border border-{{ $estadoColor }} border-opacity-25 px-3 py-2">
                                        <i class="fas {{ $estadoIcon }} me-1"></i>{{ $estadoTexto }}
                                    </span>
                                </td>
                                
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($archivoExiste)
                                            <!-- Botón Restaurar -->
                                            <button type="button" 
                                                    class="btn btn-outline-warning" 
                                                    onclick="restaurarRespaldo({{ $respaldo->id }}, '{{ $respaldo->Nombre }}')"
                                                    data-bs-toggle="tooltip"
                                                    title="Restaurar base de datos">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                            <!-- Botón Descargar -->
                                            <a href="{{ route('respaldos.descargar', $respaldo->id) }}" 
                                               class="btn btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Descargar respaldo">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        <!-- Botón Eliminar -->
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="confirmarEliminacion({{ $respaldo->id }})"
                                                data-bs-toggle="tooltip"
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
                                        <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                            <div class="row">
                                                <!-- Información del respaldo -->
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3 text-primary">
                                                        <i class="fas fa-info-circle me-2"></i>Información del Respaldo
                                                    </h6>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted">Descripción completa:</label>
                                                        <p class="mb-0">{{ $respaldo->Descripcion ?? 'Sin descripción' }}</p>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="form-label small text-muted">Fecha creación:</label>
                                                            <h6 class="fw-bold">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('d/m/Y H:i:s') }}</h6>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small text-muted">Usuario:</label>
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                                    {{ $usuarioCorreo ?? 'N/A' }}
                                                                </span>
                                                                <small class="text-muted ms-2">ID: {{ $respaldo->Usuario }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <label class="form-label small text-muted">Ruta del archivo:</label>
                                                        <div class="bg-dark text-light p-2 rounded small font-monospace">
                                                            {{ $respaldo->Ruta }}
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
                                                            
                                                            <!-- Información del archivo -->
                                                            <div class="mb-4">
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <small class="text-muted">Tamaño del archivo:</small>
                                                                    <small class="fw-bold">{{ $tamaño }}</small>
                                                                </div>
                                                                <div class="progress" style="height: 10px;">
                                                                    @php
                                                                        $maxSize = 100 * 1024 * 1024;
                                                                        $fileSize = $archivoExiste ? filesize($respaldo->Ruta) : 0;
                                                                        $porcentaje = min(100, ($fileSize / $maxSize) * 100);
                                                                    @endphp
                                                                    <div class="progress-bar bg-{{ $estadoColor }}" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $porcentaje }}%"
                                                                         aria-valuenow="{{ $fileSize }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="{{ $maxSize }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Estado actual -->
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted">Estado:</label>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm bg-{{ $estadoColor }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                        <i class="fas {{ $estadoIcon }} text-{{ $estadoColor }}"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-medium">{{ $estadoTexto }}</div>
                                                                        @if(!$archivoExiste)
                                                                        <small class="text-{{ $estadoColor }}">
                                                                            Alerta: Archivo físico no encontrado en el servidor
                                                                        </small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <hr>
                                                            
                                                            <div class="mt-4">
                                                                <div class="d-grid gap-2">
                                                                    @if($archivoExiste)
                                                                    <button type="button" 
                                                                            class="btn btn-outline-warning btn-sm"
                                                                            onclick="restaurarRespaldo({{ $respaldo->id }}, '{{ $respaldo->Nombre }}')">
                                                                        <i class="fas fa-redo me-1"></i> Restaurar Base de Datos
                                                                    </button>
                                                                    <a href="{{ route('respaldos.descargar', $respaldo->id) }}" 
                                                                       class="btn btn-outline-primary btn-sm">
                                                                        <i class="fas fa-download me-1"></i> Descargar Respaldo
                                                                    </a>
                                                                    @endif
                                                                    <button type="button" 
                                                                            class="btn btn-outline-danger btn-sm"
                                                                            onclick="confirmarEliminacion({{ $respaldo->id }})">
                                                                        <i class="fas fa-trash me-1"></i> Eliminar Respaldo
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
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-5">
                                        <i class="fas fa-database fa-4x text-muted mb-4"></i>
                                        <h4 class="text-muted fw-bold mb-3">No hay respaldos registrados</h4>
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
</div>

<!-- Modal para restaurar desde archivo SQL -->
<div class="modal fade" id="restaurarModal" tabindex="-1" aria-labelledby="restaurarModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white position-relative">
                <h5 class="modal-title fw-bold" id="restaurarModalLabel">
                    <i class="fas fa-redo me-2"></i>
                    Restaurar Base de Datos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="restaurarForm" action="{{ route('respaldos.restaurar-archivo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <!-- Advertencia -->
                    <div class="alert alert-danger border-danger border-opacity-50 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-2">¡ADVERTENCIA CRÍTICA!</h5>
                                <p class="mb-0">Esta acción <strong>ELIMINARÁ</strong> toda la base de datos actual y <strong>CREARÁ UNA NUEVA</strong> con los datos del archivo SQL seleccionado.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de archivo -->
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">
                            <i class="fas fa-file-import me-2"></i>Seleccionar Archivo SQL
                        </label>
                        <div class="input-group">
                            <input type="file" 
                                   class="form-control form-control-lg" 
                                   id="sqlFile" 
                                   name="sql_file" 
                                   accept=".sql,.gz,.zip"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('sqlFile').click()">
                                <i class="fas fa-folder-open"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Seleccione un archivo SQL, SQL comprimido (.gz) o ZIP
                        </div>
                    </div>

                    <!-- Información del archivo (se llena dinámicamente) -->
                    <div id="fileInfo" class="d-none">
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-10">
                                <i class="fas fa-file-code me-2"></i>Información del Archivo
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Nombre del archivo:</label>
                                            <div class="fw-bold" id="fileName"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Tamaño:</label>
                                            <div class="fw-bold" id="fileSize"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-0">
                                            <label class="form-label small text-muted">Ruta:</label>
                                            <div class="text-muted font-monospace small" id="filePath"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opciones de restauración -->
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">
                            <i class="fas fa-cogs me-2"></i>Opciones de Restauración
                        </label>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="dropDatabase" name="drop_database" checked>
                            <label class="form-check-label" for="dropDatabase">
                                <strong>Eliminar base de datos actual</strong> antes de restaurar
                            </label>
                            <div class="form-text">
                                Esta opción es necesaria para una restauración completa
                            </div>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="createDatabase" name="create_database" checked>
                            <label class="form-check-label" for="createDatabase">
                                <strong>Crear nueva base de datos</strong> si no existe
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="backupCurrent" name="backup_current" checked>
                            <label class="form-check-label" for="backupCurrent">
                                <strong>Crear respaldo</strong> de la base de datos actual antes de restaurar
                            </label>
                            <div class="form-text">
                                Recomendado: Crea un respaldo de seguridad de los datos actuales
                            </div>
                        </div>
                    </div>

                    <!-- Confirmación final -->
                    <div class="alert alert-warning border-warning border-opacity-50">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt fa-2x me-3 text-warning"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Confirmación Final</h6>
                                <p class="mb-0">Para proceder con la restauración, escriba <code>CONFIRMAR</code> en el campo de abajo:</p>
                                <input type="text" 
                                       class="form-control mt-3" 
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning btn-lg" id="submitRestaurar" disabled>
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

@push('styles')
<style>
/* Estilos adaptados para respaldos */
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

.table tbody tr:not(.detalle-respaldo-row) {
    transition: all 0.2s ease;
}

.table tbody tr:not(.detalle-respaldo-row):hover {
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

.shadow-lg {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.fw-semibold {
    font-weight: 600;
}

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

.detalle-respaldo-row {
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

/* Estado de respaldo */
.bg-success.bg-opacity-10 {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-danger.bg-opacity-10 {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info.bg-opacity-10 {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

.bg-purple.bg-opacity-10 {
    background-color: rgba(111, 66, 193, 0.1) !important;
}

.text-purple {
    color: #6f42c1 !important;
}

/* Estilos específicos para barras de progreso */
.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

/* Modal styles */
.modal-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.modal-content {
    border-radius: 12px;
}

/* Estilos para el modal de restauración */
#restaurarModal .modal-lg {
    max-width: 800px;
}

#restaurarModal .form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1.1rem;
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
    
    .d-flex {
        flex-wrap: wrap;
    }
    
    .btn-group {
        flex-wrap: wrap;
    }
    
    #restaurarModal .modal-lg {
        max-width: 95%;
        margin: 0.5rem auto;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Token CSRF global
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

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
            
            if (icon) {
                if (isExpanded) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(180deg)';
                }
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
                // Validar que sea un archivo SQL, GZ o ZIP
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
                
                // Mostrar información del archivo
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
            
            // Confirmación final
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
                customClass: {
                    popup: 'shadow-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Restaurando Base de Datos',
                        html: `
                            <div class="text-center">
                                <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <h5 class="text-warning mb-2">Restauración en progreso</h5>
                                <p class="mb-2">Esto puede tomar varios minutos...</p>
                                <small class="text-muted">No cierre esta ventana</small>
                            </div>
                        `,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        backdrop: 'rgba(0,0,0,0.7)'
                    });
                    
                    // Enviar formulario
                    this.submit();
                }
            });
        });
    }
});

// Función para validar confirmación en tiempo real
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

// Función para formatear tamaño de archivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// ===========================================
// FUNCIÓN PARA RESTAURAR RESPALDO EXISTENTE
// ===========================================
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
            // Crear formulario y enviar por POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/respaldos/restaurar/${id}`;
            form.style.display = 'none';
            
            // Token CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Campo de confirmación
            const confirmInput = document.createElement('input');
            confirmInput.type = 'hidden';
            confirmInput.name = 'confirmacion';
            confirmInput.value = 'CONFIRMAR';
            form.appendChild(confirmInput);
            
            document.body.appendChild(form);
            
            // Mostrar loading
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

// Función para filtrar la tabla
function filtrarTabla() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const estadoFilter = document.getElementById('estadoFilter').value;
    
    const rows = document.querySelectorAll('#tablaRespaldos tbody tr:not(.detalle-respaldo-row)');
    
    rows.forEach(row => {
        const texto = row.textContent.toLowerCase();
        const estado = row.getAttribute('data-estado');
        
        // Aplicar filtro de búsqueda
        const matchSearch = searchInput === '' || texto.includes(searchInput);
        
        // Aplicar filtro de estado
        const matchEstado = estadoFilter === '' || estado === estadoFilter;
        
        // Mostrar/ocultar fila
        if (matchSearch && matchEstado) {
            row.style.display = '';
            // Mostrar también la fila de detalles si existe
            const detallesRow = row.nextElementSibling;
            if (detallesRow && detallesRow.classList.contains('detalle-respaldo-row')) {
                detallesRow.style.display = '';
            }
        } else {
            row.style.display = 'none';
            // Ocultar también la fila de detalles
            const detallesRow = row.nextElementSibling;
            if (detallesRow && detallesRow.classList.contains('detalle-respaldo-row')) {
                detallesRow.style.display = 'none';
            }
        }
    });
}

function generarRespaldoManual() {
    Swal.fire({
        title: 'Generar Respaldo Rápido',
        text: '¿Desea generar un respaldo rápido de la base de datos?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-bolt me-1"></i> Sí, Generar',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        customClass: {
            confirmButton: 'shadow-sm',
            cancelButton: 'shadow-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Generando Respaldo',
                text: 'Por favor espere...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario
            document.getElementById('quickBackupForm').submit();
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
    // Mostrar loading
    Swal.fire({
        title: 'Eliminando Respaldo',
        text: 'Por favor espere...',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Enviar solicitud DELETE usando fetch
    fetch(`/respaldos/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
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
            }).then(() => {
                window.location.reload();
            });
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
</script>
@endpush
@endsection