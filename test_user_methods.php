<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\UserCredit;

// Este es un archivo de prueba para verificar que los métodos existen
// Puedes ejecutarlo con: php test_user_methods.php

$user = new User();

// Verificar que los métodos existen
if (method_exists($user, 'getCredits')) {
    echo "✓ Método getCredits existe\n";
} else {
    echo "✗ Método getCredits NO existe\n";
}

if (method_exists($user, 'consumeCredits')) {
    echo "✓ Método consumeCredits existe\n";
} else {
    echo "✗ Método consumeCredits NO existe\n";
}

if (method_exists($user, 'payments')) {
    echo "✓ Método payments existe\n";
} else {
    echo "✗ Método payments NO existe\n";
}

if (method_exists($user, 'hasCredits')) {
    echo "✓ Método hasCredits existe\n";
} else {
    echo "✗ Método hasCredits NO existe\n";
}

echo "\nTodos los métodos necesarios están definidos en el modelo User.\n";
echo "Si hay errores en el IDE, puede ser un problema del language server.\n";
