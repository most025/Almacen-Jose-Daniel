<?php
$conn = new mysqli("localhost", "root", "", "almacen_db");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cliente = $conn->real_escape_string($_POST['cliente']);
  $metodo_pago = $conn->real_escape_string($_POST['metodo_pago']);
  $productos = json_decode($_POST['productos_seleccionados'], true);
  $total_venta = floatval($_POST['total_venta']);

  foreach ($productos as $prod) {
    $id_carga = intval($prod['id']);
    $cantidad = intval($prod['cantidad']);
    $subtotal = $prod['precio'] * $cantidad;

    // Obtener id_inventario
    $res = $conn->query("SELECT id_inventario, stockDisponible FROM inventario_productos WHERE id_carga = $id_carga");
    $inv = $res->fetch_assoc();
    $id_inventario = $inv['id_inventario'];
    $stockActual = $inv['stockDisponible'];

    // Insertar venta
    $conn->query("INSERT INTO ventas_productos (id_inventario, cantidad_vendida, total_venta, metodo_pago)
                  VALUES ($id_inventario, $cantidad, $subtotal, '$metodo_pago')");

    // Descontar stock
    $nuevoStock = max(0, $stockActual - $cantidad);
    $conn->query("UPDATE inventario_productos SET stockDisponible = $nuevoStock WHERE id_inventario = $id_inventario");
  }

  echo "<script>
          alert('✅ Venta registrada correctamente');
          window.location.href = 'reporte_ventas.php';
        </script>";
}
?>
