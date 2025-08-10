<?php

use Illuminate\Support\Facades\DB;

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar estructura de la tabla estados
echo "Estructura de la tabla estados:\n";
$result = DB::select('DESCRIBE estados');
foreach($result as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Key . ' - ' . $column->Extra . "\n";
}

echo "\nVerificando claves foráneas que referencian estados:\n";
$foreignKeys = DB::select("
    SELECT 
        TABLE_NAME,
        COLUMN_NAME,
        CONSTRAINT_NAME, 
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE REFERENCED_TABLE_NAME = 'estados'
");

foreach($foreignKeys as $fk) {
    echo "Tabla: {$fk->TABLE_NAME}, Columna: {$fk->COLUMN_NAME}, Referencia: {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
}
