<?php
require_once __DIR__ . '/../../config/database.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios WHERE estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario($usuario) {
        $sql = "SELECT * FROM usuarios WHERE usuario = ? AND estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $usuario, $contraseña, $rol) {
        $hash = password_hash($contraseña, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, usuario, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $usuario, $hash, $rol]);
    }
}
