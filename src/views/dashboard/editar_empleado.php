<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado | Renlo</title>
    <link rel="stylesheet" href="/qr_eys/public/css/dashboards.css">
    <link rel="stylesheet" href="/qr_eys/public/css/empleados.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/qr_eys/public/js/alerts.js" defer></script>
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
            <a href="#">Cerrar sesión</a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <h1>Editar Empleado</h1>
        </header>

        <div class="form-container">
            <form action="/qr_eys/public/empleados/actualizar" method="POST" class="form-card">
                <input type="hidden" name="id_empleado" value="<?= $empleado['id_empleado'] ?>">

                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($empleado['nombre']) ?>" required>

                <label>Puesto</label>
                <input type="text" name="puesto" value="<?= htmlspecialchars($empleado['puesto']) ?>" required>

                <label>Correo electrónico</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($empleado['correo']) ?>" required>

                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($empleado['telefono']) ?>" required>

                <div class="form-buttons">
                    <button type="submit" class="btn primary">💾 Actualizar</button>
                    <a href="/qr_eys/public/empleados" class="btn danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>