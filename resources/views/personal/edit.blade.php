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
                                <i class="fas fa-user-edit fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Editar Empleado
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Modifique la información del colaborador #{{ $empleado->id }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
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
                                <small class="text-muted" id="completedFields">0 de 11 campos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="empleadoForm" action="{{ route('personal.update', $empleado->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Sección 1: Información Personal -->
                            <div class="form-section mb-5">
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
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('Nombre') is-invalid @enderror" 
                                                       id="Nombre" 
                                                       name="Nombre" 
                                                       value="{{ old('Nombre', $empleado->Nombre) }}" 
                                                       placeholder="Ej: Juan Carlos" 
                                                       required 
                                                       maxlength="85"
                                                       data-original="{{ $empleado->Nombre }}"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">{{ strlen($empleado->Nombre) }}/85</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->Nombre }}</span>
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
                                                       value="{{ old('ApPaterno', $empleado->ApPaterno) }}" 
                                                       placeholder="Ej: Pérez" 
                                                       required 
                                                       maxlength="85"
                                                       data-original="{{ $empleado->ApPaterno }}"
                                                       data-char-counter="apPaternoCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="apPaternoCount">{{ strlen($empleado->ApPaterno) }}/85</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->ApPaterno }}</span>
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
                                                       value="{{ old('ApMaterno', $empleado->ApMaterno) }}" 
                                                       placeholder="Ej: González" 
                                                       maxlength="85"
                                                       data-original="{{ $empleado->ApMaterno ?? '' }}"
                                                       data-char-counter="apMaternoCount">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="apMaternoCount">{{ strlen($empleado->ApMaterno ?? '') }}/85</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->ApMaterno ?? 'No especificado' }}</span>
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
                                            
                                            <div class="date-picker-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <input type="date" 
                                                       class="date-picker @error('Fecha_nacimiento') is-invalid @enderror" 
                                                       id="Fecha_nacimiento" 
                                                       name="Fecha_nacimiento" 
                                                       value="{{ old('Fecha_nacimiento', $empleado->Fecha_nacimiento ? $empleado->Fecha_nacimiento->format('Y-m-d') : '') }}" 
                                                       min="1950-01-01" 
                                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}" 
                                                       data-original="{{ $empleado->Fecha_nacimiento ? $empleado->Fecha_nacimiento->format('Y-m-d') : '' }}"
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
                                                    <span>
                                                        @if($empleado->Fecha_nacimiento)
                                                            {{ \Carbon\Carbon::parse($empleado->Fecha_nacimiento)->age }} años
                                                        @else
                                                            Seleccione una fecha
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->Fecha_nacimiento ? $empleado->Fecha_nacimiento->format('d/m/Y') : 'No especificada' }}</span>
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
                                            
                                            <div class="gender-selector-grid" data-required="true" id="genderGroup">
                                                <div class="gender-option-card {{ old('Sexo', $empleado->Sexo) == 'M' ? 'selected' : '' }}" data-gender="M">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-mars"></i>
                                                    </div>
                                                    <span class="gender-label">Masculino</span>
                                                    <input type="radio" name="Sexo" value="M" id="sexo_m" 
                                                           class="gender-input" {{ old('Sexo', $empleado->Sexo) == 'M' ? 'checked' : '' }} required>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="gender-option-card {{ old('Sexo', $empleado->Sexo) == 'F' ? 'selected' : '' }}" data-gender="F">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-venus"></i>
                                                    </div>
                                                    <span class="gender-label">Femenino</span>
                                                    <input type="radio" name="Sexo" value="F" id="sexo_f"
                                                           class="gender-input" {{ old('Sexo', $empleado->Sexo) == 'F' ? 'checked' : '' }}>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="gender-option-card {{ old('Sexo', $empleado->Sexo) == 'Otro' ? 'selected' : '' }}" data-gender="Otro">
                                                    <div class="gender-icon-wrapper">
                                                        <i class="fas fa-transgender-alt"></i>
                                                    </div>
                                                    <span class="gender-label">Otro</span>
                                                    <input type="radio" name="Sexo" value="Otro" id="sexo_otro"
                                                           class="gender-input" {{ old('Sexo', $empleado->Sexo) == 'Otro' ? 'checked' : '' }}>
                                                    <div class="selection-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="gender-error-message error-message" style="display: none;">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Debe seleccionar un género
                                            </div>

                                            <div class="input-meta mt-2">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: 
                                                    <span class="fw-medium">
                                                        @if($empleado->Sexo == 'M') Masculino
                                                        @elseif($empleado->Sexo == 'F') Femenino
                                                        @else Otro @endif
                                                    </span>
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
                                                       value="{{ old('Telefono', $empleado->Telefono) }}" 
                                                       placeholder="777 123 4567" 
                                                       required 
                                                       maxlength="10"
                                                       pattern="[0-9]{10}"
                                                       data-original="{{ $empleado->Telefono }}"
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
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->Telefono }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Información Laboral -->
                            <div class="form-section mb-5">
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
                                            
                                            <div class="select-wrapper" data-required="true">
                                                <div class="select-icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <select class="select-field @error('Cargo') is-invalid @enderror" 
                                                        id="Cargo" name="Cargo" 
                                                        data-original="{{ $empleado->Cargo }}"
                                                        required>
                                                    <option value="" disabled {{ old('Cargo', $empleado->Cargo) ? '' : 'selected' }}>Seleccione un cargo</option>
                                                    @foreach($cargos ?? ['Administrador','Gerente','Encargado de almacén','Supervisor','Auxiliar'] as $cargo)
                                                        <option value="{{ $cargo }}" {{ old('Cargo', $empleado->Cargo) == $cargo ? 'selected' : '' }}>
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
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->Cargo }}</span>
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
                                            
                                            <div class="select-wrapper" data-required="true">
                                                <div class="select-icon">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                                <select class="select-field @error('Area_trabajo') is-invalid @enderror" 
                                                        id="Area_trabajo" name="Area_trabajo" 
                                                        data-original="{{ $empleado->Area_trabajo }}"
                                                        required>
                                                    <option value="" disabled {{ old('Area_trabajo', $empleado->Area_trabajo) ? '' : 'selected' }}>Seleccione un área</option>
                                                    @foreach($areas ?? ['Almacén','Oficina','Logística','Recursos Humanos','Ventas','Producción'] as $area)
                                                        <option value="{{ $area }}" {{ old('Area_trabajo', $empleado->Area_trabajo) == $area ? 'selected' : '' }}>
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
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Original: <span class="fw-medium">{{ $empleado->Area_trabajo }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 3: Credenciales de Usuario -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #42e695 0%, #3bb2b8 100%);">
                                            <i class="fas fa-user-lock"></i>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div>
                                                <h3 class="section-title mb-1">Credenciales de Usuario</h3>
                                                <p class="section-subtitle mb-0">Datos de acceso al sistema</p>
                                            </div>
                                            @if($empleado->usuario)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" 
                                                       id="editar_credenciales" 
                                                       name="editar_credenciales" 
                                                       value="1" 
                                                       {{ old('editar_credenciales') ? 'checked' : '' }} 
                                                       style="width: 3em; height: 1.5em; cursor: pointer;"
                                                       onchange="toggleCredencialesFields(); updateProgress();">
                                                <label class="form-check-label fw-medium ms-2" for="editar_credenciales" id="credencialesSwitchLabel">
                                                    {{ old('editar_credenciales') ? 'Editando Credenciales' : 'Mantener Actuales' }}
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="section-divider"></div>
                                </div>

                                <div id="usuario_fields">
                                    @if($empleado->usuario)
                                    <!-- Información de usuario actual -->
                                    <div class="alert alert-info mb-4" style="border-radius: 12px; border-left: 4px solid #3b82f6;">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div style="
                                                    width: 40px;
                                                    height: 40px;
                                                    background: rgba(59, 130, 246, 0.1);
                                                    border-radius: 10px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                ">
                                                    <i class="fas fa-user-check text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0 fw-bold">Usuario actual asignado</h6>
                                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                                        <i class="fas fa-check-circle me-1"></i>Activo
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-envelope text-muted me-2"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Correo actual:</small>
                                                                <span class="fw-medium">{{ $empleado->usuario->correo }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-tag text-muted me-2"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Rol actual:</small>
                                                                <span class="fw-medium">{{ $empleado->usuario->rol ?? 'Sin rol asignado' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campos editables de credenciales -->
                                    <div id="credenciales_fields" class="{{ old('editar_credenciales') ? '' : 'd-none' }}">
                                        <div class="row g-4">
                                            <!-- Correo -->
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
                                                               class="input-field @error('correo') is-invalid @enderror" 
                                                               id="correo" 
                                                               name="correo" 
                                                               value="{{ old('correo', $empleado->usuario->correo) }}" 
                                                               placeholder="correo@dominio.com"
                                                               data-original="{{ $empleado->usuario->correo }}"
                                                               {{ old('editar_credenciales') ? 'required' : '' }}>
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
                                                        <div class="input-hint">
                                                            <i class="fas fa-lightbulb"></i>
                                                            Original: <span class="fw-medium">{{ $empleado->usuario->correo }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Rol -->
                                            <div class="col-md-6">
                                                <div class="form-group-enhanced">
                                                    <label class="form-label-enhanced">
                                                        <span class="label-text">Rol de Usuario</span>
                                                        <span class="label-required">*</span>
                                                    </label>
                                                    
                                                    <div class="role-selector-grid" data-required="true" id="roleGroup">
                                                        @foreach($roles ?? ['Administración','Almacén','Logística'] as $rol)
                                                        <div class="role-option-card {{ old('rol', $empleado->usuario->rol) == $rol ? 'selected' : '' }}" data-role="{{ $rol }}">
                                                            <div class="role-icon-wrapper">
                                                                <i class="fas fa-user-tag"></i>
                                                            </div>
                                                            <span class="role-label">{{ $rol }}</span>
                                                            <input type="radio" name="rol" value="{{ $rol }}" 
                                                                   class="role-input" id="rol_{{ $rol }}" 
                                                                   {{ old('rol', $empleado->usuario->rol) == $rol ? 'checked' : '' }} 
                                                                   {{ old('editar_credenciales') ? 'required' : '' }}>
                                                            <div class="selection-indicator">
                                                                <i class="fas fa-check"></i>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    
                                                    <div class="role-error-message error-message" style="display: none;">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        Debe seleccionar un rol
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información sobre contraseña -->
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" style="border-radius: 12px;">
                                                    <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                                                    <div>
                                                        <h6 class="alert-heading mb-1 fw-bold">Contraseña no modificable</h6>
                                                        <p class="mb-0 small">La contraseña no se puede modificar desde esta sección. 
                                                            Si el usuario necesita cambiar su contraseña, debe hacerlo desde la opción 
                                                            "Olvidé mi contraseña" en el inicio de sesión o contactar al administrador.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <!-- Si no tiene usuario -->
                                    <div class="alert alert-warning d-flex align-items-center" style="border-radius: 12px;">
                                        <div class="me-3">
                                            <div style="
                                                width: 50px;
                                                height: 50px;
                                                background: rgba(245, 158, 11, 0.1);
                                                border-radius: 12px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                            ">
                                                <i class="fas fa-user-slash fa-2x text-warning"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Sin usuario asignado</h6>
                                            <p class="mb-0">Este empleado no tiene credenciales de acceso al sistema.</p>
                                            <small class="text-warning-emphasis">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Para crear un usuario, utilice la opción "Crear Usuario" desde la vista de detalles del empleado.
                                            </small>
                                        </div>
                                    </div>
                                    @endif
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
                                                <span id="validFieldsCount">0/11</span> completados
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-history text-info"></i>
                                                <span id="modifiedFieldsCount">0</span> modificados
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="button" class="btn btn-outline-secondary btn-action" onclick="resetForm()" style="
                                            padding: 12px 24px;
                                            border-radius: 12px;
                                            font-weight: 600;
                                        ">
                                            <i class="fas fa-undo-alt me-2"></i>
                                            Restablecer
                                        </button>
                                        <button type="button" class="btn btn-primary btn-submit" id="submitBtn" style="
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
                                                Guardar Cambios
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

<!-- Incluir todos los estilos del diseño original -->
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

@keyframes progress {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
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
.date-picker-wrapper,
.phone-input-wrapper,
.select-wrapper,
.email-input-wrapper,
.password-input-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper:focus-within,
.date-picker-wrapper:focus-within,
.phone-input-wrapper:focus-within,
.select-wrapper:focus-within,
.email-input-wrapper:focus-within,
.password-input-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.input-wrapper.error,
.date-picker-wrapper.error,
.phone-input-wrapper.error,
.select-wrapper.error,
.email-input-wrapper.error,
.password-input-wrapper.error {
    border-color: var(--danger-color);
    animation: shake 0.5s;
}

.input-wrapper.valid,
.date-picker-wrapper.valid,
.phone-input-wrapper.valid,
.select-wrapper.valid,
.email-input-wrapper.valid,
.password-input-wrapper.valid {
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
.password-field,
.phone-input,
.date-picker {
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
.password-field::placeholder,
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
.date-picker-wrapper:focus-within .input-decoration,
.phone-input-wrapper:focus-within .input-decoration,
.select-wrapper:focus-within .input-decoration,
.email-input-wrapper:focus-within .input-decoration,
.password-input-wrapper:focus-within .input-decoration {
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
    cursor: pointer;
}

.date-picker {
    color: transparent;
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
    display: none;
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

/* Select Fields */
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

.role-error-message {
    margin-top: 8px;
    display: none;
}

.role-selector-grid.error {
    border: 2px solid var(--danger-color);
    border-radius: 12px;
    padding: 2px;
}

/* Password Input */
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

/* Toast Notifications System - Copiado del original */
.toast-notification {
    position: fixed;
    top: 30px;
    right: 30px;
    min-width: 380px;
    max-width: 450px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2), 0 10px 30px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    overflow: hidden;
    animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border: none;
}

.toast-notification.hiding {
    animation: slideOutRight 0.3s ease-in-out forwards;
}

.toast-content {
    display: flex;
    align-items: center;
    padding: 20px;
    position: relative;
}

.toast-icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.toast-icon-wrapper i {
    font-size: 24px;
    color: white;
}

.toast-text {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 4px;
    color: #1f2937;
}

.toast-message {
    font-size: 0.9rem;
    color: #6b7280;
    line-height: 1.4;
}

.toast-close {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #f3f4f6;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.toast-close:hover {
    background: #e5e7eb;
    color: #4b5563;
    transform: rotate(90deg);
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    background: linear-gradient(90deg, rgba(255,255,255,0.5), rgba(255,255,255,0.8));
    animation: progress 4s linear forwards;
}

/* Tipos de Toast */
.toast-success .toast-icon-wrapper {
    background: linear-gradient(135deg, #10b981, #059669);
}

.toast-success .toast-progress {
    background: linear-gradient(90deg, #10b981, #059669);
}

.toast-error .toast-icon-wrapper {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.toast-error .toast-progress {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.toast-warning .toast-icon-wrapper {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.toast-warning .toast-progress {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

.toast-info .toast-icon-wrapper {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.toast-info .toast-progress {
    background: linear-gradient(90deg, #3b82f6, #2563eb);
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
    .gender-selector-grid,
    .role-selector-grid {
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

    .toast-notification {
        top: 20px;
        right: 20px;
        left: 20px;
        min-width: auto;
        max-width: none;
    }

    .progress-overview {
        padding: 15px;
    }

    .form-stats {
        flex-direction: column;
        align-items: center;
    }
}

/* Clase shake mejorada */
.shake-enhanced {
    animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}

/* Highlight para campos modificados */
.modified-highlight {
    border-left: 4px solid #f59e0b !important;
    background: rgba(245, 158, 11, 0.02);
}
</style>

<script>
// Sistema de Notificaciones Mejorado - Copiado del original
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.maxNotifications = 3;
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

        document.body.appendChild(notification);
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
            'Fecha_nacimiento': 'Fecha de Nacimiento',
            'Sexo': 'Género',
            'Telefono': 'Teléfono',
            'Cargo': 'Cargo',
            'Area_trabajo': 'Área de Trabajo',
            'correo': 'Correo Electrónico',
            'rol': 'Rol de Usuario'
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
            'Nombre', 'ApPaterno', 'Fecha_nacimiento', 'Sexo', 'Telefono',
            'Cargo', 'Area_trabajo'
        ];
        
        @if($empleado->usuario)
        // Si hay usuario, los campos de credenciales pueden ser requeridos según el switch
        this.requiredFields.push('correo', 'rol');
        @endif
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.initDatePicker();
        this.initGenderSelector();
        this.initRoleSelector();
        this.initEmailValidation();
        this.updateProgress();
        this.initRealTimeValidation();
        this.initModifiedTracking();
    }

    setupEventListeners() {
        document.getElementById('submitBtn').addEventListener('click', (e) => {
            e.preventDefault();
            this.validateAndSubmit();
        });

        window.clearDate = () => {
            document.getElementById('Fecha_nacimiento').value = '';
            this.updateDateDisplay('');
            this.calculateAge('');
            this.validateField('Fecha_nacimiento');
            this.updateProgress();
        };

        window.clearPhone = () => {
            document.getElementById('Telefono').value = '';
            this.validateField('Telefono');
            this.updateProgress();
        };
    }

    initRealTimeValidation() {
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                    this.trackModified(field);
                });
                field.addEventListener('change', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                    this.trackModified(field);
                });
            }
        });

        document.querySelectorAll('input[name="Sexo"]').forEach(radio => {
            radio.addEventListener('change', () => {
                this.validateGender();
                this.updateProgress();
                this.trackModified(radio);
            });
        });

        @if($empleado->usuario)
        const checkbox = document.getElementById('editar_credenciales');
        if(checkbox) {
            checkbox.addEventListener('change', () => {
                this.toggleCredencialesFields();
                this.updateProgress();
            });
        }

        document.querySelectorAll('input[name="rol"]').forEach(radio => {
            radio.addEventListener('change', () => {
                this.validateRole();
                this.updateProgress();
                this.trackModified(radio);
            });
        });
        @endif
    }

    initModifiedTracking() {
        this.modifiedCount = 0;
        this.modifiedFields = new Set();
    }

    trackModified(element) {
        if(!element) return;
        
        const fieldId = element.id || element.name;
        const originalValue = element.dataset.original;
        const currentValue = element.value;
        
        if(originalValue && currentValue !== originalValue) {
            if(!this.modifiedFields.has(fieldId)) {
                this.modifiedFields.add(fieldId);
                this.modifiedCount++;
                element.closest('.form-group-enhanced')?.classList.add('modified-highlight');
            }
        } else {
            if(this.modifiedFields.has(fieldId)) {
                this.modifiedFields.delete(fieldId);
                this.modifiedCount--;
                element.closest('.form-group-enhanced')?.classList.remove('modified-highlight');
            }
        }
        
        document.getElementById('modifiedFieldsCount').textContent = this.modifiedCount;
    }

    toggleCredencialesFields() {
        const checkbox = document.getElementById('editar_credenciales');
        const fields = document.getElementById('credenciales_fields');
        const label = document.getElementById('credencialesSwitchLabel');
        const correo = document.getElementById('correo');
        const roleInputs = document.querySelectorAll('input[name="rol"]');
        
        if(checkbox.checked) {
            fields.classList.remove('d-none');
            label.textContent = 'Editando Credenciales';
            if(correo) {
                correo.required = true;
                if(!this.requiredFields.includes('correo')) this.requiredFields.push('correo');
            }
            if(roleInputs.length > 0) {
                roleInputs.forEach(r => r.required = true);
                if(!this.requiredFields.includes('rol')) this.requiredFields.push('rol');
            }
        } else {
            fields.classList.add('d-none');
            label.textContent = 'Mantener Actuales';
            if(correo) {
                correo.required = false;
                const index = this.requiredFields.indexOf('correo');
                if(index > -1) this.requiredFields.splice(index, 1);
            }
            if(roleInputs.length > 0) {
                roleInputs.forEach(r => r.required = false);
                const index = this.requiredFields.indexOf('rol');
                if(index > -1) this.requiredFields.splice(index, 1);
            }
        }
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return true;

        const wrapper = field.closest('.input-wrapper, .date-picker-wrapper, .phone-input-wrapper, .select-wrapper, .email-input-wrapper, .password-input-wrapper');
        if (!wrapper) return true;

        wrapper.classList.remove('error', 'valid');

        // Verificar si el campo es requerido
        const isRequired = field.hasAttribute('required') || 
                          (fieldId === 'correo' && document.getElementById('editar_credenciales')?.checked) ||
                          (fieldId === 'rol' && document.getElementById('editar_credenciales')?.checked);

        if (isRequired && !field.value.trim()) {
            wrapper.classList.add('error');
            return false;
        }

        // Validaciones específicas
        if (fieldId === 'correo' && field.value.trim()) {
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

        if (isRequired && field.value.trim()) {
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
            if(errorMessage) errorMessage.style.display = 'flex';
            return false;
        } else {
            genderGroup.classList.remove('error');
            if(errorMessage) errorMessage.style.display = 'none';
            return true;
        }
    }

    validateRole() {
        const checkbox = document.getElementById('editar_credenciales');
        if(checkbox && !checkbox.checked) return true;
        
        const selected = document.querySelector('input[name="rol"]:checked');
        const roleGroup = document.getElementById('roleGroup');
        const errorMessage = document.querySelector('.role-error-message');

        if (!selected) {
            roleGroup?.classList.add('error');
            if(errorMessage) errorMessage.style.display = 'flex';
            return false;
        } else {
            roleGroup?.classList.remove('error');
            if(errorMessage) errorMessage.style.display = 'none';
            return true;
        }
    }

    validateAllFields() {
        const errors = [];
        
        if (!this.validateField('Nombre')) errors.push('Nombre');
        if (!this.validateField('ApPaterno')) errors.push('ApPaterno');
        if (!this.validateField('Fecha_nacimiento')) errors.push('Fecha_nacimiento');
        if (!this.validateGender()) errors.push('Sexo');
        if (!this.validateField('Telefono')) errors.push('Telefono');
        if (!this.validateField('Cargo')) errors.push('Cargo');
        if (!this.validateField('Area_trabajo')) errors.push('Area_trabajo');
        
        @if($empleado->usuario)
        const checkbox = document.getElementById('editar_credenciales');
        if(checkbox && checkbox.checked) {
            if (!this.validateField('correo')) errors.push('correo');
            if (!this.validateRole()) errors.push('rol');
        }
        @endif

        if (errors.length > 0) {
            notifier.showValidationError(errors);
            
            const firstErrorId = errors[0] === 'Sexo' ? 'sexo_m' : 
                                errors[0] === 'rol' ? `rol_${document.querySelector('input[name="rol"]:checked')?.value || 'Administración'}` : 
                                errors[0];
            
            const firstError = document.getElementById(firstErrorId);
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                const wrapper = firstError.closest('.input-wrapper, .date-picker-wrapper, .phone-input-wrapper, .select-wrapper, .email-input-wrapper, .gender-selector-grid, .role-selector-grid');
                if (wrapper) {
                    wrapper.classList.add('shake-enhanced');
                    setTimeout(() => wrapper.classList.remove('shake-enhanced'), 800);
                }
            }
        }

        return errors.length === 0;
    }

    updateProgress() {
        let completedCount = 0;
        const totalFields = this.requiredFields.length;

        this.requiredFields.forEach(fieldId => {
            if (fieldId === 'Sexo') {
                if (document.querySelector('input[name="Sexo"]:checked')) completedCount++;
                return;
            }
            if (fieldId === 'rol') {
                const checkbox = document.getElementById('editar_credenciales');
                if(checkbox && !checkbox.checked) {
                    completedCount++; // No requerido si no se editan credenciales
                    return;
                }
                if (document.querySelector('input[name="rol"]:checked')) completedCount++;
                return;
            }
            if (fieldId === 'correo') {
                const checkbox = document.getElementById('editar_credenciales');
                if(checkbox && !checkbox.checked) {
                    completedCount++; // No requerido si no se editan credenciales
                    return;
                }
                const field = document.getElementById(fieldId);
                if (field && field.value.trim() !== '') completedCount++;
                return;
            }
            const field = document.getElementById(fieldId);
            if (field && field.value.trim() !== '') completedCount++;
        });

        const percentage = Math.round((completedCount / totalFields) * 100);
        
        document.getElementById('formProgress').style.width = `${percentage}%`;
        document.getElementById('progressPercentage').textContent = `${percentage}% Completado`;
        document.getElementById('completedFields').textContent = `${completedCount} de ${totalFields} campos completados`;
        document.getElementById('validFieldsCount').textContent = `${completedCount}/${totalFields}`;
    }

    initCharacterCounters() {
        const textFields = ['Nombre', 'ApPaterno', 'ApMaterno'];
        
        textFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', (e) => {
                    const length = e.target.value.length;
                    this.updateCharCounter(fieldId + 'Count', length);
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

    initDatePicker() {
        const dateField = document.getElementById('Fecha_nacimiento');
        if (dateField) {
            dateField.addEventListener('change', (e) => {
                this.updateDateDisplay(e.target.value);
                this.calculateAge(e.target.value);
                this.validateField('Fecha_nacimiento');
            });
            this.updateDateDisplay(dateField.value);
            this.calculateAge(dateField.value);
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
        if (!ageCalculator) return;

        if (!dateString) {
            ageCalculator.innerHTML = `
                <i class="fas fa-birthday-cake"></i>
                <span>Seleccione una fecha</span>
            `;
            return;
        }

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
                this.validateGender();
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
        @if($empleado->usuario)
        document.querySelectorAll('.role-option-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const role = card.dataset.role;
                this.selectRole(role);
                this.validateRole();
            });
        });
        @endif
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
        const emailField = document.getElementById('correo');
        if (emailField) {
            emailField.addEventListener('input', (e) => {
                this.validateField('correo');
            });
        }
    }

    validateAndSubmit() {
        if (this.validateAllFields()) {
            this.submitForm();
        }
    }

    submitForm() {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('empleadoForm');

        if (submitBtn && form) {
            notifier.showInfo('Procesando solicitud...', 'Un momento por favor');
            
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            setTimeout(() => {
                form.submit();
            }, 500);
        }
    }
}

// Hacer toggleCredencialesFields global
window.toggleCredencialesFields = function() {
    if (window.formManager) {
        window.formManager.toggleCredencialesFields();
    }
};

// Reset form
window.resetForm = function() {
    if (confirm('¿Está seguro de que desea restablecer todos los cambios a los valores originales?')) {
        location.reload();
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.formManager = new FormManager();
});
</script>
@endsection