<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente | Renlo</title>
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
    <!-- ===== MAIN ===== -->
    <main class="main-content">
        <header>
            <h1>Editar Cliente</h1>
        </header>

        <div class="form-container">
            <form action="/qr_eys/public/clientes/actualizar" method="POST" class="form-card">
                <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">

                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>

                <label>Correo electrónico</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" required>

                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>

                <div class="form-buttons">
                    <button type="submit" class="btn primary">Actualizar</button>
                    <a href="/qr_eys/public/clientes" class="btn danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>