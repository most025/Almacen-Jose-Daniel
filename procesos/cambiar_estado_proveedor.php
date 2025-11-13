<?php
include('../config/conexion.php');

$id = $_GET['id'];
$estado = $_GET['estado'];

$sql = "UPDATE proveedores SET estado = '$estado' WHERE id_proveedor = $id";
mysqli_query($conexion, $sql);

mysqli_close($conexion);
?>
