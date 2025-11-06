<?php
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../utils/CSRF.php';

class AdminController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    public function index() {
        $usuarios = $this->adminModel->obtenerTodos();
        include __DIR__ . '/../views/dashboard/administracion.php';
    }

    public function mostrarFormularioAgregar() {
        include __DIR__ . '/../views/dashboard/agregar_admin.php';
    }

    public function guardarAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            CSRF::validateOrDie();

            $nombre = $_POST['nombre'] ?? '';
            $usuario = $_POST['usuario'] ?? '';
            $contraseña = $_POST['contraseña'] ?? '';
            $rol = $_POST['rol'] ?? 'admin';

            if (empty($nombre) || empty($usuario) || empty($contraseña)) {
                header("Location: /qr_eys/public/administracion?type=error&msg=" . urlencode('Todos los campos son obligatorios'));
                exit;
            }

            // Encriptar contraseña
            $hash = password_hash($contraseña, PASSWORD_BCRYPT);

            $this->adminModel->insertarAdmin($nombre, $usuario, $hash, $rol);

            header("Location: /qr_eys/public/administracion?type=success&msg=" . urlencode('Administrador agregado correctamente'));
            exit;
        }
    }

    public function mostrarFormularioEditar($id) {
        $admin = $this->adminModel->obtenerPorId($id);
        include __DIR__ . '/../views/dashboard/editar_admin.php';
    }

    public function actualizarAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            CSRF::validateOrDie();

            $id = $_POST['id_usuario'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $usuario = $_POST['usuario'] ?? '';
            $rol = $_POST['rol'] ?? 'admin';

            if (empty($id) || empty($nombre) || empty($usuario)) {
                header("Location: /qr_eys/public/administracion?type=error&msg=" . urlencode('Todos los campos son obligatorios'));
                exit;
            }

            $this->adminModel->actualizarAdmin($id, $nombre, $usuario, $rol);
            header("Location: /qr_eys/public/administracion?type=success&msg=" . urlencode('Administrador actualizado correctamente'));
            exit;
        }
    }

    public function eliminarAdmin($id = null) {
        // Si viene de POST, validar CSRF y obtener ID
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::validateOrDie();
            $id = $_POST['id'] ?? null;
        }

        if ($this->adminModel->eliminarAdmin($id)) {
            header("Location: /qr_eys/public/administracion?type=success&msg=" . urlencode('Administrador eliminado correctamente'));
            exit;
        } else {
            header("Location: /qr_eys/public/administracion?type=error&msg=" . urlencode('Error al eliminar administrador'));
            exit;
        }
    }
}
