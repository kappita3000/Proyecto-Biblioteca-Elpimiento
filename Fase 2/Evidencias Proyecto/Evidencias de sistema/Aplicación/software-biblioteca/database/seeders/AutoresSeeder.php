<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutoresSeeder extends Seeder
{
    public function run()
    {
        DB::table('autor')->insert([
            ['Nombre' => 'Gabriel', 'Apellido' => 'García Márquez', 'Nacionalidad' => 'Colombiano'],
            ['Nombre' => 'J.K.', 'Apellido' => 'Rowling', 'Nacionalidad' => 'Británica'],
            ['Nombre' => 'Antoine', 'Apellido' => 'de Saint-Exupéry', 'Nacionalidad' => 'Francés'],
        ]);
    }
}
