<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $sacramentos = DB::table('sacramentos')->get();
    
    echo "Sacramentos en la base de datos:\n";
    echo "================================\n";
    
    if ($sacramentos->isEmpty()) {
        echo "No hay sacramentos en la base de datos.\n";
    } else {
        foreach ($sacramentos as $sacramento) {
            echo "ID: {$sacramento->cve_sacramentos}\n";
            echo "Nombre: {$sacramento->nombre_sacramento}\n";
            echo "Descripción: {$sacramento->descripcion}\n";
            echo "--------------------\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error al consultar sacramentos: " . $e->getMessage() . "\n";
}
