<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes | Renlo</title>
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
      <a href="/qr_eys/public/clientes" class="active">Clientes</a>
      <a href="/qr_eys/public/citas">Citas</a>
      <a href="/qr_eys/public/reportes">Reportes</a>
      <a href="/qr_eys/public/configuracion">Configuración</a>
    </nav>
    <div class="logout">
      <a href="#">Cerrar sesión</a>
    </div>
  </aside>

  <main class="container">
    <h1>Clientes</h1>
    <?php if (!empty($clientes)): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clientes as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['id_cliente'] ?? '') ?></td>
              <td><?= htmlspecialchars($c['nombre'] ?? '') ?></td>
              <td><?= htmlspecialchars($c['correo'] ?? '') ?></td>
              <td><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No hay clientes registrados.</p>
    <?php endif; ?>
  </main>
</body>
</html>
