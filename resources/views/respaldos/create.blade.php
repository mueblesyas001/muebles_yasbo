@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-database me-2"></i>Generar Nuevo Respaldo
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('respaldos.store') }}" method="POST" id="respaldoForm">
                    @csrf
                    
                    <!-- Campo oculto con el ID del usuario logueado -->
                    <input type="hidden" name="Usuario" value="{{ auth()->id() }}">
                    
                    <!-- Información General del Respaldo -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Información General del Respaldo
                            </h5>
                        </div>

                        <!-- Nombre del Respaldo - IGUAL AL DE VENTAS -->
                        <div class="col-md-6 mb-3">
                            <label for="Nombre" class="form-label">Nombre del Respaldo *</label>
                            <input type="text" 
                                   class="form-control @error('Nombre') is-invalid @enderror" 
                                   id="Nombre" 
                                   name="Nombre" 
                                   value="{{ old('Nombre', 'Respaldo ' . now()->format('d-m-Y H:i')) }}" 
                                   required 
                                   maxlength="80"
                                   placeholder="Ej: Respaldo Mensual Enero 2024">
                            @error('Nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Identificador único para este respaldo</div>
                        </div>

                        <!-- Usuario Responsable - IGUAL AL DE VENTAS -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usuario Responsable</label>
                            <div class="form-control bg-light">
                                {{ auth()->user()->correo ?? auth()->user()->email ?? auth()->user()->name ?? 'Usuario' }}
                            </div>
                            <div class="form-text">Se registrará automáticamente</div>
                        </div>

                        <!-- Descripción - IGUAL AL DE VENTAS -->
                        <div class="col-12 mb-3">
                            <label for="Descripcion" class="form-label">Descripción <span class="text-muted fw-normal">(Opcional)</span></label>
                            <textarea class="form-control @error('Descripcion') is-invalid @enderror" 
                                      id="Descripcion" 
                                      name="Descripcion" 
                                      rows="3" 
                                      maxlength="280"
                                      placeholder="Describe el propósito de este respaldo...">{{ old('Descripcion') }}</textarea>
                            @error('Descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Máximo 280 caracteres</small>
                                <small class="text-muted" id="contadorCaracteres">0/280</small>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Sistema - IGUAL AL DE VENTAS (RESUMEN) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-server me-2"></i>Información del Sistema
                            </h5>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Base de Datos</label>
                            <div class="form-control bg-light">
                                {{ config('database.connections.mysql.database') }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tablas</label>
                            <div class="form-control bg-light">
                                {{ $estadisticas['total_tablas'] ?? '0' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tamaño Total</label>
                            <div class="form-control bg-light">
                                {{ $estadisticas['tamaño_total'] ?? '0 MB' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha y Hora Actual</label>
                            <div class="form-control bg-light">
                                @php
                                    $fechaHora = now(config('app.timezone', 'America/Lima'));
                                @endphp
                                {{ $fechaHora->isoFormat('DD/MM/YYYY hh:mm A') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Último Respaldo</label>
                            <div class="form-control bg-light">
                                @if(isset($estadisticas['ultimo_respaldo_fecha']) && $estadisticas['ultimo_respaldo_fecha'])
                                    @php
                                        $fechaUltimoRespaldo = \Carbon\Carbon::parse($estadisticas['ultimo_respaldo_fecha'])->timezone(config('app.timezone', 'America/Lima'));
                                    @endphp
                                    {{ $fechaUltimoRespaldo->isoFormat('DD/MM/YYYY hh:mm A') }}
                                @else
                                    Sin respaldos previos
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del Respaldo - IGUAL AL DE VENTAS (ALERT INFO) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle me-2 mt-1"></i>
                                    <div>
                                        <strong>Información del proceso:</strong>
                                        <ul class="mb-0 mt-1">
                                            <li>Se generará un archivo SQL con toda la estructura y datos</li>
                                            <li>El respaldo incluirá todas las tablas del sistema</li>
                                            <li>Archivo: <code>backup_{{ config('database.connections.mysql.database') }}_fecha.sql</code></li>
                                            <li>Ubicación: <code>storage/app/backups/</code></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción - IGUAL AL DE VENTAS -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="{{ route('respaldos.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-success" id="btnGenerarRespaldo">
                            <i class="fas fa-database me-1"></i> Generar Respaldo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Progreso -->
<div class="modal fade" id="progresoModal" tabindex="-1" aria-labelledby="progresoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="progresoModalLabel">
                    <i class="fas fa-sync-alt me-2"></i>
                    Generando Respaldo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-2" id="estadoProceso">Preparando respaldo...</h5>
                <p class="text-muted mb-4">Procesando base de datos</p>
                
                <!-- Barra de progreso -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Progreso</small>
                        <small class="fw-bold" id="porcentajeProgreso">0%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
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
                        <div>
                            <small class="fw-semibold">Base de datos:</small>
                            <div><code>{{ config('database.connections.mysql.database') }}</code></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <div>
                            <small class="fw-semibold">Inicio:</small>
                            <div id="horaInicio">
                                @php
                                    echo now(config('app.timezone', 'America/Lima'))->isoFormat('HH:mm:ss');
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning text-start">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡No cierre esta ventana!</strong><br>
                    <small>El proceso puede tomar varios minutos dependiendo del tamaño de la base de datos.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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

    // Contador de caracteres para descripción
    if (textareaDescripcion && contadorCaracteres) {
        const longitudInicial = textareaDescripcion.value.length;
        contadorCaracteres.textContent = `${longitudInicial}/280`;

        textareaDescripcion.addEventListener('input', function() {
            const longitud = this.value.length;
            contadorCaracteres.textContent = `${longitud}/280`;
        });
    }

    // Función para mostrar alerta de error
    function mostrarError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error en el formulario',
            html: mensaje,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545',
            customClass: {
                popup: 'swal2-custom-popup'
            }
        });
    }

    // Función para mostrar confirmación de respaldo
    function mostrarConfirmacionRespaldo(datosVenta) {
        Swal.fire({
            title: '¿Generar Respaldo?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de generar el siguiente respaldo?</p>
                    <div class="alert alert-info">
                        <strong>Resumen del Respaldo:</strong><br>
                        • Nombre: <strong>${datosVenta.nombre}</strong><br>
                        • Descripción: <strong>${datosVenta.descripcion || 'Sin descripción'}</strong>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-database me-1"></i> Sí, Generar',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-custom-popup',
                confirmButton: 'swal2-confirm-btn',
                cancelButton: 'swal2-cancel-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                iniciarProcesoRespaldo();
            }
        });
    }

    // Función para validar formulario
    function validarFormulario() {
        let isValid = true;
        const errores = [];
        
        const nombre = document.getElementById('Nombre')?.value.trim();
        
        if (!nombre) {
            isValid = false;
            errores.push('• Debe ingresar un nombre para el respaldo.');
            document.getElementById('Nombre').classList.add('is-invalid');
        } else {
            document.getElementById('Nombre').classList.remove('is-invalid');
        }
        
        return { isValid, errores };
    }

    // Función para simular proceso de respaldo
    function iniciarProcesoRespaldo() {
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
            progreso += Math.random() * 10 + 5;
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
                        btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Procesando...';
                        
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
            // Mostrar todos los errores con SweetAlert2
            const mensajeErrores = `
                <div class="text-start">
                    <p class="mb-2">Por favor, corrija los siguientes errores:</p>
                    <div class="alert alert-danger text-start">
                        ${[...new Set(validacion.errores)].join('<br>')}
                    </div>
                </div>
            `;
            mostrarError(mensajeErrores);
        } else {
            // Mostrar confirmación con SweetAlert2
            const nombre = document.getElementById('Nombre').value.trim();
            const descripcion = document.getElementById('Descripcion').value.trim();
            
            const datosRespaldo = {
                nombre: nombre,
                descripcion: descripcion || 'Sin descripción'
            };
            
            mostrarConfirmacionRespaldo(datosRespaldo);
        }
    });

    // Limpiar validaciones al interactuar
    document.getElementById('Nombre').addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-bottom: none;
    padding: 1.5rem 2rem;
    border-radius: 1rem 1rem 0 0 !important;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.btn {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn-success:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-secondary:hover {
    transform: translateY(-2px);
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    min-height: 38px;
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
}

.text-primary {
    color: #28a745 !important;
}

.border-top {
    border-top: 2px solid #e9ecef !important;
}

h5 {
    font-weight: 600;
    margin-bottom: 1rem;
}

/* Estilos para SweetAlert2 personalizados */
.swal2-custom-popup {
    border-radius: 1rem;
    padding: 2rem;
}

.swal2-confirm-btn {
    border-radius: 0.5rem !important;
    padding: 0.75rem 1.5rem !important;
}

.swal2-cancel-btn {
    border-radius: 0.5rem !important;
    padding: 0.75rem 1.5rem !important;
}

.swal2-popup .alert {
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header {
        padding: 1rem 1.5rem;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .swal2-popup {
        margin: 0 10px;
    }
}

/* Estilos para loading */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Ajustes específicos */
.col-lg-10.col-xl-8 {
    padding: 0 15px;
}

code {
    background: #e9ecef;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    color: #d63384;
}

.alert-info {
    background-color: #cff4fc;
    border-color: #b6effb;
    color: #055160;
}

.alert-info i {
    color: #0c5460;
}

.alert-info ul {
    padding-left: 1.2rem;
}

.alert-info li {
    margin-bottom: 0.25rem;
}

.alert-info li:last-child {
    margin-bottom: 0;
}

/* Estilos para el modal de progreso */
.modal-content {
    border: none;
    border-radius: 1rem;
}

.modal-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-bottom: none;
    padding: 1rem 1.5rem;
    border-radius: 1rem 1rem 0 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 600;
}

.modal-body {
    padding: 2rem;
}

.modal-body .spinner-border {
    color: #28a745 !important;
}

.modal-body .progress {
    height: 0.8rem;
    border-radius: 0.5rem;
}

.modal-body .progress-bar {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.modal-body .bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
}

.modal-body .alert-warning {
    background-color: #fff3cd;
    border-color: #ffecb5;
    color: #664d03;
    border-radius: 0.5rem;
}
</style>
@endsection