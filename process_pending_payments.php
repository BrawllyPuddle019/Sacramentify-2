<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Configurar Stripe
\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

$payments = App\Models\Payment::where('status', 'pending')
    ->whereNotNull('stripe_session_id')
    ->get();

echo "Procesando pagos con sesiones de Stripe..." . PHP_EOL;

foreach($payments as $payment) {
    try {
        echo "Procesando pago ID: {$payment->id}, Session: {$payment->stripe_session_id}" . PHP_EOL;
        
        $session = \Stripe\Checkout\Session::retrieve($payment->stripe_session_id);
        
        echo "  Estado de pago en Stripe: {$session->payment_status}" . PHP_EOL;
        
        if ($session->payment_status === 'paid') {
            // Actualizar payment_intent_id si no lo tenemos
            if (!$payment->stripe_payment_intent_id && $session->payment_intent) {
                $payment->update([
                    'stripe_payment_intent_id' => $session->payment_intent
                ]);
                echo "  Payment Intent ID actualizado: {$session->payment_intent}" . PHP_EOL;
            }
            
            // Marcar como exitoso
            $payment->markAsSucceeded();
            
            // Añadir créditos
            $userCredits = App\Models\UserCredit::getOrCreateForUser($payment->user_id);
            $userCredits->addCredits($payment->credits_purchased);
            
            echo "  ✅ Pago procesado exitosamente. {$payment->credits_purchased} créditos añadidos al usuario {$payment->user_id}" . PHP_EOL;
        } else {
            echo "  ⏳ Pago no completado en Stripe (estado: {$session->payment_status})" . PHP_EOL;
        }
        
    } catch (\Exception $e) {
        echo "  ❌ Error procesando pago {$payment->id}: " . $e->getMessage() . PHP_EOL;
    }
    
    echo PHP_EOL;
}

echo "Proceso completado." . PHP_EOL;
