@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card border-0">
            <!-- Header mejorado -->
            <div class="card-header bg-gradient-primary text-white py-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <h3 class="mb-1 fw-bold">
                            <i class="fas fa-edit me-2"></i>Editar Compra
                        </h3>
                        <p class="mb-0 opacity-75">Actualiza la información de la compra en el sistema</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('compras.update', $compra->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información General de la Compra -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-info-circle text-primary fa-lg"></i>
                                </div>
                                <h5 class="text-primary mb-0 fw-bold">Información General de la Compra</h5>
                            </div>
                        </div>

                        <div class="col-md-7 mb-3">
                            <label for="Proveedor_idProveedor" class="form-label fw-semibold">
                                <i class="fas fa-truck me-1 text-muted"></i>Proveedor *
                            </label>
                            <select class="form-select shadow-sm @error('Proveedor_idProveedor') is-invalid @enderror" 
                                    id="Proveedor_idProveedor" name="Proveedor_idProveedor" required>
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" 
                                        {{ old('Proveedor_idProveedor', $compra->Proveedor_idProveedor) == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno }} - {{ $proveedor->Empresa_asociada }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Proveedor_idProveedor')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-muted"></i>Fecha de Compra
                            </label>
                            <div class="form-control bg-light bg-gradient border-0">
                                {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y h:i A') }}
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Fecha de registro de la compra
                            </div>
                        </div>
                    </div>

                    <!-- Productos de la Compra -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                        <i class="fas fa-boxes text-success fa-lg"></i>
                                    </div>
                                    <h5 class="text-success mb-0 fw-bold">Productos de la Compra</h5>
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
                                            <th width="30%">Producto</th>
                                            <th width="15%">Categoría</th>
                                            <th width="15%">Precio Unitario</th>
                                            <th width="12%">Cantidad</th>
                                            <th width="18%">Subtotal</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaProductos">
                                        <!-- Las filas de productos se agregarán aquí dinámicamente -->
                                        @foreach($compra->detalleCompras as $index => $detalle)
                                        <tr class="fila-producto" data-fila-id="fila-{{ $index }}">
                                            <td>
                                                <select class="form-control select-producto" name="productos[{{ $index }}][id]" required>
                                                    <option value="">Seleccionar producto</option>
                                                    @foreach($productos as $producto)
                                                        <option value="{{ $producto->id }}" 
                                                                data-precio="{{ $producto->Precio }}"
                                                                data-stock="{{ $producto->Cantidad }}"
                                                                data-nombre="{{ $producto->Nombre }}"
                                                                data-categoria="{{ $producto->categoria->Nombre ?? 'Sin categoría' }}"
                                                                data-categoria-id="{{ $producto->Categoria }}"
                                                                {{ $detalle->Producto == $producto->id ? 'selected' : '' }}>
                                                            {{ $producto->Nombre }} - Stock: {{ $producto->Cantidad }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="info-stock text-sm text-muted mt-1" style="display: none;"></div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control categoria" readonly 
                                                       value="{{ $detalle->producto->categoria->Nombre ?? 'Sin categoría' }}">
                                                <input type="hidden" class="categoria-id" 
                                                       value="{{ $detalle->producto->Categoria ?? '' }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control precio-unitario" 
                                                       name="productos[{{ $index }}][precio_unitario]" 
                                                       step="0.01" min="0.01" value="{{ $detalle->Precio_unitario }}" 
                                                       placeholder="0.00" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control cantidad" 
                                                       name="productos[{{ $index }}][cantidad]" 
                                                       min="1" value="{{ $detalle->Cantidad }}" required>
                                                <div class="form-text text-sm stock-info" style="display: none;"></div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control subtotal" readonly
                                                       value="${{ number_format($detalle->Subtotal, 2) }}">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">TOTAL:</td>
                                            <td colspan="2">
                                                <span class="fw-bold text-success fs-5" id="totalCompra">
                                                    ${{ number_format($compra->Total, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de la Compra -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-chart-bar text-warning fa-lg"></i>
                                </div>
                                <h5 class="text-warning mb-0 fw-bold">Resumen de la Compra</h5>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Total de Productos</label>
                            <div class="form-control bg-light bg-gradient border-0 text-center">
                                <span class="fw-bold fs-5" id="totalProductos">{{ $compra->detalleCompras->count() }}</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Unidades Totales</label>
                            <div class="form-control bg-light bg-gradient border-0 text-center">
                                <span class="fw-bold fs-5" id="totalUnidades">{{ $compra->detalleCompras->sum('Cantidad') }}</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Monto Total</label>
                            <div class="form-control bg-light bg-gradient border-0 text-center">
                                <span class="fw-bold fs-5 text-success" id="montoTotal">
                                    ${{ number_format($compra->Total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                        <div>
                            <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i> Volver al Listado
                            </a>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('compras.index') }}" class="btn btn-lg px-4" 
                               style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4 shadow" 
                                    style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border: none;">
                                <i class="fas fa-save me-2"></i> Actualizar Compra
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
                            data-nombre="{{ $producto->Nombre }}"
                            data-categoria="{{ $producto->categoria->Nombre ?? 'Sin categoría' }}"
                            data-categoria-id="{{ $producto->Categoria }}">
                        {{ $producto->Nombre }} - Stock: {{ $producto->Cantidad }}
                    </option>
                @endforeach
            </select>
            <div class="info-stock text-sm text-muted mt-1" style="display: none;"></div>
        </td>
        <td>
            <input type="text" class="form-control categoria" readonly>
            <input type="hidden" class="categoria-id">
        </td>
        <td>
            <input type="number" class="form-control precio-unitario" name="productos[][precio_unitario]" 
                   step="0.01" min="0.01" placeholder="0.00" required>
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
    let contadorProductos = {{ $compra->detalleCompras->count() }};
    const productosSeleccionados = new Map();
    const template = document.getElementById('templateFilaProducto');
    const cuerpoTabla = document.getElementById('cuerpoTablaProductos');
    const btnAgregar = document.getElementById('agregarProducto');
    const form = document.getElementById('editForm');
    const btnRegistrar = document.querySelector('button[type="submit"]');

    // Inicializar productos seleccionados con los datos existentes
    document.querySelectorAll('.fila-producto').forEach(fila => {
        const selectProducto = fila.querySelector('.select-producto');
        const productoId = selectProducto.value;
        const filaId = fila.getAttribute('data-fila-id');
        if (productoId) {
            productosSeleccionados.set(filaId, productoId);
        }
    });

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
    function mostrarConfirmacionActualizacion(datosCompra) {
        Swal.fire({
            title: '¿Actualizar Compra?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de actualizar la siguiente compra?</p>
                    <div class="alert alert-info">
                        <strong>Resumen de la Compra:</strong><br>
                        • Total: <strong>${datosCompra.total}</strong><br>
                        • Productos: <strong>${datosCompra.totalProductos}</strong><br>
                        • Unidades: <strong>${datosCompra.totalUnidades}</strong>
                    </div>
                    <p class="text-warning mb-0"><small>⚠️ El stock será restaurado y actualizado automáticamente</small></p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Sí, Actualizar',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
            confirmButtonColor: '#0d6efd',
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
                btnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...';
                
                // Enviar formulario
                form.submit();
            }
        });
    }

    // Función para agregar nueva fila de producto
    function agregarFilaProducto() {
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.fila-producto');
        const filaId = 'fila-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        fila.setAttribute('data-fila-id', filaId);
        
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
        inicializarEventosFila(fila, filaId);
        
        return fila;
    }

    // Función para verificar si un producto ya está en uso en otra fila
    function productoYaEnUso(productoId, filaActualId) {
        for (let [filaId, producto] of productosSeleccionados) {
            if (filaId !== filaActualId && producto === productoId) {
                return true;
            }
        }
        return false;
    }

    // Función para inicializar eventos de una fila
    function inicializarEventosFila(fila, filaId) {
        const selectProducto = fila.querySelector('.select-producto');
        const inputPrecioUnitario = fila.querySelector('.precio-unitario');
        const displayCategoria = fila.querySelector('.categoria');
        const inputCategoriaId = fila.querySelector('.categoria-id');
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
            const stock = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
            const nombre = opcionSeleccionada.getAttribute('data-nombre');
            const categoria = opcionSeleccionada.getAttribute('data-categoria');
            const categoriaId = opcionSeleccionada.getAttribute('data-categoria-id');
            const productoId = this.value;

            if (productoId) {
                // Verificar si el producto ya fue seleccionado en OTRA fila
                if (productoYaEnUso(productoId, filaId)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado a la compra en otra fila.',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    limpiarFila(fila);
                    return;
                }

                // Remover el producto anterior del mapa si existía
                if (productoActual) {
                    productosSeleccionados.delete(filaId);
                }

                // Agregar el nuevo producto al mapa
                productosSeleccionados.set(filaId, productoId);
                productoActual = productoId;
                
                // Establecer valores por defecto
                inputPrecioUnitario.value = precio || '';
                displayCategoria.value = categoria;
                inputCategoriaId.value = categoriaId;
                
                // Mostrar información de stock
                infoStock.style.display = 'block';
                infoStock.textContent = `Stock actual: ${stock}`;
                infoStock.className = 'text-sm text-info mt-1';
                
                // Configurar cantidad máxima
                const maxPermitido = 10000;
                inputCantidad.max = maxPermitido;
                inputCantidad.setAttribute('data-stock', stock);
                
                // Mostrar información de stock debajo del input de cantidad
                stockInfo.style.display = 'block';
                stockInfo.textContent = `Máximo: ${maxPermitido}`;
                stockInfo.className = 'form-text text-sm text-info';
                
                // Calcular subtotal inicial
                calcularSubtotal(fila);
                
                // Enfocar el input de precio unitario
                inputPrecioUnitario.focus();
            } else {
                // Si se selecciona "Seleccionar producto", limpiar y remover del mapa
                if (productoActual) {
                    productosSeleccionados.delete(filaId);
                    productoActual = '';
                }
                limpiarFila(fila);
            }
            
            actualizarResumen();
        });

        // Eventos para precio unitario
        inputPrecioUnitario.addEventListener('input', function() {
            calcularSubtotal(fila);
            actualizarResumen();
        });

        // Eventos para cantidad
        inputCantidad.addEventListener('focus', function() {
            this.select();
        });

        inputCantidad.addEventListener('input', function() {
            let cantidad = parseInt(this.value) || 0;
            const maxPermitido = 10000;
            
            if (cantidad < 1) {
                cantidad = 1;
                this.value = cantidad;
            } else if (cantidad > maxPermitido) {
                cantidad = maxPermitido;
                this.value = cantidad;
            }
            
            calcularSubtotal(fila);
            actualizarResumen();
        });

        inputCantidad.addEventListener('blur', function() {
            let cantidad = parseInt(this.value) || 0;
            if (cantidad < 1) {
                this.value = 1;
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
                text: `¿Está seguro de eliminar "${productoNombre}" de la compra?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remover del mapa de productos seleccionados
                    productosSeleccionados.delete(filaId);
                    productoActual = '';
                    
                    fila.remove();
                    actualizarResumen();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto eliminado',
                        text: 'El producto ha sido removido de la compra',
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
            inputPrecioUnitario.value = '';
            displayCategoria.value = '';
            inputCategoriaId.value = '';
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
            const precioUnitario = parseFloat(inputPrecioUnitario.value) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const subtotal = precioUnitario * cantidad;
            
            displaySubtotal.value = '$' + subtotal.toFixed(2);
        }
    }

    // Función para actualizar el resumen general
    function actualizarResumen() {
        let totalCompra = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = cuerpoTabla.querySelectorAll('.fila-producto');
        
        filas.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputPrecioUnitario = fila.querySelector('.precio-unitario');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoSeleccionado = selectProducto.value;
            const precioUnitario = parseFloat(inputPrecioUnitario.value) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            
            if (productoSeleccionado && precioUnitario > 0) {
                const subtotal = precioUnitario * cantidad;
                
                totalCompra += subtotal;
                totalProductos++;
                totalUnidades += cantidad;
            }
        });
        
        // Actualizar displays
        document.getElementById('totalCompra').textContent = '$' + totalCompra.toFixed(2);
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('montoTotal').textContent = '$' + totalCompra.toFixed(2);
    }

    // Evento para agregar producto
    btnAgregar.addEventListener('click', function() {
        agregarFilaProducto();
    });

    // Validación del formulario mejorada
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const proveedorId = document.getElementById('Proveedor_idProveedor').value;
        const filasProductos = cuerpoTabla.querySelectorAll('.fila-producto');
        const errores = [];
        
        // Validar proveedor
        if (!proveedorId) {
            isValid = false;
            errores.push('• Debe seleccionar un proveedor.');
            document.getElementById('Proveedor_idProveedor').classList.add('is-invalid');
        } else {
            document.getElementById('Proveedor_idProveedor').classList.remove('is-invalid');
        }
        
        // Validar que haya al menos un producto
        if (filasProductos.length === 0) {
            isValid = false;
            errores.push('• Debe agregar al menos un producto a la compra.');
        }
        
        // Validar que todos los productos tengan datos válidos
        let productosValidos = 0;
        filasProductos.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputPrecioUnitario = fila.querySelector('.precio-unitario');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoId = selectProducto.value;
            const precioUnitario = parseFloat(inputPrecioUnitario.value) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const productoNombre = selectProducto.options[selectProducto.selectedIndex]?.getAttribute('data-nombre') || 'Producto';
            
            if (!productoId) {
                isValid = false;
                selectProducto.classList.add('is-invalid');
                errores.push(`• Hay productos sin seleccionar.`);
            } else {
                selectProducto.classList.remove('is-invalid');
                
                if (precioUnitario <= 0) {
                    isValid = false;
                    inputPrecioUnitario.classList.add('is-invalid');
                    errores.push(`• El precio unitario para "<strong>${productoNombre}</strong>" debe ser mayor a 0.`);
                } else {
                    inputPrecioUnitario.classList.remove('is-invalid');
                }
                
                if (cantidad < 1) {
                    isValid = false;
                    inputCantidad.classList.add('is-invalid');
                    errores.push(`• La cantidad para "<strong>${productoNombre}</strong>" debe ser al menos 1.`);
                } else if (cantidad > 10000) {
                    isValid = false;
                    inputCantidad.classList.add('is-invalid');
                    errores.push(`• La cantidad para "<strong>${productoNombre}</strong>" no puede ser mayor a 10,000.`);
                } else {
                    inputCantidad.classList.remove('is-invalid');
                    productosValidos++;
                }
            }
        });
        
        if (productosValidos === 0 && filasProductos.length > 0) {
            isValid = false;
            errores.push('• Debe agregar al menos un producto válido a la compra.');
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
            const datosCompra = {
                total: document.getElementById('montoTotal').textContent,
                totalProductos: document.getElementById('totalProductos').textContent,
                totalUnidades: document.getElementById('totalUnidades').textContent
            };
            
            mostrarConfirmacionActualizacion(datosCompra);
        }
    });

    // Limpiar validaciones al interactuar con los campos
    document.getElementById('Proveedor_idProveedor').addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });

    // Inicializar eventos para las filas existentes
    document.querySelectorAll('.fila-producto').forEach(fila => {
        const filaId = fila.getAttribute('data-fila-id');
        inicializarEventosFila(fila, filaId);
    });

    // Inicializar el resumen
    actualizarResumen();
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

.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
}

.form-control, .form-select {
    border: 1px solid #e0e0e0;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    transform: translateY(-2px);
}

.btn {
    border-radius: 0.75rem;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.bg-light.bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border: 1px solid #dee2e6;
}

.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}

.alert {
    border-radius: 0.75rem;
    border: none;
    min-width: 400px;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.08) !important;
}

.border-top {
    border-top: 2px solid rgba(0, 0, 0, 0.05) !important;
}

.text-primary {
    color: #0d6efd !important;
}

.text-success {
    color: #198754 !important;
}

.text-warning {
    color: #ffc107 !important;
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

#totalCompra, #montoTotal {
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
.col-md-9 {
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