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
<link rel="icon" href="logo.ico" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background-color: #f8f9fa;
  font-family: 'Segoe UI', sans-serif;
  color: #333;
  margin: 20px;
}
.table-wrapper {
  overflow-x: auto;
}
.btn-imprimir {
  display: block;
  margin: 30px auto;
  background-color: #0d1b4c;
  color: white;
  border: none;
  padding: 10px 25px;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
}
.btn-imprimir:hover {
  background-color: #0a3b99;
}
.resumen {
  background-color: #e9ecef;
  border-radius: 8px;
  padding: 15px;
  margin-top: 20px;
}
footer {
  text-align: center;
  font-size: 12px;
  color: #777;
  margin-top: 40px;
}
@media print {
  .btn-imprimir { display: none; }
  body { background: white; margin: 0; }
}
</style>
</head>
<body>

<?php if ($resultado && $resultado->num_rows > 0): ?>

  <div class="table-wrapper">
    <table class="table table-bordered table-striped text-center align-middle">
      <thead class="table-dark">
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
      $totalGeneral = 0;
      $totalEfectivo = 0;
      $totalTransferencia = 0;

      while ($fila = $resultado->fetch_assoc()):
          $totalGeneral += (float)$fila['total_venta'];
          $metodo = strtolower(trim($fila['metodo_pago'] ?? ''));

          if ($metodo === 'efectivo') {
              $totalEfectivo += (float)$fila['total_venta'];
          } elseif ($metodo === 'transferencia') {
              $totalTransferencia += (float)$fila['total_venta'];
          }
      ?>
        <tr>
          <td><?= htmlspecialchars($fila['id_venta']) ?></td>
          <td><?= htmlspecialchars($fila['producto']) ?></td>
          <td><?= htmlspecialchars($fila['cliente']) ?></td>
          <td><strong>$<?= number_format($fila['total_venta'], 2) ?></strong></td>
          <td><?= htmlspecialchars($fila['metodo_pago']) ?></td>
          <td>
      <a href="generar_ticket.php?id_venta=<?= urlencode($fila['id_venta']) ?>" class="btn btn-success btn-sm">
        Ticket
      </a>
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
