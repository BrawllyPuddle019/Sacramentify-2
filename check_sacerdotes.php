<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Sacerdote;

echo "=== VERIFICACIÓN DE SACERDOTES ===\n";
echo "Total de sacerdotes: " . Sacerdote::count() . "\n\n";

$sacerdotes = Sacerdote::select('cve_sacerdotes', 'nombre', 'paterno', 'materno')->get();

if ($sacerdotes->count() > 0) {
    echo "Lista de sacerdotes:\n";
    foreach ($sacerdotes as $sacerdote) {
        echo "- ID: {$sacerdote->cve_sacerdotes} | Nombre: {$sacerdote->nombre} {$sacerdote->paterno} {$sacerdote->materno}\n";
    }
} else {
    echo "❌ No hay sacerdotes registrados en la base de datos.\n";
    echo "📝 Necesitas crear al menos un sacerdote para que aparezca en el calendario.\n";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
