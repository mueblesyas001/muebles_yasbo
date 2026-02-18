@extends('layouts.app')

@section('content')
<div id="clientes-page" class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h1 class="h2 mb-1 text-primary fw-bold">Gestión de Clientes</h1>
            <p class="text-muted mb-0">Administra la información de tus clientes registrados</p>
        </div>
        <div>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Nuevo Cliente
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Clientes</h6>
                            <h3 class="mb-0">{{ $clientes->count() }}</h3>
                        </div>
                        <div class="cliente-avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Hombres</h6>
                            <h3 class="mb-0 text-primary">
                                {{ $clientes->where('Sexo', 'Masculino')->count() }}
                            </h3>
                        </div>
                        <div class="cliente-avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-male text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Mujeres</h6>
                            <h3 class="mb-0 text-pink">
                                {{ $clientes->where('Sexo', 'Femenino')->count() }}
                            </h3>
                        </div>
                        <div class="cliente-avatar-sm bg-pink bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-female text-pink fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Otros</h6>
                            <h3 class="mb-0 text-secondary">
                                {{ $clientes->whereNotIn('Sexo', ['Masculino', 'Femenino'])->count() }}
                            </h3>
                        </div>
                        <div class="cliente-avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-alt text-secondary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Búsqueda y Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-search me-2 text-primary"></i>
                Búsqueda y Filtros
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">
                        <i class="fas fa-search me-1"></i> Buscar Cliente
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="searchInput" 
                               class="form-control" 
                               placeholder="Nombre, correo, teléfono..."
                               aria-label="Buscar cliente">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        <i class="fas fa-venus-mars me-1"></i> Sexo
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <select id="filterSexo" class="form-select" aria-label="Sexo">
                            <option value="">Todos los sexos</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        <i class="fas fa-sort me-1"></i> Ordenar por
                    </label>
                    <select id="sortBy" class="form-select" aria-label="Ordenar por">
                        <option value="Nombre">Nombre</option>
                        <option value="id">ID</option>
                        <option value="Correo">Correo</option>
                        <option value="Telefono">Teléfono</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">
                        <i class="fas fa-sort-amount-down me-1"></i> Dirección
                    </label>
                    <select id="sortOrder" class="form-select" aria-label="Dirección orden">
                        <option value="asc">Ascendente</option>
                        <option value="desc">Descendente</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end align-items-center h-100 gap-2">
                        <div class="text-muted small">
                            <span id="filterCount">0</span> filtro(s) activo(s)
                        </div>
                        <div class="btn-group">
                            <button type="button" id="applyFilters" class="btn btn-primary px-4">
                                <i class="fas fa-filter me-1"></i> Aplicar
                            </button>
                            <button type="button" id="resetFilters" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Lista de Clientes
                </h5>
                <small class="text-muted">
                    <span id="totalCount">{{ $clientes->count() }}</span> cliente(s) registrado(s)
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">
                    Ordenado por: 
                    <span class="badge bg-light text-dark" id="sortDisplay">
                        Nombre <i class="fas fa-arrow-up ms-1"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="clientesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3" width="60px"></th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Contacto</th>
                            <th class="py-3">Dirección</th>
                            <th class="py-3">Sexo</th>
                            <th class="text-end py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        @php
                            $nombreCompleto = $cliente->Nombre . ' ' . $cliente->ApPaterno . ($cliente->ApMaterno ? ' ' . $cliente->ApMaterno : '');
                        @endphp
                        <tr class="align-middle cliente-row" data-nombre="{{ strtolower($nombreCompleto) }}" 
                            data-correo="{{ strtolower($cliente->Correo) }}" 
                            data-telefono="{{ $cliente->Telefono ?? '' }}"
                            data-sexo="{{ $cliente->Sexo ?? '' }}">
                            <!-- Botón para expandir -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary btn-expand-cliente" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detallesCliente{{ $cliente->id }}" 
                                        aria-expanded="false"
                                        aria-controls="detallesCliente{{ $cliente->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="cliente-avatar cliente-avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $nombreCompleto }}</h6>
                                        <small class="text-muted">
                                            ID: #{{ $cliente->id }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <a href="mailto:{{ $cliente->Correo }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        {{ $cliente->Correo }}
                                    </a>
                                </div>
                                <div>
                                    @if($cliente->Telefono)
                                    <i class="fas fa-phone me-1 text-muted"></i>
                                    {{ $cliente->Telefono }}
                                    @else
                                    <span class="text-muted">Sin teléfono</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($cliente->Direccion)
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      data-bs-toggle="tooltip" title="{{ $cliente->Direccion }}">
                                    {{ $cliente->Direccion }}
                                </span>
                                @else
                                <span class="text-muted">Sin dirección</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $cliente->Sexo == 'Masculino' ? 'primary' : ($cliente->Sexo == 'Femenino' ? 'pink' : 'secondary') }} bg-opacity-10 text-{{ $cliente->Sexo == 'Masculino' ? 'primary' : ($cliente->Sexo == 'Femenino' ? 'pink' : 'secondary') }} border border-{{ $cliente->Sexo == 'Masculino' ? 'primary' : ($cliente->Sexo == 'Femenino' ? 'pink' : 'secondary') }} border-opacity-25 px-3 py-2 rounded-pill">
                                    {{ $cliente->Sexo ?? 'No especificado' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                       class="btn btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar cliente">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            onclick="setDeleteCliente({{ $cliente->id }}, '{{ addslashes($nombreCompleto) }}')"
                                            title="Eliminar cliente">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Fila expandible con detalles del cliente -->
                        <tr class="detalle-cliente-row">
                            <td colspan="6" class="p-0 border-0">
                                <div class="collapse" id="detallesCliente{{ $cliente->id }}">
                                    <div class="card card-body border-0 bg-light bg-gradient rounded-0">
                                        <div class="row">
                                            <!-- Información detallada -->
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-3 text-primary">
                                                    <i class="fas fa-info-circle me-2"></i>Información del Cliente
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card bg-white border-0 shadow-sm h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-muted small mb-3">Información Personal</h6>
                                                                <div class="mb-2">
                                                                    <small class="text-muted d-block">Nombre completo:</small>
                                                                    <span class="fw-semibold">{{ $nombreCompleto }}</span>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <small class="text-muted d-block">ID Cliente:</small>
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary">#{{ $cliente->id }}</span>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">Sexo:</small>
                                                                    <span class="badge bg-{{ $cliente->Sexo == 'Masculino' ? 'primary' : ($cliente->Sexo == 'Femenino' ? 'pink' : 'secondary') }} bg-opacity-10 text-{{ $cliente->Sexo == 'Masculino' ? 'primary' : ($cliente->Sexo == 'Femenino' ? 'pink' : 'secondary') }}">
                                                                        {{ $cliente->Sexo ?? 'No especificado' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card bg-white border-0 shadow-sm h-100">
                                                            <div class="card-body">
                                                                <h6 class="text-muted small mb-3">Información de Contacto</h6>
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Correo electrónico:</small>
                                                                    <a href="mailto:{{ $cliente->Correo }}" class="text-decoration-none">
                                                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                                                        {{ $cliente->Correo }}
                                                                    </a>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">Teléfono:</small>
                                                                    @if($cliente->Telefono)
                                                                    <a href="tel:{{ $cliente->Telefono }}" class="text-decoration-none">
                                                                        <i class="fas fa-phone me-1 text-primary"></i>
                                                                        {{ $cliente->Telefono }}
                                                                    </a>
                                                                    @else
                                                                    <span class="text-muted">No especificado</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card bg-white border-0 shadow-sm">
                                                            <div class="card-body">
                                                                <h6 class="text-muted small mb-3">Dirección</h6>
                                                                @if($cliente->Direccion)
                                                                <p class="mb-0">{{ $cliente->Direccion }}</p>
                                                                @else
                                                                <p class="mb-0 text-muted">No se ha registrado una dirección</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Resumen y acciones -->
                                            <div class="col-md-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="fas fa-chart-pie me-2"></i>Resumen
                                                        </h6>
                                                        
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">ID Cliente:</span>
                                                                <span class="fw-medium">#{{ $cliente->id }}</span>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted">Estado:</span>
                                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                                    Activo
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="mt-4">
                                                            <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                                               class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                                                <i class="fas fa-edit me-1"></i> Editar cliente
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm w-100"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#deleteModal" 
                                                                    onclick="setDeleteCliente({{ $cliente->id }}, '{{ addslashes($nombreCompleto) }}')">
                                                                <i class="fas fa-trash me-1"></i> Eliminar cliente
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-user-times fa-4x text-muted mb-4"></i>
                                    <h4 class="text-muted fw-bold mb-3">No hay clientes registrados</h4>
                                    <p class="text-muted mb-4">
                                        Comienza registrando el primer cliente en el sistema.
                                    </p>
                                    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Registrar Cliente
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando <span id="visibleCount">{{ $clientes->count() }}</span> de {{ $clientes->count() }} cliente(s)
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white position-relative">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                </div>
                <p class="fs-6">
                    Estás a punto de eliminar al cliente <strong id="deleteClienteNombre"></strong>
                </p>
            </div>
            <!-- Reemplaza esta parte del modal -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Rotar la flecha del botón expandir al hacer clic
    document.querySelectorAll('.btn-expand-cliente').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Rotar el icono
            if (icon) {
                if (isExpanded) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(180deg)';
                }
                icon.style.transition = 'transform 0.3s ease';
            }
            
            // Cambiar clase del botón
            if (isExpanded) {
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-secondary');
            } else {
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
            }
        });
    });

    // Cerrar detalles cuando se hace clic en otro botón
    document.querySelectorAll('.btn-expand-cliente').forEach(button => {
        button.addEventListener('click', function(e) {
            const targetId = this.getAttribute('data-bs-target');
            
            // Cerrar otros acordeones abiertos
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                if (collapse.id !== targetId.replace('#', '')) {
                    const collapseButton = document.querySelector(`[data-bs-target="#${collapse.id}"]`);
                    if (collapseButton) {
                        collapseButton.click();
                    }
                }
            });
        });
    });

    // Elementos del filtro
    const searchInput = document.getElementById('searchInput');
    const filterSexo = document.getElementById('filterSexo');
    const sortBy = document.getElementById('sortBy');
    const sortOrder = document.getElementById('sortOrder');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    const clientesRows = document.querySelectorAll('.cliente-row');
    const totalCount = document.getElementById('totalCount');
    const visibleCount = document.getElementById('visibleCount');
    const filterCount = document.getElementById('filterCount');
    const sortDisplay = document.getElementById('sortDisplay');

    // Función para actualizar contador de filtros
    function updateFilterCount() {
        let count = 0;
        if (searchInput.value.trim()) count++;
        if (filterSexo.value) count++;
        filterCount.textContent = count;
    }

    // Función para aplicar filtros
    function applyTableFilters() {
        const searchText = searchInput.value.toLowerCase();
        const sexoValue = filterSexo.value;
        let visibleRows = 0;

        clientesRows.forEach(row => {
            const nombre = row.dataset.nombre;
            const correo = row.dataset.correo;
            const telefono = row.dataset.telefono;
            const sexo = row.dataset.sexo;

            const matchesSearch = searchText === '' || 
                nombre.includes(searchText) || 
                correo.includes(searchText) || 
                telefono.includes(searchText);
            const matchesSexo = sexoValue === '' || sexo === sexoValue;

            if (matchesSearch && matchesSexo) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        visibleCount.textContent = visibleRows;
        updateFilterCount();
        updateSortDisplay();
    }

    // Función para actualizar display de ordenamiento
    function updateSortDisplay() {
        const sortText = sortBy.options[sortBy.selectedIndex].text;
        const orderIcon = sortOrder.value === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down';
        sortDisplay.innerHTML = `${sortText} <i class="fas ${orderIcon} ms-1"></i>`;
    }

    // Función para ordenar la tabla
    function sortTable() {
        const sortColumn = sortBy.value;
        const order = sortOrder.value;

        const rowsArray = Array.from(clientesRows);
        
        rowsArray.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortColumn) {
                case 'Nombre':
                    aValue = a.dataset.nombre;
                    bValue = b.dataset.nombre;
                    break;
                case 'Correo':
                    aValue = a.dataset.correo;
                    bValue = b.dataset.correo;
                    break;
                case 'Telefono':
                    aValue = a.dataset.telefono;
                    bValue = b.dataset.telefono;
                    break;
                default: // ID
                    aValue = parseInt(a.querySelector('td:first-child').textContent.replace('#', ''));
                    bValue = parseInt(b.querySelector('td:first-child').textContent.replace('#', ''));
            }

            if (order === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        // Reordenar filas en la tabla
        const tbody = document.querySelector('#clientesTable tbody');
        rowsArray.forEach(row => {
            tbody.appendChild(row);
            // Mover también la fila expandible correspondiente
            const detallesRow = row.nextElementSibling;
            if (detallesRow && detallesRow.classList.contains('detalle-cliente-row')) {
                tbody.appendChild(detallesRow);
            }
        });
    }

    // Event Listeners
    applyFilters.addEventListener('click', function() {
        applyTableFilters();
        sortTable();
    });

    resetFilters.addEventListener('click', function() {
        searchInput.value = '';
        filterSexo.value = '';
        sortBy.value = 'Nombre';
        sortOrder.value = 'asc';
        applyTableFilters();
        sortTable();
    });

    sortBy.addEventListener('change', sortTable);
    sortOrder.addEventListener('change', sortTable);

    // Aplicar filtros en tiempo real
    searchInput.addEventListener('input', applyTableFilters);
    filterSexo.addEventListener('change', applyTableFilters);

    // Inicializar
    applyTableFilters();
    updateSortDisplay();
});

// CORRECCIÓN: Función para el modal de eliminación - USANDO EL MÉTODO DE PROVEEDORES
function setDeleteCliente(clienteId, nombreCompleto) {
    // Actualizar el nombre en el modal
    document.getElementById('deleteClienteNombre').textContent = nombreCompleto;
    
    // Obtener el formulario
    const deleteForm = document.getElementById('deleteForm');
    
    // FORMA CORRECTA: Usar el mismo método que en proveedores
    // "{{ route('clientes.destroy', ':id') }}" se reemplaza :id con el clienteId
    const actionUrl = "{{ route('clientes.destroy', ':id') }}".replace(':id', clienteId);
    
    // Asignar la acción al formulario
    deleteForm.action = actionUrl;
    
    // Mostrar el modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush
<style>
#clientes-page {
    padding-top: 20px;
}

#clientes-page .cliente-avatar {
    width: 48px;
    height: 48px;
}

#clientes-page .cliente-avatar-md {
    width: 40px;
    height: 40px;
}

#clientes-page .cliente-avatar-sm {
    width: 36px;
    height: 36px;
}

#clientes-page .cliente-avatar-xs {
    width: 24px;
    height: 24px;
    font-size: 0.7rem;
}

#clientes-page .table th { 
    border-top: none; 
    font-weight: 600; 
    font-size: 0.875rem; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    border-bottom: 2px solid #dee2e6;
}

#clientes-page .table tbody tr {
    transition: all 0.2s ease;
}

#clientes-page .table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

#clientes-page .btn-group .btn { 
    border-radius: 0.375rem !important; 
    margin: 0 2px; 
}

#clientes-page .badge { 
    font-size: 0.75rem; 
    font-weight: 500;
}

#clientes-page .card {
    border-radius: 12px;
}

#clientes-page .shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

#clientes-page .fw-semibold {
    font-weight: 600;
}

/* Estilos para el botón expandir */
#clientes-page .btn-expand-cliente {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 6px !important;
}

#clientes-page .btn-expand-cliente:hover {
    transform: scale(1.1);
}

#clientes-page .btn-expand-cliente i {
    transition: transform 0.3s ease;
}

/* Estilos para la fila expandible */
#clientes-page .detalle-cliente-row {
    background-color: #f8fafc;
}

#clientes-page .collapse {
    transition: all 0.3s ease;
}

#clientes-page .collapsing {
    transition: height 0.35s ease;
}

/* Estilos para búsqueda */
#clientes-page .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Colores personalizados */
#clientes-page .text-pink {
    color: #e83e8c !important;
}

#clientes-page .bg-pink {
    background-color: #e83e8c !important;
}

#clientes-page .bg-pink.bg-opacity-10 {
    background-color: rgba(232, 62, 140, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    #clientes-page .btn-expand-cliente {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    #clientes-page .cliente-avatar, 
    #clientes-page .cliente-avatar-md, 
    #clientes-page .cliente-avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    #clientes-page .cliente-avatar-xs {
        width: 20px;
        height: 20px;
        font-size: 0.6rem;
    }
    
    #clientes-page .table-responsive {
        font-size: 0.9rem;
    }
    
    #clientes-page .detalle-cliente-row .row {
        flex-direction: column;
    }
    
    #clientes-page .detalle-cliente-row .col-md-8,
    #clientes-page .detalle-cliente-row .col-md-4 {
        width: 100% !important;
        margin-bottom: 1rem;
    }
    
    #clientes-page .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Filtros responsive */
    #clientes-page .card-body .row > div {
        margin-bottom: 1rem;
    }
}

/* Mejorar la legibilidad de los detalles */
#clientes-page .detalle-cliente-row .card-body {
    padding: 1.5rem;
}

#clientes-page .detalle-cliente-row h6 {
    font-size: 0.95rem;
}

/* Animación suave para expandir */
#clientes-page .collapse.show {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Tooltips para direcciones truncadas */
#clientes-page .text-truncate[data-bs-toggle="tooltip"] {
    cursor: help;
}
</style>
@endsection