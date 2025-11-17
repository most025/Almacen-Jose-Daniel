<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

// Datos del formulario
$cliente = trim($_POST['cliente']);
$metodo_pago = trim($_POST['metodo_pago']);
$total_venta = floatval($_POST['total_venta']);
$fecha_venta = date("Y-m-d");

// Decodificar JSON de productos
$productos_json = $_POST['productos_seleccionados'];
$productos = json_decode($productos_json, true);

if (!$productos || count($productos) === 0) {
    die("❌ No hay productos seleccionados.");
}

// Crear la descripción agrupada y actualizar stock
$descripcion_arr = [];
foreach ($productos as $p) {
    $id_carga = intval($p['id_carga']);
    $cantidad = intval($p['cantidad']);

    // Obtener nombre del producto y stock actual
    $stmt = $conn->prepare("SELECT nombre_producto, stockDisponible FROM carga_productos cp
                            INNER JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
                            WHERE cp.id_carga = ?");
    $stmt->bind_param("i", $id_carga);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        die("❌ Producto con id $id_carga no encontrado.");
    }
    $row = $res->fetch_assoc();
    $nombre = $row['nombre_producto'];
    $stock_actual = intval($row['stockDisponible']);
    $stmt->close();

    if ($cantidad > $stock_actual) {
        die("❌ Stock insuficiente para $nombre. Disponible: $stock_actual, pedido: $cantidad.");
    }

    $descripcion_arr[] = "$nombre x$cantidad";

    // Actualizar stock
    $nuevo_stock = $stock_actual - $cantidad;
    $stmt2 = $conn->prepare("UPDATE inventario_productos SET stockDisponible = ? WHERE id_carga = ?");
    $stmt2->bind_param("ii", $nuevo_stock, $id_carga);
    $stmt2->execute();
    $stmt2->close();
}

// Insertar venta (una sola fila con productos agrupados)
$descripcion_final = implode(", ", $descripcion_arr);
$stmt3 = $conn->prepare("INSERT INTO ventas_productos
    (descripcion, id_cliente, total_venta, fecha_venta, metodo_pago)
    VALUES (?, ?, ?, ?, ?)");
$stmt3->bind_param("sidss",  $descripcion_final, $cliente, $total_venta, $fecha_venta, $metodo_pago);

if ($stmt3->execute()) {
    echo "✅ Venta registrada correctamente.";
    // Redirigir a reporte de ventas
    header("Location: reporte_ventas.php");
    exit;
} else {
    echo "❌ Error al registrar venta: " . $stmt3->error;
}

$stmt3->close();
$conn->close();
?>
