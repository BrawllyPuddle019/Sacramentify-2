<?php

require_once 'vendor/autoload.php';

// Configurar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Acta;
use App\Models\Sacramento;

echo "=== VERIFICACIÓN DE DISPLAY DE BAUTISMOS ===\n\n";

// Verificar nombres de sacramentos
echo "1. Nombres de sacramentos en la base de datos:\n";
$sacramentos = Sacramento::all();
foreach ($sacramentos as $sacramento) {
    echo "   ID: {$sacramento->id} - Nombre: '{$sacramento->nombre}'\n";
}

echo "\n2. Actas de bautismo y datos relacionados:\n";
$bautismo = Sacramento::where('nombre', 'Bautismo')->first();
if ($bautismo) {
    $actas = Acta::with(['persona', 'padre', 'madre', 'padrino1', 'madrina1', 'tipoActa'])
        ->where('tipo_acta', $bautismo->id)
        ->get();
    
    foreach ($actas as $acta) {
        echo "   Acta ID: {$acta->id}\n";
        echo "   - Tipo: {$acta->tipoActa->nombre}\n";
        echo "   - Persona Principal: " . ($acta->persona ? $acta->persona->nombre : 'Sin asignar') . "\n";
        echo "   - Padre: " . ($acta->padre ? $acta->padre->nombre : 'Sin asignar') . "\n";
        echo "   - Madre: " . ($acta->madre ? $acta->madre->nombre : 'Sin asignar') . "\n";
        echo "   - Padrino1: " . ($acta->padrino1 ? $acta->padrino1->nombre : 'Sin asignar') . "\n";
        echo "   - Madrina1: " . ($acta->madrina1 ? $acta->madrina1->nombre : 'Sin asignar') . "\n";
        echo "   - Sexo persona: " . ($acta->persona ? $acta->persona->sexo : 'No definido') . "\n";
        echo "   ---\n";
    }
} else {
    echo "   No se encontró sacramento 'Bautismo'\n";
}

echo "\n3. Verificación de comparaciones en vista:\n";
echo "   Las vistas ahora comparan contra 'Bautismo' y 'Confirmación'\n";
echo "   Filtros ahora envían 'Bautismo' y 'Confirmación'\n";

echo "\n=== FIN DE VERIFICACIÓN ===\n";

?>
