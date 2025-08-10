<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar que tenemos municipios disponibles
echo "Municipios disponibles:\n";
$municipios = App\Models\Municipio::all();
foreach($municipios as $municipio) {
    echo "ID: {$municipio->cve_municipio} - Nombre: {$municipio->nombre_municipio}\n";
}

echo "\n" . str_repeat("-", 50) . "\n";

// Probar crear una persona
try {
    // Obtener el siguiente ID disponible
    $maxId = App\Models\Persona::max('cve_persona') ?? 0;
    $nextId = $maxId + 1;
    
    $persona = new App\Models\Persona();
    $persona->cve_persona = $nextId;
    $persona->nombre = 'Juan';
    $persona->apellido_paterno = 'Pérez';
    $persona->apellido_materno = 'García';
    $persona->fecha_nacimiento = '1990-01-01';
    $persona->direccion = 'Calle Principal 123';
    $persona->cve_municipio = $municipios->first()->cve_municipio; // Usar el primer municipio disponible
    $persona->sexo = 1; // Masculino
    $persona->telefono = '9991234567';
    $persona->save();
    
    echo "Persona creada exitosamente:\n";
    echo "ID: {$persona->cve_persona}\n";
    echo "Nombre: {$persona->nombre} {$persona->apellido_paterno} {$persona->apellido_materno}\n";
    echo "Municipio ID: {$persona->cve_municipio}\n";
    
} catch (Exception $e) {
    echo "Error al crear persona: " . $e->getMessage() . "\n";
}
