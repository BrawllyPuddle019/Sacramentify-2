<?php

use Illuminate\Support\Facades\DB;

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Estructura de la tabla sacerdotes:\n";
$result = DB::select('DESCRIBE sacerdotes');
foreach($result as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Key . ' - ' . $column->Extra . "\n";
}
