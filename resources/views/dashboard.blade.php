@extends('layouts.app')

@section('content')
<div class="dashboard-cute">
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
    @php
        use Carbon\Carbon;

        // Asegurar que alertas siempre exista
        $alertas = $alertas ?? collect();

        // Obtener pedidos próximos a entregar (entre 1 y 7 días)
        $proximosPedidos = collect();
        $totalProximos = 0;

        if (class_exists('App\\Models\\Pedido')) {
            try {
                $proximosPedidos = \App\Models\Pedido::with(['cliente', 'detallePedidos.producto'])
                    ->whereNotIn('Estado', ['entregado', 'cancelado'])
                    ->whereNotNull('Fecha_entrega')
                    ->get()
                    ->filter(function($pedido) {
                        $fechaEntrega = Carbon::parse($pedido->Fecha_entrega);
                        $diasRestantes = now()->diffInDays($fechaEntrega, false);
                        return $diasRestantes >= 1 && $diasRestantes <= 7;
                    })
                    ->sortBy('Fecha_entrega');

                $totalProximos = $proximosPedidos->count();
            } catch (\Exception $e) {
                $proximosPedidos = collect();
                $totalProximos = 0;
            }
        }
    @endphp

    @if($alertas->count() > 0 || $totalProximos > 0)
<div class="notifications-container">
    <div class="notifications-header">
        <div class="notifications-title">
            <i class="fas fa-bell"></i>
            <span>Notificaciones</span>
            <span class="notifications-badge">
                {{ $alertas->count() + $totalProximos }}
            </span>
        </div>
        <button class="notifications-clear" onclick="closeAllNotifications()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="notifications-list">

        {{-- 🔔 PEDIDOS PRÓXIMOS --}}
        @foreach($proximosPedidos as $pedido)

            @php
                $fechaEntrega = Carbon::parse($pedido->Fecha_entrega);
                $diasRestantes = now()->diffInDays($fechaEntrega, false);

                if ($diasRestantes <= 1) {
                    $tipo = 'danger';
                    $icono = 'fa-hourglass-end';
                } elseif ($diasRestantes <= 3) {
                    $tipo = 'warning';
                    $icono = 'fa-hourglass-half';
                } else {
                    $tipo = 'info';
                    $icono = 'fa-hourglass-start';
                }
            @endphp

            <div class="notification-item notification-{{ $tipo }}" id="notification-pedido-{{ $pedido->id }}">
                <div class="notification-icon">
                    <i class="fas {{ $icono }}"></i>
                </div>

                <div class="notification-content">
                    <h4>🚚 Pedido por Entregar</h4>
                    <p>
                        <strong>Pedido #{{ $pedido->id }}</strong> -
                        {{ optional($pedido->cliente)->Nombre ?? 'Cliente no registrado' }}
                    </p>

                    <a href="{{ route('pedidos.edit', $pedido->id) }}"
                       class="notification-btn notification-btn-{{ $tipo }}">
                        Ver Pedido
                    </a>
                </div>

                <button onclick="closeNotification('notification-pedido-{{ $pedido->id }}')">
                    ✖
                </button>
            </div>

        @endforeach


        {{-- 🔔 ALERTAS DEL SISTEMA --}}
        @foreach($alertas as $index => $alerta)

            <div class="notification-item notification-{{ $alerta['tipo'] }}"
                 id="notification-{{ $index }}">

                <div class="notification-icon">
                    <i class="fas {{ $alerta['icono'] }}"></i>
                </div>

                <div class="notification-content">
                    <h4>{{ $alerta['titulo'] }}</h4>
                    <p>{!! $alerta['mensaje'] !!}</p>

                    <a href="{{ $alerta['accion'] }}"
                       class="notification-btn notification-btn-{{ $alerta['tipo'] }}">
                        {{ $alerta['btn_texto'] }}
                    </a>
                </div>

                <button onclick="closeNotification('notification-{{ $index }}')">
                    ✖
                </button>
            </div>

        @endforeach

    </div>
</div>
@endif

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
                    @endif
                    @if($totalProximos > 0)
                        <span class="detail-badge warning mt-1">{{ $totalProximos }} próximos a entregar</span>
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
                    @if($pedido->Fecha_entrega)
                    <p class="product-stats">
                        <i class="far fa-calendar-alt me-1"></i>
                        Entrega: {{ \Carbon\Carbon::parse($pedido->Fecha_entrega)->format('d/m/Y') }}
                        @php
                            $diasEntrega = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($pedido->Fecha_entrega), false);
                        @endphp
                        @if($diasEntrega <= 7 && $diasEntrega > 0)
                            <span class="badge bg-warning ms-2">{{ $diasEntrega }} días</span>
                        @elseif($diasEntrega <= 0)
                            <span class="badge bg-danger ms-2">VENCIDO</span>
                        @endif
                    </p>
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
            @elseif($pedidos_pendientes > 0 || $totalProximos > 0) info
            @else success @endif">
            <div class="message-emoji">
                @if($productos_bajo_stock > 0) ⚠️
                @elseif($pedidos_pendientes > 0) 📋
                @elseif($totalProximos > 0) 🚚
                @else ✅ @endif
            </div>
            <div class="message-content">
                <h3>
                    @if($productos_bajo_stock > 0)
                    Atención: {{ $productos_bajo_stock }} productos con stock bajo
                    @elseif($pedidos_pendientes > 0)
                    Tienes {{ $pedidos_pendientes }} pedidos pendientes
                    @elseif($totalProximos > 0)
                    {{ $totalProximos }} pedidos próximos a entregar
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
                    @elseif($totalProximos > 0)
                    <a href="{{ route('pedidos.index', ['proximos' => 1]) }}" style="color: #b45309; text-decoration: underline;">
                        Revisa los pedidos próximos a entregar
                    </a>
                    @else
                    Mantén el excelente trabajo 👍
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ESTILOS MEJORADOS PARA NOTIFICACIONES 💖 -->
<style>
/* Estilos base del dashboard */
.dashboard-cute {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header con efecto glassmorphism */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    padding: 25px 35px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255, 192, 203, 0.3);
}

.welcome-title {
    font-size: 2.2em;
    font-weight: 700;
    margin: 0;
    background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-subtitle {
    color: #666;
    margin: 5px 0 0 0;
    font-size: 1.1em;
}

.user-badge {
    margin-top: 10px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.user-role {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.9em;
    font-weight: 500;
}

.user-area {
    background: #f0f0f0;
    color: #555;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.9em;
}

.date-card {
    display: flex;
    align-items: center;
    gap: 15px;
    background: white;
    padding: 15px 25px;
    border-radius: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.date-icon {
    font-size: 2em;
}

.date-info {
    text-align: right;
}

.current-date {
    font-weight: 600;
    color: #333;
}

.current-time {
    color: #ff6b6b;
    font-weight: 500;
}

/* Contenedor de notificaciones */
.notifications-container {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 380px;
    max-width: calc(100vw - 40px);
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-radius: 25px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 192, 203, 0.3);
    z-index: 9999;
    animation: slideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 2px solid #ffe4e6;
}

.notifications-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2em;
    font-weight: 600;
    color: #4a5568;
}

.notifications-title i {
    color: #ff6b6b;
}

.notifications-badge {
    background: #ff6b6b;
    color: white;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 0.8em;
    margin-left: 10px;
}

.notifications-clear {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 1.2em;
    padding: 5px;
    transition: all 0.3s ease;
}

.notifications-clear:hover {
    color: #ff6b6b;
    transform: rotate(90deg);
}

.notifications-list {
    max-height: 60vh;
    overflow-y: auto;
    padding: 10px;
}

/* Items de notificación */
.notification-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 20px;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.notification-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: currentColor;
}

.notification-item.removing {
    animation: fadeOut 0.3s ease forwards;
}

@keyframes fadeOut {
    to {
        opacity: 0;
        transform: translateX(50px);
    }
}

/* Tipos de notificación */
.notification-danger {
    background: #fff5f5;
    color: #c53030;
}

.notification-danger::before {
    background: #fc8181;
}

.notification-warning {
    background: #fffaf0;
    color: #c05621;
}

.notification-warning::before {
    background: #f6ad55;
}

.notification-info {
    background: #ebf8ff;
    color: #2b6cb0;
}

.notification-info::before {
    background: #4299e1;
}

.notification-icon {
    font-size: 1.5em;
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
}

.notification-content {
    flex: 1;
}

.notification-content h4 {
    margin: 0 0 5px 0;
    font-size: 1em;
}

.notification-content p {
    margin: 0 0 10px 0;
    font-size: 0.9em;
    color: #4a5568;
}

.notification-btn {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.85em;
    font-weight: 600;
    transition: all 0.3s ease;
}

.notification-btn-danger {
    background: #fc8181;
    color: white;
}

.notification-btn-warning {
    background: #f6ad55;
    color: white;
}

.notification-btn-info {
    background: #4299e1;
    color: white;
}

.notification-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

.notification-item > button {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 1em;
    padding: 5px;
    height: fit-content;
    transition: all 0.3s ease;
}

.notification-item > button:hover {
    color: #ff6b6b;
    transform: scale(1.1);
}

/* Tarjetas principales */
.main-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.cute-card {
    background: white;
    border-radius: 30px;
    padding: 25px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255, 192, 203, 0.2);
}

.cute-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(255, 107, 107, 0.1);
}

.card-emoji {
    font-size: 2.5em;
    margin-bottom: 15px;
}

.card-value {
    font-size: 2em;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 5px;
}

.card-label {
    color: #718096;
    font-size: 0.95em;
    margin-bottom: 10px;
}

.card-trend, .card-detail {
    font-size: 0.9em;
}

.card-sparkle {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 1.2em;
    opacity: 0.3;
    transition: all 0.3s ease;
}

.cute-card:hover .card-sparkle {
    opacity: 1;
    transform: rotate(360deg);
}

/* Colores específicos para tarjetas */
.sales-card {
    background: linear-gradient(135deg, #fff6e5, #ffe6d5);
}
.orders-card {
    background: linear-gradient(135deg, #e6f3ff, #d4e8ff);
}
.clients-card {
    background: linear-gradient(135deg, #f0e6ff, #e5d4ff);
}
.products-card {
    background: linear-gradient(135deg, #e6ffe6, #d4ffd4);
}

/* Badges de detalle */
.detail-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 0.85em;
    font-weight: 500;
}

.detail-badge.pending {
    background: #ffd966;
    color: #856404;
}

.detail-badge.warning {
    background: #f8d7da;
    color: #721c24;
}

.detail-badge.success {
    background: #d4edda;
    color: #155724;
}

.mt-1 {
    margin-top: 5px;
}

/* Secciones de alertas */
.alert-section, .popular-section {
    background: white;
    border-radius: 30px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 1.5em;
    color: #2d3748;
    margin: 0;
}

.section-emoji {
    font-size: 2em;
}

/* Grid de productos */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.product-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 20px;
    transition: all 0.3s ease;
    border: 1px solid #edf2f7;
}

.product-card:hover {
    transform: translateX(5px);
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.product-emoji {
    font-size: 2.5em;
    min-width: 50px;
    text-align: center;
}

.product-info {
    flex: 1;
}

.product-name {
    font-size: 1.1em;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 5px 0;
}

.product-price {
    font-size: 0.95em;
    color: #4a5568;
    margin: 0 0 5px 0;
}

.product-stats {
    font-size: 0.85em;
    color: #718096;
    margin: 0;
}

.product-badge {
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.8em;
    font-weight: 600;
    white-space: nowrap;
}

.product-badge.stock-low {
    background: #f8d7da;
    color: #721c24;
}

.product-badge.stock-ok {
    background: #d4edda;
    color: #155724;
}

.product-badge.stock-high {
    background: #cce5ff;
    color: #004085;
}

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-emoji {
    font-size: 4em;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    color: #2d3748;
    margin: 0 0 10px 0;
}

.empty-state p {
    color: #718096;
    margin: 0;
}

/* Estadísticas rápidas */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    background: white;
    padding: 20px;
    border-radius: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: scale(1.05);
}

.stat-emoji {
    font-size: 2em;
}

.stat-number {
    font-size: 1.5em;
    font-weight: 700;
    color: #2d3748;
    line-height: 1.2;
}

.stat-label {
    color: #718096;
    font-size: 0.85em;
}

/* Mensaje del sistema */
.system-message {
    margin-top: 30px;
}

.message-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    border-radius: 25px;
    background: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.message-card.warning {
    background: linear-gradient(135deg, #fff5f5, #ffe6e6);
}

.message-card.info {
    background: linear-gradient(135deg, #e6f3ff, #d4e8ff);
}

.message-card.success {
    background: linear-gradient(135deg, #e6ffe6, #d4ffd4);
}

.message-emoji {
    font-size: 2.5em;
}

.message-content h3 {
    font-size: 1.2em;
    margin: 0 0 5px 0;
    color: #2d3748;
}

.message-content p {
    margin: 0;
    color: #4a5568;
}

/* Responsive */
@media (max-width: 1200px) {
    .main-cards, .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .main-cards, .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .message-card {
        flex-direction: column;
        text-align: center;
    }
}

/* Animaciones */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.detail-badge.warning, .detail-badge.pending {
    animation: pulse 2s infinite;
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #ffb6c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #ff9aac;
}

/* Countdown timer */
.countdown-timer {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 50px;
    font-size: 0.85em;
    font-weight: 600;
    margin-left: 8px;
}

.countdown-timer.urgent {
    background: #f8d7da;
    color: #721c24;
    animation: pulse 1s infinite;
}

.countdown-timer.warning {
    background: #fff3cd;
    color: #856404;
}

.countdown-timer.normal {
    background: #d4edda;
    color: #155724;
}

/* Progress bar */
.progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #ff6b6b, #ff8e8e);
    transition: width 0.3s ease;
}

.notification-item {
    position: relative;
    overflow: hidden;
}

.notification-item .progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #ff6b6b, #ff8e8e);
    transition: width 0.3s ease;
}
</style>

<script>
// Funciones mejoradas para notificaciones
function closeNotification(id) {
    const notification = document.getElementById(id);
    if (notification) {
        notification.classList.add('removing');
        setTimeout(() => {
            notification.remove();
            
            // Verificar si ya no hay notificaciones
            const notifications = document.querySelectorAll('.notification-item');
            if (notifications.length === 0) {
                const container = document.querySelector('.notifications-container');
                if (container) {
                    container.style.animation = 'slideOut 0.3s ease forwards';
                    setTimeout(() => container.remove(), 300);
                }
            }
        }, 300);
    }
}

function closeAllNotifications() {
    const notifications = document.querySelectorAll('.notification-item');
    notifications.forEach(notification => {
        notification.classList.add('removing');
    });
    
    setTimeout(() => {
        const container = document.querySelector('.notifications-container');
        if (container) {
            container.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => container.remove(), 300);
        }
    }, 300);
}

// Sistema de cuenta regresiva en tiempo real
function iniciarCountdowns() {
    const timers = document.querySelectorAll('.countdown-timer');
    
    timers.forEach(timer => {
        const fechaEntrega = new Date(timer.dataset.fecha).getTime();
        const pedidoId = timer.dataset.pedidoId;
        
        function actualizarTimer() {
            const ahora = new Date().getTime();
            const diferencia = fechaEntrega - ahora;
            
            if (diferencia <= 0) {
                timer.textContent = '¡VENCIDO!';
                timer.classList.add('urgent');
                return;
            }
            
            const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            
            let tiempoTexto = '';
            timer.className = 'countdown-timer'; // Reset classes
            
            if (dias > 0) {
                tiempoTexto = `${dias}d ${horas}h`;
                if (dias <= 1) {
                    timer.classList.add('urgent');
                } else if (dias <= 3) {
                    timer.classList.add('warning');
                } else {
                    timer.classList.add('normal');
                }
            } else if (horas > 0) {
                tiempoTexto = `${horas}h ${minutos}m`;
                timer.classList.add('urgent');
            } else {
                tiempoTexto = `${minutos}m`;
                timer.classList.add('urgent');
            }
            
            timer.textContent = tiempoTexto;
            
            // Actualizar también el progreso
            const notification = document.getElementById(`notification-pedido-${pedidoId}`);
            if (notification) {
                const progressBar = notification.querySelector('.progress-bar');
                if (progressBar) {
                    const totalDias = 7;
                    const diasRestantes = Math.max(0, dias);
                    const progreso = ((totalDias - diasRestantes) / totalDias) * 100;
                    progressBar.style.width = Math.min(100, Math.max(0, progreso)) + '%';
                }
            }
        }
        
        // Actualizar cada minuto
        actualizarTimer();
        setInterval(actualizarTimer, 60000);
    });
}

// Efectos interactivos bonitos
document.addEventListener('DOMContentLoaded', function() {
    // Iniciar cuenta regresiva
    iniciarCountdowns();
    
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

    // Cerrar notificaciones automáticamente después de 8 segundos (solo las del sistema, no las de pedidos)
    setTimeout(() => {
        const notifications = document.querySelectorAll('.notification-item:not([id^="notification-pedido-"])');
        notifications.forEach((notification, index) => {
            setTimeout(() => {
                closeNotification(notification.id);
            }, index * 500);
        });
    }, 8000);
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection