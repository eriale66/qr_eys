<?php
require_once __DIR__ . '/../../config/database.php';

class PasswordResetModel {
    private $conn;
    private $table = 'password_reset_tokens';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Generar un token único y seguro
     */
    public function generarToken() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Guardar token de recuperación en la base de datos
     */
    public function crearToken($email, $token) {
        try {
            // Primero, marcar como usados todos los tokens anteriores de este email
            $query = "UPDATE " . $this->table . " SET usado = 1 WHERE email = :email AND usado = 0";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Crear nuevo token que expira en 1 hora
            $query = "INSERT INTO " . $this->table . "
                      (email, token, expira_en)
                      VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al crear token: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validar token: debe existir, no estar usado y no estar expirado
     */
    public function validarToken($token) {
        try {
            $query = "SELECT * FROM " . $this->table . "
                      WHERE token = :token
                      AND usado = 0
                      AND expira_en > NOW()
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al validar token: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar token como usado
     */
    public function marcarComoUsado($token) {
        try {
            $query = "UPDATE " . $this->table . "
                      SET usado = 1
                      WHERE token = :token";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al marcar token como usado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Limpiar tokens expirados (para mantenimiento)
     */
    public function limpiarTokensExpirados() {
        try {
            $query = "DELETE FROM " . $this->table . "
                      WHERE expira_en < NOW() OR usado = 1";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al limpiar tokens: " . $e->getMessage());
            return false;
        }
    }
}
