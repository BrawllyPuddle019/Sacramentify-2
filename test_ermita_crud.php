<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Ermita;
use App\Models\Parroquia;
use App\Models\Municipio;

try {
    // Verificar que hay datos para las relaciones
    $parroquia = Parroquia::first();
    $municipio = Municipio::first();
    
    if (!$parroquia || !$municipio) {
        echo "❌ No hay parroquias o municipios en la base de datos" . PHP_EOL;
        exit;
    }
    
    // Crear una ermita de prueba
    $maxId = Ermita::max('cve_ermitas') ?? 0;
    $nextId = $maxId + 1;
    
    $ermita = new Ermita;
    $ermita->cve_ermitas = $nextId;
    $ermita->cve_parroquia = $parroquia->cve_parroquia;
    $ermita->cve_municipio = $municipio->cve_municipio;
    $ermita->nombre_ermita = 'Ermita Test';
    $ermita->direccion = 'Dirección Test';
    $ermita->save();
    
    echo "✅ Ermita de prueba creada: " . $ermita->nombre_ermita . PHP_EOL;
    echo "   ID: " . $ermita->cve_ermitas . PHP_EOL;
    echo "   Parroquia: " . ($ermita->parroquia ? $ermita->parroquia->nombre_parroquia : 'null') . PHP_EOL;
    echo "   Municipio: " . ($ermita->municipio ? $ermita->municipio->nombre_municipio : 'null') . PHP_EOL;
    
    // Probar actualización
    $ermita->nombre_ermita = 'Ermita Test Actualizada';
    $ermita->direccion = 'Nueva Dirección';
    $ermita->save();
    
    echo "✅ Ermita actualizada: " . $ermita->nombre_ermita . PHP_EOL;
    
    // Probar eliminación
    $ermita->delete();
    echo "✅ Ermita eliminada exitosamente" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
