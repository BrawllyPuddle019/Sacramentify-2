<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Constancia de Confirmación</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: rgb(178, 218, 215);
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        .container {
            width: 95%;
            margin: auto;
            padding: 30px;
            box-sizing: border-box;
            background-color: rgb(178, 218, 215);
            position: relative;
            min-height: 100vh;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .signature {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 150px;
        }
        .signature p {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .border-bottom {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px;
        }
        /* Estilos actualizados para las firmas */
        .firma-area {
            position: relative;
            width: 100%;
            margin-top: 50px;
        }
        .firma {
            position: absolute;
            bottom: 80px;
            width: 250px; /* Ancho fijo para la línea de firma */
        }
        .firma-sacerdote {
            left: 100px; /* Posición desde la izquierda */
        }
        .firma-obispo {
            right: 100px; /* Posición desde la derecha */
        }
        .firma-linea {
            width: 100%;
            border-top: 1px solid black;
            margin-bottom: 10px;
        }
        .firma-texto {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CONSTANCIA DE CONFIRMACIÓN</h2>
            <p>PARROQUIA DEL SAGRARIO</p>
        </div>
        <div class="content">
            <p class="border-bottom">
                El día <strong>{{ \Carbon\Carbon::parse($confirmacion->fecha)->format('d') }}</strong>
                de <strong>{{ \Carbon\Carbon::parse($confirmacion->fecha)->locale('es')->isoFormat('MMMM') }}</strong>
                de <strong>{{ \Carbon\Carbon::parse($confirmacion->fecha)->format('Y') }}</strong>,
                recibió el sacramento de la confirmación:
            </p>
            <table class="border-bottom">
                <tr>
                    <td><strong>Confirmado:</strong></td>
                    <td>{{ $persona ? $persona->nombre . ' ' . $persona->paterno . ' ' . $persona->materno : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Padre:</strong></td>
                    <td>{{ $padre ? $padre->nombre . ' ' . $padre->paterno . ' ' . $padre->materno : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Madre:</strong></td>
                    <td>{{ $madre ? $madre->nombre . ' ' . $madre->paterno . ' ' . $madre->materno : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Padrino:</strong></td>
                    <td>{{ $padrino ? $padrino->nombre . ' ' . $padrino->paterno . ' ' . $padrino->materno : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Madrina:</strong></td>
                    <td>{{ $madrina ? $madrina->nombre . ' ' . $madrina->paterno . ' ' . $madrina->materno : 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="signature">
            <p>Lo certifica:</p>
            <p>
                {{ $sacerdote ? $sacerdote->nombre . ' ' . $sacerdote->paterno . ' ' . $sacerdote->materno : 'N/A' }}
            </p>
            <p>PBRO.</p>
            <p>Celebró el sacramento:</p>
            <p>
                {{ $obispo ? $obispo->nombre . ' ' . $obispo->paterno . ' ' . $obispo->materno : 'N/A' }}
            </p>
        </div>

        <!-- Firmas actualizadas -->
        <div class="firma-area">
            <div class="firma firma-sacerdote">
                <div class="firma-linea"></div>
                <div class="firma-texto">Firma del Sacerdote</div>
            </div>
            <div class="firma firma-obispo">
                <div class="firma-linea"></div>
                <div class="firma-texto">Firma del Obispo</div>
            </div>
        </div>
    </div>
</body>
</html>


