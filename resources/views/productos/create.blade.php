@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <!-- Fondo decorativo -->
    <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-xxl-block" style="
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.05) 0%, rgba(255, 167, 38, 0.03) 100%);
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
                                background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(255, 107, 107, 0.25);
                                animation: float 6s ease-in-out infinite;
                            ">
                                <i class="fas fa-box fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Nuevo Producto
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Complete todos los campos para registrar un nuevo producto
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
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
                                    Progreso del Formulario
                                </h5>
                                <div class="progress-percentage">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" id="progressPercentage">
                                        0% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #ff6b6b, #ffa726); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">0 de 9 campos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="productoForm" action="{{ route('productos.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <!-- Sección 1: Información Básica -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información Básica</h3>
                                            <p class="section-subtitle mb-0">Datos generales del producto</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #ff6b6b, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Nombre -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Nombre del Producto</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <input type="text" 
                                                       class="input-field @error('Nombre') is-invalid @enderror" 
                                                       id="Nombre" 
                                                       name="Nombre" 
                                                       value="{{ old('Nombre') }}" 
                                                       placeholder="Ej: Laptop Dell XPS 15" 
                                                       required 
                                                       maxlength="100"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">0/100</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Nombre descriptivo del producto
                                                </div>
                                            </div>
                                            
                                            @error('Nombre') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Categoría -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Categoría</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-folder"></i>
                                                </div>
                                                <select class="input-field @error('Categoria') is-invalid @enderror" 
                                                        id="Categoria" 
                                                        name="Categoria" 
                                                        required>
                                                    <option value="" disabled selected>Seleccionar categoría</option>
                                                    @foreach($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}" 
                                                            {{ old('Categoria') == $categoria->id ? 'selected' : '' }}
                                                            data-proveedor="{{ $categoria->Proveedor ?? 'Sin proveedor' }}">
                                                            {{ $categoria->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Seleccione la categoría del producto
                                                </div>
                                            </div>
                                            
                                            @error('Categoria') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Descripción -->
                                    <div class="col-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Descripción</span>
                                                <span class="label-optional">(opcional)</span>
                                            </label>
                                            
                                            <div class="input-wrapper">
                                                <div class="input-icon">
                                                    <i class="fas fa-align-left"></i>
                                                </div>
                                                <textarea class="input-field @error('Descripcion') is-invalid @enderror" 
                                                          id="Descripcion" 
                                                          name="Descripcion" 
                                                          placeholder="Describa las características del producto..."
                                                          maxlength="200"
                                                          style="min-height: 100px; padding-top: 16px;"
                                                          data-char-counter="descripcionCount">{{ old('Descripcion') }}</textarea>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="descripcionCount">0/200</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Descripción detallada del producto
                                                </div>
                                            </div>
                                            
                                            @error('Descripcion') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Preview de Categoría -->
                                    <div class="col-12">
                                        <div class="preview-card p-3" id="categoriaPreview" style="display: none;">
                                            <div class="d-flex align-items-center">
                                                <div class="gender-icon-wrapper me-3" style="
                                                    width: 40px;
                                                    height: 40px;
                                                    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 167, 38, 0.1));
                                                    border-radius: 10px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    color: #ff6b6b;
                                                ">
                                                    <i class="fas fa-folder-open"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold" id="categoriaNombre">Nombre de categoría</h6>
                                                    <small class="text-muted d-flex align-items-center">
                                                        <i class="fas fa-truck me-2"></i>
                                                        <span id="categoriaProveedor">Proveedor asociado</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Precio y Gestión de Inventario -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #ffa726 0%, #ff6b6b 100%);">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Precio y Gestión de Inventario</h3>
                                            <p class="section-subtitle mb-0">Control de stock y precios</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #ffa726, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Precio -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Precio Unitario</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </div>
                                                <input type="number" 
                                                       class="input-field @error('Precio') is-invalid @enderror" 
                                                       id="Precio" 
                                                       name="Precio" 
                                                       value="{{ old('Precio') }}" 
                                                       placeholder="0.00" 
                                                       step="0.01" 
                                                       min="0" 
                                                       required>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Precio unitario del producto
                                                </div>
                                            </div>
                                            
                                            @error('Precio') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Valor Total Estimado -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Valor Total Estimado</span>
                                            </label>
                                            
                                            <div class="preview-card p-3" style="
                                                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                                border-radius: 12px;
                                                border-left: 5px solid #28a745;
                                            ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold" id="valorTotal">$0.00</h6>
                                                        <small class="text-muted">Valor del inventario</small>
                                                    </div>
                                                    <i class="fas fa-chart-line text-success fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Cantidad Inicial</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-boxes"></i>
                                                </div>
                                                <input type="number" 
                                                       class="input-field @error('Cantidad') is-invalid @enderror" 
                                                       id="Cantidad" 
                                                       name="Cantidad" 
                                                       value="{{ old('Cantidad') }}" 
                                                       placeholder="0" 
                                                       min="0" 
                                                       required>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            @error('Cantidad') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Cantidad Mínima -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Cantidad Mínima</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <input type="number" 
                                                       class="input-field @error('Cantidad_minima') is-invalid @enderror" 
                                                       id="Cantidad_minima" 
                                                       name="Cantidad_minima" 
                                                       value="{{ old('Cantidad_minima') }}" 
                                                       placeholder="0" 
                                                       min="0" 
                                                       required>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Stock mínimo antes de alertar
                                                </div>
                                            </div>
                                            
                                            @error('Cantidad_minima') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Cantidad Máxima -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Cantidad Máxima</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-warehouse"></i>
                                                </div>
                                                <input type="number" 
                                                       class="input-field @error('Cantidad_maxima') is-invalid @enderror" 
                                                       id="Cantidad_maxima" 
                                                       name="Cantidad_maxima" 
                                                       value="{{ old('Cantidad_maxima') }}" 
                                                       placeholder="0" 
                                                       min="0" 
                                                       required>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Capacidad máxima de almacenamiento
                                                </div>
                                            </div>
                                            
                                            @error('Cantidad_maxima') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Resumen de Inventario -->
                                    <div class="col-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Resumen de Inventario</span>
                                            </label>
                                            
                                            <div class="inventory-summary-card p-4" style="
                                                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                                border-radius: 16px;
                                                border: 1px solid rgba(0,0,0,0.05);
                                            ">
                                                <div class="row g-4">
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <div class="inventory-icon-wrapper" style="
                                                                width: 60px;
                                                                height: 60px;
                                                                background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(13, 110, 253, 0.2));
                                                                border-radius: 50%;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                margin: 0 auto 12px;
                                                                color: #0d6efd;
                                                                font-size: 1.5rem;
                                                            ">
                                                                <i class="fas fa-boxes"></i>
                                                            </div>
                                                            <h4 class="mb-1 fw-bold" id="stockActual">0</h4>
                                                            <small class="text-muted">Stock Actual</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <div class="inventory-icon-wrapper" style="
                                                                width: 60px;
                                                                height: 60px;
                                                                background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.2));
                                                                border-radius: 50%;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                margin: 0 auto 12px;
                                                                color: #ffc107;
                                                                font-size: 1.5rem;
                                                            ">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                            </div>
                                                            <h4 class="mb-1 fw-bold" id="stockMinimo">0</h4>
                                                            <small class="text-muted">Mínimo</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <div class="inventory-icon-wrapper" style="
                                                                width: 60px;
                                                                height: 60px;
                                                                background: linear-gradient(135deg, rgba(25, 135, 84, 0.1), rgba(25, 135, 84, 0.2));
                                                                border-radius: 50%;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                margin: 0 auto 12px;
                                                                color: #198754;
                                                                font-size: 1.5rem;
                                                            ">
                                                                <i class="fas fa-warehouse"></i>
                                                            </div>
                                                            <h4 class="mb-1 fw-bold" id="stockMaximo">0</h4>
                                                            <small class="text-muted">Máximo</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="progress mt-4" style="height: 10px; border-radius: 10px;">
                                                    <div class="progress-bar" id="stockProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #ff6b6b, #ffa726); border-radius: 10px;"></div>
                                                </div>
                                                
                                                <div class="text-center mt-3">
                                                    <small class="text-muted" id="stockStatus">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Ingrese valores de inventario
                                                    </small>
                                                </div>
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
                                                <span id="validFieldsCount">0/9</span> completados
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="button" class="btn btn-action btn-outline-secondary" onclick="resetForm()">
                                            <i class="fas fa-redo me-2"></i>
                                            Limpiar
                                        </button>
                                        
                                        <button type="submit" class="btn btn-primary btn-submit" id="submitBtn" style="
                                            background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
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
                                                Guardar Producto
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

<!-- Toast Notifications Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
    --success-gradient: linear-gradient(135deg, #42e695 0%, #3bb2b8 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --danger-color: #ef4444;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
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
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
}

.toast-info .toast-icon-wrapper {
    background: rgba(59, 130, 246, 0.1);
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
    --progress-color-start: #f59e0b;
    --progress-color-end: #d97706;
}

.toast-info .toast-progress {
    --progress-color-start: #3b82f6;
    --progress-color-end: #2563eb;
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
    background: var(--primary-gradient);
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
    background: linear-gradient(to right, #ff6b6b, transparent);
    border-radius: 2px;
    margin-top: 1rem;
}

/* Enhanced Form Groups */
.form-group-enhanced {
    margin-bottom: 1.5rem;
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

.label-optional {
    color: #9ca3af;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Input Wrapper */
.input-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper:focus-within {
    border-color: #ff6b6b;
    box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
    transform: translateY(-2px);
}

.input-wrapper.error {
    border-color: var(--danger-color);
    animation: shake 0.5s;
}

.input-wrapper.valid {
    border-color: var(--success-color);
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #ff6b6b;
    font-size: 1.1rem;
    z-index: 2;
}

.input-field {
    width: 100%;
    padding: 20px 20px 20px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1f2937;
    outline: none;
    transition: var(--transition);
}

.input-field::placeholder {
    color: #9ca3af;
}

.input-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.input-wrapper:focus-within .input-decoration {
    transform: scaleX(1);
}

.input-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    padding: 0 4px;
}

.char-counter {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
}

.input-hint {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Inventory Summary */
.inventory-summary-card {
    transition: var(--transition);
}

.inventory-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.inventory-icon-wrapper {
    transition: var(--transition);
}

.inventory-summary-card:hover .inventory-icon-wrapper {
    transform: scale(1.1);
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
    box-shadow: 0 10px 25px rgba(255, 107, 107, 0.4);
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

/* Preview Card */
.preview-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #ff6b6b;
    transition: all 0.3s ease;
}

.preview-card:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-sm);
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-action,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }

    .progress-overview {
        padding: 15px;
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
// Sistema de Notificaciones Mejorado
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

    showValidationError(fields) {
        const fieldNames = {
            'Nombre': 'Nombre del Producto',
            'Categoria': 'Categoría',
            'Precio': 'Precio',
            'Cantidad': 'Cantidad',
            'Cantidad_minima': 'Cantidad Mínima',
            'Cantidad_maxima': 'Cantidad Máxima'
        };

        const fieldList = fields.map(f => `<span style="display: inline-block; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px; margin: 2px; font-size: 0.8rem;">${fieldNames[f] || f}</span>`).join(' ');
        
        this.show(
            `<div style="text-align: left; margin-top: 5px;">${fieldList}</div>`,
            'warning',
            'Campos requeridos'
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

class FormManager {
    constructor() {
        this.requiredFields = [
            'Nombre', 'Categoria', 'Precio', 'Cantidad', 'Cantidad_minima', 'Cantidad_maxima'
        ];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.updateProgress();
        this.initRealTimeValidation();
        this.initCategoryPreview();
        
        // Mostrar errores de validación del servidor si existen
        this.showServerErrors();
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
        document.getElementById('productoForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.validateAndSubmit();
        });

        window.resetForm = () => {
            if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
                document.getElementById('productoForm').reset();
                this.resetAllVisuals();
                notifier.showSuccess('Formulario restablecido correctamente');
            }
        };
    }

    initRealTimeValidation() {
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                    if (fieldId === 'Precio' || fieldId === 'Cantidad' || 
                        fieldId === 'Cantidad_minima' || fieldId === 'Cantidad_maxima') {
                        this.calcularInventario();
                    }
                });
                field.addEventListener('change', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                    this.calcularInventario();
                });
                field.addEventListener('blur', () => {
                    this.validateField(fieldId);
                });
            }
        });

        // Validación especial para select
        const categoriaField = document.getElementById('Categoria');
        if (categoriaField) {
            categoriaField.addEventListener('change', () => {
                this.validateField('Categoria');
                this.updateProgress();
                this.updateCategoryPreview();
            });
        }
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const wrapper = field.closest('.input-wrapper');
        if (!wrapper) return;

        wrapper.classList.remove('error', 'valid');

        if (field.hasAttribute('required') && !field.value.trim()) {
            wrapper.classList.add('error');
            return false;
        }

        // Validaciones específicas
        if (fieldId === 'Precio' && field.value) {
            const precio = parseFloat(field.value);
            if (precio <= 0) {
                wrapper.classList.add('error');
                return false;
            }
        }

        if ((fieldId === 'Cantidad' || fieldId === 'Cantidad_minima' || fieldId === 'Cantidad_maxima') && field.value) {
            const valor = parseInt(field.value);
            if (valor < 0) {
                wrapper.classList.add('error');
                return false;
            }
        }

        if (field.value.trim()) {
            wrapper.classList.add('valid');
        }
        
        return true;
    }

    validateAllFields() {
        const errors = [];
        
        if (!this.validateField('Nombre')) errors.push('Nombre');
        if (!this.validateField('Categoria')) errors.push('Categoria');
        if (!this.validateField('Precio')) errors.push('Precio');
        if (!this.validateField('Cantidad')) errors.push('Cantidad');
        if (!this.validateField('Cantidad_minima')) errors.push('Cantidad_minima');
        if (!this.validateField('Cantidad_maxima')) errors.push('Cantidad_maxima');

        // Validar relaciones entre cantidades
        const cantidad = parseInt(document.getElementById('Cantidad').value) || 0;
        const minima = parseInt(document.getElementById('Cantidad_minima').value) || 0;
        const maxima = parseInt(document.getElementById('Cantidad_maxima').value) || 0;

        if (minima > maxima && minima > 0 && maxima > 0) {
            errors.push('minima_maxima');
            notifier.showWarning('La cantidad mínima no puede ser mayor que la máxima');
        }

        if (cantidad < minima && cantidad > 0 && minima > 0) {
            errors.push('cantidad_minima');
            notifier.showWarning('La cantidad actual está por debajo del mínimo establecido');
        }

        if (cantidad > maxima && cantidad > 0 && maxima > 0) {
            errors.push('cantidad_maxima');
            notifier.showWarning('La cantidad actual supera el máximo establecido');
        }

        if (errors.length > 0 && !errors.includes('minima_maxima') && 
            !errors.includes('cantidad_minima') && !errors.includes('cantidad_maxima')) {
            notifier.showValidationError(errors);
            
            const firstErrorId = errors[0];
            const firstError = document.getElementById(firstErrorId);
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                const wrapper = firstError.closest('.input-wrapper');
                if (wrapper) {
                    wrapper.classList.add('shake-enhanced');
                    setTimeout(() => wrapper.classList.remove('shake-enhanced'), 800);
                }
            }
        }

        return errors.length === 0;
    }

    updateProgress() {
        const completedFields = this.requiredFields.filter(fieldId => {
            const field = document.getElementById(fieldId);
            return field && field.value.trim() !== '';
        }).length;

        const percentage = Math.round((completedFields / this.requiredFields.length) * 100);
        
        document.getElementById('formProgress').style.width = `${percentage}%`;
        document.getElementById('progressPercentage').textContent = `${percentage}% Completado`;
        document.getElementById('completedFields').textContent = `${completedFields} de ${this.requiredFields.length} campos completados`;
        document.getElementById('validFieldsCount').textContent = `${completedFields}/${this.requiredFields.length}`;
    }

    initCharacterCounters() {
        const textFields = [
            { id: 'Nombre', counter: 'nombreCount', max: 100 },
            { id: 'Descripcion', counter: 'descripcionCount', max: 200 }
        ];
        
        textFields.forEach(item => {
            const field = document.getElementById(item.id);
            if (field) {
                field.addEventListener('input', (e) => {
                    const length = e.target.value.length;
                    this.updateCharCounter(item.counter, length, item.max);
                });
                this.updateCharCounter(item.counter, field.value.length, item.max);
            }
        });
    }

    updateCharCounter(elementId, length, max) {
        const counter = document.getElementById(elementId);
        if (counter) {
            counter.textContent = `${length}/${max}`;
            counter.style.color = length > max ? '#ef4444' : length > 0 ? '#10b981' : '#9ca3af';
        }
    }

    initCategoryPreview() {
        const categoriaSelect = document.getElementById('Categoria');
        if (categoriaSelect) {
            categoriaSelect.addEventListener('change', () => this.updateCategoryPreview());
        }
    }

    updateCategoryPreview() {
        const categoriaSelect = document.getElementById('Categoria');
        const preview = document.getElementById('categoriaPreview');
        
        if (categoriaSelect && preview) {
            const selectedOption = categoriaSelect.options[categoriaSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const proveedor = selectedOption.getAttribute('data-proveedor') || 'Sin proveedor asociado';
                
                preview.style.display = 'block';
                document.getElementById('categoriaNombre').textContent = selectedOption.text;
                document.getElementById('categoriaProveedor').textContent = proveedor;
                
                preview.classList.add('updated');
                setTimeout(() => preview.classList.remove('updated'), 1000);
            } else {
                preview.style.display = 'none';
            }
        }
    }

    calcularInventario() {
        const precio = parseFloat(document.getElementById('Precio').value) || 0;
        const cantidad = parseInt(document.getElementById('Cantidad').value) || 0;
        const minima = parseInt(document.getElementById('Cantidad_minima').value) || 0;
        const maxima = parseInt(document.getElementById('Cantidad_maxima').value) || 0;
        
        // Calcular valor total
        const valorTotal = precio * cantidad;
        document.getElementById('valorTotal').textContent = `$${valorTotal.toFixed(2)}`;
        
        // Actualizar valores en tarjetas
        document.getElementById('stockActual').textContent = cantidad;
        document.getElementById('stockMinimo').textContent = minima;
        document.getElementById('stockMaximo').textContent = maxima;
        
        // Actualizar barra de progreso
        if (maxima > 0) {
            const porcentaje = Math.min((cantidad / maxima) * 100, 100);
            const progressBar = document.getElementById('stockProgress');
            progressBar.style.width = `${porcentaje}%`;
            
            // Cambiar color según nivel
            if (cantidad < minima) {
                progressBar.style.background = 'linear-gradient(90deg, #dc3545, #ff6b6b)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Stock crítico - por debajo del mínimo';
                document.getElementById('stockStatus').className = 'text-danger';
            } else if (cantidad > maxima) {
                progressBar.style.background = 'linear-gradient(90deg, #ffc107, #ffa726)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Stock excede capacidad máxima';
                document.getElementById('stockStatus').className = 'text-warning';
            } else if (cantidad <= minima * 1.5 && minima > 0) {
                progressBar.style.background = 'linear-gradient(90deg, #ffc107, #ffa726)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-info-circle me-1"></i>Stock cercano al mínimo';
                document.getElementById('stockStatus').className = 'text-warning';
            } else {
                progressBar.style.background = 'linear-gradient(90deg, #28a745, #20c997)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-check-circle me-1"></i>Stock en niveles óptimos';
                document.getElementById('stockStatus').className = 'text-success';
            }
        } else {
            document.getElementById('stockProgress').style.width = '0%';
            document.getElementById('stockStatus').innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese valores de inventario';
            document.getElementById('stockStatus').className = 'text-muted';
        }
    }

    validateAndSubmit() {
        if (this.validateAllFields()) {
            this.submitForm();
        }
    }

    submitForm() {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('productoForm');

        if (submitBtn && form) {
            notifier.showInfo('Procesando solicitud...', 'Un momento por favor');
            
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            setTimeout(() => {
                form.submit();
            }, 500);
        }
    }

    resetAllVisuals() {
        this.updateCharCounter('nombreCount', 0, 100);
        this.updateCharCounter('descripcionCount', 0, 200);

        document.querySelectorAll('.input-wrapper').forEach(wrapper => {
            wrapper.classList.remove('error', 'valid');
        });

        document.querySelectorAll('.error-message').forEach(msg => {
            msg.style.display = 'none';
        });

        document.getElementById('categoriaPreview').style.display = 'none';
        this.calcularInventario();
        this.updateProgress();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FormManager();
});
</script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection