<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        // Validar las credenciales
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|min:8',
        ]);
    
        // Verificar si el admin existe en la base de datos
        $admin = Admin::where('correo', $request->correo)->first();
        if ($admin && Hash::check($request->contraseña, $admin->contraseña)) {
            // Si la contraseña es correcta, autenticar manualmente al admin
            Auth::guard('admin')->login($admin);  // Forzar la autenticación manual del admin
    
            // Verificar si se autenticó correctamente
            return redirect('probando')->with('success', 'Login exitoso como ' . $admin->rol);
        }
    
        // Intentar autenticar como usuario normal
        if (Auth::guard('web')->attempt(['correo' => $request->correo, 'password' => $request->contraseña])) {
            return redirect('libros')->with('success', 'Login exitoso como Usuario');
        }
    
        // Si las credenciales no coinciden
        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('correo'));
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cerrar sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}