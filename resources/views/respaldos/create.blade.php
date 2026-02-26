@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <!-- Fondo decorativo -->
    <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-xxl-block" style="
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(32, 201, 151, 0.03) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 25% 0);
        z-index: 0;
    "></div>

    <div class="position-relative z-1">
        <div class="row justify-content-center g-0">
            <div class="col-12 col-xxl-10">
                <!-- Header Superior Mejorado -->
                <div class="header-glass py-4 px-4 px-lg-5 mb-4" style="
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(10px);
                    border-bottom: 1px solid rgba(0,0,0,0.08);
                ">
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon" style="
                                width: 60px;
                                height: 60px;
                                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
                                animation: float 6s ease-in-out infinite;
                            ">
                                <i class="fas fa-database fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Generar Nuevo Respaldo
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Complete la información para generar un respaldo de la base de datos
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('respaldos.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
                                border-radius: 12px;
                                padding: 8px 16px;
                                font-size: 0.9rem;
                                border: 1px solid #dee2e6;
                                transition: all 0.3s ease;
                            ">
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-md-inline">Volver</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Principal -->
                <div class="main-card mx-3 mx-lg-4 mb-5" style="
                    background: white;
                    border-radius: 24px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.03);
                    overflow: hidden;
                ">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Barra de Progreso General -->
                        <div class="progress-overview mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="fas fa-tasks me-2 text-success"></i>
                                    Progreso del Formulario
                                </h5>
                                <div class="progress-percentage">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" id="progressPercentage">
                                        0% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">0 de 3 secciones</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Complete la información del respaldo
                                </small>
                            </div>
                        </div>

                        <form id="respaldoForm" action="{{ route('respaldos.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            
                            <!-- Campo oculto con el ID del usuario logueado -->
                            <input type="hidden" name="Usuario" value="{{ auth()->id() }}">

                            <!-- Sección 1: Información General del Respaldo -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información General</h3>
                                            <p class="section-subtitle mb-0">Datos básicos del respaldo</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #28a745, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Nombre del Respaldo -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Nombre del Respaldo</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('Nombre') is-invalid @enderror" 
                                                       id="Nombre" 
                                                       name="Nombre" 
                                                       value="{{ old('Nombre', 'Respaldo ' . now()->format('d-m-Y H:i')) }}" 
                                                       placeholder="Ej: Respaldo Mensual Enero 2024"
                                                       required 
                                                       maxlength="80"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration" style="background: linear-gradient(90deg, #28a745, #20c997);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">{{ strlen(old('Nombre', 'Respaldo ' . now()->format('d-m-Y H:i'))) }}/80</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Identificador único para este respaldo
                                                </div>
                                            </div>
                                            
                                            @error('Nombre')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Usuario Responsable -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Usuario Responsable</span>
                                            </label>
                                            
                                            <div class="info-card">
                                                <div class="info-card-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="info-card-content">
                                                    <span class="info-card-label">Usuario actual</span>
                                                    <span class="info-card-value">
                                                        {{ auth()->user()->correo ?? auth()->user()->email ?? auth()->user()->name ?? 'Usuario' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Se registrará automáticamente
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Descripción -->
                                    <div class="col-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Descripción</span>
                                                <span class="label-optional">(Opcional)</span>
                                            </label>
                                            
                                            <div class="textarea-wrapper">
                                                <div class="input-icon" style="top: 24px; transform: none;">
                                                    <i class="fas fa-align-left"></i>
                                                </div>
                                                <textarea class="textarea-field @error('Descripcion') is-invalid @enderror" 
                                                          id="Descripcion" 
                                                          name="Descripcion" 
                                                          placeholder="Describe el propósito de este respaldo..."
                                                          maxlength="280"
                                                          rows="3"
                                                          data-char-counter="descripcionCount">{{ old('Descripcion') }}</textarea>
                                                <div class="input-decoration" style="background: linear-gradient(90deg, #28a745, #20c997);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="descripcionCount">{{ strlen(old('Descripcion') ?? '') }}/280</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Describe el propósito del respaldo
                                                </div>
                                            </div>
                                            
                                            @error('Descripcion')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Información del Sistema -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                            <i class="fas fa-server"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información del Sistema</h3>
                                            <p class="section-subtitle mb-0">Detalles de la base de datos</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #007bff, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                                <i class="fas fa-database"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Base de Datos</span>
                                                <span class="summary-value" style="font-size: 1.2rem;">{{ config('database.connections.mysql.database') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                                <i class="fas fa-table"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Tablas</span>
                                                <span class="summary-value">{{ $estadisticas['total_tablas'] ?? '0' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Tamaño Total</span>
                                                <span class="summary-value">{{ $estadisticas['tamaño_total'] ?? '0 MB' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-card-detailed">
                                            <div class="info-card-detailed-icon" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1)); color: #28a745;">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="info-card-detailed-content">
                                                <span class="info-card-detailed-label">Fecha y Hora Actual</span>
                                                <span class="info-card-detailed-value">
                                                    @php
                                                        $fechaHora = now(config('app.timezone', 'America/Lima'));
                                                    @endphp
                                                    {{ $fechaHora->isoFormat('DD/MM/YYYY hh:mm A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-card-detailed">
                                            <div class="info-card-detailed-icon" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(253, 126, 20, 0.1)); color: #ffc107;">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="info-card-detailed-content">
                                                <span class="info-card-detailed-label">Último Respaldo</span>
                                                <span class="info-card-detailed-value">
                                                    @if(isset($estadisticas['ultimo_respaldo_fecha']) && $estadisticas['ultimo_respaldo_fecha'])
                                                        @php
                                                            $fechaUltimoRespaldo = \Carbon\Carbon::parse($estadisticas['ultimo_respaldo_fecha'])->timezone(config('app.timezone', 'America/Lima'));
                                                        @endphp
                                                        {{ $fechaUltimoRespaldo->isoFormat('DD/MM/YYYY hh:mm A') }}
                                                    @else
                                                        Sin respaldos previos
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 3: Información del Proceso -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información del Proceso</h3>
                                            <p class="section-subtitle mb-0">Detalles sobre la generación del respaldo</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #17a2b8, transparent);"></div>
                                </div>

                                <div class="process-info-card">
                                    <div class="process-info-header">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <span>Detalles del respaldo a generar</span>
                                    </div>
                                    <div class="process-info-body">
                                        <ul class="process-info-list">
                                            <li>
                                                <i class="fas fa-file-code text-primary"></i>
                                                <div>
                                                    <strong>Archivo:</strong>
                                                    <code>backup_{{ config('database.connections.mysql.database') }}_{{ now()->format('Y-m-d_H-i') }}.sql</code>
                                                </div>
                                            </li>
                                            <li>
                                                <i class="fas fa-folder-open text-success"></i>
                                                <div>
                                                    <strong>Ubicación:</strong>
                                                    <code>storage/app/backups/</code>
                                                </div>
                                            </li>
                                            <li>
                                                <i class="fas fa-table text-info"></i>
                                                <div>
                                                    <strong>Contenido:</strong>
                                                    <span>Estructura y datos de todas las tablas ({{ $estadisticas['total_tablas'] ?? '0' }} tablas)</span>
                                                </div>
                                            </li>
                                            <li>
                                                <i class="fas fa-clock text-warning"></i>
                                                <div>
                                                    <strong>Tiempo estimado:</strong>
                                                    <span>Variable según el tamaño de la base de datos</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones del Formulario -->
                            <div class="form-actions mt-5 pt-4 border-top">
                                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-4">
                                    <div class="form-info">
                                        <div class="form-stats">
                                            <div class="stat-item">
                                                <i class="fas fa-asterisk text-danger"></i>
                                                <span>Campos obligatorios</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span id="validFieldsCount">0/3</span> secciones
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('respaldos.index') }}" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>
                                            Cancelar
                                        </a>
                                        <button type="button" class="btn btn-success btn-submit" id="btnGenerarRespaldo" style="
                                            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                                            border: none;
                                            padding: 12px 32px;
                                            border-radius: 12px;
                                            font-weight: 700;
                                            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                            position: relative;
                                            overflow: hidden;
                                        ">
                                            <span class="submit-content">
                                                <i class="fas fa-database me-2"></i>
                                                Generar Respaldo
                                            </span>
                                            <span class="submit-loader">
                                                <i class="fas fa-spinner fa-spin me-2"></i>
                                                Procesando...
                                            </span>
                                            <div class="submit-shine"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Progreso Mejorado -->
<div class="modal fade" id="progresoModal" tabindex="-1" aria-labelledby="progresoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title" id="progresoModalLabel">
                    <i class="fas fa-sync-alt me-2 fa-spin"></i>
                    Generando Respaldo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="modal-spinner-container mb-4">
                    <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <div class="spinner-grow text-success" style="width: 3rem; height: 3rem; margin-left: -0.5rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-2" id="estadoProceso">Preparando respaldo...</h5>
                <p class="text-muted mb-4">Procesando base de datos</p>
                
                <!-- Barra de progreso mejorada -->
                <div class="progress-container mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Progreso</small>
                        <small class="fw-bold text-success" id="porcentajeProgreso">0%</small>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: 0%; background: linear-gradient(90deg, #28a745, #20c997);" 
                             id="barraProgreso">
                        </div>
                    </div>
                </div>
                
                <!-- Detalles del proceso mejorados -->
                <div class="process-details bg-light p-3 rounded text-start mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="process-detail-icon">
                            <i class="fas fa-database text-primary"></i>
                        </div>
                        <div class="process-detail-content">
                            <small class="text-muted d-block">Base de datos:</small>
                            <code class="fw-semibold">{{ config('database.connections.mysql.database') }}</code>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="process-detail-icon">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                        <div class="process-detail-content">
                            <small class="text-muted d-block">Inicio:</small>
                            <span class="fw-semibold" id="horaInicio">
                                @php
                                    echo now(config('app.timezone', 'America/Lima'))->isoFormat('HH:mm:ss');
                                @endphp
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning alert-modern text-start">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡No cierre esta ventana!</strong>
                    <small class="d-block mt-1">El proceso puede tomar varios minutos dependiendo del tamaño de la base de datos.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-color: #ef4444;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --border-radius: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
    --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
}

/* Animaciones */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@keyframes shine {
    0% { transform: rotate(30deg) translateX(-100%); }
    100% { transform: rotate(30deg) translateX(100%); }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Toast Notifications */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 350px;
    max-width: 450px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    overflow: hidden;
    z-index: 10000;
    animation: slideInRight 0.3s ease forwards;
    border-left: 4px solid;
}

.toast-notification.hiding {
    animation: slideOutRight 0.3s ease forwards;
}

.toast-notification.toast-success {
    border-left-color: var(--success-color);
}

.toast-notification.toast-error {
    border-left-color: var(--danger-color);
}

.toast-notification.toast-warning {
    border-left-color: var(--warning-color);
}

.toast-notification.toast-info {
    border-left-color: var(--info-color);
}

.toast-content {
    position: relative;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: white;
}

.toast-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.toast-success .toast-icon-wrapper {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.toast-error .toast-icon-wrapper {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
}

.toast-warning .toast-icon-wrapper {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning-color);
}

.toast-info .toast-icon-wrapper {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info-color);
}

.toast-text {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    margin-bottom: 4px;
    color: #1f2937;
    font-size: 0.95rem;
}

.toast-message {
    color: #6b7280;
    font-size: 0.85rem;
}

.toast-close {
    background: transparent;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: var(--transition);
}

.toast-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: linear-gradient(90deg, var(--progress-color-start), var(--progress-color-end));
    animation: progress 4s linear forwards;
}

.toast-success .toast-progress {
    --progress-color-start: #28a745;
    --progress-color-end: #20c997;
}

.toast-error .toast-progress {
    --progress-color-start: #ef4444;
    --progress-color-end: #dc2626;
}

.toast-warning .toast-progress {
    --progress-color-start: #ffc107;
    --progress-color-end: #fd7e14;
}

.toast-info .toast-progress {
    --progress-color-start: #17a2b8;
    --progress-color-end: #138496;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

/* Shake animation enhanced */
.shake-enhanced {
    animation: shake 0.8s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
    perspective: 1000px;
}

/* Form Sections */
.form-section {
    animation: slideIn 0.6s ease-out;
    margin-bottom: 2.5rem;
}

.section-header {
    margin-bottom: 2rem;
}

.section-icon-badge {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-md);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    letter-spacing: -0.5px;
}

.section-subtitle {
    color: #6b7280;
    font-size: 0.9rem;
}

.section-divider {
    height: 2px;
    border-radius: 2px;
    margin-top: 1rem;
}

/* Enhanced Form Groups */
.form-group-enhanced {
    margin-bottom: 1.5rem;
}

.form-label-enhanced {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.label-text {
    flex: 1;
}

.label-required {
    color: var(--danger-color);
    font-weight: 700;
}

.label-optional {
    color: #9ca3af;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Input Wrapper */
.input-wrapper,
.textarea-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper:focus-within,
.textarea-wrapper:focus-within {
    border-color: #28a745;
    box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
    transform: translateY(-2px);
}

.input-wrapper.error,
.textarea-wrapper.error {
    border-color: var(--danger-color);
    animation: shake 0.5s;
}

.input-wrapper.valid,
.textarea-wrapper.valid {
    border-color: var(--success-color);
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #28a745;
    font-size: 1.1rem;
    z-index: 2;
}

.textarea-wrapper .input-icon {
    top: 24px;
    transform: none;
}

.input-field,
.textarea-field {
    width: 100%;
    padding: 20px 20px 20px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
    transition: var(--transition);
}

.textarea-field {
    padding: 20px 20px 20px 48px;
    resize: vertical;
    min-height: 100px;
}

.input-field::placeholder,
.textarea-field::placeholder {
    color: #9ca3af;
}

.input-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.input-wrapper:focus-within .input-decoration,
.textarea-wrapper:focus-within .input-decoration {
    transform: scaleX(1);
}

.input-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    padding: 0 4px;
}

.char-counter {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
}

.input-hint {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Info Card */
.info-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #28a745;
}

.info-card-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #28a745;
    font-size: 1.2rem;
}

.info-card-content {
    display: flex;
    flex-direction: column;
}

.info-card-label {
    font-size: 0.8rem;
    color: #6b7280;
}

.info-card-value {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

/* Info Card Detailed */
.info-card-detailed {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    border: 1px solid #e5e7eb;
    transition: var(--transition);
}

.info-card-detailed:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.info-card-detailed-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.info-card-detailed-content {
    display: flex;
    flex-direction: column;
}

.info-card-detailed-label {
    font-size: 0.8rem;
    color: #6b7280;
}

.info-card-detailed-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1f2937;
}

/* Summary Cards */
.summary-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    transition: var(--transition);
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.summary-content {
    display: flex;
    flex-direction: column;
}

.summary-label {
    font-size: 0.9rem;
    color: #6b7280;
}

.summary-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
}

/* Process Info Card */
.process-info-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: var(--shadow-sm);
}

.process-info-header {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    padding: 15px 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.process-info-body {
    padding: 20px;
}

.process-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.process-info-list li {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #e5e7eb;
}

.process-info-list li:last-child {
    border-bottom: none;
}

.process-info-list li i {
    width: 24px;
    font-size: 1.1rem;
}

.process-info-list li div {
    flex: 1;
}

.process-info-list li code {
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.85rem;
    color: #d63384;
}

/* Form Actions */
.form-actions {
    position: relative;
}

.form-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #6b7280;
}

.btn-action {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-submit {
    position: relative;
    overflow: hidden;
    min-width: 200px;
}

.submit-content,
.submit-loader {
    display: flex;
    align-items: center;
    transition: opacity 0.3s ease;
}

.submit-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
}

.btn-submit.loading .submit-content {
    opacity: 0;
}

.btn-submit.loading .submit-loader {
    opacity: 1;
}

.btn-submit:hover:not(.loading) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
}

.btn-submit:active:not(.loading) {
    transform: translateY(1px);
}

.submit-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 100%
    );
    transform: rotate(30deg);
    animation: shine 3s infinite;
}

/* Error States */
.error-message {
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    animation: slideIn 0.3s ease;
}

.is-invalid {
    border-color: var(--danger-color) !important;
}

.is-valid {
    border-color: var(--success-color) !important;
}

/* Progress Overview */
.progress-overview {
    background: #f9fafb;
    padding: 20px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
}

.progress-percentage .badge {
    font-size: 0.9rem;
    font-weight: 600;
}

/* Info Footer */
.info-footer {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    margin: 0 1rem;
    border: 1px solid rgba(0,0,0,0.05);
}

.info-item {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Modal Modern */
.modal-modern {
    border: none;
    border-radius: 20px;
    overflow: hidden;
}

.modal-header-gradient {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-bottom: none;
}

.modal-header-gradient .btn-close {
    filter: brightness(0) invert(1);
}

.modal-spinner-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.progress-modern {
    height: 10px;
    border-radius: 10px;
    background: #e5e7eb;
}

.process-details {
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.process-detail-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.process-detail-content {
    flex: 1;
}

.alert-modern {
    border: none;
    border-radius: 12px;
    background: #fff3cd;
    color: #856404;
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-action,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }

    .progress-overview {
        padding: 15px;
    }

    .form-stats {
        flex-wrap: wrap;
        justify-content: center;
    }

    .summary-card {
        margin-bottom: 15px;
    }
    
    .toast-notification {
        min-width: 300px;
        max-width: 350px;
        top: 10px;
        right: 10px;
    }
}
</style>

<script>
// Sistema de Notificaciones
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.maxNotifications = 3;
        this.container = document.getElementById('toastContainer');
    }

    show(message, type = 'info', title = null) {
        if (this.notifications.length >= this.maxNotifications) {
            const oldestNotification = this.notifications.shift();
            this.removeNotification(oldestNotification);
        }

        const notification = this.createNotification(message, type, title);
        this.notifications.push(notification);
        
        setTimeout(() => {
            this.removeNotification(notification);
        }, 4000);

        return notification;
    }

    createNotification(message, type, title) {
        const titles = {
            success: '¡Excelente!',
            error: '¡Oops! Algo salió mal',
            warning: '¡Atención!',
            info: 'Información'
        };

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const notificationTitle = title || titles[type] || 'Notificación';

        const notification = document.createElement('div');
        notification.className = `toast-notification toast-${type}`;
        notification.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon-wrapper">
                    <i class="fas ${icons[type]}"></i>
                </div>
                <div class="toast-text">
                    <div class="toast-title">${notificationTitle}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.closest('.toast-notification').classList.add('hiding'); setTimeout(() => this.closest('.toast-notification').remove(), 300)">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress"></div>
            </div>
        `;

        this.container.appendChild(notification);
        return notification;
    }

    removeNotification(notification) {
        if (!notification) return;
        
        notification.classList.add('hiding');
        setTimeout(() => {
            notification.remove();
            this.notifications = this.notifications.filter(n => n !== notification);
        }, 300);
    }

    showValidationError(message) {
        this.show(message, 'warning', 'Errores en el formulario');
    }

    showSuccess(message) {
        this.show(message, 'success', '¡Operación exitosa!');
    }

    showError(message) {
        this.show(message, 'error', 'Error en la operación');
    }

    showWarning(message) {
        this.show(message, 'warning', 'Advertencia');
    }

    showInfo(message) {
        this.show(message, 'info', 'Información');
    }
}

// Instancia global del notification manager
const notifier = new NotificationManager();

class RespaldoManager {
    constructor() {
        this.requiredFields = ['Nombre'];
        this.progresoModal = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.updateProgress();
        this.initRealTimeValidation();
        this.showServerErrors();
        
        // Inicializar modal
        const modalElement = document.getElementById('progresoModal');
        if (modalElement) {
            this.progresoModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
        }
    }

    showServerErrors() {
        @if($errors->any())
            const errorMessages = [];
            @foreach($errors->all() as $error)
                errorMessages.push('{{ $error }}');
            @endforeach
            notifier.showError(errorMessages.join('<br>'));
        @endif
    }

    setupEventListeners() {
        this.btnGenerar = document.getElementById('btnGenerarRespaldo');
        this.form = document.getElementById('respaldoForm');

        if (this.btnGenerar) {
            this.btnGenerar.addEventListener('click', (e) => this.handleGenerar(e));
        }
    }

    initRealTimeValidation() {
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                });
                field.addEventListener('blur', () => {
                    this.validateField(fieldId);
                });
            }
        });
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const wrapper = field.closest('.input-wrapper, .textarea-wrapper');
        if (!wrapper) return;

        wrapper.classList.remove('error', 'valid');

        if (field.hasAttribute('required') && !field.value.trim()) {
            wrapper.classList.add('error');
            return false;
        }

        wrapper.classList.add('valid');
        return true;
    }

    validateAllFields() {
        const errors = [];
        
        if (!this.validateField('Nombre')) errors.push('• Debe ingresar un nombre para el respaldo');

        if (errors.length > 0) {
            notifier.showValidationError(errors.join('<br>'));
            
            const firstErrorId = 'Nombre';
            const firstError = document.getElementById(firstErrorId);
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                const wrapper = firstError.closest('.input-wrapper');
                if (wrapper) {
                    wrapper.classList.add('shake-enhanced');
                    setTimeout(() => wrapper.classList.remove('shake-enhanced'), 800);
                }
            }
        }

        return errors.length === 0;
    }

    updateProgress() {
        const completedFields = this.requiredFields.filter(fieldId => {
            const field = document.getElementById(fieldId);
            return field && field.value.trim() !== '';
        }).length;

        const percentage = Math.round((completedFields / this.requiredFields.length) * 100);
        
        document.getElementById('formProgress').style.width = `${percentage}%`;
        document.getElementById('progressPercentage').textContent = `${percentage}% Completado`;
        document.getElementById('completedFields').textContent = `${completedFields} de ${this.requiredFields.length} secciones`;
        document.getElementById('validFieldsCount').textContent = `${completedFields}/${this.requiredFields.length}`;
    }

    initCharacterCounters() {
        const textFields = [
            { id: 'Nombre', counter: 'nombreCount', max: 80 },
            { id: 'Descripcion', counter: 'descripcionCount', max: 280 }
        ];
        
        textFields.forEach(item => {
            const field = document.getElementById(item.id);
            if (field) {
                field.addEventListener('input', (e) => {
                    const length = e.target.value.length;
                    this.updateCharCounter(item.counter, length, item.max);
                    
                    if (item.id === 'Nombre') {
                        this.validateField('Nombre');
                        this.updateProgress();
                    }
                });
                this.updateCharCounter(item.counter, field.value.length, item.max);
            }
        });
    }

    updateCharCounter(elementId, length, max) {
        const counter = document.getElementById(elementId);
        if (counter) {
            counter.textContent = `${length}/${max}`;
            counter.style.color = length > max ? '#ef4444' : length > 0 ? '#28a745' : '#9ca3af';
        }
    }

    handleGenerar(e) {
        e.preventDefault();
        
        if (this.validateAllFields()) {
            this.mostrarConfirmacion();
        }
    }

    mostrarConfirmacion() {
        const nombre = document.getElementById('Nombre').value.trim();
        const descripcion = document.getElementById('Descripcion').value.trim();

        Swal.fire({
            title: '¿Generar Respaldo?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de generar el siguiente respaldo?</p>
                    <div class="alert alert-info" style="background: #e8f4f8; border-radius: 12px; padding: 15px;">
                        <strong>Resumen del Respaldo:</strong><br>
                        • Nombre: <strong>${nombre}</strong><br>
                        • Descripción: <strong>${descripcion || 'Sin descripción'}</strong>
                    </div>
                    <p class="text-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este proceso puede tomar varios minutos. No interrumpa la operación.
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Generar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                this.iniciarProcesoRespaldo();
            }
        });
    }

    iniciarProcesoRespaldo() {
        // Mostrar modal de progreso
        if (this.progresoModal) {
            this.progresoModal.show();
        }
        
        // Mostrar loader en el botón
        const submitBtn = document.getElementById('btnGenerarRespaldo');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        // Inicializar variables de progreso
        let progreso = 0;
        const mensajes = [
            'Preparando respaldo...',
            'Exportando estructura de tablas...',
            'Exportando datos de usuarios...',
            'Exportando datos de productos...',
            'Exportando datos de ventas...',
            'Exportando datos de compras...',
            'Exportando datos de transacciones...',
            'Verificando integridad de datos...',
            'Generando archivo SQL...',
            'Finalizando proceso...'
        ];
        
        let mensajeIndex = 0;
        
        // Simular progreso
        const interval = setInterval(() => {
            progreso += Math.random() * 10 + 5;
            if (progreso > 100) progreso = 100;
            
            // Actualizar barra de progreso
            document.getElementById('barraProgreso').style.width = progreso + '%';
            document.getElementById('porcentajeProgreso').textContent = Math.round(progreso) + '%';
            
            // Cambiar mensaje cada cierto progreso
            if (progreso >= (mensajeIndex + 1) * 10) {
                document.getElementById('estadoProceso').textContent = mensajes[mensajeIndex];
                mensajeIndex = Math.min(mensajeIndex + 1, mensajes.length - 1);
            }
            
            // Cuando llegue al 100%
            if (progreso >= 100) {
                clearInterval(interval);
                
                // Animación final
                setTimeout(() => {
                    document.getElementById('estadoProceso').textContent = '¡Respaldo completado!';
                    document.getElementById('barraProgreso').classList.remove('progress-bar-animated');
                    
                    // Esperar y enviar formulario
                    setTimeout(() => {
                        if (this.progresoModal) {
                            this.progresoModal.hide();
                        }
                        
                        notifier.showInfo('Procesando respaldo...', 'Un momento por favor');
                        
                        // Enviar formulario
                        setTimeout(() => {
                            document.getElementById('respaldoForm').submit();
                        }, 500);
                    }, 1500);
                }, 500);
            }
        }, 400);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new RespaldoManager();
});
</script>

<!-- Include Bootstrap JS for Modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection