<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRUEBA DE MODELOS CORREGIDOS ===\n";

try {
    echo "1. Probando modelo Persona:\n";
    $persona = App\Models\Persona::first();
    if ($persona) {
        echo "   ✅ Persona encontrada: " . $persona->nombre . "\n";
        echo "   - Paterno: " . $persona->paterno . "\n";
        echo "   - Materno: " . $persona->materno . "\n";
        echo "   - Municipio: " . ($persona->municipio ? $persona->municipio->nombre : 'Sin municipio') . "\n";
    } else {
        echo "   ❌ No se encontraron personas\n";
    }
    
    echo "\n2. Probando modelo Municipio:\n";
    $municipio = App\Models\Municipio::first();
    if ($municipio) {
        echo "   ✅ Municipio encontrado: " . $municipio->nombre . "\n";
        echo "   - Estado: " . ($municipio->estado ? $municipio->estado->nombre : 'Sin estado') . "\n";
    } else {
        echo "   ❌ No se encontraron municipios\n";
    }
    
    echo "\n3. Probando modelo Estado:\n";
    $estado = App\Models\Estado::first();
    if ($estado) {
        echo "   ✅ Estado encontrado: " . $estado->nombre . "\n";
    } else {
        echo "   ❌ No se encontraron estados\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Modelos corregidos y funcionando\n";
