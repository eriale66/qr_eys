<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes y Estadísticas | Renlo</title>
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <script src="../public/js/dashboards.js" defer></script>
  <style>
    .container {
      margin-left: 250px;
      padding: 30px
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 30px
    }

    .card {
      background: #1b1f25;
      padding: 20px;
      border-radius: 10px;
      text-align: center
    }

    .accent {
      color: #4cc9f0
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th,
    td {
      padding: 10px
    }

    thead {
      background: #0d6efd;
      color: #fff
    }

    tr:nth-child(odd) {
      background: rgba(255, 255, 255, 0.05)
    }
  </style>
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

  <main class="container">
    <h1>Reportes y Estadísticas</h1>

    <section class="cards">
      <div class="card">
        <h3>Total de registros</h3>
        <p class="accent"><?= isset($registros) ? count($registros) : 0 ?></p>
      </div>
      <div class="card">
        <h3>Total de entradas</h3>
        <p class="accent"><?= $totalEntradas ?? 0 ?></p>
      </div>
      <div class="card">
        <h3>Total de salidas</h3>
        <p class="accent"><?= $totalSalidas ?? 0 ?></p>
      </div>
    </section>

    <section>
      <h2>Últimos movimientos</h2>
      <?php if (!empty($registros)): ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Tipo usuario</th>
              <th>Movimiento</th>
              <th>Fecha y hora</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($registros as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['id_referencia'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['tipo_usuario'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['tipo_movimiento'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['fecha_hora'] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No hay registros disponibles.</p>
      <?php endif; ?>
    </section>
  </main>
</body>

</html>