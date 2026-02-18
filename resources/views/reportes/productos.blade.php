@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">📦 Reporte de Productos</h1>
                <div>
                    <button class="btn btn-primary" onclick="generarPDF()">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Generar PDF
                    </button>
                    <button class="btn btn-success" onclick="exportarExcel()">
                        <i class="bi bi-file-earmark-excel me-1"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filtroForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Ordenar por:</label>
                    <select class="form-select" id="ordenar">
                        <option value="nombre">Nombre</option>
                        <option value="stock">Stock</option>
                        <option value="ganancia">Ganancia</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mostrar:</label>
                    <select class="form-select" id="mostrar">
                        <option value="todos">Todos los productos</option>
                        <option value="stock_bajo">Stock bajo (< 10)</option>
                        <option value="sin_stock">Sin stock</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-primary me-2" onclick="filtrarProductos()">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFiltros()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Productos</h5>
                    <h2 class="mb-0">{{ $totalProductos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Stock Total</h5>
                    <h2 class="mb-0">{{ number_format($totalStock, 0) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Valor Inventario</h5>
                    <h2 class="mb-0">${{ number_format($totalValorInventario, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Ganancia Potencial</h5>
                    <h2 class="mb-0">${{ number_format($totalGananciaPotencial, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tablaProductos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end">Precio Compra</th>
                            <th class="text-end">Precio Venta</th>
                            <th class="text-end">Ganancia/Unidad</th>
                            <th class="text-end">Valor Inventario</th>
                            <th class="text-end">Ganancia Potencial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto['id'] }}</td>
                            <td>
                                <strong>{{ $producto['nombre'] }}</strong>
                                @if($producto['stock'] == 0)
                                <span class="badge bg-danger">Agotado</span>
                                @elseif($producto['stock'] < 10)
                                <span class="badge bg-warning">Stock Bajo</span>
                                @endif
                            </td>
                            <td>{{ $producto['categoria'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $producto['stock'] > 20 ? 'success' : ($producto['stock'] > 5 ? 'warning' : 'danger') }}">
                                    {{ $producto['stock'] }}
                                </span>
                            </td>
                            <td class="text-end">${{ number_format($producto['precio_compra'], 2) }}</td>
                            <td class="text-end">${{ number_format($producto['precio_venta'], 2) }}</td>
                            <td class="text-end">
                                <span class="badge bg-success">${{ number_format($producto['ganancia_unidad'], 2) }}</span>
                            </td>
                            <td class="text-end">${{ number_format($producto['valor_inventario'], 2) }}</td>
                            <td class="text-end">
                                <span class="badge bg-primary">${{ number_format($producto['ganancia_total'], 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="3"><strong>TOTALES</strong></td>
                            <td class="text-center"><strong>{{ $totalStock }}</strong></td>
                            <td colspan="3"></td>
                            <td class="text-end"><strong>${{ number_format($totalValorInventario, 2) }}</strong></td>
                            <td class="text-end"><strong>${{ number_format($totalGananciaPotencial, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Título
    doc.setFontSize(20);
    doc.text('Reporte de Productos', 14, 15);
    doc.setFontSize(10);
    doc.text(`Generado: ${new Date().toLocaleDateString()}`, 14, 22);
    
    // Datos
    const headers = [['ID', 'Producto', 'Categoría', 'Stock', 'P. Compra', 'P. Venta', 'Ganancia/Unid', 'Valor Inv.', 'Ganancia Pot.']];
    const data = @json($productos).map(p => [
        p.id,
        p.nombre.substring(0, 20),
        p.categoria.substring(0, 15),
        p.stock,
        `$${p.precio_compra.toFixed(2)}`,
        `$${p.precio_venta.toFixed(2)}`,
        `$${p.ganancia_unidad.toFixed(2)}`,
        `$${p.valor_inventario.toFixed(2)}`,
        `$${p.ganancia_total.toFixed(2)}`
    ]);
    
    doc.autoTable({
        head: headers,
        body: data,
        startY: 30,
        theme: 'grid',
        styles: { fontSize: 8 },
        headStyles: { fillColor: [41, 128, 185] }
    });
    
    // Totales
    const finalY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(10);
    doc.text(`Total Productos: ${@json($totalProductos)}`, 14, finalY);
    doc.text(`Total Stock: ${@json($totalStock)}`, 14, finalY + 7);
    doc.text(`Valor Total Inventario: $${@json($totalValorInventario).toFixed(2)}`, 14, finalY + 14);
    doc.text(`Ganancia Potencial Total: $${@json($totalGananciaPotencial).toFixed(2)}`, 14, finalY + 21);
    
    // Guardar PDF
    doc.save(`reporte_productos_${new Date().toISOString().slice(0,10)}.pdf`);
}

function exportarExcel() {
    // Crear tabla HTML temporal para exportar
    let tableHtml = '<table border="1"><thead><tr>';
    const headers = ['ID', 'Producto', 'Categoría', 'Stock', 'Precio Compra', 'Precio Venta', 'Ganancia/Unidad', 'Valor Inventario', 'Ganancia Potencial'];
    
    headers.forEach(header => {
        tableHtml += `<th>${header}</th>`;
    });
    tableHtml += '</tr></thead><tbody>';
    
    @foreach($productos as $producto)
    tableHtml += `<tr>
        <td>{{ $producto['id'] }}</td>
        <td>{{ $producto['nombre'] }}</td>
        <td>{{ $producto['categoria'] }}</td>
        <td>{{ $producto['stock'] }}</td>
        <td>{{ number_format($producto['precio_compra'], 2) }}</td>
        <td>{{ number_format($producto['precio_venta'], 2) }}</td>
        <td>{{ number_format($producto['ganancia_unidad'], 2) }}</td>
        <td>{{ number_format($producto['valor_inventario'], 2) }}</td>
        <td>{{ number_format($producto['ganancia_total'], 2) }}</td>
    </tr>`;
    @endforeach
    
    tableHtml += `<tr>
        <td colspan="3"><strong>TOTALES</strong></td>
        <td><strong>{{ $totalStock }}</strong></td>
        <td colspan="3"></td>
        <td><strong>{{ number_format($totalValorInventario, 2) }}</strong></td>
        <td><strong>{{ number_format($totalGananciaPotencial, 2) }}</strong></td>
    </tr>`;
    
    tableHtml += '</tbody></table>';
    
    // Crear y descargar archivo
    const blob = new Blob([tableHtml], { type: 'application/vnd.ms-excel' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `reporte_productos_${new Date().toISOString().slice(0,10)}.xls`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Funciones de filtrado (opcionales)
function filtrarProductos() {
    const ordenar = document.getElementById('ordenar').value;
    const mostrar = document.getElementById('mostrar').value;
    
    // Aquí puedes implementar lógica de filtrado con AJAX si lo necesitas
    alert('Funcionalidad de filtrado pendiente de implementar');
}

function resetFiltros() {
    document.getElementById('ordenar').value = 'nombre';
    document.getElementById('mostrar').value = 'todos';
}
</script>
@endpush
@endsection