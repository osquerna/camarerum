<?php
// Archivo db_connect.php (conexión a la base de datos)
$host = 'bssmsfpa.mysql.db';
$dbname = 'bssmsfpa';
$username = 'bssmsfpa';
$password = 'Lakylag9';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Obtener el ID del pedido a marcar como preparado
$pedidoId = $_POST['pedidoId'];

// Actualizar el estado del pedido a preparado en la base de datos
$updateQuery = "UPDATE pedidos SET preparado = 1 WHERE id = :pedidoId";
$updateStmt = $pdo->prepare($updateQuery);
$updateStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
$updateStmt->execute();

// Cerrar la conexión a la base de datos
$pdo = null;

// Redireccionar a la página principal del camarero
header("Location: camarero.php");
exit();
