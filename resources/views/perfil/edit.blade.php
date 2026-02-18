@extends('layouts.app')

@section('content')
<section class="profile-section py-5">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row justify-content-center">
            <!-- Tarjeta principal - EXTRA ANCHA -->
            <div class="col-12 col-xxl-11">
                <div class="card profile-card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Encabezado con gradiente -->
                    <div class="card-header-gradient py-4 px-5 px-lg-6">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <h1 class="h3 text-white fw-bold mb-1">
                                    <i class="fas fa-user-cog me-2"></i>Configuración de Perfil
                                </h1>
                                <p class="text-white-80 mb-0">Actualiza tu información personal y de contacto</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Cuerpo de la tarjeta -->
                    <div class="card-body p-5 p-lg-5">
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

                        @if($errors->any())
                            <div class="alert alert-danger alert-elegant alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-title mb-1">Error en el formulario</h6>
                                        <ul class="mb-0 ps-3">
                                            @foreach($errors->all() as $error)
                                                <li class="small">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <div class="row g-4">
                            <!-- Información del usuario - COLUMNA MÁS ANCHA -->
                            <div class="col-lg-5">
                                <div class="user-info-card h-100 p-4 rounded-4 border shadow-sm">
                                    <div class="text-center mb-4">
                                        <div class="avatar-circle mx-auto mb-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1">{{ $user->empleado->nombre ?? $user->nombre ?? 'Usuario' }}</h4>
                                        <p class="text-muted mb-2">{{ $user->correo }}</p>
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            <i class="fas fa-user-tag me-1"></i>{{ $user->rol }}
                                        </span>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="user-details mb-4">
                                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                            <i class="fas fa-info-circle me-2"></i>Información de Contacto
                                        </h5>
                                        <div class="detail-item mb-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-envelope text-primary me-2"></i>
                                                <span class="fw-semibold">Correo Actual:</span>
                                            </div>
                                            <p class="text-muted ps-4 mb-0">{{ $user->correo }}</p>
                                        </div>
                                        @if($user->empleado && $user->empleado->nombre)
                                        <div class="detail-item mb-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <span class="fw-semibold">Nombre:</span>
                                            </div>
                                            <p class="text-muted ps-4 mb-0">{{ $user->empleado->nombre }}</p>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Recomendaciones de seguridad -->
                                    <div class="security-tips">
                                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                            <i class="fas fa-shield-alt me-2"></i>Recomendaciones de Seguridad
                                        </h5>
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex align-items-start mb-2">
                                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                                <span>Mantén tu correo electrónico actualizado</span>
                                            </li>
                                            <li class="d-flex align-items-start mb-2">
                                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                                <span>Usa un correo válido y accesible</span>
                                            </li>
                                            <li class="d-flex align-items-start mb-2">
                                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                                <span>Recibirás notificaciones importantes</span>
                                            </li>
                                            <li class="d-flex align-items-start">
                                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                                <span>Verifica tu nuevo correo inmediatamente</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de actualización - COLUMNA MÁS ANCHA -->
                            <div class="col-lg-7">
                                <div class="form-card h-100 p-4 rounded-4 border shadow-sm">
                                    <h4 class="fw-bold text-dark mb-3">
                                        <i class="fas fa-envelope me-2"></i>Actualizar Correo Electrónico
                                    </h4>
                                    
                                    <p class="text-muted mb-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Actualiza tu dirección de correo electrónico para recibir notificaciones importantes y mantener el acceso a tu cuenta.
                                    </p>

                                    <form method="POST" action="{{ route('perfil.update') }}" id="profileForm" class="mt-3">
                                        @csrf
                                        @method('PUT')

                                        <!-- Sección de correo actual -->
                                        <div class="mb-4">
                                            <h5 class="fw-bold text-dark mb-3">
                                                <i class="fas fa-envelope-open me-2"></i>Correo Actual Registrado
                                            </h5>
                                            <div class="current-email-display p-3 bg-light rounded-4 border">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="email-icon me-3">
                                                            <i class="fas fa-at text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold mb-1">Correo Principal</h6>
                                                            <p class="text-primary fw-bold mb-0">{{ $user->correo }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                                        <i class="fas fa-check-circle me-1"></i>Activo
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sección de nuevo correo -->
                                        <div class="mb-4">
                                            <h5 class="fw-bold text-dark mb-3">
                                                <i class="fas fa-pen me-2"></i>Ingresa Tu Nuevo Correo
                                            </h5>
                                            
                                            <!-- Campo de nuevo correo -->
                                            <div class="mb-3">
                                                <label for="correo" class="form-label fw-semibold text-dark mb-2">
                                                    <i class="fas fa-envelope me-2"></i>Nuevo Correo Electrónico *
                                                </label>
                                                <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden">
                                                    <span class="input-group-text bg-white border-end-0 rounded-start-4 px-3">
                                                        <i class="fas fa-at text-primary"></i>
                                                    </span>
                                                    <input type="email" 
                                                           class="form-control border-start-0 rounded-end-4 py-3 @error('correo') is-invalid @enderror" 
                                                           id="correo" 
                                                           name="correo" 
                                                           value="{{ old('correo', $user->correo) }}"
                                                           required
                                                           placeholder="ejemplo@correo.com"
                                                           autofocus>
                                                    <div class="valid-feedback">
                                                        <i class="fas fa-check-circle me-1"></i>¡Correo electrónico válido!
                                                    </div>
                                                </div>
                                                @error('correo')
                                                    <div class="error-message mt-2 p-2">
                                                        <i class="fas fa-exclamation-circle me-2"></i>
                                                        <span>{{ $message }}</span>
                                                    </div>
                                                @enderror
                                                
                                                <div class="mt-2">
                                                    <small class="text-muted d-flex align-items-start">
                                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                                        <span>Ingresa el nuevo correo electrónico que utilizarás para iniciar sesión en el sistema.</span>
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Confirmación del correo -->
                                            <div class="mb-3">
                                                <label for="correo_confirmation" class="form-label fw-semibold text-dark mb-2">
                                                    <i class="fas fa-check-double me-2"></i>Confirmar Nuevo Correo *
                                                </label>
                                                <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden">
                                                    <span class="input-group-text bg-white border-end-0 rounded-start-4 px-3">
                                                        <i class="fas fa-check-circle text-primary"></i>
                                                    </span>
                                                    <input type="email" 
                                                           class="form-control border-start-0 rounded-end-4 py-3 @error('correo_confirmation') is-invalid @enderror" 
                                                           id="correo_confirmation" 
                                                           name="correo_confirmation" 
                                                           placeholder="ejemplo@correo.com"
                                                           required>
                                                    <div class="valid-feedback">
                                                        <i class="fas fa-check-circle me-1"></i>¡Los correos coinciden!
                                                    </div>
                                                </div>
                                                @error('correo_confirmation')
                                                    <div class="error-message mt-2 p-2">
                                                        <i class="fas fa-exclamation-circle me-2"></i>
                                                        <span>{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Advertencia importante -->
                                        <div class="alert alert-warning alert-elegant rounded-3 mb-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="alert-title mb-2">Importante</h5>
                                                    <ul class="mb-0">
                                                        <li class="mb-1">Después de actualizar tu correo, deberás utilizar la nueva dirección para iniciar sesión.</li>
                                                        <li class="mb-1">Te enviaremos un correo de confirmación a la nueva dirección.</li>
                                                        <li>Si no recibes el correo de confirmación, revisa tu carpeta de spam o contacta con soporte.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('perfil.cambiar-password') }}" class="btn btn-outline-primary btn-lg px-4 py-2">
                                                    <i class="fas fa-key me-2"></i>Cambiar Contraseña
                                                </a>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-lg px-4 py-2">
                                                    <i class="fas fa-times me-2"></i>Cancelar
                                                </a>
                                                <button type="submit" class="btn btn-primary btn-lg px-4 py-2" id="saveButton">
                                                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                                                    <span class="button-text">
                                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer de la tarjeta -->
                    <div class="card-footer bg-light border-0 py-3 px-5 px-lg-6">
                        <div class="text-center">
                            <small class="text-muted d-flex align-items-center justify-content-center">
                                <i class="fas fa-shield-alt me-2"></i>
                                <span>Sistema Seguro • © {{ date('Y') }} Muebles Yasbo • v2.0</span>
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
    --primary-light: #eef2ff;
    --success-color: #4cc9f0;
    --warning-color: #ffc107;
    --danger-color: #f72585;
    --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.15);
    --transition: all 0.3s ease;
}

/* SECCIÓN PRINCIPAL */
.profile-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    min-height: calc(100vh - 76px);
}

/* TARJETA PRINCIPAL */
.profile-card {
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    transition: var(--transition);
    max-width: 1800px;
    margin: 0 auto;
}

/* ENCABEZADO CON GRADIENTE */
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

/* AVATAR */
.avatar-circle {
    width: 100px;
    height: 100px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
    border: 4px solid white;
    transition: var(--transition);
}

.avatar-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(67, 97, 238, 0.4);
}

/* INPUTS */
.form-control {
    border: 2px solid #e9ecef;
    font-size: 1rem;
    padding: 0.875rem 1rem;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
}

/* VALIDACIÓN */
.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    padding: 0.5rem;
    background: rgba(247, 37, 133, 0.05);
    border-radius: 0.5rem;
    border-left: 3px solid var(--danger-color);
}

/* BOTONES */
.btn-primary {
    background: var(--gradient-primary);
    border: none;
    font-weight: 600;
    transition: var(--transition);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3a56d4 0%, #2a0a91 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .col-lg-5, .col-lg-7 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<!-- Scripts para validación -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profileForm');
    const emailInput = document.getElementById('correo');
    const confirmInput = document.getElementById('correo_confirmation');
    const saveButton = document.getElementById('saveButton');
    
    // Validación en tiempo real
    emailInput.addEventListener('input', validateEmail);
    confirmInput.addEventListener('input', validateConfirmation);
    
    function validateEmail() {
        const email = emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
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
    
    function validateConfirmation() {
        const email = emailInput.value;
        const confirmation = confirmInput.value;
        
        if (!confirmation) {
            setInvalid(confirmInput, 'Por favor confirma el correo');
            return false;
        } else if (email !== confirmation) {
            setInvalid(confirmInput, 'Los correos no coinciden');
            return false;
        } else {
            setValid(confirmInput);
            return true;
        }
    }
    
    function setInvalid(input, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
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
        
        const errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Manejo del envío del formulario
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const isEmailValid = validateEmail();
        const isConfirmationValid = validateConfirmation();
        
        if (isEmailValid && isConfirmationValid) {
            // Mostrar estado de carga
            const spinner = saveButton.querySelector('.spinner-border');
            const buttonText = saveButton.querySelector('.button-text');
            
            spinner.classList.remove('d-none');
            buttonText.innerHTML = 'Guardando...';
            saveButton.disabled = true;
            
            // Enviar formulario
            form.submit();
        } else {
            // Agitar el formulario para indicar error
            saveButton.style.animation = 'shake 0.5s';
            setTimeout(() => {
                saveButton.style.animation = '';
            }, 500);
            
            // Scroll al primer campo con error
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                firstInvalid.focus();
            }
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
    `;
    document.head.appendChild(style);
});
</script>
@endsection