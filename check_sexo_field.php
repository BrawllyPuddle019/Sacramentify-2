<?php

use Illuminate\Support\Facades\DB;

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar estructura del campo sexo
echo "Estructura del campo sexo en la tabla personas:\n";
$result = DB::select("DESCRIBE personas");
foreach($result as $column) {
    if($column->Field === 'sexo') {
        echo "Campo: {$column->Field}\n";
        echo "Tipo: {$column->Type}\n";
        echo "Null: {$column->Null}\n";
        echo "Default: {$column->Default}\n";
        break;
    }
}

echo "\n" . str_repeat("-", 50) . "\n";

// Verificar valores actuales en la base de datos
echo "Valores de sexo en la base de datos:\n";
$personas = App\Models\Persona::select('cve_persona', 'nombre', 'sexo')->get();
foreach($personas as $persona) {
    $sexoTipo = gettype($persona->sexo);
    $sexoValor = var_export($persona->sexo, true);
    echo "ID: {$persona->cve_persona} - Nombre: {$persona->nombre} - Sexo: {$sexoValor} (tipo: {$sexoTipo})\n";
}
