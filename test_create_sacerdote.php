<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Probando la creación de un sacerdote...\n\n";

try {
    // Obtener el siguiente ID disponible
    $maxId = App\Models\Sacerdote::max('cve_sacerdotes') ?? 0;
    $nextId = $maxId + 1;
    
    // Verificar que hay diócesis disponibles
    $diocesis = App\Models\Diocesi::first();
    if (!$diocesis) {
        echo "❌ No hay diócesis disponibles. Creando una de prueba...\n";
        
        $maxDiocesisId = App\Models\Diocesi::max('cve_diocesis') ?? 0;
        $nextDiocesisId = $maxDiocesisId + 1;
        
        $diocesis = new App\Models\Diocesi();
        $diocesis->cve_diocesis = $nextDiocesisId;
        $diocesis->nombre_diocesis = 'Diócesis de Prueba';
        $diocesis->save();
        
        echo "✅ Diócesis creada: {$diocesis->nombre_diocesis} (ID: {$diocesis->cve_diocesis})\n";
    }
    
    $sacerdote = new App\Models\Sacerdote();
    $sacerdote->cve_sacerdotes = $nextId;
    $sacerdote->nombre_sacerdote = 'José';
    $sacerdote->apellido_paterno = 'García';
    $sacerdote->apellido_materno = 'López';
    $sacerdote->cve_diocesis = $diocesis->cve_diocesis;
    $sacerdote->save();
    
    echo "✅ Sacerdote creado exitosamente:\n";
    echo "ID: {$sacerdote->cve_sacerdotes}\n";
    echo "Nombre: {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno} {$sacerdote->apellido_materno}\n";
    echo "Diócesis: {$sacerdote->cve_diocesis}\n";
    
} catch (Exception $e) {
    echo "❌ Error al crear sacerdote: " . $e->getMessage() . "\n";
}
