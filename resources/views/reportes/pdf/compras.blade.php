<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Compras</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .reporte-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px 40px;
        }
        
        .header h1 {
            font-size: 2.2em;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 30px;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card .numero {
            font-size: 1.8em;
            font-weight: bold;
            color: #f5576c;
            margin: 10px 0;
        }
        
        .graficas-row {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .grafica-card {
            flex: 1;
            min-width: 400px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .grafica-card h3 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        
        .grafica {
            width: 100%;
            height: 300px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background: #f5576c;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .badge {
            background: #f093fb;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            border-top: 1px solid #e0e0e0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="reporte-container">
        <div class="header">
            <h1>📦 Reporte de Compras</h1>
            <p>Período: {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
            <p style="font-size: 0.9em;">Generado: {{ $fechaGeneracion }}</p>
        </div>
        
        <div class="content">
            <!-- Cards de resumen -->
            <div class="cards-container">
                <div class="card">
                    <h3>Total Compras</h3>
                    <div class="numero">{{ $totalCompras }}</div>
                </div>
                <div class="card">
                    <h3>Total Egresos</h3>
                    <div class="numero">${{ number_format($totalEgresos, 2) }}</div>
                </div>
                <div class="card">
                    <h3>Compra Promedio</h3>
                    <div class="numero">${{ number_format($compraPromedio, 2) }}</div>
                </div>
                <div class="card">
                    <h3>Proveedores</h3>
                    <div class="numero">{{ $totalProveedores }}</div>
                </div>
            </div>
            
            <!-- Gráficas -->
            <div class="graficas-row">
                <!-- Gráfica de Proveedores -->
                <div class="grafica-card">
                    <h3>🏢 Compras por Proveedor</h3>
                    <div id="graficaProveedores" class="grafica"></div>
                </div>
                
                <!-- Gráfica de Productos Comprados -->
                <div class="grafica-card">
                    <h3>📦 Productos más comprados</h3>
                    <div id="graficaProductos" class="grafica"></div>
                </div>
            </div>
            
            <div class="graficas-row">
                <!-- Gráfica de Compras por Día -->
                <div class="grafica-card" style="flex: 2;">
                    <h3>📉 Tendencia de Compras Diarias</h3>
                    <div id="graficaDiaria" class="grafica"></div>
                </div>
            </div>
            
            <!-- Tabla de productos más comprados -->
            <h3 style="margin: 30px 0 15px;">🔥 Productos más comprados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo Total</th>
                        <th>Veces comprado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productosMasComprados as $item)
                    <tr>
                        <td><strong>{{ $item->producto->Nombre ?? 'Producto' }}</strong></td>
                        <td><span class="badge">{{ $item->total_comprado }} und</span></td>
                        <td>${{ number_format($item->costo_total ?? 0, 2) }}</td>
                        <td>{{ $item->veces_comprado }} veces</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">No hay compras en este período</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="footer">
                <p>© {{ date('Y') }} - Sistema de Gestión</p>
            </div>
        </div>
    </div>
    
    <script>
        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(drawCharts);
        
        function drawCharts() {
            // Gráfica de Proveedores
            @if(!empty($datosGraficaProveedores) && count($datosGraficaProveedores) > 0)
                try {
                    var dataProveedores = new google.visualization.DataTable();
                    dataProveedores.addColumn('string', 'Proveedor');
                    dataProveedores.addColumn('number', 'Monto');
                    
                    @foreach($datosGraficaProveedores as $proveedor)
                        dataProveedores.addRow(['{{ $proveedor['nombre'] }}', {{ $proveedor['monto'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Compras por Proveedor',
                        colors: ['#f093fb'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Monto ($)', format: 'currency' },
                        hAxis: { title: 'Proveedor', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaProveedores'));
                    chart.draw(dataProveedores, options);
                } catch (e) {
                    document.getElementById('graficaProveedores').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaProveedores').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica de Productos
            @if(!empty($datosGraficaProductosComprados) && count($datosGraficaProductosComprados) > 0)
                try {
                    var dataProductos = new google.visualization.DataTable();
                    dataProductos.addColumn('string', 'Producto');
                    dataProductos.addColumn('number', 'Cantidad');
                    
                    @foreach($datosGraficaProductosComprados as $producto)
                        dataProductos.addRow(['{{ $producto['nombre'] }}', {{ $producto['cantidad'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Productos más comprados',
                        colors: ['#f5576c'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Cantidad' },
                        hAxis: { title: 'Producto', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaProductos'));
                    chart.draw(dataProductos, options);
                } catch (e) {
                    document.getElementById('graficaProductos').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaProductos').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica Diaria
            @if(!empty($datosGraficaComprasDiarias) && count($datosGraficaComprasDiarias) > 0)
                try {
                    var dataDiaria = new google.visualization.DataTable();
                    dataDiaria.addColumn('string', 'Fecha');
                    dataDiaria.addColumn('number', 'Compras');
                    
                    @foreach($datosGraficaComprasDiarias as $dia)
                        dataDiaria.addRow(['{{ $dia['fecha'] }}', {{ $dia['total'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Compras por Día',
                        colors: ['#f093fb'],
                        legend: { position: 'none' },
                        curveType: 'function',
                        vAxis: { title: 'Monto ($)', format: 'currency' },
                        hAxis: { title: 'Fecha' },
                        height: 300,
                        pointSize: 5
                    };
                    
                    var chart = new google.visualization.LineChart(document.getElementById('graficaDiaria'));
                    chart.draw(dataDiaria, options);
                } catch (e) {
                    document.getElementById('graficaDiaria').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaDiaria').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
        }
    </script>
</body>
</html>