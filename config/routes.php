<?php
// ================================================
// Archivo de rutas del sistema
// ================================================

// Cargar controladores
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/RegistroController.php';

// Obtener URI actual
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Detectar subcarpeta base (ej. /qr_eys/public)
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim($scriptName, '/');
$uri = str_replace($basePath, '', $uri);

// Normalizar URI
$uri = rtrim($uri, '/');

// Ruteo simple
switch ($uri) {
    case '':
    case '/':
    case '/dashboard':
        (new DashboardController())->index();
        break;

    case '/registro':
        (new RegistroController())->mostrarFormulario();
        break;

    case '/registrar-acceso':
        (new RegistroController())->registrarAcceso();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>Ruta solicitada: <strong>$uri</strong></p>";
        break;
}
