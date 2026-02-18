@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar Restauración de Base de Datos
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">
                            <i class="fas fa-radiation me-2"></i>
                            ¡ADVERTENCIA CRÍTICA!
                        </h5>
                        <p class="mb-0">
                            Esta acción <strong>ELIMINARÁ TODOS LOS DATOS ACTUALES</strong> de la base de datos 
                            y los reemplazará con los datos del respaldo seleccionado.
                        </p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0">Respaldo a Restaurar</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nombre:</strong> {{ $respaldo->Nombre }}</p>
                                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($respaldo->Fecha)->format('d/m/Y H:i:s') }}</p>
                                    <p><strong>Tamaño:</strong> {{ $controller->formatearTamaño($fileSize) }}</p>
                                    <p><strong>Archivo:</strong> {{ basename($respaldo->Ruta) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h6 class="mb-0">Estado Actual</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $estadisticas = $controller->obtenerEstadisticasBaseDatos();
                                    @endphp
                                    <p><strong>Tablas actuales:</strong> {{ $estadisticas['total_tablas'] }}</p>
                                    <p><strong>Tamaño actual:</strong> {{ $estadisticas['tamaño_total'] }}</p>
                                    <p><strong>Última modificación:</strong> {{ $lastModified }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>
                            Recomendaciones antes de continuar:
                        </h6>
                        <ul class="mb-0">
                            <li>Realice un respaldo de la base de datos actual</li>
                            <li>Asegúrese de que todos los usuarios hayan cerrado sesión</li>
                            <li>Este proceso puede tomar varios minutos dependiendo del tamaño</li>
                            <li>No cierre el navegador durante el proceso</li>
                        </ul>
                    </div>
                    
                    <form action="{{ route('respaldos.restaurar', $respaldo->id) }}" method="POST" id="restoreForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="confirmacion" class="form-label">
                                Para confirmar, escriba <strong>CONFIRMAR</strong> en el siguiente campo:
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg text-uppercase @error('confirmacion') is-invalid @enderror" 
                                   id="confirmacion" 
                                   name="confirmacion" 
                                   required
                                   placeholder="CONFIRMAR">
                            @error('confirmacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg" id="btnRestaurar">
                                <i class="fas fa-database me-2"></i>
                                RESTAURAR BASE DE DATOS
                            </button>
                            <a href="{{ route('respaldos.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('restoreForm');
    const btnRestaurar = document.getElementById('btnRestaurar');
    const confirmacionInput = document.getElementById('confirmacion');
    
    btnRestaurar.addEventListener('click', function(e) {
        e.preventDefault();
        
        const confirmacion = confirmacionInput.value.trim().toUpperCase();
        
        if (confirmacion !== 'CONFIRMAR') {
            alert('Debe escribir EXACTAMENTE la palabra "CONFIRMAR" para proceder.');
            confirmacionInput.focus();
            return;
        }
        
        // Mostrar confirmación final
        Swal.fire({
            title: '¿ESTÁ ABSOLUTAMENTE SEGURO?',
            html: `
                <div class="text-start">
                    <div class="alert alert-danger">
                        <p>Esta acción <strong>NO SE PUEDE DESHACER</strong>.</p>
                        <p>Todos los datos actuales se perderán permanentemente.</p>
                    </div>
                    <p>¿Desea continuar con la restauración?</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-database me-2"></i> Sí, Restaurar',
            cancelButtonText: '<i class="fas fa-times me-2"></i> Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            customClass: {
                popup: 'shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Deshabilitar botón y mostrar cargando
                btnRestaurar.disabled = true;
                btnRestaurar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Restaurando...';
                
                // Enviar formulario
                form.submit();
            }
        });
    });
});
</script>
@endpush
@endsection