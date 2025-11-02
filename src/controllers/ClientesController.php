<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class ClienteController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new ClienteModel();
    }

    public function index() {
        $clientes = $this->clienteModel->obtenerTodos();
        include __DIR__ . '/../views/dashboard/clientes.php';
    }

    public function mostrarFormularioAgregar() {
        include __DIR__ . '/../views/dashboard/agregar_cliente.php';
    }

    public function guardarCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';

            if (empty($nombre) || empty($correo) || empty($telefono)) {
                header("Location: /qr_eys/public/clientes?type=error&msg=" . urlencode('Todos los campos son obligatorios'));
                exit;
            }

            $codigoQR = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $this->clienteModel->insertarCliente($nombre, $correo, $telefono, $codigoQR);

            header("Location: /qr_eys/public/clientes?type=success&msg=" . urlencode('Cliente agregado correctamente'));
            exit;
        }
    }

    // public function generarQR($id_cliente) {
    //     $cliente = $this->clienteModel->obtenerPorId($id_cliente);
    //     if (!$cliente) {
    //         echo "Cliente no encontrado";
    //         return;
    //     }

    //     $qr = QrCode::create($cliente['codigo_qr'])->setSize(200)->setMargin(10);
    //     $writer = new PngWriter();
    //     $result = $writer->write($qr);

    //     $path = __DIR__ . "/../../public/qr_clientes/";
    //     if (!file_exists($path)) mkdir($path, 0777, true);
    //     $filePath = $path . $cliente['nombre'] . ".png";
    //     $result->saveToFile($filePath);

    //     echo "<script>alert('QR generado correctamente para {$cliente['nombre']}'); window.location.href='/qr_eys/public/clientes';</script>";
    // }

    public function generarQR($id_cliente)
    {
        $cliente = $this->clienteModel->obtenerPorId($id_cliente);
        if (!$cliente) {
            header("Location: /qr_eys/public/clientes?type=error&msg=" . urlencode('Cliente no encontrado'));
            exit;
        }

        $builder = new Builder();
        $result = $builder->build(
            writer: new PngWriter(),
            data: (string)($cliente['codigo_qr'] ?? ''),
            encoding: new Encoding('UTF-8'),
            size: 200,
            margin: 10
        );

        $path = __DIR__ . "/../../public/qr_clientes/";
        if (!file_exists($path)) mkdir($path, 0777, true);
        $filePath = $path . $cliente['nombre'] . ".png";
        $result->saveToFile($filePath);

        header("Location: /qr_eys/public/clientes?type=success&msg=" . urlencode("QR generado correctamente para {$cliente['nombre']}"));
        exit;
    }

    public function mostrarFormularioEditar($id) {
        $cliente = $this->clienteModel->obtenerPorId($id);
        include __DIR__ . '/../views/dashboard/editar_cliente.php';
    }

    public function actualizarCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_cliente'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';

            if (empty($id) || empty($nombre) || empty($correo) || empty($telefono)) {
                header("Location: /qr_eys/public/clientes?type=error&msg=" . urlencode('Todos los campos son obligatorios'));
                exit;
            }

            $clienteActual = $this->clienteModel->obtenerPorId($id);
            if (!$clienteActual) {
                header("Location: /qr_eys/public/clientes?type=error&msg=" . urlencode('Cliente no encontrado'));
                exit;
            }

            $qrDir = __DIR__ . "/../../public/qr_clientes/";
            $oldName = $clienteActual['nombre'];
            $oldQRPath = $qrDir . $oldName . ".png";
            $newQRPath = $qrDir . $nombre . ".png";

            $this->clienteModel->actualizarCliente($id, $nombre, $correo, $telefono);

            if ($oldName !== $nombre && file_exists($oldQRPath)) {
                rename($oldQRPath, $newQRPath);
            }

            header("Location: /qr_eys/public/clientes?type=success&msg=" . urlencode('Cliente actualizado correctamente'));
            exit;
        }
    }

    public function eliminarCliente($id) {
        $cliente = $this->clienteModel->obtenerPorId($id);

        if (!$cliente) {
            header("Location: /qr_eys/public/clientes?type=error&msg=" . urlencode('Cliente no encontrado'));
            exit;
        }

        $qrPath = __DIR__ . "/../../public/qr_clientes/" . $cliente['nombre'] . ".png";

        if ($this->clienteModel->eliminarCliente($id)) {
            if (file_exists($qrPath)) unlink($qrPath);
            header("Location: /qr_eys/public/clientes?type=success&msg=" . urlencode('Cliente eliminado correctamente'));
            exit;
        } else {
            header("Location: /qr_eys/public/clientes?type=error&msg=" . urlencode('Error al eliminar cliente'));
            exit;
        }
    }

    public function exportarPDF() {
        $clientes = $this->clienteModel->obtenerTodos();
        $html = '<h2>Lista de Clientes</h2><table border="1" cellpadding="8"><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th></tr>';
        foreach ($clientes as $c) {
            $html .= "<tr>
                <td>{$c['id_cliente']}</td>
                <td>{$c['nombre']}</td>
                <td>{$c['correo']}</td>
                <td>{$c['telefono']}</td>
            </tr>";
        }
        $html .= '</table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("clientes.pdf");
    }

    public function exportarExcel() {
        $clientes = $this->clienteModel->obtenerTodos();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Correo');
        $sheet->setCellValue('D1', 'Teléfono');

        $row = 2;
        foreach ($clientes as $c) {
            $sheet->setCellValue("A$row", $c['id_cliente']);
            $sheet->setCellValue("B$row", $c['nombre']);
            $sheet->setCellValue("C$row", $c['correo']);
            $sheet->setCellValue("D$row", $c['telefono']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'clientes.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
    }
}
