<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar estructura de la tabla personas
echo "Estructura de la tabla personas:\n";
$result = DB::select('DESCRIBE personas');
foreach($result as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Key . ' - ' . $column->Extra . "\n";
}
