@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="min-height: 100vh; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb" style="font-size: 0.9rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('personal.index') }}" class="text-decoration-none"><i class="fas fa-users me-1"></i>Empleados</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('personal.show', $empleado->id) }}" class="text-decoration-none"><i class="fas fa-user me-1"></i>{{ $empleado->Nombre }}</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page"><i class="fas fa-edit me-1"></i>Editar</li>
                </ol>
            </nav>

            <div class="card shadow-lg border-0" style="border-radius: 1rem; overflow: hidden;">
                <!-- Header Mejorado -->
                <div class="card-header text-white position-relative" style="
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    border-bottom: none;
                    padding: 1.5rem 2rem;
                ">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3" style="
                                background: rgba(255,255,255,0.2);
                                width: 50px;
                                height: 50px;
                                border-radius: 12px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <i class="fas fa-user-edit fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-1" style="font-weight: 700; font-size: 1.5rem;">Editar Empleado</h4>
                                <p class="mb-0 opacity-75" style="font-size: 0.9rem;">ID: {{ $empleado->id }} | Última actualización: {{ $empleado->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="status-badge">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-history me-1"></i>Editando
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4" style="font-size: 0.95rem;">
                    <form id="empleadoForm" action="{{ route('personal.update', $empleado->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Información Personal -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4 d-flex align-items-center">
                                <div class="section-icon me-3" style="
                                    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                ">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1" style="font-weight: 600; color: #2c3e50;">Información Personal</h5>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Actualice los datos básicos del empleado</p>
                                </div>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                            title="Ver información original" onclick="mostrarOriginales()">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Nombre -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('Nombre') is-invalid @enderror" 
                                               id="Nombre" name="Nombre" value="{{ old('Nombre', $empleado->Nombre) }}" 
                                               placeholder="Nombre" required maxlength="85">
                                        <label for="Nombre" class="required-field">Nombre <span class="text-danger">*</span></label>
                                        <div class="form-text d-flex justify-content-between">
                                            <span class="original-value" style="display:none;">Original: {{ $empleado->Nombre }}</span>
                                            <span id="nombreCount">{{ strlen(old('Nombre', $empleado->Nombre)) }}/85</span>
                                        </div>
                                        @error('Nombre') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Apellido Paterno -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('ApPaterno') is-invalid @enderror" 
                                               id="ApPaterno" name="ApPaterno" value="{{ old('ApPaterno', $empleado->ApPaterno) }}" 
                                               placeholder="Apellido Paterno" required maxlength="85">
                                        <label for="ApPaterno" class="required-field">Apellido Paterno <span class="text-danger">*</span></label>
                                        <div class="form-text d-flex justify-content-between">
                                            <span class="original-value" style="display:none;">Original: {{ $empleado->ApPaterno }}</span>
                                            <span id="apPaternoCount">{{ strlen(old('ApPaterno', $empleado->ApPaterno)) }}/85</span>
                                        </div>
                                        @error('ApPaterno') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Apellido Materno -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('ApMaterno') is-invalid @enderror" 
                                               id="ApMaterno" name="ApMaterno" value="{{ old('ApMaterno', $empleado->ApMaterno) }}" 
                                               placeholder="Apellido Materno" maxlength="85">
                                        <label for="ApMaterno">Apellido Materno</label>
                                        <div class="form-text d-flex justify-content-between">
                                            <span class="original-value" style="display:none;">Original: {{ $empleado->ApMaterno ?? 'No especificado' }}</span>
                                            <span id="apMaternoCount">{{ strlen(old('ApMaterno', $empleado->ApMaterno ?? '')) }}/85</span>
                                        </div>
                                        @error('ApMaterno') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Fecha de nacimiento -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="date" class="form-control @error('Fecha_nacimiento') is-invalid @enderror" 
                                               id="Fecha_nacimiento" name="Fecha_nacimiento" 
                                               value="{{ old('Fecha_nacimiento', $empleado->Fecha_nacimiento) }}" 
                                               min="1950-01-01" max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                                        <label for="Fecha_nacimiento" class="required-field">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                        <div class="form-text d-flex justify-content-between">
                                            <span class="original-value" style="display:none;">
                                                Original: {{ date('d/m/Y', strtotime($empleado->Fecha_nacimiento)) }}
                                            </span>
                                            <span><i class="fas fa-info-circle me-1"></i> Mayor de 18 años</span>
                                        </div>
                                        @error('Fecha_nacimiento') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Sexo -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select @error('Sexo') is-invalid @enderror" id="Sexo" name="Sexo" required>
                                            <option value="" disabled {{ old('Sexo', $empleado->Sexo) ? '' : 'selected' }}>Seleccionar</option>
                                            <option value="M" {{ old('Sexo', $empleado->Sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F" {{ old('Sexo', $empleado->Sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                            <option value="Otro" {{ old('Sexo', $empleado->Sexo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        <label for="Sexo" class="required-field">Sexo <span class="text-danger">*</span></label>
                                        <div class="form-text">
                                            <span class="original-value" style="display:none;">
                                                Original: {{ $empleado->Sexo == 'M' ? 'Masculino' : ($empleado->Sexo == 'F' ? 'Femenino' : 'Otro') }}
                                            </span>
                                        </div>
                                        @error('Sexo') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('Telefono') is-invalid @enderror" 
                                               id="Telefono" name="Telefono" value="{{ old('Telefono', $empleado->Telefono) }}" 
                                               placeholder="7771234567" required pattern="[0-9]{10}">
                                        <label for="Telefono" class="required-field">Teléfono <span class="text-danger">*</span></label>
                                        <div class="form-text d-flex justify-content-between">
                                            <span class="original-value" style="display:none;">Original: {{ $empleado->Telefono }}</span>
                                            <span><i class="fas fa-mobile-alt me-1"></i> 10 dígitos</span>
                                        </div>
                                        @error('Telefono') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Laboral -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4 d-flex align-items-center">
                                <div class="section-icon me-3" style="
                                    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                ">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1" style="font-weight: 600; color: #2c3e50;">Información Laboral</h5>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Actualice el puesto y área de trabajo</p>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Cargo -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('Cargo') is-invalid @enderror" id="Cargo" name="Cargo" required>
                                            <option value="" disabled {{ old('Cargo', $empleado->Cargo) ? '' : 'selected' }}>Seleccionar cargo</option>
                                            @foreach($cargos ?? ['Administrador','Gerente','Encargado de almacén','Supervisor','Auxiliar'] as $cargo)
                                                <option value="{{ $cargo }}" {{ old('Cargo', $empleado->Cargo) == $cargo ? 'selected' : '' }}>
                                                    {{ $cargo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="Cargo" class="required-field">Cargo <span class="text-danger">*</span></label>
                                        <div class="form-text">
                                            <span class="original-value" style="display:none;">Original: {{ $empleado->Cargo }}</span>
                                        </div>
                                        @error('Cargo') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Área de trabajo -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('Area_trabajo') is-invalid @enderror" id="Area_trabajo" name="Area_trabajo" required>
                                            <option value="" disabled {{ old('Area_trabajo', $empleado->Area_trabajo) ? '' : 'selected' }}>Seleccionar área</option>
                                            @foreach($areas ?? ['Almacén','Oficina','Logística','Recursos Humanos','Ventas','Producción'] as $area)
                                                <option value="{{ $area }}" {{ old('Area_trabajo', $empleado->Area_trabajo) == $area ? 'selected' : '' }}>
                                                    {{ $area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="Area_trabajo" class="required-field">Área de Trabajo <span class="text-danger">*</span></label>
                                        <div class="form-text">
                                            <span class="original-value" style="display:none;">Original: {{ $empleado->Area_trabajo }}</span>
                                        </div>
                                        @error('Area_trabajo') 
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gestión de Usuario - SOLO CORREO Y ROL -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4 d-flex align-items-center">
                                <div class="section-icon me-3" style="
                                    background: linear-gradient(135deg, #42e695 0%, #3bb2b8 100%);
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                ">
                                    <i class="fas fa-user-lock"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <h5 class="mb-1" style="font-weight: 600; color: #2c3e50;">Credenciales de Usuario</h5>
                                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Modifique el correo y rol del usuario asociado</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               id="editar_credenciales" name="editar_credenciales" value="1" 
                                               {{ $empleado->usuario ? (old('editar_credenciales') ? 'checked' : '') : '' }} 
                                               style="width: 3em; height: 1.5em;" onchange="toggleCredencialesFields()">
                                        <label class="form-check-label fw-medium ms-2" for="editar_credenciales">
                                            {{ $empleado->usuario ? (old('editar_credenciales') ? 'Editar Credenciales' : 'Mantener Actuales') : 'Sin Usuario' }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @if($empleado->usuario)
                            <!-- Información de usuario existente -->
                            <div class="alert alert-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-check fa-lg me-3"></i>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Usuario actual asignado</h6>
                                            <span class="badge bg-success">Activo</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Correo actual:</small>
                                                <p class="mb-1 fw-medium">{{ $empleado->usuario->correo }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Rol actual:</small>
                                                <p class="mb-1 fw-medium">
                                                    {{ $empleado->usuario->rol ?? 'Sin rol asignado' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos para editar credenciales -->
                            <div id="credenciales_fields" class="{{ old('editar_credenciales') ? '' : 'd-none' }}">
                                <div class="row g-4">
                                    <!-- Correo -->
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                                                   id="correo" name="correo" 
                                                   value="{{ old('correo', $empleado->usuario->correo) }}" 
                                                   placeholder="nuevo@correo.com" required>
                                            <label for="correo" class="required-field">Nuevo Correo <span class="text-danger">*</span></label>
                                            <div class="form-text">
                                                <i class="fas fa-envelope me-1"></i> Será el nuevo correo para acceder al sistema
                                            </div>
                                            @error('correo') 
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Rol -->
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select @error('rol') is-invalid @enderror" 
                                                    id="rol" name="rol" required>
                                                <option value="" disabled {{ old('rol', $empleado->usuario->rol) ? '' : 'selected' }}>Seleccionar rol</option>
                                                @foreach($roles as $rol)
                                                    <option value="{{ $rol }}" 
                                                        {{ old('rol', $empleado->usuario->rol) == $rol ? 'selected' : '' }}>
                                                        {{ $rol }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="rol" class="required-field">Nuevo Rol <span class="text-danger">*</span></label>
                                            <div class="form-text">
                                                <i class="fas fa-user-tag me-1"></i> Define los permisos en el sistema
                                            </div>
                                            @error('rol') 
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Información sobre contraseña -->
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            <div class="d-flex">
                                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1">Contraseña</h6>
                                                    <p class="mb-0 small">La contraseña no se puede modificar desde esta sección. 
                                                        Si el usuario necesita cambiar su contraseña, debe hacerlo desde la opción 
                                                        "Olvidé mi contraseña" en el inicio de sesión o contactar al administrador.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Si no tiene usuario -->
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-times fa-lg me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Sin usuario asignado</h6>
                                        <p class="mb-0">Este empleado no tiene credenciales de acceso al sistema.</p>
                                        <small>Para crear un usuario, utilice la opción "Crear Usuario" desde la vista de detalles del empleado.</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                            <div>
                                <span class="text-muted" style="font-size: 0.85rem;">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Campos marcados con <span class="text-danger">*</span> son obligatorios
                                </span>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('personal.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-times me-2"></i> Cancelar
                                </a>
                                <button type="reset" class="btn btn-light btn-lg px-4">
                                    <i class="fas fa-redo me-2"></i> Restablecer
                                </button>
                                <button type="button" class="btn btn-primary btn-lg px-5" style="
                                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                                    border: none;
                                    font-weight: 600;
                                    transition: transform 0.2s;
                                " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'"
                                onclick="showConfirmation()">
                                    <i class="fas fa-save me-2"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Cambios</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="icon-circle mb-3" style="
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        <i class="fas fa-user-edit fa-2x text-white"></i>
                    </div>
                    <h5 class="mb-2">¿Actualizar información del empleado?</h5>
                    <p class="text-muted">Se actualizarán los datos de <strong>{{ $empleado->Nombre }} {{ $empleado->ApPaterno }}</strong></p>
                    
                    <!-- Resumen de cambios en credenciales -->
                    @if($empleado->usuario && old('editar_credenciales'))
                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-lock me-2"></i>
                            <div>
                                <h6 class="mb-1">Cambios en credenciales:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Correo actual:</small>
                                        <p class="mb-1"><s>{{ $empleado->usuario->correo }}</s></p>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Correo nuevo:</small>
                                        <p class="mb-1 fw-bold">{{ old('correo', $empleado->usuario->correo) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Atención</h6>
                            <p class="mb-0 small">Esta acción no se puede deshacer. Todos los cambios serán registrados en el sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="submitForm()" style="
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    border: none;
                ">
                    <i class="fas fa-check me-1"></i> Confirmar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Valores Originales -->
<div class="modal fade" id="originalValuesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-history me-2"></i>Valores Originales</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Campo</th>
                                <th>Valor Original</th>
                                <th>Valor Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nombre</td>
                                <td><span class="text-muted">{{ $empleado->Nombre }}</span></td>
                                <td id="currentNombre">{{ old('Nombre', $empleado->Nombre) }}</td>
                            </tr>
                            <tr>
                                <td>Apellido Paterno</td>
                                <td><span class="text-muted">{{ $empleado->ApPaterno }}</span></td>
                                <td id="currentApPaterno">{{ old('ApPaterno', $empleado->ApPaterno) }}</td>
                            </tr>
                            <tr>
                                <td>Apellido Materno</td>
                                <td><span class="text-muted">{{ $empleado->ApMaterno ?? 'No especificado' }}</span></td>
                                <td id="currentApMaterno">{{ old('ApMaterno', $empleado->ApMaterno ?? '') }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Nacimiento</td>
                                <td><span class="text-muted">{{ date('d/m/Y', strtotime($empleado->Fecha_nacimiento)) }}</span></td>
                                <td id="currentFechaNac">{{ old('Fecha_nacimiento', $empleado->Fecha_nacimiento) }}</td>
                            </tr>
                            <tr>
                                <td>Sexo</td>
                                <td><span class="text-muted">{{ $empleado->Sexo == 'M' ? 'Masculino' : ($empleado->Sexo == 'F' ? 'Femenino' : 'Otro') }}</span></td>
                                <td id="currentSexo">{{ old('Sexo', $empleado->Sexo) == 'M' ? 'Masculino' : (old('Sexo', $empleado->Sexo) == 'F' ? 'Femenino' : 'Otro') }}</td>
                            </tr>
                            <tr>
                                <td>Teléfono</td>
                                <td><span class="text-muted">{{ $empleado->Telefono }}</span></td>
                                <td id="currentTelefono">{{ old('Telefono', $empleado->Telefono) }}</td>
                            </tr>
                            <tr>
                                <td>Cargo</td>
                                <td><span class="text-muted">{{ $empleado->Cargo }}</span></td>
                                <td id="currentCargo">{{ old('Cargo', $empleado->Cargo) }}</td>
                            </tr>
                            <tr>
                                <td>Área de Trabajo</td>
                                <td><span class="text-muted">{{ $empleado->Area_trabajo }}</span></td>
                                <td id="currentAreaTrabajo">{{ old('Area_trabajo', $empleado->Area_trabajo) }}</td>
                            </tr>
                            @if($empleado->usuario)
                            <tr>
                                <td>Correo de Usuario</td>
                                <td><span class="text-muted">{{ $empleado->usuario->correo }}</span></td>
                                <td id="currentCorreo">{{ old('correo', $empleado->usuario->correo) }}</td>
                            </tr>
                            <tr>
                                <td>Rol de Usuario</td>
                                <td><span class="text-muted">{{ $empleado->usuario->rol ?? 'Sin rol' }}</span></td>
                                <td id="currentRol">{{ old('rol', $empleado->usuario->rol ?? '') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.form-section {
    background: white;
    border-radius: 12px;
    padding: 1.75rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 4px solid transparent;
    transition: transform 0.2s, box-shadow 0.2s;
}

.form-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.required-field::after {
    content: ' *';
    color: #dc3545;
}

.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown),
.form-floating > .form-select {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}

.form-floating > label {
    padding: 0.5rem 0.75rem;
}

.original-value {
    font-size: 0.8rem;
    color: #6c757d;
    font-style: italic;
}

/* Animaciones */
@keyframes highlightChange {
    0% { background-color: #fff3cd; }
    100% { background-color: transparent; }
}

.changed-field {
    animation: highlightChange 2s ease-out;
}

/* Badges para estados de usuario */
.usuario-status-badge {
    font-size: 0.75em;
    padding: 0.25em 0.6em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Contadores de caracteres
    const textFields = ['Nombre', 'ApPaterno', 'ApMaterno'];
    textFields.forEach(id => {
        const field = document.getElementById(id);
        const countElement = document.getElementById(id + 'Count');
        
        if(field && countElement) {
            countElement.textContent = field.value.length + '/85';
            field.addEventListener('input', function() {
                countElement.textContent = this.value.length + '/85';
                highlightChange(this);
            });
        }
    });

    // Formatear teléfono
    const telefonoField = document.getElementById('Telefono');
    if(telefonoField) {
        telefonoField.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
            highlightChange(this);
        });
    }

    // Validación del formulario
    const form = document.getElementById('empleadoForm');
    if(form) {
        form.addEventListener('submit', function(e) {
            if(!validateCredencialesFields()) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            if(!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Scroll al primer campo con error
                const firstInvalid = this.querySelector('.is-invalid');
                if(firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            }
            
            form.classList.add('was-validated');
        });
    }

    // Añadir event listeners para detectar cambios en credenciales
    const correoField = document.getElementById('correo');
    const rolField = document.getElementById('rol');
    
    if(correoField) {
        correoField.addEventListener('input', function() {
            if(this.value !== '{{ $empleado->usuario->correo ?? "" }}') {
                highlightChange(this);
            }
        });
    }
    
    if(rolField) {
        rolField.addEventListener('change', function() {
            highlightChange(this);
        });
    }
});

function toggleCredencialesFields() {
    const editarCredenciales = document.getElementById('editar_credenciales');
    const credencialesFields = document.getElementById('credenciales_fields');
    const label = editarCredenciales.nextElementSibling;
    
    if(editarCredenciales.checked) {
        credencialesFields.classList.remove('d-none');
        label.textContent = 'Editar Credenciales';
        
        // Hacer campos obligatorios
        const correo = document.getElementById('correo');
        const rol = document.getElementById('rol');
        if(correo) correo.required = true;
        if(rol) rol.required = true;
    } else {
        credencialesFields.classList.add('d-none');
        label.textContent = 'Mantener Actuales';
        
        // Quitar obligatoriedad
        const correo = document.getElementById('correo');
        const rol = document.getElementById('rol');
        if(correo) correo.required = false;
        if(rol) rol.required = false;
    }
}

function validateCredencialesFields() {
    const editarCredenciales = document.getElementById('editar_credenciales');
    
    if(!editarCredenciales || !editarCredenciales.checked) {
        return true; // No se editan credenciales, validación OK
    }
    
    const correo = document.getElementById('correo');
    const rol = document.getElementById('rol');
    
    // Validar correo
    if(correo && !correo.value.trim()) {
        correo.classList.add('is-invalid');
        correo.focus();
        return false;
    }
    
    // Validar rol
    if(rol && !rol.value) {
        rol.classList.add('is-invalid');
        rol.focus();
        return false;
    }
    
    return true;
}

function mostrarOriginales() {
    // Actualizar valores actuales en el modal
    document.getElementById('currentNombre').textContent = document.getElementById('Nombre').value;
    document.getElementById('currentApPaterno').textContent = document.getElementById('ApPaterno').value;
    document.getElementById('currentApMaterno').textContent = document.getElementById('ApMaterno').value;
    document.getElementById('currentFechaNac').textContent = document.getElementById('Fecha_nacimiento').value;
    document.getElementById('currentTelefono').textContent = document.getElementById('Telefono').value;
    
    const sexoSelect = document.getElementById('Sexo');
    const sexoText = sexoSelect.options[sexoSelect.selectedIndex].text;
    document.getElementById('currentSexo').textContent = sexoText;
    
    const cargoSelect = document.getElementById('Cargo');
    const cargoText = cargoSelect.options[cargoSelect.selectedIndex].text;
    document.getElementById('currentCargo').textContent = cargoText;
    
    const areaSelect = document.getElementById('Area_trabajo');
    const areaText = areaSelect.options[areaSelect.selectedIndex].text;
    document.getElementById('currentAreaTrabajo').textContent = areaText;
    
    // Actualizar credenciales si existen
    const correoField = document.getElementById('correo');
    const rolField = document.getElementById('rol');
    
    if(correoField) {
        document.getElementById('currentCorreo').textContent = correoField.value || '{{ $empleado->usuario->correo ?? "" }}';
    }
    
    if(rolField) {
        const rolText = rolField.options[rolField.selectedIndex].text;
        document.getElementById('currentRol').textContent = rolText || '{{ $empleado->usuario->rol ?? "" }}';
    }
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('originalValuesModal'));
    modal.show();
}

function highlightChange(element) {
    element.classList.add('changed-field');
    setTimeout(() => {
        element.classList.remove('changed-field');
    }, 2000);
}

function showConfirmation() {
    // Validar campos antes de mostrar el modal
    if(!validateCredencialesFields()) {
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    modal.show();
}

function submitForm() {
    document.getElementById('empleadoForm').submit();
}
</script>
@endsection