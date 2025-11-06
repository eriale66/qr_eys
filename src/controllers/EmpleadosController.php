<?php
require_once __DIR__ . '/../models/empleadoModel.php';
require_once __DIR__ . '/../utils/CSRF.php';
require_once __DIR__ . '/../utils/FileHelper.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class EmpleadoController
{
    private $empleadoModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
    }

    public function index()
    {
        $empleados = $this->empleadoModel->obtenerTodos();
        include __DIR__ . '/../views/dashboard/empleados.php';
    }

    public function generarQR($id_empleado)
    {
        $empleado = $this->empleadoModel->obtenerPorId($id_empleado);
        if (!$empleado) {
            echo "Empleado no encontrado";
            return;
        }

        $builder = new Builder();
        $result = $builder->build(
            writer: new PngWriter(),
            data: (string)($empleado['codigo_qr'] ?? ''),
            encoding: new Encoding('UTF-8'),
            size: 200,
            margin: 10
        );

        $path = __DIR__ . "/../../public/qr_empleados/";
        if (!file_exists($path)) mkdir($path, 0755, true);

        // Sanitizar nombre de archivo para prevenir path traversal
        $safeFilename = FileHelper::sanitizeFilename($empleado['nombre'], 'png');
        $filePath = $path . $safeFilename;

        $result->saveToFile($filePath);

        header("Location: /qr_eys/public/empleados?type=success&msg=" . urlencode("QR generado correctamente para {$empleado['nombre']}"));
        exit;
    }

    public function exportarPDF()
    {
        $empleados = $this->empleadoModel->obtenerTodos();
        $html = '<h2>Lista de Empleados</h2><table border="1" cellpadding="8"><tr><th>ID</th><th>Nombre</th><th>Puesto</th><th>Correo</th><th>Teléfono</th></tr>';
        foreach ($empleados as $e) {
            $html .= "<tr>
                <td>" . htmlspecialchars($e['id_empleado'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($e['nombre'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($e['puesto'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($e['correo'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($e['telefono'], ENT_QUOTES, 'UTF-8') . "</td>
            </tr>";
        }
        $html .= '</table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("empleados.pdf");
    }

    public function exportarExcel()
    {
        $empleados = $this->empleadoModel->obtenerTodos();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Puesto');
        $sheet->setCellValue('D1', 'Correo');
        $sheet->setCellValue('E1', 'Teléfono');

        $row = 2;
        foreach ($empleados as $e) {
            $sheet->setCellValue("A$row", $e['id_empleado']);
            $sheet->setCellValue("B$row", $e['nombre']);
            $sheet->setCellValue("C$row", $e['puesto']);
            $sheet->setCellValue("D$row", $e['correo']);
            $sheet->setCellValue("E$row", $e['telefono']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'empleados.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
    }

    public function mostrarFormularioAgregar()
    {
        include __DIR__ . '/../views/dashboard/agregar_empleado.php';
    }

    public function guardarEmpleado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            CSRF::validateOrDie();

            $nombre = $_POST['nombre'] ?? '';
            $puesto = $_POST['puesto'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';

            // Validar campos
            if (empty($nombre) || empty($puesto) || empty($correo) || empty($telefono)) {
                header("Location: /qr_eys/public/empleados/agregar?type=error&msg=" . urlencode('Todos los campos son obligatorios'));
                exit;
            }

            // Validar email
            if (!Validator::validateEmail($correo)) {
                header("Location: /qr_eys/public/empleados/agregar?type=error&msg=" . urlencode('El email no es válido'));
                exit;
            }

            // Validar teléfono
            if (!Validator::validatePhone($telefono)) {
                header("Location: /qr_eys/public/empleados/agregar?type=error&msg=" . urlencode('El teléfono no es válido'));
                exit;
            }

            // Generar un código QR único
            $codigoQR = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

            // Guardar en la base de datos
            $this->empleadoModel->insertarEmpleado($nombre, $puesto, $correo, $telefono, $codigoQR);

            header("Location: /qr_eys/public/empleados?type=success&msg=" . urlencode('Empleado agregado correctamente'));
            exit;

        }
    }

    public function mostrarFormularioEditar($id)
    {
        $empleado = $this->empleadoModel->obtenerPorId($id);
        include __DIR__ . '/../views/dashboard/editar_empleado.php';
    }

    public function actualizarEmpleado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            CSRF::validateOrDie();

            $id = $_POST['id_empleado'] ?? '';
            $nombre = trim($_POST['nombre'] ?? '');
            $puesto = trim($_POST['puesto'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');

            if (empty($id) || empty($nombre) || empty($puesto) || empty($correo) || empty($telefono)) {
                header("Location: /qr_eys/public/empleados?type=error&msg=" . urlencode('Todos los campos son obligatorios'));
                exit;
            }

            // Validar email
            if (!Validator::validateEmail($correo)) {
                header("Location: /qr_eys/public/empleados?type=error&msg=" . urlencode('El email no es válido'));
                exit;
            }

            // Validar teléfono
            if (!Validator::validatePhone($telefono)) {
                header("Location: /qr_eys/public/empleados?type=error&msg=" . urlencode('El teléfono no es válido'));
                exit;
            }

            // Obtener datos actuales del empleado
            $empleadoActual = $this->empleadoModel->obtenerPorId($id);
            if (!$empleadoActual) {
                header("Location: /qr_eys/public/empleados?type=error&msg=" . urlencode('Empleado no encontrado'));
                exit;
            }

            // Guardar el nombre actual del archivo QR
            $qrDir = __DIR__ . "/../../public/qr_empleados/";

            // Sanitizar nombres de archivos
            $oldSafeFilename = FileHelper::sanitizeFilename($empleadoActual['nombre'], 'png');
            $newSafeFilename = FileHelper::sanitizeFilename($nombre, 'png');

            $oldQRPath = $qrDir . $oldSafeFilename;
            $newQRPath = $qrDir . $newSafeFilename;

            // Actualizar en la base de datos
            $this->empleadoModel->actualizarEmpleado($id, $nombre, $puesto, $correo, $telefono);

            // Si el archivo QR existe y el nombre cambió, renombrarlo
            if ($oldSafeFilename !== $newSafeFilename && file_exists($oldQRPath)) {
                rename($oldQRPath, $newQRPath);
            }

            header("Location: /qr_eys/public/empleados?type=success&msg=" . urlencode('Empleado actualizado correctamente'));
            exit;
        }
    }

    public function eliminarEmpleado($id = null)
    {
        // Si viene de POST, validar CSRF y obtener ID
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::validateOrDie();
            $id = $_POST['id'] ?? null;
        }

        $empleado = $this->empleadoModel->obtenerPorId($id);

        if (!$empleado) {
            header("Location: /qr_eys/public/empleados?type=error&msg=" . urlencode('Empleado no encontrado'));
            exit;
        }

        // Ruta del archivo QR correspondiente (sanitizada)
        $safeFilename = FileHelper::sanitizeFilename($empleado['nombre'], 'png');
        $qrPath = __DIR__ . "/../../public/qr_empleados/" . $safeFilename;

        // Eliminar empleado de la base de datos
        if ($this->empleadoModel->eliminarEmpleado($id)) {
            // Si existe un QR, eliminarlo del servidor
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }
            header("Location: /qr_eys/public/empleados?type=success&msg=" . urlencode('Empleado eliminado correctamente'));
            exit;
        } else {
            header("Location: /qr_eys/public/empleados?type=error&msg=" . urlencode('Error al eliminar empleado'));
            exit;
        }
    }
}
