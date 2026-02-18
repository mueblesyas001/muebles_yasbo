@extends('layouts.app')

@section('content')
<section class="password-reset-section d-flex align-items-center justify-content-center min-vh-100">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card password-reset-card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Header con gradiente -->
                    <div class="card-header-gradient p-4">
                        <div class="text-center">
                            <!-- Logo animado -->
                            <div class="logo-container mb-3 mx-auto">
                                <div class="logo-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-key logo-icon"></i>
                                </div>
                                <div class="logo-pulse"></div>
                            </div>
                            
                            <!-- Títulos -->
                            <h1 class="brand-name text-white fw-bold mb-2">RECUPERAR ACCESO</h1>
                            <p class="brand-subtitle text-white-80 mb-0">
                                MUEBLES YASBO • Sistema Administrativo
                            </p>
                        </div>
                    </div>
                    
                    <!-- Cuerpo del formulario -->
                    <div class="card-body p-4 p-md-5">
                        <!-- Información principal -->
                        <div class="text-center mb-4">
                            <h3 class="text-dark fw-bold mb-3">
                                <i class="fas fa-unlock-alt me-2 text-primary"></i>
                                Restablecer Contraseña
                            </h3>
                            <p class="text-muted mb-0">
                                Ingresa tu correo electrónico registrado. Te enviaremos un código de verificación para recuperar tu acceso.
                            </p>
                        </div>
                        
                        <!-- Mensajes de estado -->
                        @if (session('status'))
                            <div class="alert alert-success alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="alert-title mb-1">¡Solicitud recibida!</h5>
                                        <span class="alert-message">{{ session('status') }}</span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="alert-title mb-1">¡Operación exitosa!</h5>
                                        <span class="alert-message">{{ session('success') }}</span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="alert-title mb-1">Se encontraron errores</h5>
                                        @foreach ($errors->all() as $error)
                                            <span class="alert-message d-block">{{ $error }}</span>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Formulario -->
                        <form method="POST" action="{{ route('password.email') }}" id="passwordResetForm">
                            @csrf
                            
                            <!-- Campo Email -->
                            <div class="form-group mb-4">
                                <label for="email" class="form-label fw-semibold mb-3">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    Correo Electrónico Registrado
                                </label>
                                
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3 px-4">
                                        <i class="fas fa-at text-primary"></i>
                                    </span>
                                    <input type="email" 
                                            class="form-control @error('correo') is-invalid @enderror" 
                                            id="correo" 
                                            name="correo" 
                                            value="{{ old('correo') }}" 
                                            required
                                            placeholder="ejemplo@mueblesyasbo.com">
                                </div>
                                
                                @error('correo')  <!-- Cambiado de 'email' a 'correo' -->
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                <!-- Instrucciones -->
                                <div class="mt-3">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <span>Ingresa exactamente el mismo correo que utilizas para iniciar sesión.</span>
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Ventajas del sistema -->
                            <div class="row mb-4 g-3">
                                <div class="col-md-4">
                                    <div class="feature-box h-100 p-3 rounded-3 border text-center">
                                        <div class="feature-icon mx-auto mb-2">
                                            <i class="fas fa-shield-alt text-success fa-lg"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">Seguro</h6>
                                        <p class="text-muted small mb-0">
                                            Encriptación de última generación
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-box h-100 p-3 rounded-3 border text-center">
                                        <div class="feature-icon mx-auto mb-2">
                                            <i class="fas fa-bolt text-warning fa-lg"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">Rápido</h6>
                                        <p class="text-muted small mb-0">
                                            Código en menos de 1 minuto
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-box h-100 p-3 rounded-3 border text-center">
                                        <div class="feature-icon mx-auto mb-2">
                                            <i class="fas fa-clock text-info fa-lg"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">Temporal</h6>
                                        <p class="text-muted small mb-0">
                                            Válido por 15 minutos
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón de Enviar -->
                            <div class="d-grid mb-4">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg fw-bold py-3 rounded-3 reset-btn"
                                        id="submitBtn">
                                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                                    <span class="button-text">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        ENVIAR CÓDIGO DE VERIFICACIÓN
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Separador -->
                            <div class="divider my-4">
                                <span class="divider-text text-muted fw-medium">
                                    ¿Ya recordaste tu contraseña?
                                </span>
                            </div>
                            
                            <!-- Botón de volver -->
                            <div class="text-center">
                                <a href="{{ route('login') }}" 
                                   class="btn btn-outline-primary fw-semibold py-2 px-4 rounded-3">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    VOLVER AL INICIO DE SESIÓN
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Footer -->
                    <div class="card-footer bg-light border-0 py-3 px-4">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="security-badge me-3">
                                        <i class="fas fa-shield-alt text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-lock me-2"></i>
                                            Sistema protegido • © {{ date('Y') }} Muebles Yasbo • v2.0
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-user-shield me-2"></i>
                                            Proceso seguro y confidencial
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estilos personalizados -->
<style>
:root {
    --primary-color: #4361ee;
    --primary-dark: #3a56d4;
    --primary-light: #eef2ff;
    --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --gradient-success: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.1);
    --shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s ease;
}

.password-reset-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    padding: 20px;
}

/* Tarjeta principal */
.password-reset-card {
    background: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    animation: fadeInUp 0.6s ease-out;
}

.card-header-gradient {
    background: var(--gradient-primary);
    position: relative;
    overflow: hidden;
}

.card-header-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
    animation: shimmer 4s infinite linear;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Logo */
.logo-container {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto;
}

.logo-circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: white;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
    position: relative;
    z-index: 2;
    transition: var(--transition);
    border: 3px solid rgba(255, 255, 255, 0.8);
}

.logo-icon {
    font-size: 2.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.logo-pulse {
    position: absolute;
    top: -15px;
    left: -15px;
    right: -15px;
    bottom: -15px;
    border-radius: 50%;
    background: var(--gradient-primary);
    opacity: 0.2;
    z-index: 1;
    animation: pulse 2s ease-out infinite;
}

@keyframes pulse {
    0% { transform: scale(0.9); opacity: 0.2; }
    70% { opacity: 0.1; }
    100% { transform: scale(1.2); opacity: 0; }
}

/* Títulos */
.brand-name {
    font-size: 2rem;
    letter-spacing: -0.5px;
}

.brand-subtitle {
    font-size: 1rem;
    opacity: 0.9;
}

/* Inputs */
.input-group-lg .form-control,
.input-group-lg .input-group-text {
    border-radius: 12px !important;
    transition: var(--transition);
}

.input-group-text {
    background-color: white;
    border: 2px solid #e9ecef;
    border-right: none;
}

.form-control {
    border: 2px solid #e9ecef;
    border-left: none;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
}

/* Feature boxes */
.feature-box {
    transition: var(--transition);
    background: rgba(255, 255, 255, 0.5);
}

.feature-box:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-sm);
    border-color: var(--primary-color) !important;
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Botón principal */
.reset-btn {
    background: var(--gradient-primary);
    border: none;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

.reset-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, #2a0a91 100%);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
}

.reset-btn:active {
    transform: translateY(-1px);
}

/* Divider */
.divider {
    display: flex;
    align-items: center;
    text-align: center;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    border-bottom: 2px dashed #e9ecef;
}

.divider-text {
    padding: 0 1.5rem;
    color: #6c757d;
    font-weight: 500;
}

/* Botón outline */
.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    background: transparent;
    transition: var(--transition);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

/* Alertas */
.alert-elegant {
    border: none;
    border-left: 4px solid;
    border-radius: 10px;
    padding: 1rem 1.5rem;
}

.alert-success {
    border-left-color: #28a745;
    background: linear-gradient(to right, rgba(40, 167, 69, 0.05), transparent);
}

.alert-danger {
    border-left-color: #dc3545;
    background: linear-gradient(to right, rgba(220, 53, 69, 0.05), transparent);
}

.alert-title {
    font-size: 1.1rem;
    font-weight: 600;
}

.alert-icon {
    font-size: 1.5rem;
}

/* Error message */
.error-message {
    color: #dc3545;
    background: linear-gradient(to right, rgba(220, 53, 69, 0.05), transparent);
    border-radius: 8px;
    border-left: 4px solid #dc3545;
}

/* Security badge */
.security-badge {
    width: 50px;
    height: 50px;
    background: rgba(76, 201, 240, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(76, 201, 240, 0.3);
}

/* Animación de entrada */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .password-reset-card {
        margin: 0 10px;
    }
    
    .logo-container {
        width: 80px;
        height: 80px;
    }
    
    .logo-icon {
        font-size: 2rem;
    }
    
    .brand-name {
        font-size: 1.5rem;
    }
    
    .brand-subtitle {
        font-size: 0.9rem;
    }
    
    .feature-box {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1.5rem !important;
    }
    
    .security-badge {
        width: 40px;
        height: 40px;
        margin-right: 1rem;
    }
}
</style>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordResetForm');
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');
    const buttonText = submitBtn.querySelector('.button-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    // Validación en tiempo real
    emailInput.addEventListener('input', validateEmail);
    emailInput.addEventListener('blur', validateEmail);
    
    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        emailInput.classList.remove('is-valid', 'is-invalid');
        
        if (email === '') {
            emailInput.classList.add('is-invalid');
            return false;
        } else if (!emailRegex.test(email)) {
            emailInput.classList.add('is-invalid');
            return false;
        } else {
            emailInput.classList.add('is-valid');
            return true;
        }
    }
    
    // Manejo del envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isValid = validateEmail();
        
        if (isValid) {
            // Mostrar estado de carga
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            buttonText.innerHTML = 'ENVIANDO CÓDIGO...';
            submitBtn.style.transform = 'scale(0.98)';
            
            // Efecto de pulsación
            submitBtn.style.animation = 'pulse 1s infinite';
            
            // Enviar formulario después de breve delay
            setTimeout(() => {
                form.submit();
            }, 800);
        } else {
            // Efecto de error
            submitBtn.style.animation = 'shake 0.5s';
            setTimeout(() => {
                submitBtn.style.animation = '';
            }, 500);
            
            // Focus en el campo con error
            emailInput.focus();
            emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
    
    // Auto-focus al cargar
    setTimeout(() => {
        if (emailInput && !emailInput.value) {
            emailInput.focus();
        }
    }, 300);
    
    // Efectos hover en feature boxes
    const featureBoxes = document.querySelectorAll('.feature-box');
    featureBoxes.forEach(box => {
        box.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.feature-icon i');
            if (icon) {
                icon.style.transform = 'scale(1.2) rotate(5deg)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
        
        box.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.feature-icon i');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Auto-dismiss alerts después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
    
    // Agregar animación shake para errores
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection