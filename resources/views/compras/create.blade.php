@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12 col-xl-11"> <!-- HECHO MÁS ANCHO -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Registrar Nueva Compra
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('compras.store') }}" method="POST" id="compraForm">
                    @csrf
                    
                    <!-- Información General de la Compra -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Información General de la Compra
                            </h5>
                        </div>

                        <div class="col-md-8 mb-3"> <!-- HECHO MÁS ANCHO -->
                            <label for="Proveedor_idProveedor" class="form-label">Proveedor *</label>
                            <select class="form-control @error('Proveedor_idProveedor') is-invalid @enderror" 
                                    id="Proveedor_idProveedor" name="Proveedor_idProveedor" required>
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" 
                                        {{ old('Proveedor_idProveedor') == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno }} - {{ $proveedor->Empresa_asociada }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Proveedor_idProveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
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
                                    <i class="fas fa-boxes me-2"></i>Productos de la Compra
                                </h5>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="agregarProducto" disabled>
                                    <i class="fas fa-plus me-1"></i> Agregar Producto
                                </button>
                            </div>
                            <div class="alert alert-info" id="alertaProveedor">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="mensajeProveedor">Primero seleccione un proveedor para poder agregar productos</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaProductos">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Producto</th> <!-- MÁS ANCHO -->
                                            <th width="15%">Categoría</th> <!-- MÁS ANCHO -->
                                            <th width="12%">Precio Compra</th>
                                            <th width="10%">% Ganancia</th>
                                            <th width="12%">Precio Venta</th>
                                            <th width="10%">Cantidad</th>
                                            <th width="11%">Subtotal</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaProductos">
                                        <!-- Las filas de productos se agregarán aquí dinámicamente -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="6" class="text-end fw-bold">TOTAL:</td>
                                            <td colspan="2">
                                                <span class="fw-bold text-success fs-5" id="totalCompra">$0.00</span>
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
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Resumen de la Compra
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
                        <a href="{{ route('compras.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-success" id="btnRegistrarCompra">
                            <i class="fas fa-save me-1"></i> Registrar Compra
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
            </select>
            <div class="info-stock text-sm text-muted mt-1" style="display: none;"></div>
        </td>
        <td>
            <input type="text" class="form-control categoria" readonly>
            <input type="hidden" class="categoria-id">
        </td>
        <td>
            <input type="number" class="form-control precio-compra" name="productos[][precio_unitario]" 
                   step="0.01" min="0.01" value="" placeholder="0.00" required>
        </td>
        <td>
            <input type="number" class="form-control porcentaje-ganancia" 
                   step="0.1" min="0" value="30" required>
        </td>
        <td>
            <input type="number" class="form-control precio-venta" name="productos[][precio_venta]" 
                   step="0.01" min="0.01" readonly value="">
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

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ JavaScript cargado correctamente');
    
    // Variables globales
    let contadorProductos = 0;
    const productosSeleccionados = new Set();
    const template = document.getElementById('templateFilaProducto');
    const cuerpoTabla = document.getElementById('cuerpoTablaProductos');
    const btnAgregar = document.getElementById('agregarProducto');
    const selectProveedor = document.getElementById('Proveedor_idProveedor');
    const alertaProveedor = document.getElementById('alertaProveedor');
    const mensajeProveedor = document.getElementById('mensajeProveedor');

    // Datos de productos y categorías disponibles
    const productosDisponibles = @json($productos);
    const categoriasDisponibles = @json($categorias);
    
    console.log('📦 Productos disponibles:', productosDisponibles);
    console.log('📂 Categorías disponibles:', categoriasDisponibles);

    // Función para obtener categorías del proveedor
    function obtenerCategoriasDelProveedor(proveedorId) {
        return categoriasDisponibles.filter(categoria => {
            return categoria.Proveedor == proveedorId;
        });
    }

    // Función para obtener productos por categorías del proveedor
    function obtenerProductosDelProveedor(proveedorId) {
        // Primero obtenemos las categorías del proveedor
        const categoriasProveedor = obtenerCategoriasDelProveedor(proveedorId);
        const categoriasIds = categoriasProveedor.map(cat => cat.id);
        
        console.log('📂 Categorías del proveedor:', categoriasProveedor);
        console.log('🎯 IDs de categorías:', categoriasIds);
        
        // Filtramos productos que pertenezcan a estas categorías
        const productosFiltrados = productosDisponibles.filter(producto => {
            return categoriasIds.includes(parseInt(producto.Categoria));
        });
        
        console.log('📦 Productos filtrados por categorías:', productosFiltrados);
        return productosFiltrados;
    }

    // Función para obtener nombre de categoría por ID
    function obtenerNombreCategoria(categoriaId) {
        const categoria = categoriasDisponibles.find(cat => cat.id == categoriaId);
        return categoria ? categoria.Nombre : 'Sin categoría';
    }

    // Función para verificar y habilitar productos
    function verificarYHabilitarProductos() {
        const proveedorId = selectProveedor.value;
        console.log('🔍 Verificando proveedor:', proveedorId);
        
        if (!proveedorId) {
            btnAgregar.disabled = true;
            alertaProveedor.style.display = 'block';
            mensajeProveedor.textContent = 'Primero seleccione un proveedor para poder agregar productos';
            return;
        }
        
        // Obtener productos del proveedor a través de sus categorías
        const productosDelProveedor = obtenerProductosDelProveedor(proveedorId);
        
        console.log('🎯 Productos del proveedor encontrados:', productosDelProveedor.length);
        
        if (productosDelProveedor.length === 0) {
            btnAgregar.disabled = true;
            alertaProveedor.style.display = 'block';
            mensajeProveedor.textContent = 'El proveedor seleccionado no tiene productos asociados';
            console.log('❌ Botón deshabilitado: Proveedor sin productos');
        } else {
            btnAgregar.disabled = false;
            alertaProveedor.style.display = 'none';
            console.log('✅ Botón HABILITADO: Proveedor tiene productos');
        }
    }

    // Función para poblar select de productos
    function poblarSelectProductos(selectElement) {
        const proveedorId = selectProveedor.value;
        
        if (!proveedorId) {
            selectElement.innerHTML = '<option value="">Seleccionar producto</option>';
            return;
        }
        
        // Obtener productos del proveedor
        const productosFiltrados = obtenerProductosDelProveedor(proveedorId);
        
        const valorActual = selectElement.value;
        selectElement.innerHTML = '<option value="">Seleccionar producto</option>';
        
        productosFiltrados.forEach(producto => {
            const option = document.createElement('option');
            option.value = producto.id;
            option.textContent = `${producto.Nombre} - Stock: ${producto.Cantidad}`;
            option.setAttribute('data-precio-venta', producto.Precio);
            option.setAttribute('data-stock', producto.Cantidad);
            option.setAttribute('data-nombre', producto.Nombre);
            option.setAttribute('data-categoria-id', producto.Categoria);
            option.setAttribute('data-categoria-nombre', obtenerNombreCategoria(producto.Categoria));
            
            selectElement.appendChild(option);
        });
        
        if (valorActual && productosFiltrados.some(p => p.id == valorActual)) {
            selectElement.value = valorActual;
        }
    }

    // Función para agregar nueva fila de producto
    function agregarFilaProducto() {
        console.log('➕ Agregando nueva fila de producto');
        
        if (!selectProveedor.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un proveedor',
                text: 'Primero debe seleccionar un proveedor para poder agregar productos.',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.fila-producto');
        
        const inputs = fila.querySelectorAll('select, input');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('[]', `[${contadorProductos}]`);
            }
        });

        cuerpoTabla.appendChild(nuevaFila);
        contadorProductos++;
        
        const selectProducto = fila.querySelector('.select-producto');
        poblarSelectProductos(selectProducto);
        inicializarEventosFila(fila);
        
        console.log('✅ Fila agregada correctamente');
    }

    // Función para inicializar eventos de una fila
    function inicializarEventosFila(fila) {
        const selectProducto = fila.querySelector('.select-producto');
        const inputPrecioCompra = fila.querySelector('.precio-compra');
        const inputPorcentajeGanancia = fila.querySelector('.porcentaje-ganancia');
        const inputPrecioVenta = fila.querySelector('.precio-venta');
        const displayCategoria = fila.querySelector('.categoria');
        const inputCategoriaId = fila.querySelector('.categoria-id');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        const infoStock = fila.querySelector('.info-stock');
        const btnEliminar = fila.querySelector('.btn-eliminar');

        selectProducto.addEventListener('change', function() {
            const productoId = this.value;
            const opcionSeleccionada = this.options[this.selectedIndex];
            
            console.log('🔄 Producto seleccionado:', productoId);
            console.log('📋 Opción seleccionada:', opcionSeleccionada);
            
            if (productoId) {
                if (productosSeleccionados.has(productoId)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado a la compra.',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    return;
                }
                
                productosSeleccionados.add(productoId);
                
                const precioVentaBase = parseFloat(opcionSeleccionada.getAttribute('data-precio-venta')) || 0;
                const stock = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
                const categoriaId = opcionSeleccionada.getAttribute('data-categoria-id');
                const categoriaNombre = opcionSeleccionada.getAttribute('data-categoria-nombre');
                
                console.log('🏷️ Categoría del producto:', {
                    id: categoriaId,
                    nombre: categoriaNombre
                });
                
                // ACTUALIZAR CATEGORÍA - CORREGIDO
                displayCategoria.value = categoriaNombre;
                inputCategoriaId.value = categoriaId;
                
                if (precioVentaBase > 0) {
                    inputPrecioVenta.value = precioVentaBase.toFixed(2);
                    const precioCompraCalculado = precioVentaBase / 1.3;
                    inputPrecioCompra.value = precioCompraCalculado.toFixed(2);
                }
                
                infoStock.style.display = 'block';
                infoStock.textContent = `Stock actual: ${stock}`;
                infoStock.className = 'text-sm text-info mt-1';
                
                calcularSubtotal();
                
                console.log(`✅ Producto seleccionado: ${opcionSeleccionada.getAttribute('data-nombre')}`);
                console.log(`🏷️ Categoría mostrada: ${categoriaNombre}`);
            } else {
                if (selectProducto.dataset.productoId) {
                    productosSeleccionados.delete(selectProducto.dataset.productoId);
                }
                displayCategoria.value = '';
                inputCategoriaId.value = '';
                inputPrecioCompra.value = '';
                inputPrecioVenta.value = '';
                infoStock.style.display = 'none';
                displaySubtotal.value = '';
            }
            
            selectProducto.dataset.productoId = productoId;
            actualizarResumen();
        });

        function calcularPrecios() {
            const precioCompra = parseFloat(inputPrecioCompra.value) || 0;
            const porcentajeGanancia = parseFloat(inputPorcentajeGanancia.value) || 0;
            
            if (precioCompra > 0 && porcentajeGanancia > 0) {
                const precioVenta = precioCompra * (1 + porcentajeGanancia / 100);
                inputPrecioVenta.value = precioVenta.toFixed(2);
            }
            
            calcularSubtotal();
        }

        function calcularSubtotal() {
            const precioCompra = parseFloat(inputPrecioCompra.value) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const subtotal = precioCompra * cantidad;
            
            displaySubtotal.value = '$' + subtotal.toFixed(2);
            actualizarResumen();
        }

        inputPrecioCompra.addEventListener('input', calcularPrecios);
        inputPorcentajeGanancia.addEventListener('input', calcularPrecios);
        inputCantidad.addEventListener('input', calcularSubtotal);

        btnEliminar.addEventListener('click', function() {
            const productoId = selectProducto.dataset.productoId;
            if (productoId) {
                productosSeleccionados.delete(productoId);
            }
            fila.remove();
            actualizarResumen();
        });
    }

    // Función para actualizar resumen
    function actualizarResumen() {
        let totalCompra = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = cuerpoTabla.querySelectorAll('.fila-producto');
        
        filas.forEach(fila => {
            const inputPrecioCompra = fila.querySelector('.precio-compra');
            const inputCantidad = fila.querySelector('.cantidad');
            const selectProducto = fila.querySelector('.select-producto');
            
            const precioCompra = parseFloat(inputPrecioCompra.value) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            const productoSeleccionado = selectProducto.value;
            
            if (productoSeleccionado && precioCompra > 0) {
                const subtotal = precioCompra * cantidad;
                totalCompra += subtotal;
                totalProductos++;
                totalUnidades += cantidad;
            }
        });
        
        document.getElementById('totalCompra').textContent = '$' + totalCompra.toFixed(2);
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('montoTotal').textContent = '$' + totalCompra.toFixed(2);
    }

    // Eventos principales
    selectProveedor.addEventListener('change', function() {
        console.log('🔄 Proveedor cambiado:', this.value);
        
        cuerpoTabla.innerHTML = '';
        productosSeleccionados.clear();
        contadorProductos = 0;
        
        verificarYHabilitarProductos();
        actualizarResumen();
    });

    btnAgregar.addEventListener('click', agregarFilaProducto);

    document.getElementById('btnRegistrarCompra').addEventListener('click', function() {
        const proveedorId = selectProveedor.value;
        const filasProductos = cuerpoTabla.querySelectorAll('.fila-producto');
        
        if (!proveedorId) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Debe seleccionar un proveedor.' });
            return;
        }
        
        if (filasProductos.length === 0) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Debe agregar al menos un producto.' });
            return;
        }

        // Validar que todos los productos tengan datos válidos
        let productosValidos = true;
        let errores = [];
        
        filasProductos.forEach((fila, index) => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputPrecioCompra = fila.querySelector('.precio-compra');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoNombre = selectProducto.options[selectProducto.selectedIndex]?.text || `Producto ${index + 1}`;
            
            if (!selectProducto.value) {
                productosValidos = false;
                errores.push(`• El ${productoNombre} no tiene un producto seleccionado`);
            }
            
            if (!inputPrecioCompra.value || parseFloat(inputPrecioCompra.value) <= 0) {
                productosValidos = false;
                errores.push(`• El precio de compra para ${productoNombre} debe ser mayor a 0`);
            }
            
            if (!inputCantidad.value || parseInt(inputCantidad.value) < 1) {
                productosValidos = false;
                errores.push(`• La cantidad para ${productoNombre} debe ser al menos 1`);
            }
        });
        
        if (!productosValidos) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                html: `
                    <div class="text-start">
                        <p>Por favor, corrija los siguientes errores:</p>
                        <div class="alert alert-danger text-start">
                            ${errores.join('<br>')}
                        </div>
                    </div>
                `,
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Confirmar envío
        Swal.fire({
            title: '¿Registrar Compra?',
            html: `
                <div class="text-start">
                    <p>¿Está seguro de registrar la compra?</p>
                    <div class="alert alert-info">
                        <strong>Resumen:</strong><br>
                        • Total: <strong>${document.getElementById('montoTotal').textContent}</strong><br>
                        • Productos: <strong>${document.getElementById('totalProductos').textContent}</strong><br>
                        • Unidades: <strong>${document.getElementById('totalUnidades').textContent}</strong>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('compraForm').submit();
            }
        });
    });

    // Inicialización
    verificarYHabilitarProductos();
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

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Hacer la tabla más ancha */
.table {
    font-size: 0.95rem;
}

.table th {
    font-weight: 600;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Asegurar que los inputs sean legibles */
.form-control {
    font-size: 0.9rem;
}

.categoria {
    background-color: #f8f9fa;
    font-weight: 500;
    color: #495057;
}
</style>
@endsection