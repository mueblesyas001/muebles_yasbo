<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
        }
        
        .header h1 {
            font-size: 2.2em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
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
            color: #667eea;
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
            font-size: 1.2em;
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
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .badge {
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
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="reporte-container">
        <div class="header">
            <h1>📊 Reporte de Ventas</h1>
            <p>Período: {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
            <p style="font-size: 0.9em;">Generado: {{ $fechaGeneracion }}</p>
        </div>
        
        <div class="content">
            <!-- Cards de resumen -->
            <div class="cards-container">
                <div class="card">
                    <h3>Total Ventas</h3>
                    <div class="numero">{{ $totalVentas }}</div>
                </div>
                <div class="card">
                    <h3>Total Ingresos</h3>
                    <div class="numero">${{ number_format($totalIngresos, 2) }}</div>
                </div>
                <div class="card">
                    <h3>Ticket Promedio</h3>
                    <div class="numero">${{ number_format($ventaPromedio, 2) }}</div>
                </div>
                <div class="card">
                    <h3>Días con Ventas</h3>
                    <div class="numero">{{ $resumenEjecutivo['dias_con_ventas'] ?? 0 }}</div>
                </div>
            </div>
            
            <!-- Gráficas -->
            <div class="graficas-row">
                <!-- Gráfica de Empleados -->
                <div class="grafica-card">
                    <h3>👥 Ventas por Vendedor</h3>
                    <div id="graficaEmpleados" class="grafica"></div>
                </div>
                
                <!-- Gráfica de Productos -->
                <div class="grafica-card">
                    <h3>🏆 Productos más vendidos</h3>
                    <div id="graficaProductos" class="grafica"></div>
                </div>
            </div>
            
            <div class="graficas-row">
                <!-- Gráfica de Ventas por Día -->
                <div class="grafica-card" style="flex: 2;">
                    <h3>📈 Tendencia de Ventas Diarias</h3>
                    <div id="graficaDiaria" class="grafica"></div>
                </div>
            </div>
            
            <!-- Tabla de productos más vendidos -->
            <h3 style="margin: 30px 0 15px;">🔥 Productos más vendidos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Ingresos</th>
                        <th>% del total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productosMasVendidos as $item)
                    @php
                        $porcentaje = $totalIngresos > 0 ? ($item->total_ingresos / $totalIngresos) * 100 : 0;
                    @endphp
                    <tr>
                        <td><strong>{{ $item->producto->Nombre ?? 'Producto' }}</strong></td>
                        <td><span class="badge">{{ $item->total_vendido }} und</span></td>
                        <td class="text-right">${{ number_format($item->total_ingresos, 2) }}</td>
                        <td class="text-right">{{ number_format($porcentaje, 1) }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">No hay productos vendidos en este período</td>
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
        // Cargar Google Charts
        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(drawCharts);
        
        function drawCharts() {
            // 1. GRÁFICA DE EMPLEADOS
            @if(!empty($datosGraficaEmpleados) && count($datosGraficaEmpleados) > 0)
                try {
                    var dataEmpleados = new google.visualization.DataTable();
                    dataEmpleados.addColumn('string', 'Vendedor');
                    dataEmpleados.addColumn('number', 'Ventas');
                    
                    @foreach($datosGraficaEmpleados as $empleado)
                        dataEmpleados.addRow(['{{ $empleado['nombre'] }}', {{ $empleado['total'] }}]);
                    @endforeach
                    
                    var optionsEmpleados = {
                        title: 'Ventas por Vendedor',
                        colors: ['#667eea'],
                        legend: { position: 'none' },
                        bars: 'vertical',
                        vAxis: { 
                            title: 'Monto ($)',
                            format: 'currency'
                        },
                        hAxis: { 
                            title: 'Vendedor',
                            slantedText: true,
                            slantedTextAngle: 30
                        },
                        height: 300
                    };
                    
                    var chartEmpleados = new google.visualization.ColumnChart(document.getElementById('graficaEmpleados'));
                    chartEmpleados.draw(dataEmpleados, optionsEmpleados);
                } catch (e) {
                    console.error('Error empleados:', e);
                    document.getElementById('graficaEmpleados').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error al cargar gráfica</p>';
                }
            @else
                document.getElementById('graficaEmpleados').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">No hay datos de ventas por empleado</p>';
            @endif
            
            // 2. GRÁFICA DE PRODUCTOS
            @if(!empty($datosGraficaProductos) && count($datosGraficaProductos) > 0)
                try {
                    var dataProductos = new google.visualization.DataTable();
                    dataProductos.addColumn('string', 'Producto');
                    dataProductos.addColumn('number', 'Cantidad');
                    
                    @foreach($datosGraficaProductos as $producto)
                        dataProductos.addRow(['{{ $producto['nombre'] }}', {{ $producto['cantidad'] }}]);
                    @endforeach
                    
                    var optionsProductos = {
                        title: 'Productos más vendidos',
                        colors: ['#ff9f40'],
                        legend: { position: 'none' },
                        bars: 'vertical',
                        vAxis: { 
                            title: 'Cantidad',
                            format: '0'
                        },
                        hAxis: { 
                            title: 'Producto',
                            slantedText: true,
                            slantedTextAngle: 30
                        },
                        height: 300
                    };
                    
                    var chartProductos = new google.visualization.ColumnChart(document.getElementById('graficaProductos'));
                    chartProductos.draw(dataProductos, optionsProductos);
                } catch (e) {
                    console.error('Error productos:', e);
                    document.getElementById('graficaProductos').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error al cargar gráfica</p>';
                }
            @else
                document.getElementById('graficaProductos').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">No hay datos de productos vendidos</p>';
            @endif
            
            // 3. GRÁFICA DIARIA
            @if(!empty($datosGraficaDiaria) && count($datosGraficaDiaria) > 0)
                try {
                    var dataDiaria = new google.visualization.DataTable();
                    dataDiaria.addColumn('string', 'Fecha');
                    dataDiaria.addColumn('number', 'Ventas');
                    
                    @foreach($datosGraficaDiaria as $dia)
                        dataDiaria.addRow(['{{ $dia['fecha'] }}', {{ $dia['total'] }}]);
                    @endforeach
                    
                    var optionsDiaria = {
                        title: 'Ventas por Día',
                        colors: ['#10b981'],
                        legend: { position: 'none' },
                        curveType: 'function',
                        vAxis: { 
                            title: 'Monto ($)',
                            format: 'currency'
                        },
                        hAxis: { 
                            title: 'Fecha',
                            slantedText: true,
                            slantedTextAngle: 30
                        },
                        height: 300,
                        pointSize: 5
                    };
                    
                    var chartDiaria = new google.visualization.LineChart(document.getElementById('graficaDiaria'));
                    chartDiaria.draw(dataDiaria, optionsDiaria);
                } catch (e) {
                    console.error('Error diaria:', e);
                    document.getElementById('graficaDiaria').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error al cargar gráfica</p>';
                }
            @else
                document.getElementById('graficaDiaria').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">No hay datos de ventas diarias</p>';
            @endif
        }
    </script>
</body>
</html>