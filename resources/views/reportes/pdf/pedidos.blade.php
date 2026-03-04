<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pedidos</title>
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
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
            color: #4facfe;
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
            background: #4facfe;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .badge-pendiente {
            background: #f59e0b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .badge-proceso {
            background: #3b82f6;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .badge-entregado {
            background: #10b981;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .badge-cancelado {
            background: #ef4444;
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
            <h1>📋 Reporte de Pedidos</h1>
            <p>Período: {{ date('d/m/Y', strtotime($fechaInicio)) }} - {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
            <p style="font-size: 0.9em;">Generado: {{ $fechaGeneracion }}</p>
        </div>
        
        <div class="content">
            <!-- Cards de resumen -->
            <div class="cards-container">
                <div class="card">
                    <h3>Total Pedidos</h3>
                    <div class="numero">{{ $totalPedidos }}</div>
                </div>
                <div class="card">
                    <h3>Pendientes</h3>
                    <div class="numero">{{ $pedidosPendientes }}</div>
                </div>
                <div class="card">
                    <h3>Entregados</h3>
                    <div class="numero">{{ $pedidosEntregados }}</div>
                </div>
                <div class="card">
                    <h3>Cancelados</h3>
                    <div class="numero">{{ $pedidosCancelados }}</div>
                </div>
            </div>
            
            <!-- Gráficas -->
            <div class="graficas-row">
                <!-- Gráfica de Estados -->
                <div class="grafica-card">
                    <h3>🔄 Estado de Pedidos</h3>
                    <div id="graficaEstados" class="grafica"></div>
                </div>
                
                <!-- Gráfica de Vendedores -->
                <div class="grafica-card">
                    <h3>👥 Pedidos por Vendedor</h3>
                    <div id="graficaVendedores" class="grafica"></div>
                </div>
            </div>
            
            <div class="graficas-row">
                <!-- Gráfica de Productos -->
                <div class="grafica-card" style="flex: 2;">
                    <h3>📦 Productos más pedidos</h3>
                    <div id="graficaProductos" class="grafica"></div>
                </div>
            </div>
            
            <!-- Tabla de productos más pedidos -->
            <h3 style="margin: 30px 0 15px;">🔥 Productos más pedidos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Veces pedido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productosMasPedidos as $item)
                    <tr>
                        <td><strong>{{ $item->producto->Nombre ?? 'Producto' }}</strong></td>
                        <td><span class="badge-entregado">{{ $item->total_cantidad }} und</span></td>
                        <td>{{ $item->veces_pedido }} veces</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">No hay pedidos en este período</td>
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
            // Gráfica de Estados (Pastel)
            @if(!empty($datosGraficaEstados) && count($datosGraficaEstados) > 0)
                try {
                    var dataEstados = new google.visualization.DataTable();
                    dataEstados.addColumn('string', 'Estado');
                    dataEstados.addColumn('number', 'Cantidad');
                    
                    @foreach($datosGraficaEstados as $estado)
                        dataEstados.addRow(['{{ $estado['estado'] }}', {{ $estado['cantidad'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Distribución de Estados',
                        colors: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                        height: 300,
                        pieHole: 0.4,
                        legend: { position: 'bottom' }
                    };
                    
                    var chart = new google.visualization.PieChart(document.getElementById('graficaEstados'));
                    chart.draw(dataEstados, options);
                } catch (e) {
                    document.getElementById('graficaEstados').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaEstados').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica de Vendedores
            @if(!empty($datosGraficaVendedores) && count($datosGraficaVendedores) > 0)
                try {
                    var dataVendedores = new google.visualization.DataTable();
                    dataVendedores.addColumn('string', 'Vendedor');
                    dataVendedores.addColumn('number', 'Pedidos');
                    
                    @foreach($datosGraficaVendedores as $vendedor)
                        dataVendedores.addRow(['{{ $vendedor['nombre'] }}', {{ $vendedor['total'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Pedidos por Vendedor',
                        colors: ['#4facfe'],
                        legend: { position: 'none' },
                        vAxis: { title: 'Cantidad' },
                        hAxis: { title: 'Vendedor', slantedText: true },
                        height: 300
                    };
                    
                    var chart = new google.visualization.ColumnChart(document.getElementById('graficaVendedores'));
                    chart.draw(dataVendedores, options);
                } catch (e) {
                    document.getElementById('graficaVendedores').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Error</p>';
                }
            @else
                document.getElementById('graficaVendedores').innerHTML = '<p style="text-align: center; color: #999; padding: 50px;">Sin datos</p>';
            @endif
            
            // Gráfica de Productos
            @if(!empty($datosGraficaProductosPedidos) && count($datosGraficaProductosPedidos) > 0)
                try {
                    var dataProductos = new google.visualization.DataTable();
                    dataProductos.addColumn('string', 'Producto');
                    dataProductos.addColumn('number', 'Cantidad');
                    
                    @foreach($datosGraficaProductosPedidos as $producto)
                        dataProductos.addRow(['{{ $producto['nombre'] }}', {{ $producto['cantidad'] }}]);
                    @endforeach
                    
                    var options = {
                        title: 'Productos más pedidos',
                        colors: ['#00f2fe'],
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
        }
    </script>
</body>
</html>