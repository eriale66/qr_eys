<?php
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';

class DashboardController {
    private $registroModel;
    private $empleadoModel;
    private $clienteModel;

    public function __construct() {
        $this->registroModel = new RegistroModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->clienteModel = new ClienteModel();
    }

    public function index() {
    $registros = $this->registroModel->obtenerRegistros(10) ?? [];
    $empleados = $this->empleadoModel->obtenerTodos() ?? [];
    $clientes = $this->clienteModel->obtenerTodos() ?? [];

    $totalEmpleados = is_array($empleados) ? count($empleados) : 0;
    $totalClientes = is_array($clientes) ? count($clientes) : 0;
    $totalRegistros = is_array($registros) ? count($registros) : 0;

    include __DIR__ . '/../views/dashboard/index.php';
}
}
?>
