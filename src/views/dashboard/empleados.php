<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Empleados | Renlo</title>
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <link rel="stylesheet" href="../public/css/empleados.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../public/js/alerts.js" defer></script>
  <script src="../public/js/dashboards.js" defer></script>
  <script src="../public/js/empleados.js" defer></script>
</head>

<body>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="brand">
      <h2>Renlo</h2>
      <p>Control de Acceso</p>
    </div>
    <nav>
      <a href="/qr_eys/public/dashboard">Inicio</a>
      <a href="/qr_eys/public/empleados" class="active">Empleados</a>
      <a href="/qr_eys/public/clientes">Clientes</a>
      <!-- <a href="/qr_eys/public/citas">Citas</a> -->
      <a href="/qr_eys/public/reportes">Reportes</a>
      <a href="/qr_eys/public/administracion">Administración</a>
      <a href="/qr_eys/public/configuracion">Configuración</a>
    </nav>
    <div class="logout">
      <a href="/qr_eys/public/logout">Cerrar sesión</a>
    </div>
  </aside>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <main class="main-content">
    <header>
      <h1>Gestión de Empleados</h1>
    </header>

    <div class="container">
      <div class="panel-info">
        <div class="info-box">
          <h4>Total de empleados</h4>
          <p><?= count($empleados) ?></p>
        </div>
      </div>

      <div class="toolbar">
        <div class="left">
          <a href="/qr_eys/public/empleados/agregar" class="btn primary">➕ Agregar Empleado</a>
          <a href="/qr_eys/public/empleados/pdf" class="btn danger">📄 Exportar PDF</a>
          <a href="/qr_eys/public/empleados/excel" class="btn success">📊 Exportar Excel</a>
        </div>
        <div class="right">
          <input type="text" id="buscador" placeholder="Buscar empleado...">
        </div>
      </div>

      <table class="tabla">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Puesto</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>QR Generado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          foreach ($empleados as $e): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($e['nombre']) ?></td>
              <td><?= htmlspecialchars($e['puesto']) ?></td>
              <td><?= htmlspecialchars($e['correo']) ?></td>
              <td><?= htmlspecialchars($e['telefono']) ?></td>

              <td style="text-align:center;">
                <?php
                $qrPath = "/qr_eys/public/qr_empleados/" . rawurlencode($e['nombre']) . ".png";
                $filePath = $_SERVER['DOCUMENT_ROOT'] . "/qr_eys/public/qr_empleados/" . $e['nombre'] . ".png";

                if (file_exists($filePath)): ?>
                  <a href="<?= $qrPath ?>" download="<?= $e['nombre'] ?>.png" title="Descargar QR">
                    <img src="<?= $qrPath ?>" alt="QR <?= htmlspecialchars($e['nombre']) ?>" width="70" height="70">
                  </a>
                <?php else: ?>
                  <em style="color:#aaa;">Aún no tiene QR</em>
                <?php endif; ?>
              </td>

              <td class="acciones">
                <a href="/qr_eys/public/empleados/generarQR?id=<?= $e['id_empleado'] ?>" class="btn small">🎟 QR</a>
                <a href="/qr_eys/public/empleados/editar?id=<?= $e['id_empleado'] ?>" class="btn edit">✏️ Editar</a>
                <a href="/qr_eys/public/empleados/eliminar?id=<?= $e['id_empleado'] ?>"
                  onclick="event.preventDefault(); confirmarEliminacion('<?= htmlspecialchars($e['nombre']) ?>').then(c=>{ if(c) window.location.href=this.href; });"
                  class="btn danger small">🗑 Eliminar</a>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

</body>

</html>
