<?php

namespace App\Http\Controllers;

use App\Mail\CuentaCreadaNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Usuario;
use App\Models\Prestamo;

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
            'correo' => 'required|email|min:10', // Quitamos la validación 'unique' para manejarla manualmente
            'contraseña' => 'required|min:8|confirmed', // Se requiere confirmación de la contraseña
        ]);

        // Verificar si el correo ya existe en la tabla admin
        if (Admin::where('correo', $request->correo)->exists()) {
            return back()->withErrors([
                'correo' => 'Este correo ya está registrado en el sistema como administrador. Intenta usar otro correo.',
            ])->withInput(); // Retornar los datos ingresados
        }

        // Verificar si el correo ya existe en la tabla de usuarios registrados
        if (Usuario::where('correo', $request->correo)->where('tipo_usuario', 'Registrado')->exists()) {
            return back()->withErrors([
                'correo' => 'Este correo ya está registrado. Intenta iniciar sesión o usa otro correo.',
            ])->withInput(); // Retornar los datos ingresados
        }

        // Buscar si existe un usuario no registrado con el mismo correo
        $usuariosNoRegistrados = Usuario::where('correo', $request->correo)
            ->where('tipo_usuario', 'No Registrado')
            ->get();

        if ($usuariosNoRegistrados->isNotEmpty()) {
            // Elegir el nuevo usuario registrado como principal
            $usuarioPrincipal = new Usuario;
            $usuarioPrincipal->nombre = $request->nombre;
            $usuarioPrincipal->apellido = $request->apellido;
            $usuarioPrincipal->correo = $request->correo;
            $usuarioPrincipal->contraseña = Hash::make($request->contraseña);
            $usuarioPrincipal->tipo_usuario = 'Registrado'; // Usuario nuevo es Registrado

            // Guardar el nuevo usuario registrado
            $usuarioPrincipal->save();

            // Actualizar los préstamos asociados al correo no registrado
            foreach ($usuariosNoRegistrados as $usuarioNoRegistrado) {
                Prestamo::where('id_usuario', $usuarioNoRegistrado->id)
                    ->update(['id_usuario' => $usuarioPrincipal->id]);

                // Eliminar o desactivar los usuarios no registrados
                $usuarioNoRegistrado->delete();
            }

            // Enviar correo de notificación
            Mail::to($usuarioPrincipal->correo)->send(new CuentaCreadaNotification($usuarioPrincipal));

            // Redirigir con un mensaje de éxito
            return redirect()->route('usuario')->with('success', 'Usuario creado correctamente y préstamos actualizados.');
        }

        // Si no existe usuario no registrado con ese correo, crear nuevo usuario
        $usuario = new Usuario;
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->correo = $request->correo;
        $usuario->contraseña = Hash::make($request->contraseña);
        $usuario->tipo_usuario = 'Registrado'; // Usuario nuevo es Registrado

        // Guardar usuario en la base de datos
        $usuario->save();

        // Enviar correo de notificación
        Mail::to($usuario->correo)->send(new CuentaCreadaNotification($usuario));

        // Redirigir con un mensaje de éxito
        return redirect()->route('usuario')->with('success', 'Usuario creado correctamente');
    }

    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $usuarios = [];

        if ($query) {
            // Divide el query en palabras (nombre y apellido)
            $keywords = explode(' ', $query);

            // Construir la consulta
            $usuarios = Usuario::query()
                ->when(count($keywords) > 1, function ($q) use ($keywords) {
                    $q->where('nombre', 'like', '%' . $keywords[0] . '%')
                    ->where('apellido', 'like', '%' . $keywords[1] . '%');
                }, function ($q) use ($query) {
                    $q->where('nombre', 'like', '%' . $query . '%')
                    ->orWhere('apellido', 'like', '%' . $query . '%')
                    ->orWhere('correo', 'like', '%' . $query . '%');
                })
                ->get();
        }

        return response()->json($usuarios);
    }
}
