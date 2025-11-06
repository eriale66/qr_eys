<?php
/**
 * Gestor de sesiones con timeout y seguridad
 */
class SessionManager {

    /**
     * Tiempo máximo de inactividad en segundos (30 minutos)
     */
    const TIMEOUT_DURATION = 1800; // 30 minutos

    /**
     * Tiempo máximo de vida de una sesión (2 horas)
     */
    const MAX_LIFETIME = 7200; // 2 horas

    /**
     * Verifica si la sesión ha expirado y la invalida si es necesario
     * @return bool True si la sesión es válida, False si expiró
     */
    public static function checkTimeout() {
        // Si no hay sesión activa, retornar false
        if (!isset($_SESSION['LAST_ACTIVITY'])) {
            return false;
        }

        // Verificar timeout por inactividad
        if (time() - $_SESSION['LAST_ACTIVITY'] > self::TIMEOUT_DURATION) {
            self::destroySession();
            return false;
        }

        // Verificar lifetime máximo de sesión
        if (isset($_SESSION['CREATED']) && (time() - $_SESSION['CREATED'] > self::MAX_LIFETIME)) {
            self::destroySession();
            return false;
        }

        // Actualizar timestamp de última actividad
        $_SESSION['LAST_ACTIVITY'] = time();

        // Regenerar ID de sesión periódicamente (cada 30 minutos)
        if (!isset($_SESSION['LAST_REGENERATION'])) {
            $_SESSION['LAST_REGENERATION'] = time();
        } elseif (time() - $_SESSION['LAST_REGENERATION'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['LAST_REGENERATION'] = time();
        }

        return true;
    }

    /**
     * Destruye la sesión completamente
     */
    public static function destroySession() {
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

        session_destroy();
    }

    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    public static function isAuthenticated() {
        return isset($_SESSION['usuario']) && isset($_SESSION['rol']);
    }

    /**
     * Requiere autenticación, redirige al login si no está autenticado
     * @param string $loginUrl URL de la página de login
     */
    public static function requireAuth($loginUrl = '/qr_eys/public/login') {
        // Verificar timeout
        if (!self::checkTimeout()) {
            header("Location: $loginUrl?timeout=1");
            exit;
        }

        // Verificar autenticación
        if (!self::isAuthenticated()) {
            header("Location: $loginUrl");
            exit;
        }
    }

    /**
     * Obtiene el rol del usuario actual
     * @return string|null
     */
    public static function getUserRole() {
        return $_SESSION['rol'] ?? null;
    }

    /**
     * Obtiene el nombre de usuario actual
     * @return string|null
     */
    public static function getUsername() {
        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Verifica si el usuario tiene un rol específico
     * @param string|array $allowedRoles
     * @return bool
     */
    public static function hasRole($allowedRoles) {
        if (!self::isAuthenticated()) {
            return false;
        }

        $allowedRoles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
        return in_array(self::getUserRole(), $allowedRoles);
    }
}
