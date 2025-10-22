<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración | Renlo</title>
  <link rel="stylesheet" href="../public/css/dashboards.css">
  <script src="../public/js/dashboards.js" defer></script>
  <style> .container{margin-left:250px;padding:30px} .card{background:#1b1f25;padding:20px;border-radius:10px;margin-bottom:20px} label{display:block;margin:10px 0 4px;color:#a9b3c1} input{width:100%;padding:10px;border-radius:6px;border:1px solid #333;background:#111;color:#f1f3f6} button{margin-top:15px;padding:10px 16px;border:none;border-radius:6px;background:#0d6efd;color:#fff;cursor:pointer} </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">
      <h2>Renlo</h2>
      <p>Control de Acceso</p>
    </div>
    <nav>
      <a href="/qr_eys/public/dashboard">Inicio</a>
      <a href="/qr_eys/public/empleados">Empleados</a>
      <a href="/qr_eys/public/clientes">Clientes</a>
      <a href="/qr_eys/public/citas">Citas</a>
      <a href="/qr_eys/public/reportes">Reportes</a>
      <a href="/qr_eys/public/configuracion" class="active">Configuración</a>
    </nav>
    <div class="logout">
      <a href="#">Cerrar sesión</a>
    </div>
  </aside>

  <main class="container">
    <h1>Configuración</h1>
    <div class="card">
      <h3>Parámetros generales</h3>
      <form>
        <label>Nombre de la empresa</label>
        <input type="text" placeholder="Renlo S.A.">
        <label>Zona horaria</label>
        <input type="text" placeholder="America/Mexico_City">
        <button type="button">Guardar</button>
      </form>
    </div>
  </main>
</body>
</html>
