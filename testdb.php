<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "✅ Conexión exitosa a la base de datos: " . $_ENV['DB_NAME'];
} else {
    echo "❌ Error al conectar.";
}
