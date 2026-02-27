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
                                <i class="fas fa-edit fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Editar Producto
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Actualice la información del producto #{{ $producto->id }}
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
                                        100% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #ff6b6b, #ffa726); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">6 de 6 campos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="productoForm" action="{{ route('productos.update', $producto->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

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
                                    <div class="col-md-8">
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
                                                       value="{{ old('Nombre', $producto->Nombre) }}" 
                                                       placeholder="Ej: Laptop Dell XPS 15" 
                                                       required 
                                                       maxlength="100"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">{{ strlen($producto->Nombre) }}/100</span>
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
                                    <div class="col-md-4">
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
                                                    <option value="" disabled>Seleccionar categoría</option>
                                                    @foreach($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}" 
                                                            {{ old('Categoria', $producto->Categoria) == $categoria->id ? 'selected' : '' }}
                                                            data-proveedor="{{ $categoria->proveedor->Nombre ?? 'Sin proveedor' }}">
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
                                                          data-char-counter="descripcionCount">{{ old('Descripcion', $producto->Descripcion) }}</textarea>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="descripcionCount">{{ strlen($producto->Descripcion ?? '') }}/200</span>
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
                                        <div class="preview-card p-3" id="categoriaPreview" style="display: {{ $producto->Categoria ? 'block' : 'none' }};">
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
                                                    <h6 class="mb-1 fw-bold" id="categoriaNombre">
                                                        {{ $categorias->find($producto->Categoria)->Nombre ?? 'Nombre de categoría' }}
                                                    </h6>
                                                    <small class="text-muted d-flex align-items-center">
                                                        <i class="fas fa-truck me-2"></i>
                                                        <span id="categoriaProveedor">
                                                            {{ $categorias->find($producto->Categoria)->proveedor->Nombre ?? 'Proveedor asociado' }}
                                                        </span>
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
                                    <div class="col-md-4">
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
                                                       value="{{ old('Precio', $producto->Precio) }}" 
                                                       placeholder="0.00" 
                                                       step="0.01" 
                                                       min="0.01" 
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

                                    <!-- Cantidad -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Stock Actual</span>
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
                                                       value="{{ old('Cantidad', $producto->Cantidad) }}" 
                                                       placeholder="0" 
                                                       min="0" 
                                                       required>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Cantidad actual en inventario
                                                </div>
                                            </div>
                                            
                                            @error('Cantidad') 
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Estado del Stock -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Estado del Stock</span>
                                            </label>
                                            
                                            <div class="status-card p-3" id="estadoStockCard" style="
                                                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                                border-radius: 12px;
                                                border-left: 5px solid;
                                                transition: all 0.3s ease;
                                            ">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $cantidad = $producto->Cantidad;
                                                        $minima = $producto->Cantidad_minima;
                                                        $maxima = $producto->Cantidad_maxima;
                                                        
                                                        if($cantidad == 0) {
                                                            $icono = 'fa-times-circle';
                                                            $texto = 'Sin Stock';
                                                            $color = 'danger';
                                                            $borderColor = '#dc3545';
                                                        } elseif($cantidad > $maxima) {
                                                            $icono = 'fa-exclamation-triangle';
                                                            $texto = 'Stock Excedido';
                                                            $color = 'warning';
                                                            $borderColor = '#ffc107';
                                                        } elseif($cantidad <= $minima) {
                                                            $icono = 'fa-exclamation-triangle';
                                                            $texto = 'Stock Bajo';
                                                            $color = 'warning';
                                                            $borderColor = '#ffc107';
                                                        } else {
                                                            $icono = 'fa-check-circle';
                                                            $texto = 'Stock Normal';
                                                            $color = 'success';
                                                            $borderColor = '#28a745';
                                                        }
                                                    @endphp
                                                    <i class="fas {{ $icono }} text-{{ $color }} me-3 fs-4"></i>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-{{ $color }}" id="estadoStockText">{{ $texto }}</h6>
                                                        <small class="text-muted">Actualizado en tiempo real</small>
                                                    </div>
                                                </div>
                                                <style>
                                                    #estadoStockCard {
                                                        border-left-color: {{ $borderColor }} !important;
                                                    }
                                                </style>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stock Mínimo -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Stock Mínimo</span>
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
                                                       value="{{ old('Cantidad_minima', $producto->Cantidad_minima) }}" 
                                                       placeholder="0" 
                                                       min="0" 
                                                       required>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #ff6b6b, #ffa726);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Se alertará cuando el stock llegue a este nivel
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

                                    <!-- Stock Máximo -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Stock Máximo</span>
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
                                                       value="{{ old('Cantidad_maxima', $producto->Cantidad_maxima) }}" 
                                                       placeholder="0" 
                                                       min="1" 
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

                                    <!-- Valor Total -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Valor Total del Inventario</span>
                                            </label>
                                            
                                            <div class="preview-card p-3" style="
                                                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                                border-radius: 12px;
                                                border-left: 5px solid #28a745;
                                            ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold" id="valorTotal">
                                                            ${{ number_format($producto->Precio * $producto->Cantidad, 2) }}
                                                        </h6>
                                                        <small class="text-muted">Valor del inventario</small>
                                                    </div>
                                                    <i class="fas fa-chart-line text-success fs-4"></i>
                                                </div>
                                            </div>
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
                                                            <h4 class="mb-1 fw-bold" id="stockActual">{{ $producto->Cantidad }}</h4>
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
                                                            <h4 class="mb-1 fw-bold" id="stockMinimo">{{ $producto->Cantidad_minima }}</h4>
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
                                                            <h4 class="mb-1 fw-bold" id="stockMaximo">{{ $producto->Cantidad_maxima }}</h4>
                                                            <small class="text-muted">Máximo</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="progress mt-4" style="height: 10px; border-radius: 10px;">
                                                    <div class="progress-bar" id="stockProgress" role="progressbar" 
                                                         style="width: {{ min(($producto->Cantidad / max($producto->Cantidad_maxima, 1)) * 100, 100) }}%; background: linear-gradient(90deg, #ff6b6b, #ffa726); border-radius: 10px;"></div>
                                                </div>
                                                
                                                <div class="text-center mt-3">
                                                    <small class="text-muted" id="stockStatus">
                                                        @php
                                                            if($producto->Cantidad < 0) {
                                                                echo '<i class="fas fa-exclamation-circle me-1 text-secondary"></i>Error: Stock negativo';
                                                            } elseif($producto->Cantidad > $producto->Cantidad_maxima) {
                                                                echo '<i class="fas fa-exclamation-triangle me-1 text-warning"></i>Stock excede capacidad máxima';
                                                            } elseif($producto->Cantidad < $producto->Cantidad_minima) {
                                                                echo '<i class="fas fa-exclamation-circle me-1 text-danger"></i>Stock crítico - por debajo del mínimo';
                                                            } elseif($producto->Cantidad <= $producto->Cantidad_minima * 1.5) {
                                                                echo '<i class="fas fa-info-circle me-1 text-warning"></i>Stock cercano al mínimo';
                                                            } else {
                                                                echo '<i class="fas fa-check-circle me-1 text-success"></i>Stock en niveles óptimos';
                                                            }
                                                        @endphp
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
                                                <span id="validFieldsCount">6/6</span> completados
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="button" class="btn btn-action btn-outline-secondary" onclick="resetForm()">
                                            <i class="fas fa-redo me-2"></i>
                                            Restaurar
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
                                                Actualizar Producto
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
    border-color: var(--danger-color) !important;
    animation: shake 0.5s;
}

.input-wrapper.error .input-decoration {
    background: linear-gradient(90deg, #ef4444, #ff6b6b);
}

/* Indicador visual para campos con error */
.input-wrapper.error::after {
    content: '!';
    position: absolute;
    top: 50%;
    right: 16px;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    z-index: 2;
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

/* Status Card */
.status-card {
    transition: var(--transition);
}

.status-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
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
    flex-wrap: wrap;
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

.btn-submit.btn-disabled {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
    background: #6c757d !important;
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

.btn-submit:hover:not(.loading):not(.btn-disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 107, 107, 0.4);
}

.btn-submit:active:not(.loading):not(.btn-disabled) {
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

.field-error-message {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    animation: slideIn 0.3s ease;
    padding: 4px 8px;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 6px;
}

.field-error-message i {
    font-size: 0.9rem;
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
    
    .form-stats {
        justify-content: center;
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
            'Cantidad': 'Stock Actual',
            'Cantidad_minima': 'Stock Mínimo',
            'Cantidad_maxima': 'Stock Máximo'
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
        this.originalData = {
            nombre: '{{ $producto->Nombre }}',
            descripcion: '{{ $producto->Descripcion }}',
            categoria: '{{ $producto->Categoria }}',
            precio: '{{ $producto->Precio }}',
            cantidad: '{{ $producto->Cantidad }}',
            cantidad_minima: '{{ $producto->Cantidad_minima }}',
            cantidad_maxima: '{{ $producto->Cantidad_maxima }}'
        };
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.updateProgress();
        this.initRealTimeValidation();
        this.initCategoryPreview();
        this.calcularInventario();
        
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
            if (confirm('¿Está seguro de que desea restaurar los valores originales?')) {
                this.restoreOriginalValues();
                notifier.showSuccess('Valores restaurados correctamente');
            }
        };
    }

    restoreOriginalValues() {
        document.getElementById('Nombre').value = this.originalData.nombre;
        document.getElementById('Descripcion').value = this.originalData.descripcion;
        document.getElementById('Categoria').value = this.originalData.categoria;
        document.getElementById('Precio').value = this.originalData.precio;
        document.getElementById('Cantidad').value = this.originalData.cantidad;
        document.getElementById('Cantidad_minima').value = this.originalData.cantidad_minima;
        document.getElementById('Cantidad_maxima').value = this.originalData.cantidad_maxima;
        
        this.updateCharCounter('nombreCount', this.originalData.nombre.length, 100);
        this.updateCharCounter('descripcionCount', this.originalData.descripcion.length, 200);
        
        this.calcularInventario();
        this.updateCategoryPreview();
        this.updateProgress();
        
        // Quitar errores
        document.querySelectorAll('.input-wrapper').forEach(wrapper => {
            wrapper.classList.remove('error', 'valid');
        });
        document.querySelectorAll('.field-error-message').forEach(msg => msg.remove());
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
        if (!field) return true;

        const wrapper = field.closest('.input-wrapper');
        if (!wrapper) return true;

        // Remover mensajes de error previos
        const existingError = wrapper.closest('.form-group-enhanced').querySelector('.field-error-message');
        if (existingError) existingError.remove();

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
                this.showFieldError(fieldId, 'El precio debe ser mayor a 0');
                return false;
            }
        }

        if ((fieldId === 'Cantidad' || fieldId === 'Cantidad_minima' || fieldId === 'Cantidad_maxima') && field.value) {
            const valor = parseInt(field.value);
            if (valor < 0) {
                wrapper.classList.add('error');
                this.showFieldError(fieldId, 'El valor no puede ser negativo');
                return false;
            }
        }

        // Validación especial para Cantidad contra mínimos y máximos
        if (fieldId === 'Cantidad' && field.value) {
            const cantidad = parseInt(field.value) || 0;
            const minima = parseInt(document.getElementById('Cantidad_minima').value) || 0;
            const maxima = parseInt(document.getElementById('Cantidad_maxima').value) || 0;
            
            if (cantidad < 0) {
                wrapper.classList.add('error');
                this.showFieldError(fieldId, 'La cantidad no puede ser negativa');
                return false;
            }
            
            if (minima > 0 && minima < maxima && cantidad < minima) {
                wrapper.classList.add('error');
                this.showFieldError(fieldId, `La cantidad (${cantidad}) no puede ser menor al mínimo (${minima})`);
                return false;
            }
            
            if (maxima > 0 && minima < maxima && cantidad > maxima) {
                wrapper.classList.add('error');
                this.showFieldError(fieldId, `La cantidad (${cantidad}) no puede ser mayor al máximo (${maxima})`);
                return false;
            }
        }

        // Validación de consistencia entre mínimo y máximo
        if (fieldId === 'Cantidad_minima' || fieldId === 'Cantidad_maxima') {
            const minima = parseInt(document.getElementById('Cantidad_minima').value) || 0;
            const maxima = parseInt(document.getElementById('Cantidad_maxima').value) || 0;
            
            if (minima >= maxima && minima > 0 && maxima > 0) {
                wrapper.classList.add('error');
                this.showFieldError(fieldId, minima >= maxima ? 
                    'El mínimo debe ser menor que el máximo' : 'El máximo debe ser mayor que el mínimo');
                
                // También marcar el otro campo como error
                const otroCampo = fieldId === 'Cantidad_minima' ? 'Cantidad_maxima' : 'Cantidad_minima';
                const otroWrapper = document.getElementById(otroCampo)?.closest('.input-wrapper');
                if (otroWrapper) {
                    otroWrapper.classList.add('error');
                    this.showFieldError(otroCampo, minima >= maxima ? 
                        'El máximo debe ser mayor que el mínimo' : 'El mínimo debe ser menor que el máximo');
                }
                
                return false;
            }
        }

        if (field.value.trim()) {
            wrapper.classList.add('valid');
        }
        
        return true;
    }

    showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        // Buscar o crear elemento de error
        let errorElement = field.closest('.form-group-enhanced').querySelector('.field-error-message');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error-message animated';
            field.closest('.form-group-enhanced').appendChild(errorElement);
        }
        
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        // Remover el error después de 3 segundos
        setTimeout(() => {
            if (errorElement && errorElement.parentNode) {
                errorElement.remove();
            }
        }, 3000);
    }

    validateAllFields() {
        const errors = [];
        let hasCriticalError = false;
        
        // Validar campos requeridos
        this.requiredFields.forEach(fieldId => {
            if (!this.validateField(fieldId)) {
                errors.push(fieldId);
            }
        });

        // Obtener valores
        const cantidad = parseInt(document.getElementById('Cantidad').value) || 0;
        const minima = parseInt(document.getElementById('Cantidad_minima').value) || 0;
        const maxima = parseInt(document.getElementById('Cantidad_maxima').value) || 0;

        // Validar relaciones entre cantidades
        if (minima >= maxima && minima > 0 && maxima > 0) {
            hasCriticalError = true;
            notifier.showWarning('El stock mínimo debe ser menor que el máximo');
            
            document.getElementById('Cantidad_minima').closest('.input-wrapper').classList.add('error');
            document.getElementById('Cantidad_maxima').closest('.input-wrapper').classList.add('error');
            this.showFieldError('Cantidad_minima', 'El mínimo debe ser menor que el máximo');
        }

        if (cantidad < 0) {
            hasCriticalError = true;
            notifier.showWarning('La cantidad no puede ser negativa');
            document.getElementById('Cantidad').closest('.input-wrapper').classList.add('error');
            this.showFieldError('Cantidad', 'La cantidad no puede ser negativa');
        }

        // Validar cantidad contra mínimos y máximos (solo si mínimos y máximos son válidos)
        if (minima < maxima) {
            if (cantidad < minima) {
                hasCriticalError = true;
                notifier.showWarning(`La cantidad actual (${cantidad}) es menor al mínimo permitido (${minima})`);
                document.getElementById('Cantidad').closest('.input-wrapper').classList.add('error');
                this.showFieldError('Cantidad', `La cantidad no puede ser menor a ${minima}`);
            }
            
            if (cantidad > maxima) {
                hasCriticalError = true;
                notifier.showWarning(`La cantidad actual (${cantidad}) es mayor al máximo permitido (${maxima})`);
                document.getElementById('Cantidad').closest('.input-wrapper').classList.add('error');
                this.showFieldError('Cantidad', `La cantidad no puede ser mayor a ${maxima}`);
            }
        }

        // Mostrar errores de campos requeridos
        if (errors.length > 0 && !hasCriticalError) {
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

        const submitBtn = document.getElementById('submitBtn');
        if (errors.length > 0 || hasCriticalError) {
            submitBtn.classList.add('btn-disabled');
            return false;
        } else {
            submitBtn.classList.remove('btn-disabled');
            return true;
        }
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
        
        // Validar cantidad contra límites
        const cantidadWrapper = document.getElementById('Cantidad').closest('.input-wrapper');
        const minimaWrapper = document.getElementById('Cantidad_minima').closest('.input-wrapper');
        const maximaWrapper = document.getElementById('Cantidad_maxima').closest('.input-wrapper');
        
        // Remover mensajes de error previos
        const errorMsg = cantidadWrapper?.closest('.form-group-enhanced')?.querySelector('.field-error-message');
        if (errorMsg) errorMsg.remove();
        
        // Calcular valor total
        const valorTotal = precio * cantidad;
        document.getElementById('valorTotal').textContent = `$${valorTotal.toFixed(2)}`;
        
        // Actualizar valores en tarjetas
        document.getElementById('stockActual').textContent = cantidad;
        document.getElementById('stockMinimo').textContent = minima;
        document.getElementById('stockMaximo').textContent = maxima;
        
        // Actualizar estado del stock
        this.actualizarEstadoStock(cantidad, minima, maxima);
        
        // Actualizar barra de progreso
        if (maxima > 0) {
            const porcentaje = Math.min((cantidad / maxima) * 100, 100);
            const progressBar = document.getElementById('stockProgress');
            progressBar.style.width = `${porcentaje}%`;
            
            // Cambiar color según nivel
            if (cantidad < 0) {
                progressBar.style.background = 'linear-gradient(90deg, #6c757d, #495057)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-exclamation-circle me-1 text-secondary"></i>Error: Stock negativo';
                document.getElementById('stockStatus').className = 'text-secondary fw-bold';
            } else if (cantidad > maxima) {
                progressBar.style.background = 'linear-gradient(90deg, #ffc107, #ffa726)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-exclamation-triangle me-1 text-warning"></i>Stock excede capacidad máxima';
                document.getElementById('stockStatus').className = 'text-warning';
            } else if (cantidad < minima) {
                progressBar.style.background = 'linear-gradient(90deg, #dc3545, #ff6b6b)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-exclamation-circle me-1 text-danger"></i>Stock crítico - por debajo del mínimo';
                document.getElementById('stockStatus').className = 'text-danger';
            } else if (cantidad <= minima * 1.5 && minima > 0) {
                progressBar.style.background = 'linear-gradient(90deg, #ffc107, #ffa726)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-info-circle me-1 text-warning"></i>Stock cercano al mínimo';
                document.getElementById('stockStatus').className = 'text-warning';
            } else {
                progressBar.style.background = 'linear-gradient(90deg, #28a745, #20c997)';
                document.getElementById('stockStatus').innerHTML = '<i class="fas fa-check-circle me-1 text-success"></i>Stock en niveles óptimos';
                document.getElementById('stockStatus').className = 'text-success';
            }
        } else {
            document.getElementById('stockProgress').style.width = '0%';
            document.getElementById('stockStatus').innerHTML = '<i class="fas fa-info-circle me-1"></i>Ingrese valores de inventario';
            document.getElementById('stockStatus').className = 'text-muted';
        }
        
        // Actualizar botón de submit
        this.validateAllFields();
    }

    actualizarEstadoStock(cantidad, minima, maxima) {
        const estadoCard = document.getElementById('estadoStockCard');
        const estadoText = document.getElementById('estadoStockText');
        const icono = estadoCard.querySelector('i');
        
        let iconoClass, texto, color, borderColor;
        
        if (cantidad < 0) {
            iconoClass = 'fa-exclamation-circle';
            texto = 'Stock Negativo';
            color = 'secondary';
            borderColor = '#6c757d';
        } else if (cantidad === 0) {
            iconoClass = 'fa-times-circle';
            texto = 'Sin Stock';
            color = 'danger';
            borderColor = '#dc3545';
        } else if (cantidad > maxima) {
            iconoClass = 'fa-exclamation-triangle';
            texto = 'Stock Excedido';
            color = 'warning';
            borderColor = '#ffc107';
        } else if (cantidad <= minima) {
            iconoClass = 'fa-exclamation-triangle';
            texto = 'Stock Bajo';
            color = 'warning';
            borderColor = '#ffc107';
        } else {
            iconoClass = 'fa-check-circle';
            texto = 'Stock Normal';
            color = 'success';
            borderColor = '#28a745';
        }
        
        icono.className = `fas ${iconoClass} text-${color} me-3 fs-4`;
        estadoText.textContent = texto;
        estadoText.className = `mb-0 fw-bold text-${color}`;
        estadoCard.style.borderLeftColor = borderColor;
    }

    checkForChanges() {
        const currentData = {
            nombre: document.getElementById('Nombre').value.trim(),
            descripcion: document.getElementById('Descripcion').value.trim(),
            categoria: document.getElementById('Categoria').value,
            precio: document.getElementById('Precio').value,
            cantidad: document.getElementById('Cantidad').value,
            cantidad_minima: document.getElementById('Cantidad_minima').value,
            cantidad_maxima: document.getElementById('Cantidad_maxima').value
        };

        return JSON.stringify(this.originalData) !== JSON.stringify(currentData);
    }

    validateAndSubmit() {
        if (!this.checkForChanges()) {
            Swal.fire({
                icon: 'info',
                title: 'Sin Cambios',
                text: 'No se han realizado modificaciones en los datos.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ff6b6b',
            });
            return;
        }

        if (this.validateAllFields()) {
            this.confirmAndSubmit();
        }
    }

    confirmAndSubmit() {
        const cantidad = parseInt(document.getElementById('Cantidad').value) || 0;
        const minima = parseInt(document.getElementById('Cantidad_minima').value) || 0;
        const maxima = parseInt(document.getElementById('Cantidad_maxima').value) || 0;
        const precio = parseFloat(document.getElementById('Precio').value) || 0;

        Swal.fire({
            title: '¿Actualizar Producto?',
            html: `
                <div class="text-start">
                    <p><strong>Producto:</strong> ${document.getElementById('Nombre').value}</p>
                    <p><strong>Categoría:</strong> ${document.getElementById('Categoria').options[document.getElementById('Categoria').selectedIndex]?.text || 'Sin categoría'}</p>
                    <p><strong>Precio:</strong> $${precio.toFixed(2)} | <strong>Stock:</strong> ${cantidad}</p>
                    <p><strong>Límites:</strong> Mín: ${minima} | Máx: ${maxima}</p>
                    <p><strong>Valor Total:</strong> $${(precio * cantidad).toFixed(2)}</p>
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
                this.submitForm();
            }
        });
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
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FormManager();
});
</script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Include SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection