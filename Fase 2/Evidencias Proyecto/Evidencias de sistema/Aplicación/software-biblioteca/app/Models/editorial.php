<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    protected $table = 'editoriales'; // Indicar que la tabla es 'autores'
    use HasFactory;
    protected $fillable = [
        'nombre', 'pais'
    ];
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_editorial');
    }
}
