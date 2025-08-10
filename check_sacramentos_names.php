<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICANDO NOMBRES DE SACRAMENTOS ===\n";

$sacramentos = App\Models\Sacramento::all(['cve_sacramentos', 'nombre_sacramento']);

foreach($sacramentos as $sacramento) {
    echo "ID: {$sacramento->cve_sacramentos} - Nombre: '{$sacramento->nombre_sacramento}'\n";
}

echo "\n=== VERIFICANDO ACTA RECIENTE ===\n";
$acta = App\Models\Acta::with('tipoActa')->orderBy('cve_actas', 'desc')->first();
if ($acta && $acta->tipoActa) {
    echo "Tipo de acta: '{$acta->tipoActa->nombre}'\n";
    echo "Nombre trimmed: '" . trim($acta->tipoActa->nombre) . "'\n";
}
