<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AutoresSeeder::class,
            CategoriasSeeder::class,
            EditorialesSeeder::class,
            GenerosSeeder::class,
            RepisasSeeder::class,
            LibrosSeeder::class,
            UsuariosSeeder::class,
            PrestamosSeeder::class,
        ]);
    }
}
