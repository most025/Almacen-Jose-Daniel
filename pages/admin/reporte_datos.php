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
$dniFiltro = isset($_GET['dni']) ? trim($_GET['dni']) : '';

// === CONDICIÓN PRINCIPAL DEL FILTRO ===
if ($filtro === 'mensual') {
    $condicion = "MONTH(fecha_venta) = MONTH(CURRENT_DATE()) AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())";
} else {
    $condicion = "DATE(fecha_venta) = CURDATE()";
}

// Si se ingresó un DNI, lo agregamos al filtro
if ($dniFiltro !== '') {
    $condicion .= " AND dni = '" . $conn->real_escape_string($dniFiltro) . "'";
}

$sql = "SELECT id_venta, descripcion AS producto, cliente, dni, total_venta, metodo_pago, fecha_venta
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
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(180deg, #0d1b4c, #020a1f);
        color: white;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #fff;
        margin-bottom: 25px;
        font-size: 24px;
    }

    .filtros {
        background: white;
        color: #333;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 25px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    label {
        font-weight: bold;
        margin-right: 10px;
    }

    select, input[type="text"], button {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
        margin: 5px;
    }

    button {
        background-color: #0d1b4c;
        color: white;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background-color: #e66e1d;
    }

    .table-wrapper {
        overflow-x: auto;
        background: white;
        color: #333;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        margin-bottom: 30px;
        padding: 15px;
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
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-ticket:hover {
        background-color: #005fa3;
    }

    .resumen {
        background-color: #f0f2f7;
        border-radius: 8px;
        padding: 15px 20px;
        margin-top: 20px;
        color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .resumen h5 {
        color: #0d1b4c;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .resumen ul {
        list-style: none;
        padding-left: 0;
    }

    .resumen li {
        margin: 5px 0;
    }

    .text-center { text-align: center; }
    .text-muted { color: #777; }

    footer {
        text-align: center;
        font-size: 12px;
        color: #ccc;
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.3);
    }

    @media print {
        body { background: white; color: black; margin: 0; }
        .filtros, .btn-ticket, button { display: none; }
    }
</style>
</head>
<body>

<h1>📊 Reporte de Ventas <?= ucfirst($filtro) ?></h1>

<!-- FILTROS -->
<div class="filtros">
    <form method="GET">
        <label for="filtro">Tipo de Reporte:</label>
        <select name="filtro" id="filtro">
            <option value="diario" <?= ($filtro === 'diario') ? 'selected' : '' ?>>Diario</option>
            <option value="mensual" <?= ($filtro === 'mensual') ? 'selected' : '' ?>>Mensual</option>
        </select>

        <label for="dni">Filtrar por DNI:</label>
        <input type="text" name="dni" id="dni" value="<?= htmlspecialchars($dniFiltro) ?>" placeholder="Ingrese DNI">

        <button type="submit">🔍 Filtrar</button>
        <button type="button" onclick="window.location.href='reporte_datos.php'">🔄 Limpiar</button>
    </form>
</div>

<?php if ($resultado && $resultado->num_rows > 0): ?>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Venta N°</th>
          <th>Producto</th>
          <th>Cliente</th>
          <th>DNI</th>
          <th>Total Venta</th>
          <th>Método de Pago</th>
          <th>Fecha Venta</th>
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
          <td><?= htmlspecialchars($fila['dni']) ?></td>
          <td><strong>$<?= number_format($total, 2) ?></strong></td>
          <td><?= htmlspecialchars(ucfirst($fila['metodo_pago'])) ?></td>
          <td><?= htmlspecialchars($fila['fecha_venta']) ?></td>
          <td><a href="generar_ticket.php?id_venta=<?= urlencode($fila['id_venta']) ?>" class="btn-ticket">🎟️ Ticket</a></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="resumen">
    <h5>📋 Resumen del Reporte <?= ($filtro == 'mensual') ? 'Mensual' : 'Diario' ?></h5>
    <ul>
      <li><strong>Total en efectivo:</strong> $<?= number_format($totalEfectivo, 2) ?></li>
      <li><strong>Total en transferencias:</strong> $<?= number_format($totalTransferencia, 2) ?></li>
      <li><strong>Total recaudado:</strong> $<?= number_format($totalEfectivo + $totalTransferencia, 2) ?></li>
    </ul>
  </div>

<?php else: ?>
  <p class="text-center text-muted mt-4">No se encontraron ventas registradas<?= $dniFiltro ? " para el DNI <strong>$dniFiltro</strong>" : "" ?>.</p>
<?php endif; ?>

<footer>
  <p>Sistema de Almacén © <?= date("Y") ?> — Reporte generado automáticamente</p>
</footer>

</body>
</html>

<?php $conn->close(); ?>



