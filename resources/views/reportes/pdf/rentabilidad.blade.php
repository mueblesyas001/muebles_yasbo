<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas por Vendedor</title>
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
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
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
            color: #a8edea;
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
            background: #a8edea;
            color: #333;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .badge {
            background: #a8edea;
            color: #333;
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
            <h1>👥 Reporte de Ventas por Vendedor</h1>
            <p>Período: {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
            <p style="font-size: 0.9em;">Generado: {{ $fechaGeneracion }}</p>
        </div>
        
        <div class="content">
            <!-- Cards de resumen -->
            <div class="cards-container">
                <div class="card">
                    <h3>Total Vendedores</h3>
                    <div class="numero">{{ $totalVendedores }}</div>
                </div>
                <div class="card">
                    <h3>Ventas Totales</h3>
                    <div class="numero">${{ number_format($totalGeneralVentas, 2) }}</div>
                </div>
                <div class="card">
                    <h3>Productos Vendidos</h3>
                    <div class="numero">{{ $totalGeneralProductos }} und</div>
                </div>
                <div class="card">
                    <h3>Promedio x Vendedor</h3>
                    <div class="numero">${{ number_format($promedioPorVendedor, 2) }}</div>
                </div>
            </div>
            
            <!-- Gráficas -->
            <div class="graficas-row">
                <!-- Gráfica de Ventas por Vendedor -->
                <div class="grafica-card">
                    <h3>💰 Ventas por Vendedor</h3>
                    <div id="graficaVentas" class="grafica"></div>
                </div>
                
                <!-- Gráfica de Productos Vendidos -->
                <div class="grafica-card">
                    <h3>📦 Productos vendidos</h3>
                    <div id="graficaProductos" class="grafica"></div>
                </div>
            </div>
            
            <div class="graficas-row">
                <!-- Gráfica de Ticket Promedio -->
                <div class="grafica-card" style="flex: 2;">
                    <h3>🎫 Ticket Promedio por Vendedor</h3>
                    <div id="graficaTickets" class="grafica"></div>
                </div>
            </div>
            
            <!-- Tabla de rendimiento por vendedor -->
            <h3 style="margin: 30px 0 15px;">📊 Rendimiento por Vendedor</h3>
            <table>
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Ventas</th>
                        <th>Productos</th>
                        <th>Ticket Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datosVendedores as $item)
                    <tr>
                        <td><strong>{{ $item['nombre_completo'] }}</strong></td>
                        <td>${{ number_format($item['total_ventas'], 2) }}</td>
                        <td><span class="badge">{{ $item['total_productos'] }} und</span></td>
                        <td>${{ number_format($item['ticket_promedio'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">No hay datos de vendedores</td>
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
            // Gráfica de Ventas
            @if(!empty($datosGraficaVentas) && count($datosGraficaVentas) > 0)
                try {
                    var dataVentas = new google.visualization.DataTable();
                    dataVentas.addColumn('string', 'Vendedor');
                    dataVentas.addColumn('number', 'Ventas');
                    
                    @foreach($datosGraficaVentas as $vendedor)
                        dataVentas.addRow(['{{ $vendedor['nombre'] }}', {{ $vendedor['total'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Ventas por Vendedor',
                        colors: ['#a8edea'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Ventas ($)', format: 'currency' },
                        hAxis: { title: 'Vendedor', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaVentas'));
                    chart.draw(dataVentas, options);
                } catch (e) {
                    document.getElementById('graficaVentas').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaVentas').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica de Productos
            @if(!empty($datosGraficaProductosVendidos) && count($datosGraficaProductosVendidos) > 0)
                try {
                    var dataProductos = new google.visualization.DataTable();
                    dataProductos.addColumn('string', 'Vendedor');
                    dataProductos.addColumn('number', 'Productos');
                    
                    @foreach($datosGraficaProductosVendidos as $vendedor)
                        dataProductos.addRow(['{{ $vendedor['nombre'] }}', {{ $vendedor['total'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Productos vendidos por Vendedor',
                        colors: ['#fed6e3'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Cantidad' },
                        hAxis: { title: 'Vendedor', slantedText: true },
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
            
            // Gráfica de Tickets
            @if(!empty($datosGraficaTickets) && count($datosGraficaTickets) > 0)
                try {
                    var dataTickets = new google.visualization.DataTable();
                    dataTickets.addColumn('string', 'Vendedor');
                    dataTickets.addColumn('number', 'Ticket');
                    
                    @foreach($datosGraficaTickets as $vendedor)
                        dataTickets.addRow(['{{ $vendedor['nombre'] }}', {{ $vendedor['total'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Ticket Promedio por Vendedor',
                        colors: ['#a8edea'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Ticket ($)', format: 'currency' },
                        hAxis: { title: 'Vendedor', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaTickets'));
                    chart.draw(dataTickets, options);
                } catch (e) {
                    document.getElementById('graficaTickets').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaTickets').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
        }
    </script>
</body>
</html>