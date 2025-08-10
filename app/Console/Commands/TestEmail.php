<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {recipient}';
    protected $description = 'Envía un email de prueba';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        try {
            Mail::raw('Este es un email de prueba desde Laravel Sacramentify.', function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('Prueba de Email - Sacramentify');
            });
            
            $this->info("✅ Email enviado exitosamente a: {$recipient}");
        } catch (\Exception $e) {
            $this->error("❌ Error al enviar email: " . $e->getMessage());
        }
    }
}
