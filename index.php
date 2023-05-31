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

// Obtener bebidas
$bebidasQuery = "SELECT * FROM bebidas ORDER BY tipo";
$bebidasStmt = $pdo->prepare($bebidasQuery);
$bebidasStmt->execute();
$bebidas = $bebidasStmt->fetchAll(PDO::FETCH_ASSOC);
if ($bebidasStmt->errorCode() !== '00000') {
    print_r($bebidasStmt->errorInfo());
}

// Obtener platos por categoría
$platosQuery = "SELECT * FROM platos ORDER BY tipo";
$platosStmt = $pdo->prepare($platosQuery);
$platosStmt->execute();
$platos = $platosStmt->fetchAll(PDO::FETCH_ASSOC);
if ($platosStmt->errorCode() !== '00000') {
    print_r($platosStmt->errorInfo());
}

// Cerrar la conexión a la base de datos
$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <title>Carta del Restaurante</title>
    <style>
        .categoria {
            margin-bottom: 20px;
        }
        .nombre-plato {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Bienvenido al Comedor</h1>

    <form action="procesar_pedido.php" method="POST">
        <h2>Carta</h2>

        <!-- Platos -->
        <h3>Platos</h3>
        <h4>Entrantes</h4>
        <?php foreach ($platos as $plato): ?>
            <?php if ($plato['tipo'] === 'entrante'): ?>
                <input type="checkbox" name="platos[]" value="<?php echo $plato['id']; ?>">
                <label><?php echo $plato['nombre']; ?> - Precio: <?php echo $plato['precio']; ?></label><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <h4>Platos principales</h4>
        <?php foreach ($platos as $plato): ?>
            <?php if ($plato['tipo'] === 'plato'): ?>
                <input type="checkbox" name="platos[]" value="<?php echo $plato['id']; ?>">
                <label><?php echo $plato['nombre']; ?> - Precio: <?php echo $plato['precio']; ?></label><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <h4>Postres</h4>
        <?php foreach ($platos as $plato): ?>
            <?php if ($plato['tipo'] === 'postre'): ?>
                <input type="checkbox" name="platos[]" value="<?php echo $plato['id']; ?>">
                <label><?php echo $plato['nombre']; ?> - Precio: <?php echo $plato['precio']; ?></label><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Bebidas -->
        <h3>Bebidas</h3>
        <h4>Bebidas alcohólicas</h4>
        <?php foreach ($bebidas as $bebida): ?>
            <?php if ($bebida['tipo'] === 'alcoholica'): ?>
                <input type="checkbox" name="bebidas[]" value="<?php echo $bebida['id']; ?>">
                <label><?php echo $bebida['nombre']; ?> - Precio: <?php echo $bebida['precio']; ?></label><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <h4>Bebidas no alcohólicas</h4>
        <?php foreach ($bebidas as $bebida): ?>
            <?php if ($bebida['tipo'] === 'no_alcoholica'): ?>
                <input type="checkbox" name="bebidas[]" value="<?php echo $bebida['id']; ?>">
                <label><?php echo $bebida['nombre']; ?> - Precio: <?php echo $bebida['precio']; ?></label><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <h4>Cafés o tés</h4>
        <?php foreach ($bebidas as $bebida): ?>
            <?php if ($bebida['tipo'] === 'cafe'): ?>
                <div class="checkbox-container">
                <input type="checkbox" name="bebidas[]" value="<?php echo $bebida['id']; ?>">
                </div>
                <label><?php echo $bebida['nombre']; ?> - Precio: <?php echo $bebida['precio']; ?></label><br>
            <?php endif; ?>
            <?php if ($bebida['tipo'] === 'te'): ?>
                <div class="checkbox-container">
                <input type="checkbox" name="bebidas[]" value="<?php echo $bebida['id']; ?>">
            
                <label><?php echo $bebida['nombre']; ?> - Precio: <?php echo $bebida['precio']; ?></label><br>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>

        <br>
        <label for="mesa">Número de mesa:</label>
        <input type="number" name="mesa" required>

        <br><br>
        <input type="submit" value="Realizar pedido">
    </form>
</body>
</html>
