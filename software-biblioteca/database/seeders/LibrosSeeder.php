<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibrosSeeder extends Seeder
{
    public function run()
    {
        DB::table('Libros')->insert([
            [
                'Titulo' => 'Cien años de soledad',
                'ID_Autor' => 1,
                'ID_Genero' => 1,
                'ID_Categoria' => 1,
                'ID_Repisa' => 1,
                'Fecha_Publicacion' => '1967-05-30',
                'Disponible' => 1,
                'Cantidad' => 5,
                'Descripcion' => 'La épica saga de la familia Buendía.',
                'ID_Editorial' => 1,
            ],
            [
                'Titulo' => 'Harry Potter y la piedra filosofal',
                'ID_Autor' => 2,
                'ID_Genero' => 2,
                'ID_Categoria' => 2,
                'ID_Repisa' => 2,
                'Fecha_Publicacion' => '1997-06-26',
                'Disponible' => 1,
                'Cantidad' => 10,
                'Descripcion' => 'La historia mágica de Harry Potter.',
                'ID_Editorial' => 2,
            ],
            [
                'Titulo' => 'El principito',
                'ID_Autor' => 3,
                'ID_Genero' => 3,
                'ID_Categoria' => 3,
                'ID_Repisa' => 3,
                'Fecha_Publicacion' => '1943-04-06',
                'Disponible' => 1,
                'Cantidad' => 7,
                'Descripcion' => 'Una historia filosófica sobre la vida y la naturaleza humana.',
                'ID_Editorial' => 3,
            ],
        ]);
    }
}
