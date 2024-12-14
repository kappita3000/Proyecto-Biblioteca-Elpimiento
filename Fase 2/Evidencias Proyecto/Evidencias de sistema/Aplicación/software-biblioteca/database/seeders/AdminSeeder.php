<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'nombre' => 'Seas',
            'apellido' => 'Sjkr',
            'correo' => 'seasjkr@gmail.com',
            'contraseña' => Hash::make('12341234'), // Cifrar la contraseña
            'rol' => 'superadmin', // Asignar rol
        ]);
    }
}
