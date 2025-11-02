<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Administrador | Renlo</title>
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
            <a href="#">Cerrar sesión</a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <h1>Agregar Nuevo Administrador</h1>
        </header>

        <div class="form-container">
            <form action="/qr_eys/public/administracion/guardar" method="POST" class="form-card">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required>

                <label>Usuario</label>
                <input type="text" name="usuario" required>

                <label>Contraseña</label>
                <input type="password" name="contraseña" required>

                <label>Rol</label>
                <select name="rol" required>
                    <option value="admin">Administrador</option>
                    <option value="superadmin">Super Administrador</option>
                </select>

                <div class="form-buttons">
                    <button type="submit" class="btn primary">💾 Guardar</button>
                    <a href="/qr_eys/public/administracion" class="btn danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>