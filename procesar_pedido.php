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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener los platos y bebidas seleccionados por el cliente
$platosSeleccionados = $_POST['platos'] ?? [];
$bebidasSeleccionadas = $_POST['bebidas'] ?? [];
$mesa = $_POST['mesa'] ?? '';

// Insertar el pedido en la tabla 'pedidos'
$insertPedidoQuery = "INSERT INTO pedidos (mesa) VALUES (:mesa)";
$insertPedidoStmt = $pdo->prepare($insertPedidoQuery);
$insertPedidoStmt->bindParam(':mesa', $mesa, PDO::PARAM_STR);
$insertPedidoStmt->execute();

// Obtener el ID del último pedido insertado
$pedidoId = $pdo->lastInsertId();
$cantidad = 1;
// Insertar los platos seleccionados en la tabla 'pedidos_detalle'
foreach ($platosSeleccionados as $platoId) {
    // Obtener el precio del plato
    $precioPlatoQuery = "SELECT precio FROM platos WHERE id = :platoId";
    $precioPlatoStmt = $pdo->prepare($precioPlatoQuery);
    $precioPlatoStmt->bindParam(':platoId', $platoId, PDO::PARAM_INT);
    $precioPlatoStmt->execute();
    $precioPlato = $precioPlatoStmt->fetchColumn();

    $insertDetalleQuery = "INSERT INTO pedidos_detalle (pedido_id, plato_id, cantidad) VALUES (:pedidoId, :platoId, :cantidad)";
    $insertDetalleStmt = $pdo->prepare($insertDetalleQuery);
    $insertDetalleStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $insertDetalleStmt->bindParam(':platoId', $platoId, PDO::PARAM_INT);
    $insertDetalleStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
    $insertDetalleStmt->execute();
}

// Insertar las bebidas seleccionadas en la tabla 'pedidos_detalle'
foreach ($bebidasSeleccionadas as $bebidaId) {
    // Obtener el precio de la bebida
    $precioBebidaQuery = "SELECT precio FROM bebidas WHERE id = :bebidaId";
    $precioBebidaStmt = $pdo->prepare($precioBebidaQuery);
    $precioBebidaStmt->bindParam(':bebidaId', $bebidaId, PDO::PARAM_INT);
    $precioBebidaStmt->execute();
    $precioBebida = $precioBebidaStmt->fetchColumn();

    $insertDetalleQuery = "INSERT INTO pedidos_detalle (pedido_id, bebida_id, cantidad) VALUES (:pedidoId, :bebidaId, :cantidad)";
    $insertDetalleStmt = $pdo->prepare($insertDetalleQuery);
    $insertDetalleStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $insertDetalleStmt->bindParam(':bebidaId', $bebidaId, PDO::PARAM_INT);
    $insertDetalleStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
    $insertDetalleStmt->execute();
}

// Actualizar el stock de raciones de platos seleccionados
foreach ($platosSeleccionados as $platoId) {
    $updatePlatoQuery = "UPDATE platos SET stock = stock - 1 WHERE id = :platoId";
    $updatePlatoStmt = $pdo->prepare($updatePlatoQuery);
    $updatePlatoStmt->bindParam(':platoId', $platoId, PDO::PARAM_INT);
    $updatePlatoStmt->execute();
}

// Actualizar el stock de raciones de bebidas seleccionadas
foreach ($bebidasSeleccionadas as $bebidaId) {
    $updateBebidaQuery = "UPDATE bebidas SET stock = stock - 1 WHERE id = :bebidaId";
    $updateBebidaStmt = $pdo->prepare($updateBebidaQuery);
    $updateBebidaStmt->bindParam(':bebidaId', $bebidaId, PDO::PARAM_INT);
    $updateBebidaStmt->execute();
}

// Cerrar la conexión a la base de datos
$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$pedidoId = $pedidoId ?? null;
if ($pedidoId) {
    echo "<a href='generar_ticket.php?pedido_id=$pedidoId'>Descargar ticket y pagar.</a>";
} else {
    echo "No se ha especificado un pedido válido.";
}
?>
</body>
</html>
