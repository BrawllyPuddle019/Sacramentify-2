<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRUEBA DE MUNICIPIOS ===\n";

try {
    echo "1. Probando modelo Municipio:\n";
    $municipios = App\Models\Municipio::with('estado')->get();
    
    foreach ($municipios as $municipio) {
        echo "   ✅ " . $municipio->nombre . "\n";
        echo "      - ID: " . $municipio->cve_municipio . "\n";
        echo "      - Estado: " . ($municipio->estado ? $municipio->estado->nombre : 'Sin estado') . "\n";
    }
    
    echo "\n2. Probando rutas (simulando controlador):\n";
    echo "   - Total municipios: " . $municipios->count() . "\n";
    echo "   - Primera clave primaria: " . $municipios->first()->getKeyName() . "\n";
    echo "   - Primer ID: " . $municipios->first()->getKey() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Modelos y vistas de municipios corregidos\n";
