<?php
include('../config/conexion.php');
// $conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
// if ($conn->connect_error) {
//   die("Error de conexión: " . $conn->connect_error);
// }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_carga = intval($_POST['id_carga']);
  $nombre = $_POST['nombre_producto'];
  $descripcion = $_POST['descripcion'];
  $categoria = intval($_POST['categoria']);
  $precio_compra = floatval($_POST['precio_compra']);
  $precio_venta = floatval($_POST['precio_venta']);
  $stock = intval($_POST['stockDisponible']);

  // Transacción para asegurar integridad
  $conexion->begin_transaction();

  try {
    $sql1 = "UPDATE carga_productos 
             SET nombre_producto=?, descripcion=?, id_categoria=?, precio_compra=?, precio_venta=?
             WHERE id_carga=?";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("ssiddi", $nombre, $descripcion, $categoria, $precio_compra, $precio_venta, $id_carga);
    $stmt1->execute();

    $sql2 = "UPDATE inventario_productos 
             SET stockDisponible=? 
             WHERE id_carga=?";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("ii", $stock, $id_carga);
    $stmt2->execute();

    $conexion->commit();

    // echo "<script>
    //         alert('✅ Producto actualizado correctamente.');
    //         window.location.href='../pages/admin/ingresar_producto.php';
    //       </script>";
    ?>
    <html>
<head>
    <meta charset="UTF-8">
    <title>Producto Actualizado</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.ico">
    <script src="../dependencias/sweetalert2@11.js"></script>
    <body>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Producto actualizado correctamente',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        window.location.href = '../pages/admin/ingresar_producto.php';
    });
    </script>
    </body>
    </html>
    <?php
  } catch (Exception $e) {
    $conexion->rollback();
    echo "<script>
            alert('❌ Error al actualizar el producto.');
            window.location.href='../pages/admin/ingresar_producto.php';
          </script>";
  }
}
$conexion->close();
?>