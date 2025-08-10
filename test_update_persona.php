<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Probar actualizar una persona
try {
    // Buscar una persona existente
    $persona = App\Models\Persona::first();
    
    if (!$persona) {
        echo "No hay personas en la base de datos para probar.\n";
        exit;
    }
    
    echo "Persona encontrada: {$persona->nombre} {$persona->apellido_paterno} {$persona->apellido_materno}\n";
    echo "ID: {$persona->cve_persona}\n";
    echo "Municipio actual: {$persona->cve_municipio}\n";
    
    // Simular actualización
    $persona->apellido_paterno = 'Apellido_Actualizado';
    $persona->apellido_materno = 'Materno_Actualizado';
    $persona->save();
    
    echo "Persona actualizada exitosamente:\n";
    echo "Nuevo nombre: {$persona->nombre} {$persona->apellido_paterno} {$persona->apellido_materno}\n";
    
} catch (Exception $e) {
    echo "Error al actualizar persona: " . $e->getMessage() . "\n";
}
