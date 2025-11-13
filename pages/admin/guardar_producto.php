<?php
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre_producto'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['categoria'];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$stock = $_POST['stock'];

// Insertar en carga_productos
$sql1 = "INSERT INTO carga_productos (nombre_producto, descripcion, id_categoria, precio_compra, precio_venta, estado) 
         VALUES ('$nombre', '$descripcion', '$categoria', '$precio_compra', '$precio_venta', 'Activo')";

if ($conn->query($sql1) === TRUE) {
    $id_carga = $conn->insert_id;

    // Insertar en inventario_productos
    $sql2 = "INSERT INTO inventario_productos (id_carga, stockDisponible) 
             VALUES ('$id_carga', '$stock')";
    $conn->query($sql2);

    echo "<script>
        alert('✅ Producto registrado correctamente');
        window.location.href='ingresar_producto.php';
    </script>";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

$conn->close();
?>
