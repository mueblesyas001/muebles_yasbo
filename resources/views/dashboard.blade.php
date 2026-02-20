@extends('layouts.app')

@section('content')
<div class="dashboard-cute">
    <!-- Header Super Bonito -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1 class="welcome-title">¡Hola, {{ $nombre_empleado }}! 👋</h1>
            <p class="welcome-subtitle">Bienvenido al panel de control</p>
            @if(isset($empleado) && $empleado && $empleado->Cargo)
            <div class="user-badge">
                <span class="user-role">{{ $empleado->Cargo }}</span>
                @if($empleado->Area_trabajo)
                <span class="user-area">• {{ $empleado->Area_trabajo }}</span>
                @endif
            </div>
            @endif
        </div>
        <div class="date-card">
            <div class="date-icon">📅</div>
            <div class="date-info">
                <div class="current-date">{{ now()->isoFormat('DD MMM YYYY') }}</div>
                <div class="current-time">{{ now()->format('h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Toast Container para alertas -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; max-width: 400px;" id="toastContainer">
        @if(isset($alertas) && $alertas->count() > 0)
            @foreach($alertas as $alerta)
            <div class="toast show mb-2 border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" 
                 data-bs-autohide="true" data-bs-delay="8000"
                 style="border-left: 4px solid @switch($alerta['tipo'])
                            @case('danger') #dc3545 @break
                            @case('warning') #ffc107 @break
                            @case('success') #198754 @break
                            @default #0dcaf0
                        @endswitch">
                <div class="toast-header bg-white border-0">
                    <i class="fas {{ $alerta['icono'] }} me-2 text-{{ $alerta['tipo'] }}"></i>
                    <strong class="me-auto">{{ $alerta['titulo'] }}</strong>
                    <small class="text-muted">ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body bg-white">
                    <p class="mb-3">{!! $alerta['mensaje'] !!}</p>
                    <a href="{{ $alerta['accion'] }}" class="btn btn-sm btn-{{ $alerta['tipo'] }}">
                        <i class="fas {{ $alerta['btn_icono'] }} me-1"></i>
                        {{ $alerta['btn_texto'] }}
                    </a>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- Tarjetas Principales Bonitas -->
    <div class="main-cards">
        <!-- Ventas Hoy -->
        <div class="cute-card sales-card">
            <div class="card-emoji">💰</div>
            <div class="card-content">
                <div class="card-value">${{ number_format($ventas_hoy, 2) }}</div>
                <div class="card-label">Ventas Hoy</div>
                <div class="card-trend">
                    @if($ventas_hoy > 0)
                    📈 Activo
                    @else
                    😴 Sin ventas
                    @endif
                </div>
            </div>
            <div class="card-sparkle">✨</div>
        </div>

        <!-- Pedidos -->
        <div class="cute-card orders-card">
            <div class="card-emoji">🛒</div>
            <div class="card-content">
                <div class="card-value">{{ $pedidos_hoy }}</div>
                <div class="card-label">Pedidos Hoy</div>
                <div class="card-detail">
                    @if($pedidos_pendientes > 0)
                    <span class="detail-badge pending">{{ $pedidos_pendientes }} pendientes</span>
                    @else
                    <span class="detail-badge success">✅ Al día</span>
                    @endif
                </div>
            </div>
            <div class="card-sparkle">🎯</div>
        </div>

        <!-- Clientes -->
        <div class="cute-card clients-card">
            <div class="card-emoji">👥</div>
            <div class="card-content">
                <div class="card-value">{{ $total_clientes }}</div>
                <div class="card-label">Clientes Totales</div>
                <div class="card-trend">❤️ Base de clientes</div>
            </div>
            <div class="card-sparkle">🌟</div>
        </div>

        <!-- Productos -->
        <div class="cute-card products-card">
            <div class="card-emoji">📦</div>
            <div class="card-content">
                <div class="card-value">{{ $total_productos }}</div>
                <div class="card-label">Productos Activos</div>
                <div class="card-detail">
                    @if($productos_bajo_stock > 0)
                    <span class="detail-badge warning">⚠️ {{ $productos_bajo_stock }} bajo stock</span>
                    @else
                    <span class="detail-badge success">✅ Stock OK</span>
                    @endif
                </div>
            </div>
            <div class="card-sparkle">🔥</div>
        </div>
    </div>

   <!-- Sección de Alertas Detalladas -->
    @if(isset($pedidos_pendientes_lista) && $pedidos_pendientes_lista->count() > 0)
    <div class="alert-section mb-4">
        <div class="section-header">
            <h2>📋 Pedidos Pendientes</h2>
            <span class="badge bg-warning">{{ $pedidos_pendientes }} pendientes</span>
        </div>
        <div class="products-grid">
            @foreach($pedidos_pendientes_lista as $pedido)
            <div class="product-card">
                <div class="product-emoji">🛒</div>
                <div class="product-info">
                    <h4 class="product-name">Pedido #{{ $pedido->id }}</h4>
                    <p class="product-price">
                        Cliente: {{ optional($pedido->cliente)->Nombre ?? 'No registrado' }} {{ optional($pedido->cliente)->ApPaterno ?? '' }}
                    </p>
                    <p class="product-stats">
                        @if($pedido->detallePedidos->count() > 0)
                            Productos: {{ $pedido->detallePedidos->count() }} •
                        @endif
                        Total: ${{ number_format($pedido->Total ?? 0, 2) }} •
                        Prioridad: 
                        <span class="badge @if($pedido->Prioridad == 'alta') bg-danger 
                                            @elseif($pedido->Prioridad == 'media') bg-warning 
                                            @else bg-info @endif">
                            {{ $pedido->Prioridad ?? 'Normal' }}
                        </span>
                    </p>
                    @if($pedido->detallePedidos->count() > 0)
                        <small class="text-muted">
                            Productos: 
                            @foreach($pedido->detallePedidos->take(2) as $detalle)
                                {{ optional($detalle->producto)->Nombre ?? 'Producto' }} ({{ $detalle->Cantidad }})
                                @if(!$loop->last), @endif
                            @endforeach
                            @if($pedido->detallePedidos->count() > 2)
                                ... y {{ $pedido->detallePedidos->count() - 2 }} más
                            @endif
                        </small>
                    @endif
                </div>
                <a href="{{ route('pedidos.edit', $pedido->id) }}" class="product-badge" style="background: #ffc107; text-decoration: none; color: #000;">
                    Procesar
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sección de Productos con Stock Bajo -->
    @if(isset($productos_bajo_stock_lista) && $productos_bajo_stock_lista->count() > 0)
    <div class="alert-section mb-4">
        <div class="section-header">
            <h2>⚠️ Productos con Stock Bajo</h2>
            <span class="badge bg-danger">{{ $productos_bajo_stock }} productos</span>
        </div>
        <div class="products-grid">
            @foreach($productos_bajo_stock_lista as $producto)
            <div class="product-card">
                <div class="product-emoji">📦</div>
                <div class="product-info">
                    <h4 class="product-name">{{ $producto->Nombre }}</h4>
                    <p class="product-price">
                        <span class="badge bg-danger">Stock: {{ $producto->Cantidad }} unidades</span>
                    </p>
                    <p class="product-stats">
                        Mínimo: {{ $producto->Cantidad_minima }} • 
                        Máximo: {{ $producto->Cantidad_maxima }}
                    </p>
                </div>
                <a href="{{ route('productos.edit', $producto->id) }}" class="product-badge" style="background: #dc3545; text-decoration: none;">
                    Reabastecer
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sección de Productos con Stock Bajo -->
    @if(isset($productos_bajo_stock_lista) && $productos_bajo_stock_lista->count() > 0)
    <div class="alert-section mb-4">
        <div class="section-header">
            <h2>⚠️ Productos con Stock Bajo</h2>
            <span class="badge bg-danger">{{ $productos_bajo_stock }} productos</span>
        </div>
        <div class="products-grid">
            @foreach($productos_bajo_stock_lista as $producto)
            <div class="product-card">
                <div class="product-emoji">📦</div>
                <div class="product-info">
                    <h4 class="product-name">{{ $producto->Nombre }}</h4>
                    <p class="product-price">Stock actual: {{ $producto->Cantidad }} unidades</p>
                    <p class="product-stats">
                        Mínimo: {{ $producto->Cantidad_minima }} • Máximo: {{ $producto->Cantidad_maxima }}
                    </p>
                </div>
                <a href="{{ route('productos.edit', $producto->id) }}" class="product-badge" style="background: #dc3545; text-decoration: none;">
                    Reabastecer
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sección de Productos Más Vendidos -->
    <div class="popular-section">
        <div class="section-header">
            <h2>🎯 Productos Más Vendidos</h2>
            <div class="section-emoji">🏆</div>
        </div>
        
        @if(isset($top_productos) && $top_productos->count() > 0)
        <div class="products-grid">
            @foreach($top_productos as $producto)
            <div class="product-card">
                <div class="product-emoji">
                    @switch($loop->iteration)
                        @case(1) 🥇 @break
                        @case(2) 🥈 @break
                        @case(3) 🥉 @break
                        @default ⭐
                    @endswitch
                </div>
                <div class="product-info">
                    <h4 class="product-name">{{ $producto->Nombre }}</h4>
                    <p class="product-price">${{ number_format($producto->Precio, 2) }}</p>
                    <p class="product-stats">
                        Vendido: {{ $producto->total_vendido }} veces
                        • Stock: {{ $producto->Cantidad }}
                    </p>
                </div>
                <div class="product-badge 
                    @if($producto->Cantidad <= $producto->Cantidad_minima) stock-low
                    @elseif($producto->Cantidad >= $producto->Cantidad_maxima) stock-high
                    @else stock-ok @endif">
                    Top {{ $loop->iteration }}
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-emoji">😴</div>
            <h3>No hay ventas aún</h3>
            <p>Los productos más vendidos aparecerán aquí</p>
        </div>
        @endif
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="quick-stats">
        <div class="stat-item">
            <div class="stat-emoji">💼</div>
            <div class="stat-info">
                <div class="stat-number">{{ $total_empleados }}</div>
                <div class="stat-label">Empleados</div>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-emoji">🏪</div>
            <div class="stat-info">
                <div class="stat-number">{{ $total_proveedores }}</div>
                <div class="stat-label">Proveedores</div>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-emoji">📊</div>
            <div class="stat-info">
                <div class="stat-number">{{ $total_ventas }}</div>
                <div class="stat-label">Ventas Totales</div>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-emoji">📝</div>
            <div class="stat-info">
                <div class="stat-number">{{ $total_pedidos }}</div>
                <div class="stat-label">Pedidos Totales</div>
            </div>
        </div>
        
        <div class="stat-item">
            <div class="stat-emoji">👤</div>
            <div class="stat-info">
                <div class="stat-number">{{ $total_usuarios }}</div>
                <div class="stat-label">Usuarios</div>
            </div>
        </div>
    </div>

    <!-- Mensaje del Sistema -->
    <div class="system-message">
        <div class="message-card 
            @if($productos_bajo_stock > 0) warning
            @elseif($pedidos_pendientes > 0) info
            @else success @endif">
            <div class="message-emoji">
                @if($productos_bajo_stock > 0) ⚠️
                @elseif($pedidos_pendientes > 0) 📋
                @else ✅ @endif
            </div>
            <div class="message-content">
                <h3>
                    @if($productos_bajo_stock > 0)
                    Atención: {{ $productos_bajo_stock }} productos con stock bajo
                    @elseif($pedidos_pendientes > 0)
                    Tienes {{ $pedidos_pendientes }} pedidos pendientes
                    @else
                    ¡Todo bajo control!
                    @endif
                </h3>
                <p>
                    @if($productos_bajo_stock > 0)
                    <a href="{{ route('productos.index', ['bajo_stock' => 1]) }}" style="color: #c53030; text-decoration: underline;">
                        Revisa el inventario para reabastecer
                    </a>
                    @elseif($pedidos_pendientes > 0)
                    <a href="{{ route('pedidos.index', ['estado' => 'pendiente']) }}" style="color: #2c5282; text-decoration: underline;">
                        Continúa con los pedidos pendientes
                    </a>
                    @else
                    Mantén el excelente trabajo 👍
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ESTILOS CON FONDO BLANCO Y MEJORAS 💖 -->
<style>
.dashboard-cute {
    background: #ffffff;
    min-height: 100vh;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header Bonito */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 10px 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.welcome-subtitle {
    color: rgba(255,255,255,0.9);
    font-size: 1.1rem;
    margin: 0 0 15px 0;
}

.user-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.user-role {
    background: rgba(255,255,255,0.2);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.user-area {
    font-size: 0.9rem;
    opacity: 0.8;
}

.date-card {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid rgba(255,255,255,0.3);
}

.date-icon {
    font-size: 1.8rem;
}

.date-info {
    color: white;
    text-align: right;
}

.current-date {
    font-size: 1rem;
    font-weight: 600;
}

.current-time {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Tarjetas Principales */
.main-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.cute-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 25px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.cute-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-color: #e2e8f0;
}

.sales-card { border-left: 4px solid #ff6b6b; }
.orders-card { border-left: 4px solid #4ecdc4; }
.clients-card { border-left: 4px solid #45b7d1; }
.products-card { border-left: 4px solid #96ceb4; }

.card-emoji {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.card-content {
    position: relative;
    z-index: 2;
}

.card-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 5px;
}

.card-label {
    font-size: 0.95rem;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 10px;
}

.card-trend {
    font-size: 0.85rem;
    color: #48bb78;
    font-weight: 600;
}

.card-detail {
    margin-top: 10px;
}

.detail-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.detail-badge.pending {
    background: #fed7d7;
    color: #c53030;
}

.detail-badge.warning {
    background: #feebc8;
    color: #dd6b20;
}

.detail-badge.success {
    background: #c6f6d5;
    color: #276749;
}

.card-sparkle {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 1.3rem;
    opacity: 0.7;
}

/* Toast Notifications */
.toast {
    opacity: 0.95;
    border-radius: 12px;
    overflow: hidden;
}

.toast.show {
    animation: slideInRight 0.3s ease;
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

/* Sección Productos Populares */
.popular-section, .alert-section {
    background: #ffffff;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f5f9;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-header h2 {
    color: #1e293b;
    margin: 0;
    font-size: 1.4rem;
    font-weight: 700;
}

.section-emoji {
    font-size: 1.8rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 15px;
}

.product-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.product-card:hover {
    transform: translateY(-3px);
    border-color: #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.product-emoji {
    font-size: 1.8rem;
}

.product-info {
    flex: 1;
}

.product-name {
    color: #1e293b;
    font-weight: 700;
    margin: 0 0 5px 0;
    font-size: 0.95rem;
}

.product-price {
    color: #1e293b;
    font-weight: 600;
    margin: 0 0 5px 0;
    font-size: 0.85rem;
    background: #ffffff;
    padding: 2px 8px;
    border-radius: 8px;
    display: inline-block;
    border: 1px solid #e2e8f0;
}

.product-stats {
    color: #64748b;
    font-size: 0.75rem;
    margin: 0;
}

.product-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
}

.product-badge:hover {
    opacity: 0.9;
    transform: scale(1.05);
}

.stock-low { background: #fc8181; }
.stock-ok { background: #68d391; }
.stock-high { background: #4fd1c7; }

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
    background: #f8fafc;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.empty-emoji {
    font-size: 3rem;
    margin-bottom: 15px;
}

.empty-state h3 {
    color: #475569;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.empty-state p {
    margin: 0;
    font-size: 0.9rem;
}

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.stat-item {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.stat-item:hover {
    transform: scale(1.03);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.stat-emoji {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.stat-number {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 5px;
}

.stat-label {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Mensaje del Sistema */
.system-message {
    margin-bottom: 30px;
}

.message-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f5f9;
}

.message-card.warning {
    border-left: 4px solid #e53e3e;
    background: linear-gradient(135deg, #fed7d7, #fff5f5);
}

.message-card.info {
    border-left: 4px solid #3182ce;
    background: linear-gradient(135deg, #bee3f8, #ebf8ff);
}

.message-card.success {
    border-left: 4px solid #38a169;
    background: linear-gradient(135deg, #c6f6d5, #f0fff4);
}

.message-emoji {
    font-size: 2.5rem;
}

.message-content h3 {
    margin: 0 0 10px 0;
    color: #1e293b;
    font-size: 1.1rem;
}

.message-content p {
    margin: 0;
    color: #64748b;
    font-size: 0.9rem;
}

.message-content a {
    color: inherit;
    text-decoration: underline;
}

.message-content a:hover {
    text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }
    
    .welcome-title {
        font-size: 2rem;
    }
    
    .main-cards {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .message-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .user-badge {
        justify-content: center;
    }
    
    #toastContainer {
        max-width: 90%;
        right: 5%;
    }
}

/* Animaciones bonitas */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.cute-card:hover .card-emoji {
    animation: float 2s ease-in-out infinite;
}

@keyframes sparkle {
    0%, 100% { opacity: 0.7; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.1); }
}

.card-sparkle {
    animation: sparkle 3s ease-in-out infinite;
}
</style>

<script>
// Efectos interactivos bonitos
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar toasts
    var toastElList = [].slice.call(document.querySelectorAll('.toast'));
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 8000
        });
    });
    
    // Mostrar toasts con retraso
    setTimeout(function() {
        toastList.forEach(toast => toast.show());
    }, 1000);
    
    // Efecto al hacer hover en tarjetas
    const cards = document.querySelectorAll('.cute-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Actualizar hora en tiempo real
    function updateTime() {
        const timeElement = document.querySelector('.current-time');
        if (timeElement) {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            
            const timeString = hours + ':' + minutes + ' ' + ampm;
            timeElement.textContent = timeString;
        }
    }
    
    updateTime();
    setInterval(updateTime, 60000);
    
    // Efecto de parpadeo para alertas
    const warningBadges = document.querySelectorAll('.detail-badge.warning, .detail-badge.pending');
    warningBadges.forEach(badge => {
        setInterval(() => {
            badge.style.opacity = badge.style.opacity === '0.7' ? '1' : '0.7';
        }, 1000);
    });
    
    // Vibrar si hay alertas críticas
    @if(isset($productos_bajo_stock) && $productos_bajo_stock > 0)
        if (window.navigator.vibrate) {
            window.navigator.vibrate([200, 100, 200]);
        }
    @endif
    
    // Actualizar título con contador
    @if(isset($pedidos_pendientes) && $pedidos_pendientes > 0)
        document.title = `({{ $pedidos_pendientes }}) Dashboard - Muebles Yasbo`;
    @endif
});
</script>
@endsection