@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">
                <i class="fas fa-edit me-2"></i>Editar Respaldo #{{ $respaldo->id }}
            </h1>
            <p class="text-muted mb-0">Actualiza la información del respaldo de seguridad</p>
        </div>
        <div>
            <a href="{{ route('respaldos.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <!-- Panel principal -->
    <div class="row">
        <!-- Formulario principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Editar Información del Respaldo
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('respaldos.update', $respaldo->id) }}" method="POST" id="editarRespaldoForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Nombre del Respaldo -->
                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    <i class="fas fa-tag me-1"></i> Nombre del Respaldo *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('Nombre') is-invalid @enderror" 
                                           id="Nombre" 
                                           name="Nombre" 
                                           value="{{ old('Nombre', $respaldo->Nombre) }}"
                                           required 
                                           maxlength="80"
                                           placeholder="Ej: Respaldo Mensual Enero 2024">
                                </div>
                                @error('Nombre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block">
                                    Identificador único para este respaldo
                                </small>
                            </div>

                            <!-- Ruta del Archivo (solo lectura) -->
                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    <i class="fas fa-file-archive me-1"></i> Ruta del Archivo
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-file-code"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="{{ $respaldo->Ruta }}" 
                                           readonly>
                                    <span class="input-group-text">
                                        <i class="fas fa-lock text-secondary"></i>
                                    </span>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1 text-info"></i> La ruta del archivo no se puede modificar
                                </small>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label class="form-label small text-muted">
                                    <i class="fas fa-file-alt me-1"></i> Descripción
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-align-left"></i>
                                    </span>
                                    <textarea class="form-control @error('Descripcion') is-invalid @enderror" 
                                              id="Descripcion" 
                                              name="Descripcion" 
                                              rows="3" 
                                              maxlength="280"
                                              placeholder="Describe el propósito de este respaldo...">{{ old('Descripcion', $respaldo->Descripcion) }}</textarea>
                                </div>
                                @error('Descripcion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Máximo 280 caracteres</small>
                                    <small class="text-muted" id="contadorCaracteres">0/280</small>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Sistema (solo lectura) -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    Información del Sistema
                                </h6>
                                
                                <div class="row g-3">
                                    <!-- Fecha de Creación -->
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">
                                            <i class="fas fa-calendar me-1"></i> Fecha de Creación
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control bg-light" 
                                                   value="{{ \Carbon\Carbon::parse($respaldo->Fecha)->setTimezone(config('app.timezone', 'America/Lima'))->format('d/m/Y h:i A') }}" 
                                                   readonly>
                                        </div>
                                    </div>

                                    <!-- Usuario -->
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">
                                            <i class="fas fa-user me-1"></i> Usuario
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user-check"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control bg-light" 
                                                   @if($respaldo->usuario)
                                                   value="{{ $respaldo->usuario->correo ?? $respaldo->usuario->Nombre ?? 'Usuario #' . $respaldo->Usuario }}"
                                                   @else
                                                   value="Usuario #{{ $respaldo->Usuario }}"
                                                   @endif
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25 mt-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                                        <div>
                                            <small class="fw-semibold d-block">Información del respaldo:</small>
                                            <small>• Solo se puede modificar el nombre y la descripción del respaldo</small><br>
                                            <small>• El archivo original se mantiene intacto</small><br>
                                            <small>• Ubicación: <code>{{ $respaldo->Ruta }}</code></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Solo los campos editables se actualizarán
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary px-4" id="btnActualizarRespaldo">
                                    <i class="fas fa-save me-2"></i> Actualizar Respaldo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel lateral - Información -->
        <div class="col-lg-4">
            <!-- Detalles del Respaldo -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-database me-2 text-primary"></i>
                        Detalles del Respaldo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Base de Datos</label>
                        <div class="d-flex align-items-center bg-light p-3 rounded">
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-database text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ config('database.connections.mysql.database') }}</div>
                                <small class="text-muted">MySQL</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="card border h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-calendar-alt text-info"></i>
                                    </div>
                                    <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($respaldo->Fecha)->format('d/m/Y') }}</div>
                                    <small class="text-muted">Fecha</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-hashtag text-success"></i>
                                    </div>
                                    <div class="fw-bold fs-5">#{{ $respaldo->id }}</div>
                                    <small class="text-muted">ID</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado Actual -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Estado Actual</label>
                        <div class="d-flex align-items-center bg-light p-3 rounded">
                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Completado</div>
                                <small class="text-muted">Respaldo disponible</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-success bg-success bg-opacity-10 border border-success border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <div class="flex-grow-1">
                                <small class="fw-semibold d-block">Respaldo disponible:</small>
                                <small>El archivo está listo para restaurar cuando sea necesario</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Historial de Cambios -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Historial
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Creado por</label>
                        <div class="d-flex align-items-center bg-light p-3 rounded">
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-user text-warning"></i>
                            </div>
                            <div>
                                @if($respaldo->usuario)
                                <div class="fw-semibold">{{ $respaldo->usuario->correo ?? $respaldo->usuario->Nombre ?? 'Usuario #' . $respaldo->Usuario }}</div>
                                @else
                                <div class="fw-semibold">Usuario #{{ $respaldo->Usuario }}</div>
                                @endif
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($respaldo->Fecha)->setTimezone(config('app.timezone', 'America/Lima'))->isoFormat('DD/MM/YYYY hh:mm A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    @if($respaldo->updated_at && $respaldo->updated_at != $respaldo->Fecha)
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Última modificación</label>
                        <div class="d-flex align-items-center bg-light p-3 rounded">
                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-edit text-info"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Actualizado</div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($respaldo->updated_at)->setTimezone(config('app.timezone', 'America/Lima'))->isoFormat('DD/MM/YYYY hh:mm A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="alert alert-warning bg-warning bg-opacity-10 border border-warning border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            <div class="flex-grow-1">
                                <small class="fw-semibold d-block">Importante:</small>
                                <small>Los cambios solo afectan la información del respaldo, no el archivo físico</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editarRespaldoForm');
    const btnActualizar = document.getElementById('btnActualizarRespaldo');
    const contadorCaracteres = document.getElementById('contadorCaracteres');
    const textareaDescripcion = document.getElementById('Descripcion');

    // Inicializar contador de caracteres
    if (textareaDescripcion && contadorCaracteres) {
        const longitudInicial = textareaDescripcion.value.length;
        contadorCaracteres.textContent = `${longitudInicial}/280`;
        
        if (longitudInicial > 250) {
            contadorCaracteres.classList.remove('text-muted');
            contadorCaracteres.classList.add('text-warning');
        } else if (longitudInicial > 270) {
            contadorCaracteres.classList.remove('text-warning');
            contadorCaracteres.classList.add('text-danger');
        }
    }

    // Contador de caracteres para descripción
    if (textareaDescripcion) {
        textareaDescripcion.addEventListener('input', function() {
            const longitud = this.value.length;
            if (contadorCaracteres) {
                contadorCaracteres.textContent = `${longitud}/280`;
                
                if (longitud > 250) {
                    contadorCaracteres.classList.remove('text-muted');
                    contadorCaracteres.classList.add('text-warning');
                } else if (longitud > 270) {
                    contadorCaracteres.classList.remove('text-warning');
                    contadorCaracteres.classList.add('text-danger');
                } else {
                    contadorCaracteres.classList.remove('text-warning', 'text-danger');
                    contadorCaracteres.classList.add('text-muted');
                }
            }
        });
    }

    // Función para validar formulario
    function validarFormulario() {
        let isValid = true;
        const errores = [];
        
        const nombre = document.getElementById('Nombre')?.value.trim();
        const descripcion = document.getElementById('Descripcion')?.value.trim();
        
        // Validar nombre
        if (!nombre) {
            isValid = false;
            errores.push('Debe ingresar un nombre para el respaldo');
            document.getElementById('Nombre').classList.add('is-invalid');
        } else if (nombre.length > 80) {
            isValid = false;
            errores.push('El nombre no puede exceder los 80 caracteres');
            document.getElementById('Nombre').classList.add('is-invalid');
        } else {
            document.getElementById('Nombre').classList.remove('is-invalid');
        }
        
        // Validar descripción
        if (descripcion && descripcion.length > 280) {
            isValid = false;
            errores.push('La descripción no puede exceder los 280 caracteres');
            document.getElementById('Descripcion').classList.add('is-invalid');
        } else {
            document.getElementById('Descripcion').classList.remove('is-invalid');
        }
        
        return { isValid, errores };
    }

    // Función para mostrar error
    function mostrarError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error en el formulario',
            html: `
                <div class="text-start">
                    <p class="mb-3">Por favor, corrija los siguientes errores:</p>
                    <div class="alert alert-danger bg-danger bg-opacity-10 border border-danger border-opacity-25 text-start">
                        ${Array.isArray(mensaje) ? mensaje.map(e => `<div class="mb-1"><i class="fas fa-times-circle me-1"></i>${e}</div>`).join('') : mensaje}
                    </div>
                </div>
            `,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545',
            customClass: {
                popup: 'shadow-lg'
            }
        });
    }

    // Función para mostrar confirmación de actualización
    function mostrarConfirmacionActualizacion(datos) {
        Swal.fire({
            title: '¿Actualizar Respaldo?',
            html: `
                <div class="text-start">
                    <div class="mb-4">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-edit fa-lg text-primary"></i>
                        </div>
                        <p class="mb-3">¿Está seguro de actualizar la información del respaldo?</p>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <small class="text-muted d-block">Nuevo nombre</small>
                                <strong>${datos.nombre}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <small class="text-muted d-block">ID</small>
                                <strong>#${datos.id}</strong>
                            </div>
                        </div>
                    </div>
                    
                    ${datos.descripcion ? `
                    <div class="mb-3">
                        <small class="text-muted d-block">Nueva descripción</small>
                        <div class="bg-light p-2 rounded">${datos.descripcion.substring(0, 80)}${datos.descripcion.length > 80 ? '...' : ''}</div>
                    </div>
                    ` : `
                    <div class="mb-3">
                        <small class="text-muted d-block">Descripción</small>
                        <div class="bg-light p-2 rounded text-muted"><em>Sin descripción</em></div>
                    </div>
                    `}
                    
                    <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            <div>
                                <small>Archivo original preservado:</small>
                                <div><code class="small">${datos.ruta}</code></div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-2"></i> Sí, Actualizar',
            cancelButtonText: '<i class="fas fa-times me-2"></i> Cancelar',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            customClass: {
                popup: 'shadow-lg'
            },
            width: '500px'
        }).then((result) => {
            if (result.isConfirmed) {
                // Deshabilitar botón temporalmente
                btnActualizar.disabled = true;
                btnActualizar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...';
                
                // Enviar formulario
                form.submit();
            }
        });
    }

    // Evento para botón Actualizar Respaldo
    btnActualizar.addEventListener('click', function(e) {
        e.preventDefault();
        
        const validacion = validarFormulario();
        
        if (!validacion.isValid) {
            mostrarError(validacion.errores);
            return;
        }
        
        // Obtener datos del formulario
        const nombre = document.getElementById('Nombre').value.trim();
        const descripcion = document.getElementById('Descripcion').value.trim();
        const ruta = "{{ $respaldo->Ruta }}";
        const id = "{{ $respaldo->id }}";
        
        const datosRespaldo = {
            nombre: nombre,
            descripcion: descripcion,
            ruta: ruta,
            id: id
        };
        
        mostrarConfirmacionActualizacion(datosRespaldo);
    });

    // Limpiar validaciones al interactuar
    const nombreInput = document.getElementById('Nombre');
    if (nombreInput) {
        nombreInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }

    if (textareaDescripcion) {
        textareaDescripcion.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }
});
</script>
@endpush

<style>
/* Estilos consistentes con el sistema */
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

.avatar-lg {
    width: 60px;
    height: 60px;
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

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-control:read-only {
    background-color: #f8f9fa;
    cursor: not-allowed;
    border-color: #dee2e6;
}

.form-control:read-only:focus {
    border-color: #dee2e6;
    box-shadow: none;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
}

/* Estilos para badges con opacidad */
.bg-primary.bg-opacity-10 {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-success.bg-opacity-10 {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info.bg-opacity-10 {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-danger.bg-opacity-10 {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

/* Estilos para bordes con opacidad */
.border-primary.border-opacity-25 {
    border-color: rgba(13, 110, 253, 0.25) !important;
}

.border-success.border-opacity-25 {
    border-color: rgba(25, 135, 84, 0.25) !important;
}

.border-warning.border-opacity-25 {
    border-color: rgba(255, 193, 7, 0.25) !important;
}

.border-info.border-opacity-25 {
    border-color: rgba(13, 202, 240, 0.25) !important;
}

.border-danger.border-opacity-25 {
    border-color: rgba(220, 53, 69, 0.25) !important;
}

/* Responsive */
@media (max-width: 992px) {
    .btn {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .avatar, .avatar-md, .avatar-sm, .avatar-lg {
        width: 32px;
        height: 32px;
    }
    
    .avatar-lg {
        width: 48px;
        height: 48px;
    }
}

/* Animaciones */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.btn-primary:not(:disabled):hover {
    animation: pulse 0.3s ease;
}

/* Estilos para SweetAlert */
.swal2-popup {
    border-radius: 12px !important;
}

/* Mejoras visuales */
.bg-light {
    background-color: #f8f9fa !important;
}

.alert {
    border-radius: 8px;
}

/* Estilos para el estado */
.text-capitalize {
    text-transform: capitalize;
}

/* Spinner para botón */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection