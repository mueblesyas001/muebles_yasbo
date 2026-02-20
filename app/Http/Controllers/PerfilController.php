<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        
        if ($user->empleado) {
            $user->load('empleado');
        }
        
        return view('perfil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'correo' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('usuarios', 'correo')->ignore($user->id)
            ],
            'correo_confirmation' => 'required|same:correo',
        ], [
            'correo_confirmation.required' => 'La confirmación del correo es requerida',
            'correo_confirmation.same' => 'Los correos electrónicos no coinciden',
        ]);

        $user->update([
            'correo' => $request->correo,
        ]);

        return back()->with('success', 'Correo electrónico actualizado correctamente.');
    }

    public function showChangePasswordForm()
    {
        return view('perfil.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'min:6'],
            'new_password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',      // al menos una letra minúscula
                'regex:/[A-Z]/',      // al menos una letra mayúscula
                'regex:/[0-9]/',      // al menos un número
                'regex:/[@$!%*#?&]/', // al menos un carácter especial
                'confirmed'
            ],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'current_password.min' => 'La contraseña actual debe tener al menos 6 caracteres.',
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial.',
            'new_password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->contrasena)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta.'
            ])->withInput();
        }

        // Actualizar contraseña
        $user->contrasena = Hash::make($request->new_password);
        $user->updated_at = now();
        $user->save();

        return back()->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
    }
}