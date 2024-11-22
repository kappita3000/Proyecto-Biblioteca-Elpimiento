<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Cambiar a 'id' para consistencia
    protected $fillable = ['nombre'];

    // Relación con libros
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_categoria');
    }
}