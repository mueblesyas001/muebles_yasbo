@extends('layouts.app')

@section('content')
<section class="profile-section py-5">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xxl-11">
                <!-- Tarjeta principal con diseño limpio -->
                <div class="card profile-card border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    <!-- Header minimalista -->
                    <div class="card-header bg-white border-0 pt-5 pb-4 px-5">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="header-icon-wrapper">
                                        <i class="fas fa-pen-fancy fs-4 text-primary"></i>
                                    </div>
                                    <h1 class="display-6 fw-bold text-dark mb-0">Configuración de Perfil</h1>
                                </div>
                                <p class="text-secondary ms-1 mb-0">
                                    <i class="fas fa-circle me-2 small text-primary"></i>
                                    Actualiza tu información personal y de contacto
                                </p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                <span>Volver al Dashboard</span>
                            </a>
                        </div>
                    </div>

                    <!-- Cuerpo principal -->
                    <div class="card-body p-5 pt-0">
                        
                        <!-- Sistema de notificaciones -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white text-success rounded-circle p-2 me-3">
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-semibold">{{ session('success') }}</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white text-danger rounded-circle p-2 me-3">
                                        <i class="fas fa-exclamation-circle fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="fw-semibold mb-1">Errores en el formulario</p>
                                        <ul class="mb-0 small ps-3">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <!-- Grid principal -->
                        <div class="row g-4">
                            
                            <!-- Columna izquierda - Perfil del usuario -->
                            <div class="col-lg-5">
                                <div class="profile-sidebar h-100">
                                    
                                    <!-- Tarjeta de perfil -->
                                    <div class="card border-0 bg-light rounded-4 mb-4 overflow-hidden">
                                        <div class="bg-primary bg-gradient p-4" style="height: 80px;"></div>
                                        <div class="card-body text-center px-4 pb-4" style="margin-top: -40px;">
                                            <!-- Avatar -->
                                            <div class="position-relative d-inline-block">
                                                <div class="bg-white rounded-circle p-1 shadow-sm">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                        <span class="text-white fw-bold fs-2">
                                                            {{ strtoupper(substr($user->empleado->nombre ?? $user->nombre ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-2"></span>
                                            </div>
                                            
                                            <h4 class="fw-bold text-dark mt-3 mb-1">{{ $user->empleado->nombre ?? $user->nombre ?? 'Usuario' }}</h4>
                                            <p class="text-muted small mb-3">{{ $user->correo }}</p>
                                            
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                                <i class="fas fa-user-tag me-2"></i>
                                                {{ $user->rol }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Tarjetas de información -->
                                    <div class="vstack gap-3">
                                        <!-- Contacto -->
                                        <div class="card border-0 bg-light rounded-4">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                    <div class="bg-primary bg-opacity-10 rounded-2 p-2">
                                                        <i class="fas fa-envelope text-primary"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-0">Información de Contacto</h6>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <small class="text-secondary d-block mb-1">Correo electrónico</small>
                                                    <p class="fw-semibold text-dark mb-0">{{ $user->correo }}</p>
                                                </div>
                                                
                                                @if($user->empleado && $user->empleado->nombre)
                                                <div>
                                                    <small class="text-secondary d-block mb-1">Nombre completo</small>
                                                    <p class="fw-semibold text-dark mb-0">{{ $user->empleado->nombre }}</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Consejos de seguridad -->
                                        <div class="card border-0 bg-light rounded-4">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <div class="bg-success bg-opacity-10 rounded-2 p-2">
                                                        <i class="fas fa-shield-alt text-success"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-0">Consejos de Seguridad</h6>
                                                </div>
                                                
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-flex align-items-start gap-2 mb-2">
                                                        <i class="fas fa-check-circle text-success mt-1"></i>
                                                        <span class="small">Usa un correo que revises frecuentemente</span>
                                                    </li>
                                                    <li class="d-flex align-items-start gap-2 mb-2">
                                                        <i class="fas fa-check-circle text-success mt-1"></i>
                                                        <span class="small">Mantén tu información siempre actualizada</span>
                                                    </li>
                                                    <li class="d-flex align-items-start gap-2">
                                                        <i class="fas fa-check-circle text-success mt-1"></i>
                                                        <span class="small">Verifica tu nuevo correo inmediatamente</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha - Formulario -->
                            <div class="col-lg-7">
                                <div class="card border-0 bg-light rounded-4">
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <h3 class="fw-bold text-dark mb-2">Actualizar correo electrónico</h3>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                                Modifica tu dirección de correo para mantener tu cuenta segura y actualizada
                                            </p>
                                        </div>

                                        <form method="POST" action="{{ route('perfil.update') }}" id="profileForm">
                                            @csrf
                                            @method('PUT')

                                            <!-- Email actual -->
                                            <div class="bg-white rounded-3 p-3 mb-4 border">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-3">
                                                        <i class="fas fa-envelope-open-text text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <small class="text-secondary d-block">Correo actual</small>
                                                        <span class="fw-semibold text-dark">{{ $user->correo }}</span>
                                                    </div>
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                                        <i class="fas fa-check-circle me-1"></i>Verificado
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Campos del formulario -->
                                            <div class="vstack gap-4">
                                                <!-- Nuevo correo -->
                                                <div>
                                                    <label for="correo" class="form-label fw-semibold text-dark mb-2">
                                                        <i class="fas fa-envelope me-2 text-primary"></i>
                                                        Nuevo correo electrónico
                                                    </label>
                                                    <input type="email" 
                                                           class="form-control form-control-lg rounded-3 border-0 bg-white @error('correo') is-invalid @enderror" 
                                                           id="correo" 
                                                           name="correo" 
                                                           value="{{ old('correo', $user->correo) }}"
                                                           placeholder="ejemplo@correo.com"
                                                           required>
                                                    @error('correo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-secondary mt-2 d-block">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Ingresa el correo que usarás para iniciar sesión
                                                    </small>
                                                </div>

                                                <!-- Confirmar correo -->
                                                <div>
                                                    <label for="correo_confirmation" class="form-label fw-semibold text-dark mb-2">
                                                        <i class="fas fa-check-double me-2 text-primary"></i>
                                                        Confirmar nuevo correo
                                                    </label>
                                                    <input type="email" 
                                                           class="form-control form-control-lg rounded-3 border-0 bg-white @error('correo_confirmation') is-invalid @enderror" 
                                                           id="correo_confirmation" 
                                                           name="correo_confirmation" 
                                                           placeholder="ejemplo@correo.com"
                                                           required>
                                                    
                                                    <!-- Indicador de coincidencia -->
                                                    <div class="mt-2" id="emailMatchMessage"></div>
                                                </div>

                                                <!-- Alerta importante -->
                                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 border border-warning border-opacity-25">
                                                    <div class="d-flex">
                                                        <div class="text-warning me-3">
                                                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold text-dark mb-2">Importante</h6>
                                                            <ul class="small text-secondary mb-0 ps-3">
                                                                <li class="mb-1">Después de actualizar, deberás usar el nuevo correo para iniciar sesión</li>
                                                                <li class="mb-1">Recibirás un correo de confirmación en la nueva dirección</li>
                                                                <li>Revisa tu carpeta de spam si no recibes el correo</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Acciones del formulario -->
                                            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mt-4 pt-4 border-top">
                                                <a href="{{ route('perfil.cambiar-password') }}" class="btn btn-outline-primary rounded-pill px-4">
                                                    <i class="fas fa-key me-2"></i>
                                                    Cambiar Contraseña
                                                </a>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4">
                                                        Cancelar
                                                    </a>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-5" id="saveButton">
                                                        <span class="spinner-border spinner-border-sm d-none" id="submitSpinner"></span>
                                                        <span id="submitText">
                                                            <i class="fas fa-save me-2"></i>
                                                            Guardar Cambios
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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

<style>
:root {
    --primary: #4361ee;
    --primary-light: #eef2ff;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
}

/* Background de la sección - BLANCO */
.profile-section {
    background-color: #f8fafc;
    min-height: calc(100vh - 76px);
}

/* Tarjeta principal */
.profile-card {
    background: white;
}

/* Header Icon */
.header-icon-wrapper {
    width: 48px;
    height: 48px;
    background: var(--primary-light);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Inputs */
.form-control {
    border: 1px solid #e9ecef;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

/* Badges */
.badge {
    font-weight: 500;
}

/* Cards */
.card {
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Buttons */
.btn {
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: var(--primary);
    border: none;
}

.btn-primary:hover {
    background: #3651d4;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
}

.btn-outline-primary {
    border: 1px solid var(--primary);
    color: var(--primary);
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-1px);
}

.btn-light {
    background: white;
    border: 1px solid #e9ecef;
    color: #495057;
}

.btn-light:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex.flex-wrap.gap-3.justify-content-between {
        flex-direction: column;
    }
    
    .d-flex.flex-wrap.gap-3.justify-content-between > * {
        width: 100%;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profileForm');
    const emailInput = document.getElementById('correo');
    const confirmInput = document.getElementById('correo_confirmation');
    const matchMessage = document.getElementById('emailMatchMessage');
    const saveButton = document.getElementById('saveButton');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitText = document.getElementById('submitText');

    // Validación en tiempo real
    emailInput.addEventListener('input', validateEmails);
    confirmInput.addEventListener('input', validateEmails);

    function validateEmails() {
        const email = emailInput.value;
        const confirm = confirmInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validar formato del email
        if (email && !emailRegex.test(email)) {
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
        } else if (email) {
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
        } else {
            emailInput.classList.remove('is-valid', 'is-invalid');
        }

        // Validar coincidencia
        if (confirm) {
            if (email !== confirm) {
                confirmInput.classList.add('is-invalid');
                confirmInput.classList.remove('is-valid');
                matchMessage.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle me-1"></i>Los correos no coinciden</small>';
            } else {
                confirmInput.classList.remove('is-invalid');
                confirmInput.classList.add('is-valid');
                matchMessage.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Los correos coinciden</small>';
            }
        } else {
            confirmInput.classList.remove('is-valid', 'is-invalid');
            matchMessage.innerHTML = '';
        }
    }

    // Manejo del envío
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Validar antes de enviar
        if (!emailInput.value || !confirmInput.value) {
            alert('Por favor, completa todos los campos');
            return;
        }

        if (emailInput.value !== confirmInput.value) {
            alert('Los correos no coinciden');
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            alert('Por favor, ingresa un correo electrónico válido');
            return;
        }

        // Mostrar loading
        submitSpinner.classList.remove('d-none');
        submitText.innerHTML = 'Guardando...';
        saveButton.disabled = true;

        // Enviar formulario
        form.submit();
    });
});
</script>
@endsection