<?php
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/RegistroController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$uri = str_replace($basePath, '', $uri);
$uri = rtrim($uri, '/');



switch ($uri) {
    case '':
    case '/':
    case '/dashboard':
        (new DashboardController())->index();
        break;

    case '/registro':
        echo "<h3>Ruta /registro detectada</h3>"; // 🔍 prueba
        (new RegistroController())->mostrarFormulario();
        break;

    case '/registrar-acceso':
        (new RegistroController())->registrarAcceso();
        break;

    // Rutas de dashboard
    case '/empleados':
        (new DashboardController())->empleados();
        break;

    case '/clientes':
        (new DashboardController())->clientes();
        break;

    case '/citas':
        (new DashboardController())->citas();
        break;

    case '/reportes':
        (new DashboardController())->reportes();
        break;

    case '/configuracion':
        (new DashboardController())->configuracion();
        break;

    default:
        echo "<h1>404 - Página no encontrada</h1><p>Ruta: $uri</p>";
        break;
}
