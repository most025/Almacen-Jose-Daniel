<?php
require_once '../config/conexion.php';

if (isset($_GET['id_categoria'])) {
    $id_categoria = $_GET['id_categoria'];
    $sql = "DELETE FROM categoria WHERE id_categoria = ?";
    $stmt = $conexion->prepare($sql);   
    if ($stmt) {
        $stmt->bind_param("i", $id_categoria);
        if ($stmt->execute()) {
            header("Location: ../pages/productos.php");
            exit();
        }
    }
}