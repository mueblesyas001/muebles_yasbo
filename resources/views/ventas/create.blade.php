@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-cash-register me-2"></i>Registrar Nueva Venta
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
                    @csrf
                    
                    <!-- Información General de la Venta -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Información General de la Venta
                            </h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="empleado_id" class="form-label">Empleado *</label>
                            <select class="form-control @error('empleado_id') is-invalid @enderror" 
                                    id="empleado_id" name="empleado_id" required>
                                <option value="">Seleccionar empleado</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" 
                                        {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno}}
                                    </option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha y Hora</label>
                            <div class="form-control bg-light">
                                {{ \Carbon\Carbon::now('America/Mexico_City')->format('d/m/Y h:i A') }}
                            </div>
                            <div class="form-text">Fecha automática del sistema</div>
                        </div>
                    </div>
                    <!-- Selección de Productos -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-boxes me-2"></i>Productos de la Venta
                                </h5>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="agregarProducto">
                                    <i class="fas fa-plus me-1"></i> Agregar Producto
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaProductos">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="35%">Producto</th>
                                            <th width="20%">Precio Unitario</th>
                                            <th width="20%">Cantidad</th>
                                            <th width="15%">Subtotal</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaProductos">
                                        <!-- Las filas de productos se agregarán aquí dinámicamente -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                            <td colspan="2">
                                                <span class="fw-bold text-success fs-5" id="totalVenta">$0.00</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de la Venta -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Resumen de la Venta
                            </h5>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total de Productos</label>
                            <div class="form-control bg-light text-center">
                                <span class="fw-bold fs-5" id="totalProductos">0</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unidades Totales</label>
                            <div class="form-control bg-light text-center">
                                <span class="fw-bold fs-5" id="totalUnidades">0</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Monto Total</label>
                            <div class="form-control bg-light text-center">
                                <span class="fw-bold fs-5 text-success" id="montoTotal">$0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-success" id="btnRegistrarVenta">
                            <i class="fas fa-save me-1"></i> Registrar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Template para fila de producto (oculto) -->
<template id="templateFilaProducto">
    <tr class="fila-producto">
        <td>
            <select class="form-control select-producto" name="productos[][id]" required>
                <option value="">Seleccionar producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" 
                            data-precio="{{ $producto->Precio }}"
                            data-stock="{{ $producto->Cantidad }}"
                            data-nombre="{{ $producto->Nombre }}">
                        {{ $producto->Nombre }} - Stock: {{ $producto->Cantidad }} - ${{ number_format($producto->Precio, 2) }}
                    </option>
                @endforeach
            </select>
            <div class="info-stock text-sm text-muted mt-1" style="display: none;"></div>
        </td>
        <td>
            <input type="text" class="form-control precio-unitario" readonly>
        </td>
        <td>
            <input type="number" class="form-control cantidad" name="productos[][cantidad]" 
                   min="1" value="1" required>
            <div class="form-text text-sm stock-info" style="display: none;"></div>
        </td>
        <td>
            <input type="text" class="form-control subtotal" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<!-- Incluir SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let contadorProductos = 0;
    const productosSeleccionados = new Set();
    const template = document.getElementById('templateFilaProducto');
    const cuerpoTabla = document.getElementById('cuerpoTablaProductos');
    const btnAgregar = document.getElementById('agregarProducto');
    const form = document.getElementById('ventaForm');
    const btnRegistrar = document.getElementById('btnRegistrarVenta');

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

    // Función para mostrar confirmación de venta
    function mostrarConfirmacionVenta(datosVenta) {
        Swal.fire({
            title: '¿Registrar Venta?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de registrar la siguiente venta?</p>
                    <div class="alert alert-info">
                        <strong>Resumen de la Venta:</strong><br>
                        • Total: <strong>${datosVenta.total}</strong><br>
                        • Productos: <strong>${datosVenta.totalProductos}</strong><br>
                        • Unidades: <strong>${datosVenta.totalUnidades}</strong>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Sí, Registrar',
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
                // Deshabilitar el botón para evitar doble envío
                btnRegistrar.disabled = true;
                btnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Registrando...';
                
                // Enviar formulario
                form.submit();
            }
        });
    }

    // Función para validar cantidad con mejor manejo de eventos
    function validarCantidad(inputCantidad) {
        const stock = parseInt(inputCantidad.getAttribute('data-stock')) || 0;
        let cantidad = parseInt(inputCantidad.value) || 0;
        const maxPermitido = Math.min(stock, 1000);
        
        // Si está vacío o no es número, establecer 1
        if (isNaN(cantidad) || cantidad < 1) {
            inputCantidad.value = 1;
            cantidad = 1;
        }
        
        // Si excede el máximo permitido
        if (cantidad > maxPermitido) {
            inputCantidad.value = maxPermitido;
            cantidad = maxPermitido;
            
            // Mostrar alerta solo si el usuario está interactuando activamente
            if (document.activeElement === inputCantidad) {
                setTimeout(() => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cantidad ajustada',
                        text: `La cantidad ha sido ajustada al máximo permitido: ${maxPermitido}`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }, 100);
            }
        }
        
        return cantidad;
    }

    // Función para agregar nueva fila de producto
    function agregarFilaProducto() {
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.fila-producto');
        
        // Actualizar índices de los nombres de los campos
        const selects = fila.querySelectorAll('select, input');
        selects.forEach(select => {
            if (select.name) {
                select.name = select.name.replace('[]', `[${contadorProductos}]`);
            }
        });

        cuerpoTabla.appendChild(nuevaFila);
        contadorProductos++;
        
        // Inicializar eventos para la nueva fila
        inicializarEventosFila(fila);
        
        return fila;
    }

    // Función para inicializar eventos de una fila
    function inicializarEventosFila(fila) {
        const selectProducto = fila.querySelector('.select-producto');
        const displayPrecio = fila.querySelector('.precio-unitario');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        const infoStock = fila.querySelector('.info-stock');
        const stockInfo = fila.querySelector('.stock-info');
        const btnEliminar = fila.querySelector('.btn-eliminar');

        // Evento para cambio de producto
        selectProducto.addEventListener('change', function() {
            const opcionSeleccionada = this.options[this.selectedIndex];
            const precio = opcionSeleccionada.getAttribute('data-precio');
            const stock = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
            const nombre = opcionSeleccionada.getAttribute('data-nombre');
            const productoId = this.value;

            if (productoId) {
                // Verificar si el producto ya fue seleccionado
                if (productosSeleccionados.has(productoId)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado a la venta.',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    limpiarFila(fila);
                    return;
                }

                productosSeleccionados.add(productoId);
                
                // Actualizar precios
                displayPrecio.value = '$' + parseFloat(precio).toFixed(2);
                
                // Mostrar información de stock
                infoStock.style.display = 'block';
                infoStock.textContent = `Stock disponible: ${stock}`;
                infoStock.className = 'text-sm text-success mt-1';
                
                // Configurar cantidad máxima
                const maxPermitido = Math.min(stock, 1000);
                inputCantidad.max = maxPermitido;
                inputCantidad.setAttribute('data-stock', stock);
                
                // Mostrar información de stock debajo del input de cantidad
                stockInfo.style.display = 'block';
                stockInfo.textContent = `Máximo: ${maxPermitido}`;
                stockInfo.className = 'form-text text-sm text-info';
                
                // Calcular subtotal inicial
                calcularSubtotal(fila);
            } else {
                limpiarFila(fila);
                productosSeleccionados.delete(productoId);
            }
            
            actualizarResumen();
        });

        // Eventos mejorados para cantidad
        inputCantidad.addEventListener('input', function() {
            const cantidad = validarCantidad(this);
            calcularSubtotal(fila);
            actualizarResumen();
        });

        inputCantidad.addEventListener('blur', function() {
            const cantidad = validarCantidad(this);
            calcularSubtotal(fila);
            actualizarResumen();
        });

        inputCantidad.addEventListener('change', function() {
            const cantidad = validarCantidad(this);
            calcularSubtotal(fila);
            actualizarResumen();
        });

        // Evento para eliminar fila con confirmación
        btnEliminar.addEventListener('click', function() {
            const productoId = selectProducto.value;
            const productoNombre = selectProducto.options[selectProducto.selectedIndex]?.getAttribute('data-nombre') || 'Producto';
            
            Swal.fire({
                title: '¿Eliminar Producto?',
                text: `¿Está seguro de eliminar "${productoNombre}" de la venta?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    productosSeleccionados.delete(productoId);
                    fila.remove();
                    actualizarResumen();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto eliminado',
                        text: 'El producto ha sido removido de la venta',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });
        });

        function limpiarFila(fila) {
            displayPrecio.value = '';
            displaySubtotal.value = '';
            inputCantidad.value = '1';
            inputCantidad.removeAttribute('data-stock');
            inputCantidad.removeAttribute('max');
            infoStock.style.display = 'none';
            
            const stockInfo = fila.querySelector('.stock-info');
            if (stockInfo) {
                stockInfo.style.display = 'none';
            }
        }

        function calcularSubtotal(fila) {
            const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
            const precio = opcionSeleccionada ? parseFloat(opcionSeleccionada.getAttribute('data-precio')) : 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const subtotal = precio * cantidad;
            
            displaySubtotal.value = '$' + subtotal.toFixed(2);
        }
    }

    // Función para actualizar el resumen general
    function actualizarResumen() {
        let totalVenta = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = cuerpoTabla.querySelectorAll('.fila-producto');
        
        filas.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
            const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
            const productoSeleccionado = selectProducto.value;
            
            if (productoSeleccionado && opcionSeleccionada) {
                const precio = parseFloat(opcionSeleccionada.getAttribute('data-precio')) || 0;
                const subtotal = precio * cantidad;
                
                totalVenta += subtotal;
                totalProductos++;
                totalUnidades += cantidad;
            }
        });
        
        // Actualizar displays
        document.getElementById('totalVenta').textContent = '$' + totalVenta.toFixed(2);
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('montoTotal').textContent = '$' + totalVenta.toFixed(2);
    }

    // Evento para agregar producto
    btnAgregar.addEventListener('click', function() {
        agregarFilaProducto();
    });

    // Validación del formulario mejorada
    btnRegistrar.addEventListener('click', function() {
        let isValid = true;
        const empleadoId = document.getElementById('empleado_id').value;
        const filasProductos = cuerpoTabla.querySelectorAll('.fila-producto');
        const errores = [];
        
        // Validar empleado
        if (!empleadoId) {
            isValid = false;
            errores.push('• Debe seleccionar un empleado.');
            document.getElementById('empleado_id').classList.add('is-invalid');
        } else {
            document.getElementById('empleado_id').classList.remove('is-invalid');
        }
        
        // Validar que haya al menos un producto
        if (filasProductos.length === 0) {
            isValid = false;
            errores.push('• Debe agregar al menos un producto a la venta.');
        }
        
        // Validar que todos los productos tengan cantidad válida
        let productosValidos = 0;
        filasProductos.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoId = selectProducto.value;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const stock = parseInt(inputCantidad.getAttribute('data-stock')) || 0;
            const productoNombre = selectProducto.options[selectProducto.selectedIndex]?.getAttribute('data-nombre') || 'Producto';
            
            if (!productoId) {
                isValid = false;
                selectProducto.classList.add('is-invalid');
                errores.push(`• Hay productos sin seleccionar.`);
            } else {
                selectProducto.classList.remove('is-invalid');
                
                if (cantidad < 1) {
                    isValid = false;
                    inputCantidad.classList.add('is-invalid');
                    errores.push(`• La cantidad para "<strong>${productoNombre}</strong>" debe ser al menos 1.`);
                } else if (cantidad > stock) {
                    isValid = false;
                    inputCantidad.classList.add('is-invalid');
                    errores.push(`• Stock insuficiente para "<strong>${productoNombre}</strong>". Stock disponible: ${stock}`);
                } else if (cantidad > 1000) {
                    isValid = false;
                    inputCantidad.classList.add('is-invalid');
                    errores.push(`• La cantidad para "<strong>${productoNombre}</strong>" no puede ser mayor a 1000.`);
                } else {
                    inputCantidad.classList.remove('is-invalid');
                    productosValidos++;
                }
            }
        });
        
        if (productosValidos === 0 && filasProductos.length > 0) {
            isValid = false;
            errores.push('• Debe agregar al menos un producto válido a la venta.');
        }
        
        if (!isValid) {
            // Mostrar todos los errores con SweetAlert2
            const mensajeErrores = `
                <div class="text-start">
                    <p class="mb-2">Por favor, corrija los siguientes errores:</p>
                    <div class="alert alert-danger text-start">
                        ${[...new Set(errores)].join('<br>')}
                    </div>
                </div>
            `;
            mostrarError(mensajeErrores);
        } else {
            // Mostrar confirmación con SweetAlert2
            const datosVenta = {
                total: document.getElementById('montoTotal').textContent,
                totalProductos: document.getElementById('totalProductos').textContent,
                totalUnidades: document.getElementById('totalUnidades').textContent
            };
            
            mostrarConfirmacionVenta(datosVenta);
        }
    });

    // Limpiar validaciones al interactuar con los campos
    document.getElementById('empleado_id').addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });

    // Agregar primera fila al cargar
    agregarFilaProducto();
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

.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    min-height: 38px;
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    justify-content: center;
}

.text-primary {
    color: #28a745 !important;
}

.border-top {
    border-top: 2px solid #e9ecef !important;
}

h5 {
    font-weight: 600;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    font-size: 0.8rem;
    padding: 0.75rem 0.5rem;
}

.table td {
    padding: 0.75rem 0.5rem;
}

.text-sm {
    font-size: 0.75rem;
}

.fila-producto td {
    vertical-align: middle;
}

.btn-eliminar {
    border: none;
    padding: 0.25rem 0.5rem;
}

.btn-eliminar:hover {
    background-color: #dc3545;
    color: white;
}

#totalVenta, #montoTotal {
    font-size: 1.1rem;
}

.info-stock {
    font-size: 0.7rem;
}

.stock-info {
    font-size: 0.7rem;
    margin-top: 0.25rem;
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
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .fila-producto td {
        padding: 0.5rem 0.25rem;
    }
    
    .card-header {
        padding: 1rem 1.5rem;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
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

/* Ajustes específicos para el formulario */
.col-lg-10.col-xl-8 {
    padding: 0 15px;
}
</style>
@endsection