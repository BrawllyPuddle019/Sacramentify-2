<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Obtener todas las actas ordenadas por ID
$actas = App\Models\Acta::orderBy('cve_actas')->get();
$numero = 1;

foreach($actas as $acta) {
    $acta->numero_consecutivo = $numero++;
    $acta->save();
}

echo "Números consecutivos asignados correctamente: " . ($numero - 1) . " actas actualizadas.\n";
