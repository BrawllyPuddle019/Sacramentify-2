<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentPackage;
use App\Models\Payment;
use App\Models\UserCredit;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Muestra la página de paquetes de créditos
     */
    public function index()
    {
        $packages = PaymentPackage::getAvailablePackages();
        /** @var User $user */
        $user = Auth::user();
        $userCredits = $user->getCredits();
        
        return view('payments.index', compact('packages', 'userCredits'));
    }

    /**
     * Muestra el historial de pagos del usuario
     */
    public function history()
    {
        /** @var User $user */
        $user = Auth::user();
        $payments = $user->payments()
            ->with('paymentPackage')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('payments.history', compact('payments'));
    }

    /**
     * Crea una sesión de pago con Stripe
     */
    public function createCheckoutSession(Request $request)
    {
        try {
            Log::info('=== INICIO createCheckoutSession ===', ['request_data' => $request->all()]);
            
            // Validación básica
            $request->validate([
                'package_id' => 'required|exists:payment_packages,id'
            ]);
            Log::info('Validación pasada');

            // Obtener paquete
            $package = PaymentPackage::findOrFail($request->package_id);  
            Log::info('Paquete encontrado', ['package' => $package->toArray()]);
            
            // Obtener usuario
            $user = Auth::user();
            Log::info('Usuario encontrado', ['user_id' => $user->id]);

            // Crear el registro de pago
            $payment = Payment::create([
                'user_id' => $user->id,
                'payment_package_id' => $package->id,
                'status' => 'pending',
                'amount' => $package->price,
                'currency' => $package->currency,
                'credits_purchased' => $package->credits_amount,
            ]);

            Log::info('Pago creado', ['payment_id' => $payment->id]);

            // Crear sesión de Stripe Checkout
            Log::info('Creando sesión de Stripe...');
            
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($package->currency),
                        'product_data' => [
                            'name' => $package->name,
                            'description' => $package->description,
                        ],
                        'unit_amount' => intval($package->price * 100), // Stripe usa centavos
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['payment' => $payment->id]),
                'cancel_url' => route('payment.cancel', ['payment' => $payment->id]),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                ],
            ]);

            Log::info('Sesión de Stripe creada exitosamente', [
                'session_id' => $session->id,
                'checkout_url' => $session->url,
                'payment_intent' => $session->payment_intent
            ]);

            // Actualizar el pago con el ID de la sesión solamente
            $payment->update([
                'stripe_session_id' => $session->id,
            ]);

            Log::info('Pago actualizado con session_id', ['payment_id' => $payment->id]);

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('=== ERROR de Stripe ===', [
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getStripeCode(),
                'body' => $e->getJsonBody()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error de Stripe: ' . $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('=== ERROR general ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de éxito después del pago
     */
    public function success(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        Log::info('=== SUCCESS CALLBACK ===', [
            'payment_id' => $payment->id,
            'current_status' => $payment->status,
            'stripe_session_id' => $payment->stripe_session_id
        ]);

        // Verificar el estado del pago con Stripe usando la sesión
        try {
            if ($payment->stripe_session_id) {
                $session = Session::retrieve($payment->stripe_session_id);
                
                Log::info('Stripe session retrieved', [
                    'session_id' => $session->id,
                    'payment_status' => $session->payment_status,
                    'payment_intent' => $session->payment_intent
                ]);
                
                if ($session->payment_status === 'paid' && $payment->status !== 'succeeded') {
                    // Actualizar el payment_intent_id si no lo tenemos
                    if (!$payment->stripe_payment_intent_id && $session->payment_intent) {
                        $payment->update([
                            'stripe_payment_intent_id' => $session->payment_intent
                        ]);
                    }
                    
                    $this->processSuccessfulPayment($payment);
                    Log::info('Payment processed successfully', ['payment_id' => $payment->id]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error verifying payment in success callback', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id
            ]);
        }

        return view('payments.success', compact('payment'));
    }

    /**
     * Página de cancelación del pago
     */
    public function cancel(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $payment->update(['status' => 'canceled']);

        return view('payments.cancel', compact('payment'));
    }

    /**
     * Webhook de Stripe para manejar eventos
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response('Webhook signature verification failed', 400);
        }

        // Manejar el evento
        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event['data']['object']);
                break;
            
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event['data']['object']);
                break;
                
            default:
                Log::info('Received unknown event type: ' . $event['type']);
        }

        return response('Webhook handled', 200);
    }

    /**
     * Procesa un pago exitoso
     */
    private function processSuccessfulPayment(Payment $payment)
    {
        $payment->markAsSucceeded();
        
        // Añadir créditos al usuario
        $userCredits = UserCredit::getOrCreateForUser($payment->user_id);
        $userCredits->addCredits($payment->credits_purchased);
        
        Log::info("Payment {$payment->id} processed successfully. Added {$payment->credits_purchased} credits to user {$payment->user_id}");
    }

    /**
     * Maneja el evento de pago exitoso de Stripe
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent['id'])->first();
        
        if ($payment && $payment->status !== 'succeeded') {
            $this->processSuccessfulPayment($payment);
        }
    }

    /**
     * Maneja el evento de pago fallido de Stripe
     */
    private function handlePaymentIntentFailed($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent['id'])->first();
        
        if ($payment) {
            $payment->markAsFailed();
            Log::info("Payment {$payment->id} marked as failed");
        }
    }
}
