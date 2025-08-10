<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);

// Verificar actas disponibles por tipo de sacramento
echo "🔍 VERIFICANDO ACTAS DISPONIBLES PARA PDF\n";
echo "==========================================\n\n";

// Obtener actas por tipo
$actas = App\Models\Acta::with(['tipoActa', 'persona'])->get();

echo "📊 TOTAL DE ACTAS: " . $actas->count() . "\n\n";

// Agrupar por tipo de sacramento
$tipos = $actas->groupBy(function($acta) {
    return $acta->tipoActa ? $acta->tipoActa->nombre : 'Sin tipo';
});

foreach ($tipos as $tipo => $actasPorTipo) {
    echo "📋 TIPO: $tipo\n";
    echo "   Cantidad: " . $actasPorTipo->count() . " actas\n";
    
    // Mostrar algunas actas de ejemplo
    $ejemplos = $actasPorTipo->take(3);
    foreach ($ejemplos as $acta) {
        $persona = $acta->persona ? 
            $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno : 
            'Sin persona';
        echo "   - ID: {$acta->cve_actas} | Persona: $persona\n";
    }
    echo "\n";
}

echo "🎯 URLs DE PRUEBA PARA PDF:\n";
echo "============================\n";

// Mostrar URLs para probar PDFs
foreach ($tipos as $tipo => $actasPorTipo) {
    if ($actasPorTipo->count() > 0) {
        $primeraActa = $actasPorTipo->first();
        $url = "http://localhost/sacramentify/public/actas/{$primeraActa->cve_actas}/pdf";
        echo "📄 $tipo: $url\n";
    }
}

echo "\n✅ VERIFICACIÓN COMPLETADA\n";
echo "Puedes probar los PDFs visitando las URLs mostradas arriba.\n";
?>
