<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            ['nombre' => 'Carlos', 'apellido' => 'Pérez', 'correo' => 'carlos@example.com', 'contraseña' => Hash::make('12341234'), 'tipo_usuario' => 'Registrado', 'solicitudes' => 0],
            ['nombre' => 'María', 'apellido' => 'García', 'correo' => 'maria@example.com', 'contraseña' => Hash::make('12341234'), 'tipo_usuario' => 'No Registrado', 'solicitudes' => 0],
        ];

        // Crear 28 usuarios adicionales
        for ($i = 1; $i <= 28; $i++) {
            $usuarios[] = [
                'nombre' => 'Usuario' . $i,
                'apellido' => 'Apellido' . $i,
                'correo' => 'usuario' . $i . '@example.com',
                'contraseña' => Hash::make('12341234'),
                'tipo_usuario' => $i % 2 == 0 ? 'Registrado' : 'No Registrado',
                'solicitudes' => 0,
            ];
        }

        // Insertar usuarios en la base de datos
        DB::table('usuarios')->insert($usuarios);
    }
}
