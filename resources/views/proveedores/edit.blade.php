@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card border-0 shadow-lg">
            <!-- Header igual al de crear -->
            <div class="card-header py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-user-edit text-white fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 text-white fw-bold">Editar Proveedor</h4>
                        <p class="mb-0 text-white opacity-75">Actualice la información del proveedor</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('proveedores.update', $proveedor->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Progress Indicator -->
                    <div class="progress-steps mb-5">
                        <div class="d-flex justify-content-between position-relative">
                            <div class="step completed">
                                <div class="step-circle">1</div>
                                <div class="step-label mt-2">Información Personal</div>
                            </div>
                            <div class="step active">
                                <div class="step-circle">2</div>
                                <div class="step-label mt-2">Empresa y Contacto</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">3</div>
                                <div class="step-label mt-2">Confirmación</div>
                            </div>
                            <div class="progress-line">
                                <div class="progress-fill" style="width: 50%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Personal -->
                    <div class="section-card mb-5">
                        <div class="section-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-primary-subtle me-3">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-dark fw-bold">Información Personal</h5>
                            </div>
                            <div class="section-line"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('Nombre') is-invalid @enderror" 
                                           id="Nombre" name="Nombre" value="{{ old('Nombre', $proveedor->Nombre) }}" 
                                           placeholder="Nombre(s) del proveedor" required maxlength="255">
                                    <label for="Nombre" class="text-muted">
                                        <i class="fas fa-signature me-2"></i>Nombre(s) *
                                    </label>
                                    @error('Nombre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('ApPaterno') is-invalid @enderror" 
                                           id="ApPaterno" name="ApPaterno" value="{{ old('ApPaterno', $proveedor->ApPaterno) }}" 
                                           placeholder="Apellido paterno" required maxlength="255">
                                    <label for="ApPaterno" class="text-muted">
                                        <i class="fas fa-user me-2"></i>Apellido Paterno *
                                    </label>
                                    @error('ApPaterno')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('ApMaterno') is-invalid @enderror" 
                                           id="ApMaterno" name="ApMaterno" value="{{ old('ApMaterno', $proveedor->ApMaterno) }}" 
                                           placeholder="Apellido materno" required maxlength="255">
                                    <label for="ApMaterno" class="text-muted">
                                        <i class="fas fa-user me-2"></i>Apellido Materno *
                                    </label>
                                    @error('ApMaterno')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-floating">
                                    <select class="form-select @error('Sexo') is-invalid @enderror" id="Sexo" name="Sexo" required>
                                        <option value="">Seleccionar sexo</option>
                                        <option value="Masculino" {{ old('Sexo', $proveedor->Sexo) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('Sexo', $proveedor->Sexo) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        <option value="Otro" {{ old('Sexo', $proveedor->Sexo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    <label for="Sexo" class="text-muted">
                                        <i class="fas fa-venus-mars me-2"></i>Sexo *
                                    </label>
                                    @error('Sexo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted mb-2">
                                    <i class="fas fa-id-card me-2"></i>Nombre Completo
                                </label>
                                <div class="preview-card p-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-check text-success me-3 fs-5"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold" id="nombreCompleto">{{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno }}</h6>
                                            <small class="text-muted">Actualizado en tiempo real</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Empresa y Contacto -->
                    <div class="section-card mb-5">
                        <div class="section-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-info-subtle me-3">
                                    <i class="fas fa-building text-info"></i>
                                </div>
                                <h5 class="mb-0 text-dark fw-bold">Información de Empresa y Contacto</h5>
                            </div>
                            <div class="section-line"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('Empresa_asociada') is-invalid @enderror" 
                                           id="Empresa_asociada" name="Empresa_asociada" value="{{ old('Empresa_asociada', $proveedor->Empresa_asociada) }}" 
                                           placeholder="Nombre de la empresa" required maxlength="255">
                                    <label for="Empresa_asociada" class="text-muted">
                                        <i class="fas fa-briefcase me-2"></i>Empresa Asociada *
                                    </label>
                                    @error('Empresa_asociada')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('Correo') is-invalid @enderror" 
                                           id="Correo" name="Correo" value="{{ old('Correo', $proveedor->Correo) }}" 
                                           placeholder="correo@empresa.com" required maxlength="255">
                                    <label for="Correo" class="text-muted">
                                        <i class="fas fa-envelope me-2"></i>Correo Electrónico *
                                    </label>
                                    @error('Correo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-floating">
                                    <input type="tel" class="form-control @error('Telefono') is-invalid @enderror" 
                                           id="Telefono" name="Telefono" value="{{ old('Telefono', $proveedor->Telefono) }}" 
                                           placeholder="10 dígitos" required pattern="[0-9]{10}" maxlength="10">
                                    <label for="Telefono" class="text-muted">
                                        <i class="fas fa-phone me-2"></i>Teléfono *
                                    </label>
                                    @error('Telefono')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text ms-2">
                                        <i class="fas fa-info-circle me-1"></i> Ingrese 10 dígitos sin espacios
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo oculto para el ID -->
                    <input type="hidden" name="idProveedor" value="{{ $proveedor->id }}">

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between pt-4 border-top">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                        <div>
                            <a href="{{ route('proveedores.show', $proveedor->id) }}" class="btn btn-info btn-lg me-3 px-4">
                                <i class="fas fa-eye me-2"></i> Ver Detalles
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                <i class="fas fa-save me-2"></i> Actualizar Proveedor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del formulario
    const nombreInput = document.getElementById('Nombre');
    const apPaternoInput = document.getElementById('ApPaterno');
    const apMaternoInput = document.getElementById('ApMaterno');
    const nombreCompletoDiv = document.getElementById('nombreCompleto');
    const telefonoInput = document.getElementById('Telefono');
    const form = document.getElementById('editForm');
    
    // Efecto de etiquetas flotantes
    const floatLabels = document.querySelectorAll('.form-floating input, .form-floating select');
    floatLabels.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
        // Inicializar estado (todos los campos tienen valores en edición)
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });

    // Función para actualizar el nombre completo con efectos
    function actualizarNombreCompleto() {
        const nombre = nombreInput.value.trim();
        const apPaterno = apPaternoInput.value.trim();
        const apMaterno = apMaternoInput.value.trim();
        
        let nombreCompleto = '';
        
        if (nombre || apPaterno || apMaterno) {
            nombreCompleto = `${nombre} ${apPaterno}`;
            if (apMaterno) {
                nombreCompleto += ` ${apMaterno}`;
            }
            nombreCompletoDiv.innerHTML = nombreCompleto;
            nombreCompletoDiv.parentElement.querySelector('small').textContent = 'Actualizado en tiempo real';
            
            // Efecto visual de actualización
            nombreCompletoDiv.parentElement.classList.add('updated');
            setTimeout(() => {
                nombreCompletoDiv.parentElement.classList.remove('updated');
            }, 1000);
        } else {
            nombreCompletoDiv.innerHTML = 'Ingrese los datos del proveedor';
            nombreCompletoDiv.parentElement.querySelector('small').textContent = 'Complete los campos...';
        }
    }

    // Escuchar cambios en los campos de nombre
    if (nombreInput && apPaternoInput && apMaternoInput) {
        [nombreInput, apPaternoInput, apMaternoInput].forEach(input => {
            input.addEventListener('input', function() {
                actualizarNombreCompleto();
                // Efecto visual al escribir
                if (this.value) {
                    this.style.borderLeftColor = '#667eea';
                    this.style.borderLeftWidth = '3px';
                } else {
                    this.style.borderLeftColor = '';
                    this.style.borderLeftWidth = '';
                }
            });
        });
    }

    // Validación del teléfono con formato
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            // Solo permitir números
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limitar a 10 dígitos
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            
            // Mostrar contador
            const counter = this.parentElement.querySelector('.char-counter') || 
                           (() => {
                               const span = document.createElement('span');
                               span.className = 'char-counter position-absolute end-0 bottom-0 me-3 mb-2 text-muted small';
                               this.parentElement.appendChild(span);
                               return span;
                           })();
            
            counter.textContent = `${this.value.length}/10`;
            
            // Cambiar color según longitud
            if (this.value.length === 10) {
                counter.style.color = '#28a745';
                this.style.borderColor = '#28a745';
            } else {
                counter.style.color = '#6c757d';
                this.style.borderColor = '';
            }
        });
        
        // Trigger inicial
        telefonoInput.dispatchEvent(new Event('input'));
    }

    // Validación del formulario con SweetAlert
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const errors = [];
            
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    const label = field.parentElement.querySelector('label').textContent;
                    errors.push(`<i class="fas fa-exclamation-circle me-2"></i>${label} es requerido`);
                    
                    // Efecto de shake
                    field.parentElement.classList.add('animate__animated', 'animate__shakeX');
                    setTimeout(() => {
                        field.parentElement.classList.remove('animate__animated', 'animate__shakeX');
                    }, 1000);
                }
            });
            
            // Validar formato de teléfono
            if (telefonoInput && telefonoInput.value.length !== 10) {
                isValid = false;
                telefonoInput.classList.add('is-invalid');
                errors.push('<i class="fas fa-phone me-2"></i>El teléfono debe tener exactamente 10 dígitos');
            }
            
            // Validar formato de correo
            const correoInput = document.getElementById('Correo');
            if (correoInput && correoInput.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(correoInput.value)) {
                    isValid = false;
                    correoInput.classList.add('is-invalid');
                    errors.push('<i class="fas fa-envelope me-2"></i>Por favor ingrese un correo electrónico válido');
                }
            }
            
            // Validar si hay cambios
            const originalData = {
                nombre: '{{ $proveedor->Nombre }}',
                apPaterno: '{{ $proveedor->ApPaterno }}',
                apMaterno: '{{ $proveedor->ApMaterno }}',
                sexo: '{{ $proveedor->Sexo }}',
                empresa: '{{ $proveedor->Empresa_asociada }}',
                correo: '{{ $proveedor->Correo }}',
                telefono: '{{ $proveedor->Telefono }}'
            };
            
            const currentData = {
                nombre: nombreInput.value.trim(),
                apPaterno: apPaternoInput.value.trim(),
                apMaterno: apMaternoInput.value.trim(),
                sexo: document.getElementById('Sexo').value,
                empresa: document.getElementById('Empresa_asociada').value.trim(),
                correo: correoInput.value.trim(),
                telefono: telefonoInput.value.trim()
            };
            
            const hasChanges = JSON.stringify(originalData) !== JSON.stringify(currentData);
            
            if (!hasChanges) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin Cambios',
                    text: 'No se han realizado modificaciones en los datos.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#667eea',
                });
                return;
            }
            
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Validación',
                    html: `<div class="text-start">${errors.join('<br>')}</div>`,
                    confirmButtonText: 'Corregir',
                    confirmButtonColor: '#667eea',
                });
                // Enfocar el primer campo con error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            } else {
                // Confirmación antes de actualizar
                Swal.fire({
                    title: '¿Actualizar Proveedor?',
                    html: `
                        <div class="text-start">
                            <p><strong>Proveedor Actualizado:</strong></p>
                            <p><strong>Nombre:</strong> ${currentData.nombre} ${currentData.apPaterno} ${currentData.apMaterno}</p>
                            <p><strong>Empresa:</strong> ${currentData.empresa}</p>
                            <p><strong>Contacto:</strong> ${currentData.correo} | ${currentData.telefono}</p>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Esta acción actualizará permanentemente los datos del proveedor.
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loader
                        Swal.fire({
                            title: 'Actualizando...',
                            text: 'Por favor espere',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Enviar formulario
                        form.submit();
                    }
                });
            }
        });
    }

    // Limpiar validación al escribir
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Efecto al pasar sobre botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Efecto visual para mostrar que es edición
    const sectionCards = document.querySelectorAll('.section-card');
    sectionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.2)';
            this.style.borderColor = 'rgba(102, 126, 234, 0.3)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.05)';
            this.style.borderColor = 'rgba(0, 0, 0, 0.05)';
        });
    });
});
</script>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.card {
    border-radius: 20px;
    overflow: hidden;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-bottom: none;
    padding: 2rem !important;
}

.section-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}

.section-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.section-header {
    position: relative;
}

.section-line {
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: var(--primary-gradient);
    border-radius: 2px;
}

.icon-wrapper {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.form-floating {
    position: relative;
}

.form-floating.focused label {
    color: #667eea;
    font-weight: 600;
}

.form-control, .form-select {
    border-radius: 10px;
    padding: 1rem 0.75rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
    background: white;
    transform: translateY(-2px);
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: #dc3545;
    background-image: none;
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.1);
}

.preview-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #28a745;
    transition: all 0.3s ease;
}

.preview-card:hover {
    transform: translateX(5px);
}

.preview-card.updated {
    animation: pulseUpdate 1s ease;
    border-left-color: #667eea;
}

@keyframes pulseUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Progress Steps */
.progress-steps {
    position: relative;
}

.step {
    text-align: center;
    z-index: 1;
    flex: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto;
    border: 3px solid white;
    transition: all 0.3s ease;
}

.step.completed .step-circle {
    background: var(--primary-gradient);
    color: white;
}

.step.active .step-circle {
    background: white;
    color: #667eea;
    border-color: #667eea;
    transform: scale(1.1);
    box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.2);
}

.step-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.step.active .step-label {
    color: #667eea;
    font-weight: 600;
}

.progress-line {
    position: absolute;
    top: 20px;
    left: 10%;
    right: 10%;
    height: 4px;
    background: #e9ecef;
    z-index: 0;
}

.progress-fill {
    height: 100%;
    background: var(--primary-gradient);
    border-radius: 2px;
    transition: width 0.5s ease;
}

/* Botones mejorados */
.btn {
    border-radius: 10px;
    padding: 0.875rem 1.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    position: relative;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4090 100%);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-outline-secondary {
    border-width: 2px;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

.btn-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    border: none;
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #0ba8cc 0%, #098da6 100%);
    box-shadow: 0 10px 25px rgba(13, 202, 240, 0.3);
}

.btn-light {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
}

.btn-light:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

/* Animaciones */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.btn-primary:active {
    animation: pulse 0.2s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 2rem 1.5rem !important;
    }
    
    .section-card {
        padding: 1.5rem;
    }
    
    .progress-steps .step-label {
        font-size: 0.75rem;
    }
    
    .btn {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
    }
}

/* Estilos para la validación en tiempo real */
.char-counter {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Indicador visual para campos editados */
.form-control:not([readonly]):focus {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-left: 4px solid #667eea;
}

/* Efecto especial para edición */
.edit-indicator {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #667eea;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection