<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "almacen_db"; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Filtro del reporte (diario o mensual)
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'diario';

// Consulta SQL con JOIN doble para obtener la descripción del producto
if ($filtro == 'mensual') {
    $sql = "SELECT v.id_venta, cp.descripcion AS producto, v.cantidad_vendida, 
                   v.total_venta, v.metodo_pago, v.fecha_venta
            FROM ventas_productos v
            INNER JOIN inventario_productos ip ON v.id_inventario = ip.id_inventario
            INNER JOIN carga_productos cp ON ip.id_carga = cp.id_carga
            WHERE MONTH(v.fecha_venta) = MONTH(CURRENT_DATE())
              AND YEAR(v.fecha_venta) = YEAR(CURRENT_DATE())
            ORDER BY v.fecha_venta DESC";
} else {
    $sql = "SELECT v.id_venta, cp.descripcion AS producto, v.cantidad_vendida, 
                   v.total_venta, v.metodo_pago, v.fecha_venta
            FROM ventas_productos v
            INNER JOIN inventario_productos ip ON v.id_inventario = ip.id_inventario
            INNER JOIN carga_productos cp ON ip.id_carga = cp.id_carga
            WHERE DATE(v.fecha_venta) = CURDATE()
            ORDER BY v.fecha_venta DESC";
}

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container">
        <h1 class="text-center mb-4">📊 Reporte de Ventas (<?= ucfirst($filtro) ?>)</h1>

        <!-- Botones de acción -->
        <div class="mb-3 text-center">
            <a href="reporte_ventas.php?filtro=diario" class="btn btn-primary">📅 Diario</a>
            <a href="reporte_ventas.php?filtro=mensual" class="btn btn-success">🗓️ Mensual</a>
            <button class="btn btn-danger" onclick="window.print()">🖨️ Imprimir Reporte</button>
        </div>

        <?php
        if ($resultado && $resultado->num_rows > 0) {
            echo "<table class='table table-bordered table-striped text-center align-middle'>";
            echo "<thead class='table-dark'>
                    <tr>
                        <th>ID Venta</th>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th>Total Venta</th>
                        <th>Método de Pago</th>
                        <th>Fecha Venta</th>
                        <th>Ticket</th>
                    </tr>
                  </thead><tbody>";

            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>{$fila['id_venta']}</td>
                        <td>{$fila['producto']}</td>
                        <td>{$fila['cantidad_vendida']}</td>
                        <td><strong>$" . number_format($fila['total_venta'], 2) . "</strong></td>
                        <td>{$fila['metodo_pago']}</td>
                        <td>{$fila['fecha_venta']}</td>
                        <td>
                            <a href='generar_ticket.php?id_venta={$fila['id_venta']}' target='_blank' 
                               class='btn btn-outline-primary btn-sm'>Ticket</a>
                        </td>
                      </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p class='text-center text-muted mt-4'>No se encontraron ventas registradas.</p>";
        }
        ?>
    </div>
</body>
</html>
