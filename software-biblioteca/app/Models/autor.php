<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $primaryKey = 'ID'; // La clave primaria si es `ID`
    
    // Especificar el nombre correcto de la tabla
    protected $table = 'autor';

    // Relación inversa con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'ID_Autor');
    }
}
