@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">
                <i class="fas fa-database me-2"></i>Generar Nuevo Respaldo
            </h1>
            <p class="text-muted mb-0">Crea un respaldo de seguridad de toda la base de datos del sistema</p>
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
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Información del Respaldo
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('respaldos.store') }}" method="POST" id="respaldoForm">
                        @csrf
                        
                        <!-- Campo oculto con el ID del usuario logueado -->
                        <input type="hidden" name="Usuario" value="{{ auth()->id() }}">
                        
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
                                           value="{{ old('Nombre', 'Respaldo ' . now()->format('d-m-Y H:i')) }}"
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

                            <!-- Usuario Responsable (campo de solo lectura) -->
                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    <i class="fas fa-user me-1"></i> Usuario Responsable
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-check"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           id="usuarioDisplay"
                                           value="{{ auth()->user()->correo ?? auth()->user()->email ?? auth()->user()->name ?? 'Usuario' }}" 
                                           readonly>
                                    <span class="input-group-text">
                                        <i class="fas fa-check text-success"></i>
                                    </span>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1 text-info"></i> Se registrará automáticamente como usuario responsable
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
                                              placeholder="Describe el propósito de este respaldo...">{{ old('Descripcion') }}</textarea>
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

                        <!-- Detalles del Respaldo -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-cogs me-2 text-primary"></i>
                                    Detalles del Respaldo
                                </h6>
                                
                                <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                                        <div>
                                            <small class="fw-semibold d-block">Información del proceso:</small>
                                            <small>• Se generará un archivo SQL con toda la estructura y datos</small><br>
                                            <small>• El respaldo incluirá todas las tablas del sistema</small><br>
                                            <small>• Archivo: <code>backup_{{ config('database.connections.mysql.database') }}_fecha.sql</code></small><br>
                                            <small>• Ubicación: <code>storage/app/backups/</code></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Todos los campos marcados con * son obligatorios
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary px-4" id="btnGenerarRespaldo">
                                    <i class="fas fa-database me-2"></i> Generar Respaldo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel lateral - Información -->
        <div class="col-lg-4">
            <!-- Información del Sistema -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-server me-2 text-primary"></i>
                        Información del Sistema
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
                                        <i class="fas fa-table text-info"></i>
                                    </div>
                                    <div class="fw-bold fs-5">{{ $estadisticas['total_tablas'] ?? '0' }}</div>
                                    <small class="text-muted">Tablas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border h-100">
                                <div class="card-body text-center p-3">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-weight-hanging text-success"></i>
                                    </div>
                                    <div class="fw-bold fs-5">{{ $estadisticas['tamaño_total'] ?? '0 MB' }}</div>
                                    <small class="text-muted">Tamaño</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Fecha y Hora Actual</label>
                        <div class="d-flex align-items-center bg-light p-3 rounded">
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-calendar-alt text-warning"></i>
                            </div>
                            <div>
                                @php
                                    // Usar la fecha/hora actual del servidor en la zona horaria correcta
                                    $fechaHora = now(config('app.timezone', 'America/Lima'));
                                @endphp
                                <div class="fw-semibold">{{ $fechaHora->isoFormat('DD/MM/YYYY') }}</div>
                                <small class="text-muted">{{ $fechaHora->isoFormat('hh:mm A') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-success bg-success bg-opacity-10 border border-success border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <div class="flex-grow-1">
                                <small class="fw-semibold d-block">Estado:</small>
                                <small>Sistema listo para generar respaldo</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Último Respaldo -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Historial de Respaldos
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($estadisticas['ultimo_respaldo_fecha']) && $estadisticas['ultimo_respaldo_fecha'])
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Último respaldo</label>
                        <div class="d-flex align-items-center bg-light p-3 rounded">
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-history text-warning"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $estadisticas['ultimo_respaldo'] ?? 'Nunca' }}</div>
                                @if($estadisticas['ultimo_respaldo_fecha'])
                                @php
                                    $fechaUltimoRespaldo = \Carbon\Carbon::parse($estadisticas['ultimo_respaldo_fecha'])->timezone(config('app.timezone', 'America/Lima'));
                                @endphp
                                <small class="text-muted">
                                    {{ $fechaUltimoRespaldo->isoFormat('DD/MM/YYYY hh:mm A') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <div class="avatar-md bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                            <i class="fas fa-database text-secondary"></i>
                        </div>
                        <h6 class="text-muted fw-semibold">Sin respaldos previos</h6>
                        <small class="text-muted">Este será el primer respaldo del sistema</small>
                    </div>
                    @endif
                    
                    <div class="alert alert-warning bg-warning bg-opacity-10 border border-warning border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            <div class="flex-grow-1">
                                <small class="fw-semibold d-block">Recomendación:</small>
                                <small>Realice respaldos periódicos para mayor seguridad</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Progreso -->
<div class="modal fade" id="progresoModal" tabindex="-1" aria-labelledby="progresoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white position-relative">
                <h5 class="modal-title fw-bold" id="progresoModalLabel">
                    <i class="fas fa-sync-alt me-2"></i>
                    Generando Respaldo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-database fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Procesando base de datos</h5>
                    <p class="text-muted mb-4" id="estadoProceso">Preparando respaldo...</p>
                </div>
                
                <!-- Barra de progreso -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Progreso</small>
                        <small class="fw-bold" id="porcentajeProgreso">0%</small>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" 
                             style="width: 0%" 
                             id="barraProgreso">
                        </div>
                    </div>
                </div>
                
                <!-- Detalles del proceso -->
                <div class="bg-light p-3 rounded text-start mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-database text-primary me-2"></i>
                        <div class="flex-grow-1">
                            <small class="fw-semibold">Base de datos:</small>
                            <div><code>{{ config('database.connections.mysql.database') }}</code></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <div class="flex-grow-1">
                            <small class="fw-semibold">Inicio:</small>
                            <div id="horaInicio">
                                @php
                                    echo now(config('app.timezone', 'America/Lima'))->isoFormat('HH:mm:ss');
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning bg-warning bg-opacity-10 border border-warning border-opacity-25 text-start">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                        <div>
                            <small class="fw-semibold d-block">¡No cierre esta ventana!</small>
                            <small>El proceso puede tomar varios minutos dependiendo del tamaño de la base de datos.</small>
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
    const form = document.getElementById('respaldoForm');
    const btnGenerar = document.getElementById('btnGenerarRespaldo');
    const progresoModal = new bootstrap.Modal(document.getElementById('progresoModal'));
    const barraProgreso = document.getElementById('barraProgreso');
    const porcentajeProgreso = document.getElementById('porcentajeProgreso');
    const estadoProceso = document.getElementById('estadoProceso');
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

    // Función para validar formulario (solo valida nombre ahora)
    function validarFormulario() {
        let isValid = true;
        const errores = [];
        
        const nombre = document.getElementById('Nombre')?.value.trim();
        
        if (!nombre) {
            isValid = false;
            errores.push('Debe ingresar un nombre para el respaldo');
            document.getElementById('Nombre').classList.add('is-invalid');
        } else {
            document.getElementById('Nombre').classList.remove('is-invalid');
        }
        
        // Ya no validamos el usuario, se asigna automáticamente
        
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

    // Función para mostrar confirmación de respaldo
    function mostrarConfirmacionRespaldo(datos) {
        Swal.fire({
            title: '¿Generar Respaldo?',
            html: `
                <div class="text-start">
                    <div class="mb-4">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-database fa-lg text-primary"></i>
                        </div>
                        <p class="mb-3">¿Está seguro de generar un respaldo con la siguiente configuración?</p>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <small class="text-muted d-block">Nombre</small>
                                <strong>${datos.nombre}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-2 rounded">
                                <small class="text-muted d-block">Usuario</small>
                                <strong>${datos.usuario}</strong>
                            </div>
                        </div>
                    </div>
                    
                    ${datos.descripcion ? `
                    <div class="mb-3">
                        <small class="text-muted d-block">Descripción</small>
                        <div class="bg-light p-2 rounded">${datos.descripcion}</div>
                    </div>
                    ` : ''}
                    
                    <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            <div>
                                <small>El respaldo se guardará en:</small>
                                <div><code class="small">storage/app/backups/</code></div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-database me-2"></i> Sí, Generar',
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
                iniciarProcesoRespaldo(datos);
            }
        });
    }

    // Función para simular proceso de respaldo
    function iniciarProcesoRespaldo(datos) {
        // Mostrar modal de progreso
        progresoModal.show();
        
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
            progreso += Math.random() * 10 + 5; // Incremento variable
            if (progreso > 100) progreso = 100;
            
            // Actualizar barra de progreso
            barraProgreso.style.width = progreso + '%';
            porcentajeProgreso.textContent = Math.round(progreso) + '%';
            
            // Cambiar mensaje cada cierto progreso
            if (progreso >= (mensajeIndex + 1) * 10) {
                estadoProceso.textContent = mensajes[mensajeIndex];
                mensajeIndex = Math.min(mensajeIndex + 1, mensajes.length - 1);
            }
            
            // Cuando llegue al 100%
            if (progreso >= 100) {
                clearInterval(interval);
                
                // Animación final
                setTimeout(() => {
                    estadoProceso.textContent = '¡Respaldo completado!';
                    barraProgreso.classList.remove('progress-bar-animated');
                    
                    // Esperar y enviar formulario
                    setTimeout(() => {
                        progresoModal.hide();
                        
                        // Deshabilitar botón temporalmente
                        btnGenerar.disabled = true;
                        btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Procesando...';
                        
                        // Enviar formulario
                        form.submit();
                    }, 1500);
                }, 500);
            }
        }, 400);
    }

    // Evento para botón Generar Respaldo
    btnGenerar.addEventListener('click', function() {
        const validacion = validarFormulario();
        
        if (!validacion.isValid) {
            mostrarError(validacion.errores);
            return;
        }
        
        // Obtener datos del formulario
        const nombre = document.getElementById('Nombre').value.trim();
        const descripcion = document.getElementById('Descripcion').value.trim();
        const usuarioNombre = "{{ auth()->user()->correo ?? auth()->user()->email ?? auth()->user()->name ?? 'Usuario' }}";
        
        const datosRespaldo = {
            nombre: nombre,
            descripcion: descripcion,
            usuario: usuarioNombre,
            baseDatos: '{{ config('database.connections.mysql.database') }}'
        };
        
        mostrarConfirmacionRespaldo(datosRespaldo);
    });

    // Limpiar validaciones al interactuar
    const nombreInput = document.getElementById('Nombre');
    
    if (nombreInput) {
        nombreInput.addEventListener('input', function() {
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

.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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

/* Estilos para la barra de progreso */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
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
    
    .modal-dialog {
        margin: 0.5rem;
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

/* Estilos para el modal */
.modal-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.modal-content {
    border-radius: 12px;
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
</style>
@endsection