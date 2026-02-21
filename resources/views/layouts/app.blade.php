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

        <a class="nav-link-app" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-house"></i> Inicio
        </a>

        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-briefcase"></i> Operaciones
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('ventas.index') }}"><i class="fa-solid fa-cash-register"></i> Ventas</a>
                <a href="{{ route('compras.index') }}"><i class="fa-solid fa-cart-shopping"></i> Compras</a>
                <a href="{{ route('pedidos.index') }}"><i class="fa-solid fa-file-lines"></i> Pedidos</a>
                <hr>
                <a href="{{ route('reportes.index')}}">
                    <i class="fa-solid fa-chart-column"></i> Reportes
                </a>
            </div>
        </div>

        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-boxes-stacked"></i> Inventario
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('productos.index') }}"><i class="fa-solid fa-box"></i> Productos</a>
                <a href="{{ route('categorias.index') }}"><i class="fa-solid fa-layer-group"></i> Categorías</a>
            </div>
        </div>

        <a class="nav-link-app" href="{{ route('calendario.index') }}">
            <i class="fa-solid fa-calendar-days"></i> Calendario
        </a>

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
                <hr>
                <a href="{{ route('reportes.index') }}?tipo=rentabilidad">
                    <i class="fa-solid fa-chart-pie"></i> Rentabilidad
                </a>
                <a href="{{ route('reportes.index') }}?tipo=ventas">
                    <i class="fa-solid fa-chart-line"></i> Análisis de Ventas
                </a>
            </div>
        </div>
        @endif

        @if($rol === 'Almacén')
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-warehouse"></i> Almacén
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('productos.index') }}"><i class="fa-solid fa-box"></i> Productos</a>
                <a href="{{ route('categorias.index') }}"><i class="fa-solid fa-layer-group"></i> Categorías</a>
                <a href="{{ route('inventario.index') }}"><i class="fa-solid fa-clipboard-list"></i> Control de Inventario</a>
                <hr>
                <a href="{{ route('reportes.index') }}?tipo=inventario">
                    <i class="fa-solid fa-boxes"></i> Reporte de Inventario
                </a>
                <a href="{{ route('reportes.index') }}?tipo=productos">
                    <i class="fa-solid fa-chart-simple"></i> Reporte de Productos
                </a>
            </div>
        </div>
        @endif

        @if($rol === 'Logística')
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-truck-fast"></i> Logística
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('proveedores.index') }}"><i class="fa-solid fa-truck"></i> Proveedores</a>
                <a href="{{ route('compras.index') }}"><i class="fa-solid fa-cart-shopping"></i> Compras</a>
                <a href="{{ route('pedidos.index') }}"><i class="fa-solid fa-truck-loading"></i> Pedidos</a>
                <hr>
                <a href="{{ route('reportes.index') }}?tipo=compras">
                    <i class="fa-solid fa-file-invoice"></i> Reporte de Compras
                </a>
                <a href="{{ route('reportes.index') }}?tipo=inventario">
                    <i class="fa-solid fa-boxes"></i> Reporte de Inventario
                </a>
                <a href="{{ route('reportes.index') }}?tipo=proveedores">
                    <i class="fa-solid fa-truck-field"></i> Reporte de Proveedores
                </a>
            </div>
        </div>
        @endif

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('mobileToggle');
    const menu = document.getElementById('navMenu');
    const items = document.querySelectorAll('.nav-item-app');
    const userMenu = document.querySelector('.user-menu');
    
    let isMobile = window.innerWidth <= 992;

    // Función para verificar si estamos en móvil
    function checkMobile() {
        isMobile = window.innerWidth <= 992;
        if (!isMobile) {
            // Si estamos en desktop, aseguramos que el menú sea visible y sin estilos móviles
            menu.style.display = 'flex';
            menu.classList.remove('active');
            closeAllDropdowns();
        } else {
            // Si estamos en móvil, ocultamos el menú inicialmente
            menu.style.display = '';
            menu.classList.remove('active');
        }
    }

    // Función para cerrar todos los dropdowns
    function closeAllDropdowns() {
        items.forEach(item => item.classList.remove('active'));
        if (userMenu) userMenu.classList.remove('active');
    }

    // Función para cerrar el menú hamburguesa
    function closeHamburgerMenu() {
        if (isMobile) {
            menu.classList.remove('active');
            closeAllDropdowns();
        }
    }

    // Función para abrir/cerrar el menú hamburguesa
    function toggleHamburgerMenu() {
        if (isMobile) {
            menu.classList.toggle('active');
            if (!menu.classList.contains('active')) {
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
            if (isMobile) {
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
                if (isMobile) {
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
        if (isMobile) {
            const isClickInside = menu.contains(e.target) || toggle.contains(e.target);
            
            if (!isClickInside) {
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

    // Cerrar al hacer clic en enlaces
    const allLinks = document.querySelectorAll('.nav-link-app:not(.dropdown-btn), .dropdown-menu-app a');
    allLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (isMobile) {
                setTimeout(closeHamburgerMenu, 100);
            }
        });
    });

    // Manejar redimensionamiento
    window.addEventListener('resize', function() {
        checkMobile();
    });

    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMobile) {
            closeHamburgerMenu();
        }
    });

    // Eventos hover solo para desktop
    function setupHoverEvents() {
        if (!isMobile) {
            items.forEach(item => {
                item.addEventListener('mouseenter', () => item.classList.add('active'));
                item.addEventListener('mouseleave', () => item.classList.remove('active'));
            });
            if (userMenu) {
                userMenu.addEventListener('mouseenter', () => userMenu.classList.add('active'));
                userMenu.addEventListener('mouseleave', () => userMenu.classList.remove('active'));
            }
        } else {
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