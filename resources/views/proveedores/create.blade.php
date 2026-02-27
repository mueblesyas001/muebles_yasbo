@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <!-- Fondo decorativo -->
    <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-xxl-block" style="
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.03) 100%);
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
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
                                animation: float 6s ease-in-out infinite;
                            ">
                                <i class="fas fa-user-tie fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Nuevo Proveedor
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Complete todos los campos para registrar un nuevo proveedor
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
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
                                    <i class="fas fa-tasks me-2 text-primary"></i>
                                    Progreso del Formulario
                                </h5>
                                <div class="progress-percentage">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" id="progressPercentage">
                                        0% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">0 de 6 campos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="proveedorForm" action="{{ route('proveedores.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <!-- Sección 1: Información Personal -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información Personal</h3>
                                            <p class="section-subtitle mb-0">Datos básicos del proveedor</p>
                                        </div>
                                    </div>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Nombre -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Nombre</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('Nombre') is-invalid @enderror" 
                                                       id="Nombre" 
                                                       name="Nombre" 
                                                       value="{{ old('Nombre') }}" 
                                                       placeholder="Ej: Juan Carlos" 
                                                       required 
                                                       maxlength="255"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">0/255</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Nombre(s) del proveedor
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

                                    <!-- Apellido Paterno -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Apellido Paterno</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('ApPaterno') is-invalid @enderror" 
                                                       id="ApPaterno" 
                                                       name="ApPaterno" 
                                                       value="{{ old('ApPaterno') }}" 
                                                       placeholder="Ej: Pérez" 
                                                       required 
                                                       maxlength="255"
                                                       data-char-counter="apPaternoCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="apPaternoCount">0/255</span>
                                                </div>
                                                <div class="input-hint">
                                                    Primer apellido
                                                </div>
                                            </div>
                                            
                                            @error('ApPaterno') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Apellido Materno -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Apellido Materno</span>
                                                <!-- Eliminado el asterisco de requerido -->
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="false">
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('ApMaterno') is-invalid @enderror" 
                                                       id="ApMaterno" 
                                                       name="ApMaterno" 
                                                       value="{{ old('ApMaterno') }}" 
                                                       placeholder="Ej: González" 
                                                       maxlength="255"
                                                       data-char-counter="apMaternoCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="apMaternoCount">0/255</span>
                                                </div>
                                            </div>
                                            
                                            @error('ApMaterno') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Sexo -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Género</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="gender-selector-grid" data-required="true" id="genderGroup">
                                                <div class="gender-option-card {{ old('Sexo') == 'Masculino' ? 'selected' : '' }}" data-gender="Masculino">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-mars"></i>
                                                    </div>
                                                    <span class="gender-label">Masculino</span>
                                                    <input type="radio" name="Sexo" value="Masculino" id="sexo_m" 
                                                           class="gender-input" {{ old('Sexo') == 'Masculino' ? 'checked' : '' }} required>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="gender-option-card {{ old('Sexo') == 'Femenino' ? 'selected' : '' }}" data-gender="Femenino">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-venus"></i>
                                                    </div>
                                                    <span class="gender-label">Femenino</span>
                                                    <input type="radio" name="Sexo" value="Femenino" id="sexo_f"
                                                           class="gender-input" {{ old('Sexo') == 'Femenino' ? 'checked' : '' }}>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="gender-option-card {{ old('Sexo') == 'Otro' ? 'selected' : '' }}" data-gender="Otro">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-transgender-alt"></i>
                                                    </div>
                                                    <span class="gender-label">Otro</span>
                                                    <input type="radio" name="Sexo" value="Otro" id="sexo_otro"
                                                           class="gender-input" {{ old('Sexo') == 'Otro' ? 'checked' : '' }}>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="gender-error-message error-message" style="display: none;">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Debe seleccionar un género
                                            </div>
                                            
                                            @error('Sexo') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nombre Completo Generado -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Nombre Completo Generado</span>
                                            </label>
                                            
                                            <div class="preview-card p-3" style="
                                                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                                border-radius: 12px;
                                                border-left: 5px solid #28a745;
                                                transition: all 0.3s ease;
                                            ">
                                                <div class="d-flex align-items-center">
                                                    <div class="gender-icon-wrapper me-3" style="
                                                        width: 40px;
                                                        height: 40px;
                                                        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
                                                        border-radius: 10px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        color: #667eea;
                                                    ">
                                                        <i class="fas fa-user-check"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold" id="nombreCompletoPreview">
                                                            @if(old('Nombre') || old('ApPaterno') || old('ApMaterno'))
                                                                {{ old('Nombre') }} {{ old('ApPaterno') }} {{ old('ApMaterno') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted">Generado automáticamente</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Información de Empresa y Contacto -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información de Empresa y Contacto</h3>
                                            <p class="section-subtitle mb-0">Datos de la empresa y contacto del proveedor</p>
                                        </div>
                                    </div>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Empresa Asociada -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Empresa Asociada</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('Empresa_asociada') is-invalid @enderror" 
                                                       id="Empresa_asociada" 
                                                       name="Empresa_asociada" 
                                                       value="{{ old('Empresa_asociada') }}" 
                                                       placeholder="Ej: Proveedores S.A. de C.V." 
                                                       required 
                                                       maxlength="255"
                                                       data-char-counter="empresaCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="empresaCount">0/255</span>
                                                </div>
                                            </div>
                                            
                                            @error('Empresa_asociada') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Correo Electrónico -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Correo Electrónico</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="email-input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                                <input type="email" 
                                                       class="input-field @error('Correo') is-invalid @enderror" 
                                                       id="Correo" 
                                                       name="Correo" 
                                                       value="{{ old('Correo') }}" 
                                                       placeholder="proveedor@empresa.com"
                                                       required
                                                       maxlength="255">
                                                <div class="email-validation-badge" id="emailValidationBadge">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="email-status" id="emailStatus">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span>Ingrese un correo válido</span>
                                                </div>
                                            </div>
                                            
                                            @error('Correo') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Teléfono -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Teléfono</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="phone-input-wrapper" data-required="true">
                                                <div class="country-code-selector">
                                                    <div class="country-flag">
                                                        <i class="fas fa-flag mx"></i>
                                                    </div>
                                                    <span class="country-code">+52</span>
                                                </div>
                                                <input type="tel" 
                                                       class="phone-input @error('Telefono') is-invalid @enderror" 
                                                       id="Telefono" 
                                                       name="Telefono" 
                                                       value="{{ old('Telefono') }}" 
                                                       placeholder="777 123 4567" 
                                                       required 
                                                       maxlength="10"
                                                       pattern="[0-9]{10}">
                                                <div class="phone-actions">
                                                    <button type="button" class="btn-phone-clear" onclick="clearPhone()">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="phone-format">
                                                    <i class="fas fa-mobile-alt"></i>
                                                    <span>10 dígitos sin espacios</span>
                                                </div>
                                            </div>
                                            
                                            @error('Telefono') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
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
                                                <span id="validFieldsCount">0/6</span> completados
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                       
                                        
                                        <button type="submit" class="btn btn-primary btn-submit" id="submitBtn" style="
                                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                            border: none;
                                            padding: 12px 32px;
                                            border-radius: 12px;
                                            font-weight: 700;
                                            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                            position: relative;
                                            overflow: hidden;
                                        ">
                                            <span class="submit-content">
                                                <i class="fas fa-save me-2"></i>
                                                Guardar Proveedor
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

<!-- Toast Notifications Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #42e695 0%, #3bb2b8 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --danger-color: #ef4444;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
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
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
}

.toast-error .toast-icon-wrapper {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
}

.toast-warning .toast-icon-wrapper {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
}

.toast-info .toast-icon-wrapper {
    background: rgba(59, 130, 246, 0.1);
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
    --progress-color-start: #10b981;
    --progress-color-end: #059669;
}

.toast-error .toast-progress {
    --progress-color-start: #ef4444;
    --progress-color-end: #dc2626;
}

.toast-warning .toast-progress {
    --progress-color-start: #f59e0b;
    --progress-color-end: #d97706;
}

.toast-info .toast-progress {
    --progress-color-start: #3b82f6;
    --progress-color-end: #2563eb;
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
    background: var(--primary-gradient);
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
    background: linear-gradient(to right, #667eea, transparent);
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
.phone-input-wrapper,
.email-input-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper:focus-within,
.phone-input-wrapper:focus-within,
.email-input-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.input-wrapper.error,
.phone-input-wrapper.error,
.email-input-wrapper.error {
    border-color: var(--danger-color);
    animation: shake 0.5s;
}

.input-wrapper.valid,
.phone-input-wrapper.valid,
.email-input-wrapper.valid {
    border-color: var(--success-color);
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 1.1rem;
    z-index: 2;
}

.input-field,
.phone-input {
    width: 100%;
    padding: 20px 20px 20px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
    transition: var(--transition);
}

.input-field::placeholder,
.phone-input::placeholder {
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
.phone-input-wrapper:focus-within .input-decoration,
.email-input-wrapper:focus-within .input-decoration {
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

/* Gender Selector */
.gender-selector-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.gender-option-card {
    position: relative;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
}

.gender-option-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.gender-option-card.selected {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.gender-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    color: #667eea;
    font-size: 1.2rem;
    transition: var(--transition);
}

.gender-option-card.selected .gender-icon-wrapper {
    background: var(--primary-gradient);
    color: white;
}

.gender-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
}

.gender-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.selection-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    opacity: 0;
    transform: scale(0);
    transition: var(--transition);
}

.gender-option-card.selected .selection-indicator {
    opacity: 1;
    transform: scale(1);
}

.gender-error-message {
    margin-top: 8px;
    display: none;
}

.gender-selector-grid.error {
    border: 2px solid var(--danger-color);
    border-radius: 12px;
    padding: 2px;
}

/* Phone Input */
.phone-input-wrapper {
    display: flex;
}

.country-code-selector {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 16px;
    background: #f9fafb;
    border-right: 2px solid #e5e7eb;
    min-width: 100px;
}

.country-flag {
    color: #667eea;
}

.country-code {
    font-weight: 600;
    color: #374151;
}

.phone-input {
    flex: 1;
    padding: 20px 40px 20px 16px;
}

.phone-actions {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.phone-input-wrapper:hover .phone-actions {
    opacity: 1;
}

.btn-phone-clear {
    background: transparent;
    border: none;
    color: #9ca3af;
    padding: 4px;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-phone-clear:hover {
    background: #f3f4f6;
    color: #374151;
}

.phone-format {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: #6b7280;
}

/* Email Input */
.email-validation-badge {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #10b981;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    opacity: 0;
    transform: translateY(-50%) scale(0);
    transition: var(--transition);
}

.email-input-wrapper.valid .email-validation-badge {
    opacity: 1;
    transform: translateY(-50%) scale(1);
}

.email-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: #6b7280;
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
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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

/* Progress Bar */
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

/* Responsive */
@media (max-width: 768px) {
    .gender-selector-grid {
        grid-template-columns: 1fr;
    }
    
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
    
    .toast-notification {
        min-width: 300px;
        max-width: 350px;
        top: 10px;
        right: 10px;
    }
}
</style>

<script>
// Sistema de Notificaciones Mejorado
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

    showValidationError(fields) {
        const fieldNames = {
            'Nombre': 'Nombre',
            'ApPaterno': 'Apellido Paterno',
            'Sexo': 'Género',
            'Empresa_asociada': 'Empresa Asociada',
            'Correo': 'Correo Electrónico',
            'Telefono': 'Teléfono'
        };

        const fieldList = fields.map(f => `<span style="display: inline-block; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px; margin: 2px; font-size: 0.8rem;">${fieldNames[f] || f}</span>`).join(' ');
        
        this.show(
            `<div style="text-align: left; margin-top: 5px;">${fieldList}</div>`,
            'warning',
            'Campos requeridos'
        );
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

class FormManager {
    constructor() {
        this.requiredFields = [
            'Nombre', 'ApPaterno', 'Sexo', 'Empresa_asociada', 'Correo', 'Telefono'
        ];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.initGenderSelector();
        this.initEmailValidation();
        this.updateProgress();
        this.initRealTimeValidation();
        this.updateNombreCompleto();
        
        // Mostrar errores de validación del servidor si existen
        this.showServerErrors();
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
        document.getElementById('proveedorForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.validateAndSubmit();
        });

        window.resetForm = () => {
            if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
                document.getElementById('proveedorForm').reset();
                this.resetAllVisuals();
                notifier.showSuccess('Formulario restablecido correctamente');
            }
        };

        window.clearPhone = () => {
            document.getElementById('Telefono').value = '';
            this.validateField('Telefono');
            this.updateProgress();
        };
    }

    initRealTimeValidation() {
        this.requiredFields.forEach(fieldId => {
            if (fieldId === 'Sexo') return;
            
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                    if (['Nombre', 'ApPaterno'].includes(fieldId)) {
                        this.updateNombreCompleto();
                    }
                });
                field.addEventListener('change', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                });
                field.addEventListener('blur', () => {
                    this.validateField(fieldId);
                });
            }
        });

        // También validar ApMaterno pero no afecta el progreso requerido
        const apMaterno = document.getElementById('ApMaterno');
        if (apMaterno) {
            apMaterno.addEventListener('input', () => {
                this.updateNombreCompleto();
            });
        }

        document.querySelectorAll('input[name="Sexo"]').forEach(radio => {
            radio.addEventListener('change', () => {
                this.validateGender();
                this.updateProgress();
            });
        });
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const wrapper = field.closest('.input-wrapper, .phone-input-wrapper, .email-input-wrapper');
        if (!wrapper) return;

        wrapper.classList.remove('error', 'valid');

        if (field.hasAttribute('required') && !field.value.trim()) {
            wrapper.classList.add('error');
            return false;
        }

        if (fieldId === 'Correo' && field.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                wrapper.classList.add('error');
                return false;
            }
        }

        if (fieldId === 'Telefono' && field.value.trim()) {
            const phoneRegex = /^\d{10}$/;
            if (!phoneRegex.test(field.value)) {
                wrapper.classList.add('error');
                return false;
            }
        }

        if (field.value.trim()) {
            wrapper.classList.add('valid');
        }
        
        return true;
    }

    validateGender() {
        const selected = document.querySelector('input[name="Sexo"]:checked');
        const genderGroup = document.getElementById('genderGroup');
        const errorMessage = document.querySelector('.gender-error-message');

        if (!selected) {
            genderGroup.classList.add('error');
            if (errorMessage) errorMessage.style.display = 'flex';
            return false;
        } else {
            genderGroup.classList.remove('error');
            if (errorMessage) errorMessage.style.display = 'none';
            return true;
        }
    }

    validateAllFields() {
        const errors = [];
        
        if (!this.validateField('Nombre')) errors.push('Nombre');
        if (!this.validateField('ApPaterno')) errors.push('ApPaterno');
        if (!this.validateGender()) errors.push('Sexo');
        if (!this.validateField('Empresa_asociada')) errors.push('Empresa_asociada');
        if (!this.validateField('Correo')) errors.push('Correo');
        if (!this.validateField('Telefono')) errors.push('Telefono');

        if (errors.length > 0) {
            notifier.showValidationError(errors);
            
            const firstErrorId = errors[0] === 'Sexo' ? 'sexo_m' : errors[0];
            const firstError = document.getElementById(firstErrorId);
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                const wrapper = firstError.closest('.input-wrapper, .phone-input-wrapper, .email-input-wrapper, .gender-selector-grid');
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
            if (fieldId === 'Sexo') {
                return document.querySelector('input[name="Sexo"]:checked') !== null;
            }
            const field = document.getElementById(fieldId);
            return field && field.value.trim() !== '';
        }).length;

        const percentage = Math.round((completedFields / this.requiredFields.length) * 100);
        
        document.getElementById('formProgress').style.width = `${percentage}%`;
        document.getElementById('progressPercentage').textContent = `${percentage}% Completado`;
        document.getElementById('completedFields').textContent = `${completedFields} de ${this.requiredFields.length} campos completados`;
        document.getElementById('validFieldsCount').textContent = `${completedFields}/${this.requiredFields.length}`;
    }

    initCharacterCounters() {
        const textFields = [
            { id: 'Nombre', counter: 'nombreCount', max: 255 },
            { id: 'ApPaterno', counter: 'apPaternoCount', max: 255 },
            { id: 'ApMaterno', counter: 'apMaternoCount', max: 255 },
            { id: 'Empresa_asociada', counter: 'empresaCount', max: 255 }
        ];
        
        textFields.forEach(item => {
            const field = document.getElementById(item.id);
            if (field) {
                field.addEventListener('input', (e) => {
                    const length = e.target.value.length;
                    this.updateCharCounter(item.counter, length, item.max);
                });
                this.updateCharCounter(item.counter, field.value.length, item.max);
            }
        });
    }

    updateCharCounter(elementId, length, max) {
        const counter = document.getElementById(elementId);
        if (counter) {
            counter.textContent = `${length}/${max}`;
            counter.style.color = length > max ? '#ef4444' : length > 0 ? '#10b981' : '#9ca3af';
        }
    }

    initGenderSelector() {
        document.querySelectorAll('.gender-option-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const gender = card.dataset.gender;
                this.selectGender(gender);
                this.validateGender();
                this.updateProgress();
            });
        });

        // Marcar el género seleccionado inicialmente
        const selectedGender = document.querySelector('input[name="Sexo"]:checked');
        if (selectedGender) {
            const genderValue = selectedGender.value;
            const selectedCard = document.querySelector(`[data-gender="${genderValue}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
        }
    }

    selectGender(gender) {
        document.querySelectorAll('.gender-option-card').forEach(card => {
            card.classList.remove('selected');
        });

        const selectedCard = document.querySelector(`[data-gender="${gender}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
            const input = selectedCard.querySelector('.gender-input');
            if (input) {
                input.checked = true;
                input.dispatchEvent(new Event('change'));
            }
        }
    }

    initEmailValidation() {
        const emailField = document.getElementById('Correo');
        if (emailField) {
            emailField.addEventListener('input', (e) => {
                this.validateField('Correo');
            });
        }
    }

    updateNombreCompleto() {
        const nombre = document.getElementById('Nombre')?.value.trim() || '';
        const apPaterno = document.getElementById('ApPaterno')?.value.trim() || '';
        const apMaterno = document.getElementById('ApMaterno')?.value.trim() || '';
        
        const preview = document.getElementById('nombreCompletoPreview');
        if (preview) {
            if (nombre || apPaterno || apMaterno) {
                preview.textContent = `${nombre} ${apPaterno} ${apMaterno}`.trim();
            } else {
                preview.textContent = '-';
            }
        }
    }

    validateAndSubmit() {
        if (this.validateAllFields()) {
            this.submitForm();
        }
    }

    submitForm() {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('proveedorForm');

        if (submitBtn && form) {
            notifier.showInfo('Procesando solicitud...', 'Un momento por favor');
            
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            setTimeout(() => {
                form.submit();
            }, 500);
        }
    }

    resetAllVisuals() {
        this.updateCharCounter('nombreCount', 0, 255);
        this.updateCharCounter('apPaternoCount', 0, 255);
        this.updateCharCounter('apMaternoCount', 0, 255);
        this.updateCharCounter('empresaCount', 0, 255);

        document.querySelectorAll('.gender-option-card').forEach(card => {
            card.classList.remove('selected');
        });

        document.querySelectorAll('.input-wrapper, .phone-input-wrapper, .email-input-wrapper').forEach(wrapper => {
            wrapper.classList.remove('error', 'valid');
        });

        document.querySelectorAll('.error-message').forEach(msg => {
            msg.style.display = 'none';
        });

        document.getElementById('nombreCompletoPreview').textContent = '-';
        this.updateProgress();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FormManager();
});
</script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection