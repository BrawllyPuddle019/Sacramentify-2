<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Probar crear un estado
try {
    // Obtener el siguiente ID disponible
    $maxId = App\Models\Estado::max('cve_estado') ?? 0;
    $nextId = $maxId + 1;
    
    $estado = new App\Models\Estado();
    $estado->cve_estado = $nextId;
    $estado->nombre_estado = 'Tabasco';
    $estado->save();
    
    echo "Estado creado exitosamente: {$estado->nombre_estado} con ID: {$estado->cve_estado}\n";
} catch (Exception $e) {
    echo "Error al crear estado: " . $e->getMessage() . "\n";
}
