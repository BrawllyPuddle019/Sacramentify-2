<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Verificando dependencias del sacerdote con ID 1...\n";

try {
    // Buscar el sacerdote
    $sacerdote = App\Models\Sacerdote::find(1);
    if (!$sacerdote) {
        echo "No se encontró sacerdote con ID 1\n";
        exit;
    }
    
    echo "Sacerdote encontrado: {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno}\n";
    
    // Verificar actas que dependen de este sacerdote
    $actas = DB::select("SELECT * FROM actas WHERE cve_sacerdotes_celebrante = ?", [1]);
    
    echo "\nActas que dependen de este sacerdote:\n";
    foreach($actas as $acta) {
        echo "- Acta ID: {$acta->cve_actas}\n";
        echo "  Detalles: " . json_encode($acta) . "\n";
    }
    
    if (count($actas) > 0) {
        echo "\n❌ No se puede eliminar este sacerdote porque tiene " . count($actas) . " actas asociadas.\n";
    } else {
        echo "\n✅ Este sacerdote se puede eliminar sin problemas.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
