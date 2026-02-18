@extends('layouts.app')

@section('content')
<section class="verify-section d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                        <h3 class="mb-0">
                            <i class="fas fa-shield-alt me-2"></i>
                            VERIFICAR CÓDIGO
                        </h3>
                        <small class="opacity-75">Muebles Yasbo - Sistema Administrativo</small>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                            </div>
                            <h5 class="fw-bold">Ingresa el código de 6 dígitos</h5>
                            <p class="text-muted mb-0">
                                Enviamos un código al correo: 
                                <strong class="text-primary">{{ $email }}</strong>
                            </p>
                        </div>
                        
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('password.verify.code') }}" id="verifyForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <!-- Código de 6 dígitos -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Código de verificación</label>
                                <div class="d-flex justify-content-center gap-2 mb-3">
                                    @for($i = 1; $i <= 6; $i++)
                                        <input type="text" 
                                               class="form-control form-control-lg text-center code-digit" 
                                               maxlength="1" 
                                               style="width: 50px; height: 60px; font-size: 1.5rem;"
                                               data-index="{{ $i }}">
                                    @endfor
                                </div>
                                <input type="hidden" name="code" id="fullCode">
                                <small class="text-muted d-block text-center">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ingresa el código de 6 dígitos que recibiste por correo
                                </small>
                            </div>
                            
                            <!-- Tiempo restante -->
                            <div class="text-center mb-4">
                                <div class="countdown-container">
                                    <i class="fas fa-clock me-2 text-warning"></i>
                                    <span id="countdown">15:00</span>
                                </div>
                                <small class="text-muted">El código expirará en <span id="minutes">15</span> minutos</small>
                            </div>
                            
                            <!-- Botones -->
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold py-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    VERIFICAR CÓDIGO
                                </button>
                                
                                <div class="d-flex justify-content-between">
                                    <button type="button" 
                                            id="resendBtn" 
                                            class="btn btn-outline-primary"
                                            disabled>
                                        <i class="fas fa-redo me-2"></i>
                                        Reenviar código 
                                        <span id="resendTimer" class="ms-1">(60)</span>
                                    </button>
                                    
                                    <a href="{{ route('password.request') }}" class="btn btn-link text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Cambiar email
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Sistema protegido • © {{ date('Y') }} Muebles Yasbo
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.verify-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
}

.code-digit {
    border: 2px solid #dee2e6;
    border-radius: 10px !important;
    transition: all 0.3s;
}

.code-digit:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    transform: translateY(-2px);
}

.countdown-container {
    display: inline-flex;
    align-items: center;
    background: #fff3cd;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid #ffc107;
    margin-bottom: 10px;
}

#countdown {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    font-size: 1.2rem;
    color: #856404;
}

.btn-outline-primary:disabled {
    opacity: 0.6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const digits = document.querySelectorAll('.code-digit');
    const fullCodeInput = document.getElementById('fullCode');
    const form = document.getElementById('verifyForm');
    const resendBtn = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');
    
    // Manejar entrada de dígitos
    digits.forEach((digit, index) => {
        digit.addEventListener('input', function(e) {
            // Solo permitir números
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-enfocar siguiente campo
            if (this.value.length === 1 && index < digits.length - 1) {
                digits[index + 1].focus();
            }
            
            // Actualizar código completo
            updateFullCode();
        });
        
        digit.addEventListener('keydown', function(e) {
            // Manejar backspace
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                digits[index - 1].focus();
                digits[index - 1].value = '';
                updateFullCode();
            }
        });
        
        // Pegar código completo
        digit.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const numbers = pastedData.replace(/[^0-9]/g, '').slice(0, 6);
            
            numbers.split('').forEach((num, i) => {
                if (digits[i]) {
                    digits[i].value = num;
                }
            });
            
            if (numbers.length > 0) {
                updateFullCode();
                digits[Math.min(numbers.length - 1, 5)].focus();
            }
        });
    });
    
    function updateFullCode() {
        let code = '';
        digits.forEach(digit => {
            code += digit.value || '';
        });
        fullCodeInput.value = code;
    }
    
    // Validar formulario
    form.addEventListener('submit', function(e) {
        updateFullCode();
        if (fullCodeInput.value.length !== 6) {
            e.preventDefault();
            alert('Por favor ingresa los 6 dígitos del código.');
            digits[0].focus();
        }
    });
    
    // Contador regresivo para el código
    let timeLeft = 15 * 60; // 15 minutos en segundos
    const countdownElement = document.getElementById('countdown');
    
    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownElement.textContent = 
            minutes.toString().padStart(2, '0') + ':' + 
            seconds.toString().padStart(2, '0');
        
        if (timeLeft <= 0) {
            clearInterval(countdownInterval);
            countdownElement.textContent = '00:00';
            alert('El código ha expirado. Por favor solicita uno nuevo.');
            window.location.href = "{{ route('password.request') }}";
        } else {
            timeLeft--;
        }
    }
    
    const countdownInterval = setInterval(updateCountdown, 1000);
    
    // Contador para reenviar código
    let resendTimeLeft = 60;
    function updateResendTimer() {
        if (resendTimeLeft > 0) {
            resendTimeLeft--;
            resendTimer.textContent = '(' + resendTimeLeft + ')';
        } else {
            clearInterval(resendInterval);
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i class="fas fa-redo me-2"></i>Reenviar código';
        }
    }
    
    const resendInterval = setInterval(updateResendTimer, 1000);
    
    // Reenviar código
    resendBtn.addEventListener('click', function() {
        if (!this.disabled) {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
            
            // Enviar solicitud AJAX
            fetch('{{ route("password.resend.code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: '{{ $email }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Nuevo código enviado! Revisa tu correo.');
                    // Resetear contadores
                    clearInterval(countdownInterval);
                    clearInterval(resendInterval);
                    timeLeft = 15 * 60;
                    resendTimeLeft = 60;
                    updateCountdown();
                    updateResendTimer();
                    countdownInterval = setInterval(updateCountdown, 1000);
                    resendInterval = setInterval(updateResendTimer, 1000);
                } else {
                    alert('Error al reenviar código.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-redo me-2"></i>Reenviar código';
                }
            })
            .catch(error => {
                alert('Error de conexión.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-redo me-2"></i>Reenviar código';
            });
        }
    });
    
    // Auto-focus en primer dígito
    digits[0].focus();
});
</script>
@endsection