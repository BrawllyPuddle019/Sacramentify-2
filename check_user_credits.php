<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::find(2);
if ($user) {
    $credits = $user->getCredits();
    echo "Usuario: {$user->name}" . PHP_EOL;
    echo "Créditos actuales: {$credits}" . PHP_EOL;
    
    $successfulPayments = $user->payments()->where('status', 'succeeded')->get();
    echo "Pagos exitosos: " . $successfulPayments->count() . PHP_EOL;
    
    foreach($successfulPayments as $payment) {
        echo "  - Pago ID: {$payment->id}, Créditos: {$payment->credits_purchased}, Fecha: {$payment->paid_at}" . PHP_EOL;
    }
} else {
    echo "Usuario no encontrado" . PHP_EOL;
}
