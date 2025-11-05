<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se pasa un ID de producto
if (!isset($_GET['id'])) {
  die("Error: ID de producto no especificado.");
}

$id_carga = intval($_GET['id']);

// Obtener los datos del producto y su inventario
$sql = "SELECT cp.id_carga, cp.nombre_producto, cp.descripcion, cp.categoria,
               cp.precio_compra, cp.precio_venta, ip.stockDisponible
        FROM carga_productos cp
        LEFT JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
        WHERE cp.id_carga = $id_carga";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
  die("Error: Producto no encontrado.");
}

$producto = $result->fetch_assoc();

// Si el formulario fue enviado, actualizar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre_producto'];
  $descripcion = $_POST['descripcion'];
  $categoria = $_POST['categoria'];
  $precio_compra = $_POST['precio_compra'];
  $precio_venta = $_POST['precio_venta'];
  $stock = $_POST['stockDisponible'];

  // Actualizar carga_productos
  $sql1 = "UPDATE carga_productos 
           SET nombre_producto='$nombre', descripcion='$descripcion', categoria='$categoria', 
               precio_compra='$precio_compra', precio_venta='$precio_venta'
           WHERE id_carga = $id_carga";

  // Actualizar inventario_productos
  $sql2 = "UPDATE inventario_productos 
           SET stockDisponible='$stock' 
           WHERE id_carga = $id_carga";

  if ($conn->query($sql1) && $conn->query($sql2)) {
    header("Location: ingresar_producto.php");

    exit();
  } else {
    echo "<div class='alert alert-danger text-center'>Error al actualizar el producto.</div>";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e9f7ef, #d4efdf);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      width: 100%;
      max-width: 600px;
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <div class="card p-4 shadow">
    <h3 class="text-center mb-3 text-success">✏️ Editar Producto</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Nombre del Producto</label>
        <input type="text" name="nombre_producto" class="form-control" value="<?= $producto['nombre_producto'] ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="2" required><?= $producto['descripcion'] ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Categoría</label>
        <input type="text" name="categoria" class="form-control" value="<?= $producto['categoria'] ?>" required>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Precio de Compra</label>
          <input type="number" name="precio_compra" class="form-control" step="0.01" min="0" value="<?= $producto['precio_compra'] ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Precio de Venta</label>
          <input type="number" name="precio_venta" class="form-control" step="0.01" min="0" value="<?= $producto['precio_venta'] ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Stock Disponible</label>
        <input type="number" name="stockDisponible" class="form-control" min="0" value="<?= $producto['stockDisponible'] ?>" required>
      </div>

      <div class="d-flex justify-content-between">
        <a href="ventas.php" class="btn btn-secondary">⬅️ Volver</a>
        <button type="submit" class="btn btn-success">💾 Guardar</button>
      </div>
    </form>
  </div>
</body>
</html>
