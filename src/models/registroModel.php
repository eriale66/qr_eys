<?php
require_once __DIR__ . '/../../config/database.php';

class RegistroModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function registrarAcceso($tipo_usuario, $id_referencia, $tipo_movimiento, $registrado_por = null) {
        $sql = "INSERT INTO registros_acceso (tipo_usuario, id_referencia, tipo_movimiento, registrado_por)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tipo_usuario, $id_referencia, $tipo_movimiento, $registrado_por]);
    }

    public function obtenerRegistros($limite = 100) {
        $sql = "SELECT * FROM registros_acceso ORDER BY fecha_hora DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorFecha($fecha) {
        $sql = "SELECT * FROM registros_acceso WHERE DATE(fecha_hora) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
