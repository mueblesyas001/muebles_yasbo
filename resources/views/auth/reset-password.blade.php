@extends('layouts.app')

@section('content')
<section class="reset-password-section d-flex align-items-center justify-content-center min-vh-100">
    <!-- Fondo con elementos decorativos -->
    <div class="reset-background">
        <div class="decorative-shape shape-1"></div>
        <div class="decorative-shape shape-2"></div>
        <div class="decorative-shape shape-3"></div>
    </div>
    
    <!-- Contenedor principal más ancho -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11 col-xl-10">
                <!-- Tarjeta más ancha -->
                <div class="card reset-card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header-gradient p-4">
                        <div class="text-center">
                            <!-- Logo mejorado -->
                            <div class="logo-container mb-3 mx-auto">
                                <div class="logo-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-key logo-main-icon"></i>
                                </div>
                                <div class="logo-pulse"></div>
                            </div>
                            <h1 class="brand-name text-white fw-bold mb-1">NUEVA CONTRASEÑA</h1>
                            <p class="brand-subtitle text-white-80 mb-0">Crea una contraseña segura para tu cuenta</p>
                        </div>
                    </div>
                    
                    <!-- Cuerpo del formulario más amplio -->
                    <div class="card-body p-4 p-xl-5">
                        <!-- Mensajes de estado -->
                        @if (session('success'))
                            <div class="alert alert-success alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <span class="alert-message">{{ session('success') }}</span>
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

                        <!-- Mensaje de verificación mejorado -->
                        <div class="verification-badge mb-4 p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center">
                                <div class="verification-icon me-3">
                                    <i class="fas fa-envelope-open-text text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">¡Correo electrónico verificado exitosamente!</h6>
                                    <p class="small text-muted mb-0">Hemos confirmado tu identidad. Ahora puedes crear una nueva contraseña segura para tu cuenta.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FORMULARIO con grid de 2 columnas para mejor distribución -->
                        <form method="POST" action="{{ route('password.update') }}" id="resetForm" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="row g-4">
                                <!-- Columna izquierda -->
                                <div class="col-lg-6">
                                    <!-- Campo Nueva Contraseña con botón de generar -->
                                    <div class="form-group mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="password" class="form-label text-dark fw-semibold">
                                                <i class="fas fa-key me-2"></i>Nueva Contraseña
                                            </label>
                                            <button type="button" class="btn btn-sm btn-outline-primary generate-password-btn" id="generatePasswordBtn">
                                                <i class="fas fa-dice me-1"></i>
                                                Generar contraseña
                                            </button>
                                        </div>
                                        <div class="input-group input-group-lg shadow-sm">
                                            <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control border-start-0 rounded-end-3 py-3 @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Ingresa tu nueva contraseña" 
                                                   required>
                                            <button class="btn btn-outline-secondary border-start-0 rounded-end-3 py-3" type="button" id="togglePassword">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="error-message mt-2 d-flex align-items-center">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                <span class="small">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Campo Confirmar Contraseña -->
                                    <div class="form-group mb-4">
                                        <label for="password_confirmation" class="form-label text-dark fw-semibold mb-2">
                                            <i class="fas fa-check-circle me-2"></i>Confirmar Contraseña
                                        </label>
                                        <div class="input-group input-group-lg shadow-sm">
                                            <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control border-start-0 rounded-end-3 py-3" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   placeholder="Repite tu nueva contraseña" 
                                                   required>
                                            <button class="btn btn-outline-secondary border-start-0 rounded-end-3 py-3" type="button" id="toggleConfirmPassword">
                                                <i class="fas fa-eye" id="confirmEyeIcon"></i>
                                            </button>
                                        </div>
                                        <div id="passwordMatch" class="mt-2 small"></div>
                                    </div>

                                    <!-- Botón para copiar contraseña (aparece cuando se genera una) -->
                                    <div id="copyPasswordContainer" class="mb-4 d-none">
                                        <button type="button" class="btn btn-outline-success btn-sm w-100" id="copyPasswordBtn">
                                            <i class="fas fa-copy me-2"></i>
                                            Copiar contraseña generada
                                        </button>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-lg-6">
                                    <!-- Medidor de seguridad mejorado -->
                                    <div class="security-meter p-3 bg-light rounded-3 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fw-semibold">
                                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                Seguridad:
                                            </span>
                                            <span class="badge bg-white text-dark px-3 py-2" id="strengthBadge">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Débil
                                            </span>
                                        </div>
                                        <div class="progress mb-3" style="height: 10px;">
                                            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                        </div>
                                        
                                        <!-- Requisitos en grid compacto -->
                                        <div class="requirements-grid">
                                            <div class="requirement-item" id="req-length">
                                                <i class="fas fa-circle-check"></i>
                                                <span class="small">8+ caracteres</span>
                                            </div>
                                            <div class="requirement-item" id="req-uppercase">
                                                <i class="fas fa-circle-check"></i>
                                                <span class="small">Mayúscula</span>
                                            </div>
                                            <div class="requirement-item" id="req-lowercase">
                                                <i class="fas fa-circle-check"></i>
                                                <span class="small">Minúscula</span>
                                            </div>
                                            <div class="requirement-item" id="req-number">
                                                <i class="fas fa-circle-check"></i>
                                                <span class="small">Número</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Botones de acción más grandes -->
                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg w-100 py-3 rounded-3">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Cancelar
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" 
                                            class="btn btn-primary btn-lg w-100 py-3 rounded-3 reset-btn shadow-sm"
                                            id="resetButton">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                        <span class="button-text">
                                            <i class="fas fa-save me-2"></i>Guardar Contraseña
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Footer de la tarjeta -->
                    <div class="card-footer bg-light border-0 py-3 px-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center text-md-start">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-2 text-primary"></i>
                                    Contraseña encriptada SSL
                                </small>
                            </div>
                            <div class="col-md-6 text-center text-md-end">
                                <small class="text-muted">
                                    <i class="fas fa-copyright me-1"></i>
                                    {{ date('Y') }} Muebles Yasbo · v2.0
                                </small>
                            </div>
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

.reset-password-section {
    position: relative;
    min-height: 100vh;
    padding: 30px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    overflow: hidden;
}

.reset-background {
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
    width: 400px;
    height: 400px;
    top: -200px;
    right: -200px;
    animation-delay: 0s;
}

.shape-2 {
    width: 300px;
    height: 300px;
    bottom: -150px;
    left: -150px;
    background: var(--gradient-secondary);
    animation-delay: 2s;
}

.shape-3 {
    width: 200px;
    height: 200px;
    top: 30%;
    left: 5%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(5deg);
    }
}

.reset-card {
    position: relative;
    z-index: 10;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
    width: 100%;
}

.reset-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
}

.card-header-gradient {
    background: var(--gradient-primary);
    position: relative;
    overflow: hidden;
    padding: 2rem !important;
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
    font-size: 2.8rem;
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
        transform: scale(1.3);
        opacity: 0;
    }
}

.brand-name {
    font-size: 2.2rem;
    letter-spacing: -0.5px;
}

/* Verification Badge */
.verification-badge {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border-left: 4px solid var(--primary-color);
    border-radius: 12px;
    padding: 1.2rem !important;
}

.verification-icon {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-sm);
}

.verification-icon i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

/* Formulario */
.form-control {
    border: 2px solid #e9ecef;
    font-size: 1rem;
    padding: 1rem 1.25rem;
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
    padding: 1rem 1.25rem;
    font-size: 1rem;
}

/* Generate Password Button */
.generate-password-btn {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    transition: var(--transition);
}

.generate-password-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(67, 97, 238, 0.2);
}

.generate-password-btn i {
    transition: transform 0.3s ease;
}

.generate-password-btn:hover i {
    transform: rotate(180deg);
}

/* Security Meter */
.security-meter {
    border: 1px solid #e9ecef;
    background: white !important;
}

.progress {
    background-color: #e9ecef;
    border-radius: 100px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.3s ease;
    background: linear-gradient(90deg, #f72585, #ffc107, #4cc9f0);
}

/* Requirements Grid */
.requirements-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: var(--transition);
}

.requirement-item i {
    font-size: 0.95rem;
    color: #adb5bd;
    transition: var(--transition);
}

.requirement-item.valid {
    border-color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
}

.requirement-item.valid i {
    color: var(--primary-color);
}

/* Security Tips */
.security-tips {
    border: 1px solid rgba(67, 97, 238, 0.2);
}

.security-tips ul {
    list-style-type: none;
    padding-left: 0 !important;
}

.security-tips li {
    padding-left: 1.5rem;
    position: relative;
}

.security-tips li::before {
    content: '•';
    color: var(--primary-color);
    font-size: 1.2rem;
    position: absolute;
    left: 0;
    top: -2px;
}

/* Botones */
.reset-btn {
    background: var(--gradient-primary);
    border: none;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.reset-btn:hover {
    background: linear-gradient(135deg, #3a56d4 0%, #2a0a91 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3) !important;
}

.reset-btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.7s;
}

.reset-btn:hover::after {
    left: 100%;
}

.btn-outline-secondary {
    border: 2px solid #e9ecef;
    color: var(--secondary-color);
    transition: var(--transition);
}

.btn-outline-secondary:hover {
    background: var(--light-color);
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-success {
    border: 2px solid #4cc9f0;
    color: #4cc9f0;
    transition: var(--transition);
}

.btn-outline-success:hover {
    background: #4cc9f0;
    color: white;
    border-color: #4cc9f0;
}

/* Responsive */
@media (min-width: 1400px) {
    .reset-card {
        max-width: 1300px;
        margin: 0 auto;
    }
    
    .card-body {
        padding: 3rem !important;
    }
}

@media (max-width: 992px) {
    .requirements-grid {
        margin-bottom: 1rem;
    }
    
    .security-tips {
        margin-top: 1rem;
    }
}

@media (max-width: 768px) {
    .reset-password-section {
        padding: 15px;
    }
    
    .card-header-gradient {
        padding: 1.5rem !important;
    }
    
    .brand-name {
        font-size: 1.8rem;
    }
    
    .shape-1, .shape-2, .shape-3 {
        display: none;
    }
    
    .generate-password-btn {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
}

/* Animaciones */
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

.reset-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Password Match */
#passwordMatch .text-success {
    color: #4cc9f0 !important;
    font-weight: 500;
}

#passwordMatch .text-danger {
    color: #f72585 !important;
    font-weight: 500;
}

/* Copy button animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#copyPasswordContainer:not(.d-none) {
    animation: fadeIn 0.3s ease-out;
}
</style>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Toggle de contraseña principal
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
            
            togglePassword.style.transform = 'scale(0.95)';
            setTimeout(() => {
                togglePassword.style.transform = 'scale(1)';
            }, 150);
        });
    }
    
    // Toggle de confirmación
    const toggleConfirm = document.getElementById('toggleConfirmPassword');
    const confirmInput = document.getElementById('password_confirmation');
    const confirmIcon = document.getElementById('confirmEyeIcon');
    
    if (toggleConfirm) {
        toggleConfirm.addEventListener('click', () => {
            const type = confirmInput.type === 'password' ? 'text' : 'password';
            confirmInput.type = type;
            confirmIcon.classList.toggle('fa-eye');
            confirmIcon.classList.toggle('fa-eye-slash');
            
            toggleConfirm.style.transform = 'scale(0.95)';
            setTimeout(() => {
                toggleConfirm.style.transform = 'scale(1)';
            }, 150);
        });
    }
    
    // Generador de contraseña aleatoria
    const generateBtn = document.getElementById('generatePasswordBtn');
    const copyContainer = document.getElementById('copyPasswordContainer');
    const copyBtn = document.getElementById('copyPasswordBtn');
    
    generateBtn.addEventListener('click', generateRandomPassword);
    
    function generateRandomPassword() {
        const length = 12; // Longitud de la contraseña
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';
        const symbols = '!@#$%^&*';
        
        let chars = '';
        let password = '';
        
        // Asegurar al menos un carácter de cada tipo
        password += uppercase[Math.floor(Math.random() * uppercase.length)];
        password += lowercase[Math.floor(Math.random() * lowercase.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];
        
        // Completar el resto de la contraseña
        chars = uppercase + lowercase + numbers + symbols;
        for (let i = password.length; i < length; i++) {
            password += chars[Math.floor(Math.random() * chars.length)];
        }
        
        // Mezclar la contraseña para que no siempre empiece con los mismos patrones
        password = password.split('').sort(() => Math.random() - 0.5).join('');
        
        // Asignar la contraseña generada
        passwordInput.value = password;
        confirmInput.value = password;
        
        // Mostrar el botón de copiar
        copyContainer.classList.remove('d-none');
        
        // Disparar eventos para actualizar validaciones
        passwordInput.dispatchEvent(new Event('input'));
        confirmInput.dispatchEvent(new Event('input'));
        
        // Feedback visual
        generateBtn.innerHTML = '<i class="fas fa-check me-1"></i>¡Generada!';
        setTimeout(() => {
            generateBtn.innerHTML = '<i class="fas fa-dice me-1"></i>Generar contraseña';
        }, 2000);
        
        // Animación en los inputs
        passwordInput.style.transform = 'scale(1.02)';
        confirmInput.style.transform = 'scale(1.02)';
        setTimeout(() => {
            passwordInput.style.transform = 'scale(1)';
            confirmInput.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Copiar contraseña al portapapeles
    copyBtn.addEventListener('click', () => {
        passwordInput.select();
        document.execCommand('copy');
        
        // Feedback visual
        copyBtn.innerHTML = '<i class="fas fa-check me-2"></i>¡Copiada!';
        setTimeout(() => {
            copyBtn.innerHTML = '<i class="fas fa-copy me-2"></i>Copiar contraseña generada';
        }, 2000);
        
        // Deseleccionar
        window.getSelection().removeAllRanges();
    });
    
    // Password strength checker
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('strengthBar');
    const strengthBadge = document.getElementById('strengthBadge');
    const matchDiv = document.getElementById('passwordMatch');
    
    // Elementos de requisitos
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    
    password.addEventListener('input', checkPasswordStrength);
    confirm.addEventListener('input', checkPasswordMatch);
    
    function checkPasswordStrength() {
        const value = password.value;
        
        // Verificar requisitos
        const hasLength = value.length >= 8;
        const hasUppercase = /[A-Z]/.test(value);
        const hasLowercase = /[a-z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        
        // Actualizar requisitos visuales
        reqLength.classList.toggle('valid', hasLength);
        reqUppercase.classList.toggle('valid', hasUppercase);
        reqLowercase.classList.toggle('valid', hasLowercase);
        reqNumber.classList.toggle('valid', hasNumber);
        
        // Calcular fortaleza
        const checks = [hasLength, hasUppercase, hasLowercase, hasNumber];
        const strength = checks.filter(Boolean).length * 25;
        
        // Actualizar barra
        strengthBar.style.width = strength + '%';
        
        // Actualizar badge
        if (strength < 50) {
            strengthBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Débil';
            strengthBadge.className = 'badge bg-danger bg-opacity-10 text-danger px-3 py-2';
            strengthBar.style.background = 'linear-gradient(90deg, #f72585, #ff5e8a)';
        } else if (strength < 75) {
            strengthBadge.innerHTML = '<i class="fas fa-chart-line me-1"></i>Media';
            strengthBadge.className = 'badge bg-warning bg-opacity-10 text-warning px-3 py-2';
            strengthBar.style.background = 'linear-gradient(90deg, #ffc107, #ffdb6e)';
        } else {
            strengthBadge.innerHTML = '<i class="fas fa-shield-alt me-1"></i>Fuerte';
            strengthBadge.className = 'badge bg-success bg-opacity-10 text-success px-3 py-2';
            strengthBar.style.background = 'linear-gradient(90deg, #4cc9f0, #4361ee)';
        }
        
        checkPasswordMatch();
    }
    
    function checkPasswordMatch() {
        const pass = password.value;
        const conf = confirm.value;
        
        if (conf === '') {
            matchDiv.innerHTML = '';
            return;
        }
        
        if (pass === conf) {
            matchDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Las contraseñas coinciden</span>';
            document.getElementById('resetButton').disabled = false;
        } else {
            matchDiv.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Las contraseñas no coinciden</span>';
            document.getElementById('resetButton').disabled = true;
        }
    }
    
    // Manejo de envío del formulario
    const form = document.getElementById('resetForm');
    const resetButton = document.getElementById('resetButton');
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Verificar que las contraseñas coincidan
        if (password.value !== confirm.value) {
            return;
        }
        
        // Verificar que la contraseña cumpla requisitos
        const checks = [
            password.value.length >= 8,
            /[A-Z]/.test(password.value),
            /[a-z]/.test(password.value),
            /[0-9]/.test(password.value)
        ];
        
        if (checks.every(Boolean)) {
            // Mostrar estado de carga
            resetButton.classList.add('loading');
            resetButton.disabled = true;
            resetButton.querySelector('.spinner-border').classList.remove('d-none');
            
            // Enviar formulario
            form.submit();
        }
    });
});
</script>
@endsection