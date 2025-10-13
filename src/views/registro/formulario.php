<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Acceso</title>
<style>
body {
    font-family: Arial, sans-serif;
    text-align: center;
    background: #f4f6f8;
    color: #333;
}
input[type=text] {
    width: 80%;
    padding: 15px;
    font-size: 18px;
    margin-top: 50px;
    text-align: center;
}
</style>
</head>
<body>
<h2>Escanee su código QR</h2>
<form method="POST" action="/registrar-acceso">
    <input type="text" name="codigo" autofocus autocomplete="off" placeholder="Coloque el cursor aquí y escanee">
</form>
</body>
</html>
