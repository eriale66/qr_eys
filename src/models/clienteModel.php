<?php
class ClienteModel
{
    private $conn;

    public function __construct()
    {
        require_once __DIR__ . '/../../config/database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM clientes ORDER BY id_cliente DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_cliente)
    {
        $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_cliente]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarCliente($nombre, $correo, $telefono, $codigo_qr)
    {
        $sql = "INSERT INTO clientes (nombre, correo, telefono, codigo_qr, estado)
                VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $correo, $telefono, $codigo_qr]);
    }

    public function actualizarCliente($id_cliente, $nombre, $correo, $telefono)
    {
        $sql = "UPDATE clientes SET nombre=?, correo=?, telefono=? WHERE id_cliente=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $correo, $telefono, $id_cliente]);
    }

    public function eliminarCliente($id_cliente)
    {
        $sql = "DELETE FROM clientes WHERE id_cliente=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_cliente]);
    }
}
