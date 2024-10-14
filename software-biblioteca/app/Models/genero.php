<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    protected $table = 'generos'; // Indicar que la tabla es 'autores'
    use HasFactory;
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_genero');
    }
}
