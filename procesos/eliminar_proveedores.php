<?php
include('../config/conexion.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "UPDATE proveedores SET estado = 'Inactivo' WHERE id_proveedor = $id";

    if (mysqli_query($conexion, $sql)) {
        header("Location: ../pages/admin/proveedores.php?msg=eliminado");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);