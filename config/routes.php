<?php
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/RegistroController.php';
require_once __DIR__ . '/../src/controllers/LoginController.php';
require_once __DIR__ . '/../src/controllers/EmpleadosController.php';
require_once __DIR__ . '/../src/controllers/ClientesController.php';
require_once __DIR__ . '/../src/controllers/AdminController.php';





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
        // echo "<h3>Ruta /registro detectada</h3>"; //  prueba
        (new RegistroController())->mostrarFormulario();
        break;

    case '/registrar-acceso':
        (new RegistroController())->registrarAcceso();
        break;

    // Rutas de dashboard
    // case '/empleados':
    //     if (session_status() === PHP_SESSION_NONE) {
    //         session_start();
    //     }
    //     if (!isset($_SESSION['usuario'])) {
    //         header("Location: /qr_eys/public/login");
    //         exit;
    //     }
    //     (new DashboardController())->empleados();
    //     break;

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

    case '/empleados':
        (new EmpleadoController())->index();
        break;

    case '/empleados/generarQR':
        $id = $_GET['id'] ?? null;
        (new EmpleadoController())->generarQR($id);
        break;

    case '/empleados/pdf':
        (new EmpleadoController())->exportarPDF();
        break;

    case '/empleados/excel':
        (new EmpleadoController())->exportarExcel();
        break;

    case '/empleados/agregar':
        (new EmpleadoController())->mostrarFormularioAgregar();
        break;

    case '/empleados/guardar':
        (new EmpleadoController())->guardarEmpleado();
        break;

    case '/empleados/editar':
        $id = $_GET['id'] ?? null;
        (new EmpleadoController())->mostrarFormularioEditar($id);
        break;

    case '/empleados/actualizar':
        (new EmpleadoController())->actualizarEmpleado();
        break;

    case '/empleados/eliminar':
        $id = $_GET['id'] ?? null;
        (new EmpleadoController())->eliminarEmpleado($id);
        break;

    case '/clientes/generarQR':
        $id = $_GET['id'] ?? null;
        (new ClienteController())->generarQR($id);
        break;

    case '/clientes/pdf':
        (new ClienteController())->exportarPDF();
        break;

    case '/clientes/excel':
        (new ClienteController())->exportarExcel();
        break;
    case '/clientes/agregar':
        (new ClienteController())->mostrarFormularioAgregar();
        break;

    case '/clientes/guardar':
        (new ClienteController())->guardarCliente();
        break;

    case '/clientes/editar':
        $id = $_GET['id'] ?? null;
        (new ClienteController())->mostrarFormularioEditar($id);
        break;

    case '/clientes/actualizar':
        (new ClienteController())->actualizarCliente();
        break;

    case '/clientes/eliminar':
        $id = $_GET['id'] ?? null;
        (new ClienteController())->eliminarCliente($id);
        break;

    case '/administracion':
        (new AdminController())->index();
        break;

    case '/administracion/agregar':
        (new AdminController())->mostrarFormularioAgregar();
        break;

    case '/administracion/guardar':
        (new AdminController())->guardarAdmin();
        break;

    case '/administracion/editar':
        $id = $_GET['id'] ?? null;
        (new AdminController())->mostrarFormularioEditar($id);
        break;

    case '/administracion/actualizar':
        (new AdminController())->actualizarAdmin();
        break;

    case '/administracion/eliminar':
        $id = $_GET['id'] ?? null;
        (new AdminController())->eliminarAdmin($id);
        break;
    default:
        echo "<h1>404 - Página no encontrada</h1><p>Ruta: $uri</p>";
        break;
}
