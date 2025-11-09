<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PasswordResetModel.php';
require_once __DIR__ . '/../utils/CSRF.php';
require_once __DIR__ . '/../utils/EmailService.php';

use Erick\QrEys\Utils\EmailService;

// Iniciar sesión con configuración segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class LoginController
{
    private $usuarioModel;
    private $passwordResetModel;
    private $emailService;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->passwordResetModel = new PasswordResetModel();
        $this->emailService = new EmailService();
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

    /**
     * Mostrar formulario de "Olvidé mi contraseña"
     */
    public function mostrarOlvidePassword()
    {
        include __DIR__ . '/../views/login/olvide_password.php';
    }

    /**
     * Procesar solicitud de recuperación de contraseña
     */
    public function enviarRecuperacion()
    {
        // Validar token CSRF
        CSRF::validateOrDie();

        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $error = "Por favor, ingrese su correo electrónico.";
            include __DIR__ . '/../views/login/olvide_password.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Por favor, ingrese un correo electrónico válido.";
            include __DIR__ . '/../views/login/olvide_password.php';
            return;
        }

        // Buscar usuario por email
        $usuario = $this->usuarioModel->obtenerPorEmail($email);

        // Por seguridad, siempre mostramos el mismo mensaje (evitar enumerar emails)
        $success = "Si el correo está registrado, recibirás un enlace de recuperación en unos minutos.";

        if ($usuario) {
            // Generar token único
            $token = $this->passwordResetModel->generarToken();

            // Guardar token en la base de datos
            if ($this->passwordResetModel->crearToken($email, $token)) {
                // Enviar email con el token
                $enviado = $this->emailService->enviarEmailRecuperacion(
                    $email,
                    $usuario['nombre'],
                    $token
                );

                if (!$enviado) {
                    error_log("Error al enviar email de recuperación a: " . $email);
                }
            }
        }

        // Siempre mostrar el mismo mensaje (seguridad)
        include __DIR__ . '/../views/login/olvide_password.php';
    }

    /**
     * Mostrar formulario para restablecer contraseña
     */
    public function mostrarRestablecerPassword()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $error = "Token inválido o faltante.";
            include __DIR__ . '/../views/login/olvide_password.php';
            return;
        }

        // Validar que el token sea válido
        $tokenData = $this->passwordResetModel->validarToken($token);

        if (!$tokenData) {
            $error = "El enlace de recuperación es inválido o ha expirado. Por favor, solicita uno nuevo.";
            include __DIR__ . '/../views/login/olvide_password.php';
            return;
        }

        // Mostrar formulario de nueva contraseña
        include __DIR__ . '/../views/login/restablecer_password.php';
    }

    /**
     * Procesar cambio de contraseña
     */
    public function procesarRestablecerPassword()
    {
        // Validar token CSRF
        CSRF::validateOrDie();

        $token = $_POST['token'] ?? '';
        $nuevaPassword = $_POST['nueva_password'] ?? '';
        $confirmarPassword = $_POST['confirmar_password'] ?? '';

        if (empty($token) || empty($nuevaPassword) || empty($confirmarPassword)) {
            $error = "Todos los campos son obligatorios.";
            include __DIR__ . '/../views/login/restablecer_password.php';
            return;
        }

        // Validar que las contraseñas coincidan
        if ($nuevaPassword !== $confirmarPassword) {
            $error = "Las contraseñas no coinciden.";
            include __DIR__ . '/../views/login/restablecer_password.php';
            return;
        }

        // Validar longitud mínima
        if (strlen($nuevaPassword) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres.";
            include __DIR__ . '/../views/login/restablecer_password.php';
            return;
        }

        // Validar token
        $tokenData = $this->passwordResetModel->validarToken($token);

        if (!$tokenData) {
            $error = "El enlace de recuperación es inválido o ha expirado.";
            include __DIR__ . '/../views/login/olvide_password.php';
            return;
        }

        // Actualizar contraseña
        if ($this->usuarioModel->actualizarPassword($tokenData['email'], $nuevaPassword)) {
            // Marcar token como usado
            $this->passwordResetModel->marcarComoUsado($token);

            // Mostrar mensaje de éxito
            $success = true;
            include __DIR__ . '/../views/login/restablecer_password.php';
        } else {
            $error = "Error al actualizar la contraseña. Intente nuevamente.";
            include __DIR__ . '/../views/login/restablecer_password.php';
        }
    }
}
