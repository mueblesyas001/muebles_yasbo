<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base para empleados con filtros
        $query = Empleado::with('usuario');
        
        // Aplicar filtros si existen
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->filled('nombre')) {
            $query->where(function($q) use ($request) {
                $q->where('Nombre', 'LIKE', '%' . $request->nombre . '%')
                  ->orWhere('ApPaterno', 'LIKE', '%' . $request->nombre . '%')
                  ->orWhere('ApMaterno', 'LIKE', '%' . $request->nombre . '%');
            });
        }
        
        if ($request->filled('cargo')) {
            $query->where('Cargo', $request->cargo);
        }
        
        if ($request->filled('area')) {
            $query->where('Area_trabajo', $request->area);
        }
        
        if ($request->filled('estado_usuario')) {
            if ($request->estado_usuario == 'con_usuario') {
                $query->whereHas('usuario');
            } elseif ($request->estado_usuario == 'sin_usuario') {
                $query->whereDoesntHave('usuario');
            }
        }
        
        if ($request->filled('rol')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('rol', $request->rol);
            });
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Paginación con filtros
        $empleadosPaginated = $query->paginate(10)->appends($request->query());
        
        // Para estadísticas (empleados filtrados)
        $empleadosFiltrados = $query->get();
        
        // Para compatibilidad con estadísticas cuando no hay filtros
        $empleados = Empleado::with('usuario')->get();
        $usuarios = Usuario::with('empleado')->get();
        
        // Valores para filtros
        $areas = Empleado::distinct()->pluck('Area_trabajo')->filter()->values()->toArray();
        $cargos = Empleado::distinct()->pluck('Cargo')->filter()->values()->toArray();
        $roles = ['Administración', 'Almacén', 'Logística'];
        
        // Alias para la vista
        $areasUnicas = $areas;
        $cargosUnicos = $cargos;

        return view('personal.index', compact(
            'empleados',           // Todos los empleados (para estadísticas)
            'empleadosPaginated',  // Empleados paginados con filtros
            'empleadosFiltrados',  // Empleados filtrados sin paginar
            'usuarios',            // Todos los usuarios
            'areas', 
            'cargos', 
            'roles',
            'areasUnicas',
            'cargosUnicos'
        ));
    }

    /**
     * Formulario crear
     */
    public function create()
    {
        $roles = ['Administración', 'Almacén', 'Logística'];
        $cargos = ['Administrador', 'Gerente', 'Encargado de almacén', 'Supervisor', 'Auxiliar'];
        $areas = ['Almacén', 'Oficina', 'Logística', 'Recursos Humanos', 'Ventas', 'Producción'];
        
        return view('personal.create', compact('roles', 'cargos', 'areas'));
    }

    /**
     * Guardar empleado
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Log para debugging
            Log::info('Datos recibidos en store:', $request->all());
            
            // Validación con valores corregidos
            $validated = $request->validate([
                'Nombre' => 'required|string|max:85',
                'ApPaterno' => 'required|string|max:85',
                'ApMaterno' => 'nullable|string|max:85',
                'Telefono' => 'required|string|max:10|regex:/^[0-9]+$/',
                'Fecha_nacimiento' => 'required|date',
                'Cargo' => 'required|string|max:80',
                'Sexo' => 'required|in:M,F,Otro',  // Cambiado de 'O' a 'Otro'
                'Area_trabajo' => 'required|string|max:80',
                
                'correo_usuario' => 'required|email|unique:usuarios,correo',
                'contrasena' => 'required|min:6|confirmed',
                'rol' => 'required|in:Administración,Almacén,Logística',
            ]);

            Log::info('Datos validados:', $validated);

            // Calcular edad
            $edad = Carbon::parse($validated['Fecha_nacimiento'])->age;
            
            // Verificar que sea mayor de edad
            if ($edad < 18) {
                return back()->withInput()->with('error', 'El empleado debe ser mayor de 18 años.');
            }

            // Crear empleado
            $empleado = Empleado::create([
                'Nombre' => $validated['Nombre'],
                'ApPaterno' => $validated['ApPaterno'],
                'ApMaterno' => $validated['ApMaterno'] ?? null,
                'Telefono' => $validated['Telefono'],
                'Fecha_nacimiento' => $validated['Fecha_nacimiento'],
                'Cargo' => $validated['Cargo'],
                'Sexo' => $validated['Sexo'],
                'Area_trabajo' => $validated['Area_trabajo'],
            ]);

            Log::info('Empleado creado:', $empleado->toArray());

            // Siempre crear usuario
            $usuario = Usuario::create([
                'empleado_id' => $empleado->id,
                'correo' => $validated['correo_usuario'],
                'contrasena' => Hash::make($validated['contrasena']),
                'rol' => $validated['rol']
            ]);

            Log::info('Usuario creado:', $usuario->toArray());

            DB::commit();

            return redirect()->route('personal.index')->with(
                'success',
                'Empleado y usuario creados exitosamente.'
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación en store:', $e->errors());
            return back()->withInput()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store empleado: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return back()->withInput()->with('error', 'Error al crear el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar empleado
     */
    public function show($id)
    {
        $empleado = Empleado::with('usuario')->findOrFail($id);

        return view('personal.show', [
            'empleado' => $empleado,
            'totalEmpleados' => Empleado::count(),
            'empleadosArea' => Empleado::where('Area_trabajo', $empleado->Area_trabajo)->count(),
            'totalUsuarios' => Usuario::count(),
        ]);
    }

    /**
     * Editar
     */
    public function edit($id)
    {
        $empleado = Empleado::with('usuario')->findOrFail($id);
        $roles = ['Administración', 'Almacén', 'Logística'];
        $cargos = ['Administrador', 'Gerente', 'Encargado de almacén', 'Supervisor', 'Auxiliar'];
        $areas = ['Almacén', 'Oficina', 'Logística', 'Recursos Humanos', 'Ventas', 'Producción'];

        return view('personal.edit', compact('empleado', 'roles', 'cargos', 'areas'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $empleado = Empleado::with('usuario')->findOrFail($id);
            $tieneUsuario = $empleado->usuario !== null;

            $rules = [
                'Nombre' => 'required|string|max:85',
                'ApPaterno' => 'required|string|max:85',
                'ApMaterno' => 'nullable|string|max:85',
                'Telefono' => 'required|string|max:10|regex:/^[0-9]+$/',
                'Fecha_nacimiento' => 'required|date',
                'Cargo' => 'required|string|max:80',
                'Sexo' => 'required|in:M,F,Otro',  // Cambiado de 'O' a 'Otro'
                'Area_trabajo' => 'required|string|max:80',
            ];

            // Validación para editar credenciales de usuario
            if ($tieneUsuario && $request->has('editar_credenciales') && $request->boolean('editar_credenciales')) {
                $rules['correo'] = 'required|email|max:100|unique:usuarios,correo,' . $empleado->usuario->id;
                $rules['rol'] = 'required|in:Administración,Almacén,Logística';
                
                // Si se va a cambiar la contraseña
                if ($request->filled('contrasena')) {
                    $rules['contrasena'] = 'min:6|confirmed';
                }
            }

            $validated = $request->validate($rules);

            // Calcular edad y verificar
            $edad = Carbon::parse($validated['Fecha_nacimiento'])->age;
            if ($edad < 18) {
                return back()->withInput()->with('error', 'El empleado debe ser mayor de 18 años.');
            }

            // Actualizar empleado
            $empleado->update([
                'Nombre' => $validated['Nombre'],
                'ApPaterno' => $validated['ApPaterno'],
                'ApMaterno' => $validated['ApMaterno'] ?? null,
                'Telefono' => $validated['Telefono'],
                'Fecha_nacimiento' => $validated['Fecha_nacimiento'],
                'Cargo' => $validated['Cargo'],
                'Sexo' => $validated['Sexo'],
                'Area_trabajo' => $validated['Area_trabajo'],
            ]);

            // Actualizar usuario si existe y se marcó para editar
            if ($tieneUsuario && $request->has('editar_credenciales') && $request->boolean('editar_credenciales')) {
                $updateData = [
                    'correo' => $validated['correo'],
                    'rol' => $validated['rol'],
                ];
                
                // Actualizar contraseña si se proporcionó
                if ($request->filled('contrasena')) {
                    $updateData['contrasena'] = Hash::make($request->contrasena);
                }
                
                $empleado->usuario->update($updateData);
            }

            DB::commit();

            return redirect()->route('personal.index')
                             ->with('success', 'Empleado actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación en update:', $e->errors());
            return back()->withInput()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update empleado: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Error al actualizar el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar empleado (y su usuario si existe)
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $empleado = Empleado::with('usuario')->findOrFail($id);
            $nombreEmpleado = $empleado->Nombre . ' ' . $empleado->ApPaterno;
            $teniaUsuario = $empleado->usuario !== null;

            // Eliminar usuario primero (si existe) debido a la relación foreign key
            if ($teniaUsuario) {
                $empleado->usuario->delete();
            }

            // Luego eliminar el empleado
            $empleado->delete();
            
            DB::commit();

            return redirect()->route('personal.index')->with(
                'success',
                $teniaUsuario
                    ? 'Empleado "' . $nombreEmpleado . '" y su usuario eliminados exitosamente.'
                    : 'Empleado "' . $nombreEmpleado . '" eliminado exitosamente.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error destroy empleado: ' . $e->getMessage());

            return back()->with('error', 'Error al eliminar el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Crear usuario para empleado existente
     */
    public function storeUser(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $empleado = Empleado::findOrFail($id);
            
            // Verificar si ya tiene usuario
            if ($empleado->usuario) {
                return redirect()->route('personal.index')
                    ->with('error', 'Este empleado ya tiene un usuario asociado.');
            }
            
            $validated = $request->validate([
                'correo' => 'required|email|unique:usuarios,correo',
                'rol' => 'required|in:Administración,Almacén,Logística',
                'contrasena' => 'required|min:6|confirmed',
            ]);
            
            // Crear usuario
            Usuario::create([
                'empleado_id' => $empleado->id,
                'correo' => $validated['correo'],
                'contrasena' => Hash::make($validated['contrasena']),
                'rol' => $validated['rol'],
            ]);
            
            DB::commit();
            
            return redirect()->route('personal.index')
                ->with('success', 'Usuario creado exitosamente para ' . $empleado->Nombre . ' ' . $empleado->ApPaterno);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar solo el usuario (sin eliminar el empleado)
     */
    public function destroyUser($id)
    {
        DB::beginTransaction();
        
        try {
            $usuario = Usuario::with('empleado')->findOrFail($id);
            $empleadoNombre = $usuario->empleado ? 
                $usuario->empleado->Nombre . ' ' . $usuario->empleado->ApPaterno : 
                'Empleado';
            
            $usuario->delete();
            
            DB::commit();
            
            return redirect()->route('personal.index')
                ->with('success', 'Usuario eliminado exitosamente de ' . $empleadoNombre);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            
            return back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
    
    /**
     * Eliminar empleado y usuario juntos (confirmación)
     */
    public function confirmDestroy($id)
    {
        $empleado = Empleado::with('usuario')->findOrFail($id);
        return view('personal.confirm-destroy', compact('empleado'));
    }
}