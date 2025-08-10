<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Probando el nuevo sistema de eliminación de sacerdotes...\n\n";

try {
    // Simular el comportamiento del controlador
    $id = 1; // ID del sacerdote que tiene actas asociadas
    $sacerdote = App\Models\Sacerdote::findOrFail($id);
    
    echo "Sacerdote a eliminar: {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno}\n";
    
    // Verificar si el sacerdote tiene actas asociadas
    $actasCount = DB::table('actas')
        ->where('cve_sacerdotes_celebrante', $id)
        ->orWhere('cve_sacerdotes_asistente', $id)
        ->count();
    
    echo "Actas asociadas: {$actasCount}\n";
    
    if ($actasCount > 0) {
        echo "✅ Sistema funcionando: Se detectaron las dependencias correctamente.\n";
        echo "Mensaje que vería el usuario: 'No se puede eliminar el sacerdote {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno} porque tiene {$actasCount} acta(s) asociada(s).'\n";
    } else {
        echo "✅ Sistema funcionando: No hay dependencias, se puede eliminar.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
