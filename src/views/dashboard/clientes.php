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
      <a href="/qr_eys/public/dashboard">Inicio</a>
      <a href="/qr_eys/public/empleados">Empleados</a>
      <a href="/qr_eys/public/clientes" class="active">Clientes</a>
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
    <header class="page-header">
      <div>
        <h1>Gestión de Clientes</h1>
        <p class="subtitle">Administra y controla tus clientes</p>
      </div>
      <div class="header-stats">
        <div class="stat-badge">
          <span class="stat-icon">👤</span>
          <div>
            <small>Total</small>
            <strong><?= count($clientes) ?></strong>
          </div>
        </div>
      </div>
    </header>

    <section class="container">
      <div class="toolbar-advanced">
        <div class="toolbar-left">
          <a href="/qr_eys/public/clientes/agregar" class="btn-action primary">
            <span class="btn-icon">➕</span>
            <span class="btn-text">Nuevo Cliente</span>
          </a>
          <a href="/qr_eys/public/clientes/pdf" class="btn-action pdf">
            <span class="btn-icon">📄</span>
            <span class="btn-text">PDF</span>
          </a>
          <a href="/qr_eys/public/clientes/excel" class="btn-action excel">
            <span class="btn-icon">📊</span>
            <span class="btn-text">Excel</span>
          </a>
        </div>
        <div class="toolbar-right">
          <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" id="buscador" placeholder="Buscar cliente...">
          </div>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="tabla-moderna">
          <thead>
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Contacto</th>
              <th>Código QR</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            foreach ($clientes as $c): ?>
              <tr class="table-row">
                <td class="row-number"><?= $i++ ?></td>
                <td>
                  <div class="employee-info">
                    <div class="employee-avatar" style="background: linear-gradient(135deg, #fd7e14, #e85d04);">
                      <?= strtoupper(substr($c['nombre'], 0, 2)) ?>
                    </div>
                    <strong><?= htmlspecialchars($c['nombre']) ?></strong>
                  </div>
                </td>
                <td>
                  <div class="contact-info">
                    <div class="contact-item">
                      <span class="contact-icon">📧</span>
                      <span><?= htmlspecialchars($c['correo']) ?></span>
                    </div>
                    <div class="contact-item">
                      <span class="contact-icon">📱</span>
                      <span><?= htmlspecialchars($c['telefono']) ?></span>
                    </div>
                  </div>
                </td>
                <td class="qr-cell">
                  <?php
                  $qrPath = "/qr_eys/public/qr_clientes/" . rawurlencode($c['nombre']) . ".png";
                  $filePath = $_SERVER['DOCUMENT_ROOT'] . "/qr_eys/public/qr_clientes/" . $c['nombre'] . ".png";

                  if (file_exists($filePath)): ?>
                    <div class="qr-container">
                      <a href="<?= $qrPath ?>" download="<?= $c['nombre'] ?>.png" title="Descargar QR" class="qr-download">
                        <img src="<?= $qrPath ?>" alt="QR <?= htmlspecialchars($c['nombre']) ?>" class="qr-image">
                        <div class="qr-overlay">
                          <span>⬇️ Descargar</span>
                        </div>
                      </a>
                    </div>
                  <?php else: ?>
                    <div class="qr-pending">
                      <span class="pending-icon">⏳</span>
                      <span>Sin generar</span>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="action-cell">
                  <div class="action-buttons">
                    <a href="/qr_eys/public/clientes/generarQR?id=<?= $c['id_cliente'] ?>"
                      class="btn-icon-action qr"
                      title="Generar QR">
                      🎟️
                    </a>
                    <a href="/qr_eys/public/clientes/editar?id=<?= $c['id_cliente'] ?>"
                      class="btn-icon-action edit"
                      title="Editar">
                      ✏️
                    </a>
                    <a href="/qr_eys/public/clientes/eliminar?id=<?= $c['id_cliente'] ?>"
                      class="btn-icon-action delete delete-cliente"
                      title="Eliminar"
                      data-nombre="<?= htmlspecialchars($c['nombre']) ?>">
                      🗑️
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.delete-cliente').forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const nombre = this.getAttribute('data-nombre') || 'este cliente';
          confirmarEliminacion(nombre).then(c => {
            if (c) window.location.href = this.href;
          });
        });
      });
    });
  </script>
</body>

</html>