<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
session_start();

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
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'];
            header("Location: /qr_eys/public/dashboard");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
            include __DIR__ . '/../views/login/index.php';
        }
    }

    public function cerrarSesion()
    {
        session_destroy();
        header("Location: /qr_eys/public/login");
        exit;
    }
}
