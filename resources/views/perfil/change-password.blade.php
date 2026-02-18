@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8 col-xl-6">
        <div class="card border-0 shadow-sm password-change-card">
            <!-- Header minimalista -->
            <div class="card-header border-0 pt-5 pb-4 position-relative overflow-hidden">
                <div class="header-background"></div>
                <div class="position-relative d-flex align-items-center">
                    <div class="me-4">
                        <div class="icon-wrapper rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 64px; height: 64px; background: linear-gradient(135deg, #f0b429 0%, #f09329 100%);">
                            <i class="fas fa-lock text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-2 text-dark fw-semibold fs-2">Cambiar Contraseña</h4>
                        <p class="mb-0 text-muted fs-6">Actualiza tus credenciales de seguridad</p>
                    </div>
                    <div class="ms-auto">
                        <div class="security-level">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2 px-3 rounded-pill">
                                <i class="fas fa-badge-check me-2"></i>
                                Seguridad Alta
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 p-xl-5">
                <!-- Indicador de progreso minimalista -->
                <div class="progress-indicator mb-5">
                    <div class="d-flex align-items-center position-relative">
                        <div class="step active">
                            <div class="step-number">1</div>
                            <div class="step-label">Verificación</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-label">Nueva Contraseña</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-label">Confirmar</div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de éxito -->
                @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 border-start-4 border-success mb-4 d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle text-success fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-semibold mb-1">¡Contraseña actualizada!</h6>
                        <p class="mb-0 small">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('perfil.actualizar-password') }}" id="passwordForm" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Contraseña Actual -->
                    <div class="mb-5">
                        <h5 class="mb-4 fw-semibold text-dark d-flex align-items-center">
                            <i class="fas fa-user-shield text-warning me-2"></i>
                            Verificar identidad
                        </h5>
                        
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label text-muted small fw-semibold mb-2">
                                CONTRASEÑA ACTUAL
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 ps-0 @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required
                                       placeholder="Ingresa tu contraseña actual"
                                       autocomplete="current-password"
                                       value="{{ old('current_password') }}">
                                <button class="btn btn-light border password-toggle" type="button" 
                                        data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-2 d-flex align-items-start">
                                    <i class="fas fa-exclamation-circle mt-1 me-2"></i>
                                    <span>{{ translatePasswordError($message) }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nueva Contraseña -->
                    <div class="mb-5">
                        <h5 class="mb-4 fw-semibold text-dark d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-lock text-primary me-2"></i>
                                Nueva contraseña
                            </span>
                            <span class="text-muted small fw-normal" id="passwordScore">0/100</span>
                        </h5>
                        
                        <div class="form-group mb-3">
                            <label for="new_password" class="form-label text-muted small fw-semibold mb-2">
                                NUEVA CONTRASEÑA
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 ps-0 @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password" 
                                       required
                                       placeholder="Crea una nueva contraseña segura"
                                       autocomplete="new-password"
                                       value="{{ old('new_password') }}">
                                <button class="btn btn-light border password-toggle me-1" type="button" 
                                        data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-light border generate-password" type="button" 
                                        title="Generar contraseña segura">
                                    <i class="fas fa-random"></i>
                                </button>
                            </div>
                            
                            @error('new_password')
                                <div class="text-danger small mt-2 d-flex align-items-start">
                                    <i class="fas fa-exclamation-circle mt-1 me-2"></i>
                                    <span>{{ translatePasswordError($message) }}</span>
                                </div>
                            @enderror
                            
                            <!-- Indicador de fortaleza mejorado -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Seguridad</small>
                                    <small class="fw-semibold" id="strengthText">Muy débil</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" id="passwordStrength" role="progressbar"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="form-group mt-4">
                            <label for="new_password_confirmation" class="form-label text-muted small fw-semibold mb-2">
                                CONFIRMAR CONTRASEÑA
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 ps-0 @error('new_password') is-invalid @enderror" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       required
                                       placeholder="Repite la nueva contraseña"
                                       autocomplete="new-password">
                                <button class="btn btn-light border password-toggle" type="button" 
                                        data-target="new_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2" id="matchIndicator">
                                <small class="text-muted">
                                    <i class="fas fa-circle me-2" style="font-size: 6px;"></i>
                                    Las contraseñas deben coincidir
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Requisitos de seguridad en tarjeta -->
                    <div class="card border-0 bg-light bg-opacity-50 mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                <i class="fas fa-clipboard-check text-info me-2"></i>
                                Requisitos de seguridad
                                <span class="ms-auto badge bg-white text-dark border" id="requirementsCount">0/5</span>
                            </h6>
                            
                            <div class="row g-3" id="requirementsList">
                                <!-- Requisitos generados dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Tips de seguridad colapsable -->
                    <div class="card border-0 bg-light bg-opacity-25 mb-4">
                        <div class="card-body p-0">
                            <a class="d-flex align-items-center p-4 text-decoration-none" 
                               data-bs-toggle="collapse" 
                               href="#securityTips" 
                               role="button">
                                <div class="icon-wrapper bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                    <i class="fas fa-lightbulb text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1 text-dark">Consejos de seguridad</h6>
                                    <small class="text-muted">Mejores prácticas para crear contraseñas seguras</small>
                                </div>
                                <i class="fas fa-chevron-down text-muted"></i>
                            </a>
                            <div class="collapse" id="securityTips">
                                <div class="p-4 pt-0">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="fas fa-check text-success mt-1 me-3"></i>
                                                <div>
                                                    <small class="fw-semibold d-block mb-1">Usa una frase larga</small>
                                                    <small class="text-muted">Combina palabras aleatorias con números</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="fas fa-check text-success mt-1 me-3"></i>
                                                <div>
                                                    <small class="fw-semibold d-block mb-1">Caracteres especiales</small>
                                                    <small class="text-muted">Incluye símbolos como !@#$%^&*</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="fas fa-times text-danger mt-1 me-3"></i>
                                                <div>
                                                    <small class="fw-semibold d-block mb-1">Evita información personal</small>
                                                    <small class="text-muted">No uses nombres, fechas o palabras comunes</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="fas fa-times text-danger mt-1 me-3"></i>
                                                <div>
                                                    <small class="fw-semibold d-block mb-1">No reutilices contraseñas</small>
                                                    <small class="text-muted">Usa contraseñas únicas para cada sitio</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Sugerencias rápidas -->
                                    <div class="mt-3">
                                        <small class="fw-semibold d-block mb-2">Sugerencias rápidas:</small>
                                        <div class="d-flex flex-wrap gap-2" id="suggestedPasswords">
                                            <!-- Generadas dinámicamente -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between pt-4 mt-5 border-top">
                        <a href="{{ route('perfil.edit') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-outline-secondary px-4" id="resetForm">
                                <i class="fas fa-redo me-2"></i> Limpiar
                            </button>
                            <button type="submit" class="btn btn-primary px-5 fw-semibold" id="submitBtn" disabled>
                                <span class="btn-content">
                                    <i class="fas fa-save me-2"></i> Actualizar
                                </span>
                                <span class="btn-loader d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Footer informativo minimalista -->
            <div class="card-footer bg-transparent border-top py-3">
                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <div>
                        <i class="fas fa-clock me-1"></i>
                        Último cambio: 
                        @if(auth()->user()->password_changed_at)
                            <span class="fw-semibold">{{ auth()->user()->password_changed_at->diffForHumans() }}</span>
                        @else
                            <span class="fw-semibold">Nunca</span>
                        @endif
                    </div>
                    <div>
                        <i class="fas fa-shield-alt me-1"></i>
                        <span class="fw-semibold">Encriptación SSL</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración
    const config = {
        requirements: [
            { id: 'length', label: 'Mínimo 8 caracteres', regex: /.{8,}/, weight: 20 },
            { id: 'uppercase', label: '1 letra mayúscula', regex: /[A-Z]/, weight: 20 },
            { id: 'lowercase', label: '1 letra minúscula', regex: /[a-z]/, weight: 20 },
            { id: 'number', label: '1 número', regex: /[0-9]/, weight: 20 },
            { id: 'special', label: '1 carácter especial', regex: /[!@#$%^&*(),.?":{}|<>]/, weight: 20 }
        ],
        strengthColors: {
            0: '#dc3545',
            20: '#fd7e14',
            40: '#ffc107',
            60: '#20c997',
            80: '#198754'
        }
    };

    // Traducción de errores comunes
    const errorTranslations = {
        'The current password field is required.': 'La contraseña actual es obligatoria.',
        'The current password field must be at least 6 characters.': 'La contraseña actual debe tener al menos 6 caracteres.',
        'The new password field is required.': 'La nueva contraseña es obligatoria.',
        'The new password field must be at least 8 characters.': 'La nueva contraseña debe tener al menos 8 caracteres.',
        'The new password confirmation field is required.': 'La confirmación de contraseña es obligatoria.',
        'The new password confirmation field must match new password.': 'La confirmación de contraseña no coincide.',
        'The password must be at least 8 characters.': 'La contraseña debe tener al menos 8 caracteres.',
        'The password must contain at least one uppercase and one lowercase letter.': 'La contraseña debe contener al menos una letra mayúscula y una minúscula.',
        'The password must contain at least one number.': 'La contraseña debe contener al menos un número.',
        'The password must contain at least one symbol.': 'La contraseña debe contener al menos un símbolo.',
        'The current password is incorrect.': 'La contraseña actual es incorrecta.',
        'Las contraseñas no coinciden': 'Las contraseñas no coinciden'
    };

    // Elementos DOM
    const elements = {
        form: document.getElementById('passwordForm'),
        currentPassword: document.getElementById('current_password'),
        newPassword: document.getElementById('new_password'),
        confirmPassword: document.getElementById('new_password_confirmation'),
        submitBtn: document.getElementById('submitBtn'),
        strengthBar: document.getElementById('passwordStrength'),
        strengthText: document.getElementById('strengthText'),
        passwordScore: document.getElementById('passwordScore'),
        matchIndicator: document.getElementById('matchIndicator'),
        requirementsList: document.getElementById('requirementsList'),
        requirementsCount: document.getElementById('requirementsCount'),
        suggestedPasswords: document.getElementById('suggestedPasswords')
    };

    // Estado
    const state = {
        score: 0,
        metRequirements: 0,
        isSubmitting: false
    };

    // Función para traducir errores
    function translateError(message) {
        return errorTranslations[message] || message;
    }

    // Inicializar
    function init() {
        setupPasswordToggles();
        setupPasswordGenerator();
        setupRequirementsList();
        generateSuggestedPasswords();
        setupFormValidation();
        setupResetButton();
        
        // Event listeners
        elements.newPassword.addEventListener('input', updatePasswordAnalysis);
        elements.confirmPassword.addEventListener('input', updatePasswordAnalysis);
        elements.currentPassword.addEventListener('input', validateForm);
        
        // Mostrar errores del servidor si existen
        showServerErrors();
        
        updatePasswordAnalysis();
    }

    // Mostrar errores del servidor
    function showServerErrors() {
        // Verificar si hay errores en los campos específicos
        const fields = ['current_password', 'new_password', 'new_password_confirmation'];
        
        fields.forEach(field => {
            const input = document.getElementById(field);
            const errorContainer = input?.closest('.form-group')?.querySelector('.text-danger');
            
            if (input && input.classList.contains('is-invalid') && errorContainer) {
                // Animar el campo con error
                input.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    input.style.animation = '';
                }, 500);
                
                // Resaltar el campo
                input.focus();
            }
        });
    }

    // Toggles de contraseña
    function setupPasswordToggles() {
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                    this.classList.add('active');
                } else {
                    input.type = 'password';
                    icon.className = 'fas fa-eye';
                    this.classList.remove('active');
                }
            });
        });
    }

    // Generador de contraseñas
    function setupPasswordGenerator() {
        document.querySelector('.generate-password')?.addEventListener('click', function() {
            const password = generateSecurePassword();
            elements.newPassword.value = password;
            elements.confirmPassword.value = password;
            updatePasswordAnalysis();
            
            showNotification('Contraseña generada', 'Se ha creado una contraseña segura automáticamente.', 'success');
        });
    }

    function generateSecurePassword() {
        const chars = {
            lower: 'abcdefghijklmnopqrstuvwxyz',
            upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            numbers: '0123456789',
            symbols: '!@#$%^&*'
        };

        let password = '';
        
        // Asegurar un carácter de cada tipo
        password += chars.lower[Math.floor(Math.random() * chars.lower.length)];
        password += chars.upper[Math.floor(Math.random() * chars.upper.length)];
        password += chars.numbers[Math.floor(Math.random() * chars.numbers.length)];
        password += chars.symbols[Math.floor(Math.random() * chars.symbols.length)];
        
        // Completar hasta 12 caracteres
        const allChars = chars.lower + chars.upper + chars.numbers + chars.symbols;
        while (password.length < 12) {
            password += allChars[Math.floor(Math.random() * allChars.length)];
        }
        
        return password.split('').sort(() => Math.random() - 0.5).join('');
    }

    // Lista de requisitos
    function setupRequirementsList() {
        elements.requirementsList.innerHTML = config.requirements.map(req => `
            <div class="col-12 col-md-6">
                <div class="d-flex align-items-center requirement-item" data-id="${req.id}">
                    <div class="requirement-check me-3">
                        <i class="fas fa-circle" style="font-size: 8px;"></i>
                    </div>
                    <small class="text-muted requirement-label">${req.label}</small>
                    <div class="ms-auto">
                        <i class="fas fa-times text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Sugerencias de contraseñas
    function generateSuggestedPasswords() {
        const suggestions = [
            generateMemorablePassword(),
            generatePatternPassword(),
            generateSecurePassword()
        ];
        
        suggestions.forEach(password => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-sm btn-outline-primary';
            button.textContent = password.substring(0, 12) + '...';
            button.title = 'Usar esta contraseña';
            
            button.addEventListener('click', function() {
                elements.newPassword.value = password;
                elements.confirmPassword.value = password;
                updatePasswordAnalysis();
                showNotification('Contraseña aplicada', 'La sugerencia ha sido aplicada.', 'info');
            });
            
            elements.suggestedPasswords.appendChild(button);
        });
    }

    function generateMemorablePassword() {
        const words = ['Sol', 'Luna', 'Mar', 'Cielo', 'Montaña'];
        const numbers = Math.floor(Math.random() * 90) + 10;
        return words[Math.floor(Math.random() * words.length)] + numbers + '!';
    }

    function generatePatternPassword() {
        return 'Aa1!' + Math.floor(Math.random() * 90 + 10) + 'Bb@';
    }

    // Análisis de contraseña
    function updatePasswordAnalysis() {
        const password = elements.newPassword.value;
        const confirmPassword = elements.confirmPassword.value;
        
        state.score = calculatePasswordScore(password);
        checkRequirements(password, confirmPassword);
        updateStrengthIndicator();
        updateMatchIndicator(password, confirmPassword);
        validateForm();
    }

    function calculatePasswordScore(password) {
        if (!password) return 0;
        
        let score = 0;
        score += Math.min(password.length * 3, 30);
        
        const checks = [
            /[a-z]/.test(password),
            /[A-Z]/.test(password),
            /[0-9]/.test(password),
            /[^A-Za-z0-9]/.test(password)
        ];
        
        score += checks.filter(Boolean).length * 15;
        return Math.min(100, score);
    }

    function checkRequirements(password, confirmPassword) {
        state.metRequirements = 0;
        
        config.requirements.forEach(req => {
            const isMet = req.regex.test(password);
            const item = document.querySelector(`.requirement-item[data-id="${req.id}"]`);
            
            if (item) {
                const icon = item.querySelector('.fa-times');
                const check = item.querySelector('.requirement-check i');
                
                if (isMet) {
                    icon.className = 'fas fa-check text-success';
                    check.className = 'fas fa-check-circle text-success';
                    state.metRequirements++;
                } else {
                    icon.className = 'fas fa-times text-danger opacity-50';
                    check.className = 'fas fa-circle text-muted opacity-50';
                }
            }
        });
        
        // Actualizar contador
        elements.requirementsCount.textContent = `${state.metRequirements}/${config.requirements.length}`;
        elements.requirementsCount.className = state.metRequirements === config.requirements.length 
            ? 'badge bg-success text-white' 
            : 'badge bg-white text-dark border';
    }

    function updateStrengthIndicator() {
        elements.strengthBar.style.width = `${state.score}%`;
        
        let color = config.strengthColors[0];
        if (state.score >= 80) color = config.strengthColors[80];
        else if (state.score >= 60) color = config.strengthColors[60];
        else if (state.score >= 40) color = config.strengthColors[40];
        else if (state.score >= 20) color = config.strengthColors[20];
        
        elements.strengthBar.style.backgroundColor = color;
        elements.passwordScore.textContent = `${state.score}/100`;
        
        // Texto descriptivo
        let text = 'Muy débil';
        if (state.score >= 80) text = 'Excelente';
        else if (state.score >= 60) text = 'Buena';
        else if (state.score >= 40) text = 'Regular';
        else if (state.score >= 20) text = 'Débil';
        
        elements.strengthText.textContent = text;
        elements.strengthText.style.color = color;
    }

    function updateMatchIndicator(password, confirmPassword) {
        if (!password || !confirmPassword) {
            elements.matchIndicator.innerHTML = `
                <small class="text-muted">
                    <i class="fas fa-circle me-2" style="font-size: 6px;"></i>
                    Las contraseñas deben coincidir
                </small>
            `;
            return;
        }
        
        if (password === confirmPassword) {
            elements.matchIndicator.innerHTML = `
                <small class="text-success fw-semibold">
                    <i class="fas fa-check-circle me-2"></i>
                    Contraseñas coinciden
                </small>
            `;
        } else {
            elements.matchIndicator.innerHTML = `
                <small class="text-danger">
                    <i class="fas fa-times-circle me-2"></i>
                    Las contraseñas no coinciden
                </small>
            `;
        }
    }

    function validateForm() {
        const currentValid = elements.currentPassword.value.length >= 6;
        const passwordValid = state.score >= 60;
        const passwordsMatch = elements.newPassword.value === elements.confirmPassword.value;
        const allRequirementsMet = state.metRequirements === config.requirements.length;
        
        const isValid = currentValid && passwordValid && passwordsMatch && allRequirementsMet;
        elements.submitBtn.disabled = !isValid;
        
        // Actualizar clases de validación visual
        if (elements.currentPassword.value) {
            elements.currentPassword.classList.toggle('is-valid', currentValid);
            elements.currentPassword.classList.toggle('is-invalid', !currentValid);
        }
        
        if (elements.newPassword.value) {
            elements.newPassword.classList.toggle('is-valid', passwordValid);
            elements.newPassword.classList.toggle('is-invalid', !passwordValid);
        }
    }

    function setupFormValidation() {
        elements.form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (state.isSubmitting) return;
            
            const isValid = elements.submitBtn.disabled === false;
            if (!isValid) {
                showNotification('Error de validación', 'Por favor, corrige los errores en el formulario.', 'error');
                return;
            }
            
            // Confirmación
            Swal.fire({
                title: '¿Actualizar contraseña?',
                html: `
                    <p>Se actualizará tu contraseña y deberás iniciar sesión nuevamente.</p>
                    <div class="alert alert-light border mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Recomendamos guardar la nueva contraseña en un gestor seguro
                        </small>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    state.isSubmitting = true;
                    elements.submitBtn.querySelector('.btn-content').classList.add('d-none');
                    elements.submitBtn.querySelector('.btn-loader').classList.remove('d-none');
                    elements.submitBtn.disabled = true;
                    
                    setTimeout(() => {
                        elements.form.submit();
                    }, 1000);
                }
            });
        });
    }

    function setupResetButton() {
        document.getElementById('resetForm').addEventListener('click', function() {
            Swal.fire({
                title: '¿Limpiar formulario?',
                text: 'Se borrarán todos los campos ingresados',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#0d6efd'
            }).then((result) => {
                if (result.isConfirmed) {
                    elements.form.reset();
                    updatePasswordAnalysis();
                    showNotification('Formulario limpiado', 'Todos los campos han sido restablecidos.', 'info');
                }
            });
        });
    }

    function showNotification(title, message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} 
                   text-${type} me-3"></i>
                <div>
                    <strong>${title}</strong>
                    <small class="d-block text-muted">${message}</small>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    init();
});
</script>

<style>
.password-change-card {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.08);
}

/* Header minimalista */
.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.header-background {
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 193, 7, 0) 100%);
    clip-path: polygon(100% 0, 100% 100%, 0 0);
}

.icon-wrapper {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Progress indicator */
.progress-indicator {
    padding: 0 20px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}

.step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.step-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

.step.active .step-label {
    color: #0d6efd;
    font-weight: 600;
}

.step-line {
    flex: 1;
    height: 2px;
    background: #e9ecef;
    margin: 0 10px;
    position: relative;
    top: -24px;
}

/* Formulario mejorado */
.form-control {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: none;
}

.form-control.is-valid {
    border-color: #198754;
    background-image: none;
}

.input-group-lg .form-control {
    border-radius: 8px 0 0 8px;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

/* Errores mejorados */
.text-danger {
    color: #dc3545 !important;
}

.text-danger i {
    color: #dc3545;
}

/* Indicador de fortaleza */
.progress {
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.5s ease, background-color 0.5s ease;
    border-radius: 3px;
}

/* Requisitos */
.requirement-item {
    padding: 8px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.requirement-item:last-child {
    border-bottom: none;
}

.requirement-check i {
    transition: all 0.3s ease;
}

/* Botones */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-outline-secondary {
    border: 1px solid #dee2e6;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    border-color: #6c757d;
}

/* Notificaciones */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    max-width: 350px;
    z-index: 9999;
    border-left: 4px solid;
    animation: slideIn 0.3s ease;
}

.notification-success { border-color: #198754; }
.notification-error { border-color: #dc3545; }
.notification-info { border-color: #0dcaf0; }

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.fade-out {
    animation: fadeOut 0.3s ease forwards;
}

@keyframes fadeOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Animación para campos con error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Cards mejoradas */
.card {
    border-radius: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }
    
    .progress-indicator {
        padding: 0 10px;
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    .step-label {
        font-size: 0.7rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between > div {
        width: 100%;
    }
    
    .btn {
        width: 100%;
    }
}

/* Animaciones suaves */
.form-control, .btn, .card {
    transition: all 0.2s ease;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<!-- Dependencias -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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