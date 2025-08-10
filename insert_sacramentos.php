<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Verificar si ya existen sacramentos
    $existingSacramentos = DB::table('sacramentos')->count();
    
    if ($existingSacramentos > 0) {
        echo "Ya existen sacramentos en la base de datos. Saltando inserción.\n";
        exit;
    }

    // Insertar los 3 sacramentos principales
    $sacramentos = [
        [
            'cve_sacramentos' => 1,
            'nombre_sacramento' => 'Bautizo',
            'descripcion' => 'Sacramento del Bautizo - Iniciación cristiana',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'cve_sacramentos' => 2,
            'nombre_sacramento' => 'Confirmación',
            'descripcion' => 'Sacramento de la Confirmación - Fortalecimiento de la fe',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'cve_sacramentos' => 3,
            'nombre_sacramento' => 'Matrimonio',
            'descripcion' => 'Sacramento del Matrimonio - Unión sagrada',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];

    DB::table('sacramentos')->insert($sacramentos);
    
    echo "Sacramentos insertados exitosamente:\n";
    echo "1. Bautizo\n";
    echo "2. Confirmación\n";
    echo "3. Matrimonio\n";
    
} catch (Exception $e) {
    echo "Error al insertar sacramentos: " . $e->getMessage() . "\n";
}
