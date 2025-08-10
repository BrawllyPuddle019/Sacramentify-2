<?php
// Script temporal para importar el backup a Railway

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configurar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Obtener la conexión a la base de datos
$db = DB::connection();

echo "Conectando a Railway MySQL...\n";
echo "Host: " . config('database.connections.mysql.host') . "\n";
echo "Database: " . config('database.connections.mysql.database') . "\n\n";

// Leer el archivo SQL
$sqlFile = 'backup_sacramentify_local.sql';
if (!file_exists($sqlFile)) {
    die("Error: No se encontró el archivo backup_sacramentify_local.sql\n");
}

echo "Leyendo archivo SQL...\n";
$sqlContent = file_get_contents($sqlFile);

// Limpiar BOM y caracteres especiales
$sqlContent = str_replace("\xEF\xBB\xBF", '', $sqlContent); // Remover BOM UTF-8
$sqlContent = str_replace("\r\n", "\n", $sqlContent); // Normalizar saltos de línea
$sqlContent = str_replace("\r", "\n", $sqlContent);

// Remover comentarios MySQL específicos y SET statements
$lines = explode("\n", $sqlContent);
$cleanLines = [];

foreach ($lines as $line) {
    $line = trim($line);
    
    // Saltar líneas vacías, comentarios y configuraciones MySQL
    if (empty($line) || 
        strpos($line, '/*!') === 0 || 
        strpos($line, '--') === 0 ||
        strpos($line, 'SET @') === 0 ||
        strpos($line, 'SET ') === 0) {
        continue;
    }
    
    $cleanLines[] = $line;
}

$sqlContent = implode("\n", $cleanLines);

// Dividir en declaraciones individuales
$statements = explode(';', $sqlContent);

echo "Ejecutando " . count($statements) . " declaraciones SQL...\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($statements as $index => $statement) {
    $statement = trim($statement);
    
    // Saltar declaraciones vacías
    if (empty($statement)) {
        continue;
    }
    
    try {
        $db->statement($statement);
        $successCount++;
        
        // Mostrar progreso cada 10 declaraciones exitosas
        if ($successCount % 10 === 0) {
            echo "Procesadas: $successCount declaraciones exitosas\n";
        }
        
        // Mostrar información sobre tablas importantes
        if (strpos($statement, 'CREATE TABLE') !== false) {
            if (preg_match('/CREATE TABLE `([^`]+)`/', $statement, $matches)) {
                echo "✓ Tabla creada: " . $matches[1] . "\n";
            }
        }
        
    } catch (Exception $e) {
        $errorCount++;
        echo "✗ Error en declaración " . ($index + 1) . ": " . $e->getMessage() . "\n";
        
        // Mostrar la declaración que falló (primeras 200 caracteres)
        echo "SQL: " . substr($statement, 0, 200) . "...\n\n";
        
        // Solo parar si hay demasiados errores consecutivos
        if ($errorCount > 20) {
            echo "Demasiados errores. Deteniendo importación.\n";
            break;
        }
    }
}

echo "\n=== RESUMEN ===\n";
echo "Declaraciones exitosas: $successCount\n";
echo "Errores: $errorCount\n";

// Verificar algunas tablas importantes
try {
    $tablesCheck = [
        'actas' => 'SELECT COUNT(*) as count FROM actas',
        'personas' => 'SELECT COUNT(*) as count FROM personas',
        'eventos' => 'SELECT COUNT(*) as count FROM eventos',
        'ermitas' => 'SELECT COUNT(*) as count FROM ermitas'
    ];
    
    echo "\n=== VERIFICACIÓN DE TABLAS ===\n";
    foreach ($tablesCheck as $table => $query) {
        try {
            $result = $db->select($query);
            echo "$table: " . $result[0]->count . " registros\n";
        } catch (Exception $e) {
            echo "$table: No existe o error - " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error en verificación: " . $e->getMessage() . "\n";
}

echo "\nImportación completada.\n";
