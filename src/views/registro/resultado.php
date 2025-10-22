<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado de Registro</title>
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
.message {
    background: white;
    padding: 25px 40px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    font-size: 20px;
    color: #1f3c88;
    text-align: center;
}
</style>
<meta http-equiv="refresh" content="2; url=/qr_eys/public/registro">
</head>
<body>
    <div class="message">
        <p><?= $mensaje ?></p>
    </div>
</body>
</html>
