<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Constancia de Bautismo</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color:rgb(255, 255, 255);
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        .container {
            width: 90%; /* Ajustamos el ancho */
            margin: auto;
            padding: 30px;
            background-color: rgb(255, 255, 255); /* Fondo blanco para el contenido */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            color: #003366; /* Un color azul para los encabezados */
        }
        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            font-size: 18px;
            font-weight: normal;
            color: #333;
        }
        .content {
            margin-bottom: 40px;
            line-height: 1.8;
            text-align: justify;
        }
        .content p {
            margin: 0;
            padding-bottom: 15px;
        }
        .border-bottom {
            border-bottom: 1px solid #003366;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .signature {
            text-align: center;
            margin-top: 50px;
            font-size: 16px;
        }
        .signature p {
            margin: 5px 0;
        }
        .signature .role {
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        .footer p {
            margin: 0;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature-box {
            text-align: center;
            width: 45%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CONSTANCIA DE BAUTISMO</h2>
            <p>PARROQUIA DEL SAGRARIO</p>
        </div>
        <div class="content">
            <p class="border-bottom">Con el nombre de <strong>{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</strong></p>
            <p class="border-bottom">Fue elegido y santificado por Dios con las aguas del Bautismo y la unción del Espíritu Santo, para formar parte de su pueblo, para escuchar y proclamar su palabra, para adorar a Dios en la comunidad y vivir una vida nueva de amor y servicio a los demás.</p>
            <p class="border-bottom">Nació en <strong>{{ $persona->municipios->nombre }}, {{ $persona->municipios->estado->nombre }}</strong></p>
            <p class="border-bottom">el <strong>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d') }}</strong> de <strong>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->locale('es')->isoFormat('MMMM') }}</strong> de <strong>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('Y') }}</strong>.</p>
            <p class="border-bottom">Recibió la Fe de sus Padres <strong>{{ $padre->nombre }} {{ $padre->paterno }} {{ $padre->materno }}</strong> y <strong>{{ $madre->nombre }} {{ $madre->paterno }} {{ $madre->materno }}</strong></p>
            <p class="border-bottom">y de sus padrinos <strong>{{ $padrino->nombre }} {{ $padrino->paterno }} {{ $padrino->materno }}</strong> y <strong>{{ $madrina->nombre }} {{ $madrina->paterno }} {{ $madrina->materno }}</strong></p>
            <p class="border-bottom">En esta Comunidad Parroquial el <strong>{{ \Carbon\Carbon::parse($bautizo->fecha)->format('d') }}</strong> de <strong>{{ \Carbon\Carbon::parse($bautizo->fecha)->locale('es')->isoFormat('MMMM') }}</strong> de <strong>{{ \Carbon\Carbon::parse($bautizo->fecha)->format('Y') }}</strong>.</p>
        </div>
        <div class="signature-container">
            <div class="signature-box">
                <p class="role">Lo certifica</p>
                <p>{{ $sacerdote->nombre }} {{ $sacerdote->paterno }} {{ $sacerdote->materno }}</p>
            </div>
            <div class="signature-box">
                <p class="role">Me bautizó</p>
                <p>PBRO. {{ $obispo->nombre }} {{ $obispo->paterno }} {{ $obispo->materno }}</p>
            </div>
        </div>
        <div class="footer">
            <p>Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>





