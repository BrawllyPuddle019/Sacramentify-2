<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Verificar si ya existen paquetes
    $existingPackages = DB::table('payment_packages')->count();
    
    if ($existingPackages > 0) {
        echo "Ya existen paquetes de créditos en la base de datos. Saltando inserción.\n";
        exit;
    }

    // Insertar paquetes de créditos
    $packages = [
        [
            'name' => 'Paquete Básico',
            'description' => 'Ideal para uso ocasional - 10 actas',
            'credits_amount' => 10,
            'price' => 5.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 1,
            'features' => json_encode(['10 actas de sacramentos', 'Soporte por email']),
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Paquete Estándar',
            'description' => 'Perfecto para parroquias pequeñas - 25 actas',
            'credits_amount' => 25,
            'price' => 10.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 2,
            'features' => json_encode(['25 actas de sacramentos', 'Soporte prioritario', 'Descuento del 20%']),
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Paquete Premium',
            'description' => 'Para parroquias medianas - 50 actas',
            'credits_amount' => 50,
            'price' => 18.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 3,
            'features' => json_encode(['50 actas de sacramentos', 'Soporte prioritario', 'Descuento del 28%', 'Backup automático']),
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Paquete Profesional',
            'description' => 'Para parroquias grandes - 100 actas',
            'credits_amount' => 100,
            'price' => 30.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 4,
            'features' => json_encode(['100 actas de sacramentos', 'Soporte prioritario 24/7', 'Descuento del 40%', 'Backup automático', 'Reportes avanzados']),
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];

    DB::table('payment_packages')->insert($packages);
    
    echo "Paquetes de créditos insertados exitosamente:\n";
    echo "1. Paquete Básico - 10 créditos - \$5.00\n";
    echo "2. Paquete Estándar - 25 créditos - \$10.00\n";
    echo "3. Paquete Premium - 50 créditos - \$18.00\n";
    echo "4. Paquete Profesional - 100 créditos - \$30.00\n";
    
} catch (Exception $e) {
    echo "Error al insertar paquetes: " . $e->getMessage() . "\n";
}
