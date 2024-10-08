<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
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
            'correo' => 'required|email|unique:usuarios,correo|min:10', // Validación de correo único
            'contraseña' => 'required|min:10|confirmed', // Se requiere confirmación de la contraseña
            'tipo_usuario' => 'required|in:Registrado,No Registrado', // Validación del tipo de usuario
        ]);

        // Buscar si el usuario ya existe con el correo y es no registrado
        $usuario = Usuario::where('correo', $request->correo)->first();

        if ($usuario) {
            // Si existe un usuario registrado, redirigir de nuevo al formulario con error
            return back()->withErrors([
                'correo' => 'Este correo ya está registrado. Intenta iniciar sesión o usa otro correo.',
            ])->withInput(); // Retornar los datos ingresados
        }

        // Si no existe, crear nuevo usuario
        $usuario = new Usuario;
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->correo = $request->correo;
        $usuario->contraseña = Hash::make($request->contraseña);
        $usuario->tipo_usuario = 'Registrado'; // Usuario nuevo es Registrado

        // Guardar usuario en la base de datos
        $usuario->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('usuario')->with('success', 'Usuario creado correctamente');
    }
}
