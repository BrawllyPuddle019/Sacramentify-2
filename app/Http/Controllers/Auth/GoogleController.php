<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirigir al usuario a Google para autenticación
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Manejar el callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar usuario existente por Google ID
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // Usuario existente con Google ID
                Auth::login($user);
                return redirect()->intended('/home')->with('success', '¡Bienvenido de nuevo!');
            }
            
            // Buscar usuario existente por email
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Usuario existe pero sin Google ID - vincular cuenta
                // Mantener el estado de admin que ya tenía el usuario
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                    // No modificamos is_admin - mantiene su estado actual
                ]);
                
                Auth::login($existingUser);
                return redirect()->intended('/home')->with('success', '¡Cuenta vinculada exitosamente con Google!');
            }
            
            // Crear nuevo usuario
            // Solo tu email específico será admin automáticamente
            $isAdmin = ($googleUser->email === 'adrianagm291104@gmail.com');
            
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => Hash::make(Str::random(16)), // Password aleatorio
                'email_verified_at' => now(),
                'is_admin' => $isAdmin, // Solo tu email será admin
            ]);
            
            Auth::login($newUser);
            
            $message = $isAdmin 
                ? '¡Cuenta de administrador creada exitosamente! Bienvenido al Sistema de Gestión de Sacramentos.'
                : '¡Cuenta creada exitosamente! Bienvenido al Sistema de Gestión de Sacramentos.';
                
            return redirect()->intended('/home')->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error al autenticar con Google: ' . $e->getMessage());
        }
    }
}
