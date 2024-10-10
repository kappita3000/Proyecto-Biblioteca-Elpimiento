<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login'); // Asegúrate de que la vista existe
    }

    // Procesar el login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|min:10',
        ]);
    
        // Intentar autenticar al usuario utilizando 'correo' y 'contraseña'
        if (Auth::attempt(['correo' => $request->correo, 'password' => $request->contraseña])) {
            // Redirigir al usuario autenticado
            return redirect()->intended('home')->with('success', 'Login exitoso');
        }
    
        // Si las credenciales no coinciden, retornar con error
        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('correo'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}
