<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta Sacramental</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        .content {
            padding: 30px;
        }
        
        .acta-info {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .acta-info h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 18px;
        }
        
        .acta-info .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .acta-info .label {
            font-weight: 600;
            min-width: 120px;
            color: #34495e;
        }
        
        .acta-info .value {
            color: #2c3e50;
        }
        
        .message-section {
            margin: 25px 0;
            padding: 20px;
            background-color: #e8f4fd;
            border-radius: 8px;
            border: 1px solid #bee5eb;
        }
        
        .message-section h4 {
            margin: 0 0 10px 0;
            color: #0c5460;
            font-size: 16px;
        }
        
        .attachment-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .attachment-info .icon {
            font-size: 24px;
            color: #856404;
            margin-bottom: 8px;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
            color: #6c757d;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 20px;
            }
            
            .acta-info .info-row {
                flex-direction: column;
            }
            
            .acta-info .label {
                min-width: auto;
                margin-bottom: 3px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">⛪</div>
            <h1>Sistema de Gestión de Sacramentos</h1>
            <p>Acta Sacramental Adjunta</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p>Estimado/a destinatario/a,</p>
            
            <p>Se adjunta el documento PDF correspondiente al <strong>Acta de {{ $acta->tipoActa->nombre ?? 'Sacramento' }}</strong> solicitada.</p>
            
            <!-- Acta Information -->
            <div class="acta-info">
                <h3>📋 Información del Acta</h3>
                
                <div class="info-row">
                    <span class="label">Sacramento:</span>
                    <span class="value">{{ $acta->tipoActa->nombre ?? 'N/A' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Persona:</span>
                    <span class="value">{{ $acta->persona ? trim($acta->persona->nombre . ' ' . $acta->persona->paterno . ' ' . $acta->persona->materno) : 'N/A' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Fecha del Acta:</span>
                    <span class="value">{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : 'N/A' }}</span>
                </div>
                
                @if($acta->ermita)
                <div class="info-row">
                    <span class="label">Ermita:</span>
                    <span class="value">{{ $acta->ermita->nombre ?? 'N/A' }}</span>
                </div>
                @endif
                
                @if($acta->sacerdoteCelebrante)
                <div class="info-row">
                    <span class="label">Celebrante:</span>
                    <span class="value">{{ trim(($acta->sacerdoteCelebrante->nombre ?? '') . ' ' . ($acta->sacerdoteCelebrante->paterno ?? '') . ' ' . ($acta->sacerdoteCelebrante->materno ?? '')) }}</span>
                </div>
                @endif
            </div>

            <!-- Additional Information based on Sacrament Type -->
            @if($acta->tipoActa && $acta->tipoActa->nombre)
                @php $tipoSacramento = strtolower($acta->tipoActa->nombre); @endphp
                
                @if($tipoSacramento == 'matrimonio' && $acta->persona2)
                <div class="acta-info">
                    <h3>💒 Información del Matrimonio</h3>
                    <div class="info-row">
                        <span class="label">Esposo:</span>
                        <span class="value">{{ trim(($acta->persona->nombre ?? '') . ' ' . ($acta->persona->paterno ?? '') . ' ' . ($acta->persona->materno ?? '')) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Esposa:</span>
                        <span class="value">{{ trim(($acta->persona2->nombre ?? '') . ' ' . ($acta->persona2->paterno ?? '') . ' ' . ($acta->persona2->materno ?? '')) }}</span>
                    </div>
                </div>
                @elseif($tipoSacramento == 'bautizo' && ($acta->padre || $acta->madre))
                <div class="acta-info">
                    <h3>👶 Información del Bautizo</h3>
                    @if($acta->padre)
                    <div class="info-row">
                        <span class="label">Padre:</span>
                        <span class="value">{{ trim(($acta->padre->nombre ?? '') . ' ' . ($acta->padre->paterno ?? '') . ' ' . ($acta->padre->materno ?? '')) }}</span>
                    </div>
                    @endif
                    @if($acta->madre)
                    <div class="info-row">
                        <span class="label">Madre:</span>
                        <span class="value">{{ trim(($acta->madre->nombre ?? '') . ' ' . ($acta->madre->paterno ?? '') . ' ' . ($acta->madre->materno ?? '')) }}</span>
                    </div>
                    @endif
                    @if($acta->padrino)
                    <div class="info-row">
                        <span class="label">Padrino:</span>
                        <span class="value">{{ trim(($acta->padrino->nombre ?? '') . ' ' . ($acta->padrino->paterno ?? '') . ' ' . ($acta->padrino->materno ?? '')) }}</span>
                    </div>
                    @endif
                    @if($acta->madrina)
                    <div class="info-row">
                        <span class="label">Madrina:</span>
                        <span class="value">{{ trim(($acta->madrina->nombre ?? '') . ' ' . ($acta->madrina->paterno ?? '') . ' ' . ($acta->madrina->materno ?? '')) }}</span>
                    </div>
                    @endif
                </div>
                @elseif($tipoSacramento == 'confirmacion')
                <div class="acta-info">
                    <h3>✋ Información de la Confirmación</h3>
                    @if($acta->padrino1)
                    <div class="info-row">
                        <span class="label">Padrino:</span>
                        <span class="value">{{ trim(($acta->padrino1->nombre ?? '') . ' ' . ($acta->padrino1->paterno ?? '') . ' ' . ($acta->padrino1->materno ?? '')) }}</span>
                    </div>
                    @endif
                    @if($acta->madrina1)
                    <div class="info-row">
                        <span class="label">Madrina:</span>
                        <span class="value">{{ trim(($acta->madrina1->nombre ?? '') . ' ' . ($acta->madrina1->paterno ?? '') . ' ' . ($acta->madrina1->materno ?? '')) }}</span>
                    </div>
                    @endif
                </div>
                @endif
            @endif

            <!-- Custom Message -->
            @if($customMessage)
            <div class="message-section">
                <h4>💬 Mensaje del remitente:</h4>
                <p>{{ $customMessage }}</p>
            </div>
            @endif
            
            <!-- Attachment Info -->
            <div class="attachment-info">
                <div class="icon">📎</div>
                <strong>Documento adjunto:</strong> Acta_{{ str_replace(' ', '_', $acta->tipoActa->nombre ?? 'Sacramento') }}_{{ str_replace(' ', '_', $acta->persona->nombre ?? 'Persona') }}.pdf
                <br>
                <small>El documento se encuentra adjunto a este correo en formato PDF.</small>
            </div>
            
            <p>Este documento es de carácter oficial y contiene información sacramental importante.</p>
            
            <p style="margin-top: 30px;">
                <strong>Atentamente,</strong><br>
                Sistema de Gestión de Sacramentos
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Sistema de Gestión de Sacramentos</strong></p>
            <p>Este es un email automático generado por el sistema.</p>
            <p>Fecha de envío: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
