<?php
require_once __DIR__ . '/../../config/database.php';

class CitaModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerTodas() {
        $sql = "SELECT c.id_cita, cli.nombre AS cliente, emp.nombre AS empleado, c.fecha_cita, c.estado, c.observaciones
                FROM citas c
                JOIN clientes cli ON c.id_cliente = cli.id_cliente
                JOIN empleados emp ON c.id_empleado = emp.id_empleado";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($id_cliente, $id_empleado, $fecha_cita, $estado, $observaciones) {
        $sql = "INSERT INTO citas (id_cliente, id_empleado, fecha_cita, estado, observaciones)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_cliente, $id_empleado, $fecha_cita, $estado, $observaciones]);
    }

    public function actualizarEstado($id_cita, $estado) {
        $sql = "UPDATE citas SET estado = ? WHERE id_cita = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$estado, $id_cita]);
    }
}
