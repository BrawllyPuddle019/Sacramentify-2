<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Acta de Matrimonio</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: rgb(178, 218, 215);
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            color: #333;
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
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #666;
        }
        .header h2 {
            font-size: 28px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
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
            border-bottom: 1px solid #666;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        td {
            padding: 10px;
            vertical-align: top;
        }
        td:first-child {
            width: 30%;
            font-weight: bold;
        }
        .datos-principales {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .datos-secundarios {
            margin-top: 20px;
        }
        .texto-compromiso {
            font-style: italic;
            text-align: justify;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            margin: 20px 0;
            line-height: 1.6;
        }
        /* Estilos para las firmas */
        .firma-area {
            position: relative;
            width: 100%;
            margin-top: 50px;
        }
        .firma {
            position: absolute;
            bottom: 50px;
            width: 250px;
        }
        .firma-sacerdote {
            left: 100px;
        }
        .firma-obispo {
            right: 100px;
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
        .sello-area {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-style: italic;
            color: #666;
        }
        .meta-info {
            margin-top: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Acta de Matrimonio</h2>
            <p>Parroquia del Sagrario</p>
        </div>
        
        <div class="content">
            <p class="border-bottom">
                El día <strong>{{ \Carbon\Carbon::parse($matrimonio->fecha)->format('d') }}</strong>
                de <strong>{{ \Carbon\Carbon::parse($matrimonio->fecha)->locale('es')->isoFormat('MMMM') }}</strong>
                de <strong>{{ \Carbon\Carbon::parse($matrimonio->fecha)->format('Y') }}</strong>,
                se unieron en sagrado matrimonio:
            </p>

            <div class="datos-principales">
                <table>
                    <tr>
                        <td><strong>Esposo:</strong></td>
                        <td>{{ $persona ? $persona->nombre . ' ' . $persona->paterno . ' ' . $persona->materno : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Esposa:</strong></td>
                        <td>{{ $persona1 ? $persona1->nombre . ' ' . $persona1->paterno . ' ' . $persona1->materno : 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="datos-secundarios">
                <table>
                    <tr>
                        <td><strong>Padre del Esposo:</strong></td>
                        <td>{{ $padre ? $padre->nombre . ' ' . $padre->paterno . ' ' . $padre->materno : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Madre del Esposo:</strong></td>
                        <td>{{ $madre ? $madre->nombre . ' ' . $madre->paterno . ' ' . $madre->materno : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Padre de la Esposa:</strong></td>
                        <td>{{ $padre1 ? $padre1->nombre . ' ' . $padre1->paterno . ' ' . $padre1->materno : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Madre de la Esposa:</strong></td>
                        <td>{{ $madre1 ? $madre1->nombre . ' ' . $madre1->paterno . ' ' . $madre1->materno : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Testigo:</strong></td>
                        <td>{{ $padrino ? $padrino->nombre . ' ' . $padrino->paterno . ' ' . $padrino->materno : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Testigo:</strong></td>
                        <td>{{ $madrina ? $madrina->nombre . ' ' . $madrina->paterno . ' ' . $madrina->materno : 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="texto-compromiso">
                Los nuevos esposos recibieron la bendición nupcial y se comprometieron a vivir juntos en amor y fidelidad, 
                según las enseñanzas de nuestra Santa Madre Iglesia, ante Dios y la comunidad parroquial.
            </div>

            <div class="meta-info">
                <table>
                    <tr>
                        <td><strong>Lugar de celebración:</strong></td>
                        <td>{{ $ermita ? $ermita->nombre : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Libro:</strong></td>
                        <td>{{ $matrimonio->libro }}</td>
                    </tr>
                    <tr>
                        <td><strong>Foja:</strong></td>
                        <td>{{ $matrimonio->foja }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Área de firmas -->
        <div class="firma-area">
            <div class="firma firma-sacerdote">
                <div class="firma-linea"></div>
                <div class="firma-texto">Firma del Sacerdote</div>
            </div>
            <div class="firma firma-obispo">
                <div class="firma-linea"></div>
                <div class="firma-texto">Firma del Testigo</div>
            </div>
        </div>

        <!-- Área para el sello -->
        <div class="sello-area">
            <p>(Sello Parroquial)</p>
        </div>
    </div>
</body>
</html>