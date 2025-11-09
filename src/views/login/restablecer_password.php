<?php
require_once __DIR__ . '/../../utils/CSRF.php';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer Contraseña | Renlo</title>
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

    .success-container {
      text-align: center;
      padding: 20px;
    }

    .success-icon {
      font-size: 64px;
      color: #28a745;
      margin-bottom: 20px;
    }

    .success-container h2 {
      color: #28a745;
      margin-bottom: 15px;
    }

    .success-container p {
      color: #666;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .btn-login {
      display: inline-block;
      text-decoration: none;
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

    .password-requirements {
      background-color: #e7f3ff;
      border-left: 4px solid #2196F3;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 4px;
      font-size: 13px;
    }

    .password-requirements ul {
      margin: 5px 0 0 20px;
      color: #0d47a1;
    }

    .password-requirements li {
      margin: 3px 0;
    }

    .form-group {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 38px;
      cursor: pointer;
      color: #666;
      user-select: none;
    }

    .toggle-password:hover {
      color: #333;
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

        <?php if (isset($success) && $success === true): ?>
          <!-- Pantalla de éxito -->
          <div class="success-container">
            <div class="success-icon">🎉</div>
            <h2>¡Contraseña Actualizada!</h2>
            <p>
              Tu contraseña ha sido restablecida exitosamente.<br>
              Ya puedes iniciar sesión con tu nueva contraseña.
            </p>
            <a href="/qr_eys/public/login" class="btn-login">Ir al Login</a>
          </div>
        <?php else: ?>
          <!-- Formulario de nueva contraseña -->
          <h2>Nueva Contraseña</h2>
          <p class="subtitle">Ingresa tu nueva contraseña</p>

          <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="password-requirements">
            <strong>📋 Requisitos de la contraseña:</strong>
            <ul>
              <li>Mínimo 8 caracteres</li>
              <li>Debe ser segura y fácil de recordar</li>
            </ul>
          </div>

          <form method="POST" action="/qr_eys/public/procesar-restablecer-password">
            <?= CSRF::inputField() ?>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
              <label>Nueva Contraseña</label>
              <input type="password" id="nueva_password" name="nueva_password"
                     placeholder="Ingrese su nueva contraseña" required minlength="8">
              <span class="toggle-password" onclick="togglePassword('nueva_password', this)">👁️</span>
            </div>

            <div class="form-group">
              <label>Confirmar Contraseña</label>
              <input type="password" id="confirmar_password" name="confirmar_password"
                     placeholder="Confirme su nueva contraseña" required minlength="8">
              <span class="toggle-password" onclick="togglePassword('confirmar_password', this)">👁️</span>
            </div>

            <button type="submit" class="btn-login">Restablecer Contraseña</button>
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

  <script>
    function togglePassword(inputId, icon) {
      const input = document.getElementById(inputId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = '🙈';
      } else {
        input.type = 'password';
        icon.textContent = '👁️';
      }
    }

    // Validar que las contraseñas coincidan en tiempo real
    const form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', function(e) {
        const nueva = document.getElementById('nueva_password').value;
        const confirmar = document.getElementById('confirmar_password').value;

        if (nueva !== confirmar) {
          e.preventDefault();
          alert('Las contraseñas no coinciden. Por favor, verifique.');
          return false;
        }

        if (nueva.length < 8) {
          e.preventDefault();
          alert('La contraseña debe tener al menos 8 caracteres.');
          return false;
        }
      });
    }
  </script>
</body>

</html>
