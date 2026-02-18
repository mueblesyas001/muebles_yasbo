<?php
echo "<h2>Verificación de Extensiones PHP</h2>";
$extensiones = ['zip', 'gd', 'fileinfo', 'mbstring', 'openssl'];
foreach ($extensiones as $ext) {
    echo $ext . ": " . (extension_loaded($ext) ? '✅ HABILITADA' : '❌ FALTA') . "<br>";
}
?>