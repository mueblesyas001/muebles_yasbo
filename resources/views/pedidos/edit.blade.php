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
                                background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
                                border-radius: 16px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                box-shadow: 0 8px 25px rgba(67, 97, 238, 0.25);
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
                                    Editar Pedido #{{ $pedido->id }}
                                </h1>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-pen me-1 text-primary"></i>
                                    Modifique los campos necesarios para actualizar el pedido
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="
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
                                <div class="progress-bar" id="formProgress" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #4361ee, #3a0ca3); border-radius: 10px; transition: width 0.5s ease;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted" id="completedFields">6 de 6 campos básicos completados</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Todos los campos con * son obligatorios
                                </small>
                            </div>
                        </div>

                        <form id="pedidoForm" action="{{ route('pedidos.update', $pedido->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <!-- Sección 1: Información General del Pedido -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Información General del Pedido</h3>
                                            <p class="section-subtitle mb-0">Datos básicos del pedido</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #4361ee, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <!-- Cliente -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Cliente</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <select class="input-field @error('Cliente_idCliente') is-invalid @enderror" 
                                                        id="cliente_id" name="Cliente_idCliente" required>
                                                    <option value="">Seleccionar cliente</option>
                                                    @foreach($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}" {{ old('Cliente_idCliente', $pedido->Cliente_idCliente) == $cliente->id ? 'selected' : '' }}>
                                                            {{ $cliente->Nombre }} {{ $cliente->ApPaterno ?? '' }} {{ $cliente->ApMaterno ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Cliente que realiza el pedido
                                                </div>
                                            </div>
                                            
                                            @error('Cliente_idCliente')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Empleado -->
                                    <div class="col-md-6">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Empleado</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <select class="input-field @error('Empleado_idEmpleado') is-invalid @enderror" 
                                                        id="empleado_id" name="Empleado_idEmpleado" required>
                                                    <option value="">Seleccionar empleado</option>
                                                    @foreach($empleados as $empleado)
                                                        <option value="{{ $empleado->id }}" {{ old('Empleado_idEmpleado', $pedido->Empleado_idEmpleado) == $empleado->id ? 'selected' : '' }}>
                                                            {{ $empleado->Nombre }} {{ $empleado->ApPaterno }} {{ $empleado->ApMaterno }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Empleado que atiende el pedido
                                                </div>
                                            </div>
                                            
                                            @error('Empleado_idEmpleado')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Fecha de Entrega -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Fecha de Entrega</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true" id="fecha-wrapper">
                                                <div class="input-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <input type="date" 
                                                       class="input-field @error('Fecha_entrega') is-invalid @enderror" 
                                                       id="fecha_entrega" 
                                                       name="Fecha_entrega" 
                                                       value="{{ old('Fecha_entrega', $pedido->Fecha_entrega) }}" 
                                                       required 
                                                       min="{{ date('Y-m-d') }}">
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="fecha-validation-message" style="display: flex; align-items: center; gap: 6px; font-size: 0.75rem;">
                                                    <i class="fas fa-info-circle" style="color: #4361ee;"></i>
                                                    <span id="fecha-validation-text">Debe ser mayor o igual a hoy</span>
                                                </div>
                                                <div class="fecha-actual" style="font-size: 0.7rem; color: #6b7280;">
                                                    <i class="far fa-calendar-check"></i> Hoy: <span id="fecha-hoy"></span>
                                                </div>
                                            </div>
                                            
                                            @error('Fecha_entrega')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Hora de Entrega -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Hora de Entrega</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <input type="time" 
                                                       class="input-field @error('Hora_entrega') is-invalid @enderror" 
                                                       id="hora_entrega" 
                                                       name="Hora_entrega" 
                                                       value="{{ old('Hora_entrega', $pedido->Hora_entrega) }}" 
                                                       required>
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Hora estimada de entrega
                                                </div>
                                            </div>
                                            
                                            @error('Hora_entrega')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Prioridad -->
                                    <div class="col-md-4">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Prioridad</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon">
                                                    <i class="fas fa-flag"></i>
                                                </div>
                                                <select class="input-field @error('Prioridad') is-invalid @enderror" 
                                                        id="prioridad" name="Prioridad" required>
                                                    <option value="">Seleccionar prioridad</option>
                                                    <option value="Baja" {{ old('Prioridad', $pedido->Prioridad) == 'Baja' ? 'selected' : '' }}>Baja</option>
                                                    <option value="Media" {{ old('Prioridad', $pedido->Prioridad) == 'Media' ? 'selected' : '' }}>Media</option>
                                                    <option value="Alta" {{ old('Prioridad', $pedido->Prioridad) == 'Alta' ? 'selected' : '' }}>Alta</option>
                                                    <option value="Urgente" {{ old('Prioridad', $pedido->Prioridad) == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                                                </select>
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Nivel de prioridad del pedido
                                                </div>
                                            </div>
                                            
                                            @error('Prioridad')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Lugar de Entrega -->
                                    <div class="col-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Lugar de Entrega</span>
                                                <span class="label-required">*</span>
                                            </label>
                                            
                                            <div class="input-wrapper" data-required="true">
                                                <div class="input-icon" style="top: 25px; transform: none;">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <textarea class="input-field @error('Lugar_entrega') is-invalid @enderror" 
                                                          id="lugar_entrega" 
                                                          name="Lugar_entrega" 
                                                          rows="3" 
                                                          required
                                                          style="padding-top: 20px; min-height: 100px; resize: vertical;">{{ old('Lugar_entrega', $pedido->Lugar_entrega) }}</textarea>
                                                <div class="input-decoration"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Dirección completa donde se entregará el pedido
                                                </div>
                                            </div>
                                            
                                            @error('Lugar_entrega')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Comentarios del Pedido -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);">
                                            <i class="fas fa-comment-dots"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Comentarios del Pedido</h3>
                                            <p class="section-subtitle mb-0">Notas especiales y observaciones</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #4cc9f0, transparent);"></div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert-card mb-3" style="
                                            background: rgba(76, 201, 240, 0.1);
                                            border-left: 4px solid #4cc9f0;
                                            border-radius: 12px;
                                            padding: 1rem 1.5rem;
                                        ">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-info-circle" style="color: #4cc9f0;"></i>
                                                <span class="text-muted">Aquí puedes ver y editar las notas especiales del pedido como cambios de color, dimensiones personalizadas, instrucciones de entrega, etc.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group-enhanced">
                                            <label class="form-label-enhanced">
                                                <span class="label-text">Comentarios / Notas especiales</span>
                                                <span class="label-optional">(opcional)</span>
                                            </label>
                                            
                                            <div class="input-wrapper">
                                                <div class="input-icon" style="top: 25px; transform: none;">
                                                    <i class="fas fa-pencil-alt" style="color: #4cc9f0;"></i>
                                                </div>
                                                <textarea class="input-field @error('comentario') is-invalid @enderror" 
                                                          id="comentario" 
                                                          name="comentario" 
                                                          rows="4" 
                                                          placeholder="Ej: El cliente quiere el mueble en color caoba en lugar de negro. Las dimensiones son 2m de alto en lugar de 1.80m. El respaldo debe ser acolchado en tela gris."
                                                          style="padding-top: 20px; min-height: 120px; resize: vertical; background-color: #f0f9ff;">{{ old('comentario', $pedido->comentario) }}</textarea>
                                                <div class="input-decoration" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);"></div>
                                            </div>
                                            
                                            <div class="input-meta">
                                                <div class="input-hint">
                                                    <i class="fas fa-lightbulb" style="color: #4cc9f0;"></i>
                                                    Sé específico con los detalles para evitar confusiones en la producción y entrega
                                                </div>
                                            </div>
                                            
                                            @error('comentario')
                                                <div class="error-message animated">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 3: Productos del Pedido -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Productos del Pedido</h3>
                                            <p class="section-subtitle mb-0">Seleccione los productos a incluir</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #f72585, transparent);"></div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert-card mb-3" style="
                                            background: rgba(247, 37, 133, 0.05);
                                            border-left: 4px solid #f72585;
                                            border-radius: 12px;
                                            padding: 1rem 1.5rem;
                                        ">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-info-circle" style="color: #f72585;"></i>
                                                <span class="text-muted">El precio se puede editar manualmente en caso de modificaciones o personalizaciones. Los precios modificados se resaltan.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-bold mb-0">
                                                <i class="fas fa-box me-2" style="color: #f72585;"></i>
                                                Lista de Productos
                                            </h5>
                                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" id="agregarProducto" style="
                                                background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
                                                border: none;
                                                border-radius: 12px;
                                                padding: 8px 20px;
                                                font-weight: 600;
                                                box-shadow: 0 4px 15px rgba(247, 37, 133, 0.3);
                                            ">
                                                <i class="fas fa-plus"></i>
                                                <span>Agregar Producto</span>
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="tablaProductos">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="35%">Producto <span class="text-danger">*</span></th>
                                                        <th width="20%">Precio Unitario <span class="text-danger">*</span></th>
                                                        <th width="20%">Cantidad <span class="text-danger">*</span></th>
                                                        <th width="15%">Subtotal</th>
                                                        <th width="10%">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cuerpoTablaProductos">
                                                    <!-- Las filas de productos se generarán dinámicamente -->
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                                        <td colspan="2">
                                                            <span class="fw-bold text-success fs-5" id="totalPedido">${{ number_format($pedido->Total, 2) }}</span>
                                                            <input type="hidden" name="Total" id="totalInput" value="{{ $pedido->Total }}">
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 4: Resumen del Pedido -->
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="section-icon-badge" style="background: linear-gradient(135deg, #7209b7 0%, #560bad 100%);">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div>
                                            <h3 class="section-title mb-1">Resumen del Pedido</h3>
                                            <p class="section-subtitle mb-0">Visualización rápida de los datos</p>
                                        </div>
                                    </div>
                                    <div class="section-divider" style="background: linear-gradient(to right, #7209b7, transparent);"></div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="stat-card p-4 text-center" style="
                                            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1) 0%, rgba(58, 12, 163, 0.05) 100%);
                                            border-radius: 16px;
                                            border: 1px solid rgba(67, 97, 238, 0.2);
                                            transition: all 0.3s ease;
                                        ">
                                            <i class="fas fa-box-open fa-3x text-primary mb-3"></i>
                                            <h5 class="text-muted mb-2">Total de Productos</h5>
                                            <span class="fw-bold fs-2" style="color: #4361ee;" id="totalProductos">0</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="stat-card p-4 text-center" style="
                                            background: linear-gradient(135deg, rgba(76, 201, 240, 0.1) 0%, rgba(72, 149, 239, 0.05) 100%);
                                            border-radius: 16px;
                                            border: 1px solid rgba(76, 201, 240, 0.2);
                                            transition: all 0.3s ease;
                                        ">
                                            <i class="fas fa-cubes fa-3x" style="color: #4cc9f0; margin-bottom: 1rem;"></i>
                                            <h5 class="text-muted mb-2">Unidades Totales</h5>
                                            <span class="fw-bold fs-2" style="color: #4cc9f0;" id="totalUnidades">0</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="stat-card p-4 text-center" style="
                                            background: linear-gradient(135deg, rgba(247, 37, 133, 0.1) 0%, rgba(181, 23, 158, 0.05) 100%);
                                            border-radius: 16px;
                                            border: 1px solid rgba(247, 37, 133, 0.2);
                                            transition: all 0.3s ease;
                                        ">
                                            <i class="fas fa-dollar-sign fa-3x" style="color: #f72585; margin-bottom: 1rem;"></i>
                                            <h5 class="text-muted mb-2">Monto Total</h5>
                                            <span class="fw-bold fs-2" style="color: #f72585;" id="montoTotal">${{ number_format($pedido->Total, 2) }}</span>
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
                                                <span id="validFieldsCount">6/6</span> campos básicos
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-box" style="color: #f72585;"></i>
                                                <span id="productCount">{{ count($pedido->detallePedidos ?? []) }}</span> productos
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        
                                        <button type="submit" class="btn btn-primary btn-submit" id="submitBtn" style="
                                            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
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
                                                Actualizar Pedido
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

<!-- Toast Notifications Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- Template para fila de producto (oculto) -->
<template id="templateFilaProducto">
    <tr class="fila-producto" style="animation: slideIn 0.3s ease-out;">
        <td>
            <div class="input-wrapper">
                <select class="input-field select-producto" name="productos[INDEX][id]" required>
                    <option value="">Seleccionar producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" 
                                data-precio="{{ $producto->Precio }}"
                                data-nombre="{{ $producto->Nombre }}">
                            {{ $producto->Nombre }} - ${{ number_format($producto->Precio, 2) }}
                        </option>
                    @endforeach
                </select>
                <div class="input-decoration" style="background: linear-gradient(135deg, #f72585, #b5179e);"></div>
            </div>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text" style="
                    background: #ffe5f0;
                    border: 2px solid #e5e7eb;
                    border-right: none;
                    border-radius: 12px 0 0 12px;
                    font-weight: 600;
                    color: #f72585;
                ">$</span>
                <input type="number" 
                       class="input-field precio-unitario" 
                       name="productos[INDEX][precio_unitario]" 
                       step="0.01" 
                       min="0" 
                       value="0"
                       required
                       style="border-radius: 0 12px 12px 0; padding-left: 12px;">
            </div>
            <small class="precio-base-indicator text-muted" style="display: block; margin-top: 4px; font-size: 0.75rem;"></small>
        </td>
        <td>
            <div class="input-wrapper">
                <input type="number" 
                       class="input-field cantidad" 
                       name="productos[INDEX][cantidad]" 
                       min="1" 
                       value="1" 
                       required>
                <div class="input-decoration" style="background: linear-gradient(135deg, #f72585, #b5179e);"></div>
            </div>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text" style="
                    background: #e9ecef;
                    border: 2px solid #e5e7eb;
                    border-right: none;
                    border-radius: 12px 0 0 12px;
                    font-weight: 600;
                ">$</span>
                <input type="text" class="input-field subtotal" readonly value="0.00" style="background: #f8f9fa; border-radius: 0 12px 12px 0; padding-left: 12px;">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-eliminar" style="
                border-radius: 10px;
                padding: 8px 12px;
                transition: all 0.3s ease;
                border: 2px solid #e5e7eb;
                color: #6b7280;
            ">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --secondary-gradient: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
    --accent-gradient: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
    --purple-gradient: linear-gradient(135deg, #7209b7 0%, #560bad 100%);
    --danger-color: #dc3545;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --info-color: #4cc9f0;
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
    background: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.toast-error .toast-icon-wrapper {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger-color);
}

.toast-warning .toast-icon-wrapper {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning-color);
}

.toast-info .toast-icon-wrapper {
    background: rgba(76, 201, 240, 0.1);
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
    --progress-color-start: #28a745;
    --progress-color-end: #20c997;
}

.toast-error .toast-progress {
    --progress-color-start: #dc3545;
    --progress-color-end: #c82333;
}

.toast-warning .toast-progress {
    --progress-color-start: #ffc107;
    --progress-color-end: #fd7e14;
}

.toast-info .toast-progress {
    --progress-color-start: #4cc9f0;
    --progress-color-end: #4895ef;
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
    border-color: #4361ee;
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
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
    color: #4361ee;
    font-size: 1.1rem;
    z-index: 2;
}

.input-field {
    width: 100%;
    padding: 14px 20px 14px 48px;
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

.input-hint {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Estilos específicos para la validación de fecha */
.fecha-validation-message {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: #4361ee;
}

.fecha-validation-message.warning {
    color: #f72585;
}

.fecha-validation-message.warning i {
    color: #f72585;
}

.fecha-validation-message.success {
    color: #28a745;
}

.fecha-validation-message.success i {
    color: #28a745;
}

.fecha-actual {
    font-size: 0.7rem;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 12px;
}

.fecha-actual i {
    color: #4cc9f0;
}

/* Select específico */
select.input-field {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 16px;
    padding-right: 48px;
}

textarea.input-field {
    min-height: 100px;
    padding-top: 20px;
    resize: vertical;
}

/* Table styles */
.table {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.table thead th {
    background: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
    color: #374151;
    font-weight: 600;
    padding: 16px;
}

.table tbody td {
    padding: 16px;
    vertical-align: middle;
}

.table tfoot td {
    background: #f9fafb;
    padding: 16px;
}

/* Input group styles */
.input-group {
    display: flex;
    align-items: stretch;
}

.input-group .input-group-text {
    display: flex;
    align-items: center;
    padding: 0 12px;
    border: 2px solid #e5e7eb;
    border-right: none;
    border-radius: 12px 0 0 12px;
    font-weight: 600;
    color: #374151;
}

.input-group .input-field {
    flex: 1;
    border-radius: 0 12px 12px 0;
    padding-left: 12px;
}

.input-group .input-field:focus {
    border-color: #f72585;
    box-shadow: none;
}

/* Botones */
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
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
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

/* Botón eliminar */
.btn-eliminar:hover {
    background-color: #dc3545;
    border-color: #dc3545 !important;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
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

/* Stat Cards */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

/* Alert Card */
.alert-card {
    transition: all 0.3s ease;
}

.alert-card:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-md);
}

/* Precio base indicator */
.precio-base-indicator {
    display: block;
    margin-top: 4px;
    font-size: 0.75rem;
}

/* Fila de producto animation */
.fila-producto {
    animation: slideIn 0.3s ease-out;
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

    .table-responsive {
        margin: 0 -15px;
    }
}
</style>

<!-- Incluir SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            'cliente_id': 'Cliente',
            'empleado_id': 'Empleado',
            'fecha_entrega': 'Fecha de entrega',
            'hora_entrega': 'Hora de entrega',
            'prioridad': 'Prioridad',
            'lugar_entrega': 'Lugar de entrega',
            'productos': 'Productos',
            'productos_validos': 'Productos válidos'
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

// Datos originales para restaurar
const originalData = {
    cliente_id: '{{ $pedido->Cliente_idCliente }}',
    empleado_id: '{{ $pedido->Empleado_idEmpleado }}',
    fecha_entrega: '{{ $pedido->Fecha_entrega }}',
    hora_entrega: '{{ $pedido->Hora_entrega }}',
    prioridad: '{{ $pedido->Prioridad }}',
    lugar_entrega: '{{ $pedido->Lugar_entrega }}',
    comentario: '{{ $pedido->comentario }}'
};

class FormManager {
    constructor() {
        this.requiredFields = [
            'cliente_id', 'empleado_id', 'fecha_entrega', 'hora_entrega', 'prioridad', 'lugar_entrega'
        ];
        this.productosSeleccionados = new Set();
        this.contadorProductos = 0;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initDateValidation();
        this.initRealTimeValidation();
        this.updateProgress();
        this.initFormReset();
        this.cargarProductosExistentes();
        this.mostrarFechaHoy();
        
        // Mostrar errores de validación del servidor si existen
        this.showServerErrors();
    }

    mostrarFechaHoy() {
        const hoy = new Date();
        const dia = hoy.getDate().toString().padStart(2, '0');
        const mes = (hoy.getMonth() + 1).toString().padStart(2, '0');
        const año = hoy.getFullYear();
        const fechaHoy = `${dia}/${mes}/${año}`;
        document.getElementById('fecha-hoy').textContent = fechaHoy;
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

    initFormReset() {
        window.resetForm = () => {
            Swal.fire({
                title: '¿Restaurar valores originales?',
                text: 'Esta acción restaurará todos los campos a sus valores originales.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.restoreOriginalData();
                    notifier.showSuccess('Valores restaurados correctamente');
                }
            });
        };
    }

    restoreOriginalData() {
        // Restaurar campos básicos
        document.getElementById('cliente_id').value = originalData.cliente_id;
        document.getElementById('empleado_id').value = originalData.empleado_id;
        document.getElementById('fecha_entrega').value = originalData.fecha_entrega;
        document.getElementById('hora_entrega').value = originalData.hora_entrega;
        document.getElementById('prioridad').value = originalData.prioridad;
        document.getElementById('lugar_entrega').value = originalData.lugar_entrega;
        document.getElementById('comentario').value = originalData.comentario || '';

        // Limpiar y recargar productos
        this.productosSeleccionados.clear();
        const cuerpoTabla = document.getElementById('cuerpoTablaProductos');
        cuerpoTabla.innerHTML = '';
        this.contadorProductos = 0;
        
        // Cargar productos originales
        @if($pedido->detallePedidos && count($pedido->detallePedidos) > 0)
            @foreach($pedido->detallePedidos as $detalle)
                this.agregarFilaProducto(
                    '{{ $detalle->Producto }}', 
                    {{ $detalle->PrecioUnitario }}, 
                    {{ $detalle->Cantidad }}
                );
            @endforeach
        @else
            this.agregarFilaProducto();
        @endif

        // Validar todos los campos
        this.requiredFields.forEach(fieldId => this.validateField(fieldId));
        this.updateProgress();
    }

    setupEventListeners() {
        document.getElementById('pedidoForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.validateAndSubmit();
        });

        document.getElementById('agregarProducto').addEventListener('click', () => {
            this.agregarFilaProducto();
        });
    }

    initRealTimeValidation() {
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('change', () => {
                    this.validateField(fieldId);
                    this.updateProgress();
                });
                field.addEventListener('blur', () => {
                    this.validateField(fieldId);
                });
            }
        });

        // Validación especial para lugar_entrega (textarea)
        const lugarEntrega = document.getElementById('lugar_entrega');
        if (lugarEntrega) {
            lugarEntrega.addEventListener('input', () => {
                this.validateField('lugar_entrega');
                this.updateProgress();
            });
        }

        // Validación especial para fecha_entrega con feedback visual
        const fechaInput = document.getElementById('fecha_entrega');
        if (fechaInput) {
            fechaInput.addEventListener('input', () => {
                this.validarFechaEnTiempoReal();
                this.validateField('fecha_entrega');
            });
            fechaInput.addEventListener('change', () => {
                this.validarFechaEnTiempoReal();
                this.validateField('fecha_entrega');
            });
        }
    }

    validarFechaEnTiempoReal() {
        const fechaInput = document.getElementById('fecha_entrega');
        const fechaValidationText = document.getElementById('fecha-validation-text');
        const fechaValidationMessage = document.querySelector('.fecha-validation-message');
        
        if (!fechaInput.value) {
            fechaValidationMessage.classList.remove('success', 'warning');
            fechaValidationText.textContent = 'Debe seleccionar una fecha de entrega';
            return;
        }

        const selectedDate = new Date(fechaInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            fechaValidationMessage.classList.add('warning');
            fechaValidationMessage.classList.remove('success');
            fechaValidationText.textContent = '❌ La fecha debe ser mayor o igual a hoy';
        } else {
            fechaValidationMessage.classList.add('success');
            fechaValidationMessage.classList.remove('warning');
            
            if (selectedDate.getTime() === today.getTime()) {
                fechaValidationText.textContent = '✓ Fecha válida (hoy)';
            } else {
                const diffTime = selectedDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                fechaValidationText.textContent = `✓ Fecha válida (en ${diffDays} días)`;
            }
        }
    }

    initDateValidation() {
        const fechaInput = document.getElementById('fecha_entrega');
        const hoy = new Date().toISOString().split('T')[0];
        fechaInput.min = hoy;
        
        // Validar la fecha inicial si existe
        if (fechaInput.value) {
            this.validarFechaEnTiempoReal();
        }
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return true;

        const wrapper = field.closest('.input-wrapper');
        if (!wrapper) return true;

        wrapper.classList.remove('error', 'valid');
        field.classList.remove('is-invalid', 'is-valid');

        if (field.hasAttribute('required') && !field.value) {
            wrapper.classList.add('error');
            field.classList.add('is-invalid');
            return false;
        }

        if (fieldId === 'fecha_entrega' && field.value) {
            const selectedDate = new Date(field.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                wrapper.classList.add('error');
                field.classList.add('is-invalid');
                return false;
            }
        }

        if (field.value) {
            wrapper.classList.add('valid');
            field.classList.add('is-valid');
        }
        
        return true;
    }

    validateAllFields() {
        const errors = [];
        
        this.requiredFields.forEach(fieldId => {
            if (!this.validateField(fieldId)) {
                errors.push(fieldId);
            }
        });

        // Validar productos
        const filasProductos = document.querySelectorAll('.fila-producto');
        if (filasProductos.length === 0) {
            errors.push('productos');
        } else {
            let productosValidos = 0;
            filasProductos.forEach(fila => {
                const select = fila.querySelector('.select-producto');
                const precio = fila.querySelector('.precio-unitario');
                const cantidad = fila.querySelector('.cantidad');
                
                if (select.value && select.value !== '') {
                    if (parseFloat(precio.value) > 0 && parseInt(cantidad.value) > 0) {
                        productosValidos++;
                    }
                }
            });

            if (productosValidos === 0) {
                errors.push('productos_validos');
            }
        }

        // Mostrar errores si los hay
        if (errors.length > 0) {
            // Construir mensaje de error detallado
            let errorMessage = '<div style="text-align: left; padding: 10px;">';
            errorMessage += '<p style="font-weight: bold; margin-bottom: 10px;">Por favor, corrija los siguientes errores:</p>';
            errorMessage += '<ul style="list-style: none; padding: 0; margin: 0;">';
            
            errors.forEach(error => {
                switch(error) {
                    case 'cliente_id':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Cliente</span> es obligatorio</li>';
                        break;
                    case 'empleado_id':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Empleado</span> es obligatorio</li>';
                        break;
                    case 'fecha_entrega':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Fecha de entrega</span> debe ser válida y mayor o igual a hoy</li>';
                        break;
                    case 'hora_entrega':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Hora de entrega</span> es obligatoria</li>';
                        break;
                    case 'prioridad':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Prioridad</span> es obligatoria</li>';
                        break;
                    case 'lugar_entrega':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Lugar de entrega</span> es obligatorio</li>';
                        break;
                    case 'productos':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Productos</span> debe agregar al menos un producto</li>';
                        break;
                    case 'productos_validos':
                        errorMessage += '<li style="margin-bottom: 5px;">• <span style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px;">Productos</span> debe completar correctamente al menos un producto (con precio > 0 y cantidad > 0)</li>';
                        break;
                }
            });
            
            errorMessage += '</ul></div>';
            
            // Mostrar SweetAlert con los errores
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos o incorrectos',
                html: errorMessage,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#4361ee',
                width: 500
            });
            
            // Hacer scroll al primer error
            const firstErrorField = document.getElementById(errors[0]);
            if (firstErrorField && errors[0] !== 'productos' && errors[0] !== 'productos_validos') {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
        document.getElementById('completedFields').textContent = `${completedFields} de ${this.requiredFields.length} campos básicos completados`;
        document.getElementById('validFieldsCount').textContent = `${completedFields}/${this.requiredFields.length}`;
    }

    cargarProductosExistentes() {
        @if($pedido->detallePedidos && count($pedido->detallePedidos) > 0)
            @foreach($pedido->detallePedidos as $detalle)
                this.agregarFilaProducto(
                    '{{ $detalle->Producto }}', 
                    {{ $detalle->PrecioUnitario }}, 
                    {{ $detalle->Cantidad }}
                );
            @endforeach
        @else
            this.agregarFilaProducto();
        @endif
    }

    agregarFilaProducto(productoId = '', precio = 0, cantidad = 1) {
        const template = document.getElementById('templateFilaProducto');
        const cuerpoTabla = document.getElementById('cuerpoTablaProductos');
        const nuevaFila = template.content.cloneNode(true);
        const fila = nuevaFila.querySelector('.fila-producto');
        
        // Reemplazar INDEX por el contador actual
        const elementosConName = fila.querySelectorAll('[name]');
        elementosConName.forEach(elemento => {
            elemento.name = elemento.name.replace(/\[INDEX\]/g, `[${this.contadorProductos}]`);
        });

        cuerpoTabla.appendChild(nuevaFila);
        
        // Inicializar eventos para la nueva fila
        this.inicializarEventosFila(fila);
        
        // Si se proporcionan datos, establecer valores
        if (productoId) {
            const selectProducto = fila.querySelector('.select-producto');
            selectProducto.value = productoId;
            
            // Disparar evento change para calcular precios
            const event = new Event('change');
            selectProducto.dispatchEvent(event);
            
            // Establecer precio y cantidad
            const inputPrecio = fila.querySelector('.precio-unitario');
            inputPrecio.value = precio;
            
            const inputCantidad = fila.querySelector('.cantidad');
            inputCantidad.value = cantidad;
            
            // Calcular subtotal
            this.calcularSubtotal(fila);
            
            // Mostrar precio base como referencia
            const precioBaseIndicator = fila.querySelector('.precio-base-indicator');
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const precioBase = selectedOption ? parseFloat(selectedOption.getAttribute('data-precio')) || 0 : 0;
            if (precioBase !== precio) {
                precioBaseIndicator.textContent = `⚠️ Precio modificado (base: $${precioBase.toFixed(2)})`;
                precioBaseIndicator.style.color = '#dc3545';
            } else {
                precioBaseIndicator.textContent = `Precio base: $${precioBase.toFixed(2)}`;
                precioBaseIndicator.style.color = '#6c757d';
            }
            
            this.productosSeleccionados.add(productoId.toString());
        }
        
        this.contadorProductos++;
        this.actualizarResumen();
    }

    inicializarEventosFila(fila) {
        const selectProducto = fila.querySelector('.select-producto');
        const inputPrecio = fila.querySelector('.precio-unitario');
        const precioBaseIndicator = fila.querySelector('.precio-base-indicator');
        const inputCantidad = fila.querySelector('.cantidad');
        const btnEliminar = fila.querySelector('.btn-eliminar');

        selectProducto.addEventListener('change', () => {
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const productoId = selectProducto.value;
            const precioBase = selectedOption ? parseFloat(selectedOption.getAttribute('data-precio')) || 0 : 0;

            if (productoId && productoId !== '') {
                // Verificar si el producto ya fue seleccionado
                if (this.productosSeleccionados.has(productoId)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto duplicado',
                        text: 'Este producto ya ha sido agregado al pedido.',
                        confirmButtonColor: '#4361ee'
                    });
                    selectProducto.value = '';
                    inputPrecio.value = '0';
                    precioBaseIndicator.textContent = '';
                    return;
                }

                this.productosSeleccionados.add(productoId);
                inputPrecio.value = precioBase.toFixed(2);
                precioBaseIndicator.textContent = `Precio base: $${precioBase.toFixed(2)}`;
                precioBaseIndicator.style.color = '#6c757d';
            } else {
                inputPrecio.value = '0';
                precioBaseIndicator.textContent = '';
                this.productosSeleccionados.delete(productoId);
            }
            
            this.calcularSubtotal(fila);
            this.actualizarResumen();
        });

        inputPrecio.addEventListener('input', () => {
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const precioBase = selectedOption ? parseFloat(selectedOption.getAttribute('data-precio')) || 0 : 0;
            const precioActual = parseFloat(inputPrecio.value) || 0;
            
            if (precioActual !== precioBase && selectProducto.value) {
                precioBaseIndicator.textContent = `⚠️ Precio modificado (base: $${precioBase.toFixed(2)})`;
                precioBaseIndicator.style.color = '#dc3545';
            } else if (selectProducto.value) {
                precioBaseIndicator.textContent = `Precio base: $${precioBase.toFixed(2)}`;
                precioBaseIndicator.style.color = '#6c757d';
            }
            
            this.calcularSubtotal(fila);
            this.actualizarResumen();
        });

        inputCantidad.addEventListener('input', () => {
            this.calcularSubtotal(fila);
            this.actualizarResumen();
        });

        btnEliminar.addEventListener('click', () => {
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
                    this.productosSeleccionados.delete(productoId);
                    fila.remove();
                    this.actualizarResumen();
                    this.reindexarFilas();
                    
                    if (document.querySelectorAll('.fila-producto').length === 0) {
                        this.agregarFilaProducto();
                    }
                }
            });
        });
    }

    calcularSubtotal(fila) {
        const precio = parseFloat(fila.querySelector('.precio-unitario').value) || 0;
        const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
        const subtotal = precio * cantidad;
        
        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
    }

    reindexarFilas() {
        const filas = document.querySelectorAll('.fila-producto');
        this.contadorProductos = 0;
        
        filas.forEach((fila, index) => {
            const elementosConName = fila.querySelectorAll('[name]');
            elementosConName.forEach(elemento => {
                elemento.name = elemento.name.replace(/\[\d+\]/g, `[${index}]`);
            });
            this.contadorProductos = index + 1;
        });
    }

    actualizarResumen() {
        let totalPedido = 0;
        let totalProductos = 0;
        let totalUnidades = 0;
        
        const filas = document.querySelectorAll('.fila-producto');
        
        filas.forEach(fila => {
            const selectProducto = fila.querySelector('.select-producto');
            const precio = parseFloat(fila.querySelector('.precio-unitario').value) || 0;
            const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
            const productoSeleccionado = selectProducto.value;
            
            if (productoSeleccionado && productoSeleccionado !== '') {
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
        document.getElementById('productCount').textContent = totalProductos;
    }

    validateAndSubmit() {
        if (this.validateAllFields()) {
            this.mostrarConfirmacion();
        }
    }

    mostrarConfirmacion() {
        const comentario = document.getElementById('comentario').value;
        const fechaEntrega = document.getElementById('fecha_entrega').value;
        const fechaObj = new Date(fechaEntrega);
        const fechaFormateada = fechaObj.toLocaleDateString('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        const datosPedido = {
            total: document.getElementById('montoTotal').textContent,
            totalProductos: document.getElementById('totalProductos').textContent,
            totalUnidades: document.getElementById('totalUnidades').textContent,
            fechaEntrega: fechaFormateada,
            prioridad: document.getElementById('prioridad').options[document.getElementById('prioridad').selectedIndex].text,
            tieneComentario: comentario.trim() !== '',
            comentario: comentario.trim() ? comentario.substring(0, 150) + (comentario.length > 150 ? '...' : '') : ''
        };

        Swal.fire({
            title: '¿Actualizar Pedido?',
            html: `
                <div class="text-start">
                    <p class="mb-3">¿Está seguro de actualizar el pedido con los siguientes datos?</p>
                    <div class="alert" style="background: rgba(67, 97, 238, 0.1); border: none; border-radius: 12px; padding: 15px;">
                        <strong style="color: #4361ee;">Resumen del Pedido:</strong><br>
                        • Total: <strong style="color: #f72585;">${datosPedido.total}</strong><br>
                        • Productos: <strong style="color: #4361ee;">${datosPedido.totalProductos}</strong><br>
                        • Unidades: <strong style="color: #4cc9f0;">${datosPedido.totalUnidades}</strong><br>
                        • Fecha de entrega: <strong style="color: #4361ee;">${datosPedido.fechaEntrega}</strong><br>
                        • Prioridad: <strong style="color: #4361ee;">${datosPedido.prioridad}</strong>
                    </div>
                    ${datosPedido.tieneComentario ? `
                    <div class="alert" style="background: rgba(76, 201, 240, 0.1); border: none; border-radius: 12px; margin-top: 12px; padding: 15px;">
                        <i class="fas fa-comment-dots me-2" style="color: #4cc9f0;"></i>
                        <strong style="color: #4cc9f0;">Comentario:</strong><br>
                        <small>${datosPedido.comentario}</small>
                    </div>
                    ` : ''}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-2"></i> Sí, Actualizar',
            cancelButtonText: '<i class="fas fa-times me-2"></i> Cancelar',
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            width: 550
        }).then((result) => {
            if (result.isConfirmed) {
                this.submitForm();
            }
        });
    }

    submitForm() {
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('pedidoForm');

        if (submitBtn && form) {
            // Mostrar notificación de procesamiento
            Swal.fire({
                title: 'Actualizando pedido...',
                html: 'Por favor espere un momento',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
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
@endsection