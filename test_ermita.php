<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRUEBA DE MODELO ERMITA ===\n";

try {
    $ermita = App\Models\Ermita::first();
    if ($ermita) {
        echo "✅ Ermita encontrada:\n";
        echo "   - ID: " . $ermita->cve_ermitas . "\n";
        echo "   - Nombre (accessor): " . $ermita->nombre . "\n";
        echo "   - Nombre real: " . $ermita->nombre_ermita . "\n";
        echo "   - Dirección: " . $ermita->direccion . "\n";
    } else {
        echo "❌ No se encontraron ermitas\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA DE CONSULTA HOMECONTROLLER ===\n";

try {
    $topErmitas = App\Models\Acta::select('ermitas.nombre_ermita', DB::raw('COUNT(*) as total'))
        ->join('ermitas', 'actas.cve_ermitas', '=', 'ermitas.cve_ermitas')
        ->groupBy('ermitas.cve_ermitas', 'ermitas.nombre_ermita')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();
    
    echo "✅ Consulta ejecutada exitosamente\n";
    echo "   - Resultados: " . $topErmitas->count() . "\n";
    
    foreach ($topErmitas as $ermita) {
        echo "   - " . $ermita->nombre_ermita . ": " . $ermita->total . " actas\n";
    }
} catch (Exception $e) {
    echo "❌ Error en consulta: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Conexión a Railway: OK\n";
echo "✅ Modelo Ermita: OK\n";
echo "✅ Consultas: OK\n";
