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
                                <i class="fas fa-user-plus fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Nuevo Empleado
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Complete el formulario para registrar un nuevo colaborador
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            
                            <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
                                border-radius: 12px;
                                padding: 8px 16px;
                                font-size: 0.9rem;
                                border: 1px solid #dee2e6;
                            ">
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-md-inline">Volver</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Principal Rediseñada -->
                <div class="main-card mx-3 mx-lg-4 mb-5" style="
                    background: white;
                    border-radius: 24px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.03);
                    overflow: hidden;
                ">
                    <!-- Progress Steps Mejorados -->
                    <div class="progress-steps px-4 px-lg-5 pt-4">
                        <div class="steps-container">
                            <div class="steps-line"></div>
                            <div class="steps-wrapper">
                                <div class="step active">
                                    <div class="step-number">
                                        <span>1</span>
                                        <div class="step-glow"></div>
                                    </div>
                                    <div class="step-content">
                                        <small class="step-subtitle">Paso 1</small>
                                        <h6 class="step-title mb-0">Datos Personales</h6>
                                    </div>
                                </div>
                                <div class="step">
                                    <div class="step-number">
                                        <span>2</span>
                                        <div class="step-glow"></div>
                                    </div>
                                    <div class="step-content">
                                        <small class="step-subtitle">Paso 2</small>
                                        <h6 class="step-title mb-0">Información Laboral</h6>
                                    </div>
                                </div>
                                <div class="step">
                                    <div class="step-number">
                                        <span>3</span>
                                        <div class="step-glow"></div>
                                    </div>
                                    <div class="step-content">
                                        <small class="step-subtitle">Paso 3</small>
                                        <h6 class="step-title mb-0">Credenciales</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <form id="empleadoForm" action="{{ route('personal.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <!-- Sección 1: Información Personal - Rediseñada -->
                            <div class="form-section mb-5" data-step="1">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información Personal</h3>
                                            <p class="section-subtitle mb-0">Datos básicos del empleado</p>
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
                                                <div class="label-tooltip" data-bs-toggle="tooltip" title="Nombre completo del empleado">
                                                    <i class="fas fa-question-circle"></i>
                                                </div>
                                            </label>
                                            
                                            <div class="input-wrapper">
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
                                                       maxlength="85"
                                                       autocomplete="off"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">0/85</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Nombre completo
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
                                            
                                            <div class="input-wrapper">
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
                                                       maxlength="85"
                                                       data-char-counter="apPaternoCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="apPaternoCount">0/85</span>
                                                </div>
                                                <div class="input-hint">
                                                    Primer apellido
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Apellido Materno -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Apellido Materno</span>
                                                <span class="label-optional">(Opcional)</span>
                                            </label>
                                            
                                            <div class="input-wrapper">
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('ApMaterno') is-invalid @enderror" 
                                                       id="ApMaterno" 
                                                       name="ApMaterno" 
                                                       value="{{ old('ApMaterno') }}" 
                                                       placeholder="Ej: González" 
                                                       maxlength="85"
                                                       data-char-counter="apMaternoCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="apMaternoCount">0/85</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fecha de Nacimiento -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Fecha de Nacimiento</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="date-picker-wrapper">
                                                <div class="input-icon">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <input type="date" 
                                                       class="date-picker @error('Fecha_nacimiento') is-invalid @enderror" 
                                                       id="Fecha_nacimiento" 
                                                       name="Fecha_nacimiento" 
                                                       value="{{ old('Fecha_nacimiento') }}" 
                                                       min="1950-01-01" 
                                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}" 
                                                       required>
                                                <div class="date-display" id="fechaDisplay">
                                                    <span class="date-placeholder">DD / MM / AAAA</span>
                                                    <span class="date-value"></span>
                                                </div>
                                                <div class="date-actions">
                                                    <button type="button" class="btn-date-clear" onclick="clearDate()">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="age-calculator" id="ageCalculator">
                                                    <i class="fas fa-birthday-cake"></i>
                                                    <span>Seleccione una fecha</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sexo -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Género</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="gender-selector-grid">
                                                <div class="gender-option-card" data-gender="M">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-mars"></i>
                                                    </div>
                                                    <span class="gender-label">Masculino</span>
                                                    <input type="radio" name="Sexo" value="M" id="sexo_m" 
                                                           class="gender-input" {{ old('Sexo') == 'M' ? 'checked' : '' }} required>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="gender-option-card" data-gender="F">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-venus"></i>
                                                    </div>
                                                    <span class="gender-label">Femenino</span>
                                                    <input type="radio" name="Sexo" value="F" id="sexo_f"
                                                           class="gender-input" {{ old('Sexo') == 'F' ? 'checked' : '' }}>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="gender-option-card" data-gender="Otro">
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
                                        </div>
                                    </div>

                                    <!-- Teléfono -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Teléfono</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="phone-input-wrapper">
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
                                                       pattern="[0-9]{10}"
                                                       data-phone-format="true">
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Información Laboral - Rediseñada -->
                            <div class="form-section mb-5" data-step="2">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información Laboral</h3>
                                            <p class="section-subtitle mb-0">Detalles del puesto y área de trabajo</p>
                                        </div>
                                    </div>
                                    <div class="section-divider"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Cargo -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Cargo</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="select-wrapper">
                                                <div class="select-icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <select class="select-field @error('Cargo') is-invalid @enderror" 
                                                        id="Cargo" name="Cargo" required>
                                                    <option value="" disabled selected hidden>Seleccione un cargo</option>
                                                    @foreach($cargos ?? ['Administrador','Gerente','Encargado de almacén','Supervisor','Auxiliar'] as $cargo)
                                                        <option value="{{ $cargo }}" {{ old('Cargo') == $cargo ? 'selected' : '' }}>
                                                            {{ $cargo }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="select-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Posición en la empresa
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Área de trabajo -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Área de Trabajo</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="select-wrapper">
                                                <div class="select-icon">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                                <select class="select-field @error('Area_trabajo') is-invalid @enderror" 
                                                        id="Area_trabajo" name="Area_trabajo" required>
                                                    <option value="" disabled selected hidden>Seleccione un área</option>
                                                    @foreach($areas ?? ['Almacén','Oficina','Logística','Recursos Humanos','Ventas','Producción'] as $area)
                                                        <option value="{{ $area }}" {{ old('Area_trabajo') == $area ? 'selected' : '' }}>
                                                            {{ $area }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="select-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-sitemap"></i>
                                                    Departamento asignado
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 3: Credenciales de Usuario - Rediseñada -->
                            <div class="form-section mb-5" data-step="3">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #42e695 0%, #3bb2b8 100%);">
                                            <i class="fas fa-user-lock"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Credenciales de Usuario</h3>
                                            <p class="section-subtitle mb-0">Datos de acceso al sistema</p>
                                        </div>
                                    </div>
                                    <div class="section-divider"></div>
                                </div>

                                <div id="usuario_fields">
                                    <!-- Email y Rol -->
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group-enhanced">
                                                <label class="form-label-enhanced">
                                                    <span class="label-text">Correo Electrónico</span>
                                                    <span class="label-required">*</span>
                                                </label>
                                                
                                                <div class="email-input-wrapper">
                                                    <div class="input-icon">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <input type="email" 
                                                           class="input-field @error('correo_usuario') is-invalid @enderror" 
                                                           id="correo_usuario" 
                                                           name="correo_usuario" 
                                                           value="{{ old('correo_usuario') }}" 
                                                           placeholder="correo@dominio.com"
                                                           required>
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
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group-enhanced">
                                                <label class="form-label-enhanced">
                                                    <span class="label-text">Rol de Usuario</span>
                                                    <span class="label-required">*</span>
                                                </label>
                                                
                                                <div class="role-selector-grid">
                                                    @foreach($roles as $rol)
                                                    <div class="role-option-card" data-role="{{ $rol }}">
                                                        <div class="role-icon-wrapper">
                                                            <i class="fas fa-user-tag"></i>
                                                        </div>
                                                        <span class="role-label">{{ $rol }}</span>
                                                        <input type="radio" name="rol" value="{{ $rol }}" 
                                                               class="role-input" id="rol_{{ $rol }}" 
                                                               {{ old('rol') == $rol ? 'checked' : '' }} required>
                                                        <div class="selection-indicator">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                
                                                <div class="input-meta">
                                                    <div class="input-hint">
                                                        <i class="fas fa-shield-alt"></i>
                                                        Define los permisos del sistema
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contraseñas -->
                                    <div class="password-section">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group-enhanced">
                                                    <label class="form-label-enhanced">
                                                        <span class="label-text">Contraseña</span>
                                                        <span class="label-required">*</span>
                                                    </label>
                                                    
                                                    <div class="password-input-wrapper">
                                                        <div class="input-icon">
                                                            <i class="fas fa-lock"></i>
                                                        </div>
                                                        <input type="password" 
                                                               class="password-field @error('contrasena') is-invalid @enderror" 
                                                               id="contrasena" 
                                                               name="contrasena" 
                                                               placeholder="Mínimo 8 caracteres"
                                                               required 
                                                               minlength="8"
                                                               data-strength-meter="true">
                                                        <div class="password-actions">
                                                            <button type="button" class="btn-toggle-password" data-target="contrasena">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn-generate" onclick="generateSecurePassword()">
                                                                <i class="fas fa-magic"></i>
                                                            </button>
                                                        </div>
                                                        <div class="input-decoration"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group-enhanced">
                                                    <label class="form-label-enhanced">
                                                        <span class="label-text">Confirmar Contraseña</span>
                                                        <span class="label-required">*</span>
                                                    </label>
                                                    
                                                    <div class="password-input-wrapper">
                                                        <div class="input-icon">
                                                            <i class="fas fa-lock"></i>
                                                        </div>
                                                        <input type="password" 
                                                               class="password-field" 
                                                               id="contrasena_confirmation" 
                                                               name="contrasena_confirmation" 
                                                               placeholder="Repite la contraseña"
                                                               required>
                                                        <div class="password-actions">
                                                            <button type="button" class="btn-toggle-password" data-target="contrasena_confirmation">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="input-decoration"></div>
                                                    </div>
                                                    
                                                    <div class="password-match-indicator" id="passwordMatchIndicator">
                                                        <div class="match-icon">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <span class="match-text">Las contraseñas coinciden</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Panel de Seguridad -->
                                        <div class="security-panel mt-4">
                                            <div class="panel-header">
                                                <h6 class="panel-title">
                                                    <i class="fas fa-shield-alt me-2"></i>
                                                    Análisis de Seguridad
                                                </h6>
                                                <div class="security-score" id="securityScore">
                                                    <span class="score-label">Seguridad:</span>
                                                    <span class="score-value">0%</span>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="strength-meters">
                                                    <div class="strength-meter">
                                                        <div class="meter-label">
                                                            <i class="fas fa-ruler-horizontal"></i>
                                                            <span>Longitud</span>
                                                        </div>
                                                        <div class="meter-bar">
                                                            <div class="meter-fill" data-meter="length" style="width: 0%"></div>
                                                        </div>
                                                        <div class="meter-value">0/8</div>
                                                    </div>
                                                    
                                                    <div class="strength-meter">
                                                        <div class="meter-label">
                                                            <i class="fas fa-font"></i>
                                                            <span>Complejidad</span>
                                                        </div>
                                                        <div class="meter-bar">
                                                            <div class="meter-fill" data-meter="complexity" style="width: 0%"></div>
                                                        </div>
                                                        <div class="meter-value">0/3</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="security-tips">
                                                    <div class="tip-item">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                        <span>Mínimo 8 caracteres</span>
                                                    </div>
                                                    <div class="tip-item">
                                                        <i class="fas fa-times-circle text-muted"></i>
                                                        <span>Mayúsculas y minúsculas</span>
                                                    </div>
                                                    <div class="tip-item">
                                                        <i class="fas fa-times-circle text-muted"></i>
                                                        <span>Números</span>
                                                    </div>
                                                    <div class="tip-item">
                                                        <i class="fas fa-times-circle text-muted"></i>
                                                        <span>Símbolos especiales</span>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <span id="validFieldsCount">0/12</span> completados
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="reset" class="btn btn-outline-secondary btn-action" onclick="resetForm()">
                                            <i class="fas fa-eraser"></i>
                                            <span>Limpiar Todo</span>
                                        </button>
                                        
                                        <div class="btn-group-enhanced">
                                            <button type="button" class="btn btn-outline-primary btn-action" id="prevStepBtn">
                                                <i class="fas fa-arrow-left"></i>
                                                <span>Anterior</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-action" id="nextStepBtn">
                                                <span>Siguiente</span>
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                        
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
                                                <i class="fas fa-user-plus me-2"></i>
                                                Registrar Empleado
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

                <!-- Footer Informativo -->
                <div class="info-footer text-center py-4 px-3">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                        <div class="info-item">
                            <i class="fas fa-shield-alt text-primary"></i>
                            <span class="ms-2">Datos protegidos y encriptados</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock text-warning"></i>
                            <span class="ms-2">Tiempo estimado: 3 minutos</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-headset text-success"></i>
                            <span class="ms-2">Soporte: soporte@empresa.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0">
                <div class="modal-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h5 class="modal-title">Confirmar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de registrar a este nuevo empleado?</p>
                <div class="summary-card">
                    <div class="summary-item">
                        <span>Nombre completo:</span>
                        <strong id="summaryNombre"></strong>
                    </div>
                    <div class="summary-item">
                        <span>Cargo:</span>
                        <strong id="summaryCargo"></strong>
                    </div>
                    <div class="summary-item">
                        <span>Correo:</span>
                        <strong id="summaryEmail"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">Sí, Registrar</button>
            </div>
        </div>
    </div>
</div>

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

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes ripple {
    to { transform: scale(4); opacity: 0; }
}

@keyframes progress {
    from { width: 0%; }
}

/* Progress Steps */
.progress-steps {
    position: relative;
    padding-bottom: 2rem;
}

.steps-container {
    position: relative;
}

.steps-line {
    position: absolute;
    top: 24px;
    left: 50px;
    right: 50px;
    height: 3px;
    background: linear-gradient(to right, #667eea, #e5e7eb);
    border-radius: 3px;
    z-index: 1;
}

.steps-wrapper {
    display: flex;
    justify-content: space-between;
    position: relative;
    z-index: 2;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}

.step-number {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: #6b7280;
    margin-bottom: 12px;
    position: relative;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.step.active .step-number {
    background: var(--primary-gradient);
    border-color: #667eea;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.step-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: var(--primary-gradient);
    opacity: 0;
    animation: pulse 2s infinite;
}

.step.active .step-glow {
    opacity: 0.3;
}

.step-content {
    text-align: center;
}

.step-subtitle {
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.step-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
}

/* Form Sections */
.form-section {
    animation: slideIn 0.6s ease-out;
}

.section-header {
    margin-bottom: 2.5rem;
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

.label-tooltip {
    color: #9ca3af;
    cursor: help;
    font-size: 0.85rem;
}

/* Input Wrapper */
.input-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
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

.input-field {
    width: 100%;
    padding: 20px 20px 20px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
    transition: var(--transition);
}

.input-field::placeholder {
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

.input-wrapper:focus-within .input-decoration {
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

/* Date Picker */
.date-picker-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
    cursor: pointer;
}

.date-picker-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.date-picker {
    width: 100%;
    height: 56px;
    padding: 0 48px;
    border: none;
    background: transparent;
    color: transparent;
    outline: none;
    cursor: pointer;
}

.date-display {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 20px 48px;
    display: flex;
    align-items: center;
    pointer-events: none;
}

.date-placeholder {
    color: #9ca3af;
    font-size: 1rem;
}

.date-value {
    color: #1f2937;
    font-weight: 500;
    font-size: 1rem;
}

.date-actions {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.date-picker-wrapper:hover .date-actions {
    opacity: 1;
}

.btn-date-clear {
    background: transparent;
    border: none;
    color: #9ca3af;
    padding: 4px;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-date-clear:hover {
    background: #f3f4f6;
    color: #374151;
}

.age-calculator {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
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

/* Phone Input */
.phone-input-wrapper {
    display: flex;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    overflow: hidden;
    transition: var(--transition);
}

.phone-input-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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
    padding: 16px;
    border: none;
    outline: none;
    font-size: 1rem;
    color: #1f2937;
    background: transparent;
}

.phone-actions {
    padding: 0 12px;
    display: flex;
    align-items: center;
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

/* Select Fields */
.select-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.select-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.select-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    z-index: 2;
}

.select-field {
    width: 100%;
    padding: 16px 48px 16px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
    appearance: none;
    cursor: pointer;
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    transition: transform 0.3s ease;
}

.select-wrapper:focus-within .select-arrow {
    transform: translateY(-50%) rotate(180deg);
}

.select-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.select-wrapper:focus-within .select-decoration {
    transform: scaleX(1);
}

/* Email Input */
.email-input-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.email-input-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

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

/* Role Selector */
.role-selector-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.role-option-card {
    position: relative;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
}

.role-option-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.role-option-card.selected {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.role-icon-wrapper {
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
}

.role-option-card.selected .role-icon-wrapper {
    background: var(--primary-gradient);
    color: white;
}

.role-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
}

/* Password Input */
.password-input-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.password-input-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.password-field {
    width: 100%;
    padding: 20px 100px 20px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
}

.password-actions {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    gap: 8px;
}

.btn-toggle-password,
.btn-generate {
    background: transparent;
    border: none;
    color: #9ca3af;
    padding: 6px;
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-toggle-password:hover,
.btn-generate:hover {
    background: #f3f4f6;
    color: #667eea;
}

.password-match-indicator {
    margin-top: 8px;
    padding: 8px 12px;
    background: #d1fae5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    opacity: 0;
    transform: translateY(-10px);
    transition: var(--transition);
}

.password-match-indicator.show {
    opacity: 1;
    transform: translateY(0);
}

.match-icon {
    color: #10b981;
}

.match-text {
    font-size: 0.85rem;
    color: #065f46;
    font-weight: 500;
}

/* Security Panel */
.security-panel {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.panel-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.security-score {
    display: flex;
    align-items: center;
    gap: 8px;
}

.score-label {
    font-size: 0.85rem;
    color: #6b7280;
}

.score-value {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.strength-meters {
    margin-bottom: 24px;
}

.strength-meter {
    margin-bottom: 16px;
}

.meter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 0.85rem;
    color: #4b5563;
}

.meter-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.meter-fill {
    height: 100%;
    background: var(--primary-gradient);
    border-radius: 4px;
    transition: width 0.6s ease;
}

.meter-value {
    text-align: right;
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 4px;
}

.security-tips {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
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

.btn-group-enhanced {
    display: flex;
    gap: 1px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.btn-group-enhanced .btn-action {
    border-radius: 0;
    border: none;
}

.btn-submit {
    position: relative;
    overflow: hidden;
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

@keyframes shine {
    0% { transform: rotate(30deg) translateX(-100%); }
    100% { transform: rotate(30deg) translateX(100%); }
}

/* Modal */
.modal-content {
    border: none;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    padding: 32px 32px 16px;
    position: relative;
}

.modal-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-gradient);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-right: 16px;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.modal-body {
    padding: 0 32px 32px;
}

.summary-card {
    background: #f9fafb;
    border-radius: 12px;
    padding: 20px;
    margin-top: 16px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
}

.summary-item:last-child {
    border-bottom: none;
}

.modal-footer {
    padding: 24px 32px 32px;
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

/* Responsive */
@media (max-width: 768px) {
    .steps-wrapper {
        flex-direction: column;
        gap: 24px;
    }
    
    .steps-line {
        display: none;
    }
    
    .step {
        flex-direction: row;
        text-align: left;
        gap: 16px;
    }
    
    .step-number {
        margin-bottom: 0;
    }
    
    .step-content {
        text-align: left;
    }
    
    .gender-selector-grid,
    .role-selector-grid {
        grid-template-columns: 1fr;
    }
    
    .security-tips {
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
</style>

<script>
class FormManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 3;
        this.formData = {};
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.initDatePicker();
        this.initGenderSelector();
        this.initRoleSelector();
        this.initEmailValidation();
        this.initPasswordSystem();
        this.initStepNavigation();
        this.updateStepIndicator();
        this.updateFormStats();
    }

    setupEventListeners() {
        // Submit form via modal
        document.getElementById('confirmSubmit')?.addEventListener('click', () => {
            this.submitForm();
        });

        // Form submit
        document.getElementById('empleadoForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.showConfirmationModal();
        });

        // Reset form
        window.resetForm = () => {
            if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
                document.getElementById('empleadoForm').reset();
                this.resetAllVisuals();
                this.showToast('Formulario restablecido', 'success');
            }
        };

        // Clear individual fields
        window.clearField = (fieldId) => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = '';
                field.dispatchEvent(new Event('input'));
                this.showToast('Campo limpiado', 'info');
            }
        };

        window.clearDate = () => {
            const field = document.getElementById('Fecha_nacimiento');
            if (field) {
                field.value = '';
                field.dispatchEvent(new Event('change'));
                this.showToast('Fecha limpiada', 'info');
            }
        };

        window.clearPhone = () => {
            const field = document.getElementById('Telefono');
            if (field) {
                field.value = '';
                field.dispatchEvent(new Event('input'));
                this.showToast('Teléfono limpiado', 'info');
            }
        };

        // Toggle password visibility
        document.querySelectorAll('.btn-toggle-password').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const target = e.target.closest('button').dataset.target;
                this.togglePassword(target, e.target.closest('button'));
            });
        });

        // Generate password
        window.generateSecurePassword = () => {
            this.generatePassword();
        };
    }

    initCharacterCounters() {
        const textFields = ['Nombre', 'ApPaterno', 'ApMaterno'];
        
        textFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', (e) => {
                    const length = e.target.value.length;
                    this.updateCharCounter(fieldId + 'Count', length);
                    this.validateTextField(e.target, length);
                });
                this.updateCharCounter(fieldId + 'Count', field.value.length);
            }
        });
    }

    updateCharCounter(elementId, length) {
        const counter = document.getElementById(elementId);
        if (counter) {
            counter.textContent = `${length}/85`;
            counter.style.color = length > 85 ? '#ef4444' : length > 0 ? '#10b981' : '#9ca3af';
        }
    }

    validateTextField(field, length) {
        const wrapper = field.closest('.input-wrapper');
        if (!wrapper) return;

        wrapper.classList.remove('is-valid', 'is-invalid');
        
        if (length === 0) return;
        
        if (length > 85) {
            wrapper.classList.add('is-invalid');
        } else {
            wrapper.classList.add('is-valid');
        }
    }

    initDatePicker() {
        const dateField = document.getElementById('Fecha_nacimiento');
        const display = document.getElementById('fechaDisplay');
        const ageCalculator = document.getElementById('ageCalculator');

        if (dateField && display && ageCalculator) {
            dateField.addEventListener('change', (e) => {
                this.updateDateDisplay(e.target.value);
                this.calculateAge(e.target.value);
            });
            this.updateDateDisplay(dateField.value);
        }
    }

    updateDateDisplay(dateString) {
        const display = document.getElementById('fechaDisplay');
        if (!display) return;

        const placeholder = display.querySelector('.date-placeholder');
        const value = display.querySelector('.date-value');

        if (dateString) {
            const date = new Date(dateString);
            const formatted = date.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            placeholder.style.display = 'none';
            value.textContent = formatted;
            value.style.display = 'block';
        } else {
            placeholder.style.display = 'block';
            value.textContent = '';
            value.style.display = 'none';
        }
    }

    calculateAge(dateString) {
        const ageCalculator = document.getElementById('ageCalculator');
        if (!ageCalculator || !dateString) return;

        const birthDate = new Date(dateString);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        let icon, color, message;
        if (age >= 18) {
            icon = 'fa-check-circle';
            color = '#10b981';
            message = `${age} años - Mayor de edad ✓`;
        } else {
            icon = 'fa-exclamation-triangle';
            color = '#ef4444';
            message = `${age} años - Menor de edad ✗`;
        }

        ageCalculator.innerHTML = `
            <i class="fas ${icon}" style="color: ${color}"></i>
            <span style="color: ${color}">${message}</span>
        `;
    }

    initGenderSelector() {
        document.querySelectorAll('.gender-option-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const gender = card.dataset.gender;
                this.selectGender(gender);
            });
        });
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

    initRoleSelector() {
        document.querySelectorAll('.role-option-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const role = card.dataset.role;
                this.selectRole(role);
            });
        });
    }

    selectRole(role) {
        document.querySelectorAll('.role-option-card').forEach(card => {
            card.classList.remove('selected');
        });

        const selectedCard = document.querySelector(`[data-role="${role}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
            const input = selectedCard.querySelector('.role-input');
            if (input) {
                input.checked = true;
                input.dispatchEvent(new Event('change'));
            }
        }
    }

    initEmailValidation() {
        const emailField = document.getElementById('correo_usuario');
        const wrapper = document.querySelector('.email-input-wrapper');

        if (emailField && wrapper) {
            emailField.addEventListener('input', (e) => {
                this.validateEmail(e.target.value, wrapper);
            });
        }
    }

    validateEmail(email, wrapper) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(email);

        wrapper.classList.remove('is-valid', 'is-invalid', 'valid');
        
        if (email === '') return;

        if (isValid) {
            wrapper.classList.add('is-valid', 'valid');
        } else {
            wrapper.classList.add('is-invalid');
        }
    }

    initPasswordSystem() {
        const passwordField = document.getElementById('contrasena');
        const confirmField = document.getElementById('contrasena_confirmation');

        if (passwordField && confirmField) {
            passwordField.addEventListener('input', (e) => {
                this.analyzePasswordStrength(e.target.value);
                this.checkPasswordMatch();
            });

            confirmField.addEventListener('input', () => {
                this.checkPasswordMatch();
            });
        }
    }

    analyzePasswordStrength(password) {
        let scores = {
            length: Math.min((password.length / 8) * 100, 100),
            complexity: 0
        };

        // Complexity calculation
        let complexityScore = 0;
        if (/[A-Z]/.test(password)) complexityScore++;
        if (/[a-z]/.test(password)) complexityScore++;
        if (/[0-9]/.test(password)) complexityScore++;
        if (/[^A-Za-z0-9]/.test(password)) complexityScore++;

        scores.complexity = (complexityScore / 4) * 100;

        // Update meters
        this.updateStrengthMeter('length', scores.length);
        this.updateStrengthMeter('complexity', scores.complexity);

        // Update overall score
        const overallScore = (scores.length + scores.complexity) / 2;
        this.updateSecurityScore(overallScore);

        // Update tips
        this.updateSecurityTips(password);
    }

    updateStrengthMeter(type, score) {
        const fill = document.querySelector(`[data-meter="${type}"]`);
        const value = document.querySelectorAll('.meter-value')[type === 'length' ? 0 : 1];

        if (fill) {
            fill.style.width = `${score}%`;
            fill.style.background = this.getScoreColor(score);
        }

        if (value) {
            if (type === 'length') {
                value.textContent = `${Math.round(score / 100 * 8)}/8`;
            } else {
                value.textContent = `${Math.round(score / 100 * 4)}/4`;
            }
        }
    }

    updateSecurityScore(score) {
        const scoreElement = document.getElementById('securityScore');
        if (scoreElement) {
            const value = scoreElement.querySelector('.score-value');
            value.textContent = `${Math.round(score)}%`;
            value.style.color = this.getScoreColor(score);
        }
    }

    updateSecurityTips(password) {
        const tips = document.querySelectorAll('.tip-item');
        const checks = [
            password.length >= 8,
            /[A-Z]/.test(password) && /[a-z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];

        tips.forEach((tip, index) => {
            const icon = tip.querySelector('i');
            if (checks[index]) {
                icon.className = 'fas fa-check-circle text-success';
                tip.style.opacity = '1';
            } else {
                icon.className = 'fas fa-times-circle text-muted';
                tip.style.opacity = '0.6';
            }
        });
    }

    checkPasswordMatch() {
        const password = document.getElementById('contrasena')?.value;
        const confirm = document.getElementById('contrasena_confirmation')?.value;
        const indicator = document.getElementById('passwordMatchIndicator');

        if (!password || !confirm) {
            indicator?.classList.remove('show');
            return;
        }

        if (password === confirm) {
            indicator?.classList.add('show');
        } else {
            indicator?.classList.remove('show');
        }
    }

    getScoreColor(score) {
        if (score < 30) return '#ef4444';
        if (score < 60) return '#f59e0b';
        if (score < 80) return '#3b82f6';
        return '#10b981';
    }

    generatePassword() {
        const chars = {
            uppercase: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            lowercase: 'abcdefghijklmnopqrstuvwxyz',
            numbers: '0123456789',
            symbols: '!@#$%^&*()_+-=[]{}|;:,.<>?'
        };

        let password = '';
        
        // Ensure one of each type
        password += chars.uppercase[Math.floor(Math.random() * chars.uppercase.length)];
        password += chars.lowercase[Math.floor(Math.random() * chars.lowercase.length)];
        password += chars.numbers[Math.floor(Math.random() * chars.numbers.length)];
        password += chars.symbols[Math.floor(Math.random() * chars.symbols.length)];

        // Fill remaining characters
        const allChars = chars.uppercase + chars.lowercase + chars.numbers + chars.symbols;
        for (let i = 0; i < 8; i++) {
            password += allChars[Math.floor(Math.random() * allChars.length)];
        }

        // Shuffle
        password = password.split('').sort(() => Math.random() - 0.5).join('');

        // Set to fields
        document.getElementById('contrasena').value = password;
        document.getElementById('contrasena_confirmation').value = password;

        // Trigger events
        document.getElementById('contrasena').dispatchEvent(new Event('input'));
        document.getElementById('contrasena_confirmation').dispatchEvent(new Event('input'));

        this.showToast('Contraseña generada con éxito', 'success');
    }

    togglePassword(fieldId, button) {
        const field = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    initStepNavigation() {
        document.getElementById('prevStepBtn')?.addEventListener('click', () => {
            this.navigateToStep(this.currentStep - 1);
        });

        document.getElementById('nextStepBtn')?.addEventListener('click', () => {
            if (this.validateStep(this.currentStep)) {
                this.navigateToStep(this.currentStep + 1);
            }
        });
    }

    navigateToStep(step) {
        if (step < 1 || step > this.totalSteps) return;

        this.currentStep = step;
        this.updateStepIndicator();
        this.scrollToStep(step);
    }

    validateStep(step) {
        const requiredFields = document.querySelectorAll(`[data-step="${step}"] [required]`);
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.closest('.input-wrapper, .select-wrapper')?.classList.add('is-invalid');
                
                // Shake animation
                const wrapper = field.closest('.input-wrapper, .select-wrapper');
                if (wrapper) {
                    wrapper.style.animation = 'none';
                    setTimeout(() => {
                        wrapper.style.animation = 'shake 0.5s';
                    }, 10);
                }
            }
        });

        if (!isValid) {
            this.showToast('Complete los campos requeridos', 'warning');
        }

        return isValid;
    }

    updateStepIndicator() {
        document.querySelectorAll('.step').forEach((step, index) => {
            step.classList.toggle('active', index + 1 === this.currentStep);
        });

        // Update buttons
        const prevBtn = document.getElementById('prevStepBtn');
        const nextBtn = document.getElementById('nextStepBtn');

        if (prevBtn) {
            prevBtn.disabled = this.currentStep === 1;
            prevBtn.style.opacity = this.currentStep === 1 ? '0.5' : '1';
        }

        if (nextBtn) {
            nextBtn.textContent = this.currentStep === this.totalSteps ? 'Finalizar' : 'Siguiente';
        }
    }

    scrollToStep(step) {
        const section = document.querySelector(`[data-step="${step}"]`);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    updateFormStats() {
        const requiredFields = document.querySelectorAll('[required]');
        const completedFields = Array.from(requiredFields).filter(field => 
            field.value.trim() || field.type === 'radio' && field.checked
        ).length;

        const countElement = document.getElementById('validFieldsCount');
        if (countElement) {
            countElement.textContent = `${completedFields}/${requiredFields.length}`;
        }
    }

    showConfirmationModal() {
        // Gather data for summary
        this.formData = {
            nombre: document.getElementById('Nombre')?.value,
            cargo: document.getElementById('Cargo')?.value,
            email: document.getElementById('correo_usuario')?.value
        };

        // Update modal content
        document.getElementById('summaryNombre').textContent = this.formData.nombre || 'No especificado';
        document.getElementById('summaryCargo').textContent = this.formData.cargo || 'No especificado';
        document.getElementById('summaryEmail').textContent = this.formData.email || 'No especificado';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        modal.show();
    }

    submitForm() {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('empleadoForm');

        if (submitBtn && form) {
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            // Simulate processing
            setTimeout(() => {
                // Submit the form
                form.submit();
            }, 1500);
        }
    }

    resetAllVisuals() {
        // Reset character counters
        this.updateCharCounter('nombreCount', 0);
        this.updateCharCounter('apPaternoCount', 0);
        this.updateCharCounter('apMaternoCount', 0);

        // Reset date
        this.updateDateDisplay('');
        this.calculateAge('');

        // Reset gender and role selectors
        document.querySelectorAll('.gender-option-card, .role-option-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Reset email validation
        const emailWrapper = document.querySelector('.email-input-wrapper');
        emailWrapper?.classList.remove('is-valid', 'is-invalid', 'valid');

        // Reset password system
        this.analyzePasswordStrength('');
        this.checkPasswordMatch();

        // Update stats
        this.updateFormStats();
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
            </div>
            <div class="toast-message">${message}</div>
        `;

        document.body.appendChild(toast);

        // Remove after delay
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FormManager();
});
</script>
@endsection