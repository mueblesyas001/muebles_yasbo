<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Muebles Yasbo')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
:root{
    --bg:#f1f3f6;
    --nav:#111827;
    --card:#ffffff;
    --purple:#6d28d9;
    --purple-soft:#ede9fe;
    --text:#1f2937;
    --border:#e5e7eb;
    --success:#10b981;
    --warning:#f59e0b;
    --danger:#ef4444;
}

body{
    background:var(--bg);
    font-family:system-ui;
    color:var(--text);
    overflow-x: hidden;
    width: 100%;
}

/* ===== NAVBAR ===== */
.navbar-app{
    background:var(--nav);
    padding:14px 28px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
    width: 100%;
}

.brand{
    color:#fff;
    font-weight:800;
    font-size:1.3rem;
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
}

.brand i{
    background:var(--purple);
    padding:10px;
    border-radius:10px;
}

/* ===== MENU ===== */
#navMenu{
    gap:6px;
}

.nav-item-app{
    position:relative;
}

.nav-link-app{
    color:#d1d5db;
    padding:10px 14px;
    border-radius:10px;
    display:flex;
    align-items:center;
    gap:8px;
    font-weight:500;
    cursor:pointer;
    transition:.2s;
    text-decoration: none;
}

.nav-link-app i{
    color:#a78bfa;
}

.nav-link-app:hover{
    background:#1f2937;
    color:#fff;
}

/* ===== USUARIO MENU ===== */
.user-menu{
    position:relative;
}

.user-info{
    display:flex;
    align-items:center;
    gap:8px;
    color:#d1d5db;
    padding:8px 14px;
    border-radius:10px;
    cursor:pointer;
    transition:.2s;
}

.user-info:hover{
    background:#1f2937;
}

.user-avatar{
    width:32px;
    height:32px;
    background:var(--purple);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-weight:600;
    font-size:14px;
}

/* ===== DROPDOWN ===== */
.dropdown-menu-app{
    position:absolute;
    top:120%;
    left:0;
    min-width:230px;
    background:var(--card);
    border-radius:14px;
    padding:8px;
    box-shadow:0 25px 50px rgba(0,0,0,.15);
    border:1px solid var(--border);
    opacity:0;
    visibility:hidden;
    transform:translateY(10px);
    transition:.25s;
    z-index:1000;
}

.nav-item-app.active .dropdown-menu-app,
.user-menu.active .dropdown-menu-app{
    opacity:1;
    visibility:visible;
    transform:translateY(0);
}

.dropdown-menu-app a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:10px;
    color:var(--text);
    text-decoration:none;
    font-size:14px;
    transition:.2s;
}

.dropdown-menu-app a i{
    color:var(--purple);
}

.dropdown-menu-app a:hover{
    background:var(--purple-soft);
}

.dropdown-menu-app hr{
    margin:8px 0;
    border-color:var(--border);
    opacity:0.5;
}

/* ===== BADGE DE ROL ===== */
.rol-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 20px;
    background: rgba(255,255,255,0.1);
    color: #a78bfa;
    margin-left: 8px;
    text-transform: uppercase;
}

/* ===== MOBILE ===== */
#mobileToggle{
    border:none;
    background:var(--purple);
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
    display: none;
    cursor: pointer;
    z-index: 1001;
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.container-fluid.mt-4 {
    padding-right: calc(var(--bs-gutter-x) * 1);
    padding-left: calc(var(--bs-gutter-x) * 1);
    width: 100%;
    margin-top: 1.5rem !important;
}

/* ===== SESSION TIMER STYLES ===== */
.session-timer {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: rgba(255,255,255,0.1);
    border-radius: 30px;
    margin-right: 15px;
    font-size: 13px;
    color: #d1d5db;
    border: 1px solid rgba(255,255,255,0.1);
}

.session-timer i {
    color: var(--warning);
    animation: pulse 2s infinite;
}

.session-timer.warning {
    background: rgba(245, 158, 11, 0.2);
    border-color: var(--warning);
    color: #fff;
}

.session-timer.warning i {
    color: #fff;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* SweetAlert Custom Styles */
.swal2-popup.custom-swal {
    border-radius: 24px !important;
    padding: 2rem !important;
    background: white !important;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important;
}

.swal2-title.custom-title {
    font-size: 1.8rem !important;
    font-weight: 800 !important;
    color: var(--text) !important;
    margin-top: 1rem !important;
}

.swal2-html-container.custom-html {
    margin: 1.5rem 0 !important;
    font-size: 1.1rem !important;
}

.countdown-container {
    background: linear-gradient(135deg, var(--purple-soft), #fff);
    border-radius: 50px;
    padding: 20px;
    margin: 15px 0;
    border: 2px dashed var(--purple);
}

.countdown-number {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--purple);
    line-height: 1;
    font-family: monospace;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.countdown-label {
    font-size: 0.9rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.progress-timer {
    height: 8px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 20px;
}

.progress-timer-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--purple), var(--warning));
    width: 100%;
    transition: width 1s linear;
}

.toast-notification {
    position: fixed;
    top: 30px;
    right: 30px;
    min-width: 320px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    z-index: 9999;
    overflow: hidden;
    animation: slideInRight 0.3s ease;
    border-left: 4px solid;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.95);
}

.toast-notification.success { border-left-color: var(--success); }
.toast-notification.warning { border-left-color: var(--warning); }
.toast-notification.danger { border-left-color: var(--danger); }
.toast-notification.info { border-left-color: var(--purple); }

.toast-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
}

.toast-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.toast-success .toast-icon { background: rgba(16, 185, 129, 0.1); color: var(--success); }
.toast-warning .toast-icon { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
.toast-danger .toast-icon { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
.toast-info .toast-icon { background: rgba(109, 40, 217, 0.1); color: var(--purple); }

.toast-message {
    flex: 1;
    font-size: 14px;
    color: var(--text);
}

.toast-close {
    color: #9ca3af;
    cursor: pointer;
    transition: color 0.2s;
}

.toast-close:hover {
    color: var(--text);
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

/* ===== MEDIA QUERIES - MÓVIL ===== */
@media(max-width:992px){
    #mobileToggle{
        display: block;
    }

    .navbar-app {
        padding: 12px 16px;
        position: relative;
    }

    .container-fluid {
        flex-wrap: wrap;
        position: relative;
    }

    #navMenu{
        display: none !important;
        background:var(--nav);
        margin-top:14px;
        padding:16px;
        border-radius:18px;
        flex-direction:column;
        width: 100%;
        position: absolute;
        left: 0;
        top: 100%;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    #navMenu.active{
        display: flex !important;
    }

    .nav-item-app, 
    .user-menu {
        width: 100%;
    }

    .nav-link-app {
        width: 100%;
        justify-content: flex-start;
    }

    .user-info{
        justify-content:flex-start;
        padding:12px;
        width: 100%;
    }

    .user-info span {
        flex: 1;
    }

    .dropdown-menu-app{
        position:static;
        box-shadow:none;
        border:none;
        background:transparent;
        padding:8px 0 0 20px;
        min-width:100%;
        opacity: 1;
        visibility: visible;
        transform: none;
        display: none;
    }

    .nav-item-app.active .dropdown-menu-app,
    .user-menu.active .dropdown-menu-app{
        display: block;
    }

    .dropdown-menu-app a{
        color:#e5e7eb;
        padding:10px 16px;
    }

    .dropdown-menu-app a:hover{
        background:#1f2937;
    }

    .rol-badge {
        display: inline-block;
        margin-left: 0;
        margin-top: 4px;
    }

    .session-timer {
        margin-right: 0;
        margin-bottom: 10px;
        width: 100%;
        justify-content: center;
    }
}

/* Ajustes para móviles pequeños */
@media(max-width:576px){
    .brand {
        font-size: 1.1rem;
        gap: 8px;
    }

    .brand i {
        padding: 8px;
    }

    .navbar-app {
        padding: 10px 12px;
    }

    .container-fluid.mt-4 {
        padding-left: 12px;
        padding-right: 12px;
    }

    .toast-notification {
        top: 20px;
        right: 20px;
        left: 20px;
        min-width: auto;
    }

    .countdown-number {
        font-size: 2.5rem;
    }
}

/* ===== ESTILOS PARA REPORTES ===== */
.report-container {
    padding: 20px;
}

.report-card {
    background: var(--card);
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 25px;
    border: 1px solid var(--border);
    transition: transform 0.3s, box-shadow 0.3s;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.report-card h3 {
    color: var(--purple);
    margin-bottom: 20px;
    font-weight: 700;
}

.report-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    font-size: 24px;
}

.report-icon.productos { background: #e3f2fd; color: #1976d2; }
.report-icon.compras { background: #e8f5e9; color: #2e7d32; }
.report-icon.pedidos { background: #fff3e0; color: #f57c00; }
.report-icon.inventario { background: #f3e5f5; color: #7b1fa2; }
.report-icon.rentabilidad { background: #e0f2f1; color: #00695c; }
.report-icon.general { background: #fff8e1; color: #ff8f00; }

.btn-report {
    background: var(--purple);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-report:hover {
    background: #5b21b6;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(109, 40, 217, 0.3);
}

.filter-card {
    background: var(--card);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid var(--border);
}

.filter-card label {
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text);
}

.table-report {
    background: var(--card);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    width: 100%;
}

.table-report th {
    background: var(--purple-soft);
    color: var(--purple);
    font-weight: 700;
    border-bottom: 2px solid var(--purple);
    padding: 15px;
}

.table-report td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
}

.table-report tr:hover {
    background: var(--purple-soft);
}

.stat-card {
    background: linear-gradient(135deg, var(--purple), #8b5cf6);
    color: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
}

.stat-card h6 {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.stat-card h3 {
    font-weight: 700;
    margin-bottom: 0;
}

.chart-container {
    background: var(--card);
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid var(--border);
}

/* ===== MEDIA QUERIES PARA CONTENIDO ===== */
@media(max-width:768px){
    .report-container {
        padding: 15px;
    }
    
    .report-card {
        padding: 20px;
    }
    
    .table-report {
        font-size: 14px;
    }

    .btn-report {
        width: 100%;
        padding: 10px 20px;
    }

    .report-card h3 {
        font-size: 1.2rem;
    }

    .stat-card h3 {
        font-size: 1.5rem;
    }
}

@media(max-width:576px){
    .report-container {
        padding: 10px;
    }

    .report-card {
        padding: 15px;
        margin-bottom: 15px;
    }

    .filter-card {
        padding: 15px;
    }

    .table-report th,
    .table-report td {
        padding: 8px 10px;
        font-size: 13px;
    }

    .chart-container {
        padding: 15px;
    }

    .stat-card {
        padding: 15px;
    }

    .stat-card h3 {
        font-size: 1.3rem;
    }
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1rem;
    width: 100%;
}

.row {
    margin-right: -12px;
    margin-left: -12px;
}

.row > * {
    padding-right: 12px;
    padding-left: 12px;
}
</style>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>

<nav class="navbar-app">
<div class="container-fluid d-flex justify-content-between align-items-center">

    @auth
    <a href="{{ route('home') }}" class="brand">
        <i class="fa-solid fa-couch"></i> Yasbo
        @php
            $rolUsuario = auth()->user()->rol ?? 'Usuario';
        @endphp
        <span class="rol-badge">{{ $rolUsuario }}</span>
    </a>
    @else
    <a href="{{ route('login') }}" class="brand">
        <i class="fa-solid fa-couch"></i> Yasbo
    </a>
    @endauth

    <button id="mobileToggle" class="d-lg-none">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="d-flex align-items-center" id="navMenu">
        @guest
        <a class="nav-link-app" href="{{ route('login') }}">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
        </a>
        @endguest

        @auth
        @php
            $user = auth()->user();
            $rol = $user->rol ?? 'Usuario';
            $empleado = $user->empleado;
            $nombreCompleto = '';
            $inicial = 'U';
            
            if($empleado) {
                $inicial = strtoupper(substr($empleado->Nombre ?? '', 0, 1));
                $nombreCompleto = trim($empleado->Nombre . ' ' . $empleado->ApPaterno);
            }
        @endphp

        <!-- SESSION TIMER - Nuevo elemento -->
        <div class="session-timer" id="sessionTimer">
            <i class="fa-regular fa-clock"></i>
            <span id="sessionTime">15:00</span>
            <span>restantes</span>
        </div>

        <!-- INICIO - Accesible para todos -->
        <a class="nav-link-app" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-house"></i> Inicio
        </a>

        <!-- OPERACIONES - Administración y Logística -->
        @if(in_array($rol, ['Administración', 'Logística']))
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-briefcase"></i> Operaciones
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('ventas.index') }}"><i class="fa-solid fa-cash-register"></i> Ventas</a>
                <a href="{{ route('compras.index') }}"><i class="fa-solid fa-cart-shopping"></i> Compras</a>
                <a href="{{ route('pedidos.index') }}"><i class="fa-solid fa-file-lines"></i> Pedidos</a>
                
                <!-- REPORTES GENERALES - Solo Administración ve reportes en Operaciones -->
                @if($rol === 'Administración')
                <hr>
                <a href="{{ route('reportes.index')}}">
                    <i class="fa-solid fa-chart-column"></i> Reportes
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- INVENTARIO - Administración y Almacén -->
        @if(in_array($rol, ['Administración', 'Almacén']))
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-boxes-stacked"></i> Inventario
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('productos.index') }}"><i class="fa-solid fa-box"></i> Productos</a>
                <a href="{{ route('categorias.index') }}"><i class="fa-solid fa-layer-group"></i> Categorías</a>
            </div>
        </div>
        @endif

        <!-- CALENDARIO - Accesible para todos -->
        <a class="nav-link-app" href="{{ route('calendario.index') }}">
            <i class="fa-solid fa-calendar-days"></i> Calendario
        </a>

        <!-- ADMINISTRACIÓN COMPLETA - Solo Administración -->
        @if($rol === 'Administración')
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-user-shield"></i> Administración
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('personal.index') }}"><i class="fa-solid fa-users-gear"></i> Personal</a>
                <a href="{{ route('respaldos.index') }}"><i class="fa-solid fa-database"></i> Respaldos</a>
                <a href="{{ route('proveedores.index') }}"><i class="fa-solid fa-truck"></i> Proveedores</a>
                <a href="{{ route('clientes.index') }}"><i class="fa-solid fa-users"></i> Clientes</a>
            </div>
        </div>
        @endif

        <!-- MENÚ DE USUARIO - Accesible para todos -->
        <div class="user-menu">
            <div class="user-info dropdown-btn">
                <div class="user-avatar">
                    {{ $inicial }}
                </div>
                <span>
                    {{ $nombreCompleto ?: 'Usuario' }}
                </span>
                <i class="fa-solid fa-chevron-down" style="font-size:12px;"></i>
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('perfil.edit') }}">
                    <i class="fa-solid fa-user-pen"></i> Editar Perfil
                </a>
                <a href="{{ route('perfil.cambiar-password') }}">
                    <i class="fa-solid fa-key"></i> Cambiar Contraseña
                </a>
                <hr>
                <a class="text-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                </a>
            </div>
        </div>
        @endauth
    </div>
</div>
</nav>

<div class="container-fluid mt-4">
    @yield('content')
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
class SessionManager {
    constructor(timeoutMinutes = 2, warningMinutes = 1) {
        this.timeoutMinutes = timeoutMinutes;
        this.warningMinutes = warningMinutes;
        this.timeoutMilliseconds = timeoutMinutes * 60 * 1000;
        this.warningMilliseconds = warningMinutes * 60 * 1000;
        this.timeoutTimer = null;
        this.warningTimer = null;
        this.lastActivity = Date.now();
        this.isWarningShown = false;
        
        // Elementos del DOM
        this.sessionTimer = document.getElementById('sessionTimer');
        this.sessionTimeSpan = document.getElementById('sessionTime');
        
        this.init();
    }

    init() {
        // Eventos que indican actividad del usuario
        const events = [
            'mousedown', 'mousemove', 'keypress', 'keydown',
            'scroll', 'touchstart', 'click', 'focus'
        ];
        
        events.forEach(event => {
            document.addEventListener(event, () => this.resetTimer());
        });

        // Iniciar el timer
        this.resetTimer();
        
        // Actualizar el temporizador visual cada segundo
        setInterval(() => this.updateTimerDisplay(), 1000);
        
        console.log(`🔒 Sesión configurada: ${this.timeoutMinutes} minutos de inactividad`);
    }

    resetTimer() {
        // Limpiar timers existentes
        clearTimeout(this.timeoutTimer);
        clearTimeout(this.warningTimer);

        // Actualizar última actividad
        this.lastActivity = Date.now();
        this.isWarningShown = false;
        
        // Remover clase de warning del timer
        if (this.sessionTimer) {
            this.sessionTimer.classList.remove('warning');
        }

        // Programar advertencia
        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, this.timeoutMilliseconds - this.warningMilliseconds);

        // Programar cierre de sesión
        this.timeoutTimer = setTimeout(() => {
            this.logout();
        }, this.timeoutMilliseconds);
    }

    updateTimerDisplay() {
        if (!this.sessionTimer || !this.sessionTimeSpan) return;
        
        const elapsed = (Date.now() - this.lastActivity) / 1000 / 60; // minutos
        const remaining = Math.max(0, this.timeoutMinutes - elapsed);
        
        // Formatear tiempo restante (MM:SS)
        const minutes = Math.floor(remaining);
        const seconds = Math.floor((remaining - minutes) * 60);
        this.sessionTimeSpan.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Cambiar estilo cuando quede poco tiempo
        if (remaining <= this.warningMinutes && remaining > 0) {
            this.sessionTimer.classList.add('warning');
        } else {
            this.sessionTimer.classList.remove('warning');
        }
        
        // Si el tiempo llegó a cero, el logout se encargará
        if (remaining <= 0) {
            this.logout();
        }
    }

    showWarning() {
        if (this.isWarningShown) return;
        this.isWarningShown = true;
        
        let countdownInterval;
        
        Swal.fire({
            title: '¿Todavía estás ahí? 👋',
            html: `
                <div class="countdown-container">
                    <div class="countdown-number" id="countdownNumber">2:00</div>
                    <div class="countdown-label">minutos restantes</div>
                </div>
                <p style="color: #6b7280; margin-top: 15px;">
                    Tu sesión está por expirar por inactividad.<br>
                    <strong>Mueve el mouse o presiona una tecla para continuar.</strong>
                </p>
                <div class="progress-timer">
                    <div class="progress-timer-bar" id="progressBar" style="width: 100%;"></div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6d28d9',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-sync-alt me-2"></i>Seguir aquí',
            cancelButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'custom-swal',
                title: 'custom-title',
                htmlContainer: 'custom-html'
            },
            didOpen: () => {
                // Iniciar cuenta regresiva
                let secondsLeft = this.warningMinutes * 60;
                const countdownEl = document.getElementById('countdownNumber');
                const progressBar = document.getElementById('progressBar');
                const totalSeconds = this.warningMinutes * 60;
                
                countdownInterval = setInterval(() => {
                    secondsLeft--;
                    
                    if (secondsLeft <= 0) {
                        clearInterval(countdownInterval);
                        Swal.close();
                        this.logout();
                        return;
                    }
                    
                    const minutes = Math.floor(secondsLeft / 60);
                    const seconds = secondsLeft % 60;
                    countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    
                    // Actualizar barra de progreso
                    const percentage = (secondsLeft / totalSeconds) * 100;
                    progressBar.style.width = percentage + '%';
                    
                    // Cambiar color según el tiempo
                    if (secondsLeft <= 30) {
                        countdownEl.style.color = '#ef4444';
                        progressBar.style.background = 'linear-gradient(90deg, #ef4444, #f59e0b)';
                    } else if (secondsLeft <= 60) {
                        countdownEl.style.color = '#f59e0b';
                        progressBar.style.background = 'linear-gradient(90deg, #f59e0b, #6d28d9)';
                    }
                }, 1000);
            }
        }).then((result) => {
            // Limpiar intervalo
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            if (result.isConfirmed) {
                // Usuario quiere continuar
                this.resetTimer();
                this.showToast('Sesión extendida', 'Continuarás activo por ' + this.timeoutMinutes + ' minutos más.', 'success');
            } else {
                // Usuario eligió cerrar sesión
                this.logout();
            }
        });
    }

    logout() {
        // Crear un formulario para logout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logout") }}';
        form.style.display = 'none';
        
        // Agregar CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         '{{ csrf_token() }}';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        
        // Mostrar mensaje de cierre
        Swal.fire({
            title: '⏰ Sesión expirada',
            text: 'Serás redirigido al inicio de sesión por inactividad.',
            icon: 'info',
            timer: 3000,
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'custom-swal'
            }
        });
        
        // Enviar formulario después de un breve delay
        setTimeout(() => {
            form.submit();
        }, 3000);
    }

    showToast(message, title, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                    type === 'warning' ? 'fa-exclamation-triangle' : 
                                    type === 'danger' ? 'fa-exclamation-circle' : 
                                    'fa-info-circle'}"></i>
                </div>
                <div class="toast-message">
                    <strong>${title}</strong><br>
                    ${message}
                </div>
                <div class="toast-close" onclick="this.closest('.toast-notification').remove()">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
}

// Variable global para el estado del menú
let navbarState = {
    isMobile: window.innerWidth <= 992,
    isMenuOpen: false
};

document.addEventListener('DOMContentLoaded', function() {
    @auth
    const sessionManager = new SessionManager(16, 1); 
    @endauth

    // Elementos del DOM
    const toggle = document.getElementById('mobileToggle');
    const menu = document.getElementById('navMenu');
    const items = document.querySelectorAll('.nav-item-app');
    const userMenu = document.querySelector('.user-menu');
    
    // Función para verificar si estamos en móvil
    function checkMobile() {
        navbarState.isMobile = window.innerWidth <= 992;
        
        if (!navbarState.isMobile) {
            // Modo desktop - menú siempre visible
            menu.style.display = 'flex';
            menu.classList.remove('active');
            closeAllDropdowns();
            navbarState.isMenuOpen = false;
        } else {
            // Modo móvil - menú oculto por defecto
            if (!navbarState.isMenuOpen) {
                menu.style.display = '';
                menu.classList.remove('active');
            }
        }
    }

    // Función para cerrar todos los dropdowns
    function closeAllDropdowns() {
        items.forEach(item => item.classList.remove('active'));
        if (userMenu) userMenu.classList.remove('active');
    }

    // Función para cerrar el menú hamburguesa
    function closeHamburgerMenu() {
        if (navbarState.isMobile) {
            menu.classList.remove('active');
            closeAllDropdowns();
            navbarState.isMenuOpen = false;
        }
    }

    // Función para abrir/cerrar el menú hamburguesa
    function toggleHamburgerMenu() {
        if (navbarState.isMobile) {
            const isActive = menu.classList.contains('active');
            menu.classList.toggle('active');
            navbarState.isMenuOpen = !isActive;
            
            if (isActive) {
                closeAllDropdowns();
            }
        }
    }

    // Evento del botón hamburguesa
    if (toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleHamburgerMenu();
        });
    }

    // Eventos para dropdowns en móvil
    items.forEach(item => {
        const btn = item.querySelector('.dropdown-btn');
        if (!btn) return;

        btn.addEventListener('click', function(e) {
            if (navbarState.isMobile) {
                e.preventDefault();
                e.stopPropagation();
                
                // Cerrar otros dropdowns
                items.forEach(other => {
                    if (other !== item) other.classList.remove('active');
                });
                if (userMenu) userMenu.classList.remove('active');
                
                // Toggle dropdown actual
                item.classList.toggle('active');
            }
        });
    });

    // Evento para menú de usuario en móvil
    if (userMenu) {
        const userBtn = userMenu.querySelector('.dropdown-btn');
        if (userBtn) {
            userBtn.addEventListener('click', function(e) {
                if (navbarState.isMobile) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Cerrar otros dropdowns
                    items.forEach(item => item.classList.remove('active'));
                    
                    // Toggle menú usuario
                    userMenu.classList.toggle('active');
                }
            });
        }
    }

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (navbarState.isMobile) {
            const isClickInside = menu.contains(e.target) || toggle.contains(e.target);
            
            if (!isClickInside && navbarState.isMenuOpen) {
                closeHamburgerMenu();
            }
        }
    });

    // Prevenir cierre al hacer clic dentro del menú
    if (menu) {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Cerrar al hacer clic en enlaces - CORREGIDO: mantener estado del navbar
    const allLinks = document.querySelectorAll('.nav-link-app:not(.dropdown-btn), .dropdown-menu-app a');
    allLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (navbarState.isMobile) {
                // No cerrar inmediatamente para permitir la navegación
                setTimeout(() => {
                    // Solo cerrar si no es un dropdown y estamos en móvil
                    if (!this.classList.contains('dropdown-btn')) {
                        closeHamburgerMenu();
                    }
                }, 100);
            }
        });
    });

    // Manejar redimensionamiento
    window.addEventListener('resize', function() {
        checkMobile();
    });

    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navbarState.isMobile && navbarState.isMenuOpen) {
            closeHamburgerMenu();
        }
    });

    // Eventos hover solo para desktop
    function setupHoverEvents() {
        if (!navbarState.isMobile) {
            items.forEach(item => {
                item.addEventListener('mouseenter', () => item.classList.add('active'));
                item.addEventListener('mouseleave', () => item.classList.remove('active'));
            });
            if (userMenu) {
                userMenu.addEventListener('mouseenter', () => userMenu.classList.add('active'));
                userMenu.addEventListener('mouseleave', () => userMenu.classList.remove('active'));
            }
        } else {
            // Limpiar eventos hover en móvil
            items.forEach(item => {
                item.removeEventListener('mouseenter', () => {});
                item.removeEventListener('mouseleave', () => {});
            });
            if (userMenu) {
                userMenu.removeEventListener('mouseenter', () => {});
                userMenu.removeEventListener('mouseleave', () => {});
            }
        }
    }

    // Inicializar eventos hover
    setupHoverEvents();

    // Reconfigurar eventos hover al redimensionar
    window.addEventListener('resize', function() {
        setupHoverEvents();
    });

    // Inicializar estado móvil
    checkMobile();

    // CORRECCIÓN ADICIONAL: Mantener el navbar activo después de la navegación
    // Esto asegura que el menú hamburguesa permanezca visible si estaba abierto
    if (navbarState.isMobile && navbarState.isMenuOpen) {
        menu.classList.add('active');
    }
});

// Funciones para reportes
function generarPDFReporte(nombre, titulo, datos, columnas) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    doc.setFontSize(20);
    doc.text(titulo, 14, 15);
    doc.setFontSize(10);
    doc.text(`Generado: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 14, 22);
    
    doc.autoTable({
        head: [columnas],
        body: datos,
        startY: 30,
        theme: 'grid',
        styles: { fontSize: 9 },
        headStyles: { fillColor: [109, 40, 217] }
    });
    
    doc.save(`${nombre}_${new Date().toISOString().slice(0,10)}.pdf`);
}

function exportarExcel(nombre, datos, columnas) {
    let tableHtml = '<table border="1">';
    
    tableHtml += '<tr>';
    columnas.forEach(col => {
        tableHtml += `<th>${col}</th>`;
    });
    tableHtml += '</tr>';
    
    datos.forEach(fila => {
        tableHtml += '<tr>';
        fila.forEach(celda => {
            tableHtml += `<td>${celda}</td>`;
        });
        tableHtml += '</tr>';
    });
    
    tableHtml += '</table>';
    
    const blob = new Blob([tableHtml], { type: 'application/vnd.ms-excel' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${nombre}_${new Date().toISOString().slice(0,10)}.xls`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>

@auth
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
    @csrf
</form>
@endauth

@stack('scripts')
</body>
</html>