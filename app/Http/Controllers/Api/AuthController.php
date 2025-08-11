<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login para app móvil
     */
    public function mobileLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales no coinciden con nuestros registros.'
            ], 401);
        }

        // Verificar que no sea administrador (solo usuarios pueden usar la app móvil)
        if ($user->role === 'admin' || $user->is_admin == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Esta aplicación está destinada solo para usuarios. Los administradores deben usar el panel web.'
            ], 403);
        }

        // Revocar tokens existentes
        $user->tokens()->delete();

        // Crear nuevo token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'message' => 'Login exitoso'
        ]);
    }

    /**
     * Logout para app móvil
     */
    public function mobileLogout(Request $request)
    {
        // Revocar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ]);
    }

    /**
     * Cambiar contraseña del usuario autenticado
     */
    public function changePassword(Request $request)
    {
        try {
            // Validar los datos
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed', // confirmed busca new_password_confirmation
            ]);

            $user = $request->user();

            // Verificar que la contraseña actual sea correcta
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 400);
            }

            // Actualizar la contraseña
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}
