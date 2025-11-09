<?php
require_once __DIR__ . '/../../utils/CSRF.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña | Renlo</title>
  <link rel="stylesheet" href="/qr_eys/public/css/login.css">
  <style>
    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #c3e6cb;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .success-message::before {
      content: "✓";
      background-color: #28a745;
      color: white;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      color: #667eea;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s;
    }

    .back-link a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    .info-text {
      color: #666;
      font-size: 14px;
      margin-bottom: 20px;
      line-height: 1.5;
    }
  </style>
</head>

<body>
  <div class="login-wrapper">
    <!-- Columna Izquierda - Formulario -->
    <div class="login-left">
      <div class="login-form-container">
        <div class="logo-container">
          <img src="/qr_eys/public/img/logo.jpg" alt="Logo Renlo" class="logo">
        </div>

        <h2>Recuperar Contraseña</h2>
        <p class="subtitle">Ingresa tu correo electrónico para restablecer tu contraseña</p>

        <?php if (isset($error)): ?>
          <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
          <div class="success-message">
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <?php if (!isset($success)): ?>
          <p class="info-text">
            Ingresa el correo electrónico asociado a tu cuenta. Te enviaremos un enlace para restablecer tu contraseña.
          </p>

          <form method="POST" action="/qr_eys/public/enviar-recuperacion">
            <?= CSRF::inputField() ?>

            <div class="form-group">
              <label>Correo Electrónico</label>
              <input type="email" name="email" placeholder="ejemplo@correo.com" required autofocus>
            </div>

            <button type="submit" class="btn-login">Enviar Enlace de Recuperación</button>
          </form>
        <?php endif; ?>

        <div class="back-link">
          <a href="/qr_eys/public/login">← Volver al inicio de sesión</a>
        </div>

        <div class="login-footer">
          <p>&copy; 2024 Renlo. Todos los derechos reservados.</p>
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
