<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ActaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/ping', function () {
    return response()->json(['success' => true, 'message' => 'API online'], 200);
});

// Rutas públicas para app móvil
Route::post('/mobile/login', [AuthController::class, 'mobileLogin']);
// Cambiar contraseña
Route::post('/mobile/change-password', [AuthController::class, 'changePassword']);
// Rutas protegidas para app móvil
Route::middleware('auth:sanctum')->group(function () {
    // Información del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // === RUTAS PARA ACTAS ===
    
    // Listar actas con paginación
    Route::get('/mobile/actas', [ActaController::class, 'index']);
    
    // Búsqueda de actas - múltiples formas de acceso
    Route::get('/mobile/actas/search/{term}', [ActaController::class, 'search']); // URL con parámetro
    Route::get('/mobile/actas/search', [ActaController::class, 'search']); // Query parameter ?q=termino
    
    // Filtrar actas por tipo de sacramento
    Route::get('/mobile/actas/tipo/{tipoId}', [ActaController::class, 'porTipo']);
    
    // Detalle específico de un acta (debe ir al final para evitar conflictos)
    Route::get('/mobile/actas/{id}', [ActaController::class, 'show']);
    
    // === RUTAS PARA ESTADÍSTICAS ===
    
    // Estadísticas generales
    Route::get('/mobile/estadisticas', [ActaController::class, 'estadisticas']);
    
    // === RUTAS DE AUTENTICACIÓN ===
    
    // Logout
    Route::post('/mobile/logout', [AuthController::class, 'mobileLogout']);
    
    // Verificar estado de autenticación
    Route::get('/mobile/auth/check', function (Request $request) {
        return response()->json([
            'success' => true,
            'authenticated' => true,
            'user' => $request->user()
        ]);
    });
    
    // Actualizar perfil de usuario
    Route::put('/mobile/profile', function (Request $request) {
        $user = $request->user();
        $user->update($request->only(['name', 'email']));
        
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente',
            'user' => $user
        ]);
    });
});

// === RUTAS ADICIONALES PARA FUTURAS FUNCIONALIDADES ===

/*
// Rutas para otros recursos (descomentarlas cuando sea necesario)

Route::middleware('auth:sanctum')->group(function () {
    
    // === RUTAS PARA PERSONAS ===
    Route::get('/mobile/personas', [PersonaController::class, 'index']);
    Route::get('/mobile/personas/search/{term}', [PersonaController::class, 'search']);
    Route::get('/mobile/personas/{id}', [PersonaController::class, 'show']);
    
    // === RUTAS PARA ERMITAS ===
    Route::get('/mobile/ermitas', [ErmitaController::class, 'index']);
    Route::get('/mobile/ermitas/{id}', [ErmitaController::class, 'show']);
    
    // === RUTAS PARA SACERDOTES ===
    Route::get('/mobile/sacerdotes', [SacerdoteController::class, 'index']);
    Route::get('/mobile/sacerdotes/{id}', [SacerdoteController::class, 'show']);
    
    // === RUTAS PARA SACRAMENTOS ===
    Route::get('/mobile/sacramentos', [SacramentoController::class, 'index']);
    Route::get('/mobile/sacramentos/{id}', [SacramentoController::class, 'show']);
    
});
*/