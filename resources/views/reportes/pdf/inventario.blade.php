<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #2c3e50;
            background: #ffffff;
            margin: 0;
            padding: 15px;
        }
        
        .reporte {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e0e0e0;
        }
        
        .header {
            background: #27ae60;
            color: white;
            padding: 20px 25px;
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .header-info {
            display: flex;
            gap: 25px;
            font-size: 11px;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            padding: 20px 25px;
            background: white;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid;
        }
        
        .stat-card:nth-child(1) { border-left-color: #27ae60; }
        .stat-card:nth-child(2) { border-left-color: #3498db; }
        .stat-card:nth-child(3) { border-left-color: #f39c12; }
        .stat-card:nth-child(4) { border-left-color: #e74c3c; }
        
        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .stat-desc {
            font-size: 10px;
            color: #95a5a6;
        }
        
        .section {
            padding: 20px 25px;
            border-top: 1px solid #ecf0f1;
        }
        
        .section-title {
            margin-bottom: 15px;
        }
        
        .section-title h2 {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            padding-bottom: 8px;
            border-bottom: 2px solid #27ae60;
            display: inline-block;
        }
        
        .section-badge {
            background: #ecf0f1;
            color: #7f8c8d;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            margin-left: 10px;
        }
        
        .graficas-section {
            padding: 20px 25px;
            border-top: 1px solid #ecf0f1;
            page-break-inside: avoid;
        }
        
        .graficas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .grafica-card {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            border: 1px solid #e9ecef;
        }
        
        .grafica-header h3 {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
        }
        
        .grafica-container {
            text-align: center;
            min-height: 200px;
        }
        
        .grafica-container img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        
        .table-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        
        th {
            background: #f1f3f5;
            color: #2c3e50;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .rank {
            display: inline-block;
            width: 22px;
            height: 22px;
            background: #27ae60;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 22px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .rank-1 { background: #f1c40f; color: #2c3e50; }
        .rank-2 { background: #bdc3c7; }
        .rank-3 { background: #e67e22; }
        
        .product-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .stock-bajo {
            color: #e74c3c;
            font-weight: 600;
        }
        
        .stock-normal {
            color: #27ae60;
            font-weight: 600;
        }
        
        .amount {
            font-weight: 600;
            color: #27ae60;
        }
        
        .alertas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 0 25px 20px;
        }
        
        .alerta-card {
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid;
        }
        
        .alerta-card.saludable {
            background: #e8f5e9;
            border-left-color: #27ae60;
        }
        
        .alerta-card.bajo {
            background: #fff3e0;
            border-left-color: #f39c12;
        }
        
        .alerta-card.agotado {
            background: #fdeded;
            border-left-color: #e74c3c;
        }
        
        .alerta-titulo {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .alerta-numero {
            font-size: 20px;
            font-weight: 700;
        }
        
        .featured-card {
            background: #f8f9fa;
            border: 1px solid #27ae60;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 0 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .featured-badge {
            background: #27ae60;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        
        .footer {
            background: #f1f3f5;
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #7f8c8d;
            border-top: 1px solid #dee2e6;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .keep-together {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="reporte">
        <div class="header">
            <h1>REPORTE DE INVENTARIO</h1>
            <div class="header-info">
                <div>
                    <strong>Generado:</strong> {{ $fechaGeneracion }}
                </div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Productos</div>
                <div class="stat-value">{{ $totalProductos }}</div>
                <div class="stat-desc">Productos registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Stock</div>
                <div class="stat-value">{{ number_format($totalStock) }}</div>
                <div class="stat-desc">Unidades en inventario</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Valor Inventario</div>
                <div class="stat-value">${{ number_format($totalInventario, 2) }}</div>
                <div class="stat-desc">Valor total</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Stock Bajo</div>
                <div class="stat-value">{{ $productosBajoStock->count() }}</div>
                <div class="stat-desc">Productos por reabastecer</div>
            </div>
        </div>
        
        <div class="alertas-grid">
            <div class="alerta-card saludable">
                <div class="alerta-titulo">Stock Saludable</div>
                <div class="alerta-numero">{{ $productosStockSaludable->count() }}</div>
                <div style="font-size: 11px;">{{ number_format($porcentajeSaludable, 1) }}% del total</div>
            </div>
            <div class="alerta-card bajo">
                <div class="alerta-titulo">Stock Bajo</div>
                <div class="alerta-numero">{{ $productosBajoStock->count() }}</div>
                <div style="font-size: 11px;">{{ number_format($porcentajeBajoStock, 1) }}% del total</div>
            </div>
            <div class="alerta-card agotado">
                <div class="alerta-titulo">Sin Stock</div>
                <div class="alerta-numero">{{ $productosSinStock->count() }}</div>
                <div style="font-size: 11px;">{{ number_format($porcentajeSinStock, 1) }}% del total</div>
            </div>
            <div class="alerta-card saludable">
                <div class="alerta-titulo">Categorías</div>
                <div class="alerta-numero">{{ $inventarioPorCategoria->count() }}</div>
                <div style="font-size: 11px;">Categorías con productos</div>
            </div>
        </div>
        
        @if(!empty($graficas))
        <div class="graficas-section">
            <div class="section-title">
                <h2>ANÁLISIS GRÁFICO</h2>
            </div>
            
            <div class="graficas-grid">
                @if(isset($graficas['niveles']))
                <div class="grafica-card">
                    <div class="grafica-header">
                        <h3>Niveles de Stock</h3>
                    </div>
                    <div class="grafica-container">
                        <img src="{{ $graficas['niveles'] }}" alt="Niveles de Stock">
                    </div>
                </div>
                @endif

                @if(isset($graficas['categorias']))
                <div class="grafica-card">
                    <div class="grafica-header">
                        <h3>Valor por Categoría</h3>
                    </div>
                    <div class="grafica-container">
                        <img src="{{ $graficas['categorias'] }}" alt="Valor por Categoría">
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="page-break"></div>
        
        @if($productosBajoStock->isNotEmpty())
        <div class="section keep-together">
            <div class="section-title">
                <h2>PRODUCTOS CON STOCK BAJO</h2>
                <span class="section-badge">Necesitan reabastecimiento</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Faltante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productosBajoStock as $index => $producto)
                        @php $faltante = $producto->Cantidad_minima - $producto->Cantidad; @endphp
                        <tr>
                            <td><span class="rank">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $producto->Nombre }}</span>
                                <div class="category">{{ $producto->categoria->Nombre ?? 'Sin categoría' }}</div>
                            </td>
                            <td class="stock-bajo">{{ $producto->Cantidad }} und.</td>
                            <td>{{ $producto->Cantidad_minima }} und.</td>
                            <td class="stock-bajo">-{{ $faltante }} und.</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <div class="section keep-together">
            <div class="section-title">
                <h2>PRODUCTOS MÁS VALIOSOS</h2>
                <span class="section-badge">Top {{ $productosMasValiosos->count() }}</span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Precio Unit.</th>
                            <th>Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosMasValiosos as $index => $producto)
                        @php
                            $valorTotal = $producto->Cantidad * $producto->Precio;
                            $rankClass = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : ''));
                        @endphp
                        <tr>
                            <td><span class="rank {{ $rankClass }}">{{ $index + 1 }}</span></td>
                            <td>
                                <span class="product-name">{{ $producto->Nombre }}</span>
                                <div class="category">{{ $producto->categoria->Nombre ?? 'Sin categoría' }}</div>
                            </td>
                            <td>{{ number_format($producto->Cantidad) }} und.</td>
                            <td>${{ number_format($producto->Precio, 2) }}</td>
                            <td class="amount">${{ number_format($valorTotal, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No hay productos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($productosMasValiosos->isNotEmpty())
        @php $top = $productosMasValiosos->first(); @endphp
        <div class="featured-card">
            <div class="featured-info">
                <h4>PRODUCTO MÁS VALIOSO</h4>
                <p>{{ $top->Nombre }}</p>
            </div>
            <div class="featured-badge">
                ${{ number_format($top->Cantidad * $top->Precio, 2) }}
            </div>
        </div>
        @endif
        
        <div class="footer">
            <div class="footer-info">
                <span>Reporte de Inventario</span>
                <span>{{ $fechaGeneracion }}</span>
            </div>
            <div>{{ $totalProductos }} productos • {{ $totalStock }} unidades</div>
        </div>
    </div>
</body>
</html>