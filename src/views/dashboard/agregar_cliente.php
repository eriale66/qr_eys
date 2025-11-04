<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente | Renlo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
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
            <a href="/qr_eys/public/dashboard">
                <i class="fa-solid fa-house"></i>
                <span>Inicio</span>
            </a>
            <a href="/qr_eys/public/empleados">
                <i class="fa-solid fa-user-tie"></i>
                <span>Empleados</span>
            </a>
            <a href="/qr_eys/public/clientes" class="active">
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

    <!-- ===== MAIN ===== -->
    <main class="main-content">
        <header>
            <h1>Agregar Nuevo Cliente</h1>
        </header>

        <div class="form-container">
            <form action="/qr_eys/public/clientes/guardar" method="POST" class="form-card">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required placeholder="Ej. María Pérez">

                <label>Correo electrónico</label>
                <input type="email" name="correo" required placeholder="Ej. maria@ejemplo.com">

                <label>Teléfono</label>
                <input type="text" name="telefono" required placeholder="Ej. 8991234567">

                <div class="form-buttons">
                    <button type="submit" class="btn primary">💾 Guardar</button>
                    <a href="/qr_eys/public/clientes" class="btn danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>