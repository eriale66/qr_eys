<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Citas | Renlo</title>
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <script src="../public/js/dashboards.js" defer></script>
  <style> .container{margin-left:250px;padding:30px} table{width:100%;border-collapse:collapse} th,td{padding:10px} thead{background:#0d6efd;color:#fff} tr:nth-child(odd){background:rgba(255,255,255,0.05)} </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">
      <h2>Renlo</h2>
      <p>Control de Acceso</p>
    </div>
    <nav>
      <a href="/qr_eys/public/dashboard">Inicio</a>
      <a href="/qr_eys/public/empleados">Empleados</a>
      <a href="/qr_eys/public/clientes">Clientes</a>
      <a href="/qr_eys/public/citas" class="active">Citas</a>
      <a href="/qr_eys/public/reportes">Reportes</a>
      <a href="/qr_eys/public/configuracion">Configuración</a>
    </nav>
    <div class="logout">
      <a href="#">Cerrar sesión</a>
    </div>
  </aside>

  <main class="container">
    <h1>Citas</h1>
    <?php if (!empty($citas)): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Cliente</th><th>Empleado</th><th>Fecha</th><th>Estado</th><th>Observaciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($citas as $ci): ?>
            <tr>
              <td><?= htmlspecialchars($ci['id_cita'] ?? '') ?></td>
              <td><?= htmlspecialchars($ci['cliente'] ?? '') ?></td>
              <td><?= htmlspecialchars($ci['empleado'] ?? '') ?></td>
              <td><?= htmlspecialchars($ci['fecha_cita'] ?? '') ?></td>
              <td><?= htmlspecialchars($ci['estado'] ?? '') ?></td>
              <td><?= htmlspecialchars($ci['observaciones'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No hay citas registradas.</p>
    <?php endif; ?>
  </main>
</body>
</html>
