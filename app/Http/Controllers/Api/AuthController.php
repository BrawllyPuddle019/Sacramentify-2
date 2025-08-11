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
                'new_password' => 'required|string|min:6', // Cambiado a 6 para coincidir con el frontend
                'new_password_confirmation' => 'required|string|same:new_password', // Validación manual
            ]);

            $user = $request->user();
            
            // Verificar que el usuario esté autenticado
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Verificar que la contraseña actual sea correcta
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 400);
            }

            // Verificar que las contraseñas nuevas coincidan
            if ($request->new_password !== $request->new_password_confirmation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Las contraseñas nuevas no coinciden'
                ], 400);
            }

            // Actualizar la contraseña
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Log para debugging
            Log::info('Contraseña cambiada exitosamente para usuario: ' . $user->email);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación al cambiar contraseña: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar mensaje de soporte al administrador
     */
    public function sendSupportMessage(Request $request)
    {
        try {
            $request->validate([
                'subject' => 'required|string|max:200',
                'message' => 'required|string|max:1000',
            ]);

            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Crear el mensaje de soporte (puedes guardarlo en BD o enviarlo por email)
            $supportData = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'created_at' => now(),
            ];

            // Log del mensaje para que el admin lo vea
            Log::info('MENSAJE DE SOPORTE RECIBIDO', $supportData);

            // Aquí podrías:
            // 1. Guardar en una tabla 'support_messages'
            // 2. Enviar email al admin
            // 3. O simplemente loggearlo como está arriba

            return response()->json([
                'success' => true,
                'message' => 'Tu mensaje de soporte ha sido enviado correctamente. El administrador lo revisará pronto.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje de soporte: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}
