<?php

class CSRF {

    /**
     * Genera un nuevo token CSRF y lo almacena en la sesión
     * @return string El token generado
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    /**
     * Obtiene el token CSRF actual, o genera uno nuevo si no existe
     * @return string El token CSRF
     */
    public static function getToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            return self::generateToken();
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Valida el token CSRF enviado en una petición
     * @param string|null $token El token a validar (normalmente de $_POST['csrf_token'])
     * @return bool True si es válido, false en caso contrario
     */
    public static function validateToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Valida el token CSRF y muere con un error si no es válido
     * @param string|null $token El token a validar (normalmente de $_POST['csrf_token'])
     */
    public static function validateOrDie($token = null) {
        $token = $token ?? ($_POST['csrf_token'] ?? null);

        if (!self::validateToken($token)) {
            http_response_code(403);
            die('Error de seguridad: Token CSRF inválido. Por favor, recarga la página e intenta de nuevo.');
        }
    }

    /**
     * Genera el campo HTML oculto con el token CSRF
     * @return string HTML del input hidden
     */
    public static function inputField() {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
