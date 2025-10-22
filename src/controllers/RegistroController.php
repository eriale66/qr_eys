<?php
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../../config/database.php';

class RegistroController {
    private $empleadoModel;
    private $clienteModel;
    private $registroModel;

    public function __construct() {
        $this->empleadoModel = new EmpleadoModel();
        $this->clienteModel = new ClienteModel();
        $this->registroModel = new RegistroModel();
    }

    public function mostrarFormulario() {
        // ✅ Muestra el formulario de escaneo
        include __DIR__ . '/../views/registro/formulario.php';
    }

    public function registrarAcceso() {
        // ✅ Si no se envió código
        if (empty($_POST['codigo'])) {
            $mensaje = "⚠️ No se detectó ningún código.";
            include __DIR__ . '/../views/registro/resultado.php';
            return;
        }

        $codigo = trim($_POST['codigo']);
        $tipo_usuario = null;
        $persona = null;

        // Buscar si el QR pertenece a un empleado o cliente
        $persona = $this->empleadoModel->obtenerPorQR($codigo);
        if ($persona) {
            $tipo_usuario = 'empleado';
        } else {
            $persona = $this->clienteModel->obtenerPorQR($codigo);
            if ($persona) {
                $tipo_usuario = 'cliente';
            }
        }

        // Si no existe el QR
        if (!$persona) {
            $mensaje = "❌ Código no reconocido.";
            include __DIR__ . '/../views/registro/resultado.php';
            return;
        }

        // Determinar si le toca entrada o salida
        $tipo_movimiento = $this->determinarMovimiento($tipo_usuario, $persona['id_' . $tipo_usuario]);

        // Registrar movimiento
        $this->registroModel->registrarAcceso($tipo_usuario, $persona['id_' . $tipo_usuario], $tipo_movimiento, null);

        // Mostrar mensaje en pantalla
        $nombre = htmlspecialchars($persona['nombre']);
        $hora = date('H:i:s');
        $mensaje = "✅ {$tipo_movimiento} registrada para {$nombre} a las {$hora}.";
        include __DIR__ . '/../views/registro/resultado.php';
    }

    private function determinarMovimiento($tipo_usuario, $id_referencia) {
        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT tipo_movimiento FROM registros_acceso
                WHERE tipo_usuario = ? AND id_referencia = ?
                ORDER BY fecha_hora DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$tipo_usuario, $id_referencia]);
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no hay registro previo o el último fue salida → entrada
        return (!$ultimo || $ultimo['tipo_movimiento'] === 'salida') ? 'entrada' : 'salida';
    }
}
