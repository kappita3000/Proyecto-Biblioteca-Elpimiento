<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\admin;
use Illuminate\Support\Facades\Hash;

class NewAdminController extends Controller
{
   /**
     * index para mostrar todos los usuarios
     * store para guarda un usuario
     * update para actualizar un usuario
     * destroy para eliminar un usuario
     * edit para mostrar el formulario de edicion
    */

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|min:3',
            'apellido' => 'required|min:3',
            'correo' => 'required|email|unique:admins,correo|min:10', // Validación de correo único
            'contraseña' => 'required|min:8|confirmed', // Se requiere confirmación de la contraseña
        ]);

        

        

        // Si no existe, crear nuevo usuario
        $admins = new admin;
        $admins->nombre = $request->nombre;
        $admins->apellido = $request->apellido;
        $admins->correo = $request->correo;
        $admins->contraseña = Hash::make($request->contraseña);
        $admins->rol = 'moderador'; // Usuario nuevo es Registrado

        // Guardar usuario en la base de datos
        $admins->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('newAdmin')->with('success', 'Moderador creado correctamente');
    }
}
