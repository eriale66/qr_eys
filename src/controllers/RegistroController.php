<?php
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/RegistroModel.php';

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
        include __DIR__ . '/../views/registro/formulario.php';
    }

    public function registrarAcceso() {
        if (!isset($_POST['codigo']) || empty($_POST['codigo'])) {
            $mensaje = "⚠️ No se detectó ningún código.";
            include __DIR__ . '/../views/registro/resultado.php';
            return;
        }

        $codigo = trim($_POST['codigo']);
        $persona = null;
        $tipo_usuario = null;

        // Buscar en empleados
        $persona = $this->empleadoModel->obtenerPorQR($codigo);
        if ($persona) {
            $tipo_usuario = 'empleado';
        } else {
            // Buscar en clientes
            $persona = $this->clienteModel->obtenerPorQR($codigo);
            if ($persona) {
                $tipo_usuario = 'cliente';
            }
        }

        if (!$persona) {
            $mensaje = " Código no reconocido.";
            include __DIR__ . '/../views/registro/resultado.php';
            return;
        }

        // Determinar si es entrada o salida
        $tipo_movimiento = $this->determinarMovimiento($tipo_usuario, $persona['id_' . $tipo_usuario]);

        // Registrar el movimiento
        $this->registroModel->registrarAcceso(
            $tipo_usuario,
            $persona['id_' . $tipo_usuario],
            $tipo_movimiento,
            null // registrado_por (null porque es escaneo directo)
        );

        $nombre = htmlspecialchars($persona['nombre']);
        $hora = date('H:i:s');
        $mensaje = " {$tipo_movimiento} registrada para {$nombre} a las {$hora}.";
        include __DIR__ . '/../views/registro/resultado.php';
    }

    private function determinarMovimiento($tipo_usuario, $id_referencia) {
        // Consulta el último registro del usuario
        $db = new Database();
        $conn = $db->connect();
        $sql = "SELECT tipo_movimiento FROM registros_acceso 
                WHERE tipo_usuario = ? AND id_referencia = ?
                ORDER BY fecha_hora DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$tipo_usuario, $id_referencia]);
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ultimo || $ultimo['tipo_movimiento'] === 'salida') {
            return 'entrada';
        } else {
            return 'salida';
        }
    }
}
?>
