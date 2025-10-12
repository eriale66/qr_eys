<?php
require_once __DIR__ . '/../../config/database.php';

class ClienteModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM clientes WHERE estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorQR($codigo_qr) {
        $sql = "SELECT * FROM clientes WHERE codigo_qr = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$codigo_qr]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $telefono, $correo, $codigo_qr) {
        $sql = "INSERT INTO clientes (nombre, telefono, correo, codigo_qr) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $correo, $codigo_qr]);
    }

    public function eliminar($id_cliente) {
        $sql = "UPDATE clientes SET estado = 0 WHERE id_cliente = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_cliente]);
    }
}
