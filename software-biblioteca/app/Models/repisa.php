<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class repisa extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'repisa';
    // Relación inversa con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'ID_Repisa');
    }
}
