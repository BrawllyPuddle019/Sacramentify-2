<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Platica;
use App\Models\Persona;

try {
    // Verificar que hay personas para usar como padre y madre
    $personas = Persona::take(2)->get();
    
    if ($personas->count() < 2) {
        echo "❌ Se necesitan al menos 2 personas en la base de datos" . PHP_EOL;
        exit;
    }
    
    $padre = $personas[0];
    $madre = $personas[1];
    
    echo "✅ Usando padre: " . $padre->nombre . " (ID: " . $padre->cve_persona . ")" . PHP_EOL;
    echo "✅ Usando madre: " . $madre->nombre . " (ID: " . $madre->cve_persona . ")" . PHP_EOL;
    
    // Crear una plática de prueba
    $platica = new Platica;
    $platica->padre = $padre->cve_persona;
    $platica->madre = $madre->cve_persona;
    $platica->nombre = 'Plática de Prueba';
    $platica->fecha = date('Y-m-d');
    $platica->save();
    
    echo "✅ Plática creada exitosamente: " . $platica->nombre . PHP_EOL;
    echo "   ID: " . $platica->id . PHP_EOL;
    echo "   Fecha: " . $platica->fecha . PHP_EOL;
    
    // Verificar relaciones
    $platica->load('personaPadre', 'personaMadre');
    echo "   Padre: " . ($platica->personaPadre ? $platica->personaPadre->nombre : 'null') . PHP_EOL;
    echo "   Madre: " . ($platica->personaMadre ? $platica->personaMadre->nombre : 'null') . PHP_EOL;
    
    // Limpiar la prueba
    $platica->delete();
    echo "✅ Plática de prueba eliminada" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
