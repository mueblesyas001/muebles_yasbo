@extends('layouts.app')

@section('title', 'Inicio - Muebles Yasbo')

@section('content')
<style>
    /* ESTILOS EXCLUSIVOS PARA DASHBOARD */
    .dashboard-container {
        position: relative !important;
        min-height: calc(100vh - 60px) !important;
        margin-top: 60px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: linear-gradient(135deg, #f0f4ff 0%, #e6fffa 100%) !important;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(124, 58, 237, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.1) 0%, transparent 50%) !important;
        overflow: hidden !important;
        padding: 20px !important;
    }
    
    .dashboard-card {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        border-radius: 32px !important;
        padding: 3rem !important;
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.15),
            0 10px 30px rgba(0, 0, 0, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
        max-width: 1400px !important;
        width: 95% !important;
        text-align: center !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        position: relative !important;
        z-index: 1 !important;
        margin-top: 0 !important;
    }
    
    .dashboard-logo {
        width: 120px !important;
        height: 120px !important;
        margin: 0 auto 1.5rem !important;
        background: linear-gradient(135deg, #7c3aed, #10b981, #f59e0b) !important;
        border-radius: 25px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 
            0 20px 40px rgba(124, 58, 237, 0.3),
            inset 0 -8px 16px rgba(0, 0, 0, 0.2) !important;
        animation: float 6s ease-in-out infinite !important;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }
    
    .dashboard-logo i {
        font-size: 3rem !important;
        color: white !important;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2)) !important;
    }
    
    .dashboard-title {
        font-size: 3.5rem !important;
        font-weight: 800 !important;
        background: linear-gradient(135deg, #1a202c, #7c3aed) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
        margin-bottom: 0.5rem !important;
        letter-spacing: -1px !important;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.05) !important;
    }
    
    .dashboard-subtitle {
        font-size: 1.2rem !important;
        color: #4a5568 !important;
        margin-bottom: 2.5rem !important;
        opacity: 0.8 !important;
        max-width: 600px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        line-height: 1.6 !important;
    }
    
    /* MÓDULOS */
    .dashboard-modules {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 2rem !important;
        margin-bottom: 3rem !important;
    }
    
    .dashboard-module {
        background: white !important;
        border-radius: 20px !important;
        padding: 2rem 1.5rem !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        border: 1px solid rgba(255, 255, 255, 0.8) !important;
        position: relative !important;
        overflow: hidden !important;
        min-height: 220px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .dashboard-module:hover {
        transform: translateY(-8px) scale(1.02) !important;
        box-shadow: 0 25px 50px rgba(124, 58, 237, 0.15) !important;
    }
    
    .dashboard-module::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        height: 4px !important;
        background: linear-gradient(90deg, #7c3aed, #10b981) !important;
        transform: scaleX(0) !important;
        transform-origin: left !important;
        transition: transform 0.3s ease !important;
    }
    
    .dashboard-module:hover::before {
        transform: scaleX(1) !important;
    }
    
    .module-icon {
        width: 70px !important;
        height: 70px !important;
        border-radius: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto 1.5rem !important;
        font-size: 1.8rem !important;
        color: white !important;
        box-shadow: 
            0 10px 20px rgba(0, 0, 0, 0.1),
            inset 0 -2px 8px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.3s ease !important;
    }
    
    .dashboard-module:hover .module-icon {
        transform: scale(1.1) rotate(5deg) !important;
    }
    
    .module-icon-1 { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
    .module-icon-2 { background: linear-gradient(135deg, #10b981, #059669) !important; }
    .module-icon-3 { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
    .module-icon-4 { background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important; }
    
    .module-title {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #1a202c !important;
        margin-bottom: 0.5rem !important;
    }
    
    .module-desc {
        font-size: 0.95rem !important;
        color: #4a5568 !important;
        line-height: 1.6 !important;
        opacity: 0.8 !important;
    }
    
    /* BOTÓN DE INGRESAR */
    .enter-btn {
        background: linear-gradient(135deg, #7c3aed, #10b981) !important;
        color: white !important;
        border: none !important;
        padding: 1rem 2.5rem !important;
        border-radius: 16px !important;
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.8rem !important;
        margin-top: 1rem !important;
        margin-bottom: 2rem !important;
    }
    
    .enter-btn:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 15px 40px rgba(124, 58, 237, 0.4) !important;
    }
    
    .enter-btn i {
        font-size: 1.2rem !important;
    }
    
    /* STATS */
    .dashboard-stats {
        display: flex !important;
        justify-content: center !important;
        gap: 2rem !important;
        padding-top: 2rem !important;
        border-top: 1px solid rgba(226, 232, 240, 0.6) !important;
    }
    
    .stat-item {
        display: flex !important;
        align-items: center !important;
        gap: 1rem !important;
        padding: 1rem 1.5rem !important;
        background: rgba(248, 250, 252, 0.8) !important;
        border-radius: 16px !important;
        transition: all 0.3s ease !important;
    }
    
    .stat-item:hover {
        background: rgba(124, 58, 237, 0.1) !important;
        transform: translateY(-2px) !important;
    }
    
    .stat-icon {
        width: 40px !important;
        height: 40px !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: white !important;
    }
    
    .stat-icon-1 { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
    .stat-icon-2 { background: linear-gradient(135deg, #10b981, #059669) !important; }
    .stat-icon-3 { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
    
    .stat-value {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1a202c !important;
    }
    
    .stat-label {
        font-size: 0.9rem !important;
        color: #64748b !important;
    }
    
    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .dashboard-modules {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .dashboard-title {
            font-size: 3rem !important;
        }
    }
    
    @media (max-width: 992px) {
        .dashboard-container {
            margin-top: 56px !important;
            min-height: calc(100vh - 56px) !important;
        }
        
        .dashboard-card {
            padding: 2.5rem !important;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-card {
            padding: 2rem !important;
            border-radius: 24px !important;
        }
        
        .dashboard-title {
            font-size: 2.5rem !important;
        }
        
        .dashboard-logo {
            width: 100px !important;
            height: 100px !important;
        }
        
        .dashboard-logo i {
            font-size: 2.5rem !important;
        }
        
        .dashboard-modules {
            gap: 1.5rem !important;
        }
        
        .dashboard-stats {
            flex-direction: column !important;
            align-items: center !important;
            gap: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-modules {
            grid-template-columns: 1fr !important;
        }
        
        .dashboard-title {
            font-size: 2rem !important;
        }
        
        .dashboard-subtitle {
            font-size: 1rem !important;
        }
        
        .enter-btn {
            padding: 0.9rem 2rem !important;
            font-size: 1rem !important;
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-card">
        <!-- Logo -->
        <div class="dashboard-logo">
            <i class="fas fa-couch"></i>
        </div>
        
        <!-- Título -->
        <h1 class="dashboard-title">MUEBLES YASBO</h1>
        <p class="dashboard-subtitle">
            Sistema de Gestión Integral diseñado específicamente para optimizar 
            todas las operaciones de la Mueblería Yasbo
        </p>
        
        <!-- Botón de Ingresar -->
        <button class="enter-btn" onclick="window.location.href='{{ route('dashboard') }}'">
            <i class="fas fa-sign-in-alt"></i>
            Ingresar al Sistema
        </button>
        
        <!-- Módulos -->
        <div class="dashboard-modules">
            <!-- Módulo 1 -->
            <div class="dashboard-module" onclick="window.location.href='{{ route('productos.index') }}'">
                <div class="module-icon module-icon-1">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 class="module-title">Gestión de Inventario</h3>
                <p class="module-desc">Control completo de productos, categorías y niveles de stock en tiempo real</p>
            </div>
            
            <!-- Módulo 2 -->
            <div class="dashboard-module" onclick="window.location.href='{{ route('personal.index') }}'">
                <div class="module-icon module-icon-2">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="module-title">Administración</h3>
                <p class="module-desc">Sistema de usuarios, roles y permisos personalizables para todo el personal</p>
            </div>
            
            <!-- Módulo 3 -->
            <div class="dashboard-module" onclick="window.location.href='{{ route('compras.index') }}'">
                <div class="module-icon module-icon-3">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="module-title">Compras & Proveedores</h3>
                <p class="module-desc">Gestión de contactos, seguimiento de pedidos y control de proveedores</p>
            </div>
            
            <!-- Módulo 4 -->
            <div class="dashboard-module" onclick="window.location.href='{{ route('ventas.index') }}'">
                <div class="module-icon module-icon-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="module-title">Ventas & Reportes</h3>
                <p class="module-desc">Análisis, reportes financieros y seguimiento de ventas en tiempo real</p>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="dashboard-stats">
            <div class="stat-item">
                <div class="stat-icon stat-icon-1">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="stat-value">100%</div>
                    <div class="stat-label">Seguridad Garantizada</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon stat-icon-2">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <div class="stat-value">24/7</div>
                    <div class="stat-label">Disponibilidad Total</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon stat-icon-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">
                        @auth
                            {{ auth()->user()->empleado ? trim(auth()->user()->empleado->Nombre . ' ' . auth()->user()->empleado->ApPaterno) : 'Usuario' }}
                        @else
                            Invitado
                        @endauth
                    </div>
                    <div class="stat-label">Bienvenido al Sistema</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajustar altura dinámica del navbar
        function adjustDashboardHeight() {
            const navbar = document.querySelector('nav.navbar');
            const dashboard = document.querySelector('.dashboard-container');
            
            if (navbar && dashboard) {
                const navbarHeight = navbar.offsetHeight;
                dashboard.style.marginTop = `${navbarHeight}px`;
                dashboard.style.minHeight = `calc(100vh - ${navbarHeight}px)`;
            }
        }
        
        adjustDashboardHeight();
        window.addEventListener('resize', adjustDashboardHeight);
        
        // Animación de escritura para el título
        const title = document.querySelector('.dashboard-title');
        if (title) {
            const text = title.textContent;
            title.textContent = '';
            let i = 0;
            
            function typeWriter() {
                if (i < text.length) {
                    title.textContent += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, 60);
                }
            }
            
            setTimeout(typeWriter, 300);
        }
        
        // Efectos hover en módulos
        const modules = document.querySelectorAll('.dashboard-module');
        modules.forEach(module => {
            module.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            module.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
            
            // Efecto de clic
            module.addEventListener('click', function(e) {
                e.preventDefault();
                this.style.transform = 'translateY(-4px) scale(0.98)';
                
                // Pequeña animación antes de redirigir
                setTimeout(() => {
                    const href = this.getAttribute('onclick')?.match(/'(.*?)'/)?.[1];
                    if (href) window.location.href = href;
                }, 150);
            });
        });
        
        // Efecto en el botón
        const enterBtn = document.querySelector('.enter-btn');
        if (enterBtn) {
            enterBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });
            
            enterBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
            
            enterBtn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        }
        
        // Contador animado para estadísticas
        const statValues = document.querySelectorAll('.stat-value');
        statValues.forEach(stat => {
            if (stat.textContent === '100%' || stat.textContent === '24/7') {
                const target = stat.textContent;
                let current = 0;
                const duration = 1500;
                const step = target === '100%' ? 1 : 1;
                const max = target === '100%' ? 100 : 24;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current > max) {
                        stat.textContent = target;
                        clearInterval(timer);
                    } else {
                        stat.textContent = target === '24/7' ? current + '/7' : current + '%';
                    }
                }, duration / max);
            }
        });
        
        // Efecto de onda al hacer clic
        document.querySelectorAll('.dashboard-module, .enter-btn').forEach(element => {
            element.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(124, 58, 237, 0.2);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                    z-index: 1;
                `;
                
                this.appendChild(ripple);
                setTimeout(() => {
                    if (ripple.parentNode === this) {
                        this.removeChild(ripple);
                    }
                }, 600);
            });
        });
        
        // Agregar estilo para ripple
        if (!document.querySelector('#ripple-style')) {
            const style = document.createElement('style');
            style.id = 'ripple-style';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    });
</script>
@endsection