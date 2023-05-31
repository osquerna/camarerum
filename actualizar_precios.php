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

// Obtener los datos de precios enviados por el formulario
$datosPlatos = $_POST['platos'];
$datosBebidas = $_POST['bebidas'];

// Actualizar precios de platos en la base de datos
foreach ($datosPlatos as $platoId => $platoData) {
    $updateQuery = "UPDATE platos SET precio = :precio WHERE id = :platoId";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':precio', $platoData['precio'], PDO::PARAM_STR);
    $updateStmt->bindParam(':platoId', $platoId, PDO::PARAM_INT);
    $updateStmt->execute();
}

// Actualizar precios de bebidas en la base de datos
foreach ($datosBebidas as $bebidaId => $bebidaData) {
    $updateQuery = "UPDATE bebidas SET precio = :precio WHERE id = :bebidaId";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':precio', $bebidaData['precio'], PDO::PARAM_STR);
    $updateStmt->bindParam(':bebidaId', $bebidaId, PDO::PARAM_INT);
    $updateStmt->execute();
}

// Cerrar la conexión a la base de datos
$pdo = null;

// Redireccionar a la página principal del camarero
header("Location: camarero.php");
exit();
