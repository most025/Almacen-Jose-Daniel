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
    die("<p class='text-danger'>Error de conexión.</p>");
}

$filtro = $_GET['filtro'] ?? 'diario';

// Consulta principal de ventas
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

// Calcular total de ventas en efectivo
$total_efectivo = 0;
if ($resultado && $resultado->num_rows > 0) {
    $resultado->data_seek(0); // reinicia el puntero
    while ($fila = $resultado->fetch_assoc()) {
        if (strtolower($fila['metodo_pago']) == 'efectivo') {
            $total_efectivo += $fila['total_venta'];
        }
    }
}

// Calcular producto más vendido
$sql_mas_vendido = "SELECT descripcion AS producto, COUNT(*) AS cantidad
                    FROM ventas_productos
                    WHERE " . ($filtro == 'mensual' 
                        ? "MONTH(fecha_venta)=MONTH(CURRENT_DATE()) AND YEAR(fecha_venta)=YEAR(CURRENT_DATE())" 
                        : "DATE(fecha_venta)=CURDATE()") . "
                    GROUP BY descripcion
                    ORDER BY cantidad DESC
                    LIMIT 1";
$mas_vendido = $conn->query($sql_mas_vendido)->fetch_assoc();

$resultado = $conn->query($sql); // volver a ejecutar para generar la tabla

// --- HTML DEL REPORTE ---
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Ventas <?= ucfirst($filtro) ?></title>
<style>
body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; margin: 20px; color: #333; }
h1 { text-align: center; color: #0d1b4c; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { border: 1px solid #555; padding: 6px; text-align: center; }
th { background-color: #0d1b4c; color: white; }
.logo { text-align: center; margin-bottom: 10px; }
.resumen { margin-top: 15px; background: #f4f4f4; padding: 10px; border-radius: 6px; }
footer { text-align: center; font-size: 10px; color: #777; margin-top: 20px; }
</style>
</head>
<body>

<div class="logo">
  <img src="logo.png" width="100"><br>
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
      <td><?= $fila['id_venta'] ?></td>
      <td><?= htmlspecialchars($fila['producto'], ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= htmlspecialchars($fila['cliente'], ENT_QUOTES, 'UTF-8') ?></td>
      <td>$<?= number_format($fila['total_venta'], 2) ?></td>
      <td><?= htmlspecialchars($fila['metodo_pago'], ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= $fila['fecha_venta'] ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<div class="resumen">
  <p><strong>Total de Ventas en Efectivo:</strong> $<?= number_format($total_efectivo, 2) ?></p>
  <p><strong>Producto Más Vendido:</strong> <?= htmlspecialchars($mas_vendido['producto'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
</div>

<?php else: ?>
  <p style="text-align:center; color:#777;">No se encontraron ventas registradas.</p>
<?php endif; ?>

<footer>
  <p>Generado automáticamente por el Sistema de Almacén - <?= date("d/m/Y H:i") ?></p>
</footer>

</body>
</html>
<?php
$html = ob_get_clean();

// Crear el PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar el PDF
$dompdf->stream("reporte_ventas_$filtro.pdf", ["Attachment" => true]);

$conn->close();
?>
