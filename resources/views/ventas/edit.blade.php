@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <!-- Fondo decorativo -->
    <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-xxl-block" style="
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(253, 126, 20, 0.03) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 25% 0);
        z-index: 0;
    "></div>

    <div class="position-relative z-1">
        <div class="row justify-content-center g-0">
            <div class="col-12 col-xxl-10">
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
                                background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(255, 193, 7, 0.25);
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
                                    Editar Venta #{{ $venta->id }}
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($venta->Fecha)->setTimezone('America/Mexico_City')->format('d/m/Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
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
                                    <i class="fas fa-tasks me-2 text-warning"></i>
                                    Progreso del Formulario
                                </h5>
                                <div class="progress-percentage">
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2" id="progressPercentage">
                                        100% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #ffc107, #fd7e14); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">2 de 2 secciones completadas</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="ventaForm" action="{{ route('ventas.update', $venta->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Sección 1: Información General -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información General</h3>
                                            <p class="section-subtitle mb-0">Datos básicos de la venta</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #ffc107, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Empleado -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Empleado</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="select-wrapper" data-required="true">
                                                <div class="select-icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <select class="select-field @error('empleado_id') is-invalid @enderror" 
                                                        id="empleado_id" 
                                                        name="empleado_id" 
                                                        required>
                                                    <option value="" disabled selected hidden>Seleccione un empleado...</option>
                                                    @foreach($empleados as $empleado)
                                                        <option value="{{ $empleado->id }}" 
                                                                {{ old('empleado_id', $venta->Empleado_idEmpleado) == $empleado->id ? 'selected' : '' }}
                                                                data-nombre="{{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}">
                                                            {{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="select-decoration" style="background: linear-gradient(90deg, #ffc107, #fd7e14);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Seleccione el empleado que realizó la venta
                                                </div>
                                            </div>
                                            
                                            @error('empleado_id')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Fecha y Hora Original -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Fecha y Hora Original</span>
                                            </label>
                                            
                                            <div class="info-card" style="border-left-color: #ffc107;">
                                                <div class="info-card-icon" style="color: #ffc107;">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="info-card-content">
                                                    <span class="info-card-label">Fecha de registro</span>
                                                    <span class="info-card-value" id="fechaOriginal">
                                                        {{ \Carbon\Carbon::parse($venta->Fecha)->setTimezone('America/Mexico_City')->format('d/m/Y h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-clock"></i>
                                                    Fecha de registro de la venta
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Productos de la Venta -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Productos de la Venta</h3>
                                            <p class="section-subtitle mb-0">Productos incluidos en la venta</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #28a745, transparent);"></div>
                                </div>

                                <!-- Botón Agregar Producto -->
                                <div class="d-flex justify-content-end mb-4">
                                    <button type="button" class="btn btn-add-product" id="agregarProducto">
                                        <i class="fas fa-plus me-2"></i>
                                        Agregar Producto
                                    </button>
                                </div>

                                <!-- Tabla de Productos -->
                                <div class="table-responsive">
                                    <table class="table table-modern" id="tablaProductos">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%">Producto <span class="text-danger">*</span></th>
                                                <th style="width: 20%">Precio Unitario</th>
                                                <th style="width: 15%">Cantidad <span class="text-danger">*</span></th>
                                                <th style="width: 20%">Subtotal</th>
                                                <th style="width: 10%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpoTablaProductos">
                                            <!-- Las filas se generarán dinámicamente con los productos existentes -->
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-total">
                                                <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                                <td colspan="2">
                                                    <span class="total-amount" id="totalVenta">${{ number_format($venta->Total, 2) }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Mensaje cuando no hay productos -->
                                <div class="empty-state text-center py-5" id="emptyState" style="display: none;">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <h5>No hay productos agregados</h5>
                                    <p class="text-muted">Haga clic en "Agregar Producto" para comenzar</p>
                                </div>
                            </div>

                            <!-- Sección 3: Resumen -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Resumen de la Venta</h3>
                                            <p class="section-subtitle mb-0">Detalles y totales</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #17a2b8, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Total Productos</span>
                                                <span class="summary-value" id="totalProductos">{{ $venta->detalleVentas->count() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="summary-card">
                                            <div class="summary-icon" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                                <i class="fas fa-sort-amount-up"></i>
                                            </div>
                                            <div class="summary-content">
                                                <span class="summary-label">Unidades Totales</span>
                                                <span class="summary-value" id="totalUnidades">{{ $venta->detalleVentas->sum('Cantidad') }}</span>
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
                                                <span class="summary-value" id="montoTotal">${{ number_format($venta->Total, 2) }}</span>
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
                                                <span id="validFieldsCount">2/2</span> secciones
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="submit" class="btn btn-warning btn-submit" id="submitBtn" style="
                                            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
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
                                                Actualizar Venta
                                            </span>
                                            <span class="submit-loader">
                                                <i class="fas fa-spinner fa-spin me-2"></i>
                                                Procesando...
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

<!-- Template para fila de producto -->
<template id="templateFilaProducto">
    <tr class="product-row">
        <td>
            <div class="form-group-enhanced mb-0">
                <div class="select-wrapper compact" data-required="true">
                    <div class="select-icon compact">
                        <i class="fas fa-box"></i>
                    </div>
                    <select class="select-field compact select-producto" name="productos[][id]" required>
                        <option value="" disabled selected hidden>Seleccionar producto...</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" 
                                    data-precio="{{ $producto->Precio }}"
                                    data-stock="{{ $producto->Cantidad }}"
                                    data-nombre="{{ $producto->Nombre }}">
                                {{ $producto->Nombre }} - Stock: {{ $producto->Cantidad }} - ${{ number_format($producto->Precio, 2) }}
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
            <div class="form-group-enhanced mb-0">
                <div class="input-wrapper compact">
                    <div class="input-icon compact">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <input type="text" class="input-field compact precio-unitario" readonly placeholder="$0.00">
                </div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0">
                <div class="input-wrapper compact" data-required="true">
                    <div class="input-icon compact">
                        <i class="fas fa-sort-numeric-up"></i>
                    </div>
                    <input type="number" class="input-field compact cantidad" name="productos[][cantidad]" 
                           min="1" value="1" required>
                </div>
                <div class="stock-warning text-danger small mt-1" style="display: none;"></div>
            </div>
        </td>
        <td>
            <div class="form-group-enhanced mb-0">
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

<!-- Toast Notifications Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-color: #ef4444;
    --success-color: #10b981;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
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

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@keyframes shine {
    0% { transform: rotate(30deg) translateX(-100%); }
    100% { transform: rotate(30deg) translateX(100%); }
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

/* Toast Notifications */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 350px;
    max-width: 450px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    overflow: hidden;
    z-index: 10000;
    animation: slideInRight 0.3s ease forwards;
    border-left: 4px solid;
}

.toast-notification.hiding {
    animation: slideOutRight 0.3s ease forwards;
}

.toast-notification.toast-success {
    border-left-color: var(--success-color);
}

.toast-notification.toast-error {
    border-left-color: var(--danger-color);
}

.toast-notification.toast-warning {
    border-left-color: var(--warning-color);
}

.toast-notification.toast-info {
    border-left-color: var(--info-color);
}

.toast-content {
    position: relative;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: white;
}

.toast-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.toast-success .toast-icon-wrapper {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
}

.toast-error .toast-icon-wrapper {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
}

.toast-warning .toast-icon-wrapper {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning-color);
}

.toast-info .toast-icon-wrapper {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info-color);
}

.toast-text {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    margin-bottom: 4px;
    color: #1f2937;
    font-size: 0.95rem;
}

.toast-message {
    color: #6b7280;
    font-size: 0.85rem;
}

.toast-close {
    background: transparent;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: var(--transition);
}

.toast-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: linear-gradient(90deg, var(--progress-color-start), var(--progress-color-end));
    animation: progress 4s linear forwards;
}

.toast-success .toast-progress {
    --progress-color-start: #10b981;
    --progress-color-end: #059669;
}

.toast-error .toast-progress {
    --progress-color-start: #ef4444;
    --progress-color-end: #dc2626;
}

.toast-warning .toast-progress {
    --progress-color-start: #ffc107;
    --progress-color-end: #fd7e14;
}

.toast-info .toast-progress {
    --progress-color-start: #17a2b8;
    --progress-color-end: #138496;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

/* Shake animation enhanced */
.shake-enhanced {
    animation: shake 0.8s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
    perspective: 1000px;
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

/* Input Wrapper */
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
    border-color: #ffc107;
    box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.1);
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
    color: #ffc107;
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

.select-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.select-wrapper:focus-within .select-decoration {
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
    border-left: 5px solid;
}

.info-card-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(253, 126, 20, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
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

/* Table Modern */
.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 4px;
}

.table-modern thead th {
    background: #f8f9fa;
    padding: 12px 6px;
    font-weight: 600;
    color: #4b5563;
    font-size: 0.85rem;
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
    padding: 8px 4px;
    vertical-align: middle;
    border: none;
    font-size: 0.9rem;
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
    padding: 12px;
    font-weight: 700;
    color: #1f2937;
    font-size: 0.95rem;
}

.total-amount {
    font-size: 1.2rem;
    color: #28a745;
    font-weight: 700;
}

/* Delete Button */
.btn-delete-row.compact {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid #e5e7eb;
    color: #ef4444;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.btn-delete-row.compact:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
    transform: scale(1.1);
}

/* Add Product Button */
.btn-add-product {
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    transition: var(--transition);
}

.btn-add-product:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
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

.btn-action {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

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

.btn-submit:hover:not(.loading) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
}

.btn-submit:active:not(.loading) {
    transform: translateY(1px);
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

.is-valid {
    border-color: var(--success-color) !important;
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

/* Progress Bar */
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

/* Responsive */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-action,
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
        font-size: 0.8rem;
    }

    .table-modern tbody td {
        padding: 6px 2px;
    }

    .btn-delete-row.compact {
        width: 28px;
        height: 28px;
    }
    
    .toast-notification {
        min-width: 300px;
        max-width: 350px;
        top: 10px;
        right: 10px;
    }
}
</style>

<script>
// Sistema de Notificaciones
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.maxNotifications = 3;
        this.container = document.getElementById('toastContainer');
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

        this.container.appendChild(notification);
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

    showValidationError(message) {
        this.show(message, 'warning', 'Errores en el formulario');
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

class VentaEditManager {
    constructor() {
        this.productosSeleccionados = new Set();
        this.contadorProductos = 0;
        this.productos = @json($productos);
        this.productosOriginales = new Map();
        
        @foreach($venta->detalleVentas as $detalle)
            this.productosOriginales.set({{ $detalle->Producto }}, {{ $detalle->Cantidad }});
        @endforeach
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateProgress();
        this.showServerErrors();
        this.cargarProductosExistentes();
    }

    showServerErrors() {
        @if($errors->any())
            const errorMessages = [];
            @foreach($errors->all() as $error)
                errorMessages.push('{{ $error }}');
            @endforeach
            notifier.showError(errorMessages.join('<br>'));
        @endif
    }

    setupEventListeners() {
        this.selectEmpleado = document.getElementById('empleado_id');
        this.btnAgregar = document.getElementById('agregarProducto');
        this.btnActualizar = document.getElementById('submitBtn');
        this.cuerpoTabla = document.getElementById('cuerpoTablaProductos');
        this.emptyState = document.getElementById('emptyState');
        this.form = document.getElementById('ventaForm');

        if (this.btnAgregar) {
            this.btnAgregar.addEventListener('click', () => this.agregarFilaProducto());
        }

        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        if (this.selectEmpleado) {
            this.selectEmpleado.addEventListener('change', () => {
                this.updateProgress();
            });
        }
    }

    cargarProductosExistentes() {
        @foreach($venta->detalleVentas as $index => $detalle)
            const fila = this.agregarFilaProducto();
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            
            selectProducto.value = '{{ $detalle->Producto }}';
            
            setTimeout(() => {
                const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
                if (opcionSeleccionada) {
                    const stockOriginal = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
                    const cantidadOriginal = this.productosOriginales.get({{ $detalle->Producto }}) || 0;
                    const stockDisponible = stockOriginal + cantidadOriginal;
                    
                    opcionSeleccionada.setAttribute('data-stock', stockDisponible);
                    
                    const event = new Event('change');
                    selectProducto.dispatchEvent(event);
                }
            }, 100);
            
            setTimeout(() => {
                inputCantidad.value = '{{ $detalle->Cantidad }}';
                const inputEvent = new Event('input');
                inputCantidad.dispatchEvent(inputEvent);
            }, 200);
        @endforeach
    }

    agregarFilaProducto() {
        const template = document.getElementById('templateFilaProducto');
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.product-row');
        const filaId = 'fila-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        fila.setAttribute('data-fila-id', filaId);
        
        const selects = fila.querySelectorAll('select, input');
        selects.forEach(select => {
            if (select.name) {
                select.name = select.name.replace('[]', `[${this.contadorProductos}]`);
            }
        });

        this.cuerpoTabla.appendChild(nuevaFila);
        this.contadorProductos++;
        
        this.inicializarEventosFila(fila, filaId);
        this.actualizarVisibilidadTabla();
        this.updateProgress();
        
        return fila;
    }

    inicializarEventosFila(fila, filaId) {
        const selectProducto = fila.querySelector('.select-producto');
        const displayPrecio = fila.querySelector('.precio-unitario');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        const stockWarning = fila.querySelector('.stock-warning');
        const btnEliminar = fila.querySelector('.btn-delete-row');

        let productoActual = '';

        selectProducto.addEventListener('change', () => {
            const productoId = selectProducto.value;
            const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
            
            if (productoId) {
                if (this.productoYaEnUso(productoId, filaId)) {
                    notifier.showWarning('Este producto ya ha sido agregado a la venta en otra fila');
                    selectProducto.value = '';
                    this.limpiarFila(displayPrecio, inputCantidad, displaySubtotal, stockWarning);
                    return;
                }
                
                if (productoActual) {
                    this.productosSeleccionados.delete(productoActual);
                }
                
                this.productosSeleccionados.add(productoId);
                productoActual = productoId;
                
                const precio = parseFloat(opcionSeleccionada.getAttribute('data-precio')) || 0;
                let stock = parseInt(opcionSeleccionada.getAttribute('data-stock')) || 0;
                
                const cantidadOriginal = this.productosOriginales.get(parseInt(productoId)) || 0;
                if (cantidadOriginal > 0) {
                    stock += cantidadOriginal;
                }
                
                displayPrecio.value = '$' + precio.toFixed(2);
                
                const maxPermitido = Math.min(stock, 1000);
                inputCantidad.max = maxPermitido;
                inputCantidad.setAttribute('data-stock', stock);
                
                this.calcularSubtotal(fila);
                this.validarCantidad(inputCantidad, stockWarning);
            } else {
                if (productoActual) {
                    this.productosSeleccionados.delete(productoActual);
                    productoActual = '';
                }
                this.limpiarFila(displayPrecio, inputCantidad, displaySubtotal, stockWarning);
            }
            
            this.actualizarResumen();
            this.updateProgress();
        });

        inputCantidad.addEventListener('input', () => {
            this.validarCantidad(inputCantidad, stockWarning);
            this.calcularSubtotal(fila);
            this.actualizarResumen();
        });

        btnEliminar.addEventListener('click', () => {
            const productoId = selectProducto.dataset.productoId;
            
            if (productoId) {
                this.productosSeleccionados.delete(productoId);
            }
            
            fila.remove();
            this.actualizarVisibilidadTabla();
            this.actualizarResumen();
            this.updateProgress();
        });
    }

    validarCantidad(inputCantidad, stockWarning) {
        const cantidad = parseInt(inputCantidad.value) || 0;
        const stock = parseInt(inputCantidad.getAttribute('data-stock')) || 0;
        const wrapper = inputCantidad.closest('.input-wrapper');
        
        wrapper.classList.remove('error', 'valid');
        
        if (cantidad < 1) {
            wrapper.classList.add('error');
            stockWarning.style.display = 'block';
            stockWarning.textContent = 'La cantidad debe ser al menos 1';
        } else if (cantidad > stock) {
            wrapper.classList.add('error');
            stockWarning.style.display = 'block';
            stockWarning.textContent = `Stock insuficiente. Disponible: ${stock}`;
        } else {
            wrapper.classList.add('valid');
            stockWarning.style.display = 'none';
        }
    }

    limpiarFila(displayPrecio, inputCantidad, displaySubtotal, stockWarning) {
        displayPrecio.value = '';
        inputCantidad.value = '1';
        displaySubtotal.value = '';
        stockWarning.style.display = 'none';
        inputCantidad.removeAttribute('data-stock');
        inputCantidad.removeAttribute('max');
        
        const wrapper = inputCantidad.closest('.input-wrapper');
        wrapper.classList.remove('error', 'valid');
    }

    productoYaEnUso(productoId, filaActualId) {
        return this.productosSeleccionados.has(productoId);
    }

    calcularSubtotal(fila) {
        const selectProducto = fila.querySelector('.select-producto');
        const inputCantidad = fila.querySelector('.cantidad');
        const displaySubtotal = fila.querySelector('.subtotal');
        
        if (!selectProducto.value) return;
        
        const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
        const precio = parseFloat(opcionSeleccionada.getAttribute('data-precio')) || 0;
        const cantidad = parseInt(inputCantidad.value) || 0;
        const subtotal = precio * cantidad;
        
        displaySubtotal.value = '$' + subtotal.toFixed(2);
    }

    actualizarResumen() {
        let totalVenta = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        
        filas.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            
            if (selectProducto.value && inputCantidad.value) {
                const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
                const precio = parseFloat(opcionSeleccionada.getAttribute('data-precio')) || 0;
                const cantidad = parseInt(inputCantidad.value) || 0;
                
                totalVenta += precio * cantidad;
                totalProductos++;
                totalUnidades += cantidad;
            }
        });
        
        document.getElementById('totalVenta').textContent = '$' + totalVenta.toFixed(2);
        document.getElementById('totalProductos').textContent = totalProductos;
        document.getElementById('totalUnidades').textContent = totalUnidades;
        document.getElementById('montoTotal').textContent = '$' + totalVenta.toFixed(2);
    }

    actualizarVisibilidadTabla() {
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        
        if (filas.length === 0) {
            this.emptyState.style.display = 'block';
            this.agregarFilaProducto();
        } else {
            this.emptyState.style.display = 'none';
        }
    }

    updateProgress() {
        const empleadoSeleccionado = this.selectEmpleado?.value;
        const productosValidos = this.validarProductos();
        
        let completedSections = 0;
        if (empleadoSeleccionado) completedSections++;
        if (productosValidos) completedSections++;
        
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

    validarProductos() {
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        if (filas.length === 0) return false;
        
        let productosValidos = true;
        
        filas.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const inputCantidad = fila.querySelector('.cantidad');
            
            if (!selectProducto.value) {
                productosValidos = false;
                selectProducto.closest('.select-wrapper').classList.add('error');
            }
            
            const cantidad = parseInt(inputCantidad.value) || 0;
            const stock = parseInt(inputCantidad.getAttribute('data-stock')) || 0;
            
            if (cantidad < 1 || cantidad > stock) {
                productosValidos = false;
                inputCantidad.closest('.input-wrapper').classList.add('error');
            }
        });
        
        return productosValidos;
    }

    handleSubmit(e) {
        e.preventDefault();
        
        const empleadoId = this.selectEmpleado?.value;
        const filas = this.cuerpoTabla.querySelectorAll('.product-row');
        const errores = [];
        
        if (!empleadoId) {
            errores.push('• Debe seleccionar un empleado');
            this.selectEmpleado.closest('.select-wrapper').classList.add('error');
        }
        
        if (filas.length === 0) {
            errores.push('• Debe agregar al menos un producto');
        } else {
            filas.forEach((fila, index) => {
                const selectProducto = fila.querySelector('.select-producto');
                const inputCantidad = fila.querySelector('.cantidad');
                const productoNombre = selectProducto.options[selectProducto.selectedIndex]?.text.split(' - ')[0] || `Producto ${index + 1}`;
                
                if (!selectProducto.value) {
                    errores.push(`• Producto ${index + 1}: No ha seleccionado un producto`);
                    selectProducto.closest('.select-wrapper').classList.add('error');
                }
                
                const cantidad = parseInt(inputCantidad.value) || 0;
                const stock = parseInt(inputCantidad.getAttribute('data-stock')) || 0;
                
                if (cantidad < 1) {
                    errores.push(`• ${productoNombre}: La cantidad debe ser al menos 1`);
                } else if (cantidad > stock) {
                    errores.push(`• ${productoNombre}: Stock insuficiente (Disponible: ${stock})`);
                } else if (cantidad > 1000) {
                    errores.push(`• ${productoNombre}: Cantidad máxima excedida (Máx: 1000)`);
                }
            });
        }
        
        if (errores.length > 0) {
            notifier.showValidationError(errores.join('<br>'));
            
            const firstError = document.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.classList.add('shake-enhanced');
                setTimeout(() => firstError.classList.remove('shake-enhanced'), 800);
            }
            return;
        }

        const totalProductos = filas.length;
        const totalUnidades = parseInt(document.getElementById('totalUnidades').textContent);
        const montoTotal = document.getElementById('montoTotal').textContent;
        const empleadoNombre = this.selectEmpleado.options[this.selectEmpleado.selectedIndex]?.text || '';

        Swal.fire({
            title: '¿Actualizar Venta?',
            html: `
                <div class="text-start">
                    <p>¿Está seguro de actualizar la siguiente venta?</p>
                    <div class="alert alert-info" style="background: #e8f4f8; border-radius: 12px; padding: 15px;">
                        <strong style="display: block; margin-bottom: 10px;">Resumen:</strong>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>📦 Productos:</div>
                            <div><strong>${totalProductos}</strong></div>
                            <div>📊 Unidades:</div>
                            <div><strong>${totalUnidades}</strong></div>
                            <div>💰 Total:</div>
                            <div><strong class="text-success">${montoTotal}</strong></div>
                            <div style="grid-column: span 2; margin-top: 10px;">
                                <small>Empleado: ${empleadoNombre}</small>
                            </div>
                        </div>
                    </div>
                    <p class="text-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        El stock será restaurado y actualizado automáticamente
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                notifier.showInfo('Actualizando venta...', 'Un momento por favor');
                
                setTimeout(() => {
                    document.getElementById('ventaForm').submit();
                }, 500);
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new VentaEditManager();
});
</script>

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection