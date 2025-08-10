<?php

require_once 'vendor/autoload.php';

// Cargar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\UserCredit;

echo "=== RESETEAR CRÉDITOS A CERO ===\n";

try {
    // Mostrar usuarios actuales
    $users = User::all();
    echo "\nUsuarios encontrados:\n";
    foreach($users as $user) {
        echo "ID: {$user->id} - Nombre: {$user->name} - Email: {$user->email}\n";
    }
    
    // Resetear todos los créditos a 0
    $updated = UserCredit::query()->update([
        'available_credits' => 0,
        'used_credits' => 0,
        'total_credits' => 0
    ]);
    echo "\n✅ Se actualizaron {$updated} registros de créditos a 0\n";
    
    // Mostrar créditos actuales
    $credits = UserCredit::all();
    echo "\nCréditos actuales:\n";
    foreach($credits as $credit) {
        $user = User::find($credit->user_id);
        echo "Usuario: {$user->name} - Disponibles: {$credit->available_credits} - Usados: {$credit->used_credits} - Total: {$credit->total_credits}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== PROCESO COMPLETADO ===\n";
