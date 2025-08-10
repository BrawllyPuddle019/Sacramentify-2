<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$payments = App\Models\Payment::where('status', 'pending')->get();

echo "Pagos pendientes: " . $payments->count() . PHP_EOL;

foreach($payments as $payment) {
    echo "ID: {$payment->id}, Session: {$payment->stripe_session_id}, User: {$payment->user_id}" . PHP_EOL;
}
