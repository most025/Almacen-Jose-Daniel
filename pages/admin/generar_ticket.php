<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "almacen_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id_venta = $_GET['id_venta'] ?? 0;

$sql = "SELECT v.id_venta, i.descripcion AS producto, v.cantidad_vendida, 
               v.total_venta, v.metodo_pago, v.fecha_venta
        FROM ventas_productos v
        INNER JOIN inventario i ON v.id_inventario = i.id_inventario
        WHERE v.id_venta = $id_venta";

$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $venta = $result->fetch_assoc();
} else {
    die("Venta no encontrada");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: monospace;
            margin: 20px;
        }
        h2 {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .ticket {
            width: 300px;
            margin: auto;
        }
        .totales {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 5px;
        }
        .center { text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <div class="ticket">
        <h2>🧾 Ticket de Venta</h2>
        <p><strong>ID Venta:</strong> <?= $venta['id_venta'] ?></p>
        <p><strong>Producto:</strong> <?= $venta['producto'] ?></p>
        <p><strong>Cantidad Vendida:</strong> <?= $venta['cantidad_vendida'] ?></p>
        <p><strong>Método de Pago:</strong> <?= $venta['metodo_pago'] ?></p>
        <p><strong>Fecha:</strong> <?= $venta['fecha_venta'] ?></p>
        <div class="totales">
            <p><strong>Total:</strong> $<?= number_format($venta['total_venta'], 2) ?></p>
        </div>
        <p class="center">¡Gracias por su compra!</p>
    </div>
</body>
</html>
