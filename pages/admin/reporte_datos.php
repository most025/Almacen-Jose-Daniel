<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "almacen_jose_daniel";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("<p class='text-danger'>Error de conexión.</p>");
}

$filtro = $_GET['filtro'] ?? 'diario';

// === CONSULTA PRINCIPAL ===
if ($filtro == 'mensual') {
    $condicion = "MONTH(fecha_venta) = MONTH(CURRENT_DATE()) AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())";
} else {
    $condicion = "DATE(fecha_venta) = CURDATE()";
}

$sql = "SELECT id_venta, descripcion AS producto, cliente, total_venta, metodo_pago, fecha_venta
        FROM ventas_productos
        WHERE $condicion
        ORDER BY fecha_venta DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Ventas <?= ucfirst(htmlspecialchars($filtro)) ?></title>
<link rel="icon" href="../../assets/img/logo.ico" type="image/x-icon">
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6fa;
        color: #333;
        margin: 20px;
    }

    h5 {
        color: #0d1b4c;
        margin-bottom: 10px;
    }

    .table-wrapper {
        overflow-x: auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        font-size: 14px;
    }

    thead {
        background-color: #0d1b4c;
        color: white;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #eef2ff;
    }

    .btn-ticket {
        background-color: #0078d4;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-ticket:hover {
        background-color: #005fa3;
    }

    .resumen {
        background-color: #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        line-height: 1.6;
    }

    .resumen ul {
        list-style: none;
        padding-left: 0;
    }

    .resumen li {
        margin: 5px 0;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #777;
    }

    .text-danger {
        color: #c0392b;
    }

    footer {
        text-align: center;
        font-size: 12px;
        color: #777;
        margin-top: 30px;
    }

    @media print {
        body { background: white; margin: 0; }
        .btn-ticket { display: none; }
    }
</style>
</head>
<body>

<?php if ($resultado && $resultado->num_rows > 0): ?>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Venta N°</th>
          <th>Producto</th>
          <th>Cliente</th>
          <th>Total Venta</th>
          <th>Método de Pago</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      $totalEfectivo = 0;
      $totalTransferencia = 0;

      while ($fila = $resultado->fetch_assoc()):
          $total = (float)$fila['total_venta'];
          $metodo = strtolower(trim($fila['metodo_pago'] ?? ''));

          if ($metodo === 'efectivo') {
              $totalEfectivo += $total;
          } elseif ($metodo === 'transferencia') {
              $totalTransferencia += $total;
          }
      ?>
        <tr>
          <td><?= htmlspecialchars($fila['id_venta']) ?></td>
          <td><?= htmlspecialchars($fila['producto']) ?></td>
          <td><?= htmlspecialchars($fila['cliente']) ?></td>
          <td><strong>$<?= number_format($total, 2) ?></strong></td>
          <td><?= htmlspecialchars($fila['metodo_pago']) ?></td>
          <td>
              <a href="generar_ticket.php?id_venta=<?= urlencode($fila['id_venta']) ?>" class="btn-ticket">🎟️ Ticket</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="resumen">
    <h5>📊 Resumen del Reporte <?= ($filtro == 'mensual') ? 'Mensual' : 'Diario' ?></h5>
    <ul>
      <li><strong>Total en efectivo:</strong> $<?= number_format($totalEfectivo, 2) ?></li>
      <li><strong>Total en transferencias:</strong> $<?= number_format($totalTransferencia, 2) ?></li>
      <li><strong>Total recaudado:</strong> $<?= number_format($totalEfectivo + $totalTransferencia, 2) ?></li>
    </ul>
  </div>

<?php else: ?>
  <p class="text-center text-muted mt-4">No se encontraron ventas registradas.</p>
<?php endif; ?>

<footer>
  <p>Sistema de Almacén © <?= date("Y") ?> — Reporte generado automáticamente</p>
</footer>

</body>
</html>

<?php $conn->close(); ?>

