<?php

namespace App\Helpers;

class GraficaHelper
{
    /**
     * Genera una gráfica de barras con estilo moderno
     */
    public static function generarBarra($datos, $titulo = 'Gráfica de Barras')
    {
        if (empty($datos['labels']) || empty($datos['data'])) {
            return null;
        }

        try {
            // Dimensiones
            $ancho = 800;
            $alto = 500;
            $margen_izq = 100;
            $margen_der = 80;
            $margen_inf = 100;
            $margen_sup = 100;
            
            // Crear imagen
            $imagen = imagecreate($ancho, $alto);
            
            // Colores profesionales
            $blanco = imagecolorallocate($imagen, 255, 255, 255);
            $negro = imagecolorallocate($imagen, 44, 62, 80);
            $gris_claro = imagecolorallocate($imagen, 236, 240, 241);
            $gris_medio = imagecolorallocate($imagen, 189, 195, 199);
            $gris_oscuro = imagecolorallocate($imagen, 127, 140, 141);
            
            // Colores para gradientes
            $colores = [
                ['inicio' => [52, 152, 219], 'fin' => [41, 128, 185]], // Azul
                ['inicio' => [46, 204, 113], 'fin' => [39, 174, 96]],  // Verde
                ['inicio' => [155, 89, 182], 'fin' => [142, 68, 173]], // Morado
                ['inicio' => [241, 196, 15], 'fin' => [243, 156, 18]], // Amarillo/Naranja
                ['inicio' => [230, 126, 34], 'fin' => [211, 84, 0]],   // Naranja
                ['inicio' => [231, 76, 60], 'fin' => [192, 57, 43]],   // Rojo
                ['inicio' => [26, 188, 156], 'fin' => [22, 160, 133]], // Turquesa
                ['inicio' => [52, 73, 94], 'fin' => [44, 62, 80]],     // Azul oscuro
            ];
            
            // Fondo con gradiente suave
            self::dibujarGradiente($imagen, 0, 0, $ancho, $alto, 
                [245, 247, 250], [255, 255, 255]);
            
            // Sombra para el área de la gráfica
            imagefilledrectangle($imagen, 
                $margen_izq + 2, $margen_sup + 2, 
                $ancho - $margen_der + 2, $alto - $margen_inf + 2, 
                imagecolorallocatealpha($imagen, 0, 0, 0, 40));
            
            // Área de la gráfica con fondo blanco
            imagefilledrectangle($imagen, 
                $margen_izq, $margen_sup, 
                $ancho - $margen_der, $alto - $margen_inf, 
                $blanco);
            
            // Borde del área
            imagerectangle($imagen, 
                $margen_izq, $margen_sup, 
                $ancho - $margen_der, $alto - $margen_inf, 
                $gris_medio);
            
            // Título con estilo
            $fuente_titulo = 5; // Tamaño de fuente
            $ancho_titulo = strlen($titulo) * imagefontwidth($fuente_titulo) * 1.2;
            $x_titulo = ($ancho - $ancho_titulo) / 2;
            
            // Sombra del título
            imagestring($imagen, $fuente_titulo, $x_titulo + 2, 22, $titulo, 
                imagecolorallocatealpha($imagen, 0, 0, 0, 60));
            // Título principal
            imagestring($imagen, $fuente_titulo, $x_titulo, 20, $titulo, 
                imagecolorallocate($imagen, 52, 73, 94));
            
            // Calcular ejes
            $x1 = $margen_izq;
            $y1 = $margen_sup;
            $x2 = $ancho - $margen_der;
            $y2 = $alto - $margen_inf;
            
            // Dibujar ejes
            imagesetthickness($imagen, 2);
            imageline($imagen, $x1, $y1, $x1, $y2, $gris_oscuro); // Eje Y
            imageline($imagen, $x1, $y2, $x2, $y2, $gris_oscuro); // Eje X
            imagesetthickness($imagen, 1);
            
            // Calcular máximo
            $max_valor = max($datos['data']);
            if ($max_valor == 0) $max_valor = 1;
            
            // Grid con líneas suaves
            $num_lineas = 6;
            for ($i = 0; $i <= $num_lineas; $i++) {
                $y_linea = $y2 - ($i * ($y2 - $y1) / $num_lineas);
                
                // Línea de grid punteada
                for ($x = $x1; $x <= $x2; $x += 5) {
                    imagesetpixel($imagen, $x, $y_linea, $gris_claro);
                }
                
                // Etiquetas del eje Y
                $valor_linea = ($i * $max_valor / $num_lineas);
                $etiqueta = number_format($valor_linea, 0);
                
                // Fondo para etiquetas Y
                $ancho_etiq = strlen($etiqueta) * imagefontwidth(2);
                imagefilledrectangle($imagen, 
                    $x1 - $ancho_etiq - 15, $y_linea - 8, 
                    $x1 - 5, $y_linea + 8, 
                    imagecolorallocatealpha($imagen, 255, 255, 255, 80));
                
                imagestring($imagen, 2, $x1 - $ancho_etiq - 10, $y_linea - 6, $etiqueta, $gris_oscuro);
            }
            
            // Dibujar barras
            $num_barras = count($datos['data']);
            $ancho_disponible = $x2 - $x1 - 40;
            $ancho_barra = ($ancho_disponible / $num_barras) * 0.7;
            $espacio_barra = ($ancho_disponible / $num_barras) * 0.3;
            
            $x_actual = $x1 + 20;
            $max_valor_grafica = $max_valor * 1.1; // Dejar espacio arriba
            
            foreach ($datos['data'] as $index => $valor) {
                $alto_barra = ($valor / $max_valor_grafica) * ($y2 - $y1 - 20);
                $y_barra = $y2 - $alto_barra - 10;
                
                // Color de la barra (cíclico)
                $color_idx = $index % count($colores);
                $color_inicio = $colores[$color_idx]['inicio'];
                $color_fin = $colores[$color_idx]['fin'];
                
                // Dibujar barra con gradiente
                self::dibujarGradienteVertical($imagen, 
                    $x_actual, $y_barra, 
                    $x_actual + $ancho_barra, $y2 - 10, 
                    $color_inicio, $color_fin);
                
                // Borde de la barra
                $color_borde = imagecolorallocate($imagen, 
                    $color_fin[0], $color_fin[1], $color_fin[2]);
                imagerectangle($imagen, 
                    $x_actual, $y_barra, 
                    $x_actual + $ancho_barra, $y2 - 10, 
                    $color_borde);
                
                // Sombra de la barra
                imagefilledrectangle($imagen, 
                    $x_actual + 3, $y_barra + 3, 
                    $x_actual + $ancho_barra + 3, $y2 - 7, 
                    imagecolorallocatealpha($imagen, 0, 0, 0, 30));
                
                // Valor encima de la barra
                $valor_texto = number_format($valor, 0);
                $ancho_valor = strlen($valor_texto) * imagefontwidth(3);
                $x_valor = $x_actual + ($ancho_barra - $ancho_valor) / 2;
                
                // Fondo del valor
                imagefilledrectangle($imagen, 
                    $x_valor - 3, $y_barra - 22, 
                    $x_valor + $ancho_valor + 3, $y_barra - 8, 
                    imagecolorallocatealpha($imagen, 0, 0, 0, 50));
                
                imagestring($imagen, 3, $x_valor, $y_barra - 20, $valor_texto, $blanco);
                
                // Etiqueta del eje X
                $label = $datos['labels'][$index];
                if (strlen($label) > 12) {
                    $label = substr($label, 0, 10) . '...';
                }
                
                $ancho_label = strlen($label) * imagefontwidth(2);
                $x_label = $x_actual + ($ancho_barra - $ancho_label) / 2;
                
                // Fondo para etiqueta X
                imagefilledrectangle($imagen, 
                    $x_label - 3, $y2 + 5, 
                    $x_label + $ancho_label + 3, $y2 + 20, 
                    imagecolorallocatealpha($imagen, 255, 255, 255, 200));
                
                imagestring($imagen, 2, $x_label, $y2 + 8, $label, $negro);
                
                $x_actual += $ancho_barra + $espacio_barra;
            }
            
            // Línea de meta (promedio)
            $promedio = array_sum($datos['data']) / count($datos['data']);
            $y_promedio = $y2 - ($promedio / $max_valor_grafica) * ($y2 - $y1 - 20) - 10;
            
            imagesetthickness($imagen, 2);
            imageline($imagen, $x1, $y_promedio, $x2, $y_promedio, 
                imagecolorallocatealpha($imagen, 231, 76, 60, 0));
            
            // Etiqueta del promedio
            $texto_prom = 'Promedio: ' . number_format($promedio, 0);
            imagefilledrectangle($imagen, $x2 - 120, $y_promedio - 18, $x2 - 20, $y_promedio - 2, 
                imagecolorallocatealpha($imagen, 231, 76, 60, 20));
            imagestring($imagen, 2, $x2 - 115, $y_promedio - 15, $texto_prom, 
                imagecolorallocate($imagen, 192, 57, 43));
            
            imagesetthickness($imagen, 1);
            
            // Capturar imagen
            ob_start();
            imagepng($imagen);
            $imagen_data = ob_get_clean();
            imagedestroy($imagen);
            
            return 'data:image/png;base64,' . base64_encode($imagen_data);
            
        } catch (\Exception $e) {
            \Log::error('Error en GraficaHelper::barra: ' . $e->getMessage());
            return self::generarImagenError($e->getMessage());
        }
    }
    
    /**
     * Genera una gráfica de líneas con estilo moderno
     */
    public static function generarLinea($datos, $titulo = 'Gráfica de Líneas')
    {
        if (empty($datos['labels']) || empty($datos['data'])) {
            return null;
        }

        try {
            // Dimensiones
            $ancho = 800;
            $alto = 500;
            $margen_izq = 100;
            $margen_der = 80;
            $margen_inf = 100;
            $margen_sup = 100;
            
            // Crear imagen
            $imagen = imagecreate($ancho, $alto);
            
            // Colores
            $blanco = imagecolorallocate($imagen, 255, 255, 255);
            $negro = imagecolorallocate($imagen, 44, 62, 80);
            $gris_claro = imagecolorallocate($imagen, 236, 240, 241);
            $gris_medio = imagecolorallocate($imagen, 189, 195, 199);
            $gris_oscuro = imagecolorallocate($imagen, 127, 140, 141);
            $azul = imagecolorallocate($imagen, 52, 152, 219);
            $azul_oscuro = imagecolorallocate($imagen, 41, 128, 185);
            $rojo = imagecolorallocate($imagen, 231, 76, 60);
            
            // Fondo con gradiente
            self::dibujarGradiente($imagen, 0, 0, $ancho, $alto, 
                [245, 247, 250], [255, 255, 255]);
            
            // Sombra
            imagefilledrectangle($imagen, 
                $margen_izq + 2, $margen_sup + 2, 
                $ancho - $margen_der + 2, $alto - $margen_inf + 2, 
                imagecolorallocatealpha($imagen, 0, 0, 0, 40));
            
            // Área de la gráfica
            imagefilledrectangle($imagen, 
                $margen_izq, $margen_sup, 
                $ancho - $margen_der, $alto - $margen_inf, 
                $blanco);
            
            imagerectangle($imagen, 
                $margen_izq, $margen_sup, 
                $ancho - $margen_der, $alto - $margen_inf, 
                $gris_medio);
            
            // Título
            $fuente_titulo = 5;
            $ancho_titulo = strlen($titulo) * imagefontwidth($fuente_titulo) * 1.2;
            $x_titulo = ($ancho - $ancho_titulo) / 2;
            
            imagestring($imagen, $fuente_titulo, $x_titulo + 2, 22, $titulo, 
                imagecolorallocatealpha($imagen, 0, 0, 0, 60));
            imagestring($imagen, $fuente_titulo, $x_titulo, 20, $titulo, 
                imagecolorallocate($imagen, 52, 73, 94));
            
            // Ejes
            $x1 = $margen_izq;
            $y1 = $margen_sup;
            $x2 = $ancho - $margen_der;
            $y2 = $alto - $margen_inf;
            
            imagesetthickness($imagen, 2);
            imageline($imagen, $x1, $y1, $x1, $y2, $gris_oscuro);
            imageline($imagen, $x1, $y2, $x2, $y2, $gris_oscuro);
            imagesetthickness($imagen, 1);
            
            // Calcular máximo
            $max_valor = max($datos['data']);
            if ($max_valor == 0) $max_valor = 1;
            $max_valor_grafica = $max_valor * 1.1;
            
            // Grid
            $num_lineas = 6;
            for ($i = 0; $i <= $num_lineas; $i++) {
                $y_linea = $y2 - ($i * ($y2 - $y1) / $num_lineas);
                
                for ($x = $x1; $x <= $x2; $x += 5) {
                    imagesetpixel($imagen, $x, $y_linea, $gris_claro);
                }
                
                $valor_linea = ($i * $max_valor / $num_lineas);
                $etiqueta = number_format($valor_linea, 0);
                imagestring($imagen, 2, $x1 - 50, $y_linea - 6, $etiqueta, $gris_oscuro);
            }
            
            // Dibujar puntos y líneas
            $num_puntos = count($datos['data']);
            $ancho_paso = ($x2 - $x1 - 40) / ($num_puntos - 1);
            
            $puntos_x = [];
            $puntos_y = [];
            
            $x_actual = $x1 + 20;
            
            // Primero dibujar el área bajo la línea (gradiente)
            $puntos_area = [];
            $puntos_area[] = $x1 + 20;
            $puntos_area[] = $y2 - 10;
            
            foreach ($datos['data'] as $index => $valor) {
                $y_punto = $y2 - ($valor / $max_valor_grafica) * ($y2 - $y1 - 20) - 10;
                $puntos_x[] = $x_actual;
                $puntos_y[] = $y_punto;
                $puntos_area[] = $x_actual;
                $puntos_area[] = $y_punto;
                $x_actual += $ancho_paso;
            }
            
            $puntos_area[] = $x_actual - $ancho_paso;
            $puntos_area[] = $y2 - 10;
            
            // Dibujar área con transparencia
            if (count($puntos_area) > 4) {
                imagefilledpolygon($imagen, $puntos_area, count($puntos_area)/2, 
                    imagecolorallocatealpha($imagen, 52, 152, 219, 60));
            }
            
            // Dibujar líneas entre puntos
            imagesetthickness($imagen, 3);
            for ($i = 0; $i < $num_puntos - 1; $i++) {
                imageline($imagen, 
                    $puntos_x[$i], $puntos_y[$i], 
                    $puntos_x[$i + 1], $puntos_y[$i + 1], 
                    $azul);
            }
            
            // Dibujar puntos
            for ($i = 0; $i < $num_puntos; $i++) {
                // Resplandor
                imagefilledellipse($imagen, $puntos_x[$i], $puntos_y[$i], 14, 14, 
                    imagecolorallocatealpha($imagen, 52, 152, 219, 40));
                
                // Punto principal
                imagefilledellipse($imagen, $puntos_x[$i], $puntos_y[$i], 10, 10, $blanco);
                imagefilledellipse($imagen, $puntos_x[$i], $puntos_y[$i], 6, 6, $azul_oscuro);
                
                // Valor
                $valor_texto = number_format($datos['data'][$i], 0);
                imagefilledrectangle($imagen, 
                    $puntos_x[$i] - 20, $puntos_y[$i] - 25, 
                    $puntos_x[$i] + 20, $puntos_y[$i] - 10, 
                    imagecolorallocatealpha($imagen, 0, 0, 0, 50));
                imagestring($imagen, 2, $puntos_x[$i] - 10, $puntos_y[$i] - 22, $valor_texto, $blanco);
            }
            
            // Etiquetas X
            $x_actual = $x1 + 20;
            foreach ($datos['labels'] as $index => $label) {
                if (strlen($label) > 8) {
                    $label = substr($label, 0, 6) . '...';
                }
                
                $ancho_label = strlen($label) * imagefontwidth(2);
                $x_label = $x_actual - $ancho_label / 2;
                
                imagefilledrectangle($imagen, 
                    $x_label - 3, $y2 + 5, 
                    $x_label + $ancho_label + 3, $y2 + 25, 
                    imagecolorallocatealpha($imagen, 255, 255, 255, 200));
                
                imagestring($imagen, 2, $x_label, $y2 + 8, $label, $negro);
                
                $x_actual += $ancho_paso;
            }
            
            imagesetthickness($imagen, 1);
            
            // Leyenda
            imagefilledrectangle($imagen, $x2 - 150, $y1 + 10, $x2 - 20, $y1 + 40, 
                imagecolorallocatealpha($imagen, 255, 255, 255, 200));
            imagerectangle($imagen, $x2 - 150, $y1 + 10, $x2 - 20, $y1 + 40, $gris_medio);
            
            imagefilledrectangle($imagen, $x2 - 140, $y1 + 18, $x2 - 120, $y1 + 32, $azul);
            imagestring($imagen, 2, $x2 - 115, $y1 + 18, 'Valores', $negro);
            
            // Capturar imagen
            ob_start();
            imagepng($imagen);
            $imagen_data = ob_get_clean();
            imagedestroy($imagen);
            
            return 'data:image/png;base64,' . base64_encode($imagen_data);
            
        } catch (\Exception $e) {
            \Log::error('Error en GraficaHelper::linea: ' . $e->getMessage());
            return self::generarImagenError($e->getMessage());
        }
    }
    
    /**
     * Genera una gráfica de pastel con estilo moderno
     */
    public static function generarPastel($datos, $titulo = 'Gráfica de Pastel')
    {
        if (empty($datos['labels']) || empty($datos['data'])) {
            return null;
        }

        try {
            $ancho = 800;
            $alto = 600;
            
            $imagen = imagecreate($ancho, $alto);
            
            // Fondo con gradiente
            self::dibujarGradiente($imagen, 0, 0, $ancho, $alto, 
                [245, 247, 250], [255, 255, 255]);
            
            // Colores vibrantes para el pastel
            $colores_pastel = [
                [52, 152, 219],  // Azul
                [46, 204, 113],  // Verde
                [155, 89, 182],  // Morado
                [241, 196, 15],  // Amarillo
                [230, 126, 34],  // Naranja
                [231, 76, 60],   // Rojo
                [26, 188, 156],  // Turquesa
                [52, 73, 94],    // Azul oscuro
                [149, 165, 166], // Gris
                [243, 156, 18],  // Naranja claro
            ];
            
            // Título
            $fuente_titulo = 5;
            $ancho_titulo = strlen($titulo) * imagefontwidth($fuente_titulo) * 1.2;
            $x_titulo = ($ancho - $ancho_titulo) / 2;
            
            imagestring($imagen, $fuente_titulo, $x_titulo + 2, 22, $titulo, 
                imagecolorallocatealpha($imagen, 0, 0, 0, 60));
            imagestring($imagen, $fuente_titulo, $x_titulo, 20, $titulo, 
                imagecolorallocate($imagen, 52, 73, 94));
            
            // Calcular total y ángulos
            $total = array_sum($datos['data']);
            if ($total == 0) $total = 1;
            
            $angulos = [];
            $acumulado = 0;
            foreach ($datos['data'] as $valor) {
                $angulos[] = ($valor / $total) * 360;
            }
            
            // Centro del pastel
            $cx = 350;
            $cy = 300;
            $radio = 180;
            
            // Dibujar sombra
            for ($i = 0; $i < 5; $i++) {
                imagefilledellipse($imagen, $cx + 5 + $i, $cy + 5 + $i, 
                    ($radio + 10) * 2, ($radio + 10) * 2, 
                    imagecolorallocatealpha($imagen, 0, 0, 0, 20));
            }
            
            // Dibujar pastel
            $inicio = 0;
            foreach ($datos['data'] as $index => $valor) {
                $porcentaje = ($valor / $total) * 100;
                if ($porcentaje < 1) continue; // Ignorar sectores muy pequeños
                
                $angulo = $angulos[$index];
                $color_idx = $index % count($colores_pastel);
                $color = $colores_pastel[$color_idx];
                
                $color_relleno = imagecolorallocate($imagen, $color[0], $color[1], $color[2]);
                $color_borde = imagecolorallocate($imagen, 
                    max(0, $color[0] - 30), 
                    max(0, $color[1] - 30), 
                    max(0, $color[2] - 30));
                
                // Dibujar sector
                imagefilledarc($imagen, $cx, $cy, $radio * 2, $radio * 2, 
                    $inicio, $inicio + $angulo, $color_relleno, IMG_ARC_PIE);
                
                // Borde del sector
                imagearc($imagen, $cx, $cy, $radio * 2, $radio * 2, 
                    $inicio, $inicio + $angulo, $color_borde);
                
                // Calcular posición para la etiqueta
                $angulo_medio = $inicio + $angulo / 2;
                $rad = deg2rad($angulo_medio);
                $label_x = $cx + cos($rad) * ($radio * 0.7);
                $label_y = $cy + sin($rad) * ($radio * 0.7);
                
                // Etiqueta con porcentaje
                $label = number_format($porcentaje, 1) . '%';
                $ancho_label = strlen($label) * imagefontwidth(2);
                
                // Fondo para la etiqueta
                imagefilledrectangle($imagen, 
                    $label_x - $ancho_label/2 - 3, $label_y - 8, 
                    $label_x + $ancho_label/2 + 3, $label_y + 8, 
                    imagecolorallocatealpha($imagen, 255, 255, 255, 80));
                
                imagestring($imagen, 2, $label_x - $ancho_label/2, $label_y - 6, $label, $negro);
                
                $inicio += $angulo;
            }
            
            // Círculo interior (efecto donut opcional)
            imagefilledellipse($imagen, $cx, $cy, $radio * 0.6, $radio * 0.6, 
                imagecolorallocate($imagen, 255, 255, 255));
            
            // Leyenda
            $leyenda_y = 150;
            foreach ($datos['labels'] as $index => $label) {
                if ($index >= 7) break; // Mostrar solo primeros 7 para no saturar
                
                $color_idx = $index % count($colores_pastel);
                $color = $colores_pastel[$color_idx];
                $color_cuadro = imagecolorallocate($imagen, $color[0], $color[1], $color[2]);
                
                // Cuadro de color
                imagefilledrectangle($imagen, 550, $leyenda_y, 570, $leyenda_y + 15, $color_cuadro);
                imagerectangle($imagen, 550, $leyenda_y, 570, $leyenda_y + 15, $negro);
                
                // Texto de la leyenda
                $texto = strlen($label) > 15 ? substr($label, 0, 12) . '...' : $label;
                imagestring($imagen, 2, 575, $leyenda_y, $texto, $negro);
                
                $leyenda_y += 25;
            }
            
            // Capturar imagen
            ob_start();
            imagepng($imagen);
            $imagen_data = ob_get_clean();
            imagedestroy($imagen);
            
            return 'data:image/png;base64,' . base64_encode($imagen_data);
            
        } catch (\Exception $e) {
            \Log::error('Error en GraficaHelper::pastel: ' . $e->getMessage());
            return self::generarImagenError($e->getMessage());
        }
    }
    
    /**
     * Dibuja un gradiente horizontal
     */
    private static function dibujarGradiente($imagen, $x1, $y1, $x2, $y2, $color_inicio, $color_fin)
    {
        for ($x = $x1; $x <= $x2; $x++) {
            $porcentaje = ($x - $x1) / ($x2 - $x1);
            $r = $color_inicio[0] + ($color_fin[0] - $color_inicio[0]) * $porcentaje;
            $g = $color_inicio[1] + ($color_fin[1] - $color_inicio[1]) * $porcentaje;
            $b = $color_inicio[2] + ($color_fin[2] - $color_inicio[2]) * $porcentaje;
            
            $color = imagecolorallocate($imagen, (int)$r, (int)$g, (int)$b);
            imageline($imagen, $x, $y1, $x, $y2, $color);
        }
    }
    
    /**
     * Dibuja un gradiente vertical
     */
    private static function dibujarGradienteVertical($imagen, $x1, $y1, $x2, $y2, $color_inicio, $color_fin)
    {
        for ($y = $y1; $y <= $y2; $y++) {
            $porcentaje = ($y - $y1) / ($y2 - $y1);
            $r = $color_inicio[0] + ($color_fin[0] - $color_inicio[0]) * $porcentaje;
            $g = $color_inicio[1] + ($color_fin[1] - $color_inicio[1]) * $porcentaje;
            $b = $color_inicio[2] + ($color_fin[2] - $color_inicio[2]) * $porcentaje;
            
            $color = imagecolorallocate($imagen, (int)$r, (int)$g, (int)$b);
            imageline($imagen, $x1, $y, $x2, $y, $color);
        }
    }
    
    /**
     * Genera imagen de error
     */
    private static function generarImagenError($mensaje)
    {
        $ancho = 600;
        $alto = 300;
        $imagen = imagecreate($ancho, $alto);
        
        $blanco = imagecolorallocate($imagen, 255, 255, 255);
        $rojo = imagecolorallocate($imagen, 231, 76, 60);
        $negro = imagecolorallocate($imagen, 0, 0, 0);
        
        imagefill($imagen, 0, 0, $blanco);
        imagerectangle($imagen, 0, 0, $ancho-1, $alto-1, $rojo);
        
        imagestring($imagen, 5, 200, 130, "Error en gráfica", $rojo);
        imagestring($imagen, 3, 150, 160, substr($mensaje, 0, 50), $negro);
        
        ob_start();
        imagepng($imagen);
        $imagen_data = ob_get_clean();
        imagedestroy($imagen);
        
        return 'data:image/png;base64,' . base64_encode($imagen_data);
    }
}