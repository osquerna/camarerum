<?php
require('fpdf/fpdf.php');
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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pedidoId = $_GET['pedido_id'] ?? null;

if ($pedidoId) {
   
    $updateEstadoQuery = "UPDATE pedidos SET pagado = 1 WHERE id = :pedidoId";
    $updateEstadoStmt = $pdo->prepare($updateEstadoQuery);
    $updateEstadoStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $updateEstadoStmt->execute();


    $detallesQuery = "SELECT platos.nombre AS plato, bebidas.nombre AS bebida
                      FROM pedidos_detalle
                      LEFT JOIN platos ON pedidos_detalle.plato_id = platos.id
                      LEFT JOIN bebidas ON pedidos_detalle.bebida_id = bebidas.id
                      WHERE pedidos_detalle.pedido_id = :pedidoId";
    $detallesStmt = $pdo->prepare($detallesQuery);
    $detallesStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $detallesStmt->execute();
    $detalles = $detallesStmt->fetchAll(PDO::FETCH_ASSOC);

  
    echo '<form action="generar_ticket.php" method="POST">';
    echo '<input type="hidden" name="pedido_id" value="' . $pedidoId . '">';
    echo '<input type="submit" name="descargar" value="Descargar Ticket">';
    echo '</form>';
    }

    
   
    $pdo = null;
 else {
    echo "No se ha especificado un pedido válido.";
}
?>
