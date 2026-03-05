<?php

namespace App\Helpers;

class GraficaHelper
{
    /**
     * Genera una gráfica de barras con referencias en columna derecha
     */
    public static function generarBarra($datos, $titulo = 'Gráfica de Barras')
    {
        if (empty($datos['labels']) || empty($datos['data'])) {
            return null;
        }

        try {
            // Dimensiones - MÁS ANCHO para mejor separación
            $ancho = 1200; // Aumentado de 1100 a 1200
            $alto = 650;   // Aumentado de 600 a 650
            $margen_izq = 100;
            $margen_der = 300; // Aumentado de 250 a 300 para más espacio
            $margen_inf = 100;
            $margen_sup = 100;  // Aumentado de 80 a 100 para el título
            
            // Crear imagen
            $imagen = imagecreate($ancho, $alto);
            
            // Colores
            $blanco = imagecolorallocate($imagen, 255, 255, 255);
            $negro = imagecolorallocate($imagen, 44, 62, 80);
            $gris_claro = imagecolorallocate($imagen, 245, 245, 245);
            $gris_medio = imagecolorallocate($imagen, 200, 200, 200);
            $gris_oscuro = imagecolorallocate($imagen, 100, 100, 100);
            $azul = imagecolorallocate($imagen, 52, 152, 219);
            
            // Colores para barras
            $colores_barras = [
                [52, 152, 219],  // Azul
                [46, 204, 113],  // Verde
                [155, 89, 182],  // Morado
                [241, 196, 15],  // Amarillo
                [230, 126, 34],  // Naranja
                [231, 76, 60],   // Rojo
                [26, 188, 156],  // Turquesa
                [52, 73, 94],    // Azul oscuro
            ];
            
            // Fondo blanco
            imagefill($imagen, 0, 0, $blanco);
            
            // TÍTULO CON ACENTOS
            $fuente = 'C:/Windows/Fonts/arial.ttf';
            if (!file_exists($fuente)) {
                $fuente = __DIR__ . '/../../public/fonts/arial.ttf';
            }
            
            if (!file_exists($fuente)) {
                $titulo_decodificado = html_entity_decode($titulo, ENT_QUOTES, 'UTF-8');
                $ancho_titulo = strlen($titulo_decodificado) * imagefontwidth(5);
                $x_titulo = ($ancho - $ancho_titulo - $margen_der) / 2;
                imagestring($imagen, 5, $x_titulo, 30, $titulo_decodificado, $negro);
            } else {
                $x_titulo = ($ancho - $margen_der) / 2;
                imagefttext($imagen, 18, 0, $x_titulo - 100, 50, $negro, $fuente, $titulo);
            }
            
            // ÁREA DE LA GRÁFICA
            $x1 = $margen_izq;
            $y1 = $margen_sup;
            $x2 = $ancho - $margen_der;
            $y2 = $alto - $margen_inf;
            
            // Dibujar fondo del área de gráfica
            imagefilledrectangle($imagen, $x1, $y1, $x2, $y2, imagecolorallocate($imagen, 250, 250, 250));
            imagerectangle($imagen, $x1, $y1, $x2, $y2, $gris_medio);
            
            // Ejes
            imagesetthickness($imagen, 2);
            imageline($imagen, $x1, $y1, $x1, $y2, $gris_oscuro);
            imageline($imagen, $x1, $y2, $x2, $y2, $gris_oscuro);
            imagesetthickness($imagen, 1);
            
            // Calcular máximo
            $max_valor = max($datos['data']);
            if ($max_valor == 0) $max_valor = 1;
            
            // Grid horizontal
            $num_lineas = 5;
            for ($i = 0; $i <= $num_lineas; $i++) {
                $y_linea = $y2 - ($i * ($y2 - $y1) / $num_lineas);
                
                // Línea punteada
                for ($x = $x1; $x <= $x2; $x += 5) {
                    imagesetpixel($imagen, $x, $y_linea, $gris_medio);
                }
                
                // Etiquetas del eje Y
                $valor_linea = ($i * $max_valor / $num_lineas);
                $etiqueta = number_format($valor_linea, 0);
                imagestring($imagen, 2, $x1 - 45, $y_linea - 6, $etiqueta, $gris_oscuro);
            }
            
            // DIBUJAR BARRAS
            $num_barras = count($datos['data']);
            $ancho_disponible = $x2 - $x1 - 40;
            $ancho_barra = ($ancho_disponible / $num_barras) * 0.7;
            $espacio_barra = ($ancho_disponible / $num_barras) * 0.3;
            
            $x_actual = $x1 + 20;
            $max_valor_grafica = $max_valor * 1.1;
            
            foreach ($datos['data'] as $index => $valor) {
                $alto_barra = ($valor / $max_valor_grafica) * ($y2 - $y1 - 20);
                $y_barra = $y2 - $alto_barra - 10;
                
                // Color de la barra
                $color_idx = $index % count($colores_barras);
                $color_rgb = $colores_barras[$color_idx];
                $color_barra = imagecolorallocate($imagen, $color_rgb[0], $color_rgb[1], $color_rgb[2]);
                
                // Sombra sutil
                imagefilledrectangle($imagen, 
                    $x_actual + 2, $y_barra + 2, 
                    $x_actual + $ancho_barra + 2, $y2 - 8, 
                    imagecolorallocate($imagen, 220, 220, 220));
                
                // Barra principal
                imagefilledrectangle($imagen, 
                    $x_actual, $y_barra, 
                    $x_actual + $ancho_barra, $y2 - 10, 
                    $color_barra);
                
                // Borde
                imagerectangle($imagen, 
                    $x_actual, $y_barra, 
                    $x_actual + $ancho_barra, $y2 - 10, 
                    $negro);
                
                // VALOR ENCIMA DE LA BARRA
                $valor_texto = number_format($valor, 0);
                $ancho_valor = strlen($valor_texto) * imagefontwidth(3);
                $x_valor = $x_actual + ($ancho_barra - $ancho_valor) / 2;
                
                // Fondo blanco para el valor
                imagefilledrectangle($imagen, 
                    $x_valor - 3, $y_barra - 22, 
                    $x_valor + $ancho_valor + 3, $y_barra - 8, 
                    $blanco);
                imagerectangle($imagen, 
                    $x_valor - 3, $y_barra - 22, 
                    $x_valor + $ancho_valor + 3, $y_barra - 8, 
                    $gris_medio);
                
                imagestring($imagen, 3, $x_valor, $y_barra - 20, $valor_texto, $negro);
                
                // NÚMERO DE REFERENCIA (P1, P2, E1, E2) en la barra
                $num_ref = ($titulo == 'Ventas por Vendedor' ? 'E' : 'P') . ($index + 1);
                $ancho_ref = strlen($num_ref) * imagefontwidth(2);
                $x_ref = $x_actual + ($ancho_barra - $ancho_ref) / 2;
                
                // Fondo blanco para la referencia
                imagefilledrectangle($imagen, 
                    $x_ref - 3, $y_barra + 5, 
                    $x_ref + $ancho_ref + 3, $y_barra + 20, 
                    $blanco);
                imagerectangle($imagen, 
                    $x_ref - 3, $y_barra + 5, 
                    $x_ref + $ancho_ref + 3, $y_barra + 20, 
                    $gris_medio);
                
                imagestring($imagen, 2, $x_ref, $y_barra + 8, $num_ref, $negro);
                
                $x_actual += $ancho_barra + $espacio_barra;
            }
            
            // === SECCIÓN DE REFERENCIAS MEJORADA (COLUMNA DERECHA) ===
            $ref_x = $x2 + 40; // Más separación de la gráfica
            $ref_y = $y1 + 10;
            
            // Título de referencias con fondo
            imagefilledrectangle($imagen, $ref_x - 10, $ref_y - 20, $ref_x + 250, $ref_y, $azul);
            imagestring($imagen, 5, $ref_x, $ref_y - 15, 'REFERENCIAS', $blanco);
            
            // Línea separadora gruesa
            imagesetthickness($imagen, 2);
            imageline($imagen, $ref_x - 10, $ref_y + 5, $ref_x + 250, $ref_y + 5, $gris_medio);
            imagesetthickness($imagen, 1);
            
            foreach ($datos['labels'] as $index => $label) {
                $y_actual = $ref_y + ($index * 38) + 20; // Aumentado de 32 a 38 píxeles
                
                // Decodificar el label para acentos
                $label_decodificado = html_entity_decode($label, ENT_QUOTES, 'UTF-8');
                
                // Fondo alternado más notorio
                if ($index % 2 == 0) {
                    imagefilledrectangle($imagen, 
                        $ref_x - 10, $y_actual - 5, 
                        $ref_x + 250, $y_actual + 25, 
                        imagecolorallocate($imagen, 240, 240, 250));
                }
                
                // Círculo de color más grande
                $color_idx = $index % count($colores_barras);
                $color_rgb = $colores_barras[$color_idx];
                $color_circulo = imagecolorallocate($imagen, $color_rgb[0], $color_rgb[1], $color_rgb[2]);
                
                imagefilledellipse($imagen, $ref_x + 15, $y_actual + 10, 16, 16, $color_circulo);
                imageellipse($imagen, $ref_x + 15, $y_actual + 10, 16, 16, $negro);
                
                // Número de referencia en negrita
                $num_ref = ($titulo == 'Ventas por Vendedor' ? 'E' : 'P') . ($index + 1) . ':';
                imagestring($imagen, 3, $ref_x + 35, $y_actual + 5, $num_ref, $negro);
                
                // Nombre completo con acentos - con más espacio
                if (file_exists($fuente)) {
                    $nombre = $label_decodificado;
                    if (strlen($nombre) > 30) {
                        $nombre = mb_substr($nombre, 0, 27) . '...';
                    }
                    imagefttext($imagen, 11, 0, $ref_x + 90, $y_actual + 15, $azul, $fuente, $nombre);
                } else {
                    $nombre = $label_decodificado;
                    if (strlen($nombre) > 30) {
                        $nombre = substr($nombre, 0, 27) . '...';
                    }
                    imagestring($imagen, 3, $ref_x + 90, $y_actual + 5, $nombre, $azul);
                }
            }
            
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
     * Genera una gráfica de líneas con referencias mejoradas
     */
    public static function generarLinea($datos, $titulo = 'Gráfica de Líneas')
    {
        if (empty($datos['labels']) || empty($datos['data'])) {
            return null;
        }

        try {
            // Dimensiones - MÁS ESPACIO
            $ancho = 1200;
            $alto = 650;
            $margen_izq = 100;
            $margen_der = 300;
            $margen_inf = 100;
            $margen_sup = 100;
            
            // Crear imagen
            $imagen = imagecreate($ancho, $alto);
            
            // Colores
            $blanco = imagecolorallocate($imagen, 255, 255, 255);
            $negro = imagecolorallocate($imagen, 44, 62, 80);
            $gris_claro = imagecolorallocate($imagen, 245, 245, 245);
            $gris_medio = imagecolorallocate($imagen, 200, 200, 200);
            $gris_oscuro = imagecolorallocate($imagen, 100, 100, 100);
            $azul = imagecolorallocate($imagen, 52, 152, 219);
            $rojo = imagecolorallocate($imagen, 231, 76, 60);
            
            // Fondo blanco
            imagefill($imagen, 0, 0, $blanco);
            
            // TÍTULO CON ACENTOS
            $fuente = 'C:/Windows/Fonts/arial.ttf';
            if (!file_exists($fuente)) {
                $fuente = __DIR__ . '/../../public/fonts/arial.ttf';
            }
            
            if (!file_exists($fuente)) {
                $titulo_decodificado = html_entity_decode($titulo, ENT_QUOTES, 'UTF-8');
                $ancho_titulo = strlen($titulo_decodificado) * imagefontwidth(5);
                $x_titulo = ($ancho - $ancho_titulo - $margen_der) / 2;
                imagestring($imagen, 5, $x_titulo, 30, $titulo_decodificado, $negro);
            } else {
                $x_titulo = ($ancho - $margen_der) / 2;
                imagefttext($imagen, 18, 0, $x_titulo - 100, 50, $negro, $fuente, $titulo);
            }
            
            // Área de gráfica
            $x1 = $margen_izq;
            $y1 = $margen_sup;
            $x2 = $ancho - $margen_der;
            $y2 = $alto - $margen_inf;
            
            imagefilledrectangle($imagen, $x1, $y1, $x2, $y2, imagecolorallocate($imagen, 250, 250, 250));
            imagerectangle($imagen, $x1, $y1, $x2, $y2, $gris_medio);
            
            // Ejes
            imagesetthickness($imagen, 2);
            imageline($imagen, $x1, $y1, $x1, $y2, $gris_oscuro);
            imageline($imagen, $x1, $y2, $x2, $y2, $gris_oscuro);
            imagesetthickness($imagen, 1);
            
            // Calcular máximo
            $max_valor = max($datos['data']);
            if ($max_valor == 0) $max_valor = 1;
            $max_valor_grafica = $max_valor * 1.1;
            
            // Grid
            $num_lineas = 5;
            for ($i = 0; $i <= $num_lineas; $i++) {
                $y_linea = $y2 - ($i * ($y2 - $y1) / $num_lineas);
                
                for ($x = $x1; $x <= $x2; $x += 5) {
                    imagesetpixel($imagen, $x, $y_linea, $gris_medio);
                }
                
                $valor_linea = ($i * $max_valor / $num_lineas);
                $etiqueta = number_format($valor_linea, 0);
                imagestring($imagen, 2, $x1 - 45, $y_linea - 6, $etiqueta, $gris_oscuro);
            }
            
            // Dibujar puntos
            $num_puntos = count($datos['data']);
            $ancho_paso = ($x2 - $x1 - 40) / ($num_puntos - 1);
            
            $puntos_x = [];
            $puntos_y = [];
            
            $x_actual = $x1 + 20;
            
            foreach ($datos['data'] as $index => $valor) {
                $y_punto = $y2 - ($valor / $max_valor_grafica) * ($y2 - $y1 - 20) - 10;
                $puntos_x[] = $x_actual;
                $puntos_y[] = $y_punto;
                $x_actual += $ancho_paso;
            }
            
            // Dibujar línea
            imagesetthickness($imagen, 3);
            for ($i = 0; $i < $num_puntos - 1; $i++) {
                imageline($imagen, 
                    $puntos_x[$i], $puntos_y[$i], 
                    $puntos_x[$i + 1], $puntos_y[$i + 1], 
                    $azul);
            }
            
            // Dibujar puntos con referencias
            for ($i = 0; $i < $num_puntos; $i++) {
                // Punto
                imagefilledellipse($imagen, $puntos_x[$i], $puntos_y[$i], 12, 12, $azul);
                imageellipse($imagen, $puntos_x[$i], $puntos_y[$i], 12, 12, $negro);
                
                // Valor
                $valor_texto = number_format($datos['data'][$i], 0);
                $ancho_valor = strlen($valor_texto) * imagefontwidth(2);
                $x_valor = $puntos_x[$i] - $ancho_valor / 2;
                
                imagefilledrectangle($imagen, 
                    $x_valor - 3, $puntos_y[$i] - 28, 
                    $x_valor + $ancho_valor + 3, $puntos_y[$i] - 15, 
                    $blanco);
                imagerectangle($imagen, 
                    $x_valor - 3, $puntos_y[$i] - 28, 
                    $x_valor + $ancho_valor + 3, $puntos_y[$i] - 15, 
                    $gris_medio);
                
                imagestring($imagen, 2, $x_valor, $puntos_y[$i] - 25, $valor_texto, $negro);
                
                // Número de referencia
                $num_ref = 'P' . ($i + 1);
                $ancho_ref = strlen($num_ref) * imagefontwidth(2);
                $x_ref = $puntos_x[$i] - $ancho_ref / 2;
                
                imagefilledrectangle($imagen, 
                    $x_ref - 3, $puntos_y[$i] + 15, 
                    $x_ref + $ancho_ref + 3, $puntos_y[$i] + 28, 
                    $blanco);
                imagerectangle($imagen, 
                    $x_ref - 3, $puntos_y[$i] + 15, 
                    $x_ref + $ancho_ref + 3, $puntos_y[$i] + 28, 
                    $gris_medio);
                
                imagestring($imagen, 2, $x_ref, $puntos_y[$i] + 18, $num_ref, $rojo);
            }
            
            // Etiquetas X
            $x_actual = $x1 + 20;
            foreach ($datos['labels'] as $index => $label) {
                $label_decodificado = html_entity_decode($label, ENT_QUOTES, 'UTF-8');
                $label_corto = strlen($label_decodificado) > 8 ? mb_substr($label_decodificado, 0, 6) . '...' : $label_decodificado;
                $ancho_label = strlen($label_corto) * imagefontwidth(2);
                $x_label = $x_actual - $ancho_label / 2;
                
                imagestring($imagen, 2, $x_label, $y2 + 5, $label_corto, $negro);
                
                $x_actual += $ancho_paso;
            }
            
            // === SECCIÓN DE REFERENCIAS MEJORADA ===
            $ref_x = $x2 + 40;
            $ref_y = $y1 + 10;
            
            // Título de referencias
            imagefilledrectangle($imagen, $ref_x - 10, $ref_y - 20, $ref_x + 250, $ref_y, $azul);
            imagestring($imagen, 5, $ref_x, $ref_y - 15, 'PUNTOS DE LA GRÁFICA', $blanco);
            
            imagesetthickness($imagen, 2);
            imageline($imagen, $ref_x - 10, $ref_y + 5, $ref_x + 250, $ref_y + 5, $gris_medio);
            imagesetthickness($imagen, 1);
            
            foreach ($datos['labels'] as $index => $label) {
                $y_actual = $ref_y + ($index * 38) + 20;
                $label_decodificado = html_entity_decode($label, ENT_QUOTES, 'UTF-8');
                
                // Fondo alternado
                if ($index % 2 == 0) {
                    imagefilledrectangle($imagen, 
                        $ref_x - 10, $y_actual - 5, 
                        $ref_x + 250, $y_actual + 25, 
                        imagecolorallocate($imagen, 240, 240, 250));
                }
                
                // Círculo
                imagefilledellipse($imagen, $ref_x + 15, $y_actual + 10, 14, 14, $azul);
                imageellipse($imagen, $ref_x + 15, $y_actual + 10, 14, 14, $negro);
                
                // Número
                $num_ref = 'P' . ($index + 1) . ':';
                imagestring($imagen, 3, $ref_x + 35, $y_actual + 5, $num_ref, $rojo);
                
                // Nombre con acentos
                if (file_exists($fuente)) {
                    $nombre = $label_decodificado;
                    if (strlen($nombre) > 30) {
                        $nombre = mb_substr($nombre, 0, 27) . '...';
                    }
                    imagefttext($imagen, 11, 0, $ref_x + 90, $y_actual + 15, $negro, $fuente, $nombre);
                } else {
                    $nombre = $label_decodificado;
                    if (strlen($nombre) > 30) {
                        $nombre = substr($nombre, 0, 27) . '...';
                    }
                    imagestring($imagen, 3, $ref_x + 90, $y_actual + 5, $nombre, $negro);
                }
            }
            
            imagesetthickness($imagen, 1);
            
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