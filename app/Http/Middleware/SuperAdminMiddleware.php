<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión.');
        }

        // Verificar que sea el super administrador específico
        if (auth()->user()->email !== 'adrianagm291104@gmail.com') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }

        return $next($request);
    }
}
