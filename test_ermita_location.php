<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Ermita;

try {
    // Obtener una ermita existente
    $ermita = Ermita::first();
    
    if (!$ermita) {
        echo "❌ No hay ermitas en la base de datos" . PHP_EOL;
        exit;
    }
    
    echo "✅ Ermita encontrada: " . $ermita->nombre_ermita . PHP_EOL;
    echo "📍 Ubicación actual: Lat=" . ($ermita->latitude ?? 'null') . ", Lng=" . ($ermita->longitude ?? 'null') . PHP_EOL;
    
    // Probar actualizar ubicación
    $testLat = 19.432608;
    $testLng = -99.133209; // Ciudad de México como ejemplo
    
    $ermita->update([
        'latitude' => $testLat,
        'longitude' => $testLng
    ]);
    
    echo "✅ Ubicación actualizada a: Lat=$testLat, Lng=$testLng" . PHP_EOL;
    
    // Verificar que se guardó
    $ermita->refresh();
    echo "✅ Verificación: Lat=" . $ermita->latitude . ", Lng=" . $ermita->longitude . PHP_EOL;
    
    // Limpiar la prueba
    $ermita->update([
        'latitude' => null,
        'longitude' => null
    ]);
    
    echo "✅ Test completado - ubicación limpiada" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
