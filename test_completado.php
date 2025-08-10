<?php

require 'vendor/autoload.php';

// Bootstrapear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\Evento;
use App\Models\Platica;

// Verificar el evento actual
$evento = Evento::find(6);
echo "Evento actual:\n";
echo "ID: " . $evento->id . "\n";
echo "Título: " . $evento->titulo . "\n";
echo "Estado: " . $evento->estado . "\n";
echo "Padre ID: " . $evento->padre_id . "\n";
echo "Madre ID: " . $evento->madre_id . "\n";
echo "Plática ID: " . $evento->platica_id . "\n\n";

// Simular el proceso de completado
if ($evento->padre_id && $evento->madre_id) {
    echo "✅ El evento tiene padre y madre asignados\n";
    
    if ($evento->estado !== 'completado') {
        echo "🔄 Marcando evento como completado...\n";
        
        // Simular la creación de plática
        $platica = Platica::create([
            'padre' => $evento->padre_id,
            'madre' => $evento->madre_id,
            'nombre' => $evento->titulo,
            'fecha' => $evento->fecha_inicio->format('Y-m-d')
        ]);
        
        echo "✅ Plática creada con ID: " . $platica->cve_platicas . "\n";
        
        // Actualizar evento
        $evento->update([
            'estado' => 'completado',
            'platica_id' => $platica->cve_platicas
        ]);
        
        echo "✅ Evento actualizado con platica_id: " . $platica->cve_platicas . "\n";
    } else {
        echo "ℹ️ El evento ya está completado\n";
    }
} else {
    echo "❌ El evento no tiene padre y madre asignados\n";
}

echo "\nTotal de pláticas: " . Platica::count() . "\n";
