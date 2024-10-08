<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class editorial extends Model
{
    protected $primaryKey = 'ID';

    protected $table = 'editorial';
    // Relación inversa con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'ID_Editorial');
    }
}
