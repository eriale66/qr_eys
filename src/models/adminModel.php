<?php
class AdminModel {
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../../config/database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios ORDER BY id_usuario DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_usuario) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarAdmin($nombre, $usuario, $email, $contraseña, $rol) {
        $sql = "INSERT INTO usuarios (nombre, usuario, email, contraseña, rol, estado) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $usuario, $email, $contraseña, $rol]);
    }

    public function actualizarAdmin($id_usuario, $nombre, $usuario, $email, $rol, $contraseña = null) {
        if ($contraseña) {
            // Si se proporciona nueva contraseña, actualizarla también
            $sql = "UPDATE usuarios SET nombre=?, usuario=?, email=?, rol=?, contraseña=? WHERE id_usuario=?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$nombre, $usuario, $email, $rol, $contraseña, $id_usuario]);
        } else {
            // Si no se proporciona contraseña, solo actualizar los demás campos
            $sql = "UPDATE usuarios SET nombre=?, usuario=?, email=?, rol=? WHERE id_usuario=?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$nombre, $usuario, $email, $rol, $id_usuario]);
        }
    }

    public function eliminarAdmin($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
}
