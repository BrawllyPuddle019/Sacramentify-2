<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Parroquia;

try {
    // Obtener una parroquia existente
    $parroquia = Parroquia::first();
    
    if (!$parroquia) {
        echo "❌ No hay parroquias en la base de datos" . PHP_EOL;
        exit;
    }
    
    echo "✅ Parroquia encontrada: " . $parroquia->nombre_parroquia . PHP_EOL;
    echo "📍 Ubicación actual: Lat=" . ($parroquia->latitude ?? 'null') . ", Lng=" . ($parroquia->longitude ?? 'null') . PHP_EOL;
    
    // Probar actualizar ubicación
    $testLat = 19.432608;
    $testLng = -99.133209; // Ciudad de México como ejemplo
    
    $parroquia->update([
        'latitude' => $testLat,
        'longitude' => $testLng
    ]);
    
    echo "✅ Ubicación actualizada a: Lat=$testLat, Lng=$testLng" . PHP_EOL;
    
    // Verificar que se guardó
    $parroquia->refresh();
    echo "✅ Verificación: Lat=" . $parroquia->latitude . ", Lng=" . $parroquia->longitude . PHP_EOL;
    
    // Limpiar la prueba
    $parroquia->update([
        'latitude' => null,
        'longitude' => null
    ]);
    
    echo "✅ Test completado - ubicación limpiada" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
