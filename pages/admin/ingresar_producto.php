<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Eliminar producto si se recibe un ID por GET
if (isset($_GET['eliminar'])) {
  $id_carga = $_GET['eliminar'];

  // Eliminar primero del inventario (por la clave foránea)
  $conn->query("DELETE FROM inventario_productos WHERE id_carga = $id_carga");

  // Luego eliminar del registro principal
  $conn->query("DELETE FROM carga_productos WHERE id_carga = $id_carga");

  header("Location: ventas.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Productos - Almacén</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="logo.ico" type="image/x-icon">
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="text-center mb-4">
      <img src="logo.png" alt="Logo" width="180" height="90">
    </div>
    <nav>
      <a href="index.php">🏠 Inicio</a>
      <a href="proveedores.php">📦 Proveedores</a>
      <a href="ingresar_producto.php">🛠️ Administrar Artículos</a>
      <a href="clientes.php">👥 Clientes</a>
      <a href="ventas.php">💰 Ventas</a>
    </nav>
  </aside>

  <!-- Main -->
  <div class="main">
    <!-- Header -->
    <header class="header">
      <h1 class="title">PRODUCTOS</h1>
      <div class="icons">
        <span>👤</span>
        <span>⚙️</span>
      </div>
    </header>

    <!-- Contenido principal -->
    <div class="main-content container-fluid">
      <div class="row">
        
        <!-- Formulario -->
        <div class="col-md-4 mb-4">
          <div class="card p-4 shadow-sm">
            <h4 class="text-center mb-3">Nuevos Productos</h4>
            <form action="guardar_producto.php" method="POST">
              <div class="mb-3">
                <label class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre_producto" class="form-control" placeholder="Ej: Arroz 1kg" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="2" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-control" placeholder="Ej: Alimentos" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Precio de Compra</label>
                <input type="number" name="precio_compra" class="form-control" step="0.01" min="0" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Precio de Venta</label>
                <input type="number" name="precio_venta" class="form-control" step="0.01" min="0" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Stock Disponible</label>
                <input type="number" name="stock" class="form-control" min="0" required>
              </div>
              <button type="submit" class="btn btn-success w-100">Registrar Producto</button>
            </form>
          </div>
        </div>

        <!-- Tabla -->
        <div class="col-md-8">
          <div class="card p-4 shadow-sm">
            <h4 class="mb-3">Listado de Productos</h4>
            <div class="table-container">
              <table class="table table-striped table-hover align-middle">
                <thead class="table-success">
                  <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>$ - Com</th>
                    <th>$ - Vta</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT cp.id_carga, cp.nombre_producto, cp.categoria, cp.precio_compra, cp.precio_venta, 
                                 ip.stockDisponible
                          FROM carga_productos cp
                          LEFT JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
                          ORDER BY cp.id_carga DESC";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                    while ($producto = $result->fetch_assoc()) {
                      $nombre = htmlspecialchars($producto['nombre_producto']);
                      echo "<tr>
                              <td>{$nombre}</td>
                              <td>{$producto['categoria']}</td>
                              <td>\${$producto['precio_compra']}</td>
                              <td>\${$producto['precio_venta']}</td>
                              <td>{$producto['stockDisponible']}</td>
                              <td>
                                <a href='editar_producto.php?id={$producto['id_carga']}' class='btn btn-warning btn-sm me-1'>✏️ Editar</a>
                                <button class='btn btn-danger btn-sm' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#confirmarEliminarModal' 
                                        data-id='{$producto['id_carga']}' 
                                        data-nombre='{$nombre}'>
                                        🗑️ Eliminar
                                </button>
                              </td>
                            </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='6' class='text-center'>No hay productos registrados.</td></tr>";
                  }
                  $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-2">¿Seguro que deseas eliminar este producto?</p>
        <p><strong id="nombreProductoEliminar" class="text-danger"></strong></p>
        <p class="text-muted"><small>Esta acción no se puede deshacer.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Eliminar</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Captura el id y nombre del producto al abrir el modal
const eliminarModal = document.getElementById('confirmarEliminarModal');
eliminarModal.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  const idProducto = button.getAttribute('data-id');
  const nombreProducto = button.getAttribute('data-nombre');

  const confirmarBtn = document.getElementById('btnConfirmarEliminar');
  const nombreSpan = document.getElementById('nombreProductoEliminar');

  confirmarBtn.href = '?eliminar=' + idProducto;
  nombreSpan.textContent = nombreProducto;
});
</script>

</body>
</html>
