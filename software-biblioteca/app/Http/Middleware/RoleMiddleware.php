<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$roles  // Recibir los roles permitidos
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Verificar si el usuario está autenticado usando el guardia admin
        if (!Auth::guard('admin')->check()) {
            return redirect('/')->with('error', 'Debes iniciar sesión como administrador.');
        }

        // Obtener el usuario autenticado como admin
        $user = Auth::guard('admin')->user();

        // Verificar si el usuario tiene uno de los roles permitidos (superadmin o moderador)
        if (in_array($user->rol, $roles)) {
            return $next($request);  // Continuar si el rol es válido
        }

        // Si el rol no coincide, redirigir
        return redirect('/')->with('error', 'No tienes acceso a esta página.');
    }
}
