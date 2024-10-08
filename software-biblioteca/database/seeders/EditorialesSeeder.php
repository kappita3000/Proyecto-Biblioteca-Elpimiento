<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EditorialesSeeder extends Seeder
{
    public function run()
    {
        DB::table('Editorial')->insert([
            ['Nombre' => 'Editorial Sudamericana', 'Pais' => 'Argentina'],
            ['Nombre' => 'Bloomsbury', 'Pais' => 'Reino Unido'],
            ['Nombre' => 'Gallimard', 'Pais' => 'Francia'],
        ]);
    }
}
