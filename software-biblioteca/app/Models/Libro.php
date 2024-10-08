<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
  
    public $timestamps = false;
    protected $primaryKey = 'ID'; // Si la clave primaria es `ID`

    // Relación con Autor
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'ID_Autor');
    }

    // Relación con Genero
    public function genero()
    {
        return $this->belongsTo(Genero::class, 'ID_Genero');
    }

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'ID_Categoria');
    }

    // Relación con Repisa
    public function repisa()
    {
        return $this->belongsTo(Repisa::class, 'ID_Repisa');
    }

    // Relación con Editorial
    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'ID_Editorial');
    }

    protected $table = 'libros';
    protected $fillable = ['Titulo', 'Cantidad', 'Disponible']; // Añade otros campos necesarios
}
