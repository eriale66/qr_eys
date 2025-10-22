<?php
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/RegistroController.php';
require_once __DIR__ . '/../src/controllers/LoginController.php';



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$uri = str_replace($basePath, '', $uri);
$uri = rtrim($uri, '/');



switch ($uri) {
    case '':
    case '/':

    case '/login':
        (new LoginController())->mostrarLogin();
        break;

    case '/autenticar':
        (new LoginController())->autenticar();
        break;

    case '/logout':
        (new LoginController())->cerrarSesion();
        break;

    case '/dashboard':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: /qr_eys/public/login");
            exit;
        }
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            header("Location: /qr_eys/public/login");
            exit;
        }
        (new DashboardController())->empleados();
        break;

    case '/clientes':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };
        if (!isset($_SESSION['usuario'])) {
            header("Location: /qr_eys/public/login");
            exit;
        }
        (new DashboardController())->clientes();
        break;

    case '/citas':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            header("Location: /qr_eys/public/login");
            exit;
        }
        (new DashboardController())->citas();
        break;

    case '/reportes':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            header("Location: /qr_eys/public/login");
            exit;
        }
        (new DashboardController())->reportes();
        break;

    case '/configuracion':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            header("Location: /qr_eys/public/login");
            exit;
        }
        (new DashboardController())->configuracion();
        break;

    default:
        echo "<h1>404 - Página no encontrada</h1><p>Ruta: $uri</p>";
        break;
}
