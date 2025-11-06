<?php
require_once __DIR__ . '/../../utils/CSRF.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administradores | Renlo</title>
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

    <!-- SIDEBAR -->
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
            <a href="/qr_eys/public/reportes">
                <i class="fa-solid fa-chart-line"></i>
                <span>Reportes</span>
            </a>
            <a href="/qr_eys/public/administracion" class="active">
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
                <h1>Gestión de Administradores</h1>
                <p class="subtitle">Administra y controla los administradores</p>
            </div>
            <div class="header-stats">
                <div class="stat-badge">
                    <span class="stat-icon"><i class="fa-solid fa-user"></i></span>
                    <div>
                        <small>Total</small>
                        <strong><?= count($usuarios) ?></strong>
                    </div>
                </div>
            </div>
        </header>

        <section class="container">
            <div class="toolbar-advanced">
                <div class="toolbar-left">
                    <a href="/qr_eys/public/administracion/agregar" class="btn-action primary">
                        <span class="btn-icon"><i class="fa-solid fa-plus"></i></span>
                        <span class="btn-text">Nuevo Administrador</span>
                    </a>
                </div>
                <div class="toolbar-right">
                    <div class="search-box">
                        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="buscador" placeholder="Buscar administrador...">
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="tabla-moderna">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($usuarios as $u): ?>
                            <?php
                            $rol = strtolower($u['rol']);
                            $rolClass = 'usuario';
                            if (strpos($rol, 'admin') !== false) {
                                $rolClass = 'administrador';
                            } elseif (strpos($rol, 'super') !== false) {
                                $rolClass = 'supervisor';
                            }
                            ?>
                            <tr class="table-row">
                                <td class="row-number"><?= $i++ ?></td>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar" style="background: linear-gradient(135deg, #e5383b, #dc2f02);">
                                            <?= strtoupper(substr($u['nombre'], 0, 2)) ?>
                                        </div>
                                        <strong><?= htmlspecialchars($u['nombre']) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-usuario">@<?= htmlspecialchars($u['usuario']) ?></span>
                                </td>
                                <td>
                                    <span class="badge-rol <?= $rolClass ?>"><?= htmlspecialchars($u['rol']) ?></span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-buttons">
                                        <a href="/qr_eys/public/administracion/editar?id=<?= $u['id_usuario'] ?>" class="btn-icon-action edit" title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form method="POST" action="/qr_eys/public/administracion/eliminar" style="display: inline;" class="form-eliminar-admin" data-nombre="<?= htmlspecialchars($u['usuario'], ENT_QUOTES) ?>">
                                            <?= CSRF::inputField() ?>
                                            <input type="hidden" name="id" value="<?= $u['id_usuario'] ?>">
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
        </section>
    </main>

    <script>
        // Manejar eliminación con SweetAlert2
        document.querySelectorAll('.form-eliminar-admin .btn-eliminar').forEach(btn => {
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