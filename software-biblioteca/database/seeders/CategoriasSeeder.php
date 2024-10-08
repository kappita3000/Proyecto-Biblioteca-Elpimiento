<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        DB::table('Categoria')->insert([
            ['Nombre' => 'Ficción'],
            ['Nombre' => 'Fantasía'],
            ['Nombre' => 'Ciencia Ficción'],
        ]);
    }
}
