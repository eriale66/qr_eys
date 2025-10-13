<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado de Registro</title>
<style>
body {
    font-family: Arial, sans-serif;
    text-align: center;
    background: #f4f6f8;
}
.message {
    margin-top: 100px;
    padding: 20px;
    border-radius: 10px;
    display: inline-block;
    font-size: 20px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
<div class="message">
    <p><?= $mensaje ?></p>
</div>
<meta http-equiv="refresh" content="2; url=/registro">
</body>
</html>
