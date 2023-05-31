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

// Obtener pedidos pendientes
$pedidosQuery = "SELECT * FROM pedidos WHERE completado = 0";
$pedidosStmt = $pdo->prepare($pedidosQuery);
$pedidosStmt->execute();
$pedidos = $pedidosStmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener platos y bebidas para actualizar precios
$platosQuery = "SELECT * FROM platos";
$platosStmt = $pdo->prepare($platosQuery);
$platosStmt->execute();
$platos = $platosStmt->fetchAll(PDO::FETCH_ASSOC);

$bebidasQuery = "SELECT * FROM bebidas";
$bebidasStmt = $pdo->prepare($bebidasQuery);
$bebidasStmt->execute();
$bebidas = $bebidasStmt->fetchAll(PDO::FETCH_ASSOC);

// Función para marcar un pedido como preparado
function marcarPedidoPreparado($pdo, $pedidoId) {
    $updateQuery = "UPDATE pedidos SET completado = 1 WHERE id = :pedidoId";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $updateStmt->execute();
}
function eliminarPlato($pdo, $platoId) {
    $deleteQuery = "DELETE FROM platos WHERE id = :platoId";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindParam(':platoId', $platoId, PDO::PARAM_INT);
    $deleteStmt->execute();
}

// Función para eliminar una bebida
function eliminarBebida($pdo, $bebidaId) {
    $deleteQuery = "DELETE FROM bebidas WHERE id = :bebidaId";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindParam(':bebidaId', $bebidaId, PDO::PARAM_INT);
    $deleteStmt->execute();
}
// Función para actualizar precios de platos y bebidas
function actualizarPrecios($pdo, $datos) {
    foreach ($datos as $tabla => $registros) {
        foreach ($registros as $registro) {
            $updateQuery = "UPDATE $tabla SET precio = :precio WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':precio', $registro['precio'], PDO::PARAM_STR);
            $updateStmt->bindParam(':id', $registro['id'], PDO::PARAM_INT);
            $updateStmt->execute();
        }
    }
}

// Acciones al recibir formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['marcarPreparado'])) {
        $pedidoId = $_POST['pedidoId'];
        marcarPedidoPreparado($pdo, $pedidoId);
    } elseif (isset($_POST['actualizarPrecios'])) {
        $datosPlatos = $_POST['platos'];
        $datosBebidas = $_POST['bebidas'];
        actualizarPrecios($pdo, ['platos' => $datosPlatos, 'bebidas' => $datosBebidas]);
    } elseif (isset($_POST['agregarPlato'])) {
        $nombre = $_POST['platoNombre'];
        $tipo = $_POST['platoTipo'];
        $precio = $_POST['platoPrecio'];
        $stock = $_POST['platoStock'];

        $insertQuery = "INSERT INTO platos (nombre, tipo, precio, stock) VALUES (:nombre, :tipo, :precio, :stock)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $insertStmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $insertStmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $insertStmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $insertStmt->execute();
    } elseif (isset($_POST['agregarBebida'])) {
        $nombre = $_POST['bebidaNombre'];
        $tipo = $_POST['bebidaTipo'];
        $precio = $_POST['bebidaPrecio'];
        $stock = $_POST['bebidaStock'];

        $insertQuery = "INSERT INTO bebidas (nombre, tipo, precio, stock) VALUES (:nombre, :tipo, :precio, :stock)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $insertStmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $insertStmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $insertStmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $insertStmt->execute();
    }elseif (isset($_POST['eliminarPlato'])) {
        $platoId = $_POST['platoId'];
        eliminarPlato($pdo, $platoId);
    } elseif (isset($_POST['eliminarBebida'])) {
        $bebidaId = $_POST['bebidaId'];
        eliminarBebida($pdo, $bebidaId);
    }
}

// Cerrar la conexión a la base de datos
$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Interfaz del Camarero</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        form {
            display: inline;
        }

        h2 {
            margin-bottom: 10px;
        }

        form.add-form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        form.add-form label {
            margin-bottom: 5px;
        }

        form.add-form input[type="text"],
        form.add-form select {
            width: 200px;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 3px;
        }

        form.add-form input[type="submit"] {
            padding: 8px 15px;
            background-color: #80ba2f; /* Verde */
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h1>Interfaz del Camarero</h1>

<h2>Pedidos Pendientes</h2>
<table>
    <tr>
        <th>Pedido ID</th>
        <th>Mesa</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($pedidos as $pedido) { ?>
        <tr>
            <td><?php echo $pedido['id']; ?></td>
            <td><?php echo $pedido['mesa']; ?></td>
            <td>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="pedidoId" value="<?php echo $pedido['id']; ?>">
                    <input type="submit" name="marcarPreparado" value="Marcar como preparado">
                </form>
                
            </td>
        </tr>
    <?php } ?>
</table>

<h2>Actualizar Precios</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <h3>Platos</h3>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
        </tr>
        <?php foreach ($platos as $plato) { ?>
            <tr>
                <td><?php echo $plato['nombre']; ?></td>
                <td>
                    <input type="hidden" name="platos[<?php echo $plato['id']; ?>][id]"
                           value="<?php echo $plato['id']; ?>">
                    <input type="text" name="platos[<?php echo $plato['id']; ?>][precio]"
                           value="<?php echo $plato['precio']; ?>">
                </td>
                <td>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="platoId" value="<?php echo $plato['id']; ?>">
                        <input type="submit" name="eliminarPlato" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h3>Bebidas</h3>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
        </tr>
        <?php foreach ($bebidas as $bebida) { ?>
            <tr>
                <td><?php echo $bebida['nombre']; ?></td>
                <td>
                    <input type="hidden" name="bebidas[<?php echo $bebida['id']; ?>][id]"
                           value="<?php echo $bebida['id']; ?>">
                    <input type="text" name="bebidas[<?php echo $bebida['id']; ?>][precio]"
                           value="<?php echo $bebida['precio']; ?>">
                </td>
                 <td>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="bebidaId" value="<?php echo $bebida['id']; ?>">
                        <input type="submit" name="eliminarBebida" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <input type="submit" name="actualizarPrecios" value="Actualizar Precios">
</form>

<h2>Agregar Nuevo Plato</h2>
<form class="add-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="platoNombre">Nombre:</label>
    <input type="text" name="platoNombre" id="platoNombre" required>

    <label for="platoTipo">Tipo:</label>
    <select name="platoTipo" id="platoTipo" required>
        <option value="entrante">Entrante</option>
        <option value="plato">Plato Principal</option>
        <option value="postre">Postre</option>
    </select>

    <label for="platoPrecio">Precio:</label>
    <input type="text" name="platoPrecio" id="platoPrecio" required>

    <label for="platoStock">Stock:</label>
    <input type="text" name="platoStock" id="platoStock" required>

    <input type="submit" name="agregarPlato" value="Agregar Plato">
</form>

<h2>Agregar Nueva Bebida</h2>
<form class="add-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="bebidaNombre">Nombre:</label>
    <input type="text" name="bebidaNombre" id="bebidaNombre" required>

    <label for="bebidaTipo">Tipo:</label>
    <select name="bebidaTipo" id="bebidaTipo" required>
        <option value="alcoholica">Alcohólica</option>
        <option value="no_alcoholica">No Alcohólica</option>
        <option value="cafe">Café</option>
    </select>

    <label for="bebidaPrecio">Precio:</label>
    <input type="text" name="bebidaPrecio" id="bebidaPrecio" required>

    <label for="bebidaStock">Stock:</label>
    <input type="text" name="bebidaStock" id="bebidaStock" required>
    <input type="submit" name="agregarBebida" value="Agregar Bebida">
</form>
</body>
</html>
