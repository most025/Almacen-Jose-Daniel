<?php
require_once __DIR__ . '/dompdf-3.1.4/autoload.inc.php';
use Dompdf\Dompdf;

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "almacen_jose_daniel"; 

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("<p style='color:red;'>Error de conexión a la base de datos.</p>");
}

$filtro = $_GET['filtro'] ?? 'diario';

// === CONSULTA PRINCIPAL ===
if ($filtro == 'mensual') {
    $sql = "SELECT id_venta, descripcion AS producto, cliente, total_venta, metodo_pago, fecha_venta
            FROM ventas_productos
            WHERE MONTH(fecha_venta) = MONTH(CURRENT_DATE())
              AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())
            ORDER BY fecha_venta DESC";
} else {
    $sql = "SELECT id_venta, descripcion AS producto, cliente, total_venta, metodo_pago, fecha_venta
            FROM ventas_productos
            WHERE DATE(fecha_venta) = CURDATE()
            ORDER BY fecha_venta DESC";
}

$resultado = $conn->query($sql);

// === Calcular total en efectivo ===
$total_efectivo = 0;
if ($resultado && $resultado->num_rows > 0) {
    $resultado->data_seek(0);
    while ($fila = $resultado->fetch_assoc()) {
        if (strtolower(trim($fila['metodo_pago'])) === 'efectivo') {
            $total_efectivo += (float)$fila['total_venta'];
        }
    }
}

// === Calcular producto más vendido ===
$sql_mas_vendido = "SELECT descripcion AS producto, COUNT(*) AS cantidad
                    FROM ventas_productos
                    WHERE " . ($filtro == 'mensual' 
                        ? "MONTH(fecha_venta)=MONTH(CURRENT_DATE()) AND YEAR(fecha_venta)=YEAR(CURRENT_DATE())" 
                        : "DATE(fecha_venta)=CURDATE()") . "
                    GROUP BY descripcion
                    ORDER BY cantidad DESC
                    LIMIT 1";
$mas_vendido = $conn->query($sql_mas_vendido)->fetch_assoc();

// Volver a ejecutar la consulta principal para generar tabla
$resultado = $conn->query($sql);

// === HTML DEL PDF ===
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Ventas <?= ucfirst(htmlspecialchars($filtro)) ?></title>
<style>
body {
  font-family: 'DejaVu Sans', sans-serif;
  font-size: 12px;
  color: #333;
  margin: 20px;
  background-color: #fff;
}

h1 {
  text-align: center;
  color: #0d1b4c;
  margin-bottom: 10px;
}

.logo {
  text-align: center;
  margin-bottom: 10px;
}

.logo img {
  width: 90px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

th, td {
  border: 1px solid #999;
  padding: 6px;
  text-align: center;
}

thead th {
  background-color: #0d1b4c;
  color: white;
}

tr:nth-child(even) {
  background-color: #f2f4fa;
}

.resumen {
  margin-top: 20px;
  background: #eef1f8;
  border-radius: 6px;
  padding: 10px;
}

.resumen p {
  margin: 5px 0;
}

footer {
  text-align: center;
  font-size: 10px;
  color: #555;
  margin-top: 25px;
  border-top: 1px solid #ccc;
  padding-top: 5px;
}
</style>
</head>
<body>

<div class="logo">
  <img src="logo.png" alt="Logo">
  <h1>📊 Reporte de Ventas <?= ucfirst($filtro) ?></h1>
</div>

<?php if ($resultado && $resultado->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>ID Venta</th>
        <th>Producto</th>
        <th>Cliente</th>
        <th>Total Venta</th>
        <th>Método de Pago</th>
        <th>Fecha Venta</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($fila = $resultado->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($fila['id_venta']) ?></td>
        <td><?= htmlspecialchars($fila['producto']) ?></td>
        <td><?= htmlspecialchars($fila['cliente']) ?></td>
        <td>$<?= number_format($fila['total_venta'], 2) ?></td>
        <td><?= htmlspecialchars($fila['metodo_pago']) ?></td>
        <td><?= htmlspecialchars($fila['fecha_venta']) ?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <div class="resumen">
    <p><strong>Total en efectivo:</strong> $<?= number_format($total_efectivo, 2) ?></p>
    <p><strong>Producto más vendido:</strong> <?= htmlspecialchars($mas_vendido['producto'] ?? 'N/A') ?></p>
  </div>

<?php else: ?>
  <p style="text-align:center; color:#777;">No se encontraron ventas registradas.</p>
<?php endif; ?>

<footer>
  <p>Sistema de Almacén — Reporte <?= ucfirst($filtro) ?> | Generado el <?= date("d/m/Y H:i") ?></p>
</footer>

</body>
</html>
<?php
$html = ob_get_clean();

// === Generar PDF con Dompdf ===
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar el PDF
$dompdf->stream("reporte_ventas_$filtro.pdf", ["Attachment" => true]);

$conn->close();
?>
