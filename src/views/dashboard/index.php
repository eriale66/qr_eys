<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control | Renlo</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <link rel="stylesheet" href="../public/css/dashboard-enhancesd.css">
  <script src="../public/js/dashboards.js" defer></script>
  <script src="../public/js/dashboard-enhanced.js" defer></script>
</head>

<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <h2>Renlo</h2>
      <p>Control de Acceso</p>
    </div>
    <nav>
      <a href="/qr_eys/public/dashboard" class="active">
        <i class="fa-solid fa-house"></i>
        <span>Inicio</span>
      </a>
      <a href="/qr_eys/public/empleados">
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
    <div class="logout">
      <a href="/qr_eys/public/logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Cerrar sesión</span>
      </a>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main-content">
    <header>
      <h1>Panel de Control</h1>
      <p class="header-subtitle">Resumen de actividad en tiempo real</p>
    </header>

    <!-- CARDS -->
    <section class="cards">
      <div class="card card-enhanced" data-type="entradas">
        <div class="card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"/>
          </svg>
        </div>
        <h3>Entradas</h3>
        <p class="card-value" data-target="<?= $totalEntradas ?>">0</p>
        <!-- <span class="card-trend">+12% vs ayer</span> -->
      </div>
      <div class="card card-enhanced" data-type="salidas">
        <div class="card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
          </svg>
        </div>
        <h3>Salidas</h3>
        <p class="card-value" data-target="<?= $totalSalidas ?>">0</p>
        <!-- <span class="card-trend">+8% vs ayer</span> -->
      </div>
      <div class="card card-enhanced" data-type="clientes">
        <div class="card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <h3>Clientes Registrados</h3>
        <p class="card-value" data-target="<?= $totalClientes ?>">0</p>
        <!-- <span class="card-trend">+15% este mes</span> -->
      </div>
      <div class="card card-enhanced" data-type="empleados">
        <div class="card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="8.5" cy="7" r="4"/>
            <line x1="20" y1="8" x2="20" y2="14"/>
            <line x1="23" y1="11" x2="17" y2="11"/>
          </svg>
        </div>
        <h3>Empleados Activos</h3>
        <p class="card-value" data-target="<?= $totalEmpleados ?>">0</p>
        <!-- <span class="card-trend">Activos hoy</span> -->
      </div>
    </section>

    <!-- ASISTENCIA -->
    <?php // $presentes, $ausentes y $porcentaje calculados en el controlador para el día actual ?>
    <section class="stats stats-enhanced">
      <div class="stats-header">
        <div>
          <h2>Asistencia del Personal</h2>
          <p class="stats-subtitle">Visualización de presencia en tiempo real</p>
        </div>
        <div class="stats-percentage">
          <span class="percentage-value"><?= $porcentaje ?>%</span>
          <span class="percentage-label">Presente</span>
        </div>
      </div>
      
      <div class="chart-container">
        <canvas id="attendanceChart"></canvas>
      </div>
      
      <div class="chart-legend">
        <div class="legend-item">
          <span class="legend-dot present"></span>
          <span>Presente (<?= $presentes ?>)</span>
        </div>
        <div class="legend-item">
          <span class="legend-dot absent"></span>
          <span>Ausente (<?= $ausentes ?>)</span>
        </div>
      </div>
    </section>

    <!-- REGISTROS -->
    <section class="records records-enhanced">
      <div class="records-header">
        <h2>Registros Recientes</h2>
        <div class="records-filter">
          <button class="filter-btn active" data-filter="all">Todos</button>
          <button class="filter-btn" data-filter="entrada">Entradas</button>
          <button class="filter-btn" data-filter="salida">Salidas</button>
        </div>
      </div>
      
      <div class="table-container">
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
                <tr data-movement="<?= $r['tipo_movimiento'] ?>">
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar"><?= strtoupper(substr($r['nombre'], 0, 1)) ?></div>
                      <span><?= htmlspecialchars($r['nombre']) ?></span>
                    </div>
                  </td>
                  <td><span class="type-badge"><?= ucfirst($r['tipo_usuario']) ?></span></td>
                  <td>
                    <span class="status <?= $r['tipo_movimiento'] === 'entrada' ? 'present' : 'absent' ?>">
                      <?= ucfirst($r['tipo_movimiento']) ?>
                    </span>
                  </td>
                  <td class="date-cell"><?= date('d/m/Y H:i:s', strtotime($r['fecha_hora'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" style="text-align:center;">No hay registros recientes.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- PERSONAL DENTRO -->
    <section class="progress-section progress-enhanced">
      <div class="progress-header">
        <h2>Personal Dentro del Edificio</h2>
        <p class="progress-subtitle">Monitoreo de ocupación actual</p>
      </div>
      
      <div class="progress-stats">
        <div class="progress-stat">
          <span class="stat-value"><?= $presentes ?></span>
          <span class="stat-label">Dentro</span>
        </div>
        <div class="progress-stat">
          <span class="stat-value"><?= $ausentes ?></span>
          <span class="stat-label">Fuera</span>
        </div>
        <div class="progress-stat">
          <span class="stat-value"><?= $totalEmpleados ?></span>
          <span class="stat-label">Total</span>
        </div>
      </div>
      
      <div class="progress-bar-enhanced">
        <div class="progress-fill" data-percentage="<?= $porcentaje ?>"></div>
        <span class="progress-label"><?= $porcentaje ?>%</span>
      </div>
      
      <div class="capacity-indicator">
        <span class="capacity-icon">●</span>
        <span>Capacidad: <?= $porcentaje < 50 ? 'Baja' : ($porcentaje < 80 ? 'Media' : 'Alta') ?></span>
      </div>
    </section>
  </main>

</body>

</html>
