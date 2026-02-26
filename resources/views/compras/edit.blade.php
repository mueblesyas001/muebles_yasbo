@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <!-- Fondo decorativo -->
    <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-xxl-block" style="
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 86, 179, 0.03) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 25% 0);
        z-index: 0;
    "></div>

    <div class="position-relative z-1">
        <div class="row justify-content-center g-0">
            <div class="col-12 col-xxl-11">
                <!-- Header Superior Mejorado -->
                <div class="header-glass py-4 px-4 px-lg-5 mb-4" style="
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(10px);
                    border-bottom: 1px solid rgba(0,0,0,0.08);
                ">
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon" style="
                                width: 60px;
                                height: 60px;
                                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(0, 123, 255, 0.25);
                                animation: float 6s ease-in-out infinite;
                            ">
                                <i class="fas fa-edit fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Editar Compra #{{ $compra->id }}
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-edit me-1 text-warning"></i>
                                    Modifique los detalles de la compra y sus productos
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
                                border-radius: 12px;
                                padding: 8px 16px;
                                font-size: 0.9rem;
                                border: 1px solid #dee2e6;
                                transition: all 0.3s ease;
                            ">
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-md-inline">Volver</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Principal -->
                <div class="main-card mx-3 mx-lg-4 mb-5" style="
                    background: white;
                    border-radius: 24px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.03);
                    overflow: hidden;
                ">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Barra de Progreso General -->
                        <div class="progress-overview mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="fas fa-tasks me-2 text-primary"></i>
                                    Progreso de la Edición
                                </h5>
                                <div class="progress-percentage">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" id="progressPercentage">
                                        0% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #007bff, #0056b3); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">0 de 2 pasos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Revise y actualice la información
                                </small>
                            </div>
                        </div>

                        <form id="editForm" action="{{ route('compras.update', $compra->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <!-- Sección 1: Información General -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información General</h3>
                                            <p class="section-subtitle mb-0">Datos básicos de la compra</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #007bff, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Proveedor -->
                                    <div class="col-md-8">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Proveedor</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="select-wrapper" data-required="true" id="proveedorWrapper">
                                                <div class="select-icon">
                                                    <i class="fas fa-truck"></i>
                                                </div>
                                                <select class="select-field @error('Proveedor_idProveedor') is-invalid @enderror" 
                                                        id="Proveedor_idProveedor" 
                                                        name="Proveedor_idProveedor" 
                                                        required>
                                                    <option value="" disabled hidden>Seleccione un proveedor...</option>
                                                    @foreach($proveedores as $proveedor)
                                                        <option value="{{ $proveedor->id }}" 
                                                                {{ old('Proveedor_idProveedor', $compra->Proveedor_idProveedor) == $proveedor->id ? 'selected' : '' }}
                                                                data-empresa="{{ $proveedor->Empresa_asociada ?? 'Sin empresa' }}">
                                                            {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno ? ' ' . $proveedor->ApMaterno : '' }} - {{ $proveedor->Empresa_asociada }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="select-decoration" style="background: linear-gradient(90deg, #007bff, #0056b3);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Seleccione el proveedor de la compra
                                                </div>
                                            </div>
                                            
                                            @error('Proveedor_idProveedor')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Fecha y Hora -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Fecha de Compra</span>
                                            </label>
                                            
                                            <div class="info-card">
                                                <div class="info-card-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="info-card-content">
                                                    <span class="info-card-label">Fecha registro</span>
                                                    <span class="info-card-value" id="fechaCompra">
                                                        {{ \Carbon\Carbon::parse($compra->Fecha_compra)->format('d/m/Y h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-clock"></i>
                                                    Fecha de registro original
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Productos -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Productos de la Compra</h3>
                                            <p class="section-subtitle mb-0">Modifique los productos adquiridos</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #28a745, transparent);"></div>
                                </div>

                                <!-- Alerta de proveedor -->
                                <div class="proveedor-alert mb-4" id="proveedorAlert" style="display: none;">
                                    <div class="alert-content">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="proveedorAlertMessage"></span>
                                    </div>
                                </div>

                                <!-- Botón Agregar Producto -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="mb-0 fw-bold text-muted">
                                        <i class="fas fa-list me-2"></i>
                                        Lista de productos
                                    </h6>
                                    <button type="button" class="btn btn-primary btn-add-product" id="agregarProducto">
                                        <i class="fas fa-plus me-2"></i>
                                        Agregar Producto
                                    </button>
                                </div>

                                <!-- Tabla de Productos (COMPACTADA - CON PRECIO VENTA Y PORCENTAJE) -->
                                <div class="table-responsive">
                                    <table class="table table-modern table-compact" id="tablaProductos">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%">Producto</th>
                                                <th style="width: 12%">Categoría</th>
                                                <th style="width: 10%">P.Compra</th>
                                                <th style="width: 7%">%</th>
                                                <th style="width: 10%">P.Venta</th>
                                                <th style="width: 7%">Cant</th>
                                                <th style="width: 12%">Subtotal</th>
                                                <th style="width: 5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpoTablaProductos">
                                            <!-- Las filas se agregarán aquí dinámicamente -->
                                            @foreach($compra->detalleCompras as $index => $detalle)
                                            <tr class="product-row" data-fila-id="fila-{{ $index }}" data-producto-id="{{ $detalle->Producto }}">
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <div class="select-wrapper compact" style="margin-bottom: 0;">
                                                            <div class="select-icon compact">
                                                                <i class="fas fa-box"></i>
                                                            </div>
                                                            <select class="select-field compact select-producto" name="productos[{{ $index }}][id]" required>
                                                                <option value="" disabled hidden>Seleccionar...</option>
                                                                @foreach($productos as $producto)
                                                                    <option value="{{ $producto->id }}" 
                                                                            {{ $detalle->Producto == $producto->id ? 'selected' : '' }}
                                                                            data-precio-venta="{{ $producto->Precio }}"
                                                                            data-nombre="{{ $producto->Nombre }}"
                                                                            data-categoria-id="{{ $producto->Categoria }}"
                                                                            data-categoria-nombre="{{ $producto->categoria->Nombre ?? 'Sin categoría' }}">
                                                                        {{ $producto->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="select-arrow compact">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <input type="text" class="form-control-static compact categoria" value="{{ $detalle->producto->categoria->Nombre ?? 'Sin categoría' }}" readonly>
                                                        <input type="hidden" class="categoria-id" value="{{ $detalle->producto->Categoria ?? '' }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <div class="input-wrapper compact">
                                                            <div class="input-icon compact">
                                                                <i class="fas fa-dollar-sign"></i>
                                                            </div>
                                                            <input type="number" class="input-field compact precio-compra" name="productos[{{ $index }}][precio_unitario]" 
                                                                   step="0.01" min="0.01" value="{{ $detalle->Precio_unitario }}" placeholder="0.00" required>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <div class="input-wrapper compact">
                                                            <div class="input-icon compact">
                                                                <i class="fas fa-percent"></i>
                                                            </div>
                                                            <input type="number" class="input-field compact porcentaje-ganancia" 
                                                                   step="0.1" min="0" value="{{ $detalle->porcentaje_ganancia ?? 30 }}" required>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <div class="input-wrapper compact">
                                                            <div class="input-icon compact">
                                                                <i class="fas fa-tag"></i>
                                                            </div>
                                                            <input type="number" class="input-field compact precio-venta" name="productos[{{ $index }}][precio_venta]" 
                                                                   step="0.01" min="0.01" readonly value="{{ $detalle->producto->Precio ?? 0 }}" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <div class="input-wrapper compact">
                                                            <div class="input-icon compact">
                                                                <i class="fas fa-sort-numeric-up"></i>
                                                            </div>
                                                            <input type="number" class="input-field compact cantidad" name="productos[{{ $index }}][cantidad]" 
                                                                   min="1" value="{{ $detalle->Cantidad }}" required>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group-enhanced mb-0 compact">
                                                        <div class="input-wrapper compact">
                                                            <div class="input-icon compact">
                                                                <i class="fas fa-calculator"></i>
                                                            </div>
                                                            <input type="text" class="input-field compact subtotal" readonly placeholder="$0.00" value="${{ number_format($detalle->Subtotal, 2) }}">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn-delete-row compact">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-total">
                                                <td colspan="6" class="text-end fw-bold">TOTAL:</td>
                                                <td colspan="2">
                                                    <span class="total-amount" id="totalCompra">${{ number_format($compra->Total, 2) }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Mensaje cuando no hay productos -->
                                <div class="empty-state text-center py-5" id="emptyState" style="{{ $compra->detalleCompras->count() > 0 ? 'display: none;' : '' }}">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <h5>No hay productos agregados</h5>
                                    <p class="text-muted">Agregue productos a la compra</p>
                                </div>
                            </div>

                            <!-- Sección 3: Resumen -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Resumen de la Compra</h3>
                                            <p class="section-subtitle mb-0">Detalles y totales actualizados</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #ffc107, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Total Productos</span>
                                                <span class="summary-value" id="totalProductos">{{ $compra->detalleCompras->count() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                                <i class="fas fa-sort-amount-up"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Unidades Totales</span>
                                                <span class="summary-value" id="totalUnidades">{{ $compra->detalleCompras->sum('Cantidad') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Monto Total</span>
                                                <span class="summary-value" id="montoTotal">${{ number_format($compra->Total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones del Formulario -->
                            <div class="form-actions mt-5 pt-4 border-top">
                                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-4">
                                    <div class="form-info">
                                        <div class="form-stats">
                                            <div class="stat-item">
                                                <i class="fas fa-asterisk text-danger"></i>
                                                <span>Campos obligatorios</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span id="validFieldsCount">0/2</span> secciones
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>
                                            Cancelar
                                        </a>
                                        <button type="button" class="btn btn-success btn-submit" id="btnActualizarCompra" style="
                                            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                                            border: none;
                                            padding: 12px 32px;
                                            border-radius: 12px;
                                            font-weight: 700;
                                            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                            position: relative;
                                            overflow: hidden;
                                        ">
                                            <span class="submit-content">
                                                <i class="fas fa-save me-2"></i>
                                                Actualizar Compra
                                            </span>
                                            <span class="submit-loader">
                                                <i class="fas fa-spinner fa-spin me-2"></i>
                                                Actualizando...
                                            </span>
                                            <div class="submit-shine"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template para fila de producto (COMPACTADO) -->
<template id="templateFilaProducto">
    <tr class="product-row">
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <div class="select-wrapper compact" style="margin-bottom: 0;">
                    <div class="select-icon compact">
                        <i class="fas fa-box"></i>
                    </div>
                    <select class="select-field compact select-producto" name="productos[][id]" required>
                        <option value="" disabled selected hidden>Seleccionar...</option>
                    </select>
                    <div class="select-arrow compact">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <input type="text" class="form-control-static compact categoria" readonly>
                <input type="hidden" class="categoria-id">
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <div class="input-wrapper compact">
                    <div class="input-icon compact">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <input type="number" class="input-field compact precio-compra" name="productos[][precio_unitario]" 
                           step="0.01" min="0.01" placeholder="0.00" required>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <div class="input-wrapper compact">
                    <div class="input-icon compact">
                        <i class="fas fa-percent"></i>
                    </div>
                    <input type="number" class="input-field compact porcentaje-ganancia" 
                           step="0.1" min="0" value="30" required>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <div class="input-wrapper compact">
                    <div class="input-icon compact">
                        <i class="fas fa-tag"></i>
                    </div>
                    <input type="number" class="input-field compact precio-venta" name="productos[][precio_venta]" 
                           step="0.01" min="0.01" readonly placeholder="0.00">
                </div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <div class="input-wrapper compact">
                    <div class="input-icon compact">
                        <i class="fas fa-sort-numeric-up"></i>
                    </div>
                    <input type="number" class="input-field compact cantidad" name="productos[][cantidad]" 
                           min="1" value="1" required>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0 compact">
                <div class="input-wrapper compact">
                    <div class="input-icon compact">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <input type="text" class="input-field compact subtotal" readonly placeholder="$0.00">
                </div>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn-delete-row compact">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-color: #ef4444;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --info-color: #007bff;
    --border-radius: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
    --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
}

/* Animaciones */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes shine {
    0% { transform: rotate(30deg) translateX(-100%); }
    100% { transform: rotate(30deg) translateX(100%); }
}

/* Form Sections */
.form-section {
    animation: slideIn 0.6s ease-out;
    margin-bottom: 2.5rem;
}

.section-header {
    margin-bottom: 2rem;
}

.section-icon-badge {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-md);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    letter-spacing: -0.5px;
}

.section-subtitle {
    color: #6b7280;
    font-size: 0.9rem;
}

.section-divider {
    height: 2px;
    border-radius: 2px;
    margin-top: 1rem;
}

/* Enhanced Form Groups */
.form-group-enhanced {
    margin-bottom: 1.5rem;
}

.form-group-enhanced.compact {
    margin-bottom: 0;
}

.form-label-enhanced {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.label-text {
    flex: 1;
}

.label-required {
    color: var(--danger-color);
    font-weight: 700;
}

/* Input Wrapper Compact */
.input-wrapper,
.select-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper.compact,
.select-wrapper.compact {
    border-radius: 8px;
    border-width: 1px;
}

.input-wrapper:focus-within,
.select-wrapper:focus-within {
    border-color: #007bff;
    box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    transform: translateY(-2px);
}

.input-wrapper.error,
.select-wrapper.error {
    border-color: var(--danger-color);
    animation: shake 0.5s;
}

.input-wrapper.valid,
.select-wrapper.valid {
    border-color: var(--success-color);
}

.input-icon,
.select-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #007bff;
    font-size: 1.1rem;
    z-index: 2;
}

.input-icon.compact,
.select-icon.compact {
    left: 8px;
    font-size: 0.9rem;
}

.input-field,
.select-field {
    width: 100%;
    padding: 20px 20px 20px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
    transition: var(--transition);
}

.input-field.compact,
.select-field.compact {
    padding: 10px 10px 10px 35px;
    font-size: 0.85rem;
}

.select-field {
    appearance: none;
    cursor: pointer;
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    transition: transform 0.3s ease;
    pointer-events: none;
}

.select-arrow.compact {
    right: 8px;
    font-size: 0.8rem;
}

.select-wrapper:focus-within .select-arrow {
    transform: translateY(-50%) rotate(180deg);
}

.input-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.input-wrapper:focus-within .input-decoration,
.select-wrapper:focus-within .input-decoration {
    transform: scaleX(1);
}

.input-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    padding: 0 4px;
}

.input-hint {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Info Card */
.info-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #007bff;
}

.info-card-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(0, 86, 179, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #007bff;
    font-size: 1.2rem;
}

.info-card-content {
    display: flex;
    flex-direction: column;
}

.info-card-label {
    font-size: 0.8rem;
    color: #6b7280;
}

.info-card-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
}

/* Proveedor Alert */
.proveedor-alert {
    padding: 16px;
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 12px;
    color: #856404;
    animation: slideIn 0.4s ease;
}

.proveedor-alert .alert-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.proveedor-alert i {
    font-size: 1.2rem;
}

/* Table Modern Compact */
.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 4px;
}

.table-modern thead th {
    background: #f8f9fa;
    padding: 8px 6px;
    font-weight: 600;
    color: #4b5563;
    font-size: 0.8rem;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}

.table-modern tbody tr {
    background: white;
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.table-modern tbody tr:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.table-modern tbody td {
    padding: 6px 4px;
    vertical-align: middle;
    border: none;
    font-size: 0.85rem;
}

.table-modern tbody td:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.table-modern tbody td:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

.table-total {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table-total td {
    padding: 10px;
    font-weight: 700;
    color: #1f2937;
    font-size: 0.9rem;
}

.total-amount {
    font-size: 1.1rem;
    color: #28a745;
    font-weight: 700;
}

/* Form Controls Static Compact */
.form-control-static.compact {
    padding: 8px 6px;
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-weight: 500;
    color: #1f2937;
    width: 100%;
    font-size: 0.8rem;
    text-align: center;
    height: 36px;
}

/* Delete Button Compact */
.btn-delete-row.compact {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: transparent;
    border: 1px solid #e5e7eb;
    color: #ef4444;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.btn-delete-row.compact:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
    transform: scale(1.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #9ca3af;
}

/* Summary Cards */
.summary-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    transition: var(--transition);
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.summary-content {
    display: flex;
    flex-direction: column;
}

.summary-label {
    font-size: 0.9rem;
    color: #6b7280;
}

.summary-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
}

/* Add Product Button */
.btn-add-product {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    color: white;
    transition: var(--transition);
}

.btn-add-product:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
}

.btn-add-product:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #6c757d;
}

/* Form Actions */
.form-actions {
    position: relative;
}

.form-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Submit Button */
.btn-submit {
    position: relative;
    overflow: hidden;
    min-width: 200px;
}

.submit-content,
.submit-loader {
    display: flex;
    align-items: center;
    transition: opacity 0.3s ease;
}

.submit-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
}

.btn-submit.loading .submit-content {
    opacity: 0;
}

.btn-submit.loading .submit-loader {
    opacity: 1;
}

.submit-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 100%
    );
    transform: rotate(30deg);
    animation: shine 3s infinite;
}

/* Error States */
.error-message {
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    animation: slideIn 0.3s ease;
}

.is-invalid {
    border-color: var(--danger-color) !important;
}

/* Progress Overview */
.progress-overview {
    background: #f9fafb;
    padding: 20px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
}

.progress-percentage .badge {
    font-size: 0.9rem;
    font-weight: 600;
}

/* Info Footer */
.info-footer {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    margin: 0 1rem;
    border: 1px solid rgba(0,0,0,0.05);
}

.info-item {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-submit,
    .btn-add-product {
        width: 100%;
        justify-content: center;
    }

    .progress-overview {
        padding: 15px;
    }

    .form-stats {
        flex-wrap: wrap;
        justify-content: center;
    }

    .summary-card {
        margin-bottom: 15px;
    }

    .table-modern {
        font-size: 0.75rem;
    }

    .table-modern tbody td {
        padding: 4px 2px;
    }

    .btn-delete-row.compact {
        width: 24px;
        height: 24px;
    }
}
</style>

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Sistema de Notificaciones
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.maxNotifications = 3;
    }

    show(message, type = 'info', title = null) {
        if (this.notifications.length >= this.maxNotifications) {
            const oldestNotification = this.notifications.shift();
            this.removeNotification(oldestNotification);
        }

        const notification = this.createNotification(message, type, title);
        this.notifications.push(notification);
        
        setTimeout(() => {
            this.removeNotification(notification);
        }, 4000);

        return notification;
    }

    createNotification(message, type, title) {
        const titles = {
            success: '¡Excelente!',
            error: '¡Oops! Algo salió mal',
            warning: '¡Atención!',
            info: 'Información'
        };

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const notificationTitle = title || titles[type] || 'Notificación';

        const notification = document.createElement('div');
        notification.className = `toast-notification toast-${type}`;
        notification.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon-wrapper">
                    <i class="fas ${icons[type]}"></i>
                </div>
                <div class="toast-text">
                    <div class="toast-title">${notificationTitle}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.closest('.toast-notification').classList.add('hiding'); setTimeout(() => this.closest('.toast-notification').remove(), 300)">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress"></div>
            </div>
        `;

        document.body.appendChild(notification);
        return notification;
    }

    removeNotification(notification) {
        if (!notification) return;
        
        notification.classList.add('hiding');
        setTimeout(() => {
            notification.remove();
            this.notifications = this.notifications.filter(n => n !== notification);
        }, 300);
    }

    showValidationError(errors) {
        const errorList = errors.map(error => 
            `<span style="display: inline-block; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px; margin: 2px; font-size: 0.8rem;">${error}</span>`
        ).join(' ');
        
        this.show(
            `<div style="text-align: left; margin-top: 5px;">${errorList}</div>`,
            'warning',
            'Errores en el formulario'
        );
    }

    showSuccess(message) {
        this.show(message, 'success', '¡Operación exitosa!');
    }

    showError(message) {
        this.show(message, 'error', 'Error en la operación');
    }

    showWarning(message) {
        this.show(message, 'warning', 'Advertencia');
    }

    showInfo(message) {
        this.show(message, 'info', 'Información');
    }
}

// Instancia global del notification manager
const notifier = new NotificationManager();

class EditarCompraManager {
    constructor() {
        this.productosSeleccionados = new Set();
        this.productosDisponibles = @json($productos);
        this.categoriasDisponibles = @json($categorias);
        
        // Inicializar el conjunto con los productos ya seleccionados
        @foreach($compra->detalleCompras as $detalle)
            this.productosSeleccionados.add('{{ $detalle->Producto }}');
        @endforeach
        
        console.log('📦 Productos disponibles:', this.productosDisponibles);
        console.log('📂 Categorías disponibles:', this.categoriasDisponibles);
        console.log('🔢 Productos seleccionados:', Array.from(this.productosSeleccionados));
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateProgress();
    }

    setupEventListeners() {
        this.selectProveedor = document.getElementById('Proveedor_idProveedor');
        this.btnAgregar = document.getElementById('agregarProducto');
        this.btnActualizar = document.getElementById('btnActualizarCompra');
        this.cuerpoTabla = document.getElementById('cuerpoTablaProductos');
        this.emptyState = document.getElementById('emptyState');
        this.proveedorAlert = document.getElementById('proveedorAlert');
        this.proveedorAlertMessage = document.getElementById('proveedorAlertMessage');

        if (this.selectProveedor) {
            this.selectProveedor.addEventListener('change', () => this.handleProveedorChange());
        }

        if (this.btnAgregar) {
            this.btnAgregar.addEventListener('click', () => this.agregarFilaProducto());
        }

        if (this.btnActualizar) {
            this.btnActualizar.addEventListener('click', (e) => this.handleActualizar(e));
        }

        // Inicializar eventos para filas existentes
        document.querySelectorAll('.product-row').forEach(fila => {
            this.inicializarEventosFila(fila);
        });

        this.verificarProveedorSeleccionado();
    }

    obtenerCategoriasDelProveedor(proveedorId) {
        console.log('🔍 Buscando categorías para proveedor ID:', proveedorId);
        console.log('Todas las categorías:', this.categoriasDisponibles);
        
        const proveedorIdNum = parseInt(proveedorId);
        
        const categoriasFiltradas = this.categoriasDisponibles.filter(categoria => {
            console.log('Categoría:', categoria.Nombre, 'Proveedor:', categoria.Proveedor, 'Comparación:', categoria.Proveedor == proveedorIdNum);
            return parseInt(categoria.Proveedor) === proveedorIdNum;
        });
        
        console.log('📂 Categorías del proveedor encontradas:', categoriasFiltradas);
        return categoriasFiltradas;
    }

    obtenerProductosDelProveedor(proveedorId) {
        console.log('🔍 Buscando productos para proveedor ID:', proveedorId);
        
        const categoriasProveedor = this.obtenerCategoriasDelProveedor(proveedorId);
        const categoriasIds = categoriasProveedor.map(cat => cat.id);
        
        console.log('🎯 IDs de categorías:', categoriasIds);
        
        if (categoriasIds.length === 0) {
            console.log('⚠️ No hay categorías para este proveedor');
            return [];
        }
        
        const productosFiltrados = this.productosDisponibles.filter(producto => {
            const productoCatNum = parseInt(producto.Categoria);
            return categoriasIds.includes(productoCatNum);
        });
        
        console.log('📦 Productos filtrados por categorías:', productosFiltrados);
        return productosFiltrados;
    }

    obtenerNombreCategoria(categoriaId) {
        const categoria = this.categoriasDisponibles.find(cat => cat.id == categoriaId);
        return categoria ? categoria.Nombre : 'Sin categoría';
    }

    handleProveedorChange() {
        console.log('🔄 Proveedor cambiado:', this.selectProveedor.value);
        
        // Actualizar selects de productos en todas las filas
        document.querySelectorAll('.product-row .select-producto').forEach(select => {
            const productoIdActual = select.value;
            this.poblarSelectProducto(select);
            
            // Intentar restaurar la selección si el producto aún está disponible
            if (productoIdActual) {
                const opciones = Array.from(select.options);
                const opcionExiste = opciones.some(opt => opt.value == productoIdActual);
                if (opcionExiste) {
                    select.value = productoIdActual;
                } else {
                    select.value = '';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto no disponible',
                        text: 'Algunos productos han sido removidos porque no pertenecen al nuevo proveedor.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        });
        
        this.verificarProveedorSeleccionado();
        this.updateProgress();
    }

    verificarProveedorSeleccionado() {
        const proveedorId = this.selectProveedor?.value;
        
        if (!proveedorId) {
            this.btnAgregar.disabled = true;
            this.proveedorAlert.style.display = 'block';
            this.proveedorAlertMessage.textContent = 'Seleccione un proveedor para poder agregar productos';
            return false;
        }
        
        const productosDelProveedor = this.obtenerProductosDelProveedor(proveedorId);
        
        if (productosDelProveedor.length === 0) {
            this.btnAgregar.disabled = true;
            this.proveedorAlert.style.display = 'block';
            this.proveedorAlertMessage.textContent = 'El proveedor seleccionado no tiene productos asociados. Verifique que las categorías estén asignadas al proveedor.';
            return false;
        } else {
            this.btnAgregar.disabled = false;
            this.proveedorAlert.style.display = 'none';
            return true;
        }
    }

    poblarSelectProducto(selectElement) {
        const proveedorId = this.selectProveedor?.value;
        
        if (!proveedorId) {
            selectElement.innerHTML = '<option value="" disabled selected hidden>Seleccionar...</option>';
            return;
        }
        
        const productosFiltrados = this.obtenerProductosDelProveedor(proveedorId);
        
        const valorActual = selectElement.value;
        
        selectElement.innerHTML = '<option value="" disabled selected hidden>Seleccionar...</option>';
        
        if (productosFiltrados.length === 0) {
            const option = document.createElement('option');
            option.value = "";
            option.disabled = true;
            option.textContent = "No hay productos disponibles";
            selectElement.appendChild(option);
        } else {
            productosFiltrados.forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = `${producto.Nombre}`;
                option.setAttribute('data-precio-venta', producto.Precio);
                option.setAttribute('data-nombre', producto.Nombre);
                option.setAttribute('data-categoria-id', producto.Categoria);
                option.setAttribute('data-categoria-nombre', this.obtenerNombreCategoria(producto.Categoria));
                
                selectElement.appendChild(option);
            });
        }
        
        // Restaurar valor si existe y es válido
        if (valorActual) {
            const opciones = Array.from(selectElement.options);
            const opcionExiste = opciones.some(opt => opt.value == valorActual);
            if (opcionExiste) {
                selectElement.value = valorActual;
            }
        }
    }

    agregarFilaProducto() {
        if (!this.verificarProveedorSeleccionado()) return;

        const template = document.getElementById('templateFilaProducto');
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.product-row');
        
        // Obtener el contador actual de filas para el índice
        const filasExistentes = this.cuerpoTabla.querySelectorAll('.product-row');
        const nuevoIndice = filasExistentes.length;
        
        // Asignar índices únicos a los campos
        const inputs = fila.querySelectorAll('[name]');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('[]', `[${nuevoIndice}]`);
            }
        });

        this.cuerpoTabla.appendChild(nuevaFila);
        
        this.inicializarEventosFila(fila);
        this.poblarSelectProducto(fila.querySelector('.select-producto'));
        this.actualizarVisibilidadTabla();
        this.updateProgress();
    }

    inicializarEventosFila(fila) {
        const selectProducto = fila.querySelector('.select-producto');
        const inputPrecioCompra = fila.querySelector('.precio-compra');
        const inputPorcentajeGanancia = fila.querySelector('.porcentaje-ganancia');
        const inputPrecioVenta = fila.querySelector('.precio-venta');
        const displayCategoria = fila.querySelector('.categoria');
        const inputCategoriaId = fila.querySelector('.categoria-id');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        const btnEliminar = fila.querySelector('.btn-delete-row');

        // Si hay un producto seleccionado, agregarlo al conjunto
        if (selectProducto.value) {
            this.productosSeleccionados.add(selectProducto.value);
        }

        selectProducto.addEventListener('change', () => {
            const productoId = selectProducto.value;
            const productoAnterior = selectProducto.dataset.productoId;
            
            if (productoId) {
                // Verificar si el producto ya está seleccionado en otra fila
                if (productoAnterior !== productoId && this.productosSeleccionados.has(productoId)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado a la compra.',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#007bff'
                    });
                    selectProducto.value = productoAnterior || '';
                    return;
                }
                
                // Actualizar conjunto de productos seleccionados
                if (productoAnterior) {
                    this.productosSeleccionados.delete(productoAnterior);
                }
                this.productosSeleccionados.add(productoId);
                
                const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
                const precioVentaBase = parseFloat(opcionSeleccionada.getAttribute('data-precio-venta')) || 0;
                const categoriaNombre = opcionSeleccionada.getAttribute('data-categoria-nombre');
                
                displayCategoria.value = categoriaNombre;
                inputCategoriaId.value = opcionSeleccionada.getAttribute('data-categoria-id');
                
                if (precioVentaBase > 0) {
                    inputPrecioVenta.value = precioVentaBase.toFixed(2);
                    
                    // Calcular precio de compra sugerido (30% de ganancia por defecto)
                    const porcentajeGanancia = parseFloat(inputPorcentajeGanancia.value) || 30;
                    const precioCompraSugerido = precioVentaBase / (1 + porcentajeGanancia / 100);
                    
                    // Solo establecer si el campo está vacío
                    if (!inputPrecioCompra.value) {
                        inputPrecioCompra.value = precioCompraSugerido.toFixed(2);
                    }
                }
                
                this.calcularSubtotal(fila);
                this.validarFila(fila);
            } else {
                // Eliminar del conjunto si ya no está seleccionado
                if (productoAnterior) {
                    this.productosSeleccionados.delete(productoAnterior);
                }
                displayCategoria.value = '';
                inputCategoriaId.value = '';
                inputPrecioVenta.value = '';
                inputPrecioCompra.value = '';
                inputCantidad.value = '1';
                displaySubtotal.value = '';
            }
            
            selectProducto.dataset.productoId = productoId;
            this.actualizarResumen();
        });

        const calcularPrecios = () => {
            const precioCompra = parseFloat(inputPrecioCompra.value) || 0;
            const porcentajeGanancia = parseFloat(inputPorcentajeGanancia.value) || 0;
            
            if (precioCompra > 0 && porcentajeGanancia > 0) {
                const precioVenta = precioCompra * (1 + porcentajeGanancia / 100);
                inputPrecioVenta.value = precioVenta.toFixed(2);
            }
            
            this.calcularSubtotal(fila);
            this.validarFila(fila);
        };

        inputPrecioCompra.addEventListener('input', calcularPrecios);
        inputPorcentajeGanancia.addEventListener('input', calcularPrecios);
        inputCantidad.addEventListener('input', () => {
            this.calcularSubtotal(fila);
            this.validarFila(fila);
        });

        btnEliminar.addEventListener('click', () => {
            const productoId = selectProducto.dataset.productoId;
            if (productoId) {
                this.productosSeleccionados.delete(productoId);
            }
            
            Swal.fire({
                title: '¿Eliminar producto?',
                text: '¿Está seguro de eliminar este producto de la compra?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    fila.remove();
                    this.actualizarVisibilidadTabla();
                    this.actualizarResumen();
                    this.updateProgress();
                    
                    notifier.showSuccess('Producto eliminado correctamente');
                }
            });
        });
        
        // Calcular subtotal inicial si hay datos
        if (selectProducto.value && inputPrecioCompra.value) {
            this.calcularSubtotal(fila);
        }
    }

    calcularSubtotal(fila) {
        const inputPrecioCompra = fila.querySelector('.precio-compra');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        
        const precioCompra = parseFloat(inputPrecioCompra.value) || 0;
        const cantidad = parseInt(inputCantidad.value) || 0;
        const subtotal = precioCompra * cantidad;
        
        displaySubtotal.value = '$' + subtotal.toFixed(2);
        this.actualizarResumen();
    }

    validarFila(fila) {
        const selectProducto = fila.querySelector('.select-producto');
        const inputPrecioCompra = fila.querySelector('.precio-compra');
        const inputCantidad = fila.querySelector('.cantidad');
        
        const wrapperProducto = selectProducto.closest('.select-wrapper');
        const wrapperPrecio = inputPrecioCompra.closest('.input-wrapper');
        const wrapperCantidad = inputCantidad.closest('.input-wrapper');
        
        wrapperProducto?.classList.remove('error', 'valid');
        wrapperPrecio?.classList.remove('error', 'valid');
        wrapperCantidad?.classList.remove('error', 'valid');
        
        if (selectProducto.value) {
            wrapperProducto?.classList.add('valid');
        } else {
            wrapperProducto?.classList.add('error');
        }
        
        if (inputPrecioCompra.value && parseFloat(inputPrecioCompra.value) > 0) {
            wrapperPrecio?.classList.add('valid');
        } else if (inputPrecioCompra.value) {
            wrapperPrecio?.classList.add('error');
        }
        
        if (inputCantidad.value && parseInt(inputCantidad.value) > 0) {
            wrapperCantidad?.classList.add('valid');
        } else if (inputCantidad.value) {
            wrapperCantidad?.classList.add('error');
        }
    }

    actualizarResumen() {
        let totalCompra = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        
        filas.forEach(fila => {
            const inputPrecioCompra = fila.querySelector('.precio-compra');
            const inputCantidad = fila.querySelector('.cantidad');
            const selectProducto = fila.querySelector('.select-producto');
            
            const precioCompra = parseFloat(inputPrecioCompra.value) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            
            if (selectProducto.value && precioCompra > 0) {
                totalCompra += precioCompra * cantidad;
                totalProductos++;
                totalUnidades += cantidad;
            }
        });
        
        document.getElementById('totalCompra').textContent = '$' + totalCompra.toFixed(2);
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('montoTotal').textContent = '$' + totalCompra.toFixed(2);
        
        this.updateProgress();
    }

    actualizarVisibilidadTabla() {
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        
        if (filas.length === 0) {
            this.emptyState.style.display = 'block';
        } else {
            this.emptyState.style.display = 'none';
        }
    }

    updateProgress() {
        const proveedorSeleccionado = this.selectProveedor?.value;
        const productosAgregados = this.cuerpoTabla.querySelectorAll('.product-row').length > 0;
        
        let completedSections = 0;
        if (proveedorSeleccionado) completedSections++;
        if (productosAgregados) completedSections++;
        
        const percentage = (completedSections / 2) * 100;
        
        document.getElementById('formProgress').style.width = `${percentage}%`;
        document.getElementById('progressPercentage').textContent = `${Math.round(percentage)}% Completado`;
        document.getElementById('validFieldsCount').textContent = `${completedSections}/2`;
        
        if (completedSections === 2) {
            document.getElementById('completedFields').textContent = 'Todos los pasos completados';
        } else {
            document.getElementById('completedFields').textContent = `${completedSections} de 2 pasos completados`;
        }
    }

    handleActualizar(e) {
        e.preventDefault();
        
        const proveedorId = this.selectProveedor?.value;
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        
        // Validaciones
        if (!proveedorId) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                text: 'Debe seleccionar un proveedor.',
                confirmButtonColor: '#007bff'
            });
            this.selectProveedor.closest('.select-wrapper')?.classList.add('error');
            return;
        }
        
        if (filas.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                text: 'Debe agregar al menos un producto a la compra.',
                confirmButtonColor: '#007bff'
            });
            return;
        }

        // Validar cada fila
        let productosValidos = true;
        const errores = [];
        
        filas.forEach((fila, index) => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputPrecioCompra = fila.querySelector('.precio-compra');
            const inputCantidad = fila.querySelector('.cantidad');
            const productoNombre = selectProducto.options[selectProducto.selectedIndex]?.text || `Producto ${index + 1}`;
            
            if (!selectProducto.value) {
                productosValidos = false;
                errores.push(`Producto ${index + 1}: No tiene producto seleccionado`);
            }
            
            if (!inputPrecioCompra.value || parseFloat(inputPrecioCompra.value) <= 0) {
                productosValidos = false;
                errores.push(`Producto ${index + 1}: Precio de compra inválido`);
            }
            
            if (!inputCantidad.value || parseInt(inputCantidad.value) < 1) {
                productosValidos = false;
                errores.push(`Producto ${index + 1}: Cantidad inválida`);
            }
        });
        
        if (!productosValidos) {
            notifier.showValidationError(errores);
            return;
        }

        // Confirmar actualización
        const totalProductos = filas.length;
        const totalUnidades = parseInt(document.getElementById('totalUnidades').textContent);
        const montoTotal = document.getElementById('montoTotal').textContent;
        const proveedorNombre = this.selectProveedor.options[this.selectProveedor.selectedIndex]?.text || '';

        Swal.fire({
            title: '¿Actualizar Compra?',
            html: `
                <div class="text-start">
                    <p>¿Está seguro de actualizar la siguiente compra?</p>
                    <div class="alert alert-info" style="background: #e8f4f8; border-radius: 12px; padding: 15px;">
                        <strong style="display: block; margin-bottom: 10px;">Resumen de cambios:</strong>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>📦 Productos:</div>
                            <div><strong>${totalProductos}</strong></div>
                            <div>📊 Unidades:</div>
                            <div><strong>${totalUnidades}</strong></div>
                            <div>💰 Total:</div>
                            <div><strong class="text-success">${montoTotal}</strong></div>
                            <div style="grid-column: span 2; margin-top: 10px;">
                                <small>Proveedor: ${proveedorNombre}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loader
                const submitBtn = document.getElementById('btnActualizarCompra');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                notifier.showInfo('Actualizando compra...', 'Un momento por favor');
                
                // Enviar formulario
                setTimeout(() => {
                    document.getElementById('editForm').submit();
                }, 500);
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new EditarCompraManager();
});
</script>

<!-- Toast Notifications CSS -->
<style>
.toast-notification {
    position: fixed;
    top: 30px;
    right: 30px;
    min-width: 380px;
    max-width: 450px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    overflow: hidden;
    animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border: none;
}

.toast-notification.hiding {
    animation: slideOutRight 0.3s ease-in-out forwards;
}

.toast-content {
    display: flex;
    align-items: center;
    padding: 20px;
    position: relative;
}

.toast-icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.toast-icon-wrapper i {
    font-size: 24px;
    color: white;
}

.toast-text {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 4px;
    color: #1f2937;
}

.toast-message {
    font-size: 0.9rem;
    color: #6b7280;
    line-height: 1.4;
}

.toast-close {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #f3f4f6;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.toast-close:hover {
    background: #e5e7eb;
    color: #4b5563;
    transform: rotate(90deg);
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    background: linear-gradient(90deg, rgba(255,255,255,0.5), rgba(255,255,255,0.8));
    animation: progress 4s linear forwards;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast-success .toast-icon-wrapper {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.toast-error .toast-icon-wrapper {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.toast-warning .toast-icon-wrapper {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.toast-info .toast-icon-wrapper {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

@media (max-width: 768px) {
    .toast-notification {
        top: 20px;
        right: 20px;
        left: 20px;
        min-width: auto;
        max-width: none;
    }
}
</style>
@endsection