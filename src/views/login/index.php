<?php
require_once __DIR__ . '/../../utils/CSRF.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control | Renlo</title>
  <link rel="stylesheet" href="../public/css/login.css">
  <!-- <link rel="stylesheet" href="../public/css/main.css"> -->
  <!-- <script src="../public/js/dashboards.js" defer></script> -->
  <!-- <script src="../public/js/main.js" defer></script> -->
</head>

<body>
    <div class="login-container">
  <h2>Bienvenido</h2>
  <p>Inicie sesión para acceder al sistema</p>

  <?php if (isset($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="/qr_eys/public/autenticar">
    <?= CSRF::inputField() ?>

    <label>Usuario</label>
    <input type="text" name="usuario" required>

    <label>Contraseña</label>
    <input type="password" name="contraseña" required>

    <button type="submit">Ingresar</button>
  </form>
</div>

</body>
</html>
