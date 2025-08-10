<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Sacerdote;
use App\Models\Diocesi;

echo "=== CREANDO SACERDOTE DE PRUEBA ===\n";

try {
    // Verificar si ya existe una diócesis
    $diocesis = Diocesi::first();
    if (!$diocesis) {
        echo "❌ No hay diócesis registradas. Creando una diócesis de prueba...\n";
        $diocesis = Diocesi::create([
            'cve_diocesis' => 'D001',
            'nombre_diocesis' => 'Diócesis de Puebla',
            'direccion' => 'Centro, Puebla',
            'telefono' => '2222222222'
        ]);
        echo "✅ Diócesis creada: {$diocesis->nombre_diocesis}\n";
    }

    // Verificar si ya hay sacerdotes
    $sacerdoteExiste = Sacerdote::where('cve_sacerdotes', 'SAC001')->first();
    if ($sacerdoteExiste) {
        echo "✅ El sacerdote de prueba ya existe: {$sacerdoteExiste->nombre_sacerdote}\n";
    } else {
        // Crear sacerdote de prueba
        $sacerdote = Sacerdote::create([
            'cve_sacerdotes' => 'SAC001',
            'nombre_sacerdote' => 'José María',
            'apellido_paterno' => 'González',
            'apellido_materno' => 'López',
            'cve_diocesis' => $diocesis->cve_diocesis
        ]);
        echo "✅ Sacerdote creado: {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno} {$sacerdote->apellido_materno}\n";
    }

    // Mostrar todos los sacerdotes disponibles
    $todosSacerdotes = Sacerdote::all();
    echo "\n📋 Sacerdotes disponibles en el sistema:\n";
    foreach ($todosSacerdotes as $sac) {
        echo "- {$sac->cve_sacerdotes}: {$sac->nombre_sacerdote} {$sac->apellido_paterno} {$sac->apellido_materno}\n";
    }

    echo "\n✅ ¡Ahora deberían aparecer los sacerdotes en el calendario!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== FIN ===\n";
