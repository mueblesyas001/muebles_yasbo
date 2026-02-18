<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function index(){
        $areas = Empleado::distinct()->pluck('Area_trabajo')->filter()->values()->toArray();
        
        $usuarios = Usuario::with('empleado')->get();
        
        $cargos = Empleado::distinct()->pluck('Cargo')->filter()->values()->toArray();
        return view('usuarios.index', compact('usuarios', 'areas', 'cargos'));
    }

    public function create(){
        $roles = ['Administración', 'Almacén', 'Logística'];
        
        // CORREGIDO: Usar whereDoesntHave para mejor performance
        $empleados = Empleado::whereDoesntHave('usuario')->get();
        
        return view('usuarios.create', compact('roles', 'empleados'));
    }

    public function store(Request $request){
        // Debug para ver los datos
        Log::info('Datos del formulario de usuario:', $request->all());

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id', // CORREGIDO: usar 'id' no 'idEmpleado'
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|min:6|confirmed',
            'rol' => 'required|in:Administración,Almacén,Logística'
        ], [
            'empleado_id.required' => 'Debe seleccionar un empleado.',
            'empleado_id.exists' => 'El empleado seleccionado no existe.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe ser válido.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.'
        ]);

        try {
            // Verificar que el empleado no tenga ya un usuario
            $empleadoConUsuario = Usuario::where('empleado_id', $request->empleado_id)->exists();
            
            if ($empleadoConUsuario) {
                return back()->withErrors([
                    'empleado_id' => 'Este empleado ya tiene un usuario asignado.'
                ])->withInput();
            }

            Usuario::create([
                'empleado_id' => $request->empleado_id,
                'correo' => $request->correo,
                'contrasena' => bcrypt($request->contrasena),
                'rol' => $request->rol
            ]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el usuario: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Usuario $usuario){
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario){
        $roles = ['Administración', 'Almacén', 'Logística'];
        
        // CORREGIDO: Mostrar empleados sin usuario + el actual
        $empleados = Empleado::whereDoesntHave('usuario')
                            ->orWhere('id', $usuario->empleado_id)
                            ->get();
        
        return view('usuarios.edit', compact('usuario', 'roles', 'empleados'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
            'rol' => 'required|in:Administración,Almacén,Logística'
        ]);

        $data = [
            'correo' => $request->correo,
            'rol' => $request->rol
        ];

        // Si se proporciona una nueva contraseña
        if ($request->filled('contrasena')) {
            $request->validate([
                'contrasena' => 'min:6|confirmed'
            ]);
            $data['contrasena'] = bcrypt($request->contrasena);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}