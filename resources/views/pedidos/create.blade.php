@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Registrar Nuevo Pedido
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pedidos.store') }}" method="POST" id="pedidoForm">
                    @csrf
                    
                    <!-- Información General del Pedido -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Información General del Pedido
                            </h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cliente_id" class="form-label">Cliente *</label>
                            <select class="form-control @error('Cliente_idCliente') is-invalid @enderror" 
                                    id="cliente_id" name="Cliente_idCliente" required>
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('Cliente_idCliente') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->Nombre }} {{ $cliente->ApPaterno ?? '' }} {{ $cliente->ApMaterno ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Cliente_idCliente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="empleado_id" class="form-label">Empleado *</label>
                            <select class="form-control @error('Empleado_idEmpleado') is-invalid @enderror" 
                                    id="empleado_id" name="Empleado_idEmpleado" required>
                                <option value="">Seleccionar empleado</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" {{ old('Empleado_idEmpleado') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Empleado_idEmpleado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="fecha_entrega" class="form-label">Fecha de Entrega *</label>
                            <input type="date" class="form-control @error('Fecha_entrega') is-invalid @enderror" 
                                   id="fecha_entrega" name="Fecha_entrega" 
                                   value="{{ old('Fecha_entrega') }}" required min="{{ date('Y-m-d') }}">
                            @error('Fecha_entrega')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="hora_entrega" class="form-label">Hora de Entrega *</label>
                            <input type="time" class="form-control @error('Hora_entrega') is-invalid @enderror" 
                                   id="hora_entrega" name="Hora_entrega" 
                                   value="{{ old('Hora_entrega', '12:00') }}" required>
                            @error('Hora_entrega')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="prioridad" class="form-label">Prioridad *</label>
                            <select class="form-control @error('Prioridad') is-invalid @enderror" 
                                    id="prioridad" name="Prioridad" required>
                                <option value="">Seleccionar prioridad</option>
                                <option value="Baja" {{ old('Prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                                <option value="Media" {{ old('Prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                                <option value="Alta" {{ old('Prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                                <option value="Urgente" {{ old('Prioridad') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('Prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="lugar_entrega" class="form-label">Lugar de Entrega *</label>
                            <textarea class="form-control @error('Lugar_entrega') is-invalid @enderror" 
                                      id="lugar_entrega" name="Lugar_entrega" 
                                      rows="3" required>{{ old('Lugar_entrega') }}</textarea>
                            @error('Lugar_entrega')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Selección de Productos -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-boxes me-2"></i>Productos del Pedido
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
                                                <span class="fw-bold text-success fs-5" id="totalPedido">$0.00</span>
                                                <input type="hidden" name="Total" id="totalInput" value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del Pedido -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Resumen del Pedido
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
                        <a href="{{ route('pedidos.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-success" id="btnRegistrarPedido">
                            <i class="fas fa-save me-1"></i> Registrar Pedido
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
            <select class="form-control select-producto" name="productos[INDEX][id]" required>
                <option value="">Seleccionar producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" 
                            data-precio="{{ $producto->Precio }}"
                            data-nombre="{{ $producto->Nombre }}">
                        {{ $producto->Nombre }} <!-- SOLO EL NOMBRE -->
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" class="form-control precio-unitario" readonly>
            <!-- INPUT OCULTO PARA PRECIO UNITARIO -->
            <input type="hidden" class="input-precio-unitario" name="productos[INDEX][precio_unitario]">
        </td>
        <td>
            <input type="number" class="form-control cantidad" name="productos[INDEX][cantidad]" 
                   min="1" value="1" required>
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
    const form = document.getElementById('pedidoForm');
    const btnRegistrar = document.getElementById('btnRegistrarPedido');

    // Establecer fecha mínima como hoy
    const fechaInput = document.getElementById('fecha_entrega');
    const hoy = new Date().toISOString().split('T')[0];
    fechaInput.min = hoy;

    // Función para mostrar alerta de error
    function mostrarError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error en el formulario',
            html: mensaje,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545'
        });
    }

    // Función para mostrar confirmación de pedido
    function mostrarConfirmacionPedido(datosPedido) {
        Swal.fire({
            title: '¿Registrar Pedido?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de registrar el siguiente pedido?</p>
                    <div class="alert alert-info">
                        <strong>Resumen del Pedido:</strong><br>
                        • Total: <strong>${datosPedido.total}</strong><br>
                        • Productos: <strong>${datosPedido.totalProductos}</strong><br>
                        • Unidades: <strong>${datosPedido.totalUnidades}</strong><br>
                        • Fecha de entrega: <strong>${datosPedido.fechaEntrega}</strong><br>
                        • Prioridad: <strong>${datosPedido.prioridad}</strong>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Sí, Registrar',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
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

    // Función para agregar nueva fila de producto
    function agregarFilaProducto() {
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.fila-producto');
        
        // Reemplazar INDEX por el contador actual en todos los name attributes
        const elementosConName = fila.querySelectorAll('[name]');
        elementosConName.forEach(elemento => {
            elemento.name = elemento.name.replace(/\[INDEX\]/g, `[${contadorProductos}]`);
        });

        cuerpoTabla.appendChild(nuevaFila);
        
        // Inicializar eventos para la nueva fila
        inicializarEventosFila(fila, contadorProductos);
        
        contadorProductos++;
        return fila;
    }

    // Función para inicializar eventos de una fila
    function inicializarEventosFila(fila, index) {
        const selectProducto = fila.querySelector('.select-producto');
        const displayPrecio = fila.querySelector('.precio-unitario');
        const inputPrecioUnitario = fila.querySelector('.input-precio-unitario');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        const btnEliminar = fila.querySelector('.btn-eliminar');

        // Evento para cambio de producto
        selectProducto.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productoId = this.value;
            const precio = selectedOption ? parseFloat(selectedOption.getAttribute('data-precio')) || 0 : 0;
            const nombre = selectedOption ? selectedOption.getAttribute('data-nombre') : '';

            console.log('Producto seleccionado:', { productoId, precio, nombre, index });

            if (productoId && productoId !== '') {
                // Verificar si el producto ya fue seleccionado
                if (productosSeleccionados.has(productoId)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado al pedido.',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    limpiarFila();
                    return;
                }

                productosSeleccionados.add(productoId);
                
                // ACTUALIZAR PRECIO UNITARIO (display y hidden input)
                displayPrecio.value = '$' + precio.toFixed(2);
                inputPrecioUnitario.value = precio; // Guardar el precio real para el formulario
                
                // Calcular subtotal inicial
                calcularSubtotal();
            } else {
                limpiarFila();
                productosSeleccionados.delete(productoId);
            }
            
            actualizarResumen();
        });

        // Eventos para cantidad
        inputCantidad.addEventListener('input', function() {
            calcularSubtotal();
            actualizarResumen();
        });

        inputCantidad.addEventListener('change', function() {
            calcularSubtotal();
            actualizarResumen();
        });

        // Evento para eliminar fila
        btnEliminar.addEventListener('click', function() {
            const productoId = selectProducto.value;
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const productoNombre = selectedOption ? selectedOption.getAttribute('data-nombre') : 'Producto';
            
            Swal.fire({
                title: '¿Eliminar Producto?',
                text: `¿Está seguro de eliminar "${productoNombre}" del pedido?`,
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
                    reindexarFilas();
                }
            });
        });

        function limpiarFila() {
            displayPrecio.value = '';
            inputPrecioUnitario.value = '';
            displaySubtotal.value = '';
            inputCantidad.value = '1';
        }

        function calcularSubtotal() {
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const precio = selectedOption ? parseFloat(selectedOption.getAttribute('data-precio')) : 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const subtotal = precio * cantidad;
            
            displaySubtotal.value = '$' + subtotal.toFixed(2);
        }
    }

    // Función para reindexar filas después de eliminar
    function reindexarFilas() {
        const filas = cuerpoTabla.querySelectorAll('.fila-producto');
        contadorProductos = 0;
        
        filas.forEach((fila, index) => {
            const elementosConName = fila.querySelectorAll('[name]');
            elementosConName.forEach(elemento => {
                // Reemplazar el índice antiguo por el nuevo
                elemento.name = elemento.name.replace(/\[\d+\]/g, `[${index}]`);
            });
            contadorProductos = index + 1;
        });
    }

    // Función para actualizar el resumen general
    function actualizarResumen() {
        let totalPedido = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = cuerpoTabla.querySelectorAll('.fila-producto');
        
        filas.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
            const productoSeleccionado = selectProducto.value;
            
            if (productoSeleccionado && productoSeleccionado !== '' && selectedOption) {
                const precio = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
                const subtotal = precio * cantidad;
                
                totalPedido += subtotal;
                totalProductos++;
                totalUnidades += cantidad;
            }
        });
        
        // Actualizar displays
        document.getElementById('totalPedido').textContent = '$' + totalPedido.toFixed(2);
        document.getElementById('totalInput').value = totalPedido.toFixed(2);
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('montoTotal').textContent = '$' + totalPedido.toFixed(2);
    }

    // Evento para agregar producto
    btnAgregar.addEventListener('click', function() {
        agregarFilaProducto();
    });

    // Validación del formulario
    btnRegistrar.addEventListener('click', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const errores = [];
        
        // Validar campos básicos
        const camposRequeridos = [
            { id: 'cliente_id', mensaje: 'Debe seleccionar un cliente.' },
            { id: 'empleado_id', mensaje: 'Debe seleccionar un empleado.' },
            { id: 'fecha_entrega', mensaje: 'Debe especificar una fecha de entrega.' },
            { id: 'hora_entrega', mensaje: 'Debe especificar una hora de entrega.' },
            { id: 'prioridad', mensaje: 'Debe seleccionar una prioridad.' }
        ];
        
        camposRequeridos.forEach(campo => {
            const element = document.getElementById(campo.id);
            if (!element.value) {
                isValid = false;
                errores.push(`• ${campo.mensaje}`);
                element.classList.add('is-invalid');
            } else {
                element.classList.remove('is-invalid');
            }
        });
        
        // Validar lugar de entrega
        const lugarEntrega = document.getElementById('lugar_entrega');
        if (!lugarEntrega.value.trim()) {
            isValid = false;
            errores.push('• Debe especificar un lugar de entrega.');
            lugarEntrega.classList.add('is-invalid');
        } else {
            lugarEntrega.classList.remove('is-invalid');
        }
        
        // Validar productos
        const filasProductos = cuerpoTabla.querySelectorAll('.fila-producto');
        if (filasProductos.length === 0) {
            isValid = false;
            errores.push('• Debe agregar al menos un producto al pedido.');
        }
        
        let productosValidos = 0;
        filasProductos.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoId = selectProducto.value;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const productoNombre = selectedOption ? selectedOption.getAttribute('data-nombre') : 'Producto';
            
            if (!productoId || productoId === '') {
                isValid = false;
                selectProducto.classList.add('is-invalid');
                errores.push(`• Hay productos sin seleccionar.`);
            } else {
                selectProducto.classList.remove('is-invalid');
                
                if (cantidad < 1) {
                    isValid = false;
                    inputCantidad.classList.add('is-invalid');
                    errores.push(`• La cantidad para "${productoNombre}" debe ser al menos 1.`);
                } else {
                    inputCantidad.classList.remove('is-invalid');
                    productosValidos++;
                }
            }
        });
        
        if (productosValidos === 0 && filasProductos.length > 0) {
            isValid = false;
            errores.push('• Debe agregar al menos un producto válido al pedido.');
        }
        
        if (!isValid) {
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
            const datosPedido = {
                total: document.getElementById('montoTotal').textContent,
                totalProductos: document.getElementById('totalProductos').textContent,
                totalUnidades: document.getElementById('totalUnidades').textContent,
                fechaEntrega: document.getElementById('fecha_entrega').value,
                prioridad: document.getElementById('prioridad').options[document.getElementById('prioridad').selectedIndex].text
            };
            
            mostrarConfirmacionPedido(datosPedido);
        }
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
}

.card-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.fila-producto td {
    vertical-align: middle;
}

.btn-eliminar:hover {
    background-color: #dc3545;
    color: white;
}

.text-sm {
    font-size: 0.75rem;
}

/* Estilos para loading */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection