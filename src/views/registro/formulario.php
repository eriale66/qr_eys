<?php
require_once __DIR__ . '/../../utils/CSRF.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Acceso</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f6fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
}
h2 {
    color: #1f3c88;
}
form {
    margin-top: 20px;
}
input[type="text"] {
    width: 350px;
    padding: 15px;
    font-size: 18px;
    border: 2px solid #1f3c88;
    border-radius: 8px;
    text-align: center;
}
</style>
</head>

<!-- <?php include __DIR__ . '/../layout/header.php'; ?> -->

<body>
    <h2>Escanee su código QR</h2>
    <form method="POST" action="/qr_eys/public/registrar-acceso">
        <?= CSRF::inputField() ?>
        <input type="text" name="codigo" autofocus autocomplete="off" placeholder="Coloque el cursor aquí y escanee">
    </form>
</body>
</html>
