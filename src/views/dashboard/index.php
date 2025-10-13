<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard | Control de Accesos</title>
<link rel="stylesheet" href="../public/css/dashboards.css">
</head>
<body>

<header>
  <div class="logo">
    <img src="/public/img/logo.png" alt="Renlo Logo">
    <h1>Renlo Global Talent</h1>
  </div>
  <div class="user-info">
    <span>👤 Administrador</span>
  </div>
</header>

<main>
  <section class="cards">
    <div class="card">
      <h2>Empleados</h2>
      <p><?= $totalEmpleados ?></p>
    </div>
    <div class="card">
      <h2>Clientes</h2>
      <p><?= $totalClientes ?></p>
    </div>
    <div class="card">
      <h2>Registros Totales</h2>
      <p><?= $totalRegistros ?></p>
    </div>
  </section>

  <section class="table-section">
    <h2>Últimos accesos registrados</h2>
    <table>
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Nombre</th>
          <th>Movimiento</th>
          <th>Fecha y hora</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($registros as $r): ?>
          <tr>
            <td><?= ucfirst($r['tipo_usuario']) ?></td>
            <td>
              <?php
              if ($r['tipo_usuario'] == 'empleado') {
                  $persona = $this->empleadoModel->obtenerPorQR($r['id_referencia']);
              } else {
                  $persona = $this->clienteModel->obtenerPorQR($r['id_referencia']);
              }
              echo htmlspecialchars($persona['nombre'] ?? 'Desconocido');
              ?>
            </td>
            <td class="<?= $r['tipo_movimiento'] === 'entrada' ? 'entrada' : 'salida' ?>">
              <?= ucfirst($r['tipo_movimiento']) ?>
            </td>
            <td><?= date('d/m/Y H:i:s', strtotime($r['fecha_hora'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>

<footer>
  <p>© <?= date('Y') ?> Renlo Global Talent - Sistema de Control de Acceso</p>
</footer>

</body>
</html>
