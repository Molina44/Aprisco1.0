<?php
session_start();

// Configuración general
define('BASE_URL', 'http://localhost:8000');
define('SITE_NAME', 'Aprisco');

// Configuración de seguridad
define('PASSWORD_MIN_LENGTH', 6);
define('SESSION_TIMEOUT', 3600); // 1 hora

// Autoload de clases
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../src/' . strtolower(str_replace('\\', '/', $class)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Funciones auxiliares
require_once __DIR__ . '/../includes/functions.php';

// Configurar zona horaria
date_default_timezone_set('America/Bogota');