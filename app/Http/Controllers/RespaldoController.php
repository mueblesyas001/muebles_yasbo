<?php

namespace App\Http\Controllers;

use App\Models\Respaldo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PDO;

class RespaldoController extends Controller
{
    // ===========================================
    // MÉTODOS PÚBLICOS PRINCIPALES
    // ===========================================

    public function index(){
        try {
            // Obtener respaldos sin relaciones problemáticas
            $respaldos = Respaldo::orderBy('Fecha', 'desc')->get();
            
            // Cargar usuarios manualmente para evitar errores de relación
            $usuariosIds = $respaldos->pluck('Usuario')->unique()->filter()->values();
            $usuarios = [];
            
            if ($usuariosIds->count() > 0) {
                // Buscar usuarios por ID (la columna es 'id' en la tabla usuarios)
                $usuariosData = Usuario::whereIn('id', $usuariosIds)->get();
                foreach ($usuariosData as $usuario) {
                    // Usar 'id' como clave
                    $usuarios[$usuario->id] = $usuario;
                }
            }
            
            // Pasar ambos arrays a la vista
            return view('respaldos.index', [
                'respaldos' => $respaldos,
                'usuarios' => $usuarios,
                'controller' => $this
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en index de respaldos: ' . $e->getMessage());
            return $this->indexAlternativo();
        }
    }

    public function create(){
        $usuarioLogueado = auth()->user();
        $usuarios = $usuarioLogueado ? [auth()->user()] : [];
        $estadisticas = $this->obtenerEstadisticasBaseDatos();
        
        return view('respaldos.create', compact('usuarios', 'estadisticas', 'usuarioLogueado'));
    }

    public function store(Request $request){
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $fecha = Carbon::now()->format('Y-m-d_His');
            $nombreArchivo = "backup_{$fecha}.sql";
            $rutaCarpeta = storage_path('app/backups/');
            $rutaCompleta = $rutaCarpeta . $nombreArchivo;
            
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }
            
            \Log::info('=== INICIANDO RESPALDO ===');
            \Log::info('Ruta: ' . $rutaCompleta);
            
            $fileSize = $this->generarBackupPHP($rutaCompleta);
            
            if (!file_exists($rutaCompleta)) {
                throw new \Exception('No se pudo crear el archivo de respaldo');
            }
            
            if ($fileSize === 0) {
                throw new \Exception('El archivo de respaldo está vacío');
            }
            
            $usuarioId = auth()->check() ? auth()->id() : null;
            
            $respaldo = Respaldo::create([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Ruta' => $rutaCompleta,
                'Fecha' => Carbon::now(),
                'Usuario' => $usuarioId,
                'Tamaño' => $fileSize,
            ]);
            
            \Log::info('=== RESPALDO COMPLETADO ===');
            
            return redirect()->route('respaldos.index')
                ->with('success', 'Respaldo creado exitosamente! Tamaño: ' . $this->formatearTamaño($fileSize))
                ->with('nuevo_respaldo_id', $respaldo->id);

        } catch (\Exception $e) {
            \Log::error('Error en store(): ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el respaldo: ' . $e->getMessage());
        }
    }

    public function show(Respaldo $respaldo)
    {
        $archivoExiste = file_exists($respaldo->Ruta);
        $tamaño = $archivoExiste ? $this->formatearTamaño(filesize($respaldo->Ruta)) : 'No disponible';
        $usuario = Usuario::find($respaldo->Usuario);
        
        return view('respaldos.show', compact('respaldo', 'archivoExiste', 'tamaño', 'usuario'));
    }

    public function edit($id){
        $respaldo = Respaldo::findOrFail($id);
        return view('respaldos.edit', compact('respaldo'));
    }

    public function update(Request $request, Respaldo $respaldo)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'nullable|string|max:500',
        ]);

        $respaldo->update([
            'Nombre' => $request->Nombre,
            'Descripcion' => $request->Descripcion,
        ]);

        return redirect()->route('respaldos.index')
            ->with('success', 'Respaldo actualizado exitosamente!');
    }

    public function destroy(Respaldo $respaldo)
    {
        try {
            if (file_exists($respaldo->Ruta)) {
                if (!unlink($respaldo->Ruta)) {
                    throw new \Exception('No se pudo eliminar el archivo físico.');
                }
            }
            
            $respaldo->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Respaldo eliminado exitosamente!'
                ]);
            }
            
            return redirect()->route('respaldos.index')
                ->with('success', 'Respaldo eliminado exitosamente!');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el respaldo: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('respaldos.index')
                ->with('error', 'Error al eliminar el respaldo: ' . $e->getMessage());
        }
    }

    // ===========================================
    // MÉTODOS DE RESPALDO Y RESTAURACIÓN
    // ===========================================

    public function confirmarRestauracion($id){
        $respaldo = Respaldo::findOrFail($id);
        $archivoExiste = file_exists($respaldo->Ruta);
        
        if (!$archivoExiste) {
            return redirect()->route('respaldos.index')
                ->with('error', 'El archivo de respaldo no existe.');
        }
        
        $fileSize = filesize($respaldo->Ruta);
        $lastModified = date('Y-m-d H:i:s', filemtime($respaldo->Ruta));
        $registrosRespaldo = Respaldo::count();
        
        return view('respaldos.restaurar', compact('respaldo', 'fileSize', 'lastModified', 'registrosRespaldo'));
    }

    public function restaurar(Request $request, $id){
        $request->validate([
            'confirmacion' => 'required|in:CONFIRMAR',
        ], [
            'confirmacion.required' => 'Debe escribir CONFIRMAR para proceder',
            'confirmacion.in' => 'Debe escribir exactamente CONFIRMAR'
        ]);

        $respaldo = Respaldo::findOrFail($id);
        
        if (!file_exists($respaldo->Ruta)) {
            return redirect()->route('respaldos.index')
                ->with('error', 'El archivo de respaldo no existe en el servidor.');
        }
        
        if (filesize($respaldo->Ruta) === 0) {
            return redirect()->route('respaldos.index')
                ->with('error', 'El archivo de respaldo está vacío.');
        }
        
        try {
            $backupSeguridad = $this->crearBackupSeguridad();
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            
            $restaurado = $this->restaurarConPHP($respaldo->Ruta);
            
            if (!$restaurado) {
                throw new \Exception('No se pudo restaurar la base de datos.');
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            $countRespaldo = DB::table('respaldo')->count();
            
            if ($countRespaldo == 0) {
                try {
                    DB::table('respaldo')->insert([
                        'id' => $respaldo->id,
                        'Nombre' => $respaldo->Nombre,
                        'Descripcion' => $respaldo->Descripcion,
                        'Ruta' => $respaldo->Ruta,
                        'Fecha' => $respaldo->Fecha,
                        'Usuario' => $respaldo->Usuario,
                        'Tamaño' => $respaldo->Tamaño,
                        'created_at' => $respaldo->created_at,
                        'updated_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('No se pudo recuperar el respaldo: ' . $e->getMessage());
                }
            }
            
            if (Schema::hasTable('restauraciones')) {
                try {
                    DB::table('restauraciones')->insert([
                        'IDRespaldo' => $respaldo->id,
                        'IDUsuario' => auth()->check() ? auth()->id() : null,
                        'Fecha' => Carbon::now(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('No se pudo registrar la restauración: ' . $e->getMessage());
                }
            }
            
            \Log::info('=== RESTAURACIÓN COMPLETADA EXITOSAMENTE ===');
            
            return redirect()->route('respaldos.index')
                ->with('success', '¡Base de datos restaurada exitosamente desde: ' . $respaldo->Nombre . '!')
                ->with('info', 'Todos los datos fueron reemplazados por los del respaldo.');

        } catch (\Exception $e) {
            \Log::error('Error en restaurar(): ' . $e->getMessage());
            
            if (isset($backupSeguridad) && file_exists($backupSeguridad)) {
                try {
                    $this->restaurarConPHP($backupSeguridad);
                    \Log::info('Backup de seguridad restaurado');
                } catch (\Exception $restoreError) {
                    \Log::error('No se pudo restaurar backup de seguridad: ' . $restoreError->getMessage());
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            return redirect()->route('respaldos.index')
                ->with('error', 'Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }

    public function restaurarDesdeArchivo(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt,gz,zip|max:102400',
            'confirmacion' => 'required|in:CONFIRMAR',
        ], [
            'confirmacion.required' => 'Debe escribir CONFIRMAR para proceder',
            'confirmacion.in' => 'Debe escribir exactamente CONFIRMAR'
        ]);

        try {
            $file = $request->file('sql_file');
            
            \Log::info('=== INICIANDO RESTAURACIÓN DESDE ARCHIVO SUBIDO ===');
            \Log::info('Archivo original: ' . $file->getClientOriginalName());
            \Log::info('Tamaño: ' . $file->getSize() . ' bytes');
            
            if ($request->has('backup_current') && $request->backup_current == '1') {
                $this->crearRespaldoPrevioRestauracion();
            }
            
            $filePath = $file->getPathname();
            $extension = $file->getClientOriginalExtension();
            
            if (in_array($extension, ['gz', 'zip'])) {
                $filePath = $this->descomprimirArchivoBackup($filePath, $extension);
            }
            
            if (!$this->validarArchivoSQL($filePath)) {
                throw new \Exception('El archivo no parece ser un respaldo SQL válido');
            }
            
            $backupSeguridad = $this->crearBackupSeguridad();
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            
            $restaurado = $this->restaurarConPHP($filePath);
            
            if (!$restaurado) {
                throw new \Exception('No se pudo restaurar la base de datos desde el archivo subido.');
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            $this->verificarRestauracionExitosa();
            
            if ($filePath !== $file->getPathname()) {
                @unlink($filePath);
            }
            
            \Log::info('=== RESTAURACIÓN DESDE ARCHIVO COMPLETADA EXITOSAMENTE ===');
            
            return redirect()->route('respaldos.index')
                ->with('success', 'Base de datos restaurada exitosamente desde: ' . $file->getClientOriginalName())
                ->with('info', 'Todos los datos actuales fueron reemplazados por los del respaldo.');

        } catch (\Exception $e) {
            \Log::error('Error en restaurarDesdeArchivo(): ' . $e->getMessage());
            
            if (isset($backupSeguridad) && file_exists($backupSeguridad)) {
                try {
                    $this->restaurarConPHP($backupSeguridad);
                } catch (\Exception $restoreError) {
                    \Log::error('No se pudo restaurar backup de seguridad: ' . $restoreError->getMessage());
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            return redirect()->route('respaldos.index')
                ->with('error', 'Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }

    public function descargar($id)
    {
        $respaldo = Respaldo::findOrFail($id);
        
        if (!file_exists($respaldo->Ruta)) {
            return redirect()->route('respaldos.index')
                ->with('error', 'El archivo de respaldo no existe en el servidor.');
        }
        
        $nombreArchivo = basename($respaldo->Ruta);
        
        return response()->download($respaldo->Ruta, $nombreArchivo, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ]);
    }

    public function generarManual(Request $request)
    {
        try {
            $fecha = Carbon::now()->format('Y-m-d_His');
            $nombreArchivo = "backup_rapido_{$fecha}.sql";
            $rutaCarpeta = storage_path('app/backups/');
            $rutaCompleta = $rutaCarpeta . $nombreArchivo;
            
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }
            
            $fileSize = $this->generarBackupPHP($rutaCompleta);
            
            if ($fileSize === 0) {
                throw new \Exception('No se pudo generar el respaldo rápido.');
            }
            
            $usuarioId = auth()->check() ? auth()->id() : null;
            
            $respaldo = Respaldo::create([
                'Nombre' => 'Respaldo Rápido ' . Carbon::now()->format('d/m/Y H:i'),
                'Descripcion' => 'Respaldo automático generado por sistema',
                'Ruta' => $rutaCompleta,
                'Fecha' => Carbon::now(),
                'Usuario' => $usuarioId,
                'Tamaño' => $fileSize,
            ]);
            
            return redirect()->route('respaldos.index')
                ->with('success', 'Respaldo rápido creado exitosamente! Tamaño: ' . $this->formatearTamaño($fileSize))
                ->with('nuevo_respaldo_id', $respaldo->id);

        } catch (\Exception $e) {
            return redirect()->route('respaldos.index')
                ->with('error', 'Error al generar respaldo rápido: ' . $e->getMessage());
        }
    }

    public function info($id)
    {
        $respaldo = Respaldo::findOrFail($id);
        $archivoExiste = file_exists($respaldo->Ruta);
        $usuario = Usuario::find($respaldo->Usuario);
        
        return response()->json([
            'id' => $respaldo->id,
            'nombre' => $respaldo->Nombre,
            'descripcion' => $respaldo->Descripcion,
            'archivo' => basename($respaldo->Ruta),
            'ruta_completa' => $respaldo->Ruta,
            'fecha' => $respaldo->Fecha,
            'usuario' => $usuario ? $usuario->correo : 'N/A',
            'tamaño' => $archivoExiste ? $this->formatearTamaño(filesize($respaldo->Ruta)) : 'No disponible',
            'existe_archivo' => $archivoExiste,
            'fecha_formateada' => Carbon::parse($respaldo->Fecha)->format('d/m/Y H:i:s')
        ]);
    }

    public function forzarDescarga($id)
    {
        $respaldo = Respaldo::findOrFail($id);
        
        if (!file_exists($respaldo->Ruta)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo no existe en el servidor.'
            ], 404);
        }
        
        $nombreArchivo = basename($respaldo->Ruta);
        
        return response()->streamDownload(function () use ($respaldo) {
            echo file_get_contents($respaldo->Ruta);
        }, $nombreArchivo, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ]);
    }

    public function verificarEstado($id)
    {
        $respaldo = Respaldo::findOrFail($id);
        $archivoExiste = file_exists($respaldo->Ruta);
        
        return response()->json([
            'id' => $respaldo->id,
            'nombre' => $respaldo->Nombre,
            'archivo_existe' => $archivoExiste,
            'tamaño' => $archivoExiste ? $this->formatearTamaño(filesize($respaldo->Ruta)) : 'N/A',
            'ultima_modificacion' => $archivoExiste ? date('Y-m-d H:i:s', filemtime($respaldo->Ruta)) : 'N/A'
        ]);
    }

    public function verificarConexion()
    {
        try {
            $config = config('database.connections.mysql');
            $database = $config['database'];
            
            DB::connection()->getPdo();
            
            $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [$database]);
            
            $backupDir = storage_path('app/backups/');
            $dirWritable = is_writable($backupDir) || (!file_exists($backupDir) && is_writable(storage_path('app')));
            
            $tablasExistentes = [];
            $tablasImportantes = ['respaldo', 'usuarios', 'restauraciones'];
            foreach ($tablasImportantes as $tabla) {
                $tablasExistentes[$tabla] = Schema::hasTable($tabla);
            }
            
            return response()->json([
                'conexion_bd' => 'OK',
                'tablas_encontradas' => count($tables),
                'base_datos' => $database,
                'carpeta_backups' => $dirWritable ? 'Escribible' : 'No escribible',
                'ruta_backups' => $backupDir,
                'tablas_importantes' => $tablasExistentes,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'conexion_bd' => 'FALLÓ'
            ], 500);
        }
    }

    public function diagnosticar()
    {
        try {
            $database = config('database.connections.mysql.database');
            
            $tables1 = DB::select("SHOW TABLES");
            $tables2 = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [$database]);
            
            $tablasVerificar = ['respaldo', 'usuarios', 'restauraciones'];
            $estadoTablas = [];
            foreach ($tablasVerificar as $tabla) {
                $estadoTablas[$tabla] = [
                    'existe' => Schema::hasTable($tabla),
                    'registros' => Schema::hasTable($tabla) ? DB::table($tabla)->count() : 0
                ];
            }
            
            $backupDir = storage_path('app/backups/');
            $archivosBackup = [];
            if (file_exists($backupDir)) {
                $archivos = glob($backupDir . '*.sql');
                foreach ($archivos as $archivo) {
                    $archivosBackup[] = [
                        'nombre' => basename($archivo),
                        'tamaño' => filesize($archivo),
                        'modificacion' => date('Y-m-d H:i:s', filemtime($archivo))
                    ];
                }
            }
            
            return response()->json([
                'estado' => 'OK',
                'base_datos' => $database,
                'metodo_show_tables' => count($tables1),
                'metodo_information_schema' => count($tables2),
                'tablas_verificadas' => $estadoTablas,
                'archivos_respaldo' => $archivosBackup,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'estado' => 'ERROR',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    // ===========================================
    // MÉTODOS PRIVADOS DE RESPALDO
    // ===========================================

    private function indexAlternativo()
    {
        try {
            $respaldos = \DB::table('respaldo')
                ->leftJoin('usuarios', 'respaldo.Usuario', '=', 'usuarios.id')
                ->select(
                    'respaldo.*',
                    'usuarios.correo as usuario_correo',
                    'usuarios.id as usuario_id'
                )
                ->orderBy('respaldo.Fecha', 'desc')
                ->get();
            
            return view('respaldos.index', [
                'respaldos' => $respaldos,
                'usuarios' => [],
                'controller' => $this,
                'usar_join' => true
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en index alternativo: ' . $e->getMessage());
            $respaldos = Respaldo::orderBy('Fecha', 'desc')->get();
            
            return view('respaldos.index', [
                'respaldos' => $respaldos,
                'usuarios' => [],
                'controller' => $this
            ]);
        }
    }

    private function obtenerEstadisticasBaseDatos()
    {
        try {
            $database = config('database.connections.mysql.database');
            
            $totalTablas = DB::select("SELECT COUNT(*) as count FROM information_schema.tables 
                                    WHERE table_schema = ?", [$database])[0]->count ?? 0;
            
            $tamañoQuery = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = ?
                GROUP BY table_schema
            ", [$database]);
            
            $tamañoTotal = $tamañoQuery[0]->size_mb ?? 0;
            $ultimoRespaldo = Respaldo::latest('Fecha')->first();
            
            return [
                'total_tablas' => $totalTablas,
                'tamaño_total' => number_format($tamañoTotal, 2) . ' MB',
                'ultimo_respaldo' => $ultimoRespaldo ? $ultimoRespaldo->Nombre : 'Nunca',
                'ultimo_respaldo_fecha' => $ultimoRespaldo ? $ultimoRespaldo->Fecha : null,
            ];
            
        } catch (\Exception $e) {
            return [
                'total_tablas' => 'Error',
                'tamaño_total' => 'Error',
                'ultimo_respaldo' => 'Nunca',
                'ultimo_respaldo_fecha' => null,
            ];
        }
    }

    private function generarBackupPHP($rutaDestino)
    {
        try {
            $database = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            
            $backupContent = "-- ===========================================\n";
            $backupContent .= "-- RESPALDO COMPLETO DE BASE DE DATOS\n";
            $backupContent .= "-- ===========================================\n";
            $backupContent .= "-- Fecha: " . Carbon::now()->toDateTimeString() . "\n";
            $backupContent .= "-- Host: {$host}\n";
            $backupContent .= "-- Base de datos: {$database}\n";
            $backupContent .= "-- ===========================================\n\n";
            
            $backupContent .= "SET FOREIGN_KEY_CHECKS=0;\n";
            $backupContent .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $backupContent .= "START TRANSACTION;\n\n";
            
            $tables = DB::select("
                SELECT TABLE_NAME 
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = ? 
                ORDER BY TABLE_NAME
            ", [$database]);
            
            \Log::info('Total tablas a respaldar: ' . count($tables));
            
            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;
                \Log::info('Procesando tabla: ' . $tableName);
                
                $createResult = DB::select("SHOW CREATE TABLE `{$tableName}`");
                
                if (!empty($createResult)) {
                    $createRow = (array)$createResult[0];
                    $createTable = $createRow['Create Table'];
                    
                    $backupContent .= "\n-- --------------------------------------------------------\n\n";
                    $backupContent .= "--\n-- Estructura de tabla para la tabla `{$tableName}`\n--\n\n";
                    $backupContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                    $backupContent .= $createTable . ";\n\n";
                    
                    try {
                        $totalRows = DB::table($tableName)->count();
                        
                        if ($totalRows > 0) {
                            $backupContent .= "--\n-- Volcado de datos para la tabla `{$tableName}` ({$totalRows} registros)\n--\n\n";
                            
                            $columns = [];
                            $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}`");
                            foreach ($columnInfo as $col) {
                                $colArray = (array)$col;
                                $columns[] = $colArray['Field'];
                            }
                            
                            $chunkSize = 1000;
                            
                            DB::table($tableName)->orderBy($columns[0])->chunk($chunkSize, function($rows) use (&$backupContent, $tableName, $columns) {
                                $insertStatements = [];
                                
                                foreach ($rows as $row) {
                                    $rowArray = (array)$row;
                                    $values = [];
                                    
                                    foreach ($columns as $column) {
                                        $value = $rowArray[$column] ?? null;
                                        
                                        if ($value === null) {
                                            $values[] = 'NULL';
                                        } elseif (is_numeric($value) && !is_string($value)) {
                                            $values[] = $value;
                                        } else {
                                            $escapedValue = str_replace("'", "''", $value);
                                            $escapedValue = str_replace("\\", "\\\\", $escapedValue);
                                            $values[] = "'" . $escapedValue . "'";
                                        }
                                    }
                                    
                                    $insertStatements[] = "(" . implode(', ', $values) . ")";
                                }
                                
                                if (!empty($insertStatements)) {
                                    $backupContent .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES \n";
                                    $backupContent .= implode(",\n", $insertStatements) . ";\n\n";
                                }
                            });
                        } else {
                            $backupContent .= "--\n-- Tabla `{$tableName}` está vacía\n--\n\n";
                        }
                    } catch (\Exception $e) {
                        \Log::warning("Error obteniendo datos de tabla {$tableName}: " . $e->getMessage());
                        $backupContent .= "--\n-- Error obteniendo datos: " . $e->getMessage() . "\n--\n\n";
                    }
                }
            }
            
            $backupContent .= "\nSET FOREIGN_KEY_CHECKS=1;\nCOMMIT;\n";
            $backupContent .= "\n-- ===========================================\n";
            $backupContent .= "-- FIN DEL RESPALDO\n";
            $backupContent .= "-- ===========================================\n";
            
            file_put_contents($rutaDestino, $backupContent);
            
            return filesize($rutaDestino);
            
        } catch (\Exception $e) {
            \Log::error('Error en backup PHP: ' . $e->getMessage());
            return $this->generarBackupPHPSimple($rutaDestino);
        }
    }

    private function generarBackupPHPSimple($rutaDestino)
    {
        try {
            $database = config('database.connections.mysql.database');
            
            $backupContent = "-- Backup generado con método simple\n";
            $backupContent .= "-- Fecha: " . Carbon::now()->toDateTimeString() . "\n";
            $backupContent .= "-- Base de datos: {$database}\n\n";
            $backupContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            $tables = DB::select("
                SELECT TABLE_NAME 
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = ?
            ", [$database]);
            
            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;
                
                $createResult = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createResult)) {
                    $createRow = (array)$createResult[0];
                    $backupContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                    $backupContent .= $createRow['Create Table'] . ";\n\n";
                }
                
                $rows = DB::select("SELECT * FROM `{$tableName}`");
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $rowArray = (array)$row;
                        $columns = array_keys($rowArray);
                        $values = [];
                        
                        foreach ($rowArray as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($value)) {
                                $values[] = $value;
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        
                        $backupContent .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backupContent .= "\n";
                }
            }
            
            $backupContent .= "SET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents($rutaDestino, $backupContent);
            
            return filesize($rutaDestino);
            
        } catch (\Exception $e) {
            \Log::error('Error en backup PHP simple: ' . $e->getMessage());
            throw $e;
        }
    }

    private function restaurarConPHP($archivoSQL)
    {
        try {
            $sqlContent = file_get_contents($archivoSQL);
            
            if (empty($sqlContent)) {
                throw new \Exception('El archivo SQL está vacío');
            }
            
            \Log::info('Iniciando restauración. Tamaño del archivo: ' . strlen($sqlContent) . ' bytes');
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement('SET AUTOCOMMIT = 0');
            
            $queries = $this->splitSQLFile($sqlContent);
            \Log::info('Número de consultas a ejecutar: ' . count($queries));
            
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($queries as $index => $query) {
                $query = trim($query);
                
                if (empty($query) || strpos($query, '--') === 0) {
                    continue;
                }
                
                try {
                    DB::statement($query);
                    $successCount++;
                    
                    if ($successCount % 100 === 0) {
                        \Log::info("Consultas ejecutadas: {$successCount}/" . count($queries));
                    }
                    
                } catch (\Exception $e) {
                    $errorCount++;
                    if (strpos($query, 'DROP TABLE') === false && 
                        strpos($query, 'INSERT INTO') === false) {
                        \Log::warning("Error en consulta #{$index}: " . $e->getMessage());
                    }
                    continue;
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::statement('COMMIT');
            
            \Log::info("Restauración completada. Éxitos: {$successCount}, Errores: {$errorCount}");
            
            return $successCount > 0;
            
        } catch (\Exception $e) {
            try {
                DB::statement('ROLLBACK');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            } catch (\Exception $rollbackError) {
                \Log::error('Error en rollback: ' . $rollbackError->getMessage());
            }
            
            \Log::error('Error en restauración PHP: ' . $e->getMessage());
            throw $e;
        }
    }

    private function splitSQLFile($sql)
    {
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = str_replace(["\r\n", "\r"], "\n", $sql);
        
        $queries = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $escaped = false;
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            
            if (!$escaped && ($char === "'" || $char === '"' || $char === '`')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($stringChar === $char) {
                    $inString = false;
                }
            }
            
            if ($char === '\\' && !$escaped) {
                $escaped = true;
            } else {
                $escaped = false;
            }
            
            $current .= $char;
            
            if (!$inString && $char === ';') {
                $queries[] = trim($current);
                $current = '';
            }
        }
        
        if (!empty(trim($current))) {
            $queries[] = trim($current);
        }
        
        return array_filter($queries, function($query) {
            $query = trim($query);
            return !empty($query) && strlen($query) > 3 && strpos($query, '--') !== 0;
        });
    }

    private function crearBackupSeguridad()
    {
        try {
            $fecha = Carbon::now()->format('Y-m-d_His');
            $nombreArchivo = "seguridad_pre_restauracion_{$fecha}.sql";
            $rutaCarpeta = storage_path('app/backups/seguridad/');
            $rutaCompleta = $rutaCarpeta . $nombreArchivo;
            
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }
            
            $this->generarBackupPHP($rutaCompleta);
            \Log::info('Backup de seguridad creado: ' . $rutaCompleta);
            
            return $rutaCompleta;
            
        } catch (\Exception $e) {
            \Log::warning('No se pudo crear backup de seguridad: ' . $e->getMessage());
            return null;
        }
    }

    private function crearRespaldoPrevioRestauracion()
    {
        try {
            $fecha = Carbon::now()->format('Y-m-d_His');
            $nombreArchivo = "backup_previo_restauracion_{$fecha}.sql";
            $rutaCarpeta = storage_path('app/backups/previos/');
            $rutaCompleta = $rutaCarpeta . $nombreArchivo;
            
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }
            
            $fileSize = $this->generarBackupPHP($rutaCompleta);
            
            if ($fileSize > 0) {
                Respaldo::create([
                    'Nombre' => 'Backup Previo a Restauración ' . Carbon::now()->format('d/m/Y H:i:s'),
                    'Descripcion' => 'Respaldo automático generado antes de restaurar desde archivo',
                    'Ruta' => $rutaCompleta,
                    'Fecha' => Carbon::now(),
                    'Usuario' => auth()->check() ? auth()->id() : null,
                    'Tamaño' => $fileSize,
                ]);
                
                \Log::info('Respaldo previo creado: ' . $rutaCompleta);
            }
            
            return $rutaCompleta;
            
        } catch (\Exception $e) {
            \Log::warning('No se pudo crear respaldo previo: ' . $e->getMessage());
            return null;
        }
    }

    private function descomprimirArchivoBackup($filePath, $extension)
    {
        $tempDir = storage_path('app/temp_backup_' . uniqid());
        
        try {
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            if ($extension === 'gz') {
                $outputFile = $tempDir . '/backup.sql';
                $this->descomprimirGz($filePath, $outputFile);
                return $outputFile;
                
            } elseif ($extension === 'zip') {
                $zip = new \ZipArchive();
                if ($zip->open($filePath) === true) {
                    $zip->extractTo($tempDir);
                    $zip->close();
                    
                    $files = glob($tempDir . '/*.sql');
                    if (!empty($files)) {
                        return $files[0];
                    }
                    throw new \Exception('No se encontró archivo SQL en el ZIP');
                }
                throw new \Exception('No se pudo abrir el archivo ZIP');
            }
            
            throw new \Exception('Formato de compresión no soportado');
            
        } catch (\Exception $e) {
            $this->limpiarDirectorioTemp($tempDir);
            throw $e;
        }
    }

    private function descomprimirGz($archivoGz, $archivoSalida)
    {
        try {
            $gz = gzopen($archivoGz, 'rb');
            if (!$gz) {
                throw new \Exception('No se pudo abrir el archivo .gz');
            }
            
            $out = fopen($archivoSalida, 'wb');
            if (!$out) {
                gzclose($gz);
                throw new \Exception('No se pudo crear el archivo de salida');
            }
            
            while (!gzeof($gz)) {
                $data = gzread($gz, 8192);
                fwrite($out, $data);
            }
            
            fclose($out);
            gzclose($gz);
            
            \Log::info('Archivo .gz descomprimido: ' . $archivoSalida);
            return $archivoSalida;
            
        } catch (\Exception $e) {
            \Log::error('Error descomprimiendo .gz: ' . $e->getMessage());
            throw $e;
        }
    }

    private function validarArchivoSQL($filePath)
    {
        try {
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                return false;
            }
            
            $lineas = 0;
            $esSQL = false;
            
            while (($linea = fgets($handle)) !== false && $lineas < 50) {
                $linea = trim($linea);
                
                if (strpos($linea, '--') === 0 || 
                    strpos($linea, 'CREATE TABLE') !== false ||
                    strpos($linea, 'INSERT INTO') !== false ||
                    strpos($linea, 'DROP TABLE') !== false ||
                    strpos($linea, 'SET FOREIGN_KEY_CHECKS') !== false) {
                    $esSQL = true;
                    break;
                }
                
                $lineas++;
            }
            
            fclose($handle);
            
            if (!$esSQL) {
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $esSQL = in_array(strtolower($extension), ['sql', 'txt']);
            }
            
            return $esSQL;
            
        } catch (\Exception $e) {
            \Log::error('Error validando archivo SQL: ' . $e->getMessage());
            return false;
        }
    }

    private function verificarRestauracionExitosa()
    {
        try {
            $tablasImportantes = ['usuarios', 'respaldo'];
            $resultados = [];
            
            foreach ($tablasImportantes as $tabla) {
                if (Schema::hasTable($tabla)) {
                    $count = DB::table($tabla)->count();
                    $resultados[$tabla] = $count;
                    \Log::info("Tabla {$tabla} después de restauración: {$count} registros");
                }
            }
            
            return $resultados;
            
        } catch (\Exception $e) {
            \Log::warning('Error verificando restauración: ' . $e->getMessage());
            return [];
        }
    }

    private function limpiarDirectorioTemp($directorio)
    {
        if (!file_exists($directorio)) {
            return;
        }
        
        try {
            $archivos = glob($directorio . '/*');
            foreach ($archivos as $archivo) {
                if (is_file($archivo)) {
                    @unlink($archivo);
                }
            }
            @rmdir($directorio);
        } catch (\Exception $e) {
            \Log::warning('No se pudo limpiar directorio temporal: ' . $e->getMessage());
        }
    }

    public function formatearTamaño($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}