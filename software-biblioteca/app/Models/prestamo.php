<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $fillable = ['id_usuario', 'id_libro', 'titulo_libro', 'editorial_libro','fecha_solicitud', 'fecha_prestamo', 'fecha_devolucion', 'devolucion'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }
    
}
