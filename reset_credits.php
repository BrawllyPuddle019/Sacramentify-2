<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// ID del usuario (ajusta si es necesario)
$userId = 2;

$user = App\Models\User::find($userId);
if (!$user) {
    echo "Usuario con ID {$userId} no encontrado" . PHP_EOL;
    exit;
}

echo "Usuario: {$user->name}" . PHP_EOL;
echo "Créditos actuales: " . $user->getCredits() . PHP_EOL;

// Resetear créditos
$userCredits = App\Models\UserCredit::where('user_id', $userId)->first();

if ($userCredits) {
    $userCredits->update([
        'available_credits' => 0,
        'used_credits' => 0,
        'total_credits' => 0
    ]);
    
    echo "✅ Créditos reseteados a 0" . PHP_EOL;
    echo "Nuevos créditos: " . $user->fresh()->getCredits() . PHP_EOL;
} else {
    echo "❌ No se encontró registro de créditos para este usuario" . PHP_EOL;
}
