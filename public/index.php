<?php
date_default_timezone_set('America/Chicago');

// Configuración de errores según entorno
// Detectar si estamos en desarrollo (localhost) o producción
$isDevelopment = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1', 'localhost']);

if ($isDevelopment) {
    // Modo desarrollo: mostrar todos los errores
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    // Modo producción: ocultar errores y registrar en log
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');

    // Definir ruta del log de errores
    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    ini_set('error_log', $logDir . '/php_errors.log');

    // Manejador de errores personalizado para producción
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        error_log("Error [$errno]: $errstr en $errfile:$errline");
        // No mostrar el error al usuario
        return true;
    });

    // Manejador de excepciones no capturadas
    set_exception_handler(function($exception) {
        error_log("Exception no capturada: " . $exception->getMessage() . " en " . $exception->getFile() . ":" . $exception->getLine());
        http_response_code(500);
        include __DIR__ . '/../src/views/errors/500.php';
        exit;
    });
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';
