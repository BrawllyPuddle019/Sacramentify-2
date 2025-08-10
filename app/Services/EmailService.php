<?php

namespace App\Services;

use App\Models\Acta;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class EmailService
{
    /**
     * Envía un PDF de acta por email
     *
     * @param Acta $acta
     * @param string $recipientEmail
     * @param string|null $message
     * @return array
     */
    public function sendActaPdf(Acta $acta, string $recipientEmail, ?string $message = null)
    {
        try {
            // Cargar relaciones necesarias para el PDF
            $acta->load([
                'persona.municipio',
                'persona2.municipio', 
                'padre.municipio',
                'madre.municipio',
                'padre1.municipio',
                'madre1.municipio',
                'padrino.municipio',
                'madrina.municipio',
                'padrino1.municipio',
                'madrina1.municipio',
                'tipoActa',
                'ermita',
                'sacerdoteCelebrante',
                'sacerdoteAsistente',
                'obispoCelebrante'
            ]);

            // Generar el PDF
            $pdf = Pdf::loadView('actas.pdf', [
                'acta' => $acta,
                'tipo' => strtolower($acta->tipoActa->nombre ?? 'sacramento')
            ]);

            // Datos para el email
            $subject = $this->generateSubject($acta);
            $pdfFileName = $this->generatePdfFileName($acta);

            // Enviar el email
            Mail::send('emails.acta', [
                'acta' => $acta,
                'recipientEmail' => $recipientEmail,
                'customMessage' => $message
            ], function ($mail) use ($recipientEmail, $subject, $pdf, $pdfFileName) {
                $mail->to($recipientEmail)
                     ->subject($subject)
                     ->attachData($pdf->output(), $pdfFileName, [
                         'mime' => 'application/pdf',
                     ]);
            });

            // Log del envío exitoso
            Log::info('PDF enviado por email', [
                'acta_id' => $acta->cve_actas,
                'recipient' => $recipientEmail,
                'sent_by' => auth()->user()->email
            ]);

            return [
                'success' => true,
                'message' => 'PDF enviado exitosamente a ' . $recipientEmail
            ];

        } catch (\Exception $e) {
            // Log del error
            Log::error('Error enviando PDF por email', [
                'acta_id' => $acta->cve_actas,
                'recipient' => $recipientEmail,
                'error' => $e->getMessage(),
                'sent_by' => auth()->user()->email ?? 'unknown'
            ]);

            return [
                'success' => false,
                'message' => 'Error al enviar el email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Genera el asunto del email basado en el acta
     */
    private function generateSubject(Acta $acta): string
    {
        $sacramento = $acta->tipoActa->nombre ?? 'Sacramento';
        $persona = $acta->persona->nombre ?? 'Persona';
        
        return "Acta de {$sacramento} - {$persona}";
    }

    /**
     * Genera el nombre del archivo PDF
     */
    private function generatePdfFileName(Acta $acta): string
    {
        $sacramento = str_replace(' ', '_', $acta->tipoActa->nombre ?? 'Sacramento');
        $persona = str_replace(' ', '_', $acta->persona->nombre ?? 'Persona');
        $fecha = now()->format('Y-m-d');
        
        return "Acta_{$sacramento}_{$persona}_{$fecha}.pdf";
    }

    /**
     * Valida que el email sea válido
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Obtiene información de configuración de email
     */
    public function getEmailConfig(): array
    {
        return [
            'from_name' => config('mail.from.name'),
            'from_address' => config('mail.from.address'),
            'mailer' => config('mail.default')
        ];
    }
}
