<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador | Renlo</title>
    <link rel="stylesheet" href="/qr_eys/public/css/dashboards.css">
    <link rel="stylesheet" href="/qr_eys/public/css/empleados.css">
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
            <h1>Editar Administrador</h1>
        </header>

        <div class="form-container">
            <form action="/qr_eys/public/administracion/actualizar" method="POST" class="form-card">
                <input type="hidden" name="id_usuario" value="<?= $admin['id_usuario'] ?>">

                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($admin['nombre']) ?>" required>

                <label>Usuario</label>
                <input type="text" name="usuario" value="<?= htmlspecialchars($admin['usuario']) ?>" required>

                <label>Rol</label>
                <select name="rol">
                    <option value="admin" <?= $admin['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    <option value="superadmin" <?= $admin['rol'] === 'superadmin' ? 'selected' : '' ?>>Super Administrador</option>
                </select>

                <div class="form-buttons">
                    <button type="submit" class="btn primary">💾 Actualizar</button>
                    <a href="/qr_eys/public/administracion" class="btn danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>