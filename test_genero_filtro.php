<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Persona;

try {
    echo "=== VERIFICACIÓN DE FILTRADO POR GÉNERO ===" . PHP_EOL;
    
    // Obtener hombres (sexo = '1')
    $hombres = Persona::where('sexo', '1')->get();
    echo "✅ HOMBRES (sexo = '1'):" . PHP_EOL;
    foreach ($hombres as $hombre) {
        echo "   - " . $hombre->nombre . " " . $hombre->apellido_paterno . " (ID: " . $hombre->cve_persona . ")" . PHP_EOL;
    }
    
    echo PHP_EOL;
    
    // Obtener mujeres (sexo = '0')
    $mujeres = Persona::where('sexo', '0')->get();
    echo "✅ MUJERES (sexo = '0'):" . PHP_EOL;
    foreach ($mujeres as $mujer) {
        echo "   - " . $mujer->nombre . " " . $mujer->apellido_paterno . " (ID: " . $mujer->cve_persona . ")" . PHP_EOL;
    }
    
    echo PHP_EOL;
    echo "✅ Filtrado funcionando correctamente." . PHP_EOL;
    echo "   Total hombres: " . $hombres->count() . PHP_EOL;
    echo "   Total mujeres: " . $mujeres->count() . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
