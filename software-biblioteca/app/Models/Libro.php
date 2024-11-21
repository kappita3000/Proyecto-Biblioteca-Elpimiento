<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Libro extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id'; // Si la clave primaria es `ID`

    // Relación con Autor
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }

    // Relación con Genero
    public function genero()
    {
        return $this->belongsTo(Genero::class, 'id_genero');
    }

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relación con Repisa
    public function repisa()
    {
        return $this->belongsTo(Repisa::class, 'id_repisa');
    }

    // Relación con Editorial
    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial');
    }

    protected $table = 'libros';
    protected $fillable = ['titulo', 'Cantidad', 'Disponible']; // Añade otros campos necesarios
}
