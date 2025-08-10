<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Obispo;

try {
    $maxId = Obispo::max('cve_obispos') ?? 0;
    $nextId = $maxId + 1;
    echo "Next ID: " . $nextId . PHP_EOL;

    $obispo = new Obispo;
    $obispo->cve_obispos = $nextId;
    $obispo->nombre_obispo = 'Test Obispo';
    $obispo->apellido_paterno = 'Test';
    $obispo->apellido_materno = 'Paterno';
    $obispo->cve_diocesis = 1;
    $obispo->save();

    echo "✅ Obispo creado exitosamente: " . $obispo->nombre_obispo . PHP_EOL;
    
    // Cleanup test
    $obispo->delete();
    echo "✅ Test obispo eliminado" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
