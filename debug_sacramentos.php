<?php
require_once 'vendor/autoload.php';

// Configurar la conexión a la base de datos usando las mismas configuraciones del .env
$pdo = new PDO('mysql:host=localhost;dbname=sacramentify', 'root', '');

try {
    // Consultar todos los sacramentos
    echo "=== SACRAMENTOS ===\n";
    $stmt = $pdo->query("SELECT cve_sacramentos, nombre FROM sacramentos ORDER BY cve_sacramentos");
    $sacramentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($sacramentos as $sacramento) {
        echo "ID: {$sacramento['cve_sacramentos']}, Nombre: '{$sacramento['nombre']}'\n";
    }
    
    echo "\n=== CONTADORES POR TIPO_ACTA ===\n";
    
    // Contar actas por tipo
    $stmt = $pdo->query("
        SELECT a.tipo_acta, s.nombre, COUNT(*) as total 
        FROM actas a 
        LEFT JOIN sacramentos s ON a.tipo_acta = s.cve_sacramentos 
        GROUP BY a.tipo_acta, s.nombre 
        ORDER BY a.tipo_acta
    ");
    $contadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($contadores as $contador) {
        echo "tipo_acta {$contador['tipo_acta']} ({$contador['nombre']}): {$contador['total']} actas\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
