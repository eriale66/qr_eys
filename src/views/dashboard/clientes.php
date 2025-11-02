<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes | Renlo</title>
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <link rel="stylesheet" href="../public/css/empleados.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../public/js/alerts.js" defer></script>
  <script src="../public/js/dashboards.js" defer></script>
  <script src="../public/js/empleados.js" defer></script>
</head>

<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <h2>Renlo</h2>
      <p>Control de Acceso</p>
    </div>
    <nav>
      <a href="/qr_eys/public/dashboard" class="active"> Inicio</a>
      <a href="/qr_eys/public/empleados"> Empleados</a>
      <a href="/qr_eys/public/clientes"> Clientes</a>
      <!-- <a href="/qr_eys/public/citas"> Citas</a> -->
      <a href="/qr_eys/public/reportes"> Reportes</a>
      <a href="/qr_eys/public/administracion"> Administración</a>
      <a href="/qr_eys/public/configuracion"> Configuración</a>
    </nav>
    <div class="logout">
      <a href="/qr_eys/public/logout">Cerrar sesión</a>
    </div>
  </aside>

  <main class="main-content">
    <header>
      <h1>Gestión de Clientes</h1>
    </header>

    <div class="container">
      <div class="panel-info">
        <div class="info-box">
          <h4>Total de clientes</h4>
          <p><?= count($clientes) ?></p>
        </div>
      </div>

      <div class="toolbar">
        <div class="left">
          <a href="/qr_eys/public/clientes/agregar" class="btn primary">➕ Agregar Cliente</a>
          <a href="/qr_eys/public/clientes/pdf" class="btn danger">📄 Exportar PDF</a>
          <a href="/qr_eys/public/clientes/excel" class="btn success">📊 Exportar Excel</a>
        </div>
        <div class="right">
          <input type="text" id="buscador" placeholder="Buscar cliente...">
        </div>
      </div>

      <table class="tabla">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>QR Generado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          foreach ($clientes as $c): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($c['nombre']) ?></td>
              <td><?= htmlspecialchars($c['correo']) ?></td>
              <td><?= htmlspecialchars($c['telefono']) ?></td>
              <td style="text-align:center;">
                <?php
                $qrPath = "/qr_eys/public/qr_clientes/" . rawurlencode($c['nombre']) . ".png";
                $filePath = $_SERVER['DOCUMENT_ROOT'] . "/qr_eys/public/qr_clientes/" . $c['nombre'] . ".png";

                if (file_exists($filePath)): ?>
                  <a href="<?= $qrPath ?>" download="<?= $c['nombre'] ?>.png" title="Descargar QR">
                    <img src="<?= $qrPath ?>" alt="QR <?= htmlspecialchars($c['nombre']) ?>" width="70" height="70">
                  </a>
                <?php else: ?>
                  <em style="color:#aaa;">Aún no tiene QR</em>
                <?php endif; ?>
              </td>
              <td class="acciones">
                <a href="/qr_eys/public/clientes/generarQR?id=<?= $c['id_cliente'] ?>" class="btn small">🎟 QR</a>
                <a href="/qr_eys/public/clientes/editar?id=<?= $c['id_cliente'] ?>" class="btn edit">✏️ Editar</a>
                <a href="/qr_eys/public/clientes/eliminar?id=<?= $c['id_cliente'] ?>"
                  onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars($c['nombre']) ?>?')"
                  class="btn danger small">🗑 Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('a[href*="/clientes/eliminar"]').forEach(link => {
        try { link.removeAttribute('onclick'); } catch (_) {}
        link.addEventListener('click', function (e) {
          e.preventDefault();
          const nombre = this.closest('tr')?.children[1]?.textContent.trim() || 'este cliente';
          confirmarEliminacion(nombre).then(c => { if (c) window.location.href = this.href; });
        });
      });
    });
  </script>
</body>

</html>
