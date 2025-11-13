<?php
include('../config/conexion.php');
$id = $_GET['id'];
$sql = "UPDATE categoria SET estado = 'Inactivo' WHERE id_categoria = '$id'";
mysqli_query($conexion, $sql);
mysqli_close($conexion);
?>
