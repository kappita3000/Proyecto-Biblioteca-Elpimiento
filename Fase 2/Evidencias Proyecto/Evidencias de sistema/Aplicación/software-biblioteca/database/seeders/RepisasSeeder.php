<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepisasSeeder extends Seeder
{
    public function run()
    {
        DB::table('Repisa')->insert([
            ['Numero' => 1, 'Ubicacion' => 'A1'],
            ['Numero' => 2, 'Ubicacion' => 'B2'],
            ['Numero' => 3, 'Ubicacion' => 'C3'],
        ]);
    }
}
