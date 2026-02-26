@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <!-- Fondo decorativo -->
    <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-xxl-block" style="
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.03) 100%);
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
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
                                animation: float 6s ease-in-out infinite;
                            ">
                                <i class="fas fa-tags fa-lg"></i>
                            </div>
                            <div>
                                <h1 class="h3 fw-800 mb-1" style="
                                    background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                                    -webkit-background-clip: text;
                                    -webkit-text-fill-color: transparent;
                                    letter-spacing: -0.5px;
                                ">
                                    Editar Categoría
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Actualice la información de la categoría
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
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
                                        50% Completado
                                    </span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: #e5e7eb;">
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 50%; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">2 de 2 campos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="categoriaForm" action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <!-- Sección 1: Información Básica -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información Básica</h3>
                                            <p class="section-subtitle mb-0">Datos principales de la categoría</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #667eea, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Nombre de la Categoría -->
                                    <div class="col-md-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Nombre de la Categoría</span>
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
                                                       value="{{ old('Nombre', $categoria->Nombre) }}" 
                                                       placeholder="Ej: Electrónica, Ropa, Hogar" 
                                                       required 
                                                       minlength="3"
                                                       maxlength="100"
                                                       data-char-counter="nombreCount">
                                                <div class="input-decoration" style="background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="nombreCount">{{ strlen($categoria->Nombre) }}/100</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Mínimo 3 caracteres
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

                                    <!-- Descripción -->
                                    <div class="col-md-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Descripción</span>
                                                <span class="label-optional">(Opcional)</span>
                                            </label>
                                            
                                            <div class="textarea-wrapper">
                                                <div class="input-icon" style="top: 24px; transform: none;">
                                                    <i class="fas fa-align-left"></i>
                                                </div>
                                                <textarea class="textarea-field @error('Descripcion') is-invalid @enderror" 
                                                          id="Descripcion" 
                                                          name="Descripcion" 
                                                          placeholder="Describe las características de esta categoría..."
                                                          maxlength="200"
                                                          rows="4"
                                                          data-char-counter="descripcionCount">{{ old('Descripcion', $categoria->Descripcion) }}</textarea>
                                                <div class="input-decoration" style="background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="char-counter">
                                                    <i class="fas fa-text-height"></i>
                                                    <span id="descripcionCount">{{ strlen($categoria->Descripcion ?? '') }}/200</span>
                                                </div>
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Ejemplo: Muebles para sala como sofás, sillones, mesas de centro...
                                                </div>
                                            </div>
                                            
                                            @error('Descripcion')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Proveedor Asociado -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Proveedor Asociado</h3>
                                            <p class="section-subtitle mb-0">Selecciona el proveedor principal de esta categoría</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #fa709a, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Proveedor</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="select-wrapper" data-required="true">
                                                <div class="select-icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <select class="select-field @error('Proveedor') is-invalid @enderror" 
                                                        id="Proveedor" name="Proveedor" required>
                                                    <option value="" disabled selected hidden>Seleccione un proveedor...</option>
                                                    @foreach($proveedores as $proveedor)
                                                        <option value="{{ $proveedor->id }}" 
                                                                {{ old('Proveedor', $categoria->Proveedor) == $proveedor->id ? 'selected' : '' }}
                                                                data-nombre="{{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} {{ $proveedor->ApMaterno ? ' ' . $proveedor->ApMaterno : '' }}"
                                                                data-empresa="{{ $proveedor->Empresa_asociada ?? 'Sin empresa' }}"
                                                                data-telefono="{{ $proveedor->Telefono ?? 'Sin teléfono' }}"
                                                                data-correo="{{ $proveedor->Correo ?? 'Sin correo' }}">
                                                            {{ $proveedor->Nombre }} {{ $proveedor->ApPaterno }} 
                                                            {{ $proveedor->ApMaterno ? ' ' . $proveedor->ApMaterno : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="select-decoration" style="background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-info-circle"></i>
                                                    Seleccione un proveedor de la lista
                                                </div>
                                            </div>
                                            
                                            <!-- Preview del proveedor seleccionado -->
                                            <div class="preview-card mt-3" id="proveedorPreview" style="display: {{ $categoria->Proveedor ? 'block' : 'none' }};">
                                                <div class="preview-header">
                                                    <i class="fas fa-user-check text-success"></i>
                                                    <span>Proveedor seleccionado</span>
                                                </div>
                                                <div class="preview-body">
                                                    <div class="preview-row">
                                                        <i class="fas fa-user"></i>
                                                        <span id="proveedorNombre">
                                                            @php
                                                                $proveedorActual = $proveedores->firstWhere('id', $categoria->Proveedor);
                                                                if($proveedorActual) {
                                                                    echo $proveedorActual->Nombre . ' ' . $proveedorActual->ApPaterno . ' ' . ($proveedorActual->ApMaterno ?? '');
                                                                }
                                                            @endphp
                                                        </span>
                                                    </div>
                                                    <div class="preview-row">
                                                        <i class="fas fa-building"></i>
                                                        <span id="proveedorEmpresa">
                                                            @if($proveedorActual && $proveedorActual->Empresa_asociada)
                                                                {{ $proveedorActual->Empresa_asociada }}
                                                            @else
                                                                Sin empresa registrada
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="preview-row">
                                                        <i class="fas fa-phone"></i>
                                                        <span id="proveedorTelefono">
                                                            @if($proveedorActual && $proveedorActual->Telefono)
                                                                {{ $proveedorActual->Telefono }}
                                                            @else
                                                                Sin teléfono
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="preview-row">
                                                        <i class="fas fa-envelope"></i>
                                                        <span id="proveedorCorreo">
                                                            @if($proveedorActual && $proveedorActual->Correo)
                                                                {{ $proveedorActual->Correo }}
                                                            @else
                                                                Sin correo
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @error('Proveedor')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Campo oculto para el ID -->
                            <input type="hidden" name="id" value="{{ $categoria->id }}">

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
                                                <span id="validFieldsCount">2/2</span> completados
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-submit" id="submitBtn" style="
                                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                                                Actualizar Categoría
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

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
.input-wrapper,
.textarea-wrapper,
.select-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: var(--transition);
    overflow: hidden;
}

.input-wrapper:focus-within,
.textarea-wrapper:focus-within,
.select-wrapper:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.input-wrapper.error,
.textarea-wrapper.error,
.select-wrapper.error {
    border-color: var(--danger-color);
    animation: shake 0.5s;
}

.input-wrapper.valid,
.textarea-wrapper.valid,
.select-wrapper.valid {
    border-color: var(--success-color);
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 1.1rem;
    z-index: 2;
}

.input-field,
.textarea-field,
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

.textarea-field {
    padding: 20px 20px 20px 48px;
    resize: vertical;
    min-height: 120px;
}

.input-field::placeholder,
.textarea-field::placeholder {
    color: #9ca3af;
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
.textarea-wrapper:focus-within .input-decoration,
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

/* Select Wrapper */
.select-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    z-index: 2;
}

.select-field {
    padding: 16px 48px 16px 48px;
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

.select-wrapper:focus-within .select-arrow {
    transform: translateY(-50%) rotate(180deg);
}

/* Preview Card */
.preview-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border-left: 5px solid #667eea;
    overflow: hidden;
    animation: slideIn 0.4s ease;
}

.preview-header {
    background: rgba(102, 126, 234, 0.1);
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #667eea;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.preview-body {
    padding: 15px;
}

.preview-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 6px 0;
    font-size: 0.9rem;
}

.preview-row i {
    width: 20px;
    color: #6b7280;
}

.preview-row span {
    color: #374151;
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

.is-valid {
    border-color: var(--success-color) !important;
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
    
    .btn-action,
    .btn-submit {
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
}
</style>

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

    showValidationError(fields) {
        const fieldNames = {
            'Nombre': 'Nombre de la Categoría',
            'Proveedor': 'Proveedor'
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
        this.requiredFields = ['Nombre', 'Proveedor'];
        this.originalData = {
            nombre: '{{ $categoria->Nombre }}',
            proveedor: '{{ $categoria->Proveedor }}'
        };
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCharacterCounters();
        this.initProveedorPreview();
        this.updateProgress();
        this.initRealTimeValidation();
    }

    setupEventListeners() {
        document.getElementById('categoriaForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.validateAndSubmit();
        });
    }

    initRealTimeValidation() {
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                });
                field.addEventListener('change', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                });
                field.addEventListener('blur', () => {
                    this.validateField(fieldId);
                });
            }
        });
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const wrapper = field.closest('.input-wrapper, .textarea-wrapper, .select-wrapper');
        if (!wrapper) return;

        wrapper.classList.remove('error', 'valid');

        if (field.hasAttribute('required') && !field.value.trim()) {
            wrapper.classList.add('error');
            return false;
        }

        if (fieldId === 'Nombre') {
            if (field.value.trim().length < 3) {
                wrapper.classList.add('error');
                return false;
            }
        }

        wrapper.classList.add('valid');
        return true;
    }

    validateAllFields() {
        const errors = [];
        
        if (!this.validateField('Nombre')) errors.push('Nombre');
        if (!this.validateField('Proveedor')) errors.push('Proveedor');

        if (errors.length > 0) {
            notifier.showValidationError(errors);
            
            const firstErrorId = errors[0];
            const firstError = document.getElementById(firstErrorId);
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                const wrapper = firstError.closest('.input-wrapper, .textarea-wrapper, .select-wrapper');
                if (wrapper) {
                    wrapper.classList.add('shake-enhanced');
                    setTimeout(() => wrapper.classList.remove('shake-enhanced'), 800);
                }
            }
        }

        return errors.length === 0;
    }

    hasChanges() {
        const currentData = {
            nombre: document.getElementById('Nombre').value.trim(),
            proveedor: document.getElementById('Proveedor').value
        };
        
        return JSON.stringify(this.originalData) !== JSON.stringify(currentData);
    }

    updateProgress() {
        const completedFields = this.requiredFields.filter(fieldId => {
            const field = document.getElementById(fieldId);
            if (fieldId === 'Nombre') {
                return field && field.value.trim().length >= 3;
            }
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
                    
                    if (item.id === 'Nombre') {
                        this.validateField('Nombre');
                        this.updateProgress();
                    }
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

    initProveedorPreview() {
        const proveedorSelect = document.getElementById('Proveedor');
        const preview = document.getElementById('proveedorPreview');

        if (proveedorSelect) {
            proveedorSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    const nombre = selectedOption.getAttribute('data-nombre');
                    const empresa = selectedOption.getAttribute('data-empresa');
                    const telefono = selectedOption.getAttribute('data-telefono');
                    const correo = selectedOption.getAttribute('data-correo');
                    
                    document.getElementById('proveedorNombre').textContent = nombre;
                    document.getElementById('proveedorEmpresa').textContent = empresa;
                    document.getElementById('proveedorTelefono').textContent = telefono;
                    document.getElementById('proveedorCorreo').textContent = correo;
                    
                    preview.style.display = 'block';
                    preview.classList.add('updated');
                    setTimeout(() => preview.classList.remove('updated'), 1000);
                } else {
                    preview.style.display = 'none';
                }
                
                this.validateField('Proveedor');
                this.updateProgress();
            }.bind(this));
        }
    }

    validateAndSubmit() {
        if (!this.hasChanges()) {
            Swal.fire({
                icon: 'info',
                title: 'Sin Cambios',
                text: 'No se han realizado modificaciones en los datos.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#667eea',
            });
            return;
        }

        if (this.validateAllFields()) {
            this.submitForm();
        }
    }

    submitForm() {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('categoriaForm');
        const nombre = document.getElementById('Nombre').value;
        const proveedorSelect = document.getElementById('Proveedor');
        const proveedorNombre = proveedorSelect.options[proveedorSelect.selectedIndex]?.text || '';

        Swal.fire({
            title: '¿Actualizar Categoría?',
            html: `
                <div class="text-start">
                    <p><strong>Categoría Actualizada:</strong></p>
                    <p><strong>Nombre:</strong> ${nombre}</p>
                    <p><strong>Proveedor:</strong> ${proveedorNombre}</p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción actualizará permanentemente los datos de la categoría.
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loader
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                notifier.showInfo('Actualizando categoría...', 'Un momento por favor');
                
                // Enviar formulario
                setTimeout(() => {
                    form.submit();
                }, 500);
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new FormManager();
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
    background: linear-gradient(135deg, #10b981, #059669);
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

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection