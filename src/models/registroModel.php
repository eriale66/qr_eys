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

    // Nuevos filtros dinámicos para reportes
    public function obtenerRegistrosFiltrados($filtros = [], $limite = null) {
        $where = [];
        $params = [];

        if (!empty($filtros['tipo_usuario']) && in_array($filtros['tipo_usuario'], ['empleado','cliente'])) {
            $where[] = 'tipo_usuario = ?';
            $params[] = $filtros['tipo_usuario'];
        }

        if (!empty($filtros['id_referencia'])) {
            $where[] = 'id_referencia = ?';
            $params[] = (int)$filtros['id_referencia'];
        }

        if (!empty($filtros['tipo_movimiento']) && in_array($filtros['tipo_movimiento'], ['entrada','salida'])) {
            $where[] = 'tipo_movimiento = ?';
            $params[] = $filtros['tipo_movimiento'];
        }

        if (!empty($filtros['fecha_inicio'])) {
            $where[] = 'fecha_hora >= ?';
            $params[] = $filtros['fecha_inicio'] . (strlen($filtros['fecha_inicio']) === 10 ? ' 00:00:00' : '');
        }

        if (!empty($filtros['fecha_fin'])) {
            // Incluir el final del día si viene solo la fecha
            $fin = $filtros['fecha_fin'];
            if (strlen($fin) === 10) { $fin .= ' 23:59:59'; }
            $where[] = 'fecha_hora <= ?';
            $params[] = $fin;
        }

        $sql = 'SELECT * FROM registros_acceso';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY fecha_hora DESC';
        if (!is_null($limite)) {
            $sql .= ' LIMIT ?';
        }

        $stmt = $this->conn->prepare($sql);
        // Bind params
        $i = 1;
        foreach ($params as $p) {
            if (is_int($p)) {
                $stmt->bindValue($i, $p, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($i, $p, PDO::PARAM_STR);
            }
            $i++;
        }
        if (!is_null($limite)) {
            $stmt->bindValue($i, (int)$limite, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
