<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    
    protected $primaryKey = 'ID'; // Si la clave primaria es `ID`
    protected $table = 'usuario'; // Nombre de la tabla

    protected $fillable = [
        'Nombre',
        'Apellido',
        'Correo',
    ];
    public $timestamps = false; // Desactivar timestamps automáticas
}
