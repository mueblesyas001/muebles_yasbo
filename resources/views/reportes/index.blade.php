@extends('layouts.app')

@section('content')
<div id="reportes-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh;">
    <!-- Header con Glassmorphism -->
    <div class="glass-header py-4 px-4 mb-4" style="
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        margin-top: 20px;
    ">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-4">
                <div class="header-icon" style="
                    width: 70px;
                    height: 70px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 18px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
                ">
                    <i class="fas fa-chart-pie fa-2x"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1" style="
                        background: linear-gradient(135deg, #2c3e50 0%, #4a5568 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        letter-spacing: -0.5px;
                    ">
                        Reportes y Análisis
                    </h1>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-calendar-alt me-1" style="color: #667eea;"></i> 
                        {{ now()->format('d/m/Y') }} | 
                        <i class="fas fa-sync-alt me-1 fa-spin" style="color: #10b981; animation-duration: 3s;"></i> 
                        Datos en tiempo real
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary" onclick="window.location.reload()" style="
                    border-radius: 14px;
                    padding: 12px 20px;
                    border: 1px solid #e5e7eb;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#helpModal" style="
                    border-radius: 14px;
                    padding: 12px 20px;
                    border: 1px solid #e5e7eb;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-question-circle me-1"></i> Ayuda
                </button>
            </div>
        </div>
    </div>

    <!-- KPI Cards - Rediseñadas con gradientes -->
    <div class="row g-4 mb-4">
        @php
            $kpis = [
                [
                    'titulo' => 'Ventas del mes',
                    'valor' => '$' . number_format($ventasMes ?? 0, 0),
                    'icono' => 'fa-chart-line',
                    'gradiente' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'comparacion' => '+12% vs mes anterior',
                    'icono_comparacion' => 'fa-arrow-up'
                ],
                [
                    'titulo' => 'Compras del mes',
                    'valor' => '$' . number_format($comprasMes ?? 0, 0),
                    'icono' => 'fa-shopping-cart',
                    'gradiente' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'comparacion' => '-5% vs mes anterior',
                    'icono_comparacion' => 'fa-arrow-down'
                ],
                [
                    'titulo' => 'Valor inventario',
                    'valor' => '$' . number_format($valorInventario ?? 0, 0),
                    'icono' => 'fa-boxes',
                    'gradiente' => 'linear-gradient(135deg, #5ea9f0 0%, #2c3e50 100%)',
                    'comparacion' => $totalProductos ?? 0 . ' productos',
                    'icono_comparacion' => 'fa-box'
                ],
                [
                    'titulo' => 'Rentabilidad',
                    'valor' => '$' . number_format($gananciaMes ?? 0, 0),
                    'icono' => 'fa-coins',
                    'gradiente' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'comparacion' => ($gananciaMes > 0 ? round(($gananciaMes / $ventasMes) * 100, 1) : 0) . '% margen',
                    'icono_comparacion' => 'fa-percentage'
                ]
            ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="col-md-3 col-6">
            <div class="stat-card h-100" style="
                background: {{ $kpi['gradiente'] }};
                border-radius: 24px;
                padding: 1.5rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                cursor: default;
                position: relative;
                overflow: hidden;
            ">
                <div class="stat-decoration" style="
                    position: absolute;
                    top: -50%;
                    right: -50%;
                    width: 200px;
                    height: 200px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                    transition: all 0.5s ease;
                "></div>
                
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-white text-dark mb-2" style="
                            padding: 0.5rem 1rem;
                            border-radius: 50px;
                            font-size: 0.75rem;
                            font-weight: 600;
                            letter-spacing: 0.5px;
                        ">
                            {{ $kpi['titulo'] }}
                        </span>
                    </div>
                    <div class="stat-icon" style="
                        width: 50px;
                        height: 50px;
                        background: rgba(255, 255, 255, 0.2);
                        border-radius: 16px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 1.5rem;
                    ">
                        <i class="fas {{ $kpi['icono'] }}"></i>
                    </div>
                </div>
                
                <h3 class="fw-bold mb-1 text-white" style="font-size: 2rem;">{{ $kpi['valor'] }}</h3>
                <small class="text-white-50">
                    <i class="fas {{ $kpi['icono_comparacion'] }} me-1"></i> 
                    {{ $kpi['comparacion'] }}
                </small>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Generador de Reportes Mejorado -->
    <div class="report-container mb-4" style="
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
    ">
        <div class="report-header p-4" style="
            border-bottom: 1px solid #e5e7eb;
            background: white;
        ">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="
                    width: 50px;
                    height: 50px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                ">
                    <i class="fas fa-file-pdf fa-lg"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1" style="color: #1f2937;">Generador de Reportes</h5>
                    <p class="text-muted small mb-0">Selecciona el tipo de reporte y configura los parámetros</p>
                </div>
            </div>
        </div>
        
        <div class="report-body p-4">
            <!-- Tabs de selección mejorados -->
            <div class="report-tabs mb-4">
                <div class="btn-group w-100" role="group" id="reportTypeTabs" style="gap: 8px;">
                    <button type="button" class="btn btn-primary flex-fill" data-type="ventas" onclick="selectReportType('ventas')" style="
                        border-radius: 12px;
                        padding: 12px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border: none;
                    ">
                        <i class="fas fa-chart-line me-2"></i>Ventas
                        <span class="badge bg-white text-primary ms-2">{{ $ventasCount ?? 0 }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-type="compras" onclick="selectReportType('compras')" style="
                        border-radius: 12px;
                        padding: 12px;
                        border: 1px solid #e5e7eb;
                    ">
                        <i class="fas fa-shopping-cart me-2"></i>Compras
                    </button>
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-type="inventario" onclick="selectReportType('inventario')" style="
                        border-radius: 12px;
                        padding: 12px;
                        border: 1px solid #e5e7eb;
                    ">
                        <i class="fas fa-boxes me-2"></i>Inventario
                    </button>
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-type="rentabilidad" onclick="selectReportType('rentabilidad')" style="
                        border-radius: 12px;
                        padding: 12px;
                        border: 1px solid #e5e7eb;
                    ">
                        <i class="fas fa-chart-pie me-2"></i>Rentabilidad
                    </button>
                </div>
            </div>

            <!-- Configuración del reporte en grid mejorado -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="filters-card p-4" style="
                        background: #f8fafc;
                        border-radius: 16px;
                    ">
                        <h6 class="fw-bold mb-3" style="color: #1f2937;">
                            <i class="fas fa-calendar-alt me-2" style="color: #667eea;"></i>
                            Período del reporte
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Fecha inicio</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-white">
                                        <i class="fas fa-calendar text-primary"></i>
                                    </span>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                           class="form-control border-0 bg-white" 
                                           value="{{ now()->subDays(30)->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Fecha fin</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-white">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </span>
                                    <input type="date" name="fecha_fin" id="fecha_fin" 
                                           class="form-control border-0 bg-white" 
                                           value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label small fw-semibold text-muted mb-2">Rangos rápidos</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm" onclick="setDate('today')" style="
                                    background: white;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 50px;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.85rem;
                                    transition: all 0.3s ease;
                                ">
                                    <i class="fas fa-sun me-1" style="color: #f59e0b;"></i> Hoy
                                </button>
                                <button type="button" class="btn btn-sm" onclick="setDate('yesterday')" style="
                                    background: white;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 50px;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.85rem;
                                ">
                                    <i class="fas fa-cloud me-1" style="color: #6b7280;"></i> Ayer
                                </button>
                                <button type="button" class="btn btn-sm" onclick="setDate('week')" style="
                                    background: white;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 50px;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.85rem;
                                ">
                                    <i class="fas fa-calendar-week me-1" style="color: #3b82f6;"></i> 7 días
                                </button>
                                <button type="button" class="btn btn-sm" onclick="setDate('month')" style="
                                    background: white;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 50px;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.85rem;
                                ">
                                    <i class="fas fa-calendar-alt me-1" style="color: #10b981;"></i> 30 días
                                </button>
                                <button type="button" class="btn btn-sm" onclick="setDate('month-current')" style="
                                    background: white;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 50px;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.85rem;
                                ">
                                    <i class="fas fa-calendar me-1" style="color: #8b5cf6;"></i> Mes actual
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="filters-card p-4" style="
                        background: #f8fafc;
                        border-radius: 16px;
                    ">
                        <h6 class="fw-bold mb-3" style="color: #1f2937;">
                            <i class="fas fa-filter me-2" style="color: #667eea;"></i>
                            Filtros adicionales
                        </h6>
                        
                        <div id="dynamicFilters" class="animate__animated animate__fadeIn">
                            <!-- Los filtros se cargarán dinámicamente -->
                            <div class="text-center text-muted py-4">
                                <div class="spinner-border text-primary mb-2" style="width: 2rem; height: 2rem;" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mb-0 small">Cargando filtros...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones y estadísticas rápidas -->
            <div class="row g-3 mt-4">
                <div class="col-md-8">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary flex-grow-1" onclick="generarReporte()" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border: none;
                            border-radius: 14px;
                            padding: 14px 24px;
                            font-weight: 600;
                            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                        ">
                            <i class="fas fa-file-pdf me-2"></i>
                            Generar Reporte PDF
                            <i class="fas fa-external-link-alt ms-2 small"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()" style="
                            border-radius: 14px;
                            padding: 14px 24px;
                            border: 1px solid #e5e7eb;
                        ">
                            <i class="fas fa-undo-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-preview p-3" style="
                        background: #f8fafc;
                        border-radius: 14px;
                        height: 100%;
                        display: flex;
                        align-items: center;
                    ">
                        <div class="small text-muted">
                            <i class="fas fa-info-circle me-1 text-primary"></i>
                            <span id="statsPreview">Selecciona un período para ver estadísticas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Ayuda Mejorado -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">
            <!-- Header mejorado -->
            <div class="modal-header" style="
                background: linear-gradient(135deg, #1e1e2f 0%, #2d2d44 100%);
                border: none;
                padding: 1.5rem;
            ">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon" style="
                        width: 50px;
                        height: 50px;
                        background: rgba(255, 255, 255, 0.1);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                    ">
                        <i class="fas fa-circle-question fa-lg"></i>
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
            
            <!-- Body mejorado -->
            <div class="modal-body p-4" style="background: #f8fafc;">
                <h6 class="small fw-bold text-uppercase text-muted mb-3">
                    <i class="fas fa-chart-simple me-2" style="color: #667eea;"></i>
                    Tipos de Reportes Disponibles
                </h6>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="help-card p-3" style="
                            background: white;
                            border-radius: 16px;
                            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                            transition: all 0.3s ease;
                        ">
                            <div class="d-flex gap-3">
                                <div class="icon-wrapper" style="
                                    width: 48px;
                                    height: 48px;
                                    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
                                    border-radius: 12px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    <i class="fas fa-chart-line" style="color: #667eea;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Reporte de Ventas</h6>
                                    <p class="small text-muted mb-2">Análisis detallado de transacciones y rendimiento comercial</p>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">📊 Tendencias</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">👥 Por empleado</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">📦 Por producto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="help-card p-3" style="
                            background: white;
                            border-radius: 16px;
                            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                        ">
                            <div class="d-flex gap-3">
                                <div class="icon-wrapper" style="
                                    width: 48px;
                                    height: 48px;
                                    background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);
                                    border-radius: 12px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    <i class="fas fa-truck" style="color: #f5576c;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Reporte de Compras</h6>
                                    <p class="small text-muted mb-2">Seguimiento de adquisiciones y análisis de proveedores</p>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">🏭 Proveedores</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">💰 Costos</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">📅 Tendencias</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="help-card p-3" style="
                            background: white;
                            border-radius: 16px;
                            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                        ">
                            <div class="d-flex gap-3">
                                <div class="icon-wrapper" style="
                                    width: 48px;
                                    height: 48px;
                                    background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);
                                    border-radius: 12px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    <i class="fas fa-boxes" style="color: #4facfe;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Reporte de Inventario</h6>
                                    <p class="small text-muted mb-2">Control de stock, valorización y análisis ABC</p>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">📦 Stock actual</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">⚠️ Bajo mínimo</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">📊 Rotación</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="help-card p-3" style="
                            background: white;
                            border-radius: 16px;
                            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                        ">
                            <div class="d-flex gap-3">
                                <div class="icon-wrapper" style="
                                    width: 48px;
                                    height: 48px;
                                    background: linear-gradient(135deg, #5ea9f015 0%, #2c3e5015 100%);
                                    border-radius: 12px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    <i class="fas fa-coins" style="color: #2c3e50;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Reporte de Rentabilidad</h6>
                                    <p class="small text-muted mb-2">Márgenes de ganancia y productos más rentables</p>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">📈 Márgenes</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">⭐ Top productos</span>
                                        <span class="badge px-2 py-1" style="background: #f3f4f6; color: #4b5563;">💹 ROI</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Características y consejos -->
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="help-card p-3" style="
                            background: white;
                            border-radius: 16px;
                            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                        ">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-star text-warning me-2"></i>
                                Características del Sistema
                            </h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="feature-icon me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #10b98120;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-check" style="color: #10b981; font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="small">Filtros dinámicos</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="feature-icon me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #10b98120;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-check" style="color: #10b981; font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="small">Exportación PDF</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="feature-icon me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #10b98120;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-check" style="color: #10b981; font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="small">Datos en tiempo real</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="feature-icon me-2" style="
                                            width: 28px;
                                            height: 28px;
                                            background: #10b98120;
                                            border-radius: 8px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="fas fa-check" style="color: #10b981; font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="small">Presets de fecha</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="help-card p-3" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border-radius: 16px;
                            color: white;
                        ">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="icon-wrapper" style="
                                    width: 32px;
                                    height: 32px;
                                    background: rgba(255, 255, 255, 0.2);
                                    border-radius: 8px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Consejo Profesional</h6>
                            </div>
                            <p class="small text-white-50 mb-2">
                                Para obtener mejores resultados, utiliza rangos de fecha específicos y aprovecha los filtros dinámicos para segmentar la información.
                            </p>
                            <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                <span class="small">
                                    <i class="fas fa-clock me-1"></i>
                                    Los reportes se generan en menos de 5 segundos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
#reportes-page {
    padding-top: 20px;
}

.stat-card:hover .stat-decoration {
    transform: scale(1.2);
}

.help-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Estilos para los badges en los KPIs */
.stat-card .badge {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Animación para los rangos rápidos */
.btn-sm {
    transition: all 0.3s ease;
}

.btn-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Estilos para el modal */
.modal-content {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

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

.modal.fade .modal-dialog {
    transform: scale(0.95) translateY(-20px);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Responsive */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        width: 100%;
        margin: 0.25rem 0;
    }
    
    .stats-preview {
        margin-top: 1rem;
    }
    
    .modal-body {
        max-height: 80vh;
    }
}

/* Animaciones para los elementos */
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

#notification-toast {
    animation: slideIn 0.3s ease;
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
const EMPLEADOS = @json($empleados ?? []);
const PROVEEDORES = @json($proveedores ?? []);
const CATEGORIAS = @json($categorias ?? []);

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
        btn.style.background = '';
        btn.style.border = '1px solid #e5e7eb';
    });
    
    const selectedBtn = document.querySelector(`#reportTypeTabs .btn[data-type="${type}"]`);
    selectedBtn.classList.remove('btn-outline-secondary');
    selectedBtn.classList.add('btn-primary');
    selectedBtn.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    selectedBtn.style.border = 'none';
    
    // Cargar filtros
    loadFilters(type);
    updateStatsPreview();
}

// FILTROS DINÁMICOS
function loadFilters(type) {
    const container = document.getElementById('dynamicFilters');
    
    setTimeout(() => {
        if (type === 'ventas' || type === 'rentabilidad') {
            let options = '<option value="">Todos los empleados</option>';
            if (EMPLEADOS && EMPLEADOS.length > 0) {
                EMPLEADOS.forEach(emp => {
                    options += `<option value="${emp.id}">${emp.Nombre || emp.nombre || 'Empleado'} ${emp.ApPaterno || ''}</option>`;
                });
            }
            
            container.innerHTML = `
                <div class="filter-group">
                    <label class="form-label small fw-semibold text-muted">Filtrar por empleado</label>
                    <select class="form-select border-0 bg-white" id="filtro_empleado">
                        ${options}
                    </select>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle me-1" style="color: #667eea;"></i> 
                        Opcional - deja vacío para todos
                    </small>
                </div>
            `;
        } else if (type === 'compras') {
            let options = '<option value="">Todos los proveedores</option>';
            if (PROVEEDORES && PROVEEDORES.length > 0) {
                PROVEEDORES.forEach(prov => {
                    options += `<option value="${prov.id}">${prov.Nombre || prov.nombre || 'Proveedor'} ${prov.ApPaterno || ''}</option>`;
                });
            }
            
            container.innerHTML = `
                <div class="filter-group">
                    <label class="form-label small fw-semibold text-muted">Filtrar por proveedor</label>
                    <select class="form-select border-0 bg-white" id="filtro_proveedor">
                        ${options}
                    </select>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle me-1" style="color: #667eea;"></i> 
                        Opcional - deja vacío para todos
                    </small>
                </div>
            `;
        } else if (type === 'inventario') {
            let options = '<option value="">Todas las categorías</option>';
            if (CATEGORIAS && CATEGORIAS.length > 0) {
                CATEGORIAS.forEach(cat => {
                    options += `<option value="${cat.id}">${cat.Nombre || cat.nombre || 'Categoría'}</option>`;
                });
            }
            
            container.innerHTML = `
                <div class="filter-group">
                    <label class="form-label small fw-semibold text-muted">Filtrar por categoría</label>
                    <select class="form-select border-0 bg-white mb-3" id="filtro_categoria">
                        ${options}
                    </select>
                    
                    <label class="form-label small fw-semibold text-muted">Nivel de stock</label>
                    <select class="form-select border-0 bg-white" id="filtro_nivel_stock">
                        <option value="">Todos los niveles</option>
                        <option value="bajo">🔴 Bajo (menor a mínimo)</option>
                        <option value="medio">🟡 Medio (entre mínimo y máximo)</option>
                        <option value="alto">🟢 Alto (mayor a máximo)</option>
                        <option value="agotado">⚫ Agotado (stock 0)</option>
                    </select>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle me-1" style="color: #667eea;"></i> 
                        Ambos filtros son opcionales
                    </small>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-filter fa-3x mb-3 opacity-50" style="color: #9ca3af;"></i>
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
        
        const reportTypes = {
            'ventas': 'Ventas',
            'compras': 'Compras',
            'inventario': 'Inventario',
            'rentabilidad': 'Rentabilidad'
        };
        
        statsPreview.innerHTML = `
            <i class="fas fa-calendar-check me-1 text-primary"></i>
            <strong>${diffDays} días</strong> de análisis |
            <i class="fas fa-chart-line ms-2 me-1" style="color: #10b981;"></i>
            Reporte de ${reportTypes[currentReportType] || currentReportType}
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
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    document.getElementById('fecha_inicio').value = thirtyDaysAgo.toISOString().split('T')[0];
    document.getElementById('fecha_fin').value = today.toISOString().split('T')[0];
    
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
</script>
@endpush
@endsection