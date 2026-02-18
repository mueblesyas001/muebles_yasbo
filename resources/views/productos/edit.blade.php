@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card border-0 shadow-lg">
            <!-- Header con gradiente naranja idéntico al crear -->
            <div class="card-header py-4" style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-edit text-white fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 text-white fw-bold">Editar Producto</h4>
                        <p class="mb-0 text-white opacity-75">Actualiza la información del producto en el inventario</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('productos.update', $producto->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Progress Indicator -->
                    <div class="progress-steps mb-5">
                        <div class="d-flex justify-content-between position-relative">
                            <div class="step completed">
                                <div class="step-circle">1</div>
                                <div class="step-label mt-2">Información Básica</div>
                            </div>
                            <div class="step active">
                                <div class="step-circle">2</div>
                                <div class="step-label mt-2">Inventario</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">3</div>
                                <div class="step-label mt-2">Confirmación</div>
                            </div>
                            <div class="progress-line">
                                <div class="progress-fill" style="width: 50%; background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);"></div>
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
                                <h5 class="mb-0 text-dark fw-bold">Información Básica</h5>
                            </div>
                            <div class="section-line" style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('Nombre') is-invalid @enderror" 
                                           id="Nombre" name="Nombre" value="{{ old('Nombre', $producto->Nombre) }}" 
                                           placeholder="Nombre del producto" required maxlength="100">
                                    <label for="Nombre" class="text-muted">
                                        <i class="fas fa-tag me-2"></i>Nombre del Producto *
                                    </label>
                                    <div class="character-count position-absolute end-0 bottom-0 me-3 mb-2 text-muted small">
                                        <span id="nombreCount">{{ strlen($producto->Nombre) }}</span>/100
                                    </div>
                                    @error('Nombre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <select class="form-select @error('Categoria') is-invalid @enderror" 
                                            id="Categoria" name="Categoria" required>
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" 
                                                {{ old('Categoria', $producto->Categoria) == $categoria->id ? 'selected' : '' }}
                                                data-proveedor="{{ $categoria->proveedor->Nombre ?? 'Sin proveedor' }}">
                                                {{ $categoria->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="Categoria" class="text-muted">
                                        <i class="fas fa-folder me-2"></i>Categoría *
                                    </label>
                                    @error('Categoria')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="form-floating">
                                    <textarea class="form-control @error('Descripcion') is-invalid @enderror" 
                                              id="Descripcion" name="Descripcion" 
                                              placeholder="Descripción del producto"
                                              style="height: 100px" maxlength="200">{{ old('Descripcion', $producto->Descripcion) }}</textarea>
                                    <label for="Descripcion" class="text-muted">
                                        <i class="fas fa-align-left me-2"></i>Descripción
                                    </label>
                                    <div class="character-count position-absolute end-0 bottom-0 me-3 mb-2 text-muted small">
                                        <span id="descripcionCount">{{ strlen($producto->Descripcion) }}</span>/200
                                    </div>
                                    @error('Descripcion')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Precio e Inventario -->
                    <div class="section-card mb-5">
                        <div class="section-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-warning-subtle me-3">
                                    <i class="fas fa-chart-line text-warning"></i>
                                </div>
                                <h5 class="mb-0 text-dark fw-bold">Precio e Inventario</h5>
                            </div>
                            <div class="section-line"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('Precio') is-invalid @enderror" 
                                           id="Precio" name="Precio" value="{{ old('Precio', $producto->Precio) }}" 
                                           placeholder="0.00" step="0.01" min="0" required>
                                    <label for="Precio" class="text-muted">
                                        <i class="fas fa-dollar-sign me-2"></i>Precio *
                                    </label>
                                    <div class="form-text ms-2 mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Precio unitario del producto
                                    </div>
                                    @error('Precio')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('Cantidad') is-invalid @enderror" 
                                           id="Cantidad" name="Cantidad" value="{{ old('Cantidad', $producto->Cantidad) }}" 
                                           placeholder="0" min="0" required>
                                    <label for="Cantidad" class="text-muted">
                                        <i class="fas fa-boxes me-2"></i>Stock Actual *
                                    </label>
                                    @error('Cantidad')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label text-muted mb-2">
                                    <i class="fas fa-chart-bar me-2"></i>Estado del Stock
                                </label>
                                <div class="preview-card p-3">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $cantidad = $producto->Cantidad;
                                            $minima = $producto->Cantidad_minima;
                                            
                                            if($cantidad == 0) {
                                                $icono = 'fa-times-circle';
                                                $texto = 'Sin Stock';
                                                $color = 'danger';
                                            } elseif($cantidad <= $minima) {
                                                $icono = 'fa-exclamation-triangle';
                                                $texto = 'Stock Bajo';
                                                $color = 'warning';
                                            } else {
                                                $icono = 'fa-check-circle';
                                                $texto = 'Stock Normal';
                                                $color = 'success';
                                            }
                                        @endphp
                                        <i class="fas {{ $icono }} text-{{ $color }} me-3 fs-5"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-{{ $color }}" id="estadoStockText">{{ $texto }}</h6>
                                            <small class="text-muted">Actualizado en tiempo real</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('Cantidad_minima') is-invalid @enderror" 
                                           id="Cantidad_minima" name="Cantidad_minima" 
                                           value="{{ old('Cantidad_minima', $producto->Cantidad_minima) }}" 
                                           placeholder="0" min="0" required>
                                    <label for="Cantidad_minima" class="text-muted">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Stock Mínimo *
                                    </label>
                                    <div class="form-text ms-2">
                                        <i class="fas fa-info-circle me-1"></i> Se alertará cuando el stock llegue a este nivel
                                    </div>
                                    @error('Cantidad_minima')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('Cantidad_maxima') is-invalid @enderror" 
                                           id="Cantidad_maxima" name="Cantidad_maxima" 
                                           value="{{ old('Cantidad_maxima', $producto->Cantidad_maxima) }}" 
                                           placeholder="0" min="1" required>
                                    <label for="Cantidad_maxima" class="text-muted">
                                        <i class="fas fa-warehouse me-2"></i>Stock Máximo *
                                    </label>
                                    <div class="form-text ms-2">
                                        <i class="fas fa-info-circle me-1"></i> Capacidad máxima de almacenamiento
                                    </div>
                                    @error('Cantidad_maxima')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label text-muted mb-2">
                                    <i class="fas fa-calculator me-2"></i>Valor Total del Inventario
                                </label>
                                <div class="preview-card p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted d-block">Valor total</small>
                                            <h5 class="mb-0 fw-bold" id="valorTotal">
                                                ${{ number_format($producto->Precio * $producto->Cantidad, 2) }}
                                            </h5>
                                        </div>
                                        <i class="fas fa-chart-line text-success fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de progreso del stock -->
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-muted mb-2">
                                    <i class="fas fa-chart-pie me-2"></i>Nivel de Stock
                                </label>
                                <div class="preview-card p-3">
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar" id="stockProgress" role="progressbar" 
                                             style="width: {{ ($producto->Cantidad / max($producto->Cantidad_maxima, 1)) * 100 }}%"></div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <h5 class="mb-1 fw-bold">{{ $producto->Cantidad_minima }}</h5>
                                            <small class="text-muted">Mínimo</small>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-1 fw-bold">{{ $producto->Cantidad }}</h5>
                                            <small class="text-muted">Actual</small>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-1 fw-bold">{{ $producto->Cantidad_maxima }}</h5>
                                            <small class="text-muted">Máximo</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo oculto para el ID -->
                    <input type="hidden" name="id" value="{{ $producto->id }}">

                    <!-- Botones -->
                    <div class="d-flex justify-content-between pt-4 border-top">
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                        <div>
                            <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info btn-lg me-3 px-4">
                                <i class="fas fa-eye me-2"></i> Ver Detalles
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg px-5 shadow-sm" 
                                    style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%); border: none; color: white;">
                                <i class="fas fa-save me-2"></i> Actualizar Producto
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
    const form = document.getElementById('editForm');
    
    // Elementos del formulario
    const nombreInput = document.getElementById('Nombre');
    const precioInput = document.getElementById('Precio');
    const cantidadInput = document.getElementById('Cantidad');
    const cantidadMinimaInput = document.getElementById('Cantidad_minima');
    const cantidadMaximaInput = document.getElementById('Cantidad_maxima');
    const descripcionTextarea = document.getElementById('Descripcion');
    
    // Elementos de visualización
    const valorTotalElement = document.getElementById('valorTotal');
    const stockProgress = document.getElementById('stockProgress');
    const estadoStockText = document.getElementById('estadoStockText');
    
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
        // Inicializar estado (todos los campos tienen valores en edición)
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
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
                const max = parseInt(input.maxLength) || 200;
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

    // Función para actualizar cálculos
    function actualizarCalculos() {
        const precio = parseFloat(precioInput.value) || 0;
        const cantidad = parseInt(cantidadInput.value) || 0;
        const minima = parseInt(cantidadMinimaInput.value) || 0;
        const maxima = parseInt(cantidadMaximaInput.value) || 0;
        
        // Calcular valor total
        const valorTotal = precio * cantidad;
        valorTotalElement.textContent = `$${valorTotal.toFixed(2)}`;
        
        // Actualizar barra de progreso
        if (maxima > 0) {
            const porcentaje = Math.min((cantidad / maxima) * 100, 100);
            stockProgress.style.width = `${porcentaje}%`;
            
            // Cambiar color según nivel
            if (cantidad < minima) {
                stockProgress.className = 'progress-bar bg-danger';
            } else if (cantidad <= minima * 1.5) {
                stockProgress.className = 'progress-bar bg-warning';
            } else {
                stockProgress.className = 'progress-bar bg-success';
            }
        }
        
        // Actualizar estado del stock
        actualizarEstadoStock();
    }

    // Función para actualizar estado del stock
    function actualizarEstadoStock() {
        const cantidad = parseInt(cantidadInput.value) || 0;
        const minima = parseInt(cantidadMinimaInput.value) || 0;
        
        let icono, texto, color;
        
        if (cantidad === 0) {
            icono = 'fa-times-circle';
            texto = 'Sin Stock';
            color = 'danger';
        } else if (cantidad <= minima) {
            icono = 'fa-exclamation-triangle';
            texto = 'Stock Bajo';
            color = 'warning';
        } else {
            icono = 'fa-check-circle';
            texto = 'Stock Normal';
            color = 'success';
        }
        
        estadoStockText.textContent = texto;
        estadoStockText.className = `mb-0 fw-bold text-${color}`;
        estadoStockText.parentElement.parentElement.querySelector('i').className = `fas ${icono} text-${color} me-3 fs-5`;
    }

    // Escuchar cambios en campos de inventario
    [precioInput, cantidadInput, cantidadMinimaInput, cantidadMaximaInput].forEach(input => {
        if (input) {
            input.addEventListener('input', actualizarCalculos);
            input.addEventListener('change', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
                actualizarCalculos();
            });
        }
    });

    // Formatear precio a 2 decimales
    if (precioInput) {
        precioInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
                actualizarCalculos();
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
            
            // Validar cantidades
            const cantidad = parseInt(cantidadInput.value) || 0;
            const minima = parseInt(cantidadMinimaInput.value) || 0;
            const maxima = parseInt(cantidadMaximaInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            
            // Validar que stock máximo sea mayor que stock mínimo
            if (maxima <= minima) {
                isValid = false;
                cantidadMaximaInput.classList.add('is-invalid');
                errors.push('<i class="fas fa-sort-amount-down me-2"></i>El stock máximo debe ser mayor al mínimo');
            }
            
            // Validar que el precio sea positivo
            if (precio <= 0) {
                isValid = false;
                precioInput.classList.add('is-invalid');
                errors.push('<i class="fas fa-dollar-sign me-2"></i>El precio debe ser mayor que cero');
            }
            
            // Validar que todas las cantidades sean no negativas
            if (cantidad < 0 || minima < 0 || maxima < 0) {
                isValid = false;
                errors.push('<i class="fas fa-ban me-2"></i>Las cantidades no pueden ser negativas');
            }
            
            // Validar si hay cambios
            const originalData = {
                nombre: '{{ $producto->Nombre }}',
                descripcion: '{{ $producto->Descripcion }}',
                categoria: '{{ $producto->Categoria }}',
                precio: '{{ $producto->Precio }}',
                cantidad: '{{ $producto->Cantidad }}',
                cantidad_minima: '{{ $producto->Cantidad_minima }}',
                cantidad_maxima: '{{ $producto->Cantidad_maxima }}'
            };
            
            const currentData = {
                nombre: nombreInput.value.trim(),
                descripcion: descripcionTextarea.value.trim(),
                categoria: document.getElementById('Categoria').value,
                precio: precioInput.value,
                cantidad: cantidadInput.value,
                cantidad_minima: cantidadMinimaInput.value,
                cantidad_maxima: cantidadMaximaInput.value
            };
            
            const hasChanges = JSON.stringify(originalData) !== JSON.stringify(currentData);
            
            if (!hasChanges) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin Cambios',
                    text: 'No se han realizado modificaciones en los datos.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#ff6b6b',
                });
                return;
            }
            
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Validación',
                    html: `<div class="text-start">${errors.join('<br>')}</div>`,
                    confirmButtonText: 'Corregir',
                    confirmButtonColor: '#ff6b6b',
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
                    title: '¿Actualizar Producto?',
                    html: `
                        <div class="text-start">
                            <p><strong>Producto Actualizado:</strong></p>
                            <p><strong>Nombre:</strong> ${currentData.nombre}</p>
                            <p><strong>Precio:</strong> $${parseFloat(currentData.precio).toFixed(2)} | <strong>Stock:</strong> ${currentData.cantidad}</p>
                            <p><strong>Límites:</strong> Mín: ${currentData.cantidad_minima} | Máx: ${currentData.cantidad_maxima}</p>
                            <p><strong>Valor Total:</strong> $${(parseFloat(currentData.precio) * parseInt(currentData.cantidad)).toFixed(2)}</p>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Esta acción actualizará permanentemente los datos del producto.
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ff6b6b',
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
});
</script>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
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
    color: #ff6b6b;
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
    border-color: #ff6b6b;
    box-shadow: 0 0 0 0.25rem rgba(255, 107, 107, 0.1);
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
    border-left: 5px solid #ff6b6b;
    transition: all 0.3s ease;
}

.preview-card:hover {
    transform: translateX(5px);
}

.preview-card.updated {
    animation: pulseUpdate 1s ease;
    border-left-color: #ffa726;
}

@keyframes pulseUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Tarjetas de información */
.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
    color: #ff6b6b;
    border-color: #ff6b6b;
    transform: scale(1.1);
    box-shadow: 0 0 0 5px rgba(255, 107, 107, 0.2);
}

.step-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.step.active .step-label {
    color: #ff6b6b;
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

.btn-warning {
    background: var(--primary-gradient);
    border: none;
    position: relative;
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e65c5c 0%, #e69926 100%);
    box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
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

/* Barra de progreso */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease, background-color 0.6s ease;
}

/* Animaciones */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.btn-warning:active {
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
    
    .info-card {
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

/* Efecto especial para campos editados */
.form-control.edited {
    border-left: 4px solid #ff6b6b;
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
</style>

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection