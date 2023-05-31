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

$pedidoId = $_GET['pedido_id'] ?? null;

if ($pedidoId) {

    $pedidoQuery = "SELECT * FROM pedidos WHERE id = :pedidoId";
    $pedidoStmt = $pdo->prepare($pedidoQuery);
    $pedidoStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $pedidoStmt->execute();
    $pedido = $pedidoStmt->fetch(PDO::FETCH_ASSOC);


    if ($pedido && $pedido['completado'] == 0) {
       
        echo "El pedido está pendiente. Por favor, espere un momento y refresque la página.";
    } elseif ($pedido && $pedido['completado'] == 1) {
  
        $detallesQuery = "SELECT pd.*, p.nombre AS plato_nombre, b.nombre AS bebida_nombre, p.precio AS plato_precio, b.precio AS bebida_precio 
                          FROM pedidos_detalle pd 
                          JOIN platos p ON pd.plato_id = p.id 
                          LEFT JOIN bebidas b ON pd.bebida_id = b.id
                          WHERE pd.pedido_id = :pedidoId";
        $detallesStmt = $pdo->prepare($detallesQuery);
        $detallesStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
        $detallesStmt->execute();
        $detalles = $detallesStmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($detalles as $detalle) {
            $platoSubtotal = $detalle['plato_precio'] * $detalle['cantidad'];
            $bebidaSubtotal = $detalle['bebida_precio'] * $detalle['cantidad'];
            $total += $platoSubtotal + $bebidaSubtotal;
        }

  
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Detalles del pedido', 0, 1, 'C');

  
        foreach ($detalles as $detalle) {
            $plato = $detalle['plato_nombre'];
            $bebida = $detalle['bebida_nombre'];
            $cantidad = $detalle['cantidad'];
            $precioPlato = $detalle['plato_precio'] * $cantidad;
            $precioBebida = $detalle['bebida_precio'] * $cantidad;

            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, "- Plato: $plato", 0, 1);
            $pdf->Cell(0, 7, "  Precio Plato: $precioPlato", 0, 1);
            $pdf->Cell(0, 7, "  Bebida: $bebida", 0, 1);
            $pdf->Cell(0, 7, "  Precio Bebida: $precioBebida", 0, 1);
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Total a pagar: $' . $total, 0, 1);

     
        $ticketFilename = "ticket_pedido_" . $pedidoId . ".pdf";
        $pdf->Output('F', $ticketFilename);

        
        if (isset($_POST['descargar'])) {
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=$ticketFilename");
            readfile($ticketFilename);
            exit;
        }

        if (isset($_POST['pagar'])) {
    
            $updateEstadoQuery = "UPDATE pedidos SET completado = 2 WHERE id = :pedidoId";
            $updateEstadoStmt = $pdo->prepare($updateEstadoQuery);
            $updateEstadoStmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
            $updateEstadoStmt->execute();

            echo "¡El pedido ha sido pagado y completado!";
        } else {
            echo '<form action="" method="POST">';
            echo '<input type="hidden" name="pedido_id" value="' . $pedidoId . '">';
            echo '<input type="submit" name="pagar" value="Pagar">';
            echo '</form>';
        }

     
        echo '<form action="" method="POST">';
        echo '<input type="hidden" name="pedido_id" value="' . $pedidoId . '">';
        echo '<input type="submit" name="descargar" value="Descargar Ticket">';
        echo '</form>';
    } else {
        echo "No se encontró un pedido válido.";
    }
} else {
    echo "No se ha especificado un pedido válido.";
}

$pdo = null;
?>
