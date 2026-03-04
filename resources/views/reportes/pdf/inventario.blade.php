<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
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
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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
            color: #43e97b;
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
            background: #43e97b;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .badge-bajo {
            background: #ef4444;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .badge-medio {
            background: #f59e0b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .badge-alto {
            background: #10b981;
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
            <h1>📦 Reporte de Inventario</h1>
            <p>Generado: {{ $fechaGeneracion }}</p>
        </div>
        
        <div class="content">
            <!-- Cards de resumen -->
            <div class="cards-container">
                <div class="card">
                    <h3>Total Productos</h3>
                    <div class="numero">{{ $totalProductos }}</div>
                </div>
                <div class="card">
                    <h3>Stock Total</h3>
                    <div class="numero">{{ $totalStock }} und</div>
                </div>
                <div class="card">
                    <h3>Valor Inventario</h3>
                    <div class="numero">${{ number_format($totalInventario, 2) }}</div>
                </div>
                <div class="card">
                    <h3>Bajo Stock</h3>
                    <div class="numero">{{ $productosBajoStock->count() }}</div>
                </div>
            </div>
            
            <!-- Gráficas -->
            <div class="graficas-row">
                <!-- Gráfica de Niveles de Stock -->
                <div class="grafica-card">
                    <h3>📊 Niveles de Stock</h3>
                    <div id="graficaNiveles" class="grafica"></div>
                </div>
                
                <!-- Gráfica de Categorías -->
                <div class="grafica-card">
                    <h3>🏷️ Inventario por Categoría</h3>
                    <div id="graficaCategorias" class="grafica"></div>
                </div>
            </div>
            
            <div class="graficas-row">
                <!-- Gráfica de Productos Valiosos -->
                <div class="grafica-card" style="flex: 2;">
                    <h3>💰 Productos más valiosos</h3>
                    <div id="graficaValiosos" class="grafica"></div>
                </div>
            </div>
            
            <!-- Tabla de productos bajo stock -->
            <h3 style="margin: 30px 0 15px;">⚠️ Productos con stock bajo</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productosBajoStock as $producto)
                    <tr>
                        <td><strong>{{ $producto->Nombre }}</strong></td>
                        <td><span class="badge-bajo">{{ $producto->Cantidad }} und</span></td>
                        <td>{{ $producto->Cantidad_minima }} und</td>
                        <td>${{ number_format($producto->Cantidad * $producto->Precio, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">No hay productos con stock bajo</td>
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
        google.charts.load('current', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(drawCharts);
        
        function drawCharts() {
            // Gráfica de Niveles de Stock
            @if(!empty($datosGraficaStockNiveles) && count($datosGraficaStockNiveles) > 0)
                try {
                    var dataNiveles = new google.visualization.DataTable();
                    dataNiveles.addColumn('string', 'Nivel');
                    dataNiveles.addColumn('number', 'Cantidad');
                    
                    @foreach($datosGraficaStockNiveles as $nivel)
                        dataNiveles.addRow(['{{ $nivel['nivel'] }}', {{ $nivel['cantidad'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Distribución por Nivel de Stock',
                        colors: ['#10b981', '#f59e0b', '#ef4444'],
                        height: 300,
                        pieHole: 0.4,
                        legend: { position: 'bottom' }
                    };
                    
                    var chart = new google.visualization.PieChart(document.getElementById('graficaNiveles'));
                    chart.draw(dataNiveles, options);
                } catch (e) {
                    document.getElementById('graficaNiveles').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaNiveles').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica de Categorías
            @if(!empty($datosGraficaCategorias) && count($datosGraficaCategorias) > 0)
                try {
                    var dataCategorias = new google.visualization.DataTable();
                    dataCategorias.addColumn('string', 'Categoría');
                    dataCategorias.addColumn('number', 'Valor');
                    
                    @foreach($datosGraficaCategorias as $categoria)
                        dataCategorias.addRow(['{{ $categoria['nombre'] }}', {{ $categoria['valor'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Valor de Inventario por Categoría',
                        colors: ['#43e97b'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Valor ($)', format: 'currency' },
                        hAxis: { title: 'Categoría', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaCategorias'));
                    chart.draw(dataCategorias, options);
                } catch (e) {
                    document.getElementById('graficaCategorias').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaCategorias').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica de Productos Valiosos
            @if(!empty($datosGraficaProductosValiosos) && count($datosGraficaProductosValiosos) > 0)
                try {
                    var dataValiosos = new google.visualization.DataTable();
                    dataValiosos.addColumn('string', 'Producto');
                    dataValiosos.addColumn('number', 'Valor');
                    
                    @foreach($datosGraficaProductosValiosos as $producto)
                        dataValiosos.addRow(['{{ $producto['nombre'] }}', {{ $producto['valor'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Productos más valiosos',
                        colors: ['#38f9d7'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Valor ($)', format: 'currency' },
                        hAxis: { title: 'Producto', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaValiosos'));
                    chart.draw(dataValiosos, options);
                } catch (e) {
                    document.getElementById('graficaValiosos').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaValiosos').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
        }
    </script>
</body>
</html>