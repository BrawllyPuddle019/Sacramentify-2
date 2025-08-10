<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Probar crear un municipio
try {
    // Obtener el siguiente ID disponible
    $maxId = App\Models\Municipio::max('cve_municipio') ?? 0;
    $nextId = $maxId + 1;
    
    $municipio = new App\Models\Municipio();
    $municipio->cve_municipio = $nextId;
    $municipio->nombre_municipio = 'Macuspana';
    $municipio->cve_estado = 1; // Asumiendo que existe un estado con ID 1
    $municipio->save();
    
    echo "Municipio creado exitosamente: {$municipio->nombre_municipio} con ID: {$municipio->cve_municipio}\n";
    echo "Estado asociado: {$municipio->cve_estado}\n";
} catch (Exception $e) {
    echo "Error al crear municipio: " . $e->getMessage() . "\n";
}
