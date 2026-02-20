@extends('layouts.app')

@section('content')
<section class="password-change-section d-flex align-items-center justify-content-center min-vh-100">
    <!-- Fondo con elementos decorativos -->
    <div class="password-background">
        <div class="decorative-shape shape-1"></div>
        <div class="decorative-shape shape-2"></div>
        <div class="decorative-shape shape-3"></div>
    </div>
    
    <!-- Contenedor principal -->
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <!-- Tarjeta de cambio de contraseña -->
                <div class="card password-card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header-gradient p-4">
                        <div class="text-center">
                            <!-- Logo mejorado -->
                            <div class="logo-container mb-3 mx-auto">
                                <div class="logo-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-shield-alt logo-main-icon"></i>
                                </div>
                                <div class="logo-pulse"></div>
                            </div>
                            <h1 class="brand-name text-white fw-bold mb-1">CAMBIAR CONTRASEÑA</h1>
                            <p class="brand-subtitle text-white-80 mb-0">Actualiza tus credenciales de seguridad</p>
                        </div>
                    </div>
                    
                    <!-- Cuerpo del formulario -->
                    <div class="card-body p-4 p-xl-5">
                        <!-- Mensajes de estado -->
                        @if(session('success'))
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

                        @if(session('error'))
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

                        <!-- Indicador de seguridad actual -->
                        <div class="security-status mb-4 p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center">
                                <div class="status-icon me-3">
                                    <i class="fas fa-shield-alt text-primary fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">Estado de seguridad actual</h6>
                                    <p class="small text-muted mb-0">
                                        Último cambio: 
                                        @if(auth()->user()->updated_at)
                                            <span class="fw-semibold text-primary">{{ auth()->user()->updated_at->diffForHumans() }}</span>
                                        @else
                                            <span class="fw-semibold text-warning">Nunca</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="security-badge">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Seguro
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- FORMULARIO -->
                        <form method="POST" action="{{ route('perfil.actualizar-password') }}" id="passwordForm" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Columna izquierda -->
                                <div class="col-lg-6">
                                    <!-- Contraseña Actual -->
                                    <div class="form-group mb-4">
                                        <label for="current_password" class="form-label text-dark fw-semibold mb-2">
                                            <i class="fas fa-lock me-2 text-primary"></i>
                                            Contraseña Actual
                                        </label>
                                        <div class="input-group input-group-lg shadow-sm">
                                            <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                                <i class="fas fa-key text-muted"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control border-start-0 rounded-end-3 py-3 @error('current_password') is-invalid @enderror" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   placeholder="Ingresa tu contraseña actual" 
                                                   required>
                                            <button class="btn btn-outline-secondary border-start-0 rounded-end-3 py-3 toggle-password" type="button" data-target="current_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="error-message mt-2 d-flex align-items-center">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                <span class="small">{{ translatePasswordError($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Generar contraseña -->
                                    <div class="generate-password-section mb-4">
                                        <button type="button" class="btn btn-outline-primary w-100 py-2" id="generatePasswordBtn">
                                            <i class="fas fa-dice me-2"></i>
                                            Generar contraseña segura
                                        </button>
                                    </div>

                                    <!-- Botón copiar (oculto inicialmente) -->
                                    <div id="copyPasswordContainer" class="mb-4 d-none">
                                        <button type="button" class="btn btn-outline-success w-100 py-2" id="copyPasswordBtn">
                                            <i class="fas fa-copy me-2"></i>
                                            Copiar contraseña generada
                                        </button>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-lg-6">
                                    <!-- Medidor de seguridad -->
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
                                        
                                        <!-- Puntuación -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Fortaleza</small>
                                            <small class="fw-semibold" id="strengthScore">0/100</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nueva Contraseña y Confirmación en grid -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="new_password" class="form-label text-dark fw-semibold mb-2">
                                        <i class="fas fa-key me-2 text-primary"></i>
                                        Nueva Contraseña
                                    </label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control border-start-0 rounded-end-3 py-3 @error('new_password') is-invalid @enderror" 
                                               id="new_password" 
                                               name="new_password" 
                                               placeholder="Crea una nueva contraseña" 
                                               required>
                                        <button class="btn btn-outline-secondary border-start-0 rounded-end-3 py-3 toggle-password" type="button" data-target="new_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <div class="error-message mt-2 d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <span class="small">{{ translatePasswordError($message) }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="new_password_confirmation" class="form-label text-dark fw-semibold mb-2">
                                        <i class="fas fa-check-circle me-2 text-primary"></i>
                                        Confirmar Contraseña
                                    </label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3 py-3">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control border-start-0 rounded-end-3 py-3" 
                                               id="new_password_confirmation" 
                                               name="new_password_confirmation" 
                                               placeholder="Repite la nueva contraseña" 
                                               required>
                                        <button class="btn btn-outline-secondary border-start-0 rounded-end-3 py-3 toggle-password" type="button" data-target="new_password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="passwordMatch" class="mt-2 small"></div>
                                </div>
                            </div>

                            <!-- Requisitos de seguridad -->
                            <div class="requirements-box p-3 bg-light rounded-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="small fw-semibold text-dark mb-0">
                                        <i class="fas fa-list-check me-2 text-primary"></i>
                                        Requisitos de seguridad:
                                    </p>
                                    <span class="badge bg-white text-dark border" id="requirementsCount">0/5</span>
                                </div>
                                <div class="row g-2" id="requirementsList">
                                    <!-- Requisitos generados dinámicamente -->
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <a href="{{ route('perfil.edit') }}" class="btn btn-outline-secondary btn-lg w-100 py-3 rounded-3">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Cancelar
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" 
                                            class="btn btn-primary btn-lg w-100 py-3 rounded-3 reset-btn shadow-sm"
                                            id="submitBtn" 
                                            disabled>
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                        <span class="button-text">
                                            <i class="fas fa-save me-2"></i>Actualizar Contraseña
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
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
                                    <i class="fas fa-clock me-1"></i>
                                    Última actualización: 
                                    <span class="fw-semibold">{{ auth()->user()->updated_at ? auth()->user()->updated_at->format('d/m/Y') : 'Nunca' }}</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estilos -->
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

.password-change-section {
    position: relative;
    min-height: 100vh;
    padding: 30px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    overflow: hidden;
}

.password-background {
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

.password-card {
    position: relative;
    z-index: 10;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
    width: 100%;
}

.password-card:hover {
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
    font-size: 2rem;
    letter-spacing: -0.5px;
}

/* Security Status */
.security-status {
    border-left: 4px solid var(--primary-color);
    background: white !important;
}

.status-icon {
    width: 48px;
    height: 48px;
    background: rgba(67, 97, 238, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
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

.alert-danger {
    background-color: rgba(247, 37, 133, 0.1);
    border-left-color: var(--danger-color);
    color: #721c24;
}

.alert-icon {
    font-size: 1.25rem;
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

/* Requirements */
.requirements-box {
    border: 1px solid #e9ecef;
    background: white !important;
}

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

/* Generate Password Button */
.generate-password-section .btn {
    border: 2px dashed #e9ecef;
    transition: var(--transition);
}

.generate-password-section .btn:hover {
    border-color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
    transform: translateY(-2px);
}

.generate-password-section .btn i {
    transition: transform 0.3s ease;
}

.generate-password-section .btn:hover i {
    transform: rotate(180deg);
}

/* Security Tips */
.security-tips {
    border: 1px solid rgba(67, 97, 238, 0.2);
}

.security-tips ul {
    list-style-type: none;
    padding-left: 0;
}

.security-tips li {
    padding-left: 0;
    margin-bottom: 0.5rem;
}

/* Suggestions */
.suggestions-box {
    padding: 1rem;
    background: white;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.suggestions-box .btn-outline-primary {
    border: 1px solid #e9ecef;
    color: var(--secondary-color);
    font-size: 0.85rem;
    transition: var(--transition);
}

.suggestions-box .btn-outline-primary:hover {
    border-color: var(--primary-color);
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
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

.reset-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #3a56d4 0%, #2a0a91 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3) !important;
}

.reset-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

.reset-btn:hover:not(:disabled)::after {
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

/* Error message */
.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    padding: 0.5rem;
    background: rgba(247, 37, 133, 0.05);
    border-radius: 0.5rem;
    border-left: 3px solid var(--danger-color);
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

/* Responsive */
@media (max-width: 768px) {
    .password-change-section {
        padding: 15px;
    }
    
    .card-header-gradient {
        padding: 1.5rem !important;
    }
    
    .brand-name {
        font-size: 1.5rem;
    }
    
    .logo-container {
        width: 80px;
        height: 80px;
    }
    
    .logo-main-icon {
        font-size: 2rem;
    }
    
    .shape-1, .shape-2, .shape-3 {
        display: none;
    }
    
    .security-status {
        flex-direction: column;
        text-align: center;
    }
    
    .security-status .status-icon {
        margin-bottom: 1rem;
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

.password-card {
    animation: fadeInUp 0.6s ease-out;
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
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de requisitos
    const config = {
        requirements: [
            { id: 'length', label: 'Mínimo 8 caracteres', regex: /.{8,}/ },
            { id: 'uppercase', label: 'Una letra mayúscula', regex: /[A-Z]/ },
            { id: 'lowercase', label: 'Una letra minúscula', regex: /[a-z]/ },
            { id: 'number', label: 'Un número', regex: /[0-9]/ },
            { id: 'special', label: 'Un carácter especial', regex: /[!@#$%^&*(),.?":{}|<>]/ }
        ]
    };

    // Elementos DOM
    const elements = {
        currentPassword: document.getElementById('current_password'),
        newPassword: document.getElementById('new_password'),
        confirmPassword: document.getElementById('new_password_confirmation'),
        submitBtn: document.getElementById('submitBtn'),
        strengthBar: document.getElementById('strengthBar'),
        strengthBadge: document.getElementById('strengthBadge'),
        strengthScore: document.getElementById('strengthScore'),
        matchIndicator: document.getElementById('passwordMatch'),
        requirementsList: document.getElementById('requirementsList'),
        requirementsCount: document.getElementById('requirementsCount'),
        copyContainer: document.getElementById('copyPasswordContainer'),
        copyBtn: document.getElementById('copyPasswordBtn'),
        generateBtn: document.getElementById('generatePasswordBtn')
    };

    // Estado
    const state = {
        score: 0,
        metRequirements: 0
    };

    // Inicializar requisitos
    function initRequirements() {
        elements.requirementsList.innerHTML = config.requirements.map(req => `
            <div class="col-6">
                <div class="requirement-item" data-id="${req.id}">
                    <i class="fas fa-circle-check"></i>
                    <span class="small">${req.label}</span>
                </div>
            </div>
        `).join('');
    }

    // Toggle de contraseñas
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Generar contraseña
    elements.generateBtn.addEventListener('click', generateRandomPassword);

    function generateRandomPassword() {
        const length = 12;
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';
        const symbols = '!@#$%^&*';
        
        let password = '';
        
        // Asegurar al menos uno de cada tipo
        password += uppercase[Math.floor(Math.random() * uppercase.length)];
        password += lowercase[Math.floor(Math.random() * lowercase.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];
        
        // Completar
        const allChars = uppercase + lowercase + numbers + symbols;
        while (password.length < length) {
            password += allChars[Math.floor(Math.random() * allChars.length)];
        }
        
        // Mezclar
        password = password.split('').sort(() => Math.random() - 0.5).join('');
        
        elements.newPassword.value = password;
        elements.confirmPassword.value = password;
        
        elements.copyContainer.classList.remove('d-none');
        
        // Disparar eventos
        elements.newPassword.dispatchEvent(new Event('input'));
        elements.confirmPassword.dispatchEvent(new Event('input'));
        
        // Feedback
        showFeedback('¡Contraseña generada!', 'success');
    }

    // Copiar contraseña
    elements.copyBtn.addEventListener('click', function() {
        elements.newPassword.select();
        document.execCommand('copy');
        
        this.innerHTML = '<i class="fas fa-check me-2"></i>¡Copiada!';
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-copy me-2"></i>Copiar contraseña';
        }, 2000);
        
        window.getSelection().removeAllRanges();
    });

    // Analizar contraseña
    elements.newPassword.addEventListener('input', analyzePassword);
    elements.confirmPassword.addEventListener('input', checkMatch);
    elements.currentPassword.addEventListener('input', validateForm);

    function analyzePassword() {
        const password = elements.newPassword.value;
        
        // Calcular score
        let score = 0;
        score += Math.min(password.length * 3, 30);
        
        const checks = [
            /[A-Z]/.test(password),
            /[a-z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];
        
        score += checks.filter(Boolean).length * 17.5;
        state.score = Math.min(100, Math.round(score));
        
        // Actualizar requisitos
        state.metRequirements = 0;
        config.requirements.forEach(req => {
            const isMet = req.regex.test(password);
            const item = document.querySelector(`.requirement-item[data-id="${req.id}"]`);
            
            if (item) {
                if (isMet) {
                    item.classList.add('valid');
                    state.metRequirements++;
                } else {
                    item.classList.remove('valid');
                }
            }
        });
        
        // Actualizar contador
        elements.requirementsCount.textContent = `${state.metRequirements}/${config.requirements.length}`;
        elements.requirementsCount.className = state.metRequirements === config.requirements.length 
            ? 'badge bg-success text-white' 
            : 'badge bg-white text-dark border';
        
        // Actualizar barra
        elements.strengthBar.style.width = state.score + '%';
        elements.strengthScore.textContent = state.score + '/100';
        
        // Actualizar badge
        if (state.score < 40) {
            elements.strengthBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Débil';
            elements.strengthBadge.className = 'badge bg-danger bg-opacity-10 text-danger px-3 py-2';
        } else if (state.score < 70) {
            elements.strengthBadge.innerHTML = '<i class="fas fa-chart-line me-1"></i>Media';
            elements.strengthBadge.className = 'badge bg-warning bg-opacity-10 text-warning px-3 py-2';
        } else {
            elements.strengthBadge.innerHTML = '<i class="fas fa-shield-alt me-1"></i>Fuerte';
            elements.strengthBadge.className = 'badge bg-success bg-opacity-10 text-success px-3 py-2';
        }
        
        checkMatch();
        validateForm();
    }

    function checkMatch() {
        const pass = elements.newPassword.value;
        const conf = elements.confirmPassword.value;
        
        if (conf === '') {
            elements.matchIndicator.innerHTML = '';
            return;
        }
        
        if (pass === conf) {
            elements.matchIndicator.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Las contraseñas coinciden</span>';
        } else {
            elements.matchIndicator.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Las contraseñas no coinciden</span>';
        }
        
        validateForm();
    }

    function validateForm() {
        const currentValid = elements.currentPassword.value.length >= 6;
        const passwordValid = state.score >= 70;
        const passwordsMatch = elements.newPassword.value === elements.confirmPassword.value;
        const allRequirementsMet = state.metRequirements === config.requirements.length;
        
        elements.submitBtn.disabled = !(currentValid && passwordValid && passwordsMatch && allRequirementsMet);
    }

    function showFeedback(message, type) {
        const feedback = document.createElement('div');
        feedback.className = `alert alert-${type} alert-elegant position-fixed top-0 end-0 m-3`;
        feedback.style.zIndex = '9999';
        feedback.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(feedback);
        
        setTimeout(() => {
            feedback.style.transition = 'opacity 0.5s';
            feedback.style.opacity = '0';
            setTimeout(() => feedback.remove(), 500);
        }, 2000);
    }

    // Inicializar
    initRequirements();
    analyzePassword();
});
</script>

@php
function translatePasswordError($message) {
    $translations = [
        'The current password field is required.' => 'La contraseña actual es obligatoria.',
        'The current password field must be at least 6 characters.' => 'La contraseña actual debe tener al menos 6 caracteres.',
        'The new password field is required.' => 'La nueva contraseña es obligatoria.',
        'The new password field must be at least 8 characters.' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        'The new password confirmation field is required.' => 'La confirmación de contraseña es obligatoria.',
        'The new password confirmation field must match new password.' => 'La confirmación de contraseña no coincide.',
        'The password must be at least 8 characters.' => 'La contraseña debe tener al menos 8 caracteres.',
        'The password must contain at least one uppercase and one lowercase letter.' => 'La contraseña debe contener al menos una letra mayúscula y una minúscula.',
        'The password must contain at least one number.' => 'La contraseña debe contener al menos un número.',
        'The password must contain at least one symbol.' => 'La contraseña debe contener al menos un símbolo especial.',
        'The current password is incorrect.' => 'La contraseña actual es incorrecta.',
        'Las contraseñas no coinciden' => 'Las contraseñas no coinciden'
    ];
    
    return $translations[$message] ?? $message;
}
@endphp
@endsection