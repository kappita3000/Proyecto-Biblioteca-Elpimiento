<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenerosSeeder extends Seeder
{
    public function run()
    {
        DB::table('Genero')->insert([
            ['Nombre' => 'Realismo Mágico'],
            ['Nombre' => 'Fantasía'],
            ['Nombre' => 'Aventura'],
        ]);
    }
}
