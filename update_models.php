<?php

// Script para actualizar modelos con incrementing = false
// Solo para los modelos principales que se usan en el CRUD

$modelsToUpdate = [
    'Diocesi' => ['primaryKey' => 'cve_diocesis', 'fillable' => ['cve_diocesis', 'nombre_diocesis', 'cve_obispos']],
    'Parroquia' => ['primaryKey' => 'cve_parroquia', 'fillable' => ['cve_parroquia', 'nombre_parroquia', 'cve_diocesis']],
    'Sacerdote' => ['primaryKey' => 'cve_sacerdotes', 'fillable' => ['cve_sacerdotes', 'nombre_sacerdote', 'apellido_paterno', 'apellido_materno', 'cve_parroquia']],
    'Obispo' => ['primaryKey' => 'cve_obispos', 'fillable' => ['cve_obispos', 'nombre_obispo', 'apellido_paterno', 'apellido_materno']],
];

foreach ($modelsToUpdate as $modelName => $config) {
    $filePath = "app/Models/{$modelName}.php";
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Verificar si ya tiene incrementing = false
        if (strpos($content, 'public $incrementing = false;') === false) {
            // Buscar la línea de timestamps
            $content = str_replace(
                'public $timestamps = false;',
                "public \$timestamps = false;\n    public \$incrementing = false;",
                $content
            );
            
            // Actualizar fillable si es necesario
            $primaryKey = $config['primaryKey'];
            if (strpos($content, "'{$primaryKey}'") === false) {
                $fillableArray = "'" . implode("', '", $config['fillable']) . "'";
                $content = preg_replace(
                    '/protected \$fillable = \[(.*?)\];/s',
                    "protected \$fillable = [{$fillableArray}];",
                    $content
                );
            }
            
            file_put_contents($filePath, $content);
            echo "Actualizado: {$modelName}\n";
        } else {
            echo "Ya actualizado: {$modelName}\n";
        }
    } else {
        echo "No encontrado: {$filePath}\n";
    }
}

echo "Proceso completado.\n";
