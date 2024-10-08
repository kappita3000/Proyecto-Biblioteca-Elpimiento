<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        DB::table('Usuario')->insert([
            ['Nombre' => 'Carlos', 'Apellido' => 'Pérez', 'Correo' => 'carlos@example.com', 'Contraseña' => bcrypt('password'), 'Tipo_Usuario' => 'Registrado', 'Solicitudes' => 0],
            ['Nombre' => 'María', 'Apellido' => 'García', 'Correo' => 'maria@example.com', 'Contraseña' => bcrypt('password'), 'Tipo_Usuario' => 'No Registrado', 'Solicitudes' => 0],
        ]);
    }
}
