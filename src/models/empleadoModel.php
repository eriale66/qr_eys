<?php
require_once __DIR__ . '/../../config/database.php';

class EmpleadoModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM empleados WHERE estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorQR($codigo_qr) {
        $sql = "SELECT * FROM empleados WHERE codigo_qr = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$codigo_qr]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $puesto, $correo, $telefono, $codigo_qr) {
        $sql = "INSERT INTO empleados (nombre, puesto, correo, telefono, codigo_qr) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $puesto, $correo, $telefono, $codigo_qr]);
    }

    public function eliminar($id_empleado) {
        $sql = "UPDATE empleados SET estado = 0 WHERE id_empleado = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_empleado]);
    }
}
