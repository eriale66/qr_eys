<?php
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/citaModel.php';

class DashboardController {
    private $empleadoModel;
    private $clienteModel;
    private $registroModel;
    private $citaModel;

    public function __construct() {
        $this->empleadoModel = new EmpleadoModel();
        $this->clienteModel = new ClienteModel();
        $this->registroModel = new RegistroModel();
        $this->citaModel     = new CitaModel();
    }

    public function index() {
        // Obtener datos base
        $empleados = $this->empleadoModel->obtenerTodos() ?? [];
        $clientes = $this->clienteModel->obtenerTodos() ?? [];
        $registros = $this->registroModel->obtenerRegistros(10) ?? [];

        // Totales base
        $totalEmpleados = count($empleados);
        $totalClientes = count($clientes);
        $totalRegistros = count($this->registroModel->obtenerRegistros(1000));

        // Para la tabla de recientes (últimos 10 movimientos)
        $totalEntradas = 0; // se sobre-escribirá con totales del día más abajo
        $totalSalidas = 0;  // se sobre-escribirá con totales del día más abajo

        // Crear array enriquecido con nombres reales
        $registrosDetallados = [];

        foreach ($registros as $r) {
            if ($r['tipo_movimiento'] === 'entrada') $totalEntradas++;
            else $totalSalidas++;

            // Buscar nombre real del usuario
            $nombreUsuario = 'Desconocido';
            if ($r['tipo_usuario'] === 'empleado') {
                $persona = $this->empleadoModel->obtenerPorId($r['id_referencia']);
                if ($persona) $nombreUsuario = $persona['nombre'];
            } elseif ($r['tipo_usuario'] === 'cliente') {
                $persona = $this->clienteModel->obtenerPorId($r['id_referencia']);
                if ($persona) $nombreUsuario = $persona['nombre'];
            }

            // Agregar al arreglo de registros con nombre incluido
            $registrosDetallados[] = [
                'nombre' => $nombreUsuario,
                'tipo_usuario' => $r['tipo_usuario'],
                'tipo_movimiento' => $r['tipo_movimiento'],
                'fecha_hora' => $r['fecha_hora']
            ];
        }

        // Presencia por día (último movimiento de hoy por empleado)
        $hoy = date('Y-m-d');
        $ultimosHoy = $this->registroModel->obtenerUltimosMovimientosEmpleadosPorFecha($hoy);
        $presentes = 0;
        foreach ($ultimosHoy as $u) {
            if (($u['tipo_movimiento'] ?? '') === 'entrada') $presentes++;
        }
        $ausentes = max(0, $totalEmpleados - $presentes);
        $porcentaje = $totalEmpleados > 0 ? round(($presentes / $totalEmpleados) * 100, 1) : 0;

        // Presencia por día (último movimiento de hoy por empleado)
        $hoy = date('Y-m-d');
        $ultimosHoy = $this->registroModel->obtenerUltimosMovimientosEmpleadosPorFecha($hoy);
        $presentes = 0;
        foreach ($ultimosHoy as $u) {
            if (($u['tipo_movimiento'] ?? '') === 'entrada') $presentes++;
        }
        $ausentes = max(0, $totalEmpleados - $presentes);
        $porcentaje = $totalEmpleados > 0 ? round(($presentes / $totalEmpleados) * 100, 1) : 0;

        // Totales de movimientos del día (para tarjetas)
        $registrosHoy = $this->registroModel->obtenerPorFecha($hoy) ?? [];
        $totalEntradasHoy = 0; $totalSalidasHoy = 0;
        foreach ($registrosHoy as $r) {
            if (($r['tipo_movimiento'] ?? '') === 'entrada') $totalEntradasHoy++;
            elseif (($r['tipo_movimiento'] ?? '') === 'salida') $totalSalidasHoy++;
        }
        $totalEntradas = $totalEntradasHoy;
        $totalSalidas  = $totalSalidasHoy;

        include __DIR__ . '/../views/dashboard/index.php';
    }

    public function empleados() {
        $empleados = $this->empleadoModel->obtenerTodos() ?? [];
        include __DIR__ . '/../views/dashboard/empleados.php';
    }

    public function clientes() {
        $clientes = $this->clienteModel->obtenerTodos() ?? [];
        include __DIR__ . '/../views/dashboard/clientes.php';
    }

    public function citas() {
        $citas = $this->citaModel->obtenerTodas() ?? [];
        include __DIR__ . '/../views/dashboard/citas.php';
    }

    public function reportes() {
        // Datos base para reportes/estadísticas
        $registros = $this->registroModel->obtenerRegistros(500) ?? [];
        $totalEntradas = 0;
        $totalSalidas  = 0;
        foreach ($registros as $r) {
            if (($r['tipo_movimiento'] ?? '') === 'entrada') $totalEntradas++;
            else $totalSalidas++;
        }
        include __DIR__ . '/../views/dashboard/reportes_estadisticas.php';
    }

    public function configuracion() {
        include __DIR__ . '/../views/dashboard/configuracion.php';
    }
}
