<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'correo' => 'admin@sistema.com',
                'rol' => 'Administrador',
                'contrasena' => Hash::make('password'),
            ],
            [
                'correo' => 'almacen@sistema.com',
                'rol' => 'Encargado de almacén',
                'contrasena' => Hash::make('password'),
            ],
            [
                'correo' => 'gerencia@sistema.com',
                'rol' => 'Gerencia',
                'contrasena' => Hash::make('password'),
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::updateOrCreate(
                ['correo' => $usuario['correo']],
                $usuario
            );
        }
    }
}
