<?php
include('../config/conexion.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "UPDATE clientes SET estado = 'Inactivo' WHERE id_cliente = $id";

    if (mysqli_query($conexion, $sql)) {
        header("Location: ../pages/admin/clientes.php?msg=eliminado");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);