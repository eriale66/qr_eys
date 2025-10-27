<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado | Renlo</title>
    <link rel="stylesheet" href="/qr_eys/public/css/dashboards.css">
    <link rel="stylesheet" href="/qr_eys/public/css/empleados.css">
</head>

<body>

    <aside class="sidebar">
        <div class="brand">
            <h2>Renlo</h2>
            <p>Control de Acceso</p>
        </div>
        <nav>
            <a href="/qr_eys/public/dashboard">Inicio</a>
            <a href="/qr_eys/public/empleados" class="active">Empleados</a>
            <a href="/qr_eys/public/clientes">Clientes</a>
            <a href="/qr_eys/public/citas">Citas</a>
            <a href="/qr_eys/public/reportes">Reportes</a>
            <a href="/qr_eys/public/configuracion">Configuración</a>
        </nav>
        <div class="logout">
            <a href="/qr_eys/public/logout">Cerrar sesión</a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <h1>Agregar Nuevo Empleado</h1>
        </header>

        <div class="form-container">
            <form action="/qr_eys/public/empleados/guardar" method="POST" class="form-card">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required>

                <label>Puesto</label>
                <input type="text" name="puesto" required>

                <label>Correo electrónico</label>
                <input type="email" name="correo" required>

                <label>Teléfono</label>
                <input type="text" name="telefono" required>

                <div class="form-buttons">
                    <button type="submit" class="btn primary">💾 Guardar</button>
                    <a href="/qr_eys/public/empleados" class="btn danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>