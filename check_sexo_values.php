<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Persona;

echo "=== VERIFICACIÓN DE VALORES DE SEXO ===\n\n";

$personas = Persona::select('sexo')->distinct()->get();
foreach($personas as $p) {
    echo "Sexo: '{$p->sexo}'\n";
}

echo "\n=== EJEMPLOS DE PERSONAS ===\n";
$ejemplos = Persona::take(5)->get();
foreach($ejemplos as $persona) {
    echo "ID: {$persona->cve_persona} - Nombre: {$persona->nombre} - Sexo: '{$persona->sexo}'\n";
}

?>
