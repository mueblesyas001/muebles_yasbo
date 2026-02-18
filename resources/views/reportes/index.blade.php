@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header con gradiente y diseño mejorado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold mb-1" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fas fa-chart-pie me-2" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                Reportes y Análisis
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('d/m/Y') }} | 
                <i class="fas fa-sync-alt me-1 fa-spin" style="animation-duration: 3s;"></i> Datos en tiempo real
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Actualizar
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="fas fa-question-circle me-1"></i> Ayuda
            </button>
        </div>
    </div>

    <!-- KPI Cards - Rediseñadas con colores e iconos -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-primary mb-2">Ventas del mes</span>
                            <h3 class="text-white mb-0">${{ number_format($ventasMes ?? 0, 0) }}</h3>
                            <small class="text-white-50">
                                <i class="fas fa-arrow-up me-1"></i> +12% vs mes anterior
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-danger mb-2">Compras del mes</span>
                            <h3 class="text-white mb-0">${{ number_format($comprasMes ?? 0, 0) }}</h3>
                            <small class="text-white-50">
                                <i class="fas fa-arrow-down me-1"></i> -5% vs mes anterior
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="fas fa-shopping-cart fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: linear-gradient(135deg, #5ea9f0 0%, #2c3e50 100%);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-info mb-2">Valor inventario</span>
                            <h3 class="text-white mb-0">${{ number_format($valorInventario ?? 0, 0) }}</h3>
                            <small class="text-white-50">
                                <i class="fas fa-box me-1"></i> {{ $totalProductos ?? 0 }} productos
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="fas fa-boxes fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-success mb-2">Rentabilidad</span>
                            <h3 class="text-white mb-0">${{ number_format($gananciaMes ?? 0, 0) }}</h3>
                            <small class="text-white-50">
                                <i class="fas fa-percentage me-1"></i> {{ $gananciaMes > 0 ? round(($gananciaMes / $ventasMes) * 100, 1) : 0 }}% margen
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="fas fa-coins fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generador de Reportes - Diseño mejorado con tabs -->
    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
        <div class="card-header bg-white py-3 px-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f0f0f0;">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fas fa-file-pdf text-primary fa-lg"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Generador de Reportes</h5>
                    <small class="text-muted">Selecciona el tipo de reporte y configura los parámetros</small>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <!-- Tabs de selección -->
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2" id="reportTypeTabs">
                    <button type="button" class="btn btn-primary" data-type="ventas" onclick="selectReportType('ventas')">
                        <i class="fas fa-chart-line me-2"></i>Ventas
                        <span class="badge bg-white text-primary ms-2">12</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-type="compras" onclick="selectReportType('compras')">
                        <i class="fas fa-shopping-cart me-2"></i>Compras
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-type="inventario" onclick="selectReportType('inventario')">
                        <i class="fas fa-boxes me-2"></i>Inventario
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-type="rentabilidad" onclick="selectReportType('rentabilidad')">
                        <i class="fas fa-chart-pie me-2"></i>Rentabilidad
                    </button>
                </div>
            </div>

            <!-- Configuración del reporte -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card bg-light border-0" style="border-radius: 16px;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                Período del reporte
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Fecha inicio</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-primary"></i>
                                        </span>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                               class="form-control border-start-0 ps-0" 
                                               value="{{ now()->subDays(30)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Fecha fin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar-check text-primary"></i>
                                        </span>
                                        <input type="date" name="fecha_fin" id="fecha_fin" 
                                               class="form-control border-start-0 ps-0" 
                                               value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label small fw-semibold text-muted mb-2">Rangos rápidos</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDate('today')">
                                        <i class="fas fa-sun me-1"></i> Hoy
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDate('yesterday')">
                                        <i class="fas fa-cloud me-1"></i> Ayer
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDate('week')">
                                        <i class="fas fa-calendar-week me-1"></i> 7 días
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDate('month')">
                                        <i class="fas fa-calendar-alt me-1"></i> 30 días
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDate('month-current')">
                                        <i class="fas fa-calendar me-1"></i> Mes actual
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card bg-light border-0" style="border-radius: 16px;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-filter me-2 text-primary"></i>
                                Filtros adicionales
                            </h6>
                            
                            <div id="dynamicFilters" class="animate__animated animate__fadeIn">
                                <!-- Los filtros se cargarán dinámicamente -->
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                    <p class="mb-0 small">Cargando filtros...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones y estadísticas rápidas -->
            <div class="row g-3 mt-4">
                <div class="col-md-8">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-lg flex-grow-1" onclick="generarReporte()" style="border-radius: 12px;">
                            <i class="fas fa-file-pdf me-2"></i>
                            Generar Reporte PDF
                            <i class="fas fa-external-link-alt ms-2 small"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="resetForm()" style="border-radius: 12px;">
                            <i class="fas fa-undo-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded-3 h-100 d-flex align-items-center">
                        <div class="small text-muted">
                            <i class="fas fa-info-circle me-1 text-primary"></i>
                            <span id="statsPreview">Selecciona un período para ver estadísticas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de reportes generados -->
    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-history me-2 text-primary"></i>
                Reportes recientes
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tipo</th>
                            <th>Período</th>
                            <th>Generado por</th>
                            <th>Fecha</th>
                            <th>Tamaño</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3">
                                    <i class="fas fa-chart-line me-1"></i> Ventas
                                </span>
                            </td>
                            <td>01/03/2024 - 31/03/2024</td>
                            <td>Admin</td>
                            <td>31/03/2024 15:30</td>
                            <td>2.4 MB</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary" onclick="window.open('#', '_blank')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-info bg-opacity-10 text-info py-2 px-3">
                                    <i class="fas fa-boxes me-1"></i> Inventario
                                </span>
                            </td>
                            <td>01/03/2024 - 31/03/2024</td>
                            <td>Admin</td>
                            <td>30/03/2024 10:15</td>
                            <td>1.8 MB</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary" onclick="window.open('#', '_blank')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Ayuda - Versión Elegante -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 24px; overflow: hidden;">
            <!-- Header con diseño más elegante -->
            <div class="modal-header position-relative border-0 p-4" style="background: linear-gradient(135deg, #1e1e2f 0%, #2d2d44 100%);">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-circle-question text-white fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-1">Centro de Ayuda</h5>
                        <p class="text-white-50 mb-0 small">Guía completa para la generación de reportes</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <!-- Elementos decorativos -->
                <div class="position-absolute" style="top: -20px; right: -20px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                <div class="position-absolute" style="bottom: -30px; left: -30px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%;"></div>
            </div>
            
            <!-- Body con diseño de tarjetas elegantes -->
            <div class="modal-body p-4" style="background: #f8fafc;">
                <!-- Tipos de reportes en grid mejorado -->
                <h6 class="text-uppercase small fw-bold text-secondary mb-3">
                    <i class="fas fa-chart-simple me-2"></i>Tipos de Reportes Disponibles
                </h6>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); transition: transform 0.2s;">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                            <i class="fas fa-chart-line fa-xl" style="color: #667eea;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Reporte de Ventas</h6>
                                        <p class="small text-secondary mb-2">Análisis detallado de transacciones y rendimiento comercial</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-light text-dark">📊 Tendencias</span>
                                            <span class="badge bg-light text-dark">👥 Por empleado</span>
                                            <span class="badge bg-light text-dark">📦 Por producto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); transition: transform 0.2s;">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
                                            <i class="fas fa-truck fa-xl" style="color: #f5576c;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Reporte de Compras</h6>
                                        <p class="small text-secondary mb-2">Seguimiento de adquisiciones y análisis de proveedores</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-light text-dark">🏭 Proveedores</span>
                                            <span class="badge bg-light text-dark">💰 Costos</span>
                                            <span class="badge bg-light text-dark">📅 Tendencias</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); transition: transform 0.2s;">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);">
                                            <i class="fas fa-boxes fa-xl" style="color: #4facfe;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Reporte de Inventario</h6>
                                        <p class="small text-secondary mb-2">Control de stock, valorización y análisis ABC</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-light text-dark">📦 Stock actual</span>
                                            <span class="badge bg-light text-dark">⚠️ Bajo mínimo</span>
                                            <span class="badge bg-light text-dark">📊 Rotación</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); transition: transform 0.2s;">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #5ea9f015 0%, #2c3e5015 100%);">
                                            <i class="fas fa-coins fa-xl" style="color: #2c3e50;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Reporte de Rentabilidad</h6>
                                        <p class="small text-secondary mb-2">Márgenes de ganancia y productos más rentables</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-light text-dark">📈 Márgenes</span>
                                            <span class="badge bg-light text-dark">⭐ Top productos</span>
                                            <span class="badge bg-light text-dark">💹 ROI</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de características y consejos -->
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="card border-0" style="border-radius: 16px; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    Características del Sistema
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                                                <i class="fas fa-check text-success small"></i>
                                            </div>
                                            <span class="small">Filtros dinámicos</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                                                <i class="fas fa-check text-success small"></i>
                                            </div>
                                            <span class="small">Exportación PDF</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                                                <i class="fas fa-check text-success small"></i>
                                            </div>
                                            <span class="small">Datos en tiempo real</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                                                <i class="fas fa-check text-success small"></i>
                                            </div>
                                            <span class="small">Presets de fecha</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="card border-0 h-100" style="border-radius: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white bg-opacity-20 p-2 rounded-3 me-2">
                                        <i class="fas fa-lightbulb text-white"></i>
                                    </div>
                                    <h6 class="text-white fw-bold mb-0">Consejo Profesional</h6>
                                </div>
                                <p class="text-white-50 small mb-2">
                                    Para obtener mejores resultados, utiliza rangos de fecha específicos y aprovecha los filtros dinámicos para segmentar la información.
                                </p>
                                <div class="bg-white bg-opacity-10 rounded-3 p-2 mt-auto">
                                    <span class="text-white small">
                                        <i class="fas fa-clock me-1"></i>
                                        Los reportes se generan en menos de 5 segundos
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer con acciones -->
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>



@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* Estilos mejorados para el modal */
.modal-content {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal .card {
    transition: all 0.2s ease;
}

.modal .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
}

.modal .badge {
    font-weight: 400;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
}

.modal kbd {
    font-family: 'SF Mono', Monaco, Consolas, monospace;
    font-size: 0.7rem;
    border-radius: 4px;
    padding: 0.2rem 0.4rem;
}

/* Animación de entrada mejorada */
.modal.fade .modal-dialog {
    transform: scale(0.95) translateY(-20px);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Scroll personalizado para el modal */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Efecto de glassmorphism en el header */
.modal-header {
    backdrop-filter: blur(10px);
}
.form-check-input:checked {
    background-color: #4361ee;
    border-color: #4361ee;
}
.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.1);
    border-color: #4361ee;
}
.card {
    border-radius: 16px;
}
.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #e9ecef;
    padding: 0.6rem 1rem;
}
.form-control:focus, .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.1);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: transform 0.2s;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}
.btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}
.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
}
.badge {
    font-weight: 500;
    padding: 0.5rem 0.8rem;
}
.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}
.input-group-text {
    border-radius: 10px 0 0 10px;
}
.animate__animated {
    animation-duration: 0.5s;
}
#reportTypeTabs .btn {
    border-radius: 30px;
    padding: 0.6rem 1.5rem;
    transition: all 0.3s;
}
.text-white-50 {
    color: rgba(255,255,255,0.7) !important;
}
</style>
@endpush

@push('scripts')
<script>
// RUTAS
const ROUTES = {
    'ventas': '{{ route("reportes.ventas") }}',
    'compras': '{{ route("reportes.compras") }}', 
    'inventario': '{{ route("reportes.inventario") }}',
    'rentabilidad': '{{ route("reportes.rentabilidad") }}'
};

// CSRF TOKEN
const CSRF_TOKEN = '{{ csrf_token() }}';

// DATOS PARA FILTROS
const EMPLEADOS = @json($empleados);
const PROVEEDORES = @json($proveedores);
const CATEGORIAS = @json($categorias);

// TIPO DE REPORTE ACTUAL
let currentReportType = 'ventas';

document.addEventListener('DOMContentLoaded', function() {
    // Cargar filtros iniciales
    loadFilters('ventas');
    updateStatsPreview();
    
    // Actualizar preview al cambiar fechas
    document.getElementById('fecha_inicio').addEventListener('change', updateStatsPreview);
    document.getElementById('fecha_fin').addEventListener('change', updateStatsPreview);
});

// SELECCIONAR TIPO DE REPORTE
function selectReportType(type) {
    currentReportType = type;
    
    // Actualizar botones
    document.querySelectorAll('#reportTypeTabs .btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-secondary');
    });
    
    const selectedBtn = document.querySelector(`#reportTypeTabs .btn[data-type="${type}"]`);
    selectedBtn.classList.remove('btn-outline-secondary');
    selectedBtn.classList.add('btn-primary');
    
    // Cargar filtros
    loadFilters(type);
    updateStatsPreview();
}

// FILTROS DINÁMICOS
function loadFilters(type) {
    const container = document.getElementById('dynamicFilters');
    container.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><p class="mb-0 small">Cargando filtros...</p></div>';
    
    setTimeout(() => {
        if (type === 'ventas' || type === 'rentabilidad') {
            let options = '<option value="">Todos los empleados</option>';
            EMPLEADOS.forEach(emp => {
                options += `<option value="${emp.id}">${emp.nombre || emp.Nombre || 'Empleado'}</option>`;
            });
            
            container.innerHTML = `
                <label class="form-label small fw-semibold text-muted">Filtrar por empleado</label>
                <select class="form-select" id="filtro_empleado">
                    ${options}
                </select>
                <small class="text-muted mt-1 d-block">
                    <i class="fas fa-info-circle me-1"></i> Opcional - deja vacío para todos
                </small>
            `;
        } else if (type === 'compras') {
            let options = '<option value="">Todos los proveedores</option>';
            PROVEEDORES.forEach(prov => {
                options += `<option value="${prov.id}">${prov.nombre || prov.Nombre || 'Proveedor'}</option>`;
            });
            
            container.innerHTML = `
                <label class="form-label small fw-semibold text-muted">Filtrar por proveedor</label>
                <select class="form-select" id="filtro_proveedor">
                    ${options}
                </select>
                <small class="text-muted mt-1 d-block">
                    <i class="fas fa-info-circle me-1"></i> Opcional - deja vacío para todos
                </small>
            `;
        } else if (type === 'inventario') {
            let options = '<option value="">Todas las categorías</option>';
            CATEGORIAS.forEach(cat => {
                options += `<option value="${cat.id}">${cat.nombre || cat.Nombre || 'Categoría'}</option>`;
            });
            
            container.innerHTML = `
                <label class="form-label small fw-semibold text-muted">Filtrar por categoría</label>
                <select class="form-select mb-3" id="filtro_categoria">
                    ${options}
                </select>
                
                <label class="form-label small fw-semibold text-muted">Nivel de stock</label>
                <select class="form-select" id="filtro_nivel_stock">
                    <option value="">Todos los niveles</option>
                    <option value="bajo">🔴 Bajo (menor a mínimo)</option>
                    <option value="medio">🟡 Medio (entre mínimo y máximo)</option>
                    <option value="alto">🟢 Alto (mayor a máximo)</option>
                    <option value="agotado">⚫ Agotado (stock 0)</option>
                </select>
            `;
        } else {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-filter fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0">Sin filtros adicionales disponibles</p>
                </div>
            `;
        }
    }, 300);
}

// PRESETS DE FECHA MEJORADOS
function setDate(preset) {
    const inicio = document.getElementById('fecha_inicio');
    const fin = document.getElementById('fecha_fin');
    const today = new Date();
    let start = new Date();
    
    switch(preset) {
        case 'today':
            start = today;
            break;
        case 'yesterday':
            start.setDate(today.getDate() - 1);
            today.setDate(today.getDate() - 1);
            break;
        case 'week':
            start.setDate(today.getDate() - 7);
            break;
        case 'month':
            start.setDate(today.getDate() - 30);
            break;
        case 'month-current':
            start = new Date(today.getFullYear(), today.getMonth(), 1);
            break;
    }
    
    inicio.value = start.toISOString().split('T')[0];
    fin.value = today.toISOString().split('T')[0];
    
    // Animar los inputs
    [inicio, fin].forEach(input => {
        input.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            input.classList.remove('animate__animated', 'animate__pulse');
        }, 500);
    });
    
    updateStatsPreview();
}

// ACTUALIZAR PREVIEW DE ESTADÍSTICAS
function updateStatsPreview() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = document.getElementById('fecha_fin').value;
    const statsPreview = document.getElementById('statsPreview');
    
    if (inicio && fin) {
        const startDate = new Date(inicio);
        const endDate = new Date(fin);
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        statsPreview.innerHTML = `
            <i class="fas fa-calendar-check me-1 text-primary"></i>
            <strong>${diffDays} días</strong> de análisis |
            <i class="fas fa-chart-line ms-2 me-1 text-success"></i>
            Reporte de ${currentReportType}
        `;
    } else {
        statsPreview.innerHTML = `
            <i class="fas fa-info-circle me-1 text-primary"></i>
            Selecciona un período para ver estadísticas
        `;
    }
}

// RESETEAR FORMULARIO
function resetForm() {
    // Resetear tipo de reporte
    selectReportType('ventas');
    
    // Resetear fechas
    document.getElementById('fecha_inicio').value = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    document.getElementById('fecha_fin').value = new Date().toISOString().split('T')[0];
    
    // Mostrar mensaje de éxito
    showNotification('Formulario restablecido', 'success');
}

// GENERAR REPORTE (ABRE EN NUEVA PESTAÑA)
function generarReporte() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = document.getElementById('fecha_fin').value;
    
    // Validaciones
    if (!inicio || !fin) {
        showNotification('Seleccione un rango de fechas', 'error');
        return;
    }
    
    if (new Date(inicio) > new Date(fin)) {
        showNotification('La fecha inicial debe ser menor a la final', 'error');
        return;
    }
    
    // Mostrar loading
    showNotification('Generando reporte...', 'info', 2000);
    
    // Crear formulario dinámico
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = ROUTES[currentReportType];
    form.target = '_blank';
    form.style.display = 'none';
    
    // Agregar campos
    const fields = [
        { name: '_token', value: CSRF_TOKEN },
        { name: 'fecha_inicio', value: inicio },
        { name: 'fecha_fin', value: fin }
    ];
    
    // Agregar filtros según tipo
    if (currentReportType === 'ventas' || currentReportType === 'rentabilidad') {
        const empleadoSelect = document.getElementById('filtro_empleado');
        if (empleadoSelect && empleadoSelect.value) {
            fields.push({ name: 'empleado_id', value: empleadoSelect.value });
        }
    } else if (currentReportType === 'compras') {
        const proveedorSelect = document.getElementById('filtro_proveedor');
        if (proveedorSelect && proveedorSelect.value) {
            fields.push({ name: 'proveedor_id', value: proveedorSelect.value });
        }
    } else if (currentReportType === 'inventario') {
        const categoriaSelect = document.getElementById('filtro_categoria');
        if (categoriaSelect && categoriaSelect.value) {
            fields.push({ name: 'categoria_id', value: categoriaSelect.value });
        }
        
        const nivelStockSelect = document.getElementById('filtro_nivel_stock');
        if (nivelStockSelect && nivelStockSelect.value) {
            fields.push({ name: 'nivel_stock', value: nivelStockSelect.value });
        }
    }
    
    // Crear inputs
    fields.forEach(field => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = field.name;
        input.value = field.value;
        form.appendChild(input);
    });
    
    // Enviar formulario
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// SISTEMA DE NOTIFICACIONES
function showNotification(message, type = 'info', duration = 3000) {
    // Crear elemento de notificación si no existe
    let notification = document.getElementById('notification-toast');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'notification-toast';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.style.padding = '1rem';
        notification.style.borderRadius = '12px';
        notification.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
        notification.style.animation = 'slideIn 0.3s ease';
        document.body.appendChild(notification);
    }
    
    // Configurar estilos según tipo
    const colors = {
        success: { bg: '#10b981', icon: 'check-circle' },
        error: { bg: '#ef4444', icon: 'exclamation-circle' },
        info: { bg: '#3b82f6', icon: 'info-circle' },
        warning: { bg: '#f59e0b', icon: 'exclamation-triangle' }
    };
    
    const color = colors[type] || colors.info;
    
    notification.innerHTML = `
        <div style="background: ${color.bg}; color: white; padding: 1rem; border-radius: 12px; display: flex; align-items: center;">
            <i class="fas fa-${color.icon} me-2 fa-lg"></i>
            <span style="flex-grow: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Auto-cerrar
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, duration);
}

// Agregar estilos de animación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection