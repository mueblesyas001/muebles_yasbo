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
}

/* ===== NAVBAR ===== */
.navbar-app{
    background:var(--nav);
    padding:14px 28px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
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

/* ===== MOBILE ===== */
#mobileToggle{
    border:none;
    background:var(--purple);
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
}

@media(max-width:992px){
    #navMenu{
        display:none;
        background:var(--nav);
        margin-top:14px;
        padding:16px;
        border-radius:18px;
        flex-direction:column;
    }

    #navMenu.active{display:flex}

    .user-menu{
        width:100%;
    }

    .user-info{
        justify-content:center;
        padding:12px;
    }

    .dropdown-menu-app{
        position:static;
        box-shadow:none;
        border:none;
        background:transparent;
        padding:8px 0 0 0;
        min-width:100%;
    }

    .dropdown-menu-app a{
        color:#e5e7eb;
        padding:10px 16px;
    }

    .dropdown-menu-app a:hover{
        background:#1f2937;
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
}
</style>
</head>

<body>

<nav class="navbar-app">
<div class="container-fluid d-flex justify-content-between align-items-center">

    @auth
    <a href="{{ route('home') }}" class="brand">
        <i class="fa-solid fa-couch"></i> Yasbo
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
        {{-- MOSTRAR SOLO SI NO ESTÁ AUTENTICADO --}}
        @guest
        <a class="nav-link-app" href="{{ route('login') }}">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
        </a>
        @endguest

        {{-- MOSTRAR SOLO SI ESTÁ AUTENTICADO --}}
        @auth
        <!-- INICIO -->
        <a class="nav-link-app" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-house"></i> Inicio
        </a>

        <!-- OPERACIONES -->
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-briefcase"></i> Operaciones
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('ventas.index') }}"><i class="fa-solid fa-cash-register"></i> Ventas</a>
                <a href="{{ route('compras.index') }}"><i class="fa-solid fa-cart-shopping"></i> Compras</a>
                <a href="{{ route('pedidos.index') }}"><i class="fa-solid fa-file-lines"></i> Pedidos</a>
                <!-- AGREGADO: MENÚ DE REPORTES -->
                <hr>
                <a href="{{ route('reportes.index')}}">
                    <i class="fa-solid fa-chart-column"></i> Reportes
                </a>
            </div>
        </div>

        <!-- INVENTARIO -->
        <div class="nav-item-app">
            <div class="nav-link-app dropdown-btn">
                <i class="fa-solid fa-boxes-stacked"></i> Inventario
            </div>
            <div class="dropdown-menu-app">
                <a href="{{ route('productos.index') }}"><i class="fa-solid fa-box"></i> Productos</a>
                <a href="{{ route('categorias.index') }}"><i class="fa-solid fa-layer-group"></i> Categorías</a>
            </div>
        </div>

        <!-- CALENDARIO -->
        <a class="nav-link-app" href="{{ route('calendario.index') }}">
            <i class="fa-solid fa-calendar-days"></i> Calendario
        </a>

        <!-- ADMINISTRACIÓN (SOLO PARA ADMINISTRADORES) -->
        @if(auth()->user()->rol === 'Administración')
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
                    {{-- Obtener la primera letra del nombre del empleado --}}
                    @php
                        $empleado = auth()->user()->empleado;
                        $nombreCompleto = '';
                        $inicial = 'U';
                        
                        if($empleado) {
                            $inicial = strtoupper(substr($empleado->Nombre ?? '', 0, 1));
                            $nombreCompleto = trim($empleado->Nombre . ' ' . $empleado->ApPaterno);
                        }
                    @endphp
                    {{ $inicial }}
                </div>
                <span>
                    {{ $empleado ? trim($empleado->Nombre . ' ' . $empleado->ApPaterno) : 'Usuario' }}
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
<!-- Librerías para reportes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded',()=>{
    const toggle=document.getElementById('mobileToggle');
    const menu=document.getElementById('navMenu');
    const items=document.querySelectorAll('.nav-item-app');
    const userMenu=document.querySelector('.user-menu');

    // Toggle menu móvil
    if(toggle){
        toggle.onclick=()=>{
            menu.classList.toggle('active');
            // Cerrar todos los dropdowns
            items.forEach(item=>item.classList.remove('active'));
            if(userMenu) userMenu.classList.remove('active');
        };
    }

    // Dropdowns normales
    items.forEach(item=>{
        const btn=item.querySelector('.dropdown-btn');
        if(!btn) return;

        btn.onclick=e=>{
            if(window.innerWidth<=992){
                e.preventDefault();
                e.stopPropagation();
                // Cerrar otros dropdowns
                items.forEach(other=>{
                    if(other!==item) other.classList.remove('active');
                });
                if(userMenu) userMenu.classList.remove('active');
                item.classList.toggle('active');
            }
        };

        item.onmouseenter=()=>window.innerWidth>992&&item.classList.add('active');
        item.onmouseleave=()=>window.innerWidth>992&&item.classList.remove('active');
    });

    // Menú de usuario
    if(userMenu){
        const btn=userMenu.querySelector('.dropdown-btn');
        
        btn.onclick=(e)=>{
            if(window.innerWidth<=992){
                e.preventDefault();
                e.stopPropagation();
                // Cerrar otros dropdowns
                items.forEach(item=>item.classList.remove('active'));
                userMenu.classList.toggle('active');
            }
        };

        userMenu.onmouseenter=()=>window.innerWidth>992&&userMenu.classList.add('active');
        userMenu.onmouseleave=()=>window.innerWidth>992&&userMenu.classList.remove('active');

        // Cerrar menús al hacer clic fuera
        document.addEventListener('click',(e)=>{
            if(window.innerWidth>992) return;
            
            if(userMenu && !userMenu.contains(e.target)){
                userMenu.classList.remove('active');
            }
            items.forEach(item=>{
                if(!item.contains(e.target)){
                    item.classList.remove('active');
                }
            });
        });

        // Cerrar menús al redimensionar
        window.addEventListener('resize',()=>{
            if(window.innerWidth>992){
                items.forEach(item=>item.classList.remove('active'));
                userMenu.classList.remove('active');
            }
        });
    }
});

// Funciones para reportes
function generarPDFReporte(nombre, titulo, datos, columnas) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Título
    doc.setFontSize(20);
    doc.text(titulo, 14, 15);
    doc.setFontSize(10);
    doc.text(`Generado: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 14, 22);
    
    // Crear tabla
    doc.autoTable({
        head: [columnas],
        body: datos,
        startY: 30,
        theme: 'grid',
        styles: { fontSize: 9 },
        headStyles: { fillColor: [109, 40, 217] }
    });
    
    // Guardar
    doc.save(`${nombre}_${new Date().toISOString().slice(0,10)}.pdf`);
}

function exportarExcel(nombre, datos, columnas) {
    // Crear tabla HTML para Excel
    let tableHtml = '<table border="1">';
    
    // Encabezados
    tableHtml += '<tr>';
    columnas.forEach(col => {
        tableHtml += `<th>${col}</th>`;
    });
    tableHtml += '</tr>';
    
    // Datos
    datos.forEach(fila => {
        tableHtml += '<tr>';
        fila.forEach(celda => {
            tableHtml += `<td>${celda}</td>`;
        });
        tableHtml += '</tr>';
    });
    
    tableHtml += '</table>';
    
    // Crear y descargar archivo
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