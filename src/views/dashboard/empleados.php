<?php
require_once __DIR__ . '/../../utils/CSRF.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Empleados | Renlo</title>
  <script>
    // Cargar tema inmediatamente para evitar flash
    (function() {
      const savedTheme = localStorage.getItem('theme') || 'dark';
      document.documentElement.setAttribute('data-theme', savedTheme);
    })();
  </script>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    referrerpolicy="no-referrer" />
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
      <a href="/qr_eys/public/dashboard">
        <i class="fa-solid fa-house"></i>
        <span>Inicio</span>
      </a>
      <a href="/qr_eys/public/empleados" class="active">
        <i class="fa-solid fa-user-tie"></i>
        <span>Empleados</span>
      </a>
      <a href="/qr_eys/public/clientes">
        <i class="fa-solid fa-users"></i>
        <span>Clientes</span>
      </a>
      <a href="/qr_eys/public/reportes">
        <i class="fa-solid fa-chart-line"></i>
        <span>Reportes</span>
      </a>
      <a href="/qr_eys/public/administracion">
        <i class="fa-solid fa-user-shield"></i>
        <span>Administración</span>
      </a>
    </nav>
    <div class="theme-toggle">
      <button class="theme-toggle-btn" id="themeToggle">
        <span id="themeText">Modo Claro</span>
        <i class="fa-solid fa-sun" id="themeIcon"></i>
      </button>
    </div>
    <div class="logout">
      <a href="/qr_eys/public/logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Cerrar sesión</span>
      </a>
    </div>
  </aside>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <main class="main-content">
    <header class="page-header">
      <div>
        <h1>Gestión de Empleados</h1>
        <p class="subtitle">Administra y controla el personal de tu organización</p>
      </div>
      <div class="header-stats">
        <div class="stat-badge">
          <span class="stat-icon"><i class="fa-solid fa-user"></i></span>
          <div>
            <small>Total</small>
            <strong><?= count($empleados) ?></strong>
          </div>
        </div>
      </div>
    </header>

    <section class="container">
      <div class="toolbar-advanced">
        <div class="toolbar-left">
          <a href="/qr_eys/public/empleados/agregar" class="btn-action primary">
            <span class="btn-icon"><i class="fa-solid fa-plus"></i></span>
            <span class="btn-text">Nuevo Empleado</span>
          </a>
          <a href="/qr_eys/public/empleados/pdf" class="btn-action pdf">
            <span class="btn-icon"><i class="fa-solid fa-file-pdf"></i></span>
            <span class="btn-text">PDF</span>
          </a>
          <a href="/qr_eys/public/empleados/excel" class="btn-action excel">
            <span class="btn-icon"><i class="fa-solid fa-file-excel"></i></span>
            <span class="btn-text">Excel</span>
          </a>
        </div>
        <div class="toolbar-right">
          <div class="search-box">
            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" id="buscador" placeholder="Buscar empleado...">
          </div>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="tabla-moderna">
          <thead>
            <tr>
              <th>#</th>
              <th>Empleado</th>
              <th>Puesto</th>
              <th>Contacto</th>
              <th>Código QR</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            foreach ($empleados as $e): ?>
              <tr class="table-row">
                <td class="row-number"><?= $i++ ?></td>
                <td>
                  <div class="employee-info">
                    <div class="employee-avatar"><?= strtoupper(substr($e['nombre'], 0, 2)) ?></div>
                    <strong><?= htmlspecialchars($e['nombre']) ?></strong>
                  </div>
                </td>
                <td>
                  <span class="badge-puesto"><?= htmlspecialchars($e['puesto']) ?></span>
                </td>
                <td>
                  <div class="contact-info">
                    <div class="contact-item">
                      <span class="contact-icon"><i class="fa-solid fa-envelope"></i></span>
                      <span><?= htmlspecialchars($e['correo']) ?></span>
                    </div>
                    <div class="contact-item">
                      <span class="contact-icon"><i class="fa-solid fa-phone"></i></span>
                      <span><?= htmlspecialchars($e['telefono']) ?></span>
                    </div>
                  </div>
                </td>
                <td class="qr-cell">
                  <?php
                  $qrPath = "/qr_eys/public/qr_empleados/" . rawurlencode($e['nombre']) . ".png";
                  $filePath = $_SERVER['DOCUMENT_ROOT'] . "/qr_eys/public/qr_empleados/" . $e['nombre'] . ".png";

                  if (file_exists($filePath)): ?>
                    <div class="qr-container">
                      <a href="<?= htmlspecialchars($qrPath, ENT_QUOTES, 'UTF-8') ?>" download="<?= htmlspecialchars($e['nombre'], ENT_QUOTES, 'UTF-8') ?>.png" title="Descargar QR" class="qr-download">
                        <img src="<?= htmlspecialchars($qrPath, ENT_QUOTES, 'UTF-8') ?>" alt="QR <?= htmlspecialchars($e['nombre']) ?>" class="qr-image">
                        <div class="qr-overlay">
                          <span><i class="fa-solid fa-download"></i>Descargar</span>
                        </div>
                      </a>
                    </div>
                  <?php else: ?>
                    <div class="qr-pending">
                      <span class="pending-icon"><i class="fa-solid fa-hourglass-half"></i></span>
                      <span>Sin generar</span>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="action-cell">
                  <div class="action-buttons">
                    <a href="/qr_eys/public/empleados/generarQR?id=<?= $e['id_empleado'] ?>" class="btn-icon-action qr" title="Generar QR">
                      <i class="fa-solid fa-qrcode"></i>
                    </a>
                    <a href="/qr_eys/public/empleados/editar?id=<?= $e['id_empleado'] ?>" class="btn-icon-action edit" title="Editar">
                      <i class="fa-solid fa-pen"></i>
                    </a>
                    <form method="POST" action="/qr_eys/public/empleados/eliminar" style="display: inline;" class="form-eliminar-empleado" data-nombre="<?= htmlspecialchars($e['nombre'], ENT_QUOTES) ?>">
                      <?= CSRF::inputField() ?>
                      <input type="hidden" name="id" value="<?= $e['id_empleado'] ?>">
                      <button type="button" class="btn-icon-action delete btn-eliminar" title="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      </div>
  </main>

  <script>
    // Manejar eliminación con SweetAlert2
    document.querySelectorAll('.form-eliminar-empleado .btn-eliminar').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const nombre = form.dataset.nombre;

        confirmarEliminacion(nombre).then(confirmed => {
          if (confirmed) {
            form.submit();
          }
        });
      });
    });
  </script>

</body>

</html>