<?php
require_once __DIR__ . '/../models/empleadoModel.php';
require_once __DIR__ . '/../models/clienteModel.php';
require_once __DIR__ . '/../models/registroModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesController
{
    private $empleadoModel;
    private $clienteModel;
    private $registroModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->clienteModel = new ClienteModel();
        $this->registroModel = new RegistroModel();
    }

    private function buildDateRange(&$desde, &$hasta, $periodo)
    {
        $today = date('Y-m-d');
        switch ($periodo) {
            case 'semana':
                $desde = date('Y-m-d', strtotime('monday this week'));
                $hasta = $today;
                break;
            case 'mes':
                $desde = date('Y-m-01');
                $hasta = $today;
                break;
            case 'rango':
                // Mantener los valores recibidos
                if (empty($desde)) $desde = $today;
                if (empty($hasta)) $hasta = $today;
                break;
            case 'hoy':
            default:
                $desde = $today;
                $hasta = $today;
                break;
        }
    }

    private function collectFiltersFromQuery()
    {
        $tipo = $_GET['tipo'] ?? '';
        $idRef = $_GET['id_referencia'] ?? '';
        $mov = $_GET['mov'] ?? '';
        $periodo = $_GET['periodo'] ?? 'hoy';
        $agrupacion = $_GET['agrupacion'] ?? 'dia'; // dia|semana|mes
        $desde = $_GET['desde'] ?? '';
        $hasta = $_GET['hasta'] ?? '';

        $this->buildDateRange($desde, $hasta, $periodo);

        $filtros = [
            'tipo_usuario'   => in_array($tipo, ['empleado','cliente']) ? $tipo : null,
            'id_referencia'  => is_numeric($idRef) ? (int)$idRef : null,
            'tipo_movimiento'=> in_array($mov, ['entrada','salida']) ? $mov : null,
            'fecha_inicio'   => $desde,
            'fecha_fin'      => $hasta,
        ];

        return [$filtros, $periodo, $desde, $hasta, $agrupacion];
    }

    private function buildChartData(array $registros)
    {
        $map = [];
        foreach ($registros as $r) {
            $d = substr($r['fecha_hora'] ?? '', 0, 10);
            if (!$d) continue;
            if (!isset($map[$d])) $map[$d] = ['entrada' => 0, 'salida' => 0];
            $mov = $r['tipo_movimiento'] ?? '';
            if ($mov === 'entrada') $map[$d]['entrada']++;
            if ($mov === 'salida') $map[$d]['salida']++;
        }
        ksort($map);
        $labels = array_keys($map);
        $entradas = array_map(fn($v) => $v['entrada'], $map);
        $salidas  = array_map(fn($v) => $v['salida'], $map);
        return [$labels, $entradas, $salidas];
    }

    public function index()
    {
        [$filtros, $periodo, $desde, $hasta, $agrupacion] = $this->collectFiltersFromQuery();

        $registros = $this->registroModel->obtenerRegistrosFiltrados($filtros);

        $totalEntradas = 0; $totalSalidas = 0;
        foreach ($registros as $r) {
            if (($r['tipo_movimiento'] ?? '') === 'entrada') $totalEntradas++;
            if (($r['tipo_movimiento'] ?? '') === 'salida') $totalSalidas++;
        }

        [$chartLabels, $chartEntradas, $chartSalidas] = $this->buildChartData($registros);

        $empleados = $this->empleadoModel->obtenerTodos() ?? [];
        $clientes  = $this->clienteModel->obtenerTodos() ?? [];

        // Mapear nombres de usuario para mostrar en tabla/export
        $mapEmp = [];
        foreach ($empleados as $e) { $mapEmp[(string)$e['id_empleado']] = $e['nombre']; }
        $mapCli = [];
        foreach ($clientes as $c) { $mapCli[(string)$c['id_cliente']] = $c['nombre']; }

        foreach ($registros as &$r) {
            $nombre = 'Desconocido';
            $tipoU = (string)($r['tipo_usuario'] ?? '');
            $idRef = (string)($r['id_referencia'] ?? '');
            if ($tipoU === 'empleado' && isset($mapEmp[$idRef])) { $nombre = $mapEmp[$idRef]; }
            if ($tipoU === 'cliente'  && isset($mapCli[$idRef])) { $nombre = $mapCli[$idRef]; }
            $r['nombre_usuario'] = $nombre;
        }
        unset($r);

        // Agregaciones por dia/semana/mes
        [$aggLabels, $aggEntradas, $aggSalidas, $aggTotales] = $this->buildAggregations($registros, $agrupacion);
        [$aggEmp, $aggCli] = $this->buildAggregationsByUsuario($registros, $agrupacion);

        // Top empleados y clientes por movimientos
        [$topEmpNombres, $topEmpValores] = $this->buildTopByUsuario($registros, 'empleado', $mapEmp);
        [$topCliNombres, $topCliValores] = $this->buildTopByUsuario($registros, 'cliente', $mapCli);

        include __DIR__ . '/../views/dashboard/reportes_estadisticas.php';
    }

    public function exportPdf()
    {
        [$filtros, $periodo, $desde, $hasta, $agrupacion] = $this->collectFiltersFromQuery();
        $registros = $this->registroModel->obtenerRegistrosFiltrados($filtros);

        // Mapear nombres
        $empleados = $this->empleadoModel->obtenerTodos() ?? [];
        $clientes  = $this->clienteModel->obtenerTodos() ?? [];
        $mapEmp = [];
        foreach ($empleados as $e) { $mapEmp[(string)$e['id_empleado']] = $e['nombre']; }
        $mapCli = [];
        foreach ($clientes as $c) { $mapCli[(string)$c['id_cliente']] = $c['nombre']; }

        $html = '<h2>Reporte de Accesos</h2>';
        $html .= '<p><strong>Periodo:</strong> ' . htmlspecialchars($periodo) . ' (' . htmlspecialchars($desde) . ' a ' . htmlspecialchars($hasta) . ')</p>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Tipo Usuario</th>
                            <th>Movimiento</th>
                            <th>Fecha y hora</th>
                        </tr>
                    </thead><tbody>';
        foreach ($registros as $r) {
            $tipoU = (string)($r['tipo_usuario'] ?? '');
            $idRef = (string)($r['id_referencia'] ?? '');
            $nombre = 'Desconocido';
            if ($tipoU === 'empleado' && isset($mapEmp[$idRef])) { $nombre = $mapEmp[$idRef]; }
            if ($tipoU === 'cliente'  && isset($mapCli[$idRef])) { $nombre = $mapCli[$idRef]; }
            $html .= '<tr>'
                . '<td>' . htmlspecialchars($nombre) . '</td>'
                . '<td>' . htmlspecialchars((string)($r['tipo_usuario'] ?? '')) . '</td>'
                . '<td>' . htmlspecialchars((string)($r['tipo_movimiento'] ?? '')) . '</td>'
                . '<td>' . htmlspecialchars((string)($r['fecha_hora'] ?? '')) . '</td>'
                . '</tr>';
        }
        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('reporte_accesos.pdf');
    }

    public function exportExcel()
    {
        [$filtros, $periodo, $desde, $hasta, $agrupacion] = $this->collectFiltersFromQuery();
        $registros = $this->registroModel->obtenerRegistrosFiltrados($filtros);

        // Mapear nombres
        $empleados = $this->empleadoModel->obtenerTodos() ?? [];
        $clientes  = $this->clienteModel->obtenerTodos() ?? [];
        $mapEmp = [];
        foreach ($empleados as $e) { $mapEmp[(string)$e['id_empleado']] = $e['nombre']; }
        $mapCli = [];
        foreach ($clientes as $c) { $mapCli[(string)$c['id_cliente']] = $c['nombre']; }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Usuario');
        $sheet->setCellValue('B1', 'Tipo Usuario');
        $sheet->setCellValue('C1', 'Movimiento');
        $sheet->setCellValue('D1', 'Fecha y hora');

        $row = 2;
        foreach ($registros as $r) {
            $tipoU = (string)($r['tipo_usuario'] ?? '');
            $idRef = (string)($r['id_referencia'] ?? '');
            $nombre = 'Desconocido';
            if ($tipoU === 'empleado' && isset($mapEmp[$idRef])) { $nombre = $mapEmp[$idRef]; }
            if ($tipoU === 'cliente'  && isset($mapCli[$idRef])) { $nombre = $mapCli[$idRef]; }
            $sheet->setCellValue('A' . $row, $nombre);
            $sheet->setCellValue('B' . $row, (string)($r['tipo_usuario'] ?? ''));
            $sheet->setCellValue('C' . $row, (string)($r['tipo_movimiento'] ?? ''));
            $sheet->setCellValue('D' . $row, (string)($r['fecha_hora'] ?? ''));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte_accesos.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
    }

    private function buildAggregations(array $registros, string $agrupacion)
    {
        $buckets = [];
        foreach ($registros as $r) {
            $ts = strtotime($r['fecha_hora'] ?? '');
            if (!$ts) { continue; }
            switch ($agrupacion) {
                case 'semana':
                    // ISO week format e.g., 2025-W43
                    $label = date('o-\WW', $ts);
                    break;
                case 'mes':
                    $label = date('Y-m', $ts);
                    break;
                case 'dia':
                default:
                    $label = date('Y-m-d', $ts);
                    break;
            }
            if (!isset($buckets[$label])) {
                $buckets[$label] = ['entrada' => 0, 'salida' => 0, 'total' => 0];
            }
            $mov = $r['tipo_movimiento'] ?? '';
            if ($mov === 'entrada') { $buckets[$label]['entrada']++; }
            if ($mov === 'salida')  { $buckets[$label]['salida']++; }
            $buckets[$label]['total']++;
        }
        ksort($buckets);
        $labels = array_keys($buckets);
        $entradas = array_map(fn($v) => $v['entrada'], $buckets);
        $salidas  = array_map(fn($v) => $v['salida'], $buckets);
        $totales  = array_map(fn($v) => $v['total'], $buckets);
        return [$labels, $entradas, $salidas, $totales];
    }

    private function buildTopByUsuario(array $registros, string $tipoUsuario, array $mapNombres, int $limit = 10)
    {
        $counts = [];
        foreach ($registros as $r) {
            if (($r['tipo_usuario'] ?? '') !== $tipoUsuario) continue;
            $id = (string)($r['id_referencia'] ?? '');
            if ($id === '') continue;
            $counts[$id] = ($counts[$id] ?? 0) + 1;
        }
        // Sort desc and slice
        arsort($counts);
        $counts = array_slice($counts, 0, $limit, true);
        $labels = [];
        $values = [];
        foreach ($counts as $id => $cnt) {
            $labels[] = $mapNombres[$id] ?? ('ID ' . $id);
            $values[] = $cnt;
        }
        return [$labels, $values];
    }

    private function buildAggregationsByUsuario(array $registros, string $agrupacion)
    {
        $buckets = [];
        foreach ($registros as $r) {
            $ts = strtotime($r['fecha_hora'] ?? '');
            if (!$ts) continue;
            switch ($agrupacion) {
                case 'semana': $label = date('o-\WW', $ts); break;
                case 'mes':    $label = date('Y-m', $ts);   break;
                case 'dia':
                default:       $label = date('Y-m-d', $ts); break;
            }
            if (!isset($buckets[$label])) { $buckets[$label] = ['empleado'=>0, 'cliente'=>0]; }
            $tipoU = $r['tipo_usuario'] ?? '';
            if ($tipoU === 'empleado') $buckets[$label]['empleado']++;
            if ($tipoU === 'cliente')  $buckets[$label]['cliente']++;
        }
        ksort($buckets);
        $emp = [];
        $cli = [];
        foreach ($buckets as $label => $vals) {
            $emp[] = $vals['empleado'];
            $cli[] = $vals['cliente'];
        }
        return [$emp, $cli];
    }
}
