<?php
$hash = password_hash("1234", PASSWORD_BCRYPT);
echo "Contraseña hasheada: " . $hash;
