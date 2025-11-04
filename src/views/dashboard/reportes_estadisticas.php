<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes y Estadísticas | Renlo</title>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="/qr_eys/public/css/dashboards.css">
  <link rel="stylesheet" href="/qr_eys/public/css/reportes.css">
  <script src="/qr_eys/public/js/dashboards.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="/qr_eys/public/js/reportes.js" defer></script>
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

    .filters {
      background: #14181f;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 12px
    }

    .filters label {
      display: block;
      font-size: 12px;
      margin-bottom: 6px;
      opacity: .85
    }

    .filters select,
    .filters input {
      width: 100%;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #2a2f39;
      background: #0f1218;
      color: #fff
    }

    .filters .actions {
      display: flex;
      gap: 10px;
      align-items: end
    }

    .btn {
      background: #0d6efd;
      color: #fff;
      border: none;
      padding: 10px 14px;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      text-align: center
    }

    .btn.secondary {
      background: #198754
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
  <?php
  $registros = $registros ?? [];
  $totalEntradas = $totalEntradas ?? 0;
  $totalSalidas = $totalSalidas ?? 0;
  $empleados = $empleados ?? [];
  $clientes = $clientes ?? [];
  $chartLabels = $chartLabels ?? [];
  $chartEntradas = $chartEntradas ?? [];
  $chartSalidas = $chartSalidas ?? [];
  $tipo = $_GET['tipo'] ?? '';
  $id_referencia = $_GET['id_referencia'] ?? '';
  $mov = $_GET['mov'] ?? '';
  $periodo = $_GET['periodo'] ?? 'hoy';
  $agrupacion = $_GET['agrupacion'] ?? 'dia';
  $desde = $_GET['desde'] ?? date('Y-m-d');
  $hasta = $_GET['hasta'] ?? date('Y-m-d');
  $aggLabels = $aggLabels ?? [];
  $aggEntradas = $aggEntradas ?? [];
  $aggSalidas = $aggSalidas ?? [];
  $aggTotales = $aggTotales ?? [];
  $aggEmp = $aggEmp ?? [];
  $aggCli = $aggCli ?? [];
  $topEmpNombres = $topEmpNombres ?? [];
  $topEmpValores = $topEmpValores ?? [];
  $topCliNombres = $topCliNombres ?? [];
  $topCliValores = $topCliValores ?? [];
  ?>
</head>

<body>
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
      <a href="/qr_eys/public/empleados">
        <i class="fa-solid fa-user-tie"></i>
        <span>Empleados</span>
      </a>
      <a href="/qr_eys/public/clientes">
        <i class="fa-solid fa-users"></i>
        <span>Clientes</span>
      </a>
      <a href="/qr_eys/public/reportes" class="active">
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

  <main class="container">
    <header class="page-header">
      <div>
        <h1>Reportes y Estadísticas</h1>
        <p class="subtitle">Análisis detallado de movimientos y actividad</p>
      </div>
    </header>

    <form class="filters-advanced" method="get" action="/qr_eys/public/reportes" id="filtrosForm">
      <div class="filter-group">
        <label for="tipo">Tipo de usuario</label>
        <select id="tipo" name="tipo">
          <option value="">Todos</option>
          <option value="empleado" <?= $tipo === 'empleado' ? 'selected' : '' ?>>Empleado</option>
          <option value="cliente" <?= $tipo === 'cliente' ? 'selected' : '' ?>>Cliente</option>
        </select>
      </div>

      <div id="empleadoSelect" class="filter-group" style="display: <?= $tipo === 'empleado' ? 'block' : 'none' ?>;">
        <label for="id_referencia_empleado">Empleado</label>
        <select id="id_referencia_empleado">
          <option value="">Todos</option>
          <?php foreach ($empleados as $e): ?>
            <option value="<?= htmlspecialchars($e['id_empleado']) ?>" <?= ($tipo === 'empleado' && (string)$id_referencia === (string)$e['id_empleado']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div id="clienteSelect" class="filter-group" style="display: <?= $tipo === 'cliente' ? 'block' : 'none' ?>;">
        <label for="id_referencia_cliente">Cliente</label>
        <select id="id_referencia_cliente">
          <option value="">Todos</option>
          <?php foreach ($clientes as $c): ?>
            <option value="<?= htmlspecialchars($c['id_cliente']) ?>" <?= ($tipo === 'cliente' && (string)$id_referencia === (string)$c['id_cliente']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <input type="hidden" name="id_referencia" id="id_referencia" value="<?= htmlspecialchars($id_referencia) ?>">

      <div class="filter-group">
        <label for="mov">Movimiento</label>
        <select id="mov" name="mov">
          <option value="">Todos</option>
          <option value="entrada" <?= $mov === 'entrada' ? 'selected' : '' ?>>Entradas</option>
          <option value="salida" <?= $mov === 'salida' ? 'selected' : '' ?>>Salidas</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="periodo">Periodo</label>
        <select id="periodo" name="periodo">
          <option value="hoy" <?= $periodo === 'hoy' ? 'selected' : '' ?>>Hoy</option>
          <option value="semana" <?= $periodo === 'semana' ? 'selected' : '' ?>>Esta semana</option>
          <option value="mes" <?= $periodo === 'mes' ? 'selected' : '' ?>>Este mes</option>
          <option value="rango" <?= $periodo === 'rango' ? 'selected' : '' ?>>Rango</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="agrupacion">Agrupar por</label>
        <select id="agrupacion" name="agrupacion">
          <option value="dia" <?= $agrupacion === 'dia' ? 'selected' : '' ?>>Día</option>
          <option value="semana" <?= $agrupacion === 'semana' ? 'selected' : '' ?>>Semana</option>
          <option value="mes" <?= $agrupacion === 'mes' ? 'selected' : '' ?>>Mes</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="desde">Desde</label>
        <input type="date" id="desde" name="desde" value="<?= htmlspecialchars($desde) ?>" <?= $periodo !== 'rango' ? 'disabled' : '' ?>>
      </div>
      <div class="filter-group">
        <label for="hasta">Hasta</label>
        <input type="date" id="hasta" name="hasta" value="<?= htmlspecialchars($hasta) ?>" <?= $periodo !== 'rango' ? 'disabled' : '' ?>>
      </div>

      <div class="filter-actions">
        <button type="submit" class="btn-filter">
          <span><i class="fa-solid fa-filter"></i></span> Filtrar
        </button>
        <?php $qsStr = http_build_query($_GET ?? []); ?>
        <a class="btn-export pdf" href="/qr_eys/public/reportes/pdf?<?= htmlspecialchars($qsStr) ?>">
          <span><i class="fa-solid fa-file-pdf"></i></span> PDF
        </a>
        <a class="btn-export excel" href="/qr_eys/public/reportes/excel?<?= htmlspecialchars($qsStr) ?>">
          <span><i class="fa-solid fa-file-excel"></i></span> Excel
        </a>
      </div>
    </form>

    <section class="stats-grid">
      <div class="stat-card total">
        <div class="stat-icon"><i class="fa-solid fa-clipboard"></i></div>
        <div class="stat-content">
          <h3>Total de registros</h3>
          <p class="stat-value"><?= isset($registros) ? count($registros) : 0 ?></p>
        </div>
      </div>
      <div class="stat-card entrada">
        <div class="stat-icon"><i class="fa-solid fa-inbox"></i></div>
        <div class="stat-content">
          <h3>Total de entradas</h3>
          <p class="stat-value"><?= $totalEntradas ?? 0 ?></p>
        </div>
      </div>
      <div class="stat-card salida">
        <div class="stat-icon"><i class="fa-solid fa-inbox"></i></div>
        <div class="stat-content">
          <h3>Total de salidas</h3>
          <p class="stat-value"><?= $totalSalidas ?? 0 ?></p>
        </div>
      </div>
    </section>

    <div class="charts-grid">
      <section class="chart-container main-chart">
        <div class="chart-header">
          <h2>Entradas vs Salidas</h2>
          <span class="chart-badge">Tendencia</span>
        </div>
        <div class="chart-wrapper">
          <canvas id="movChart"></canvas>
        </div>
      </section>

      <section class="chart-container">
        <div class="chart-header">
          <h2>Registros por periodo</h2>
          <span class="chart-badge secondary"><?= htmlspecialchars(strtoupper($agrupacion)) ?></span>
        </div>
        <div class="chart-wrapper">
          <canvas id="aggChart"></canvas>
        </div>
      </section>

      <section class="chart-container">
        <div class="chart-header">
          <h2>Por tipo de usuario</h2>
          <span class="chart-badge tertiary"><?= htmlspecialchars(strtoupper($agrupacion)) ?></span>
        </div>
        <div class="chart-wrapper">
          <canvas id="aggTipoUsuarioChart"></canvas>
        </div>
      </section>

      <section class="chart-container half">
        <div class="chart-header">
          <h2>Top Empleados</h2>
          <span class="chart-badge success">Top 5</span>
        </div>
        <div class="chart-wrapper">
          <canvas id="topEmpsChart"></canvas>
        </div>
      </section>

      <section class="chart-container half">
        <div class="chart-header">
          <h2>Top Clientes</h2>
          <span class="chart-badge warning">Top 5</span>
        </div>
        <div class="chart-wrapper">
          <canvas id="topClientesChart"></canvas>
        </div>
      </section>
    </div>

    <section class="table-container">
      <div class="table-header">
        <h2>Últimos movimientos</h2>
        <span class="record-count"><?= count($registros) ?> registros</span>
      </div>
      <?php if (!empty($registros)): ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Tipo usuario</th>
                <th>Movimiento</th>
                <th>Fecha y hora</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($registros as $r): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($r['nombre_usuario'] ?? 'Desconocido') ?></strong></td>
                  <td>
                    <span class="badge <?= strtolower($r['tipo_usuario'] ?? '') ?>">
                      <?= htmlspecialchars($r['tipo_usuario'] ?? '') ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge-mov <?= strtolower($r['tipo_movimiento'] ?? '') ?>">
                      <?= htmlspecialchars($r['tipo_movimiento'] ?? '') ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($r['fecha_hora'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <div class="empty-icon"><i class="fa-solid fa-chart-simple"></i></div>
          <p>No hay registros disponibles</p>
          <small>Ajusta los filtros para ver resultados</small>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <script>
    const tipoSel = document.getElementById('tipo');
    const empSel = document.getElementById('empleadoSelect');
    const cliSel = document.getElementById('clienteSelect');
    const hiddenRef = document.getElementById('id_referencia');
    const empRef = document.getElementById('id_referencia_empleado');
    const cliRef = document.getElementById('id_referencia_cliente');
    const periodoSel = document.getElementById('periodo');
    const agrupSel = document.getElementById('agrupacion');
    const desdeInput = document.getElementById('desde');
    const hastaInput = document.getElementById('hasta');

    function syncRef() {
      if (tipoSel.value === 'empleado') {
        hiddenRef.value = empRef.value || '';
      } else if (tipoSel.value === 'cliente') {
        hiddenRef.value = cliRef.value || '';
      } else {
        hiddenRef.value = '';
      }
    }
    empRef && empRef.addEventListener('change', syncRef);
    cliRef && cliRef.addEventListener('change', syncRef);
    tipoSel.addEventListener('change', () => {
      empSel.style.display = (tipoSel.value === 'empleado') ? 'block' : 'none';
      cliSel.style.display = (tipoSel.value === 'cliente') ? 'block' : 'none';
      syncRef();
    });

    periodoSel.addEventListener('change', () => {
      const isRango = periodoSel.value === 'rango';
      desdeInput.disabled = !isRango;
      hastaInput.disabled = !isRango;
    });

    // Chart defaults
    Chart.defaults.color = '#a9b3c1';
    Chart.defaults.borderColor = 'rgba(169, 179, 193, 0.1)';
    Chart.defaults.font.family = "'Poppins', sans-serif";

    const labels = <?= json_encode($chartLabels) ?>;
    const entradas = <?= json_encode($chartEntradas) ?>;
    const salidas = <?= json_encode($chartSalidas) ?>;
    const ctx = document.getElementById('movChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
            label: 'Entradas',
            data: entradas,
            borderColor: '#4cc9f0',
            backgroundColor: 'rgba(76, 201, 240, .15)',
            tension: .4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#4cc9f0',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
          },
          {
            label: 'Salidas',
            data: salidas,
            borderColor: '#ef476f',
            backgroundColor: 'rgba(239, 71, 111, .15)',
            tension: .4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#ef476f',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            align: 'end',
            labels: {
              usePointStyle: true,
              padding: 15,
              font: {
                size: 12
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(27, 31, 37, 0.95)',
            padding: 12,
            borderColor: 'rgba(76, 201, 240, 0.3)',
            borderWidth: 1,
            titleFont: {
              size: 13,
              weight: 'bold'
            },
            bodyFont: {
              size: 12
            },
            cornerRadius: 6
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            },
            grid: {
              color: 'rgba(169, 179, 193, 0.08)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    const aggLabels = <?= json_encode($aggLabels) ?>;
    const aggEntradas = <?= json_encode($aggEntradas) ?>;
    const aggSalidas = <?= json_encode($aggSalidas) ?>;
    const aggTotales = <?= json_encode($aggTotales) ?>;
    const ctxAgg = document.getElementById('aggChart').getContext('2d');
    new Chart(ctxAgg, {
      type: 'bar',
      data: {
        labels: aggLabels,
        datasets: [{
            label: 'Total',
            data: aggTotales,
            backgroundColor: 'rgba(13, 110, 253, .6)',
            borderColor: '#0d6efd',
            borderWidth: 2,
            borderRadius: 6
          },
          {
            label: 'Entradas',
            data: aggEntradas,
            backgroundColor: 'rgba(76, 201, 240, .5)',
            borderColor: '#4cc9f0',
            borderWidth: 2,
            borderRadius: 6
          },
          {
            label: 'Salidas',
            data: aggSalidas,
            backgroundColor: 'rgba(239, 71, 111, .5)',
            borderColor: '#ef476f',
            borderWidth: 2,
            borderRadius: 6
          },
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
            align: 'end',
            labels: {
              usePointStyle: true,
              padding: 15,
              font: {
                size: 12
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(27, 31, 37, 0.95)',
            padding: 12,
            borderColor: 'rgba(76, 201, 240, 0.3)',
            borderWidth: 1,
            cornerRadius: 6
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            },
            grid: {
              color: 'rgba(169, 179, 193, 0.08)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    const aggEmp = <?= json_encode($aggEmp) ?>;
    const aggCli = <?= json_encode($aggCli) ?>;
    const ctxAggTU = document.getElementById('aggTipoUsuarioChart').getContext('2d');
    new Chart(ctxAggTU, {
      type: 'line',
      data: {
        labels: aggLabels,
        datasets: [{
            label: 'Empleados',
            data: aggEmp,
            borderColor: '#20c997',
            backgroundColor: 'rgba(32, 201, 151, .15)',
            tension: .4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#20c997',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
          },
          {
            label: 'Clientes',
            data: aggCli,
            borderColor: '#fd7e14',
            backgroundColor: 'rgba(253, 126, 20, .15)',
            tension: .4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#fd7e14',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
          },
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            align: 'end',
            labels: {
              usePointStyle: true,
              padding: 15,
              font: {
                size: 12
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(27, 31, 37, 0.95)',
            padding: 12,
            borderColor: 'rgba(76, 201, 240, 0.3)',
            borderWidth: 1,
            cornerRadius: 6
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            },
            grid: {
              color: 'rgba(169, 179, 193, 0.08)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    const topEmpLabels = <?= json_encode($topEmpNombres) ?>;
    const topEmpValues = <?= json_encode($topEmpValores) ?>;
    const topCliLabels = <?= json_encode($topCliNombres) ?>;
    const topCliValues = <?= json_encode($topCliValores) ?>;

    const ctxTopEmps = document.getElementById('topEmpsChart').getContext('2d');
    new Chart(ctxTopEmps, {
      type: 'bar',
      data: {
        labels: topEmpLabels,
        datasets: [{
          label: 'Movimientos',
          data: topEmpValues,
          backgroundColor: 'rgba(25, 135, 84, .6)',
          borderColor: '#198754',
          borderWidth: 2,
          borderRadius: 6
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(27, 31, 37, 0.95)',
            padding: 12,
            borderColor: 'rgba(25, 135, 84, 0.3)',
            borderWidth: 1,
            cornerRadius: 6
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            },
            grid: {
              color: 'rgba(169, 179, 193, 0.08)'
            }
          },
          y: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    const ctxTopCli = document.getElementById('topClientesChart').getContext('2d');
    new Chart(ctxTopCli, {
      type: 'bar',
      data: {
        labels: topCliLabels,
        datasets: [{
          label: 'Movimientos',
          data: topCliValues,
          backgroundColor: 'rgba(255, 193, 7, .6)',
          borderColor: '#ffc107',
          borderWidth: 2,
          borderRadius: 6
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(27, 31, 37, 0.95)',
            padding: 12,
            borderColor: 'rgba(255, 193, 7, 0.3)',
            borderWidth: 1,
            cornerRadius: 6
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            },
            grid: {
              color: 'rgba(169, 179, 193, 0.08)'
            }
          },
          y: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  </script>
</body>

</html>