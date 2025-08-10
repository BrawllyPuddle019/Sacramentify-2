<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Información de Persona</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
        .card-body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Información de Persona</div>
                    <div class="card-body">
                        @if ($persona)
                            <p><strong>Nombre:</strong> {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</p>
                            @if ($persona->municipios)
                                @if ($persona->municipios->estado)
                                    <p><strong>Estado:</strong> {{ $persona->municipios->estado->nombre }}</p>
                                @else
                                    <p><strong>Estado:</strong> Estado no disponible</p>
                                @endif
                                <p><strong>Municipio:</strong> {{ $persona->municipios->nombre }}</p>
                            @else
                                <p>No tiene municipio asignado.</p>
                            @endif
                        @else
                            <p>No se encontró la persona.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>