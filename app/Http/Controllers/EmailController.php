<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Muestra el modal/formulario para enviar email
     */
    public function showSendForm(Acta $acta)
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para enviar actas.'
            ], 401);
        }

        // Cargar las relaciones necesarias
        $acta->load(['tipoActa', 'persona']);

        // Construir nombre completo de la persona
        $nombreCompleto = 'N/A';
        if ($acta->persona) {
            $partes = array_filter([
                $acta->persona->nombre,
                $acta->persona->paterno,
                $acta->persona->materno
            ]);
            $nombreCompleto = implode(' ', $partes);
        }

        // Construir información del libro/folio
        $libroFolio = 'N/A';
        if ($acta->Libro && $acta->Folio) {
            $libroFolio = "Libro {$acta->Libro}, Folio {$acta->Folio}";
        } elseif ($acta->Libro) {
            $libroFolio = "Libro {$acta->Libro}";
        } elseif ($acta->Folio) {
            $libroFolio = "Folio {$acta->Folio}";
        }

        return response()->json([
            'success' => true,
            'acta' => [
                'id' => $acta->cve_actas,
                'sacramento' => $acta->tipoActa->nombre ?? 'N/A',
                'persona' => $nombreCompleto,
                'fecha' => $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : 'N/A',
                'libro_folio' => $libroFolio
            ]
        ]);
    }

    /**
     * Envía el PDF por email
     */
    public function sendActaPdf(Request $request, Acta $acta)
    {
        // Validar datos del formulario
        $validator = Validator::make($request->all(), [
            'recipient_email' => 'required|email|max:255',
            'message' => 'nullable|string|max:1000'
        ], [
            'recipient_email.required' => 'El email del destinatario es obligatorio.',
            'recipient_email.email' => 'El email debe tener un formato válido.',
            'recipient_email.max' => 'El email no puede tener más de 255 caracteres.',
            'message.max' => 'El mensaje no puede tener más de 1000 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para enviar actas.'
            ], 401);
        }

        // Enviar el email usando el servicio
        $result = $this->emailService->sendActaPdf(
            $acta,
            $request->recipient_email,
            $request->message
        );

        // Responder según el resultado
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 500);
        }
    }

    /**
     * Obtiene el historial de envíos de un acta (funcionalidad futura)
     */
    public function getEmailHistory(Acta $acta)
    {
        // Por ahora, devolvemos un array vacío
        // En el futuro aquí podríamos implementar un modelo EmailLog
        return response()->json([
            'success' => true,
            'history' => []
        ]);
    }

    /**
     * Verifica la configuración de email
     */
    public function checkEmailConfig()
    {
        $config = $this->emailService->getEmailConfig();
        
        return response()->json([
            'success' => true,
            'config' => [
                'from_name' => $config['from_name'],
                'mailer' => $config['mailer'],
                'configured' => !empty($config['from_address'])
            ]
        ]);
    }
}
