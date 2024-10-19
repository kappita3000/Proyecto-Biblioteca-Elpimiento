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
        // Relación con el modelo Autor
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }
    // Relación con el modelo Genero
    public function genero()
    {
        return $this->belongsTo(Genero::class, 'id_genero');
    }
    
    // Relación con el modelo Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
    public function repisa()
    {
    return $this->belongsTo(Repisa::class, 'id_repisa');
    }

}
