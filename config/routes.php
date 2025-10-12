<?php
require_once __DIR__ . '/../src/controllers/RegistroController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$controller = new RegistroController();

switch ($uri) {
    case '/':
    case '/registro':
        $controller->mostrarFormulario();
        break;
    case '/registrar-acceso':
        $controller->registrarAcceso();
        break;
    default:
        echo "404 - Página no encontrada";
}
