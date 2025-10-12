<?php
require_once '../config/conexion.php';

if (isset($_GET['id_proveedor'])) {
    $id_proveedor = $_GET['id_proveedor'];
    $sql = "DELETE FROM proveedores WHERE id_proveedor = ?";
    $stmt = $conexion->prepare($sql);   
    if ($stmt) {
        $stmt->bind_param("i", $id_proveedor);
        if ($stmt->execute()) {
            header("Location: ../pages/admin/proveedores.php");
            exit();
        }
    }
}