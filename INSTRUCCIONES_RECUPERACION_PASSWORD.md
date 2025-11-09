# 📧 Sistema de Recuperación de Contraseña - Instrucciones

## ✅ IMPLEMENTACIÓN COMPLETADA

Se ha implementado exitosamente el sistema de recuperación de contraseña con envío de emails. A continuación, los pasos para activarlo:

---

## 📋 PASOS PARA ACTIVAR EL SISTEMA

### 1️⃣ Ejecutar Scripts SQL en phpMyAdmin

Abre **phpMyAdmin** y ejecuta los siguientes scripts en orden:

#### a) Agregar campo `email` a la tabla `usuarios`

```sql
-- Ejecutar en la base de datos: control_accesos
-- Abrir el archivo: database/add_email_field.sql

ALTER TABLE `usuarios`
ADD COLUMN `email` VARCHAR(100) NULL AFTER `contraseña`;

ALTER TABLE `usuarios`
ADD UNIQUE INDEX `idx_email` (`email`);

-- OPCIONAL: Actualizar registros existentes con emails temporales
UPDATE `usuarios` SET `email` = CONCAT(usuario, '@renlo.local') WHERE `email` IS NULL;
```

#### b) Crear tabla `password_reset_tokens`

```sql
-- Ejecutar en la base de datos: control_accesos
-- Abrir el archivo: database/create_password_reset_table.sql

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expira_en` DATETIME NOT NULL,
  `usado` TINYINT(1) DEFAULT 0,
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_token` (`token`),
  INDEX `idx_email` (`email`),
  INDEX `idx_expiracion` (`expira_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 2️⃣ Configurar Email en `.env`

Edita el archivo `.env` en la raíz del proyecto y actualiza las siguientes variables:

```env
# Configuración de Email (SMTP)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Sistema Control de Accesos - Renlo"
```

#### 🔐 Cómo obtener una Contraseña de Aplicación de Gmail:

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Ve a **Seguridad** → **Verificación en 2 pasos** (debe estar activada)
3. Busca **Contraseñas de aplicaciones**
4. Selecciona **Otra (nombre personalizado)** → escribe "Sistema Renlo"
5. Copia la contraseña generada (16 caracteres sin espacios)
6. Pégala en `MAIL_PASSWORD` del archivo `.env`

#### 📧 Otras opciones de SMTP:

**Outlook/Hotmail:**
```env
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
```

**Yahoo:**
```env
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
```

**Mailtrap (para pruebas):**
```env
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username-mailtrap
MAIL_PASSWORD=tu-password-mailtrap
```

---

### 3️⃣ Agregar Emails a los Usuarios Existentes

En **phpMyAdmin**, actualiza los usuarios existentes con emails reales:

```sql
-- Actualizar emails de administradores/usuarios
UPDATE usuarios SET email = 'admin@renlo.com' WHERE usuario = 'Panchito';
UPDATE usuarios SET email = 'erick@renlo.com' WHERE usuario = 'Erick Alejandro';
-- Agrega más según tus usuarios...
```

---

## 🎯 CÓMO FUNCIONA EL SISTEMA

### Flujo de Recuperación de Contraseña:

1. **Usuario olvida su contraseña**
   - Hace clic en "¿Olvidaste tu contraseña?" en el login
   - URL: `/qr_eys/public/olvide-password`

2. **Ingresa su email**
   - El sistema valida que el email exista en la base de datos
   - Genera un token único y seguro

3. **Envía email de recuperación**
   - El usuario recibe un email con un enlace
   - El enlace contiene el token y expira en **1 hora**

4. **Usuario hace clic en el enlace**
   - URL: `/qr_eys/public/restablecer-password?token=xxxxx`
   - El sistema valida que el token sea válido y no haya expirado

5. **Ingresa nueva contraseña**
   - Debe tener mínimo 8 caracteres
   - Confirma la contraseña
   - El sistema actualiza la contraseña y marca el token como usado

6. **¡Listo!**
   - El usuario puede iniciar sesión con su nueva contraseña

---

## 📁 ARCHIVOS CREADOS/MODIFICADOS

### Nuevos archivos:

1. `src/utils/EmailService.php` - Servicio para envío de emails
2. `src/models/PasswordResetModel.php` - Modelo para tokens de recuperación
3. `src/views/login/olvide_password.php` - Formulario "Olvidé mi contraseña"
4. `src/views/login/restablecer_password.php` - Formulario nueva contraseña
5. `database/add_email_field.sql` - Script para agregar campo email
6. `database/create_password_reset_table.sql` - Script para tabla de tokens

### Archivos modificados:

1. `composer.json` - Agregada librería PHPMailer
2. `.env` - Agregadas variables de configuración SMTP
3. `src/models/usuarioModel.php` - Agregados métodos `obtenerPorEmail()` y `actualizarPassword()`
4. `src/controllers/LoginController.php` - Agregados métodos de recuperación
5. `config/routes.php` - Agregadas 4 nuevas rutas
6. `src/views/login/index.php` - Agregado enlace "¿Olvidaste tu contraseña?"

---

## 🧪 CÓMO PROBAR EL SISTEMA

### Prueba Local (sin email real):

Si quieres probar sin configurar SMTP, puedes:

1. Comentar temporalmente el envío de email en `LoginController.php` línea 141-145
2. Ver el token generado en los logs o en la base de datos
3. Copiar el token y construir la URL manualmente:
   ```
   http://localhost/qr_eys/public/restablecer-password?token=XXXXXX
   ```

### Prueba con Mailtrap (recomendado para desarrollo):

1. Crea cuenta gratis en https://mailtrap.io/
2. Copia las credenciales SMTP de tu inbox
3. Actualiza el `.env` con esas credenciales
4. Todos los emails se capturan en Mailtrap (no se envían de verdad)
5. Puedes ver y probar los emails ahí

### Prueba en Producción:

1. Configura Gmail con contraseña de aplicación
2. Prueba con un email real tuyo
3. Verifica que llegue el correo
4. Haz clic en el enlace y restablece la contraseña

---

## 🔒 CARACTERÍSTICAS DE SEGURIDAD

✅ **Tokens únicos y seguros** - Generados con `random_bytes(32)`
✅ **Expiración de tokens** - Válidos solo por 1 hora
✅ **Tokens de un solo uso** - Se marcan como usados al cambiar la contraseña
✅ **Protección CSRF** - Todos los formularios protegidos
✅ **Contraseñas hasheadas** - Usando `password_hash()` con BCRYPT
✅ **Validación de emails** - Filtros de seguridad
✅ **Prevención de enumeración** - Siempre muestra el mismo mensaje
✅ **Sanitización de entrada** - Protección contra XSS e inyecciones

---

## 🚨 SOLUCIÓN DE PROBLEMAS

### Problema: No llegan los emails

**Soluciones:**
- Verifica que las credenciales SMTP sean correctas
- Revisa la carpeta de spam/correo no deseado
- Asegúrate que Gmail tenga verificación en 2 pasos activada
- Verifica que la contraseña de aplicación esté correcta
- Revisa los logs de PHP: `error_log` en tu servidor

### Problema: Error al cargar EmailService

**Soluciones:**
- Ejecuta `composer dump-autoload` en la terminal
- Verifica que PHPMailer esté instalado: `composer show phpmailer/phpmailer`
- Asegúrate que exista el archivo `src/utils/EmailService.php`

### Problema: Token inválido o expirado

**Soluciones:**
- Los tokens expiran en 1 hora, solicita uno nuevo
- Verifica que la tabla `password_reset_tokens` exista
- Asegúrate de no haber usado el mismo token dos veces

### Problema: No se actualiza la contraseña

**Soluciones:**
- Verifica que el usuario tenga un email válido en la BD
- Revisa que la contraseña tenga al menos 8 caracteres
- Confirma que ambas contraseñas sean idénticas

---

## 📊 MANTENIMIENTO

### Limpiar tokens expirados (opcional):

Puedes ejecutar esta consulta periódicamente en phpMyAdmin:

```sql
-- Eliminar tokens expirados o usados
DELETE FROM password_reset_tokens
WHERE expira_en < NOW() OR usado = 1;
```

O puedes agregar un cron job que llame a:
```php
$passwordResetModel->limpiarTokensExpirados();
```

---

## 📧 PERSONALIZACIÓN DEL EMAIL

Para personalizar el diseño del email, edita el método `getPlantillaRecuperacion()` en:
`src/utils/EmailService.php` (líneas 75-190)

Puedes cambiar:
- Colores del diseño
- Textos y mensajes
- Logo (agrega una imagen en línea)
- Estilos CSS

---

## 📞 SOPORTE

Si tienes problemas, revisa:
1. Los logs de PHP: `C:\xampp\php\logs\php_error_log`
2. Los logs de Apache: `C:\xampp\apache\logs\error.log`
3. La consola del navegador (F12)
4. Los mensajes de error en pantalla

---

## ✨ ¡LISTO PARA USAR!

Una vez configurado todo:

1. ✅ Los usuarios pueden recuperar su contraseña desde el login
2. ✅ Reciben emails profesionales con instrucciones
3. ✅ El sistema es seguro y confiable
4. ✅ Los tokens expiran automáticamente

**¡Tu sistema de recuperación de contraseña está completo!** 🎉
