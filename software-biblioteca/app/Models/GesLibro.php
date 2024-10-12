<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GesLibro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'titulo', 'id_autor', 'id_genero', 'id_categoria', 'id_repisa', 
        'id_editorial', 'fecha_publicacion', 'disponible', 'cantidad', 'descripcion'
    ];
}
