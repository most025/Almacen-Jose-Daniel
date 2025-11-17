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

$SQLvalidacion = "SELECT id_carga FROM carga_productos WHERE nombre_producto = ? AND estado = 'Activo'";
$prepararValidacion = $conn->prepare($SQLvalidacion);
$prepararValidacion->bind_param("s", $nombre);
$prepararValidacion->execute();
$prepararValidacion->store_result();

if ($prepararValidacion->num_rows > 0) {
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Producto Existente</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo.ico">
    <script src="../../dependencias/sweetalert2@11.js"></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Producto duplicado',
            text: 'Ya existe un producto registrado con ese nombre.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = 'ingresar_producto.php';
        });
    </script>
</body>
</html>
<?php
    exit;
}
$prepararValidacion->close();
// Insertar en carga_productos
$sql1 = "INSERT INTO carga_productos (nombre_producto, descripcion, id_categoria, precio_compra, precio_venta, estado) 
         VALUES ('$nombre', '$descripcion', '$categoria', '$precio_compra', '$precio_venta', 'Activo')";

if ($conn->query($sql1) === TRUE) {
    $id_carga = $conn->insert_id;

    // Insertar en inventario_productos
    $sql2 = "INSERT INTO inventario_productos (id_carga, stockDisponible) 
             VALUES ('$id_carga', '$stock')";
    $conn->query($sql2);

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Producto Guardado</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo.ico">
    <script src="../../dependencias/sweetalert2@11.js"></script>
</head>
<body>
    
<script>
    Swal.fire({
        icon: 'success',
        title: 'Producto registrado correctamente',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        window.location.href = 'ingresar_producto.php';
    });
</script>
</body>

<?php
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

$conn->close();
?>
