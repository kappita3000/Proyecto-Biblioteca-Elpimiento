<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class admin extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'admins'; // Asegúrate de que este sea el nombre correcto de la tabla

    protected $fillable = [
        'nombre', 'apellido', 'correo', 'contraseña', 'rol',
    ];

    protected $hidden = [
        'contraseña', // Ocultar la contraseña al serializar el modelo
        'remember_token',
    ];
}
