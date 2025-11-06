<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../utils/CSRF.php';

// Iniciar sesión con configuración segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class LoginController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function mostrarLogin()
    {
        include __DIR__ . '/../views/login/index.php';
    }

    public function autenticar()
    {
        // Validar token CSRF
        CSRF::validateOrDie();

        $usuario = $_POST['usuario'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';

        if (empty($usuario) || empty($contraseña)) {
            $error = "Por favor, complete todos los campos.";
            include __DIR__ . '/../views/login/index.php';
            return;
        }

        $user = $this->usuarioModel->verificarCredenciales($usuario, $contraseña);

        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die("Detenido para depuración");

        if ($user) {
            // Regenerar ID de sesión para prevenir session fixation
            session_regenerate_id(true);

            // Establecer variables de sesión
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['LAST_ACTIVITY'] = time(); // Timestamp para timeout
            $_SESSION['CREATED'] = time(); // Timestamp de creación

            header("Location: /qr_eys/public/dashboard");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
            include __DIR__ . '/../views/login/index.php';
        }
    }

    public function cerrarSesion()
    {
        // Limpiar todas las variables de sesión
        $_SESSION = array();

        // Eliminar cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destruir sesión
        session_destroy();

        header("Location: /qr_eys/public/login");
        exit;
    }
}
