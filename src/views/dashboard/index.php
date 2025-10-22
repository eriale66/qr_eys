<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control | Renlo</title>
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <!-- <link rel="stylesheet" href="../public/css/main.css"> -->
  <script src="../public/js/dashboards.js" defer></script>
  <!-- <script src="../public/js/main.js" defer></script> -->
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
      <a href="/qr_eys/public/citas"> Citas</a>
      <a href="/qr_eys/public/reportes"> Reportes</a>
      <a href="/qr_eys/public/configuracion"> Configuración</a>
    </nav>
    <div class="logout">
      <a href="#">Cerrar sesión</a>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main-content">
    <header>
      <h1>Panel de Control</h1>
    </header>

    <!-- CARDS -->
    <section class="cards">
      <div class="card">
        <h3>Entradas</h3>
        <p><?= $totalEntradas ?></p>
      </div>
      <div class="card">
        <h3>Salidas</h3>
        <p><?= $totalSalidas ?></p>
      </div>
      <div class="card">
        <h3>Clientes Registrados</h3>
        <p><?= $totalClientes ?></p>
      </div>
      <div class="card">
        <h3>Empleados Activos</h3>
        <p><?= $totalEmpleados ?></p>
      </div>
    </section>

    <!-- ASISTENCIA -->
    <section class="stats">
      <h2>Asistencia</h2>
      <p>Asistencia del Personal:
        <span class="accent">
          <?= $totalEmpleados > 0 ? round(($totalEntradas / $totalEmpleados) * 100, 1) : 0 ?>%
        </span>
      </p>
      <div class="chart">
        <div class="bar present"></div>
        <div class="bar absent"></div>
        <div class="labels">
          <span>Presente</span>
          <span>Ausente</span>
        </div>
      </div>
    </section>

    <!-- REGISTROS -->
    <section class="records">
      <h2>Registros Recientes</h2>
      <table>
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Tipo</th>
            <th>Movimiento</th>
            <th>Fecha y Hora</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($registrosDetallados)): ?>
            <?php foreach ($registrosDetallados as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['nombre']) ?></td>
                <td><?= ucfirst($r['tipo_usuario']) ?></td>
                <td class="<?= $r['tipo_movimiento'] === 'entrada' ? 'status present' : 'status absent' ?>">
                  <?= ucfirst($r['tipo_movimiento']) ?>
                </td>
                <td><?= date('d/m/Y H:i:s', strtotime($r['fecha_hora'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" style="text-align:center;">No hay registros recientes.</td>
            </tr>
          <?php endif; ?>

        </tbody>
      </table>
    </section>

    <!-- PERSONAL DENTRO -->
    <section class="progress-section">
      <h2>Personal Dentro</h2>
      <div class="progress-bar">
        <?php
        $porcentaje = $totalEmpleados > 0 ? round(($totalEntradas / $totalEmpleados) * 100, 1) : 0;
        ?>
        <div class="progress" style="width:<?= $porcentaje ?>%;"></div>
      </div>
      <p><?= $porcentaje ?>% de empleados dentro</p>
    </section>
  </main>

</body>

</html>