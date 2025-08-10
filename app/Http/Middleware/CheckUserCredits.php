<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckUserCredits
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            
            // Verificar si el usuario tiene créditos
            if (!$user->hasCredits()) {
                // Si es una petición AJAX, devolver JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes créditos suficientes para crear actas.',
                        'redirect' => route('payments.index')
                    ], 402); // 402 Payment Required
                }
                
                // Si es una petición web, redirigir con mensaje
                return redirect()->route('payments.index')
                    ->with('warning', 'No tienes créditos suficientes para crear actas. Por favor, adquiere más créditos.');
            }
        }

        return $next($request);
    }
}
