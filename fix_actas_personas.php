<?php
require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Acta;
use App\Models\Persona;

echo "🔧 REPARANDO ACTAS SIN PERSONAS\n";
echo "===============================\n\n";

// Obtener algunas personas para asignar
$personas = Persona::all();
$hombres = $personas->where('sexo', '1');
$mujeres = $personas->where('sexo', '0');

echo "👥 Personas disponibles:\n";
echo "   • Hombres: " . $hombres->count() . "\n";
echo "   • Mujeres: " . $mujeres->count() . "\n\n";

// Acta de Bautismo (ID: 6)
echo "⛪ 1. REPARANDO ACTA DE BAUTISMO #6:\n";
$actaBautismo = Acta::find(6);
if ($actaBautismo) {
    // Asignar persona principal (puede ser cualquiera)
    $personaPrincipal = $personas->random();
    $actaBautismo->cve_persona = $personaPrincipal->cve_persona;
    
    // Asignar padres (si hay suficientes personas)
    if ($hombres->count() > 0) {
        $padre = $hombres->random();
        $actaBautismo->Per_cve_padre = $padre->cve_persona;
        echo "   ✅ Padre asignado: {$padre->nombre} {$padre->apellido_paterno}\n";
    }
    
    if ($mujeres->count() > 0) {
        $madre = $mujeres->random();
        $actaBautismo->Per_cve_madre = $madre->cve_persona;
        echo "   ✅ Madre asignada: {$madre->nombre} {$madre->apellido_paterno}\n";
    }
    
    // Asignar padrinos (diferentes a los padres)
    $padrinoDisponible = $hombres->where('cve_persona', '!=', $actaBautismo->Per_cve_padre)->first();
    if ($padrinoDisponible) {
        $actaBautismo->Per_cve_padrino = $padrinoDisponible->cve_persona;
        echo "   ✅ Padrino asignado: {$padrinoDisponible->nombre} {$padrinoDisponible->apellido_paterno}\n";
    }
    
    $madrinaDisponible = $mujeres->where('cve_persona', '!=', $actaBautismo->Per_cve_madre)->first();
    if ($madrinaDisponible) {
        $actaBautismo->Per_cve_madrina = $madrinaDisponible->cve_persona;
        echo "   ✅ Madrina asignada: {$madrinaDisponible->nombre} {$madrinaDisponible->apellido_paterno}\n";
    }
    
    $actaBautismo->save();
    echo "   ✅ Persona principal: {$personaPrincipal->nombre} {$personaPrincipal->apellido_paterno}\n";
    echo "   ✅ Acta de bautismo actualizada\n\n";
} else {
    echo "   ❌ No se encontró acta de bautismo #6\n\n";
}

// Acta de Confirmación (ID: 5)
echo "⛪ 2. REPARANDO ACTA DE CONFIRMACIÓN #5:\n";
$actaConfirmacion = Acta::find(5);
if ($actaConfirmacion) {
    // Asignar persona principal diferente al bautismo
    $personasDisponibles = $personas->where('cve_persona', '!=', $actaBautismo->cve_persona ?? null);
    $personaPrincipal = $personasDisponibles->random();
    $actaConfirmacion->cve_persona = $personaPrincipal->cve_persona;
    
    // Asignar padres (diferentes al bautismo)
    $hombresDisponibles = $hombres->where('cve_persona', '!=', $actaBautismo->Per_cve_padre ?? null);
    if ($hombresDisponibles->count() > 0) {
        $padre = $hombresDisponibles->random();
        $actaConfirmacion->Per_cve_padre = $padre->cve_persona;
        echo "   ✅ Padre asignado: {$padre->nombre} {$padre->apellido_paterno}\n";
    }
    
    $mujeresDisponibles = $mujeres->where('cve_persona', '!=', $actaBautismo->Per_cve_madre ?? null);
    if ($mujeresDisponibles->count() > 0) {
        $madre = $mujeresDisponibles->random();
        $actaConfirmacion->Per_cve_madre = $madre->cve_persona;
        echo "   ✅ Madre asignada: {$madre->nombre} {$madre->apellido_paterno}\n";
    }
    
    // Para confirmación, también podemos asignar padrinos de confirmación
    $padrinoConfDisponible = $hombres->whereNotIn('cve_persona', [
        $actaBautismo->Per_cve_padre ?? null, 
        $actaConfirmacion->Per_cve_padre ?? null,
        $actaBautismo->Per_cve_padrino ?? null
    ])->first();
    
    if ($padrinoConfDisponible) {
        $actaConfirmacion->Per_cve_padrino = $padrinoConfDisponible->cve_persona;
        echo "   ✅ Padrino de confirmación: {$padrinoConfDisponible->nombre} {$padrinoConfDisponible->apellido_paterno}\n";
    }
    
    $madrinaConfDisponible = $mujeres->whereNotIn('cve_persona', [
        $actaBautismo->Per_cve_madre ?? null, 
        $actaConfirmacion->Per_cve_madre ?? null,
        $actaBautismo->Per_cve_madrina ?? null
    ])->first();
    
    if ($madrinaConfDisponible) {
        $actaConfirmacion->Per_cve_madrina = $madrinaConfDisponible->cve_persona;
        echo "   ✅ Madrina de confirmación: {$madrinaConfDisponible->nombre} {$madrinaConfDisponible->apellido_paterno}\n";
    }
    
    $actaConfirmacion->save();
    echo "   ✅ Persona principal: {$personaPrincipal->nombre} {$personaPrincipal->apellido_paterno}\n";
    echo "   ✅ Acta de confirmación actualizada\n\n";
} else {
    echo "   ❌ No se encontró acta de confirmación #5\n\n";
}

echo "🎉 REPARACIÓN COMPLETADA\n";
echo "=======================\n";
echo "Ahora ambas actas deberían mostrar personas en los modales de edición.\n";
