<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../src/utils/SessionManager.php';
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/RegistroController.php';
require_once __DIR__ . '/../src/controllers/LoginController.php';
require_once __DIR__ . '/../src/controllers/EmpleadosController.php';
require_once __DIR__ . '/../src/controllers/ClientesController.php';
require_once __DIR__ . '/../src/controllers/AdminController.php';
require_once __DIR__ . '/../src/controllers/ReportesController.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}





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
        SessionManager::requireAuth();
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
        SessionManager::requireAuth();
        (new DashboardController())->clientes();
        break;

    case '/citas':
        SessionManager::requireAuth();
        (new DashboardController())->citas();
        break;

    case '/reportes':
        SessionManager::requireAuth();
        (new ReportesController())->index();
        break;

    case '/reportes/pdf':
        SessionManager::requireAuth();
        (new ReportesController())->exportPdf();
        break;

    case '/reportes/excel':
        SessionManager::requireAuth();
        (new ReportesController())->exportExcel();
        break;

    case '/configuracion':
        SessionManager::requireAuth();
        (new DashboardController())->configuracion();
        break;

    case '/empleados':
        SessionManager::requireAuth();
        (new EmpleadoController())->index();
        break;

    case '/empleados/generarQR':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new EmpleadoController())->generarQR($id);
        break;

    case '/empleados/pdf':
        SessionManager::requireAuth();
        (new EmpleadoController())->exportarPDF();
        break;

    case '/empleados/excel':
        SessionManager::requireAuth();
        (new EmpleadoController())->exportarExcel();
        break;

    case '/empleados/agregar':
        SessionManager::requireAuth();
        (new EmpleadoController())->mostrarFormularioAgregar();
        break;

    case '/empleados/guardar':
        SessionManager::requireAuth();
        (new EmpleadoController())->guardarEmpleado();
        break;

    case '/empleados/editar':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new EmpleadoController())->mostrarFormularioEditar($id);
        break;

    case '/empleados/actualizar':
        SessionManager::requireAuth();
        (new EmpleadoController())->actualizarEmpleado();
        break;

    case '/empleados/eliminar':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new EmpleadoController())->eliminarEmpleado($id);
        break;

    case '/clientes/generarQR':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new ClienteController())->generarQR($id);
        break;

    case '/clientes/pdf':
        SessionManager::requireAuth();
        (new ClienteController())->exportarPDF();
        break;

    case '/clientes/excel':
        SessionManager::requireAuth();
        (new ClienteController())->exportarExcel();
        break;

    case '/clientes/agregar':
        SessionManager::requireAuth();
        (new ClienteController())->mostrarFormularioAgregar();
        break;

    case '/clientes/guardar':
        SessionManager::requireAuth();
        (new ClienteController())->guardarCliente();
        break;

    case '/clientes/editar':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new ClienteController())->mostrarFormularioEditar($id);
        break;

    case '/clientes/actualizar':
        SessionManager::requireAuth();
        (new ClienteController())->actualizarCliente();
        break;

    case '/clientes/eliminar':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new ClienteController())->eliminarCliente($id);
        break;

    case '/administracion':
        SessionManager::requireAuth();
        (new AdminController())->index();
        break;

    case '/administracion/agregar':
        SessionManager::requireAuth();
        (new AdminController())->mostrarFormularioAgregar();
        break;

    case '/administracion/guardar':
        SessionManager::requireAuth();
        (new AdminController())->guardarAdmin();
        break;

    case '/administracion/editar':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new AdminController())->mostrarFormularioEditar($id);
        break;

    case '/administracion/actualizar':
        SessionManager::requireAuth();
        (new AdminController())->actualizarAdmin();
        break;

    case '/administracion/eliminar':
        SessionManager::requireAuth();
        $id = $_GET['id'] ?? null;
        (new AdminController())->eliminarAdmin($id);
        break;
    default:
        http_response_code(404);
        include __DIR__ . '/../src/views/errors/404.php';
        break;
}
