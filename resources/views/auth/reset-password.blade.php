@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Header con gradiente -->
            <div class="text-center mb-5">
                <div class="d-flex justify-content-center mb-3">
                    <div class="password-icon-container">
                        <i class="fas fa-lock password-icon"></i>
                        <div class="password-icon-shadow"></div>
                    </div>
                </div>
                <h1 class="display-6 fw-bold text-success mb-2">Nueva Contraseña</h1>
                <p class="text-muted">Crea una nueva contraseña segura para tu cuenta</p>
            </div>

            <div class="card shadow-lg border-0">
                <!-- Card header con gradiente -->
                <div class="card-header bg-gradient-success text-white py-4 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-white rounded-circle p-2 me-3">
                            <i class="fas fa-user-check text-success fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">¡Código Verificado!</h5>
                            <p class="mb-0 opacity-75">Ahora crea tu nueva contraseña</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-3 fs-5"></i>
                            <div class="flex-grow-1">{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fs-5"></i>
                                <div>
                                    <h6 class="mb-1">Error de validación</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li class="small">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Indicador de fortaleza de contraseña -->
                    <div class="password-strength mb-4" id="passwordStrength">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Fortaleza de la contraseña:</span>
                            <span class="small" id="strengthText">Débil</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <div class="form-text mt-2" id="passwordCriteria">
                            <i class="fas fa-info-circle me-1"></i>
                            La contraseña debe tener mínimo 8 caracteres
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- Campo de nueva contraseña -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-key me-2"></i>Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control password-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       minlength="8"
                                       placeholder="Ingresa tu nueva contraseña"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Requisitos de contraseña -->
                            <div class="mt-3 p-3 bg-light rounded">
                                <p class="small fw-semibold mb-2"><i class="fas fa-list-check me-2"></i>Requisitos:</p>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-1"><i class="fas fa-check-circle text-success me-2 requirement" data-requirement="length"></i>Mínimo 8 caracteres</li>
                                    <li class="mb-1"><i class="fas fa-check-circle text-success me-2 requirement" data-requirement="uppercase"></i>Al menos una mayúscula</li>
                                    <li class="mb-1"><i class="fas fa-check-circle text-success me-2 requirement" data-requirement="lowercase"></i>Al menos una minúscula</li>
                                    <li class="mb-1"><i class="fas fa-check-circle text-success me-2 requirement" data-requirement="number"></i>Al menos un número</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Campo de confirmación -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-key me-2"></i>Confirmar Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control password-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Repite la contraseña"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2" id="passwordMatch">
                                <!-- Aquí se mostrará el mensaje de coincidencia -->
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="d-grid mt-4 pt-3">
                            <button type="submit" class="btn btn-success btn-lg btn-gradient py-3 fw-semibold" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span class="submit-text">Guardar Nueva Contraseña</span>
                                <span class="spinner-border spinner-border-sm ms-2 d-none" id="submitSpinner"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer de la card -->
                <div class="card-footer bg-transparent border-0 py-4">
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-success d-inline-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver al Inicio de Sesión
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="text-center mt-4">
                <p class="small text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Tu contraseña se almacena de forma segura y encriptada
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados */
    .password-icon-container {
        position: relative;
        width: 80px;
        height: 80px;
    }
    
    .password-icon {
        font-size: 2.5rem;
        color: #28a745;
        position: relative;
        z-index: 2;
    }
    
    .password-icon-shadow {
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(40, 167, 69, 0.05));
        border-radius: 50%;
        top: 0;
        left: 0;
        z-index: 1;
        animation: pulse 2s infinite;
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
    }
    
    .btn-gradient {
        background: linear-gradient(to right, #28a745, #20c997);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    .password-input {
        padding-right: 50px;
        border-right: none;
    }
    
    .password-input:focus {
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
        border-color: #28a745;
    }
    
    .toggle-password {
        border-left: none;
        transition: all 0.2s;
    }
    
    .toggle-password:hover {
        background-color: #f8f9fa;
        color: #28a745;
    }
    
    .icon-wrapper {
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .progress-bar {
        transition: width 0.5s ease, background-color 0.5s ease;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.7; }
        50% { transform: scale(1.05); opacity: 0.9; }
        100% { transform: scale(1); opacity: 0.7; }
    }
    
    /* Estilos para los requisitos */
    .requirement.valid {
        color: #28a745;
    }
    
    .requirement.invalid {
        color: #dc3545;
    }
    
    /* Animación para el check */
    .fa-check-circle {
        transition: color 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const passwordCriteria = document.getElementById('passwordCriteria');
        const passwordMatch = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('submitBtn');
        const passwordForm = document.getElementById('passwordForm');
        const requirements = document.querySelectorAll('.requirement');
        const submitSpinner = document.getElementById('submitSpinner');
        
        // Botones para mostrar/ocultar contraseña
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.getElementById(target);
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
        
        // Validar fortaleza de contraseña en tiempo real
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let criteria = [];
            
            // Verificar longitud
            if (password.length >= 8) {
                strength += 25;
                updateRequirement('length', true);
            } else {
                updateRequirement('length', false);
            }
            
            // Verificar mayúsculas
            if (/[A-Z]/.test(password)) {
                strength += 25;
                updateRequirement('uppercase', true);
            } else {
                updateRequirement('uppercase', false);
            }
            
            // Verificar minúsculas
            if (/[a-z]/.test(password)) {
                strength += 25;
                updateRequirement('lowercase', true);
            } else {
                updateRequirement('lowercase', false);
            }
            
            // Verificar números
            if (/[0-9]/.test(password)) {
                strength += 25;
                updateRequirement('number', true);
            } else {
                updateRequirement('number', false);
            }
            
            // Actualizar barra de fortaleza
            strengthBar.style.width = strength + '%';
            
            // Cambiar color según fortaleza
            if (strength < 50) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Débil';
                strengthText.className = 'small text-danger';
            } else if (strength < 75) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Media';
                strengthText.className = 'small text-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Fuerte';
                strengthText.className = 'small text-success';
            }
            
            // Verificar coincidencia de contraseñas
            checkPasswordMatch();
        });
        
        // Verificar coincidencia de contraseñas
        confirmInput.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            
            if (confirm === '') {
                passwordMatch.innerHTML = '';
                return;
            }
            
            if (password === confirm) {
                passwordMatch.innerHTML = '<div class="text-success small"><i class="fas fa-check-circle me-1"></i>Las contraseñas coinciden</div>';
                submitBtn.disabled = false;
            } else {
                passwordMatch.innerHTML = '<div class="text-danger small"><i class="fas fa-times-circle me-1"></i>Las contraseñas no coinciden</div>';
                submitBtn.disabled = true;
            }
        }
        
        // Actualizar iconos de requisitos
        function updateRequirement(type, isValid) {
            requirements.forEach(req => {
                if (req.getAttribute('data-requirement') === type) {
                    if (isValid) {
                        req.classList.remove('text-success', 'text-danger');
                        req.classList.add('text-success');
                        req.classList.remove('fa-check-circle', 'fa-times-circle');
                        req.classList.add('fa-check-circle');
                    } else {
                        req.classList.remove('text-success', 'text-danger');
                        req.classList.add('text-danger');
                        req.classList.remove('fa-check-circle', 'fa-times-circle');
                        req.classList.add('fa-times-circle');
                    }
                }
            });
        }
        
        // Mostrar spinner al enviar el formulario
        passwordForm.addEventListener('submit', function() {
            submitSpinner.classList.remove('d-none');
            submitBtn.disabled = true;
            submitBtn.querySelector('.submit-text').textContent = 'Guardando...';
        });
        
        // Inicializar verificación de coincidencia
        checkPasswordMatch();
    });
</script>
@endsection