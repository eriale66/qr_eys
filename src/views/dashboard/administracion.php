<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administradores | Renlo</title>
    <link rel="stylesheet" href="../public/css/dashboards.css">
    <link rel="stylesheet" href="../public/css/empleados.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/js/alerts.js" defer></script>
    <script src="../public/js/dashboards.js" defer></script>
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

    <main class="main-content">
        <header>
            <h1>Gestión de Administradores</h1>
        </header>

        <div class="container">
            <div class="panel-info">
                <div class="info-box">
                    <h4>Total de administradores</h4>
                    <p><?= count($usuarios) ?></p>
                </div>
            </div>

            <div class="toolbar">
                <div class="left">
                    <a href="/qr_eys/public/administracion/agregar" class="btn primary"> Agregar Administrador</a>
                </div>
                <div class="right">
                    <input type="text" id="buscador" placeholder="Buscar administrador...">
                </div>
            </div>

            <table class="tabla">
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
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($u['nombre']) ?></td>
                            <td><?= htmlspecialchars($u['usuario']) ?></td>
                            <td><?= htmlspecialchars($u['rol']) ?></td>
                            <td class="acciones">
                                <a href="/qr_eys/public/administracion/editar?id=<?= $u['id_usuario'] ?>" class="btn edit"> Editar</a>
                                <a href="/qr_eys/public/administracion/eliminar?id=<?= $u['id_usuario'] ?>"
                                    onclick="return confirm('¿Eliminar al administrador <?= htmlspecialchars($u['usuario']) ?>?')"
                                    class="btn danger small"> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('a[href*="/administracion/eliminar"]').forEach(link => {
                try {
                    link.removeAttribute('onclick');
                } catch (_) {}
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const usuario = this.closest('tr')?.children[2]?.textContent.trim() || 'este administrador';
                    confirmarEliminacion(usuario).then(c => {
                        if (c) window.location.href = this.href;
                    });
                });
            });
        });
    </script>

</body>

</html>