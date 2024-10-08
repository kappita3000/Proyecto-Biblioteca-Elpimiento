<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestamosSeeder extends Seeder
{
    public function run()
    {
        DB::table('Prestamo')->insert([
            [
                'ID_Usuario' => 1,
                'ID_Libro' => 1,
                'Fecha_Prestamo' => now(),
                'Fecha_Devolucion' => now()->addWeeks(2),
                'Devuelto' => 0,
            ],
            [
                'ID_Usuario' => 2,
                'ID_Libro' => 2,
                'Fecha_Prestamo' => now(),
                'Fecha_Devolucion' => null,
                'Devuelto' => 0,
            ],
        ]);
    }
}
