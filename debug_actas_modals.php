<?php
require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Acta;
use App\Models\Sacramento;
use App\Models\Persona;

echo "🔍 DIAGNÓSTICO DE ACTAS Y MODALES\n";
echo "================================\n\n";

// 1. VERIFICAR DATOS BÁSICOS
echo "📊 1. CONTEO DE REGISTROS:\n";
echo "   • Actas: " . Acta::count() . "\n";
echo "   • Sacramentos: " . Sacramento::count() . "\n";
echo "   • Personas: " . Persona::count() . "\n\n";

// 2. VERIFICAR SACRAMENTOS DISPONIBLES
echo "🎯 2. SACRAMENTOS DISPONIBLES:\n";
$sacramentos = Sacramento::all();
echo "   📋 Estructura del primer sacramento:\n";
if ($sacramentos->count() > 0) {
    $primerSacramento = $sacramentos->first();
    echo "   Columnas disponibles: " . implode(', ', array_keys($primerSacramento->toArray())) . "\n";
}

foreach ($sacramentos as $sacramento) {
    // Usar el accessor que ya existe en el modelo
    $nombreCampo = $sacramento->nombre; // El accessor convierte nombre_sacramento a nombre
    
    $tipoMapeado = match(strtolower($nombreCampo)) {
        'matrimonio' => 'matrimonio',
        'bautismo' => 'bautizo',
        'confirmación' => 'confirmacion',
        default => strtolower($nombreCampo)
    };
    echo "   • ID: {$sacramento->cve_sacramentos} | Nombre: '{$nombreCampo}' | Mapeado: '{$tipoMapeado}'\n";
}
echo "\n";

// 3. VERIFICAR ACTAS POR TIPO
echo "📋 3. ACTAS POR TIPO DE SACRAMENTO:\n";
$actasPorTipo = Acta::selectRaw('tipo_acta, COUNT(*) as total')
    ->groupBy('tipo_acta')
    ->get();

foreach ($actasPorTipo as $grupo) {
    $sacramento = Sacramento::find($grupo->tipo_acta);
    $nombreSacramento = $sacramento ? $sacramento->nombre : 'Desconocido';
    echo "   • Tipo {$grupo->tipo_acta} ({$nombreSacramento}): {$grupo->total} actas\n";
}
echo "\n";

// 4. VERIFICAR ACTAS ESPECÍFICAS
echo "🔎 4. DETALLES DE ACTAS (Primeras 5):\n";
$actas = Acta::with(['sacramento'])->take(5)->get();

foreach ($actas as $acta) {
    echo "   ┌─ ACTA #{$acta->cve_actas}\n";
    $sacramentoNombre = $acta->sacramento ? $acta->sacramento->nombre : 'N/A';
    echo "   │  Sacramento: {$sacramentoNombre} (ID: {$acta->tipo_acta})\n";
    echo "   │  Fecha: {$acta->fecha}\n";
    echo "   │  Libro: {$acta->Libro}, Fojas: {$acta->Fojas}, Folio: {$acta->Folio}\n";
    
    // Verificar personas relacionadas
    $personasRelacionadas = [];
    if ($acta->cve_persona) $personasRelacionadas[] = "Persona principal: {$acta->cve_persona}";
    if ($acta->cve_persona2) $personasRelacionadas[] = "Persona 2: {$acta->cve_persona2}";
    if ($acta->Per_cve_padre) $personasRelacionadas[] = "Padre: {$acta->Per_cve_padre}";
    if ($acta->Per_cve_madre) $personasRelacionadas[] = "Madre: {$acta->Per_cve_madre}";
    if ($acta->Per_cve_padrino) $personasRelacionadas[] = "Padrino: {$acta->Per_cve_padrino}";
    if ($acta->Per_cve_madrina) $personasRelacionadas[] = "Madrina: {$acta->Per_cve_madrina}";
    
    if (!empty($personasRelacionadas)) {
        echo "   │  Personas: " . implode(", ", $personasRelacionadas) . "\n";
    } else {
        echo "   │  ⚠️  Sin personas relacionadas\n";
    }
    echo "   └─\n";
}
echo "\n";

// 5. VERIFICAR PERSONAS POR GÉNERO
echo "👥 5. DISTRIBUCIÓN DE PERSONAS POR GÉNERO:\n";
$personasPorGenero = Persona::selectRaw('sexo, COUNT(*) as total')
    ->groupBy('sexo')
    ->get();

foreach ($personasPorGenero as $grupo) {
    $genero = $grupo->sexo === '1' ? 'Masculino' : ($grupo->sexo === '0' ? 'Femenino' : 'Indefinido');
    echo "   • {$genero} ('{$grupo->sexo}'): {$grupo->total} personas\n";
}
echo "\n";

// 6. VERIFICAR ESTRUCTURA HTML ESPERADA
echo "🏗️  6. VERIFICACIÓN DE ESTRUCTURA HTML:\n";
echo "   Los IDs que se deberían generar para el acta #1:\n";
echo "   • Modal: editActa1\n";
echo "   • Formulario: editActaForm1\n";
echo "   • Select tipo: tipo_acta_1\n";
echo "   • Secciones:\n";
echo "     - camposMatrimonio1\n";
echo "     - camposBautizo1\n";
echo "     - camposConfirmacion1\n";
echo "   • Campos de personas:\n";
echo "     - cve_persona_matrimonio_1 (esposo)\n";
echo "     - cve_persona2_1 (esposa)\n";
echo "     - cve_persona_bautizo_1 (persona a bautizar)\n";
echo "     - cve_persona_confirmacion_1 (persona a confirmar)\n";
echo "\n";

// 7. VERIFICAR DATOS DE PRUEBA PARA BAUTIZO/CONFIRMACIÓN
echo "🧪 7. DATOS DE PRUEBA - BAUTIZOS Y CONFIRMACIONES:\n";

// Buscar todos los sacramentos para identificar los campos correctos
$todosSacramentos = Sacramento::all();
$bautismos = null;
$confirmaciones = null;

foreach ($todosSacramentos as $sacramento) {
    $nombreCampo = $sacramento->nombre; // Usar el accessor
    
    if (stripos($nombreCampo, 'bautis') !== false) {
        $bautismos = $sacramento;
    }
    if (stripos($nombreCampo, 'confirm') !== false) {
        $confirmaciones = $sacramento;
    }
}

if ($bautismos) {
    $actasBautizo = Acta::where('tipo_acta', $bautismos->cve_sacramentos)->count();
    echo "   • Bautismos encontrados: {$actasBautizo} actas\n";
} else {
    echo "   • ⚠️  No se encontró sacramento de bautismo\n";
}

if ($confirmaciones) {
    $actasConfirmacion = Acta::where('tipo_acta', $confirmaciones->cve_sacramentos)->count();
    echo "   • Confirmaciones encontradas: {$actasConfirmacion} actas\n";
} else {
    echo "   • ⚠️  No se encontró sacramento de confirmación\n";
}
echo "\n";

// 8. SIMULACIÓN DE DATOS PARA MODAL
echo "🎭 8. SIMULACIÓN DE DATOS PARA MODAL (Primera acta de bautizo):\n";
if ($bautismos) {
    $actaBautizo = Acta::where('tipo_acta', $bautismos->cve_sacramentos)->first();
    if ($actaBautizo) {
        echo "   ✅ Acta de bautizo encontrada: #{$actaBautizo->cve_actas}\n";
        echo "   📝 Datos que debería cargar el modal:\n";
        echo "     - Tipo seleccionado: {$actaBautizo->tipo_acta}\n";
        echo "     - Data-tipo esperado: 'bautizo'\n";
        echo "     - Sección a mostrar: camposBautizo{$actaBautizo->cve_actas}\n";
        
        // Verificar personas relacionadas específicamente
        $persona = Persona::find($actaBautizo->cve_persona);
        if ($persona) {
            echo "     - Persona principal: {$persona->nombre} {$persona->apellido_paterno}\n";
        }
        
        if ($actaBautizo->Per_cve_padre) {
            $padre = Persona::find($actaBautizo->Per_cve_padre);
            echo "     - Padre: " . ($padre ? "{$padre->nombre} {$padre->apellido_paterno}" : "ID {$actaBautizo->Per_cve_padre}") . "\n";
        }
        
        if ($actaBautizo->Per_cve_madre) {
            $madre = Persona::find($actaBautizo->Per_cve_madre);
            echo "     - Madre: " . ($madre ? "{$madre->nombre} {$madre->apellido_paterno}" : "ID {$actaBautizo->Per_cve_madre}") . "\n";
        }
    } else {
        echo "   ❌ No se encontraron actas de bautizo\n";
    }
} else {
    echo "   ❌ No se puede simular: sacramento de bautismo no encontrado\n";
}

echo "\n🎯 DIAGNÓSTICO COMPLETADO\n";
echo "========================\n";
echo "Usa esta información para verificar:\n";
echo "1. Que los IDs generados en HTML coincidan\n";
echo "2. Que el data-tipo esté correctamente mapeado\n";
echo "3. Que las actas tengan personas relacionadas\n";
echo "4. Que el JavaScript encuentre los elementos correctos\n";
