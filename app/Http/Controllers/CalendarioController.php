<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cliente;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el mes y año actual o desde la solicitud
        $mes = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);
        
        // Validar mes y año
        $mes = max(1, min(12, $mes));
        $anio = max(2020, min(2100, $anio));
        
        // Crear fecha de inicio y fin del mes
        $fechaInicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();

        // Obtener pedidos del mes seleccionado con todas las relaciones necesarias
        $pedidos = Pedido::with(['cliente', 'detallePedidos.producto'])
            ->whereBetween('Fecha_entrega', [$fechaInicio->format('Y-m-d'), $fechaFin->format('Y-m-d')])
            ->orderBy('Fecha_entrega')
            ->orderBy('Hora_entrega')
            ->get()
            ->map(function($pedido) {
                // Obtener nombre completo del cliente
                $clienteNombre = 'Cliente no encontrado';
                if ($pedido->cliente) {
                    $clienteNombre = trim(
                        ($pedido->cliente->Nombre ?? '') . ' ' . 
                        ($pedido->cliente->ApPaterno ?? '') . ' ' . 
                        ($pedido->cliente->ApMaterno ?? '')
                    );
                }
                
                return [
                    'id' => $pedido->id,
                    'fecha_entrega' => $pedido->Fecha_entrega,
                    'hora_entrega' => $pedido->Hora_entrega,
                    'lugar_entrega' => $pedido->Lugar_entrega,
                    'estado' => $pedido->Estado,
                    'prioridad' => $pedido->Prioridad,
                    'total' => number_format($pedido->Total, 2),
                    'cliente_nombre' => $clienteNombre,
                    'cliente_id' => $pedido->Cliente_idCliente,
                    'comentario' => $pedido->comentario, // NUEVO CAMPO
                    'detalles' => $pedido->detallePedidos->map(function($detalle) {
                        return [
                            'producto_nombre' => $detalle->producto->Nombre ?? 'Producto no encontrado',
                            'cantidad' => $detalle->Cantidad,
                            'precio' => number_format($detalle->PrecioUnitario, 2),
                            'subtotal' => number_format($detalle->Cantidad * $detalle->PrecioUnitario, 2)
                        ];
                    })
                ];
            });

        // Agrupar pedidos por fecha para el calendario
        $pedidosPorFecha = $pedidos->groupBy('fecha_entrega');

        // Generar estructura del calendario
        $calendario = $this->generarCalendario($mes, $anio, $pedidosPorFecha);

        $clientes = Cliente::all();

        // Fechas para navegación
        $mesAnterior = $mes - 1 <= 0 ? 12 : $mes - 1;
        $anioMesAnterior = $mes - 1 <= 0 ? $anio - 1 : $anio;
        $mesSiguiente = $mes + 1 > 12 ? 1 : $mes + 1;
        $anioMesSiguiente = $mes + 1 > 12 ? $anio + 1 : $anio;

        // Estadísticas
        $estadisticas = [
            'total_pedidos' => $pedidos->count(),
            'alta_prioridad' => $pedidos->where('prioridad', 'alta')->count(),
            'media_prioridad' => $pedidos->where('prioridad', 'media')->count(),
            'pendientes' => $pedidos->where('estado', 'pendiente')->count(),
        ];

        return view('calendario.index', compact(
            'calendario', 
            'pedidos', 
            'clientes',
            'mes',
            'anio',
            'mesAnterior',
            'anioMesAnterior',
            'mesSiguiente',
            'anioMesSiguiente',
            'estadisticas'
        ));
    }

    private function generarCalendario($mes, $anio, $pedidosPorFecha)
    {
        $fecha = Carbon::create($anio, $mes, 1);
        $diasEnMes = $fecha->daysInMonth;
        $primerDiaSemana = $fecha->dayOfWeek; // 0 (domingo) a 6 (sábado)
        
        $semanas = [];
        $semanaActual = [];
        
        // Agregar días vacíos antes del primer día del mes
        for ($i = 0; $i < $primerDiaSemana; $i++) {
            $semanaActual[] = [
                'tipo' => 'vacio',
                'numero' => null,
                'fecha' => null,
                'esHoy' => false,
                'pedidos' => []
            ];
        }
        
        // Agregar días del mes
        for ($dia = 1; $dia <= $diasEnMes; $dia++) {
            $fechaActual = $fecha->copy()->setDay($dia);
            $fechaFormateada = $fechaActual->format('Y-m-d');
            
            $diaCalendario = [
                'tipo' => 'dia',
                'numero' => $dia,
                'fecha' => $fechaFormateada,
                'esHoy' => $fechaActual->isToday(),
                'pedidos' => $pedidosPorFecha->get($fechaFormateada, [])
            ];
            
            $semanaActual[] = $diaCalendario;
            
            // Si completamos una semana (7 días), agregar a semanas y reiniciar
            if (count($semanaActual) === 7) {
                $semanas[] = $semanaActual;
                $semanaActual = [];
            }
        }
        
        // Completar la última semana con días vacíos si es necesario
        if (!empty($semanaActual)) {
            while (count($semanaActual) < 7) {
                $semanaActual[] = [
                    'tipo' => 'vacio',
                    'numero' => null,
                    'fecha' => null,
                    'esHoy' => false,
                    'pedidos' => []
                ];
            }
            $semanas[] = $semanaActual;
        }
        
        return $semanas;
    }

    // Método para obtener detalles de un pedido específico (AJAX)
    public function obtenerDetallePedido($id)
    {
        try {
            $pedido = Pedido::with(['cliente', 'detallePedidos.producto'])
                ->findOrFail($id);

            // Calcular total del pedido sumando los detalles
            $totalPedido = 0;
            $detallesHtml = '';
            
            foreach ($pedido->detallePedidos as $detalle) {
                $subtotal = $detalle->Cantidad * $detalle->PrecioUnitario;
                $totalPedido += $subtotal;
                
                $detallesHtml .= '
                <tr>
                    <td>' . ($detalle->producto->Nombre ?? 'N/A') . '</td>
                    <td class="text-center">' . $detalle->Cantidad . '</td>
                    <td class="text-end">$' . number_format($detalle->PrecioUnitario, 2) . '</td>
                    <td class="text-end">$' . number_format($subtotal, 2) . '</td>
                </tr>';
            }

            // Obtener nombre completo del cliente
            $clienteNombre = 'Cliente no encontrado';
            $clienteTelefono = 'N/A';
            $clienteCorreo = 'N/A';
            $clienteDireccion = 'N/A';
            
            if ($pedido->cliente) {
                $clienteNombre = trim(
                    ($pedido->cliente->Nombre ?? '') . ' ' . 
                    ($pedido->cliente->ApPaterno ?? '') . ' ' . 
                    ($pedido->cliente->ApMaterno ?? '')
                );
                $clienteTelefono = $pedido->cliente->Telefono ?? 'N/A';
                $clienteCorreo = $pedido->cliente->Correo ?? 'N/A';
                $clienteDireccion = $pedido->cliente->Direccion ?? 'N/A';
            }

            // Colores para badges
            $prioridadColor = $this->getPrioridadColor($pedido->Prioridad);
            $estadoColor = $this->getEstadoColor($pedido->Estado);

            // Verificar si tiene comentario
            $comentarioHtml = '';
            if (!empty($pedido->comentario)) {
                $comentarioHtml = '
                <!-- Sección 4: Comentarios del Pedido (NUEVO) -->
                <div class="seccion-notas">
                    <h5 class="titulo-seccion">
                        <i class="fas fa-comment-dots me-2"></i>Comentarios del Pedido
                    </h5>
                    <div class="observacion-box">
                        <p class="mb-0">' . nl2br(e($pedido->comentario)) . '</p>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Notas especiales para este pedido
                        </small>
                    </div>
                </div>';
            }

            $html = '
            <style>
                .detalle-completo {
                    padding: 20px;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-radius: 12px;
                }
                
                .seccion-pedido, .seccion-cliente, .seccion-productos, .seccion-notas {
                    background: white;
                    padding: 25px;
                    margin-bottom: 20px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                    border-left: 5px solid;
                }
                
                .seccion-pedido { border-left-color: #667eea; }
                .seccion-cliente { border-left-color: #4facfe; }
                .seccion-productos { border-left-color: #43e97b; }
                .seccion-notas { border-left-color: #ff9a9e; }
                
                .titulo-seccion {
                    color: #495057;
                    font-weight: 600;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #dee2e6;
                    font-size: 1.1rem;
                }
                
                .info-item {
                    margin-bottom: 15px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 8px;
                    transition: all 0.3s ease;
                }
                
                .info-item:hover {
                    background: #e9ecef;
                    transform: translateX(5px);
                }
                
                .info-label {
                    display: block;
                    font-size: 0.85rem;
                    color: #6c757d;
                    font-weight: 500;
                    margin-bottom: 5px;
                }
                
                .info-value {
                    display: block;
                    font-size: 1rem;
                    color: #212529;
                    font-weight: 600;
                }
                
                .tabla-productos {
                    width: 100%;
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                
                .tabla-productos thead {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }
                
                .tabla-productos th {
                    border: none;
                    padding: 15px;
                    font-weight: 600;
                    font-size: 0.9rem;
                }
                
                .tabla-productos tbody tr {
                    border-bottom: 1px solid #e9ecef;
                    transition: background-color 0.3s ease;
                }
                
                .tabla-productos tbody tr:hover {
                    background-color: rgba(102, 126, 234, 0.05);
                }
                
                .tabla-productos td {
                    padding: 15px;
                    vertical-align: middle;
                }
                
                .total-row {
                    background: #f8f9fa;
                    font-size: 1.1rem;
                }
                
                .total-pedido {
                    color: #198754;
                    font-size: 1.2rem;
                }
                
                .observacion-box {
                    background: #fff3cd;
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid #ffc107;
                    font-style: italic;
                }
                
                .estado-badge, .prioridad-badge {
                    padding: 8px 16px;
                    font-size: 0.9rem;
                    border-radius: 20px;
                    font-weight: 600;
                }
                
                .badge.bg-danger { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important; }
                .badge.bg-warning { background: linear-gradient(135deg, #ffd93d 0%, #ffb347 100%) !important; }
                .badge.bg-success { background: linear-gradient(135deg, #6bcf7f 0%, #4ea752 100%) !important; }
                .badge.bg-primary { background: linear-gradient(135deg, #6c8eff 0%, #4d73fe 100%) !important; }
                .badge.bg-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important; }
                
                @media (max-width: 768px) {
                    .detalle-completo {
                        padding: 10px;
                    }
                    
                    .seccion-pedido, .seccion-cliente, .seccion-productos, .seccion-notas {
                        padding: 15px;
                    }
                    
                    .tabla-productos th, 
                    .tabla-productos td {
                        padding: 8px;
                        font-size: 0.85rem;
                    }
                }
            </style>
            <div class="detalle-completo">
                <!-- Sección 1: Información del Pedido -->
                <div class="seccion-pedido">
                    <h5 class="titulo-seccion">
                        <i class="fas fa-info-circle me-2"></i>Información del Pedido
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-item">
                                <span class="info-label">Fecha de Entrega</span>
                                <span class="info-value">' . $pedido->Fecha_entrega . '</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <span class="info-label">Hora de Entrega</span>
                                <span class="info-value">' . $pedido->Hora_entrega . '</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <span class="info-label">Lugar de Entrega</span>
                                <span class="info-value">' . ($pedido->Lugar_entrega ?? 'No especificado') . '</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <span class="info-label">Estado</span>
                                <span class="badge estado-badge bg-' . $estadoColor . '">' . $pedido->Estado . '</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <span class="info-label">Prioridad</span>
                                <span class="badge prioridad-badge bg-' . $prioridadColor . '">' . $pedido->Prioridad . '</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Información del Cliente -->
                <div class="seccion-cliente">
                    <h5 class="titulo-seccion">
                        <i class="fas fa-user me-2"></i>Información del Cliente
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Nombre Completo</span>
                                <span class="info-value">' . $clienteNombre . '</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Teléfono</span>
                                <span class="info-value">
                                    <a href="tel:' . $clienteTelefono . '" class="text-decoration-none">
                                        ' . $clienteTelefono . '
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Correo Electrónico</span>
                                <span class="info-value">
                                    <a href="mailto:' . $clienteCorreo . '" class="text-decoration-none">
                                        ' . $clienteCorreo . '
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Dirección</span>
                                <span class="info-value">' . $clienteDireccion . '</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Detalles de Productos -->
                <div class="seccion-productos">
                    <h5 class="titulo-seccion">
                        <i class="fas fa-boxes me-2"></i>Detalles del Pedido
                    </h5>
                    <div class="table-responsive">
                        <table class="table tabla-productos">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th width="100" class="text-center">Cantidad</th>
                                    <th width="150" class="text-end">Precio Unitario</th>
                                    <th width="150" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . $detallesHtml . '
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="3" class="text-end fw-bold">TOTAL DEL PEDIDO:</td>
                                    <td class="text-end fw-bold total-pedido">$' . number_format($totalPedido, 2) . '</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                ' . $comentarioHtml . '
            </div>';

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar el pedido: ' . $e->getMessage()
            ], 404);
        }
    }

    // Método auxiliar para color de prioridad
    private function getPrioridadColor($prioridad)
    {
        $prioridad = strtolower($prioridad);
        
        if ($prioridad == 'alta') {
            return 'danger';
        } elseif ($prioridad == 'media') {
            return 'warning';
        } elseif ($prioridad == 'baja') {
            return 'success';
        }
        return 'primary';
    }

    // Método auxiliar para color de estado
    private function getEstadoColor($estado)
    {
        $estado = strtolower($estado);
        
        if ($estado == 'pendiente') {
            return 'warning';
        } elseif ($estado == 'confirmado') {
            return 'info';
        } elseif (in_array($estado, ['en proceso', 'en_proceso'])) {
            return 'primary';
        } elseif (in_array($estado, ['completado', 'entregado'])) {
            return 'success';
        } elseif ($estado == 'cancelado') {
            return 'danger';
        }
        return 'secondary';
    }

    public function eventosJson(Request $request)
    {
        $fechaInicio = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('end', now()->endOfMonth()->format('Y-m-d'));

        $pedidos = Pedido::with(['cliente'])
            ->whereBetween('Fecha_entrega', [$fechaInicio, $fechaFin])
            ->get()
            ->map(function($pedido) {
                // Obtener nombre completo del cliente
                $clienteNombre = 'Cliente no encontrado';
                if ($pedido->cliente) {
                    $clienteNombre = trim(
                        ($pedido->cliente->Nombre ?? '') . ' ' . 
                        ($pedido->cliente->ApPaterno ?? '') . ' ' . 
                        ($pedido->cliente->ApMaterno ?? '')
                    );
                }

                // Color según prioridad
                $color = '#007bff'; // default
                if (strtolower($pedido->Prioridad) == 'alta') {
                    $color = '#dc3545';
                } elseif (strtolower($pedido->Prioridad) == 'media') {
                    $color = '#ffc107';
                } elseif (strtolower($pedido->Prioridad) == 'baja') {
                    $color = '#28a745';
                }

                // Crear fecha y hora completa
                $fechaHora = $pedido->Fecha_entrega . ' ' . $pedido->Hora_entrega;

                return [
                    'id' => $pedido->id,
                    'title' => "Pedido #{$pedido->id} - " . $clienteNombre,
                    'start' => $fechaHora,
                    'color' => $color,
                    'extendedProps' => [
                        'cliente' => $clienteNombre,
                        'estado' => $pedido->Estado,
                        'prioridad' => $pedido->Prioridad,
                        'total' => number_format($pedido->Total, 2),
                        'lugar_entrega' => $pedido->Lugar_entrega,
                        'hora_entrega' => $pedido->Hora_entrega,
                        'comentario' => $pedido->comentario // NUEVO CAMPO
                    ]
                ];
            });

        return response()->json($pedidos);
    }
}