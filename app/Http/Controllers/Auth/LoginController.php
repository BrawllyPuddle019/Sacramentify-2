<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Importa Auth

class LoginController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | Login Controller
    |----------------------------------------------------------------------
    |
    | Este controlador maneja la autenticación de los usuarios para la aplicación y
    | los redirige a la pantalla de inicio. El controlador usa un trait
    | para proporcionar convenientemente su funcionalidad.
    |
    */

    use AuthenticatesUsers;

    /**
     * Dónde redirigir a los usuarios después de iniciar sesión.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Método de cierre de sesión
    public function logout(Request $request)
    {
        // Lógica de cierre de sesión
        Auth::logout();  // Ahora funciona correctamente con Auth importado

        // Redirigir a la página de inicio de sesión y pasar una variable de estado
        return redirect('/login')->with('status', 'logged-out');
    }
}
