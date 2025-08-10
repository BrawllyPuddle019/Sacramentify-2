<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all(); // Obtén todos los usuarios de la base de datos
        return view('users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     * Esto es solo un ejemplo, ajusta según tus necesidades.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     * Esto es solo un ejemplo, ajusta según tus necesidades.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create($validatedData);
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Actualiza el estado de administrador de un usuario.
     * Solo disponible para el super administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function toggleAdmin(Request $request, User $user)
    {
        // Solo el super administrador puede promocionar usuarios
        if (auth()->user()->email !== 'adrianagm291104@gmail.com') {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // No puede cambiarse a sí mismo (protección)
        if ($user->id === auth()->user()->id) {
            return back()->with('error', 'No puedes cambiar tu propio estado de administrador.');
        }
        
        // Cambiar el estado de administrador
        $user->is_admin = !$user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'promovido a administrador' : 'removido como administrador';
        return back()->with('success', "El usuario {$user->name} ha sido {$action}.");
    }
}