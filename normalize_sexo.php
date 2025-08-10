<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Normalizando valores de sexo en la base de datos...\n";

try {
    // Convertir 'M' a '1' (Masculino)
    $masculinos = App\Models\Persona::where('sexo', 'M')->get();
    foreach($masculinos as $persona) {
        $persona->sexo = '1';
        $persona->save();
        echo "Convertido: {$persona->nombre} - M → 1\n";
    }
    
    // Convertir 'F' a '0' (Femenino)
    $femeninos = App\Models\Persona::where('sexo', 'F')->get();
    foreach($femeninos as $persona) {
        $persona->sexo = '0';
        $persona->save();
        echo "Convertido: {$persona->nombre} - F → 0\n";
    }
    
    echo "\nVerificando todos los valores después de la normalización:\n";
    $personas = App\Models\Persona::select('cve_persona', 'nombre', 'sexo')->get();
    foreach($personas as $persona) {
        $sexoTexto = $persona->sexo == '1' ? 'Masculino' : 'Femenino';
        echo "ID: {$persona->cve_persona} - {$persona->nombre} - Sexo: {$persona->sexo} ({$sexoTexto})\n";
    }
    
    echo "\n¡Normalización completada exitosamente!\n";
    
} catch (Exception $e) {
    echo "Error durante la normalización: " . $e->getMessage() . "\n";
}
