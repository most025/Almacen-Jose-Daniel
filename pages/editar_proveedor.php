<?php
include('../config/conexion.php');

if (isset($_GET['id'])) {
    $id_proveedor = $_GET['id'];

    $sql = "SELECT * FROM proveedores WHERE id_proveedor = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_proveedor);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $proveedor = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);
} else {
    echo "ID de proveedor no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="../assets/img/logo.ico" type="image/x-icon">
</head>
<body>
<div class="container">
    <h2>Editar Proveedor</h2>
    <form method="POST" action="../procesos/editar_proveedores.php">
        <input type="hidden" name="id_proveedor" value="<?php echo $proveedor['id_proveedor']; ?>">
        <input type="text" name="nombre" value="<?php echo $proveedor['nombre_proveedor']; ?>" required>
        <input type="text" name="direccion" value="<?php echo $proveedor['direccion']; ?>" required>
        <input type="tel" name="telefono" value="<?php echo $proveedor['telefono']; ?>" required>
        <input type="email" name="email" value="<?php echo $proveedor['email']; ?>" required>
        <button type="submit" class="btn-primary">Actualizar</button>
        <button type="button" class="btn-secondary" onclick="window.location.href='proveedores.php'">Cancelar</button>
    </form>
</div>
</body>
</html>
<?php mysqli_close($conexion); ?>
