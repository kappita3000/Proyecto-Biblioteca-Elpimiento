<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // Apunta a la tabla correcta

    protected $fillable = [
        'nombre', 'apellido', 'correo', 'contraseña', 'tipo_usuario', 'solicitudes',
    ];

    // Indicar que la columna de la contraseña es 'contraseña'
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    protected $hidden = [
        'contraseña', // Asegúrate de que la contraseña esté oculta
        'remember_token',
    ];
}