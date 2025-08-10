<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Probando actualización de sexo...\n";

try {
    // Buscar una persona masculina
    $personaMasculina = App\Models\Persona::where('sexo', '1')->first();
    
    if ($personaMasculina) {
        echo "Persona masculina encontrada: {$personaMasculina->nombre}\n";
        echo "Sexo actual: {$personaMasculina->sexo}\n";
        
        // Cambiar a femenino
        $personaMasculina->sexo = '0';
        $personaMasculina->save();
        echo "Cambiado a femenino: {$personaMasculina->sexo}\n";
        
        // Cambiar de vuelta a masculino
        $personaMasculina->sexo = '1';
        $personaMasculina->save();
        echo "Cambiado de vuelta a masculino: {$personaMasculina->sexo}\n";
        
        echo "✅ Test de actualización de sexo exitoso!\n";
    } else {
        echo "No se encontró persona masculina para probar.\n";
    }
    
} catch (Exception $e) {
    echo "Error durante el test: " . $e->getMessage() . "\n";
}
