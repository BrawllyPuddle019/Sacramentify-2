<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Verificando relaciones entre Sacerdotes y Diócesis...\n\n";

try {
    // Obtener sacerdotes con sus diócesis
    $sacerdotes = App\Models\Sacerdote::with('diocesi')->get();
    
    echo "=== SACERDOTES Y SUS DIÓCESIS ===\n";
    foreach($sacerdotes as $sacerdote) {
        $diocesisNombre = $sacerdote->diocesi ? $sacerdote->diocesi->nombre_diocesis : 'Sin diócesis';
        echo "• {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno} - Diócesis: {$diocesisNombre}\n";
    }
    
    echo "\n=== DIÓCESIS Y SUS SACERDOTES ===\n";
    $diocesis = App\Models\Diocesi::with('sacerdotes')->get();
    
    foreach($diocesis as $diocesi) {
        echo "• Diócesis: {$diocesi->nombre_diocesis}\n";
        if($diocesi->sacerdotes->count() > 0) {
            foreach($diocesi->sacerdotes as $sacerdote) {
                echo "  - {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno}\n";
            }
        } else {
            echo "  - (Sin sacerdotes asignados)\n";
        }
        echo "\n";
    }
    
    echo "✅ Relaciones funcionando correctamente!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
