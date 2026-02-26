<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    public function index(Request $request){
        $query = Empleado::with('usuario')
            ->withCount(['pedidos', 'ventas']);
            // Eliminado el where('estado', 1) para mostrar TODOS los empleados

        // Filtro por estado del empleado (NUEVO)
        if ($request->filled('estado_empleado')) {
            if ($request->estado_empleado == 'activos') {
                $query->where('estado', 1);
            } elseif ($request->estado_empleado == 'inactivos') {
                $query->where('estado', 0);
            }
        } else {
            // Por defecto, mostrar todos (activos e inactivos)
            // No aplicar filtro de estado
        }

        // Filtro por ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $search = $request->nombre;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('ApPaterno', 'LIKE', "%{$search}%")
                  ->orWhere('ApMaterno', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por cargo
        if ($request->filled('cargo')) {
            $query->where('Cargo', $request->cargo);
        }

        // Filtro por área
        if ($request->filled('area')) {
            $query->where('Area_trabajo', $request->area);
        }

        // Filtro por estado de usuario (CORREGIDO)
        if ($request->filled('estado_usuario')) {
            if ($request->estado_usuario == 'con_usuario') {
                // Tiene usuario (ACTIVO O INACTIVO)
                $query->whereHas('usuario'); // Eliminado el where('estado', 1)
            } elseif ($request->estado_usuario == 'sin_usuario') {
                $query->whereDoesntHave('usuario');
            } elseif ($request->estado_usuario == 'usuario_activo') {
                // Usuario activo específicamente
                $query->whereHas('usuario', function($q) {
                    $q->where('estado', 1);
                });
            } elseif ($request->estado_usuario == 'usuario_inactivo') {
                // Usuario inactivo específicamente
                $query->whereHas('usuario', function($q) {
                    $q->where('estado', 0);
                });
            }
        }

        // Filtro por rol de usuario (CORREGIDO - muestra todos sin importar estado)
        if ($request->filled('rol')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('rol', $request->rol);
                // Eliminado el filtro de estado
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validar que el campo de ordenamiento existe
        $allowedSorts = ['id', 'Nombre', 'Cargo', 'Area_trabajo', 'estado'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        $empleados = $query->paginate(10)->appends($request->query());
        
        // Obtener datos para filtros
        $areasUnicas = Empleado::distinct()->whereNotNull('Area_trabajo')->pluck('Area_trabajo');
        $cargosUnicos = Empleado::distinct()->whereNotNull('Cargo')->pluck('Cargo');
        
        return view('personal.index', compact('empleados', 'areasUnicas', 'cargosUnicos'));
    }

    /**
     * Mostrar empleados inactivos (opcional, si aún lo necesitas)
     */
    public function inactivos(Request $request)
    {
        $empleados = Empleado::with('usuario')
            ->withCount(['pedidos', 'ventas'])
            ->where('estado', 0)
            ->paginate(10);

        return view('personal.inactivos', compact('empleados'));
    }

    public function create()
    {
        $cargos = ['Administrador', 'Gerente', 'Encargado de almacén', 'Supervisor', 'Auxiliar'];
        $areas = ['Almacén', 'Oficina', 'Logística', 'Recursos Humanos', 'Ventas', 'Producción'];
        $roles = ['Administración', 'Almacén', 'Logística'];

        return view('personal.create', compact('cargos', 'areas', 'roles'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'Nombre' => 'required|string|max:85',
                'ApPaterno' => 'required|string|max:85',
                'ApMaterno' => 'nullable|string|max:85',
                'Telefono' => [
                    'required',
                    'string',
                    'max:10',
                    'regex:/^[0-9]+$/',
                    Rule::unique('empleados', 'Telefono')
                ],
                'Fecha_nacimiento' => 'required|date',
                'Cargo' => 'required|string|max:80',
                'Sexo' => 'required|in:M,F,Otro',
                'Area_trabajo' => 'required|string|max:80',
                'correo_usuario' => [
                    'required',
                    'email',
                    Rule::unique('usuarios', 'correo')
                ],
                'contrasena' => 'required|min:6|confirmed',
                'rol' => 'required|in:Administración,Almacén,Logística',
            ], [
                'Telefono.unique' => 'El número de teléfono ya está registrado.',
                'correo_usuario.unique' => 'El correo electrónico ya está en uso.',
            ]);

            // Verificar edad mínima
            $edad = Carbon::parse($validated['Fecha_nacimiento'])->age;
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
                'estado' => 1,
            ]);

            // Crear usuario
            Usuario::create([
                'empleado_id' => $empleado->id,
                'correo' => $validated['correo_usuario'],
                'contrasena' => Hash::make($validated['contrasena']),
                'rol' => $validated['rol'],
                'estado' => 1,
            ]);

            DB::commit();

            return redirect()->route('personal.index')
                ->with('success', 'Empleado y usuario creados exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear empleado: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear el empleado: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $empleado = Empleado::with('usuario')->findOrFail($id);
        $cargos = ['Administrador', 'Gerente', 'Encargado de almacén', 'Supervisor', 'Auxiliar'];
        $areas = ['Almacén', 'Oficina', 'Logística', 'Recursos Humanos', 'Ventas', 'Producción'];
        $roles = ['Administración', 'Almacén', 'Logística'];

        return view('personal.edit', compact('empleado', 'cargos', 'areas', 'roles'));
    }

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
                'Telefono' => [
                    'required',
                    'string',
                    'max:10',
                    'regex:/^[0-9]+$/',
                    Rule::unique('empleados', 'Telefono')->ignore($empleado->id)
                ],
                'Fecha_nacimiento' => 'required|date',
                'Cargo' => 'required|string|max:80',
                'Sexo' => 'required|in:M,F,Otro',
                'Area_trabajo' => 'required|string|max:80',
            ];

            // Si tiene usuario y se editan credenciales
            if ($tieneUsuario && $request->has('editar_credenciales') && $request->boolean('editar_credenciales')) {
                $rules['correo'] = [
                    'required',
                    'email',
                    Rule::unique('usuarios', 'correo')->ignore($empleado->usuario->id)
                ];
                $rules['rol'] = 'required|in:Administración,Almacén,Logística';

                if ($request->filled('contrasena')) {
                    $rules['contrasena'] = 'min:6|confirmed';
                }
            }

            $validated = $request->validate($rules, [
                'Telefono.unique' => 'El número de teléfono ya está registrado.',
                'correo.unique' => 'El correo electrónico ya está en uso.',
            ]);

            // Verificar edad
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

            // Actualizar usuario si corresponde
            if ($tieneUsuario && $request->has('editar_credenciales') && $request->boolean('editar_credenciales')) {
                $userData = [
                    'correo' => $validated['correo'],
                    'rol' => $validated['rol'],
                ];

                if ($request->filled('contrasena')) {
                    $userData['contrasena'] = Hash::make($request->contrasena);
                }

                $empleado->usuario->update($userData);
            }

            DB::commit();

            return redirect()->route('personal.index')
                ->with('success', 'Empleado actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar empleado: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al actualizar el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Desactivar empleado (Soft Delete)
     */
    public function destroy($id)
    {
        try {
            $empleado = Empleado::with('usuario')->findOrFail($id);
            $nombreCompleto = $empleado->Nombre . ' ' . $empleado->ApPaterno . ($empleado->ApMaterno ? ' ' . $empleado->ApMaterno : '');

            // Desactivar empleado
            $empleado->estado = 0;
            $empleado->save();

            // Desactivar usuario si existe
            if ($empleado->usuario) {
                $empleado->usuario->estado = 0;
                $empleado->usuario->save();
            }

            return redirect()->route('personal.index')
                ->with('success', 'Empleado "' . $nombreCompleto . '" desactivado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al desactivar empleado: ' . $e->getMessage());
            return redirect()->route('personal.index')
                ->with('error', 'Error al desactivar el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Activar empleado
     */
    public function activar($id)
    {
        try {
            $empleado = Empleado::with('usuario')->findOrFail($id);
            $nombreCompleto = $empleado->Nombre . ' ' . $empleado->ApPaterno . ($empleado->ApMaterno ? ' ' . $empleado->ApMaterno : '');

            // Activar empleado
            $empleado->estado = 1;
            $empleado->save();

            // Activar usuario si existe
            if ($empleado->usuario) {
                $empleado->usuario->estado = 1;
                $empleado->usuario->save();
            }

            return redirect()->route('personal.index')
                ->with('success', 'Empleado "' . $nombreCompleto . '" activado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al activar empleado: ' . $e->getMessage());
            return redirect()->route('personal.index')
                ->with('error', 'Error al activar el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Toggle estado (alternar entre activo/inactivo)
     */
    public function toggleEstado($id)
    {
        try {
            $empleado = Empleado::with('usuario')->findOrFail($id);
            $nombreCompleto = $empleado->Nombre . ' ' . $empleado->ApPaterno . ($empleado->ApMaterno ? ' ' . $empleado->ApMaterno : '');
            
            $estadoAnterior = $empleado->estado;
            $empleado->estado = !$empleado->estado;
            $empleado->save();

            // Sincronizar estado del usuario
            if ($empleado->usuario) {
                $empleado->usuario->estado = $empleado->estado;
                $empleado->usuario->save();
            }

            $accion = $estadoAnterior ? 'desactivado' : 'activado';

            return redirect()->route('personal.index')
                ->with('success', "Empleado '{$nombreCompleto}' {$accion} correctamente.");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del empleado: ' . $e->getMessage());
            return redirect()->route('personal.index')
                ->with('error', 'Error al cambiar el estado del empleado: ' . $e->getMessage());
        }
    }

    /**
     * Crear usuario para empleado existente
     */
    public function storeUser(Request $request, $id)
    {
        try {
            $empleado = Empleado::findOrFail($id);

            if ($empleado->usuario) {
                return redirect()->route('personal.index')
                    ->with('error', 'Este empleado ya tiene un usuario asociado.');
            }

            $validated = $request->validate([
                'correo' => [
                    'required',
                    'email',
                    Rule::unique('usuarios', 'correo')
                ],
                'rol' => 'required|in:Administración,Almacén,Logística',
                'contrasena' => 'required|min:6|confirmed',
            ], [
                'correo.unique' => 'El correo electrónico ya está en uso.'
            ]);

            Usuario::create([
                'empleado_id' => $empleado->id,
                'correo' => $validated['correo'],
                'contrasena' => Hash::make($validated['contrasena']),
                'rol' => $validated['rol'],
                'estado' => 1,
            ]);

            $nombreCompleto = $empleado->Nombre . ' ' . $empleado->ApPaterno . ($empleado->ApMaterno ? ' ' . $empleado->ApMaterno : '');

            return redirect()->route('personal.index')
                ->with('success', 'Usuario creado exitosamente para ' . $nombreCompleto);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Desactivar usuario
     */
    public function destroyUser($id)
    {
        try {
            $usuario = Usuario::with('empleado')->findOrFail($id);
            $nombreEmpleado = $usuario->empleado ? 
                $usuario->empleado->Nombre . ' ' . $usuario->empleado->ApPaterno : 
                'desconocido';

            $usuario->estado = 0;
            $usuario->save();

            return redirect()->route('personal.index')
                ->with('success', 'Usuario desactivado exitosamente de ' . $nombreEmpleado);

        } catch (\Exception $e) {
            Log::error('Error al desactivar usuario: ' . $e->getMessage());
            return redirect()->route('personal.index')
                ->with('error', 'Error al desactivar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Activar usuario
     */
    public function activateUser($id)
    {
        try {
            $usuario = Usuario::with('empleado')->findOrFail($id);
            $nombreEmpleado = $usuario->empleado ? 
                $usuario->empleado->Nombre . ' ' . $usuario->empleado->ApPaterno : 
                'desconocido';

            $usuario->estado = 1;
            $usuario->save();

            return redirect()->route('personal.index')
                ->with('success', 'Usuario activado exitosamente para ' . $nombreEmpleado);

        } catch (\Exception $e) {
            Log::error('Error al activar usuario: ' . $e->getMessage());
            return redirect()->route('personal.index')
                ->with('error', 'Error al activar el usuario: ' . $e->getMessage());
        }
    }
}