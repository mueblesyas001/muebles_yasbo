@extends('layouts.app')

@section('content')
<div id="reportes-page" class="container-fluid px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 100vh; padding-top: 20px;">
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
                <button type="button" class="btn btn-outline-secondary" id="btnAyuda" style="
                    border-radius: 14px;
                    padding: 12px 20px;
                    border: 1px solid #e5e7eb;
                    transition: all 0.3s ease;
                    cursor: pointer;
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
            <!-- Tabs de selección mejorados - INCLUYE PEDIDOS -->
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
                    <!-- NUEVO BOTÓN PARA PEDIDOS -->
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-type="pedidos" onclick="selectReportType('pedidos')" style="
                        border-radius: 12px;
                        padding: 12px;
                        border: 1px solid #e5e7eb;
                    ">
                        <i class="fas fa-truck me-2"></i>Pedidos
                        <span class="badge bg-secondary text-white ms-2">{{ $pedidosCount ?? 0 }}</span>
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

<!-- MODAL PERSONALIZADO DE AYUDA - CON ESPACIO DEL NAVBAR -->
<div id="modalAyudaPersonalizado" class="modal-personalizado" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999; align-items: center; justify-content: center;">
    <div class="modal-personalizado-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px);" onclick="cerrarModalAyuda()"></div>
    
    <!-- Contenedor con margen superior para dejar espacio al navbar -->
    <div class="modal-personalizado-contenido" style="
        position: relative; 
        background-color: white; 
        border-radius: 24px; 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
        display: flex; 
        flex-direction: column; 
        z-index: 100000; 
        max-width: 900px; 
        width: 95%; 
        max-height: 80vh; 
        margin-top: 70px; /* ESPACIO PARA EL NAVBAR */
        animation: modalAbrir 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    ">
        
        <!-- Header del modal con gradiente -->
        <div class="modal-personalizado-header" style="
            background: linear-gradient(135deg, #1e1e2f 0%, #2d2d44 100%);
            padding: 1.5rem;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
                    <h3 class="text-white fw-bold mb-1" style="font-size: 1.5rem;">Centro de Ayuda</h3>
                    <p class="text-white-50 mb-0 small">Guía completa para la generación de reportes</p>
                </div>
            </div>
            <button class="modal-personalizado-cerrar" onclick="cerrarModalAyuda()" style="
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                font-size: 1.2rem;
                cursor: pointer;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                transition: all 0.3s ease;
            ">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Elementos decorativos -->
            <div style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
        </div>
        
        <!-- Body del modal -->
        <div class="modal-personalizado-body" style="
            padding: 1.8rem;
            background: #f8fafc;
            overflow-y: auto;
        ">
            <h6 class="small fw-bold text-uppercase text-muted mb-4">
                <i class="fas fa-chart-simple me-2" style="color: #667eea;"></i>
                Tipos de Reportes Disponibles
            </h6>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <!-- Ventas -->
                <div class="help-card" style="
                    background: white;
                    border-radius: 16px;
                    padding: 1.2rem;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                    transition: all 0.3s ease;
                ">
                    <div style="display: flex; gap: 1rem;">
                        <div style="
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
                            <h6 style="font-weight: 700; margin-bottom: 0.25rem;">Reporte de Ventas</h6>
                            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">Análisis detallado de transacciones</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">📊 Tendencias</span>
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">👥 Por empleado</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Compras -->
                <div class="help-card" style="
                    background: white;
                    border-radius: 16px;
                    padding: 1.2rem;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                ">
                    <div style="display: flex; gap: 1rem;">
                        <div style="
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
                            <h6 style="font-weight: 700; margin-bottom: 0.25rem;">Reporte de Compras</h6>
                            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">Seguimiento de adquisiciones</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">🏭 Proveedores</span>
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">💰 Costos</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Inventario -->
                <div class="help-card" style="
                    background: white;
                    border-radius: 16px;
                    padding: 1.2rem;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                ">
                    <div style="display: flex; gap: 1rem;">
                        <div style="
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
                            <h6 style="font-weight: 700; margin-bottom: 0.25rem;">Reporte de Inventario</h6>
                            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">Control de stock y valorización</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">📦 Stock actual</span>
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">⚠️ Bajo mínimo</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rentabilidad -->
                <div class="help-card" style="
                    background: white;
                    border-radius: 16px;
                    padding: 1.2rem;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                ">
                    <div style="display: flex; gap: 1rem;">
                        <div style="
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
                            <h6 style="font-weight: 700; margin-bottom: 0.25rem;">Reporte de Rentabilidad</h6>
                            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">Márgenes y productos rentables</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">📈 Márgenes</span>
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">⭐ Top productos</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pedidos - NUEVA TARJETA -->
                <div class="help-card" style="
                    background: white;
                    border-radius: 16px;
                    padding: 1.2rem;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                ">
                    <div style="display: flex; gap: 1rem;">
                        <div style="
                            width: 48px;
                            height: 48px;
                            background: linear-gradient(135deg, #f59e0b15 0%, #d9770615 100%);
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <i class="fas fa-truck" style="color: #f59e0b;"></i>
                        </div>
                        <div>
                            <h6 style="font-weight: 700; margin-bottom: 0.25rem;">Reporte de Pedidos</h6>
                            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">Seguimiento de pedidos a proveedores</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">📦 Estado pedidos</span>
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">⏱️ Tiempos entrega</span>
                                <span style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.7rem;">🏭 Por proveedor</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Características y consejos -->
            <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 1rem;">
                <!-- Características -->
                <div style="
                    background: white;
                    border-radius: 16px;
                    padding: 1.2rem;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                ">
                    <h6 style="font-weight: 700; margin-bottom: 1rem;">
                        <i class="fas fa-star text-warning me-2"></i>
                        Características del Sistema
                    </h6>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div style="display: flex; align-items: center;">
                            <div style="
                                width: 24px;
                                height: 24px;
                                background: #10b98120;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 0.5rem;
                            ">
                                <i class="fas fa-check" style="color: #10b981; font-size: 0.7rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem;">Filtros dinámicos</span>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <div style="
                                width: 24px;
                                height: 24px;
                                background: #10b98120;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 0.5rem;
                            ">
                                <i class="fas fa-check" style="color: #10b981; font-size: 0.7rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem;">Exportación PDF</span>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <div style="
                                width: 24px;
                                height: 24px;
                                background: #10b98120;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 0.5rem;
                            ">
                                <i class="fas fa-check" style="color: #10b981; font-size: 0.7rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem;">Datos en tiempo real</span>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <div style="
                                width: 24px;
                                height: 24px;
                                background: #10b98120;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 0.5rem;
                            ">
                                <i class="fas fa-check" style="color: #10b981; font-size: 0.7rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem;">Presets de fecha</span>
                        </div>
                    </div>
                </div>
                
                <!-- Consejo Profesional -->
                <div style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 16px;
                    padding: 1.2rem;
                    color: white;
                ">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="
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
                        <h6 style="font-weight: 700; margin-bottom: 0;">Consejo Profesional</h6>
                    </div>
                    <p style="font-size: 0.9rem; margin-bottom: 0.75rem; opacity: 0.9;">
                        Para obtener mejores resultados, utiliza rangos de fecha específicos y aprovecha los filtros dinámicos.
                    </p>
                    <div style="background: rgba(255,255,255,0.1); border-radius: 8px; padding: 0.5rem;">
                        <span style="font-size: 0.8rem;">
                            <i class="fas fa-clock me-1"></i>
                            Los reportes se generan en menos de 5 segundos
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer del modal -->
        <div class="modal-personalizado-footer" style="
            padding: 1.2rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #ffffff;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
            display: flex;
            justify-content: flex-end;
        ">
            <button class="btn" onclick="cerrarModalAyuda()" style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 50px;
                padding: 0.75rem 2rem;
                font-weight: 600;
                color: white;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                transition: all 0.3s ease;
            ">
                <i class="fas fa-times me-2"></i>Cerrar
            </button>
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

/* Animaciones para el modal */
@keyframes modalAbrir {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

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
    
    .modal-personalizado-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .modal-personalizado-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-personalizado-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .modal-personalizado-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .modal-personalizado-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Función para abrir el modal
function abrirModalAyuda() {
    console.log('Abriendo modal de ayuda...');
    const modal = document.getElementById('modalAyudaPersonalizado');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        console.log('Modal abierto correctamente');
    } else {
        console.error('No se encontró el modal');
        alert('Error: No se encontró el modal de ayuda');
    }
    return false;
}

// Función para cerrar el modal
function cerrarModalAyuda() {
    console.log('Cerrando modal...');
    const modal = document.getElementById('modalAyudaPersonalizado');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Asignar evento al botón cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado - Configurando botón de ayuda');
    
    // Buscar el botón por ID
    const btnAyuda = document.getElementById('btnAyuda');
    if (btnAyuda) {
        console.log('Botón de ayuda encontrado por ID');
        btnAyuda.onclick = function(e) {
            e.preventDefault();
            abrirModalAyuda();
            return false;
        };
    } else {
        // Fallback: buscar por texto
        const botones = document.querySelectorAll('button');
        botones.forEach(boton => {
            if (boton.textContent.includes('Ayuda')) {
                console.log('Botón de ayuda encontrado por texto');
                boton.onclick = function(e) {
                    e.preventDefault();
                    abrirModalAyuda();
                    return false;
                };
            }
        });
    }
    
    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalAyuda();
        }
    });
    
    // Cargar filtros iniciales
    loadFilters('ventas');
    updateStatsPreview();
    
    document.getElementById('fecha_inicio').addEventListener('change', updateStatsPreview);
    document.getElementById('fecha_fin').addEventListener('change', updateStatsPreview);
});

// RUTAS - INCLUYE PEDIDOS
const ROUTES = {
    'ventas': '{{ route("reportes.ventas") }}',
    'compras': '{{ route("reportes.compras") }}', 
    'inventario': '{{ route("reportes.inventario") }}',
    'rentabilidad': '{{ route("reportes.rentabilidad") }}',
    'pedidos': '{{ route("reportes.pedidos") }}'  // NUEVA RUTA
};

// CSRF TOKEN
const CSRF_TOKEN = '{{ csrf_token() }}';

// DATOS PARA FILTROS
const EMPLEADOS = @json($empleados ?? []);
const PROVEEDORES = @json($proveedores ?? []);
const CATEGORIAS = @json($categorias ?? []);

// TIPO DE REPORTE ACTUAL
let currentReportType = 'ventas';

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

// FILTROS DINÁMICOS - INCLUYE PEDIDOS
function loadFilters(type) {
    const container = document.getElementById('dynamicFilters');
    
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
    } 
    // NUEVA SECCIÓN PARA PEDIDOS
    else if (type === 'pedidos') {
        let proveedoresOptions = '<option value="">Todos los proveedores</option>';
        if (PROVEEDORES && PROVEEDORES.length > 0) {
            PROVEEDORES.forEach(prov => {
                proveedoresOptions += `<option value="${prov.id}">${prov.Nombre || prov.nombre || 'Proveedor'} ${prov.ApPaterno || ''}</option>`;
            });
        }
        
        container.innerHTML = `
            <div class="filter-group">
                <label class="form-label small fw-semibold text-muted">Filtrar por proveedor</label>
                <select class="form-select border-0 bg-white mb-3" id="filtro_proveedor_pedidos">
                    ${proveedoresOptions}
                </select>
                
                <label class="form-label small fw-semibold text-muted">Estado del pedido</label>
                <select class="form-select border-0 bg-white mb-3" id="filtro_estado_pedido">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">⏳ Pendiente</option>
                    <option value="en_proceso">🔄 En proceso</option>
                    <option value="completado">✅ Completado</option>
                    <option value="cancelado">❌ Cancelado</option>
                    <option value="entregado">📦 Entregado</option>
                </select>
                
                <label class="form-label small fw-semibold text-muted">Mostrar</label>
                <select class="form-select border-0 bg-white" id="filtro_tipo_pedido">
                    <option value="todos">Todos los pedidos</option>
                    <option value="pendientes">Solo pendientes</option>
                    <option value="completados">Solo completados</option>
                    <option value="atrasados">Pedidos atrasados</option>
                </select>
                <small class="text-muted mt-1 d-block">
                    <i class="fas fa-info-circle me-1" style="color: #667eea;"></i> 
                    Filtra pedidos por estado y proveedor
                </small>
            </div>
        `;
    }
}

// PRESETS DE FECHA
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
    
    updateStatsPreview();
}

// ACTUALIZAR PREVIEW DE ESTADÍSTICAS - INCLUYE PEDIDOS
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
            'rentabilidad': 'Rentabilidad',
            'pedidos': 'Pedidos'  // NUEVO
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
    selectReportType('ventas');
    
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    document.getElementById('fecha_inicio').value = thirtyDaysAgo.toISOString().split('T')[0];
    document.getElementById('fecha_fin').value = today.toISOString().split('T')[0];
    
    showNotification('Formulario restablecido', 'success');
}

// GENERAR REPORTE - INCLUYE PEDIDOS
function generarReporte() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = document.getElementById('fecha_fin').value;
    
    if (!inicio || !fin) {
        showNotification('Seleccione un rango de fechas', 'error');
        return;
    }
    
    if (new Date(inicio) > new Date(fin)) {
        showNotification('La fecha inicial debe ser menor a la final', 'error');
        return;
    }
    
    showNotification('Generando reporte...', 'info', 2000);
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = ROUTES[currentReportType];
    form.target = '_blank';
    form.style.display = 'none';
    
    const fields = [
        { name: '_token', value: CSRF_TOKEN },
        { name: 'fecha_inicio', value: inicio },
        { name: 'fecha_fin', value: fin }
    ];
    
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
    // NUEVA SECCIÓN PARA PEDIDOS
    else if (currentReportType === 'pedidos') {
        const proveedorSelect = document.getElementById('filtro_proveedor_pedidos');
        if (proveedorSelect && proveedorSelect.value) {
            fields.push({ name: 'proveedor_id', value: proveedorSelect.value });
        }
        
        const estadoSelect = document.getElementById('filtro_estado_pedido');
        if (estadoSelect && estadoSelect.value) {
            fields.push({ name: 'estado', value: estadoSelect.value });
        }
        
        const tipoSelect = document.getElementById('filtro_tipo_pedido');
        if (tipoSelect && tipoSelect.value) {
            fields.push({ name: 'tipo', value: tipoSelect.value });
        }
    }
    
    fields.forEach(field => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = field.name;
        input.value = field.value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// NOTIFICACIONES
function showNotification(message, type = 'info', duration = 3000) {
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
        document.body.appendChild(notification);
    }
    
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
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, duration);
}
</script>
@endpush
@endsection