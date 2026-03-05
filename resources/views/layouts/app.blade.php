<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Muebles Yasbo')</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

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
    position: relative;
    z-index: 9999; /* Asegurar que el navbar esté por encima */
}

.brand{
    color:#fff;
    font-weight:800;
    font-size:1.3rem;
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none !important; /* Forzar que no se subraye */
    pointer-events: auto !important; /* Forzar que sea clickeable */
}

.brand i{
    background:var(--purple);
    padding:10px;
    border-radius:10px;
}

/* ===== MENU ===== */
#navMenu{
    gap:6px;
    transition: all 0.3s ease;
    pointer-events: auto !important; /* Forzar que sea clickeable */
}

.nav-item-app{
    position:relative;
    pointer-events: auto !important;
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
    text-decoration: none !important;
    white-space: nowrap;
    pointer-events: auto !important;
    position: relative;
    z-index: 10000;
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
    pointer-events: auto !important;
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
    pointer-events: auto !important;
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
    z-index:10000;
    pointer-events: auto !important;
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
    text-decoration:none !important;
    font-size:14px;
    transition:.2s;
    pointer-events: auto !important;
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
    z-index: 10001;
    pointer-events: auto !important;
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.container-fluid.mt-4 {
    padding-right: calc(var(--bs-gutter-x) * 1);
    padding-left: calc(var(--bs-gutter-x) * 1);
    width: 100%;
    margin-top: 1.5rem !important;
    position: relative;
    z-index: 1;
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
    white-space: nowrap;
    pointer-events: auto !important;
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
    z-index: 10002;
    overflow: hidden;
    animation: slideInRight 0.3s ease;
    border-left: 4px solid;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.95);
    pointer-events: auto !important;
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
        z-index: 10000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        max-height: 80vh;
        overflow-y: auto;
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
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }

    .nav-item-app.active .dropdown-menu-app,
    .user-menu.active .dropdown-menu-app{
        display: block;
        max-height: 500px;
        padding: 8px 0 8px 20px;
    }

    .dropdown-menu-app a{
        color:#e5e7eb;
        padding:12px 16px;
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
</style>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>

<nav class="navbar-app" id="mainNavbar">
<div class="container-fluid d-flex justify-content-between align-items-center">

    @auth
    <a href="{{ route('home') }}" class="brand" id="brandLink">
        <i class="fa-solid fa-couch"></i> Yasbo
        @php
            $rolUsuario = auth()->user()->rol ?? 'Usuario';
        @endphp
        <span class="rol-badge">{{ $rolUsuario }}</span>
    </a>
    @else
    <a href="{{ route('login') }}" class="brand" id="brandLink">
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

        <!-- SESSION TIMER -->
        <div class="session-timer" id="sessionTimer">
            <i class="fa-regular fa-clock"></i>
            <span id="sessionTime">15:00</span>
            <span>restantes</span>
        </div>

        <!-- INICIO -->
        <a class="nav-link-app" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-house"></i> Inicio
        </a>

        <!-- OPERACIONES -->
        @if(in_array($rol, ['Administración', 'Logística']))
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-briefcase"></i> Operaciones
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('ventas.index') }}"><i class="fa-solid fa-cash-register"></i> Ventas</a>
                <a href="{{ route('compras.index') }}"><i class="fa-solid fa-cart-shopping"></i> Compras</a>
                <a href="{{ route('pedidos.index') }}"><i class="fa-solid fa-file-lines"></i> Pedidos</a>
                
                @if($rol === 'Administración')
                <hr>
                <a href="{{ route('reportes.index')}}">
                    <i class="fa-solid fa-chart-column"></i> Reportes
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- INVENTARIO -->
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

        <!-- CALENDARIO -->
        <a class="nav-link-app" href="{{ route('calendario.index') }}">
            <i class="fa-solid fa-calendar-days"></i> Calendario
        </a>

        <!-- ADMINISTRACIÓN -->
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

        <!-- MENÚ DE USUARIO -->
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

<div class="container-fluid mt-4" id="mainContent">
    @yield('content')
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Clase SessionManager - VERSIÓN CORREGIDA (SIN ERROR 419)
class SessionManager {
    constructor(timeoutMinutes = 16, warningMinutes = 1) {
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
        const events = ['mousedown', 'mousemove', 'keypress', 'keydown', 'scroll', 'touchstart', 'click', 'focus'];
        
        events.forEach(event => {
            document.addEventListener(event, () => this.resetTimer());
        });

        this.resetTimer();
        setInterval(() => this.updateTimerDisplay(), 1000);
    }

    resetTimer() {
        clearTimeout(this.timeoutTimer);
        clearTimeout(this.warningTimer);

        this.lastActivity = Date.now();
        this.isWarningShown = false;
        
        if (this.sessionTimer) {
            this.sessionTimer.classList.remove('warning');
        }

        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, this.timeoutMilliseconds - this.warningMilliseconds);

        this.timeoutTimer = setTimeout(() => {
            this.logout();
        }, this.timeoutMilliseconds);
    }

    updateTimerDisplay() {
        if (!this.sessionTimer || !this.sessionTimeSpan) return;
        
        const elapsed = (Date.now() - this.lastActivity) / 1000 / 60;
        const remaining = Math.max(0, this.timeoutMinutes - elapsed);
        
        const minutes = Math.floor(remaining);
        const seconds = Math.floor((remaining - minutes) * 60);
        this.sessionTimeSpan.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (remaining <= this.warningMinutes && remaining > 0) {
            this.sessionTimer.classList.add('warning');
        } else {
            this.sessionTimer.classList.remove('warning');
        }
        
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
                    <div class="countdown-number" id="countdownNumber">${this.warningMinutes}:00</div>
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
                    
                    const percentage = (secondsLeft / totalSeconds) * 100;
                    progressBar.style.width = percentage + '%';
                    
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
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            if (result.isConfirmed) {
                this.resetTimer();
                this.showToast('Sesión extendida', 'Continuarás activo por ' + this.timeoutMinutes + ' minutos más.', 'success');
            } else {
                this.logout();
            }
        });
    }

    // MÉTODO DE LOGOUT CORREGIDO - Usa GET en lugar de POST para evitar error 419
    logout() {
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
        
        setTimeout(() => {
            // Redirigir directamente a logout usando GET (esto evitará el error 419)
            window.location.href = '{{ route("logout") }}';
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

// SOLUCIÓN RADICAL PARA EL PROBLEMA DE COMPRAS
document.addEventListener('DOMContentLoaded', function() {
    console.log('🛠️ Aplicando solución para navbar...');
    
    // Inicializar SessionManager
    @auth
    const sessionManager = new SessionManager(16, 1);
    @endauth
    
    // Función para asegurar que el navbar sea clickeable
    function asegurarNavbarClickeable() {
        const navbar = document.getElementById('mainNavbar');
        const navMenu = document.getElementById('navMenu');
        const brandLink = document.getElementById('brandLink');
        const mobileToggle = document.getElementById('mobileToggle');
        
        // Forzar estilos críticos
        if (navbar) {
            navbar.style.pointerEvents = 'auto';
            navbar.style.zIndex = '9999';
            navbar.style.position = 'relative';
        }
        
        if (navMenu) {
            navMenu.style.pointerEvents = 'auto';
            navMenu.style.zIndex = '10000';
        }
        
        if (brandLink) {
            brandLink.style.pointerEvents = 'auto';
            brandLink.style.cursor = 'pointer';
        }
        
        if (mobileToggle) {
            mobileToggle.style.pointerEvents = 'auto';
            mobileToggle.style.cursor = 'pointer';
            mobileToggle.style.zIndex = '10001';
        }
        
        // Todos los enlaces del navbar
        document.querySelectorAll('.nav-link-app, .dropdown-menu-app a, .user-info').forEach(el => {
            el.style.pointerEvents = 'auto';
            el.style.cursor = 'pointer';
            el.style.position = 'relative';
            el.style.zIndex = '10002';
        });
    }
    
    // Función para prevenir que cualquier elemento fuera del navbar interfiera
    function prevenirInterferencias() {
        // Capturar todos los clics en el documento
        document.addEventListener('click', function(e) {
            // Si el clic es en el navbar o sus elementos, asegurar que pase
            if (e.target.closest('.navbar-app') || e.target.closest('#mainNavbar')) {
                console.log('✅ Clic en navbar detectado, permitiendo acción normal');
                // No hacer nada, dejar que el evento continúe
            }
        }, true); // Usar captura para asegurar que se ejecute antes que otros eventos
    }
    
    // Función para manejar el menú móvil
    function initMobileMenu() {
        const toggle = document.getElementById('mobileToggle');
        const menu = document.getElementById('navMenu');
        
        if (!toggle || !menu) return;
        
        let isMobile = window.innerWidth <= 992;
        
        function checkMobile() {
            isMobile = window.innerWidth <= 992;
            if (!isMobile) {
                menu.classList.remove('active');
            }
        }
        
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (isMobile) {
                menu.classList.toggle('active');
            }
        });
        
        // Cerrar al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (isMobile && menu.classList.contains('active') && 
                !menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.remove('active');
            }
        });
        
        window.addEventListener('resize', checkMobile);
        checkMobile();
    }
    
    // Función para manejar dropdowns en desktop
    function initDesktopDropdowns() {
        const items = document.querySelectorAll('.nav-item-app, .user-menu');
        
        items.forEach(item => {
            item.addEventListener('mouseenter', function() {
                if (window.innerWidth > 992) {
                    this.classList.add('active');
                }
            });
            
            item.addEventListener('mouseleave', function() {
                if (window.innerWidth > 992) {
                    this.classList.remove('active');
                }
            });
        });
    }
    
    // Función para manejar dropdowns en móvil
    function initMobileDropdowns() {
        const dropdowns = document.querySelectorAll('.nav-item-app .dropdown-btn, .user-menu .dropdown-btn');
        
        dropdowns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const parent = this.closest('.nav-item-app, .user-menu');
                    
                    // Cerrar otros
                    document.querySelectorAll('.nav-item-app, .user-menu').forEach(other => {
                        if (other !== parent) {
                            other.classList.remove('active');
                        }
                    });
                    
                    // Toggle actual
                    parent.classList.toggle('active');
                }
            });
        });
    }
    
    // Aplicar todas las soluciones
    asegurarNavbarClickeable();
    prevenirInterferencias();
    initMobileMenu();
    initDesktopDropdowns();
    initMobileDropdowns();
    
    console.log('✅ Solución para navbar aplicada completamente');
});

// Re-aplicar después de navegación con Livewire
document.addEventListener('livewire:navigated', function() {
    console.log('🔄 Livewire navegó - reaplicando soluciones');
    
    // Reaplicar todas las soluciones
    setTimeout(() => {
        document.querySelectorAll('.nav-link-app, .dropdown-menu-app a, .user-info').forEach(el => {
            el.style.pointerEvents = 'auto';
            el.style.cursor = 'pointer';
        });
        
        console.log('✅ Soluciones reaplicadas después de navegación');
    }, 100);
});

// Función de depuración para verificar que el navbar funciona
function debugNavbar() {
    console.log('=== DEBUG NAVBAR ===');
    console.log('Navbar existe:', document.getElementById('mainNavbar') !== null);
    console.log('Enlaces encontrados:', document.querySelectorAll('.nav-link-app').length);
    console.log('Ancho de pantalla:', window.innerWidth);
    console.log('Modo móvil:', window.innerWidth <= 992);
}

// Llamar a debug después de 1 segundo
setTimeout(debugNavbar, 1000);

// Funciones para reportes (mantener igual)
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
<form id="logout-form" method="POST" action="{{ route('welcome') }}" class="d-none">
    @csrf
</form>
@endauth

@stack('scripts')
</body>
</html>