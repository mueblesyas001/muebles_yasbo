@extends('layouts.app')

@section('content')
<section class="reset-section d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-success text-white text-center py-4 rounded-top-4">
                        <h3 class="mb-0">
                            <i class="fas fa-lock me-2"></i>
                            NUEVA CONTRASEÑA
                        </h3>
                        <small class="opacity-75">Verificación completada ✓</small>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-key fa-3x text-success"></i>
                            </div>
                            <h5 class="fw-bold">Crear nueva contraseña</h5>
                            <p class="text-muted mb-0">
                                Para la cuenta: <strong class="text-success">{{ $email }}</strong>
                            </p>
                        </div>
                        
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <!-- Nueva contraseña -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-key me-2"></i>Nueva Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           id="password"
                                           required
                                           placeholder="Mínimo 8 caracteres">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                
                                <!-- Indicador de fortaleza -->
                                <div class="password-strength mt-2">
                                    <small class="d-block mb-1">Fortaleza de la contraseña:</small>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" id="passwordStrength" style="width: 0%"></div>
                                    </div>
                                    <small id="strengthText" class="text-muted"></small>
                                </div>
                            </div>
                            
                            <!-- Confirmar contraseña -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-key me-2"></i>Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           name="password_confirmation" 
                                           id="passwordConfirmation"
                                           required
                                           placeholder="Repite la contraseña">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="mt-1"></div>
                            </div>
                            
                            <!-- Requisitos -->
                            <div class="card border mb-4">
                                <div class="card-body p-3">
                                    <small class="fw-bold d-block mb-2">La contraseña debe contener:</small>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <span id="lengthCheck">Mínimo 8 caracteres</span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <span id="lowercaseCheck">Una letra minúscula</span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <span id="uppercaseCheck">Una letra mayúscula</span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <span id="numberCheck">Un número</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Botón -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg fw-bold py-3" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>
                                    GUARDAR NUEVA CONTRASEÑA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('passwordConfirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');
    const submitBtn = document.getElementById('submitBtn');
    const passwordMatch = document.getElementById('passwordMatch');
    
    // Toggle visibilidad de contraseña
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    togglePasswordConfirmation.addEventListener('click', function() {
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Verificar fortaleza de contraseña
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Longitud
        if (password.length >= 8) strength += 25;
        
        // Minúsculas
        if (/[a-z]/.test(password)) strength += 25;
        
        // Mayúsculas
        if (/[A-Z]/.test(password)) strength += 25;
        
        // Números
        if (/[0-9]/.test(password)) strength += 25;
        
        // Actualizar barra y texto
        strengthBar.style.width = strength + '%';
        
        if (strength < 50) {
            strengthBar.className = 'progress-bar bg-danger';
            strengthText.textContent = 'Débil';
            strengthText.className = 'text-danger';
        } else if (strength < 75) {
            strengthBar.className = 'progress-bar bg-warning';
            strengthText.textContent = 'Moderada';
            strengthText.className = 'text-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            strengthText.textContent = 'Fuerte';
            strengthText.className = 'text-success';
        }
        
        // Actualizar checks
        updatePasswordChecks(password);
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
            passwordMatch.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Las contraseñas coinciden</small>';
        } else {
            passwordMatch.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle me-1"></i>Las contraseñas no coinciden</small>';
        }
    }
    
    function updatePasswordChecks(password) {
        // Actualizar íconos de requisitos
        const checks = {
            lengthCheck: password.length >= 8,
            lowercaseCheck: /[a-z]/.test(password),
            uppercaseCheck: /[A-Z]/.test(password),
            numberCheck: /[0-9]/.test(password)
        };
        
        for (const [id, isValid] of Object.entries(checks)) {
            const element = document.getElementById(id);
            const icon = element.previousElementSibling;
            icon.className = isValid ? 'fas fa-check-circle text-success me-2' : 'fas fa-times-circle text-danger me-2';
            element.style.color = isValid ? '#198754' : '#dc3545';
        }
    }
    
    // Validar formulario antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        if (password.length < 8) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 8 caracteres.');
            passwordInput.focus();
            return;
        }
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
            confirmInput.focus();
            return;
        }
        
        // Mostrar mensaje de éxito
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
        submitBtn.disabled = true;
    });
});
</script>

<style>
.reset-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
}

.password-strength .progress {
    background-color: #e9ecef;
}

.password-strength .progress-bar {
    transition: width 0.3s ease, background-color 0.3s ease;
}
</style>
@endsection