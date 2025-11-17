<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "almacen_jose_daniel";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$id_venta = $_GET['id_venta'] ?? 0;

// 🔍 Consulta según estructura real de tu tabla
$sql = "SELECT vp.id_venta, vp.descripcion AS producto, concat(cl.nombre, ' ', cl.apellido) AS cliente, vp.total_venta, vp.metodo_pago, vp.fecha_venta
        FROM ventas_productos vp
        INNER JOIN clientes cl ON vp.id_cliente = cl.id_cliente
        WHERE vp.id_venta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $venta = $result->fetch_assoc();
} else {
    die("⚠️ Venta no encontrada o error en la consulta.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta #<?= $venta['id_venta'] ?></title>
    <link rel="icon" type="image/x-icon" href="logo.ico">
    <style>
        /* ======== ESTILO GENERAL ======== */
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }
        .ticket {
            width: 300px; /* Tamaño tipo térmica (80 mm) */
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
        }

        /* ======== ENCABEZADO ======== */
        .ticket-header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .ticket-header img {
            width: 50px;
            height: 50px;
            margin-bottom: 5px;
        }
        .ticket-header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .ticket-header small {
            display: block;
            color: #555;
        }

        /* ======== CUERPO ======== */
        .ticket-body p {
            margin: 4px 0;
            font-size: 14px;
        }
        .ticket-body strong {
            font-weight: bold;
        }
        .ticket-body .producto {
            margin-top: 8px;
            border-top: 1px dashed #aaa;
            border-bottom: 1px dashed #aaa;
            padding: 6px 0;
        }

        /* ======== TOTALES ======== */
        .ticket-totales {
            margin-top: 10px;
            border-top: 1px dashed #333;
            padding-top: 6px;
        }
        .ticket-totales p {
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        /* ======== PIE DE PÁGINA ======== */
        .ticket-footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 10px;
            border-top: 1px dashed #aaa;
            padding-top: 6px;
        }

        /* ======== MODO IMPRESIÓN ======== */
        @media print {
            body {
                background: none;
            }
            .ticket {
                box-shadow: none;
                border: none;
                width: 58mm; /* Tamaño impresora térmica */
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="ticket">
        <!-- Encabezado -->
        <div class="ticket-header">
            <div class="logo"><img src="../../assets/img/logo.ico" alt="Descripción de la imagen" width="200" height="100"></div>
            <small>📍 San Juan</small>
        </div>

        <!-- Cuerpo -->
        <div class="ticket-body">
            <p><strong>ID Venta:</strong> <?= $venta['id_venta'] ?></p>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente'], ENT_QUOTES, 'UTF-8') ?></p>
            <div class="producto">
                <strong>Productos:</strong><br>
                <?= nl2br(htmlspecialchars($venta['producto'], ENT_QUOTES, 'UTF-8')) ?>
            </div>
            <p><strong>Método de Pago:</strong> <?= htmlspecialchars($venta['metodo_pago'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Fecha:</strong> <?= date("d/m/Y", strtotime($venta['fecha_venta'])) ?></p>
        </div>

        <!-- Totales -->
        <div class="ticket-totales">
            <p><strong>Total:</strong> <span>$<?= number_format($venta['total_venta'], 2) ?></span></p>
        </div>

        <!-- Pie -->
        <div class="ticket-footer">
            <p>¡Gracias por su compra! 💙</p>
        </div>
    </div>
</body>
</html>
