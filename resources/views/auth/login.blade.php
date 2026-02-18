@extends('layouts.app')

@section('content')
<section class="login-section d-flex align-items-center justify-content-center min-vh-100">
    <!-- Fondo con elementos decorativos -->
    <div class="login-background">
        <div class="decorative-shape shape-1"></div>
        <div class="decorative-shape shape-2"></div>
        <div class="decorative-shape shape-3"></div>
    </div>
    
    <!-- Contenedor principal -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <!-- Tarjeta de login mejorada -->
                <div class="card login-card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header-gradient p-4">
                        <div class="text-center">
                            <!-- Logo mejorado -->
                            <div class="logo-container mb-3 mx-auto">
                                <div class="logo-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-chair logo-main-icon"></i>
                                </div>
                                <div class="logo-pulse"></div>
                            </div>
                            <h1 class="brand-name text-white fw-bold mb-1">MUEBLES YASBO</h1>
                            <p class="brand-subtitle text-white-80 mb-0">Sistema Administrativo</p>
                        </div>
                    </div>
                    
                    <!-- Cuerpo del formulario -->
                    <div class="card-body p-4 p-md-5">
                        <!-- Mensajes de estado -->
                        @if (session('status'))
                            <div class="alert alert-success alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <span class="alert-message">{{ session('status') }}</span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <span class="alert-message">{{ session('error') }}</span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <!-- Mensaje de error de credenciales (solo si hay errores en general) -->
                        @if($errors->any())
                            <div class="alert alert-warning alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <span class="alert-message">
                                            Usuario y/o contraseña incorrectos
                                        </span>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <!-- FORMULARIO -->
                        <form method="POST" action="{{ route('login.store') }}" id="loginForm" class="needs-validation" novalidate>
                            @csrf

                            <!-- Campo Email -->
                            <div class="form-group mb-4">
                                <label for="email" class="form-label text-dark fw-semibold mb-2">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                        <i class="fas fa-at text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control border-start-0 rounded-end-3 py-3 @error('correo') is-invalid @enderror" 
                                           id="email" 
                                           name="correo" 
                                           value="{{ old('correo') }}" 
                                           placeholder="ejemplo@correo.com" 
                                           required 
                                           autofocus>
                                    <div class="valid-feedback">
                                        <i class="fas fa-check-circle me-1"></i>¡Correcto!
                                    </div>
                                </div>
                                @error('correo')
                                    <div class="error-message mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span class="small">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- Campo Contraseña -->
                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label text-dark fw-semibold mb-0">
                                        <i class="fas fa-key me-2"></i>Contraseña
                                    </label>
                                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small fw-medium">
                                        <i class="fas fa-question-circle me-1"></i>¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Ingresa tu contraseña" 
                                           required>
                                    <button class="btn btn-outline-secondary border-start-0 rounded-end-3 py-3" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                    <div class="valid-feedback">
                                        <i class="fas fa-check-circle me-1"></i>¡Correcto!
                                    </div>
                                </div>
                                @error('password')
                                    <div class="error-message mt-2 d-flex align-items-center">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span class="small">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- Recordar sesión -->
                            <div class="form-group mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-medium" for="remember">
                                        <i class="fas fa-user-clock me-2"></i>Recordar sesión
                                    </label>
                                </div>
                            </div>

                            <!-- Botón de Ingresar -->
                            <button type="submit" 
                                    class="btn btn-primary btn-lg w-100 fw-semibold py-3 mb-3 rounded-3 login-btn shadow-sm"
                                    id="loginButton">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                <span class="button-text">
                                    <i class="fas fa-sign-in-alt me-2"></i>Ingresar al Sistema
                                </span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Footer de la tarjeta -->
                    <div class="card-footer bg-light border-0 py-3 px-4">
                        <div class="text-center">
                            <small class="text-muted d-flex align-items-center justify-content-center">
                                <i class="fas fa-shield-alt me-2"></i>
                                <span>Sistema seguro • © {{ date('Y') }} Muebles Yasbo • v2.0</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estilos mejorados -->
<style>
:root {
    --primary-color: #4361ee;
    --primary-dark: #3a56d4;
    --secondary-color: #6c757d;
    --success-color: #4cc9f0;
    --warning-color: #ffc107;
    --danger-color: #f72585;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --gradient-secondary: linear-gradient(135deg, #7209b7 0%, #f72585 100%);
    --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.15);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background-color: #f8fafc;
    overflow-x: hidden;
}

.login-section {
    position: relative;
    min-height: 100vh;
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    overflow: hidden;
}

.login-background {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 0;
}

.decorative-shape {
    position: absolute;
    border-radius: 50%;
    background: var(--gradient-primary);
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 300px;
    height: 300px;
    top: -150px;
    right: -150px;
    animation-delay: 0s;
}

.shape-2 {
    width: 200px;
    height: 200px;
    bottom: -100px;
    left: -100px;
    background: var(--gradient-secondary);
    animation-delay: 2s;
}

.shape-3 {
    width: 150px;
    height: 150px;
    top: 50%;
    left: 10%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(5deg);
    }
}

.login-card {
    position: relative;
    z-index: 10;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
    max-width: 550px;
    margin: 0 auto;
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
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
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
    animation: shimmer 3s infinite linear;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.logo-container {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
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
}

.logo-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(67, 97, 238, 0.4);
}

.logo-main-icon {
    font-size: 2.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.logo-pulse {
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    border-radius: 50%;
    background: var(--gradient-primary);
    opacity: 0.3;
    z-index: 1;
    animation: pulse 2s ease-out infinite;
}

@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 0.3;
    }
    50% {
        opacity: 0.1;
    }
    100% {
        transform: scale(1.2);
        opacity: 0;
    }
}

.brand-name {
    font-size: 1.8rem;
    letter-spacing: -0.5px;
}

.brand-subtitle {
    font-size: 0.95rem;
    opacity: 0.9;
}

/* Formulario */
.form-group {
    transition: var(--transition);
}

.form-control {
    border: 2px solid #e9ecef;
    font-size: 1rem;
    padding: 0.875rem 1rem;
    transition: var(--transition);
    background-color: white;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    background-color: white;
}

.input-group-text {
    border: 2px solid #e9ecef;
    background-color: white;
    color: var(--secondary-color);
}

.input-group-lg > .form-control,
.input-group-lg > .input-group-text,
.input-group-lg > .btn {
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
}

/* Alertas */
.alert-elegant {
    border: none;
    border-left: 4px solid;
    padding: 1rem 1.25rem;
}

.alert-success {
    background-color: rgba(76, 201, 240, 0.1);
    border-left-color: var(--success-color);
    color: #0c5460;
}

.alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-left-color: var(--warning-color);
    color: #856404;
}

.alert-danger {
    background-color: rgba(247, 37, 133, 0.1);
    border-left-color: var(--danger-color);
    color: #721c24;
}

.alert-icon {
    font-size: 1.25rem;
}

/* Botón de login */
.login-btn {
    background: var(--gradient-primary);
    border: none;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.login-btn:hover {
    background: linear-gradient(135deg, #3a56d4 0%, #2a0a91 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3) !important;
}

.login-btn:active {
    transform: translateY(0);
}

.login-btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.7s;
}

.login-btn:hover::after {
    left: 100%;
}

/* Switch personalizado */
.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    background-color: #e9ecef;
    border: 2px solid #dee2e6;
    cursor: pointer;
}

.form-switch .form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-switch .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
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
    border-bottom: 1px solid #e9ecef;
}

.divider-text {
    padding: 0 1rem;
    font-size: 0.875rem;
}

/* Validación */
.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    padding: 0.5rem;
    background: rgba(247, 37, 133, 0.05);
    border-radius: 0.5rem;
    border-left: 3px solid var(--danger-color);
}

.valid-feedback {
    display: none;
    color: #28a745;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.was-validated .form-control:valid ~ .valid-feedback {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .login-card {
        margin: 1rem;
        max-width: 100%;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .logo-container {
        width: 80px;
        height: 80px;
    }
    
    .brand-name {
        font-size: 1.5rem;
    }
    
    .form-control, .btn {
        font-size: 0.95rem;
    }
}

@media (min-width: 1200px) {
    .login-card {
        max-width: 600px;
    }
    
    .card-body {
        padding: 2.5rem 3rem !important;
    }
}

@media (min-width: 1400px) {
    .col-xl-6 {
        flex: 0 0 auto;
        width: 50%;
    }
    
    .login-card {
        max-width: 650px;
    }
}

@media (max-width: 576px) {
    .login-section {
        padding: 10px;
    }
    
    .card-header-gradient {
        padding: 1.5rem !important;
    }
    
    .shape-1, .shape-2, .shape-3 {
        display: none;
    }
}

/* Animaciones de entrada */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Mejoras de accesibilidad */
.form-control:focus-visible {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

.btn:focus-visible {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

/* Estado de carga */
#loginButton.loading .button-text {
    visibility: hidden;
}

#loginButton.loading .spinner-border {
    display: inline-block !important;
}
</style>

<!-- Scripts mejorados -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Toggle de contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
            
            // Feedback visual
            togglePassword.style.transform = 'scale(0.95)';
            setTimeout(() => {
                togglePassword.style.transform = 'scale(1)';
            }, 150);
        });
    }
    
    // Validación de formulario
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInputField = document.getElementById('password');
    const loginButton = document.getElementById('loginButton');
    
    // Validación en tiempo real
    emailInput.addEventListener('input', () => {
        validateEmail();
    });
    
    passwordInputField.addEventListener('input', () => {
        validatePassword();
    });
    
    function validateEmail() {
        const email = emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email === '') {
            setInvalid(emailInput, 'El correo electrónico es requerido');
            return false;
        } else if (!emailRegex.test(email)) {
            setInvalid(emailInput, 'Por favor ingresa un correo válido');
            return false;
        } else {
            setValid(emailInput);
            return true;
        }
    }
    
    function validatePassword() {
        const password = passwordInputField.value;
        
        if (password === '') {
            setInvalid(passwordInputField, 'La contraseña es requerida');
            return false;
        } else if (password.length < 6) {
            setInvalid(passwordInputField, 'La contraseña debe tener al menos 6 caracteres');
            return false;
        } else {
            setValid(passwordInputField);
            return true;
        }
    }
    
    function setInvalid(input, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
        // Mostrar mensaje de error
        let errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block mt-1 small';
            input.parentElement.appendChild(errorDiv);
        }
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${message}`;
    }
    
    function setValid(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        // Remover mensaje de error
        const errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Manejo de envío del formulario
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        
        if (isEmailValid && isPasswordValid) {
            // Mostrar estado de carga
            loginButton.classList.add('loading');
            loginButton.disabled = true;
            
            // Enviar formulario
            form.submit();
        } else {
            // Agitar el formulario para indicar error
            form.classList.add('was-validated');
            loginButton.style.animation = 'shake 0.5s';
            setTimeout(() => {
                loginButton.style.animation = '';
            }, 500);
        }
    });
    
    // Animación de shake
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .was-validated .form-control:invalid {
            border-color: #f72585;
        }
        
        .was-validated .form-control:valid {
            border-color: #28a745;
        }
    `;
    document.head.appendChild(style);
    
    // Efecto hover en los campos
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', () => {
            control.parentElement.style.transform = 'translateY(-2px)';
        });
        
        control.addEventListener('blur', () => {
            control.parentElement.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection