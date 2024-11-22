<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';
    public $timestamps = false; // Si no usas las columnas created_at y updated_at
    protected $primaryKey = 'id';

    protected $fillable = [
        'titulo',
        'caratula',
        'id_autor',
        'id_genero',
        'id_categoria',
        'id_repisa',
        'id_editorial',
        'disponible',
        'cantidad',
        'descripcion',
        'created_at',
        'updated_at'
    ];

    // Relaciones
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }

    public function genero()
    {
        return $this->belongsTo(Genero::class, 'id_genero');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function repisa()
    {
        return $this->belongsTo(Repisa::class, 'id_repisa');
    }

    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial');
    }
}

