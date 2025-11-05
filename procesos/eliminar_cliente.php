<?php
require_once '../config/conexion.php';

if (isset($_GET['id_clientes'])) {
    $id_clientes = $_GET['id_clientes'];
    $sql = "DELETE FROM clientes WHERE id_clientes = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_clientes);
        if ($stmt->execute()) {
            header("Location: ../pages/admin/clientes.php");
            exit();
        }
    }
}