<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $sql = "INSERT INTO proveedores (nombre_proveedor, direccion, telefono, email) VALUES (?, ?, ?, ?)";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssis", $nombre, $direccion, $telefono, $email);
        if (mysqli_stmt_execute($consultaPreparada)) {
            echo "Proveedor agregado exitosamente.";
            header("Location: ../pages/proveedores.php");
        } else {
            echo "Error al agregar proveedor: " . mysqli_error($conexion);
        }
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);