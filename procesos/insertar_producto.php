<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $categoria = $_POST['categoria'];
  $precio = $_POST['precio'];
  $stock = $_POST['stock'];

  $sql = "INSERT INTO productos (nombre_producto, categoria, precio, stock) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt, "ssdi", $nombre, $categoria, $precio, $stock);

  if (mysqli_stmt_execute($stmt)) {
    echo "<script src='../dependencias/sweetalert2@11.js'></script>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Producto agregado',
        text: 'El producto fue agregado exitosamente.',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        window.location.href = '../pages/ingresar_producto.php';
      });
    </script>";
  } else {
    echo 'Error al insertar producto: ' . mysqli_error($conexion);
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conexion);
}
?>
