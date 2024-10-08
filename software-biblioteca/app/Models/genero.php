<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class genero extends Model
{
    protected $primaryKey = 'ID';

    protected $table = 'genero';
    // Relación inversa con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'ID_Genero');
    }
}
