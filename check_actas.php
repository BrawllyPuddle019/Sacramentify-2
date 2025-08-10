<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CONSULTANDO ACTAS RECIENTES ===\n";

$actas = App\Models\Acta::orderBy('cve_actas', 'desc')
    ->limit(3)
    ->get([
        'cve_actas', 
        'cve_persona', 
        'Per_cve_padre', 
        'Per_cve_madre', 
        'Per_cve_padrino1', 
        'Per_cve_madrina1',
        'Per_cve_padre1', 
        'Per_cve_madre1', 
        'Per_cve_padrino', 
        'Per_cve_madrina'
    ]);

foreach($actas as $acta) {
    echo "ID: {$acta->cve_actas}\n";
    echo "  Persona Principal: {$acta->cve_persona}\n";
    echo "  Campos HOMBRE (Per_cve_*):\n";
    echo "    Padre: {$acta->Per_cve_padre}\n";
    echo "    Madre: {$acta->Per_cve_madre}\n";
    echo "    Padrino1: {$acta->Per_cve_padrino1}\n";
    echo "    Madrina1: {$acta->Per_cve_madrina1}\n";
    echo "  Campos MUJER (Per_cve_*1 y Per_cve_padrino/madrina):\n";
    echo "    Padre1: {$acta->Per_cve_padre1}\n";
    echo "    Madre1: {$acta->Per_cve_madre1}\n";
    echo "    Padrino: {$acta->Per_cve_padrino}\n";
    echo "    Madrina: {$acta->Per_cve_madrina}\n";
    echo "  ---\n";
}
