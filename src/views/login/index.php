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
</head>

<body>
  <div class="login-wrapper">
    <!-- Columna Izquierda - Formulario -->
    <div class="login-left">
      <div class="login-form-container">
        <div class="logo-container">
          <img src="/qr_eys/public/img/logo.jpg" alt="Logo Renlo" class="logo">
        </div>

        <h2>Login</h2>
        <p class="subtitle">Inicie sesión para acceder al sistema</p>

        <?php if (isset($error)): ?>
          <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/qr_eys/public/autenticar">
          <?= CSRF::inputField() ?>

          <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" required>
          </div>

          <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="contraseña" placeholder="Ingrese su contraseña" required>
          </div>

          <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <div style="text-align: center; margin-top: 15px;">
          <a href="/qr_eys/public/olvide-password" style="color: #667eea; text-decoration: none; font-size: 14px;">¿Olvidaste tu contraseña?</a>
        </div>

        <div class="login-footer">
          <p>&copy; 2025 Renlo. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>

    <!-- Columna Derecha - Ilustración -->
    <div class="login-right">
      <div class="illustration-container">
        <div class="stars"></div>
        <div class="stars2"></div>
        <div class="stars3"></div>

        <div class="rocket-container">
          <div class="rocket">
            <div class="rocket-body"></div>
            <div class="rocket-window"></div>
            <div class="rocket-wing-left"></div>
            <div class="rocket-wing-right"></div>
            <div class="rocket-fire"></div>
          </div>
        </div>

        <div class="clouds">
          <div class="cloud cloud1"></div>
          <div class="cloud cloud2"></div>
          <div class="cloud cloud3"></div>
        </div>

        <div class="mountains">
          <div class="mountain mountain1"></div>
          <div class="mountain mountain2"></div>
          <div class="mountain mountain3"></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
