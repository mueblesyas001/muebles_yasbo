<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Empleado;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GraficaController extends Controller{
    /**
     * Genera una gráfica de barras para empleados y devuelve imagen base64
     */
    public static function graficaEmpleadosBase64($fechaInicio, $fechaFin, $empleadoId = null)
    {
        // Obtener datos de ventas por empleado
        $query = Venta::select('Empleado_idEmpleado', 
                DB::raw('COUNT(*) as total_ventas'),
                DB::raw('SUM(Total) as total_ingresos')
            )
            ->whereDate('Fecha', '>=', $fechaInicio)
            ->whereDate('Fecha', '<=', $fechaFin)
            ->groupBy('Empleado_idEmpleado')
            ->orderBy('total_ingresos', 'desc')
            ->take(6);
            
        if ($empleadoId) {
            $query->where('Empleado_idEmpleado', $empleadoId);
        }
        
        $ventasPorEmpleado = $query->get();
        
        // Si no hay datos, crear datos de ejemplo
        if ($ventasPorEmpleado->isEmpty()) {
            return $this->crearGraficaEjemplo();
        }
        
        // Crear imagen
        $ancho = 700;
        $alto = 350;
        $imagen = imagecreate($ancho, $alto);
        
        // Colores
        $blanco = imagecolorallocate($imagen, 255, 255, 255);
        $gris = imagecolorallocate($imagen, 240, 240, 240);
        $grisOscuro = imagecolorallocate($imagen, 200, 200, 200);
        $azul = imagecolorallocate($imagen, 44, 62, 80);
        $naranja = imagecolorallocate($imagen, 230, 126, 34);
        $negro = imagecolorallocate($imagen, 0, 0, 0);
        $rojo = imagecolorallocate($imagen, 255, 99, 132);
        
        imagefill($imagen, 0, 0, $blanco);
        
        // Título
        $titulo = "Rendimiento por Empleado";
        $anchoTexto = strlen($titulo) * imagefontwidth(5);
        imagestring($imagen, 5, ($ancho - $anchoTexto) / 2, 10, $titulo, $azul);
        
        $maxValor = $ventasPorEmpleado->max('total_ingresos') ?: 1;
        $margenIzq = 80;
        $margenDer = 40;
        $margenSup = 50;
        $margenInf = 70;
        $anchoGrafica = $ancho - $margenIzq - $margenDer;
        $altoGrafica = $alto - $margenSup - $margenInf;
        
        // Dibujar ejes
        imageline($imagen, $margenIzq - 5, $margenSup, $margenIzq - 5, $margenSup + $altoGrafica, $negro);
        imageline($imagen, $margenIzq - 5, $margenSup + $altoGrafica, $margenIzq + $anchoGrafica, $margenSup + $altoGrafica, $negro);
        
        // Grid horizontal
        for ($i = 0; $i <= 5; $i++) {
            $y = $margenSup + ($altoGrafica / 5) * $i;
            imageline($imagen, $margenIzq - 5, $y, $margenIzq + $anchoGrafica, $y, $gris);
            
            $valor = $maxValor * (1 - $i/5);
            imagestring($imagen, 2, 10, $y - 8, '$' . number_format($valor, 0), $grisOscuro);
        }
        
        // Calcular ancho de barras
        $numBarras = $ventasPorEmpleado->count();
        $anchoBarra = 50;
        $espacioTotal = $anchoGrafica - ($numBarras * $anchoBarra);
        $espacioEntreBarras = $numBarras > 1 ? $espacioTotal / ($numBarras + 1) : $anchoGrafica / 3;
        
        // Dibujar barras
        $i = 0;
        foreach ($ventasPorEmpleado as $item) {
            $x = $margenIzq + $espacioEntreBarras + ($anchoBarra + $espacioEntreBarras) * $i;
            $altoBarra = ($item->total_ingresos / $maxValor) * $altoGrafica;
            $yBarra = $margenSup + $altoGrafica - $altoBarra;
            
            // Barra principal
            imagefilledrectangle($imagen, $x, $yBarra, $x + $anchoBarra - 5, $margenSup + $altoGrafica - 2, $naranja);
            
            // Borde de la barra
            imagerectangle($imagen, $x, $yBarra, $x + $anchoBarra - 5, $margenSup + $altoGrafica - 2, $azul);
            
            // Valor encima de la barra
            $valorTexto = '$' . number_format($item->total_ingresos / 1000, 1) . 'k';
            imagestring($imagen, 2, $x, $yBarra - 18, $valorTexto, $azul);
            
            // Nombre del empleado
            $empleado = Empleado::find($item->Empleado_idEmpleado);
            $nombre = $empleado ? substr(trim(($empleado->Nombre ?? '') . ' ' . ($empleado->ApPaterno ?? '')), 0, 12) : 'Empleado';
            imagestring($imagen, 2, $x, $margenSup + $altoGrafica + 5, $nombre, $negro);
            
            // Ventas debajo del nombre
            imagestring($imagen, 1, $x, $margenSup + $altoGrafica + 20, $item->total_ventas . ' ventas', $grisOscuro);
            
            $i++;
        }
        
        // Leyenda
        imagefilledrectangle($imagen, $ancho - 150, 40, $ancho - 50, 60, $naranja);
        imagerectangle($imagen, $ancho - 150, 40, $ancho - 50, 60, $azul);
        imagestring($imagen, 3, $ancho - 140, 45, "Ingresos", $blanco);
        
        // Capturar la imagen en memoria
        ob_start();
        imagepng($imagen);
        $imagenData = ob_get_clean();
        imagedestroy($imagen);
        
        return base64_encode($imagenData);
    }
    
    /**
     * Crea una gráfica de ejemplo cuando no hay datos
     */
    private function crearGraficaEjemplo()
    {
        $ancho = 700;
        $alto = 350;
        $imagen = imagecreate($ancho, $alto);
        
        $blanco = imagecolorallocate($imagen, 255, 255, 255);
        $gris = imagecolorallocate($imagen, 240, 240, 240);
        $azul = imagecolorallocate($imagen, 44, 62, 80);
        $naranja = imagecolorallocate($imagen, 230, 126, 34);
        $negro = imagecolorallocate($imagen, 0, 0, 0);
        
        imagefill($imagen, 0, 0, $blanco);
        
        $titulo = "No hay datos de empleados en el período seleccionado";
        $anchoTexto = strlen($titulo) * imagefontwidth(4);
        imagestring($imagen, 4, ($ancho - $anchoTexto) / 2, 150, $titulo, $azul);
        
        ob_start();
        imagepng($imagen);
        $imagenData = ob_get_clean();
        imagedestroy($imagen);
        
        return base64_encode($imagenData);
    }
    
    // Mantén los métodos originales si los necesitas para otras cosas
    public function graficaEmpleados(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->toDateString());
        $fechaFin = $request->get('fecha_fin', Carbon::now()->toDateString());
        $empleadoId = $request->get('empleado_id');
        
        $imagenBase64 = $this->graficaEmpleadosBase64($fechaInicio, $fechaFin, $empleadoId);
        
        header('Content-Type: image/png');
        echo base64_decode($imagenBase64);
        exit;
    }
}