<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'categorias';

    protected $fillable = [
        'nombre'
    ];

    // Relación inversa con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_categoria');
    }
}
