@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card border-0 shadow-lg">
            <!-- Header mejorado -->
            <div class="card-header bg-gradient-warning text-white py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-cash-register fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <h3 class="mb-1 fw-bold">
                            <i class="fas fa-edit me-2"></i>Editar Venta #{{ $venta->id }}
                        </h3>
                        <p class="mb-0 opacity-75">Actualiza la información de la venta en el sistema</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('ventas.update', $venta->id) }}" method="POST" id="ventaForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información General de la Venta -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-info-circle text-warning fa-lg"></i>
                                </div>
                                <h5 class="text-warning mb-0 fw-bold">Información General de la Venta</h5>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="empleado_id" class="form-label fw-semibold">
                                <i class="fas fa-user-tie me-1 text-muted"></i>Empleado *
                            </label>
                            <select class="form-select shadow-sm @error('empleado_id') is-invalid @enderror" 
                                    id="empleado_id" name="empleado_id" required>
                                <option value="">Seleccionar empleado</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" 
                                        {{ old('empleado_id', $venta->Empleado_idEmpleado) == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-muted"></i>Fecha y Hora Original
                            </label>
                            <div class="form-control bg-light bg-gradient border-0">
                                {{ \Carbon\Carbon::parse($venta->Fecha)->setTimezone('America/Mexico_City')->format('d/m/Y h:i A') }}
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Fecha de registro de la venta
                            </div>
                        </div>
                    </div>

                    <!-- Productos de la Venta -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                        <i class="fas fa-boxes text-success fa-lg"></i>
                                    </div>
                                    <h5 class="text-success mb-0 fw-bold">Productos de la Venta</h5>
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm" id="agregarProducto">
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
                                                <span class="fw-bold text-success fs-5" id="totalVenta">
                                                    ${{ number_format($venta->Total, 2) }}
                                                </span>
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
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-chart-bar text-info fa-lg"></i>
                                </div>
                                <h5 class="text-info mb-0 fw-bold">Resumen de la Venta</h5>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Total de Productos</label>
                            <div class="form-control bg-light bg-gradient border-0 text-center">
                                <span class="fw-bold fs-5" id="totalProductos">{{ $venta->detalleVentas->count() }}</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Unidades Totales</label>
                            <div class="form-control bg-light bg-gradient border-0 text-center">
                                <span class="fw-bold fs-5" id="totalUnidades">{{ $venta->detalleVentas->sum('Cantidad') }}</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Monto Total</label>
                            <div class="form-control bg-light bg-gradient border-0 text-center">
                                <span class="fw-bold fs-5 text-success" id="montoTotal">
                                    ${{ number_format($venta->Total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                        <div>
                            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i> Volver al Listado
                            </a>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('ventas.index') }}" class="btn btn-lg px-4" 
                               style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg px-4 shadow" 
                                    style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); border: none; color: white;">
                                <i class="fas fa-save me-2"></i> Actualizar Venta
                            </button>
                        </div>
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
    const btnActualizar = document.querySelector('button[type="submit"]');

    // Almacenar los productos originales para calcular stock disponible correctamente
    const productosOriginales = new Map();
    @foreach($venta->detalleVentas as $detalle)
        productosOriginales.set({{ $detalle->Producto }}, {{ $detalle->Cantidad }});
    @endforeach

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

    // Función para mostrar confirmación de actualización
    function mostrarConfirmacionActualizacion(datosVenta) {
        Swal.fire({
            title: '¿Actualizar Venta?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de actualizar la siguiente venta?</p>
                    <div class="alert alert-info">
                        <strong>Resumen de la Venta:</strong><br>
                        • Total: <strong>${datosVenta.total}</strong><br>
                        • Productos: <strong>${datosVenta.totalProductos}</strong><br>
                        • Unidades: <strong>${datosVenta.totalUnidades}</strong>
                    </div>
                    <p class="text-warning mb-0"><small>⚠️ El stock será restaurado y actualizado automáticamente</small></p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Sí, Actualizar',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
            confirmButtonColor: '#ffc107',
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
                btnActualizar.disabled = true;
                btnActualizar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...';
                
                // Enviar formulario
                form.submit();
            }
        });
    }

    // Cargar productos existentes de la venta
    function cargarProductosExistentes() {
        @foreach($venta->detalleVentas as $detalle)
            const fila = agregarFilaProducto();
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            
            // Seleccionar el producto
            selectProducto.value = '{{ $detalle->Producto }}';
            
            // Forzar la actualización del stock disponible (sumando la cantidad original)
            setTimeout(() => {
                const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
                if (opcionSeleccionada) {
                    const stockOriginal = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
                    const cantidadOriginal = productosOriginales.get({{ $detalle->Producto }}) || 0;
                    const stockDisponible = stockOriginal + cantidadOriginal;
                    
                    // Actualizar el atributo data-stock con el stock disponible real
                    opcionSeleccionada.setAttribute('data-stock', stockDisponible);
                    
                    // Disparar el evento change para cargar precios y stock
                    const event = new Event('change');
                    selectProducto.dispatchEvent(event);
                }
            }, 100);
            
            // Establecer la cantidad después de un pequeño delay
            setTimeout(() => {
                inputCantidad.value = '{{ $detalle->Cantidad }}';
                
                // Disparar el evento input para calcular subtotales
                const inputEvent = new Event('input');
                inputCantidad.dispatchEvent(inputEvent);
            }, 200);
        @endforeach
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

    // Función para verificar si un producto ya está en uso en otra fila
    function productoYaEnUso(productoId) {
        return productosSeleccionados.has(productoId);
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

        // Guardar referencia al producto actual de esta fila
        let productoActual = '';

        // Evento para cambio de producto
        selectProducto.addEventListener('change', function() {
            const opcionSeleccionada = this.options[this.selectedIndex];
            const precio = opcionSeleccionada.getAttribute('data-precio');
            let stock = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
            const nombre = opcionSeleccionada.getAttribute('data-nombre');
            const productoId = this.value;

            if (productoId) {
                // Verificar si el producto ya fue seleccionado en OTRA fila
                if (productoYaEnUso(productoId) && productoActual !== productoId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado a la venta en otra fila.',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    limpiarFila(fila);
                    return;
                }

                // Remover el producto anterior del set si existía
                if (productoActual) {
                    productosSeleccionados.delete(productoActual);
                }

                // Agregar el nuevo producto al set
                productosSeleccionados.add(productoId);
                productoActual = productoId;
                
                // Si es un producto que ya estaba en la venta original, ajustar el stock disponible
                const cantidadOriginal = productosOriginales.get(parseInt(productoId)) || 0;
                if (cantidadOriginal > 0) {
                    stock += cantidadOriginal;
                }
                
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
                inputCantidad.setAttribute('data-stock-original', stock);
                
                // Mostrar información de stock debajo del input de cantidad
                stockInfo.style.display = 'block';
                stockInfo.textContent = `Máximo: ${maxPermitido}`;
                stockInfo.className = 'form-text text-sm text-info';
                
                // Calcular subtotal inicial
                calcularSubtotal(fila);
                
                // Enfocar el input de cantidad
                inputCantidad.focus();
                inputCantidad.select();
            } else {
                // Si se selecciona "Seleccionar producto", limpiar y remover del set
                if (productoActual) {
                    productosSeleccionados.delete(productoActual);
                    productoActual = '';
                }
                limpiarFila(fila);
            }
            
            actualizarResumen();
        });

        // Evento para cambio de cantidad
        inputCantidad.addEventListener('focus', function() {
            this.select();
        });

        inputCantidad.addEventListener('input', function() {
            let cantidad = parseInt(this.value) || 0;
            const stock = parseInt(this.getAttribute('data-stock-original')) || parseInt(this.getAttribute('data-stock')) || 0;
            const maxPermitido = Math.min(stock, 1000);
            
            // Validar que la cantidad sea un número válido
            if (isNaN(cantidad) || cantidad < 1) {
                this.value = 1;
                cantidad = 1;
            }
            
            // Validar que no exceda el stock disponible
            if (cantidad > maxPermitido) {
                this.value = maxPermitido;
                cantidad = maxPermitido;
                
                // Mostrar alerta solo si el usuario intenta poner más del máximo
                if (cantidad !== 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock insuficiente',
                        text: `Cantidad excede el límite. Máximo permitido: ${maxPermitido}`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
            
            // Actualizar la información de stock
            const stockInfo = this.parentElement.querySelector('.stock-info');
            if (stockInfo) {
                stockInfo.textContent = `Máximo: ${maxPermitido}`;
                if (cantidad > stock) {
                    stockInfo.className = 'form-text text-sm text-danger';
                } else {
                    stockInfo.className = 'form-text text-sm text-info';
                }
            }
            
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
                    // Remover del set de productos seleccionados
                    if (productoActual) {
                        productosSeleccionados.delete(productoActual);
                        productoActual = '';
                    }
                    
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
            inputCantidad.removeAttribute('data-stock-original');
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
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
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
        
        // Validar que todos los productos tengan datos válidos
        let productosValidos = 0;
        filasProductos.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoId = selectProducto.value;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const stock = parseInt(inputCantidad.getAttribute('data-stock-original')) || parseInt(inputCantidad.getAttribute('data-stock')) || 0;
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
            
            mostrarConfirmacionActualizacion(datosVenta);
        }
    });

    // Limpiar validaciones al interactuar con los campos
    document.getElementById('empleado_id').addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });

    // Inicializar eventos para las filas existentes
    document.querySelectorAll('.fila-producto').forEach(fila => {
        inicializarEventosFila(fila);
    });

    // Cargar productos existentes al iniciar
    setTimeout(() => {
        cargarProductosExistentes();
    }, 300);
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
    border-radius: 1rem;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 2rem;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
}

.form-control, .form-select {
    border: 1px solid #e0e0e0;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    transform: translateY(-2px);
}

.btn {
    border-radius: 0.75rem;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.bg-light.bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border: 1px solid #dee2e6;
}

.text-warning {
    color: #ffc107 !important;
}

.text-success {
    color: #198754 !important;
}

.text-info {
    color: #0dcaf0 !important;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #e0e0e0;
}

/* Estilos para tabla */
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    font-size: 0.85rem;
    padding: 0.75rem 0.5rem;
}

.table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.9rem;
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
    
    .card-header h3 {
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

/* Mejorar inputs de números */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Ajustes para tabla más ancha */
.table {
    width: 100%;
}

.table th, .table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection