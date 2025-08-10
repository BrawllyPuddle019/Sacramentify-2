<?php
// Script para actualizar las últimas tablas de Sacramentify con diseño responsivo

// Lista de archivos para actualizar
$tables = [
    'ermitas/index.blade.php',
    'platicas/index.blade.php', 
    'users/index.blade.php',
    'matrimonios/index.blade.php'
];

$basePath = 'c:\\xampp\\htdocs\\sacramentify\\resources\\views\\';

foreach ($tables as $table) {
    $filePath = $basePath . $table;
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Agregar el enlace al CSS al inicio del content
        if (strpos($content, 'responsive-tables.css') === false) {
            $content = str_replace(
                '@section(\'content\')',
                '@section(\'content\')

<link rel="stylesheet" href="{{ asset(\'assets/css/responsive-tables.css\') }}">',
                $content
            );
        }
        
        // Actualizar clases de card
        $content = str_replace(
            '<div class="card">',
            '<div class="card animate__animated animate__fadeIn">',
            $content
        );
        
        // Actualizar clases de table-responsive 
        $content = str_replace(
            '<div class="table-responsive">',
            '<div class="table-responsive table-container animate__animated animate__fadeIn">',
            $content
        );
        
        $content = str_replace(
            '<div class="table-responsive animate__animated animate__fadeIn">',
            '<div class="table-responsive table-container animate__animated animate__fadeIn">',
            $content
        );
        
        // Actualizar clases de table
        $content = str_replace(
            'table table-bordered table-fixed text-center',
            'table table-bordered table-hover align-middle table-compact',
            $content
        );
        
        $content = str_replace(
            'table table-bordered table-sm table-fixed text-center',
            'table table-bordered table-hover align-middle table-compact',
            $content
        );
        
        // Actualizar headers de acciones
        $content = str_replace(
            '<th>ACCIONES</th>',
            '<th class="actions-column header">ACCIONES</th>',
            $content
        );
        
        // Actualizar filas de datos
        $content = str_replace(
            '<tr class="">',
            '<tr>',
            $content
        );
        
        $content = str_replace(
            '<td scope="row">',
            '<td data-label="ID">',
            $content
        );
        
        // Simplificar botones - Esta será una transformación básica
        // Para una transformación completa necesitaríamos regex más complejas
        
        file_put_contents($filePath, $content);
        echo "Actualizado: $table\n";
    } else {
        echo "No encontrado: $table\n";
    }
}

echo "Proceso completado!\n";
?>
