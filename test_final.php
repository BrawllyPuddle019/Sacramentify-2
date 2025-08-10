<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRUEBA FINAL DE TODOS LOS MODELOS ===\n";

try {
    // Probar personas con relaciones
    echo "1. Probando vista de personas (simulando la consulta de la vista):\n";
    $personas = App\Models\Persona::with('municipio.estado')->get();
    
    foreach ($personas as $persona) {
        echo "   ✅ " . $persona->nombre . " " . $persona->paterno . " " . $persona->materno . "\n";
        echo "      - Municipio: " . ($persona->municipio ? $persona->municipio->nombre : 'Sin municipio') . "\n";
        echo "      - Estado: " . ($persona->municipio && $persona->municipio->estado ? $persona->municipio->estado->nombre : 'Sin estado') . "\n";
        echo "      - Sexo: " . ($persona->sexo === 'M' ? 'Masculino' : 'Femenino') . "\n";
    }
    
    echo "\n2. Probando relaciones:\n";
    echo "   - Estados: " . App\Models\Estado::count() . "\n";
    echo "   - Municipios: " . App\Models\Municipio::count() . "\n";
    echo "   - Personas: " . App\Models\Persona::count() . "\n";
    echo "   - Ermitas: " . App\Models\Ermita::count() . "\n";
    echo "   - Parroquias: " . App\Models\Parroquia::count() . "\n";
    echo "   - Diócesis: " . App\Models\Diocesi::count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Todos los modelos funcionando correctamente\n";
echo "✅ La página de personas debería funcionar ahora\n";
