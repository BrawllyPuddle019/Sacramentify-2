<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $packages = DB::table('payment_packages')->get();
    
    echo "Paquetes de créditos en la base de datos:\n";
    echo "=========================================\n";
    
    if ($packages->isEmpty()) {
        echo "No hay paquetes de créditos en la base de datos.\n";
    } else {
        foreach ($packages as $package) {
            echo "ID: {$package->id}\n";
            echo "Nombre: {$package->name}\n";
            echo "Descripción: {$package->description}\n";
            echo "Créditos: {$package->credits_amount}\n";
            echo "Precio: \${$package->price}\n";
            echo "Activo: " . ($package->is_active ? 'Sí' : 'No') . "\n";
            echo "--------------------\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error al consultar paquetes: " . $e->getMessage() . "\n";
}
