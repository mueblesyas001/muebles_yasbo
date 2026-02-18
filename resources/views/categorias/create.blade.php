@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card border-0 shadow-lg">
            <!-- Header con gradiente azul similar a proveedores -->
            <div class="card-header py-4" style="background: linear-gradient(135deg, #3a56d4 0%, #667eea 100%);">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-tag text-white fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 text-white fw-bold">Crear Nueva Categoría</h4>
                        <p class="mb-0 text-white opacity-75">Agrega una nueva categoría al sistema de inventario</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('categorias.store') }}" method="POST" id="categoria-form">
                    @csrf
                    
                    <!-- Progress Indicator -->
                    <div class="progress-steps mb-5">
                        <div class="d-flex justify-content-between position-relative">
                            <div class="step completed">
                                <div class="step-circle">1</div>
                                <div class="step-label mt-2">Información Básica</div>
                            </div>
                            <div class="step active">
                                <div class="step-circle">2</div>
                                <div class="step-label mt-2">Proveedor</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">3</div>
                                <div class="step-label mt-2">Confirmación</div>
                            </div>
                            <div class="progress-line">
                                <div class="progress-fill" style="width: 50%; background: linear-gradient(135deg, #3a56d4 0%, #667eea 100%);"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Información Básica -->
                    <div class="section-card mb-5">
                        <div class="section-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-primary-subtle me-3">
                                    <i class="fas fa-info-circle text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-dark fw-bold">Información de la Categoría</h5>
                            </div>
                            <div class="section-line" style="background: linear-gradient(135deg, #3a56d4 0%, #667eea 100%);"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('Nombre') is-invalid @enderror" 
                                           id="Nombre" name="Nombre" value="{{ old('Nombre') }}" 
                                           placeholder="Nombre de la categoría" required maxlength="255">
                                    <label for="Nombre" class="text-muted">
                                        <i class="fas fa-tag me-2"></i>Nombre de la Categoría *
                                    </label>
                                    <div class="character-count position-absolute end-0 bottom-0 me-3 mb-2 text-muted small">
                                        <span id="nombreCount">0</span>/255
                                    </div>
                                    @error('Nombre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text ms-2 mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Nombre descriptivo para identificar la categoría
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="form-floating">
                                    <textarea class="form-control @error('Descripcion') is-invalid @enderror" 
                                              id="Descripcion" name="Descripcion" 
                                              placeholder="Descripción de la categoría"
                                              style="height: 120px" maxlength="500">{{ old('Descripcion') }}</textarea>
                                    <label for="Descripcion" class="text-muted">
                                        <i class="fas fa-align-left me-2"></i>Descripción
                                    </label>
                                    <div class="character-count position-absolute end-0 bottom-0 me-3 mb-2 text-muted small">
                                        <span id="descripcionCount">0</span>/500
                                    </div>
                                    @error('Descripcion')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text ms-2 mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Describe brevemente las características de esta categoría
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Proveedor -->
                    <div class="section-card mb-5">
                        <div class="section-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-info-subtle me-3">
                                    <i class="fas fa-truck text-info"></i>
                                </div>
                                <h5 class="mb-0 text-dark fw-bold">Proveedor Asociado</h5>
                            </div>
                            <div class="section-line"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-floating">
                                    <select class="form-select @error('Proveedor') is-invalid @enderror" 
                                            id="Proveedor" name="Proveedor" required>
                                        <option value="">Seleccionar proveedor...</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}" 
                                                    {{ old('Proveedor') == $proveedor->id ? 'selected' : '' }}
                                                    data-empresa="{{ $proveedor->Empresa_asociada ?? 'Sin empresa' }}"
                                                    data-telefono="{{ $proveedor->Telefono ?? '' }}"
                                                    data-correo="{{ $proveedor->Correo ?? '' }}">
                                                {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} 
                                                {{ $proveedor->ApMaterno ? ' ' . $proveedor->ApMaterno : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="Proveedor" class="text-muted">
                                        <i class="fas fa-user-tie me-2"></i>Proveedor Asociado *
                                    </label>
                                    @error('Proveedor')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Preview del proveedor seleccionado -->
                                    <div class="preview-card p-3 mt-3" id="proveedorPreview" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-check text-success me-3 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold" id="proveedorNombre">Nombre del proveedor</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="fas fa-building me-2"></i>
                                                            <span id="proveedorEmpresa">Empresa</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="fas fa-phone me-2"></i>
                                                            <span id="proveedorTelefono">Teléfono</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="fas fa-envelope me-2"></i>
                                                            <span id="proveedorCorreo">Correo</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text ms-2 mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Selecciona un proveedor de la lista
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between pt-4 border-top">
                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                        <div>
                            <button type="reset" class="btn btn-light btn-lg me-3 px-4">
                                <i class="fas fa-redo me-2"></i> Limpiar
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                <i class="fas fa-save me-2"></i> Guardar Categoría
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
    const form = document.getElementById('categoria-form');
    
    // Elementos del formulario
    const nombreInput = document.getElementById('Nombre');
    const descripcionTextarea = document.getElementById('Descripcion');
    const proveedorSelect = document.getElementById('Proveedor');
    const proveedorPreview = document.getElementById('proveedorPreview');
    
    // Efecto de etiquetas flotantes
    const floatLabels = document.querySelectorAll('.form-floating input, .form-floating select, .form-floating textarea');
    floatLabels.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Contadores de caracteres
    const campos = ['Nombre', 'Descripcion'];
    campos.forEach(campo => {
        const input = document.getElementById(campo);
        const contador = document.getElementById(campo.toLowerCase() + 'Count');
        if (input && contador) {
            contador.textContent = input.value.length;
            input.addEventListener('input', function() {
                contador.textContent = this.value.length;
                // Cambiar color según longitud
                const max = parseInt(input.maxLength) || 255;
                const percent = (this.value.length / max) * 100;
                if (percent > 90) {
                    contador.style.color = '#dc3545';
                } else if (percent > 70) {
                    contador.style.color = '#ffc107';
                } else {
                    contador.style.color = '#6c757d';
                }
                
                // Efecto de actualización
                contador.classList.add('updated');
                setTimeout(() => {
                    contador.classList.remove('updated');
                }, 500);
            });
            // Trigger inicial
            input.dispatchEvent(new Event('input'));
        }
    });

    // Actualizar preview del proveedor
    if (proveedorSelect) {
        proveedorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const empresa = selectedOption.getAttribute('data-empresa');
            const telefono = selectedOption.getAttribute('data-telefono');
            const correo = selectedOption.getAttribute('data-correo');
            
            if (selectedOption.value) {
                // Mostrar preview
                proveedorPreview.style.display = 'block';
                document.getElementById('proveedorNombre').textContent = selectedOption.text;
                document.getElementById('proveedorEmpresa').textContent = empresa || 'Sin empresa registrada';
                document.getElementById('proveedorTelefono').textContent = telefono || 'Sin teléfono';
                document.getElementById('proveedorCorreo').textContent = correo || 'Sin correo';
                
                // Efecto visual
                proveedorPreview.classList.add('updated');
                setTimeout(() => {
                    proveedorPreview.classList.remove('updated');
                }, 1000);
            } else {
                proveedorPreview.style.display = 'none';
            }
        });
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
            
            // Validar longitud mínima del nombre
            if (nombreInput && nombreInput.value.trim().length < 3) {
                isValid = false;
                nombreInput.classList.add('is-invalid');
                errors.push('<i class="fas fa-tag me-2"></i>El nombre debe tener al menos 3 caracteres');
            }
            
            // Validar proveedor seleccionado
            if (proveedorSelect && !proveedorSelect.value) {
                isValid = false;
                proveedorSelect.classList.add('is-invalid');
                errors.push('<i class="fas fa-user-tie me-2"></i>Debe seleccionar un proveedor');
            }
            
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Validación',
                    html: `<div class="text-start">${errors.join('<br>')}</div>`,
                    confirmButtonText: 'Corregir',
                    confirmButtonColor: '#3a56d4',
                });
                // Enfocar el primer campo con error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            } else {
                // Confirmación antes de enviar
                Swal.fire({
                    title: '¿Crear Categoría?',
                    html: `
                        <div class="text-start">
                            <p><strong>Categoría:</strong> ${nombreInput.value}</p>
                            <p><strong>Descripción:</strong> ${descripcionTextarea.value || 'Sin descripción'}</p>
                            <p><strong>Proveedor:</strong> ${proveedorSelect.options[proveedorSelect.selectedIndex].text}</p>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Esta categoría quedará disponible para asignar productos
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, crear',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3a56d4',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loader
                        Swal.fire({
                            title: 'Creando...',
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
    const inputs = form.querySelectorAll('input, select, textarea');
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
    
    // Efecto visual para tarjetas de estadísticas
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.05)';
        });
    });
});
</script>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #3a56d4 0%, #667eea 100%);
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
    color: #3a56d4;
    font-weight: 600;
}

.form-control, .form-select, .form-floating textarea {
    border-radius: 10px;
    padding: 1rem 0.75rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus, .form-select:focus, .form-floating textarea:focus {
    border-color: #3a56d4;
    box-shadow: 0 0 0 0.25rem rgba(58, 86, 212, 0.1);
    background: white;
    transform: translateY(-2px);
}

.form-control.is-invalid, .form-select.is-invalid, .form-floating textarea.is-invalid {
    border-color: #dc3545;
    background-image: none;
}

.form-control.is-invalid:focus, .form-select.is-invalid:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.1);
}

.preview-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #3a56d4;
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
    color: #3a56d4;
    border-color: #3a56d4;
    transform: scale(1.1);
    box-shadow: 0 0 0 5px rgba(58, 86, 212, 0.2);
}

.step-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.step.active .step-label {
    color: #3a56d4;
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
    background: linear-gradient(135deg, #2f48b8 0%, #5a6fd8 100%);
    box-shadow: 0 10px 25px rgba(58, 86, 212, 0.3);
}

.btn-outline-secondary {
    border-width: 2px;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

.btn-light {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
}

.btn-light:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

/* Tarjetas de estadísticas */
.stat-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
    
    .stat-card {
        margin-bottom: 1rem;
    }
}

/* Estilos para contadores de caracteres */
.character-count {
    font-size: 0.75rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.character-count.updated {
    animation: countUpdate 0.5s ease;
}

@keyframes countUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Indicador de campo obligatorio */
.required-field::after {
    content: " *";
    color: #dc3545;
}

/* Estilo para textarea en form-floating */
.form-floating textarea {
    min-height: 120px;
    resize: vertical;
}

/* Efecto especial para campos editados */
.form-control.edited {
    border-left: 4px solid #3a56d4;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

/* Animación de carga para botones */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin: -10px 0 0 -10px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Estilo para select con iconos */
.form-select option:checked {
    background: linear-gradient(135deg, #3a56d4 0%, #667eea 100%);
    color: white;
}

/* Tooltip para opciones de proveedor */
.form-select option {
    padding: 10px;
    border-bottom: 1px solid #e9ecef;
}

.form-select option:hover {
    background-color: #f8f9fa;
}
</style>

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection