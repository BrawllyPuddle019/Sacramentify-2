<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>
        @if($acta->tipoActa)
            Acta de {{ $acta->tipoActa->nombre }}
        @else
            Acta Sacramental
        @endif
    </title>
    <style>
        @page {
            margin: 20mm 15mm;
            size: A4;
        }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #2c3e50;
            line-height: 1.3;
            font-size: 12px;
        }
        .container {
            width: 100%;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
            padding: 5px 0;
            border-bottom: 3px double #8B4513;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #8B4513;
            font-weight: bold;
        }
        .header h2 {
            font-size: 18px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c3e50;
        }
        .header .subtitle {
            font-size: 14px;
            color: #6c757d;
            font-style: italic;
            margin: 0;
        }
        .content {
            margin: 8px 0;
            text-align: justify;
            font-size: 12px;
            line-height: 1.4;
        }
        .content p {
            margin: 0 0 6px 0;
            text-indent: 20px;
        }
        .data-section {
            margin: 5px 0;
            padding: 6px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #8B4513;
        }
        .data-section h4 {
            color: #8B4513;
            margin: 0 0 10px 0;
            text-align: center;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .data-row {
            display: flex;
            margin-bottom: 4px;
            align-items: baseline;
        }
        .data-label {
            font-weight: bold;
            min-width: 100px;
            color: #2c3e50;
            font-size: 11px;
        }
        .data-value {
            flex: 1;
            border-bottom: 1px dotted #6c757d;
            padding-bottom: 1px;
            margin-left: 8px;
            font-size: 11px;
        }
        .signature-section {
            margin-top: 15px;
            width: 100%;
            overflow: hidden;
            padding: 0 10px;
        }
        .signature-section:after {
            content: "";
            display: table;
            clear: both;
        }
        .signature-box {
            text-align: center;
            width: 45%;
            float: left;
            margin: 0;
        }
        .signature-box:first-child {
            margin-right: 10%;
        }
        .signature-line {
            border-bottom: 1px solid #2c3e50;
            margin-bottom: 3px;
            height: 25px;
        }
        .signature-label {
            font-size: 9px;
            color: #6c757d;
            font-weight: bold;
            line-height: 1.1;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
        .registro-info {
            background-color: #e9ecef;
            border: 1px solid #8B4513;
            border-radius: 5px;
            padding: 6px;
            margin: 5px 0;
            text-align: center;
        }
        .registro-info h3 {
            margin: 0 0 8px 0;
            color: #8B4513;
            font-size: 14px;
            text-transform: uppercase;
        }
        .registro-data {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px;
        }
        .registro-item {
            font-weight: bold;
            font-size: 11px;
            white-space: nowrap;
        }
        .matrimonio-section {
            display: grid;
            justify-content: space-between;
            margin: 15px 0;
            gap: 20px;
            width: 100%;
        }
        .matrimonio-column {
            width: 48%;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            box-sizing: border-box;
        }
        .matrimonio-column h4 {
            color: #8B4513;
            text-align: center;
            margin: 0 0 10px 0;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 1px solid #8B4513;
            padding-bottom: 3px;
        }
        .personas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 10px 0;
        }
        .contrayentes-grid {
            width: 100%;
            margin: 5px 0;
            overflow: hidden;
        }
        .contrayentes-grid:after {
            content: "";
            display: table;
            clear: both;
        }
        .contrayente-box {
            width: 48%;
            float: left;
            border: 1px solid #dee2e6;
            padding: 8px;
            border-radius: 3px;
            background-color: #fafafa;
            box-sizing: border-box;
        }
        .contrayente-box:first-child {
            margin-right: 4%;
        }
        .contrayente-title {
            color: #8B4513;
            margin: 0 0 8px 0;
            text-align: center;
            font-size: 12px;
            border-bottom: 1px solid #8B4513;
            padding-bottom: 2px;
            font-weight: bold;
        }
        .persona-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 8px;
        }
        .persona-title {
            font-weight: bold;
            color: #8B4513;
            font-size: 11px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Constancia Sacramental</h1>
            @if($acta->tipoActa)
                <h2>{{ $acta->tipoActa->nombre }}</h2>
            @endif
            @if($acta->ermita)
                <p class="subtitle">{{ $acta->ermita->nombre }}</p>
            @endif
        </div>

        <!-- Content based on Sacramento type -->
        @if($acta->tipoActa && strtolower($acta->tipoActa->nombre) == 'matrimonio')
            @include('actas.pdf-sections.matrimonio', ['acta' => $acta])
        @elseif($acta->tipoActa && strtolower($acta->tipoActa->nombre) == 'bautismo')
            @include('actas.pdf-sections.bautizo', ['acta' => $acta])
        @elseif($acta->tipoActa && strtolower($acta->tipoActa->nombre) == 'confirmación')
            @include('actas.pdf-sections.confirmacion', ['acta' => $acta])
        @else
            <div class="content">
                <p>Se certifica que en los registros de esta parroquia consta el acta correspondiente al sacramento administrado.</p>
            </div>
        @endif

        <!-- Registro Information -->
        <div class="registro-info">
            <h3>Datos del Registro</h3>
            <div class="registro-data">
                <div class="registro-item">
                    <strong>Libro:</strong> {{ $acta->Libro ?? 'N/A' }}
                </div>
                <div class="registro-item">
                    <strong>Fojas:</strong> {{ $acta->Fojas ?? 'N/A' }}
                </div>
                <div class="registro-item">
                    <strong>Folio:</strong> {{ $acta->Folio ?? 'N/A' }}
                </div>
                <div class="registro-item">
                    <strong>Fecha:</strong> {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            @if($acta->sacerdoteCelebrante)
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">
                        {{ $acta->sacerdoteCelebrante->nombre_sacerdote }} {{ $acta->sacerdoteCelebrante->apellido_paterno }} {{ $acta->sacerdoteCelebrante->apellido_materno }}<br>
                        Sacerdote Celebrante
                    </div>
                </div>
            @endif

            @if($acta->obispoCelebrante)
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">
                        {{ $acta->obispoCelebrante->nombre_obispo }} {{ $acta->obispoCelebrante->apellido_paterno }} {{ $acta->obispoCelebrante->apellido_materno }}<br>
                        Obispo Celebrante
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este documento es una constancia oficial del sacramento registrado en esta parroquia.</p>
            <p>Expedido el {{ \Carbon\Carbon::now()->format('d/m/Y') }} - ID del Acta: {{ $acta->cve_actas }}</p>
        </div>
    </div>
</body>
</html>
