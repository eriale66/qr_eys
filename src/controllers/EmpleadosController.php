<?php
require_once __DIR__ . '/../models/empleadoModel.php';
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
        if (!file_exists($path)) mkdir($path, 0777, true);
        $filePath = $path . $empleado['nombre'] . ".png";
        $result->saveToFile($filePath);

        echo "<script>alert('QR generado correctamente para {$empleado['nombre']}'); window.location.href='/qr_eys/public/empleados';</script>";
    }

    public function exportarPDF()
    {
        $empleados = $this->empleadoModel->obtenerTodos();
        $html = '<h2>Lista de Empleados</h2><table border="1" cellpadding="8"><tr><th>ID</th><th>Nombre</th><th>Puesto</th><th>Correo</th><th>Teléfono</th></tr>';
        foreach ($empleados as $e) {
            $html .= "<tr>
                <td>{$e['id_empleado']}</td>
                <td>{$e['nombre']}</td>
                <td>{$e['puesto']}</td>
                <td>{$e['correo']}</td>
                <td>{$e['telefono']}</td>
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
            $nombre = $_POST['nombre'] ?? '';
            $puesto = $_POST['puesto'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';

            if (empty($nombre) || empty($puesto) || empty($correo) || empty($telefono)) {
                echo "<script>alert('Todos los campos son obligatorios'); window.location.href='/qr_eys/public/empleados/agregar';</script>";
                return;
            }

            // Generar un código QR único
            $codigoQR = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

            // Guardar en la base de datos
            $this->empleadoModel->insertarEmpleado($nombre, $puesto, $correo, $telefono, $codigoQR);

            echo "<script>alert('Empleado agregado correctamente'); window.location.href='/qr_eys/public/empleados';</script>";
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
            $id = $_POST['id_empleado'] ?? '';
            $nombre = trim($_POST['nombre'] ?? '');
            $puesto = trim($_POST['puesto'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');

            if (empty($id) || empty($nombre) || empty($puesto) || empty($correo) || empty($telefono)) {
                echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
                return;
            }

            // Obtener datos actuales del empleado
            $empleadoActual = $this->empleadoModel->obtenerPorId($id);
            if (!$empleadoActual) {
                echo "<script>alert('Empleado no encontrado'); window.location.href='/qr_eys/public/empleados';</script>";
                return;
            }

            // Guardar el nombre actual del archivo QR
            $qrDir = __DIR__ . "/../../public/qr_empleados/";
            $oldName = $empleadoActual['nombre'];
            $oldQRPath = $qrDir . $oldName . ".png";
            $newQRPath = $qrDir . $nombre . ".png";

            // Actualizar en la base de datos
            $this->empleadoModel->actualizarEmpleado($id, $nombre, $puesto, $correo, $telefono);

            // Si el archivo QR existe y el nombre cambió, renombrarlo
            if ($oldName !== $nombre && file_exists($oldQRPath)) {
                rename($oldQRPath, $newQRPath);
            }

            echo "<script>alert('Empleado actualizado correctamente'); window.location.href='/qr_eys/public/empleados';</script>";
        }
    }

    public function eliminarEmpleado($id)
    {
        $empleado = $this->empleadoModel->obtenerPorId($id);

        if (!$empleado) {
            echo "<script>alert('Empleado no encontrado'); window.location.href='/qr_eys/public/empleados';</script>";
            return;
        }

        // Ruta del archivo QR correspondiente
        $qrPath = __DIR__ . "/../../public/qr_empleados/" . $empleado['nombre'] . ".png";

        // Eliminar empleado de la base de datos
        if ($this->empleadoModel->eliminarEmpleado($id)) {
            // Si existe un QR, eliminarlo del servidor
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }
            echo "<script>alert('Empleado eliminado correctamente'); window.location.href='/qr_eys/public/empleados';</script>";
        } else {
            echo "<script>alert('Error al eliminar empleado'); window.location.href='/qr_eys/public/empleados';</script>";
        }
    }
}
