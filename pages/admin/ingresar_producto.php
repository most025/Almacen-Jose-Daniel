<?php
include('../../config/conexion.php');
$sql = "SELECT * FROM carga_productos";
$ejecucionDeConsulta= mysqli_query($conexion, $sql);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingresar Producto</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <script src="../../assets/js/script.js"></script>
  <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
  <script src="../../dependencias/sweetalert2@11.js"></script>
  <style>
  .modal-agregar-proveedores{
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
  
  }
   .modal-contenido-proveedor {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40%;
  border-radius: 10px;

  }
  .cerrar {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }
  .cerrar:hover, .cerrar:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
  .modal-editar-proveedores {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-contenido-editar {
  background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
    border-radius: 10px;
    max-width: 500px;

}
p {
  margin: 0;
  font-weight: bold;
  color: black;
  font-size: 11px;
}
.form-proveedor {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
}
.form-proveedor input {
  display: inline;
  width: 100%;
  padding: 10px;
  margin: 5px 0;
  border: 1px solid #ccc;
  border-radius: 5px;

}

.tabla-encabezado {
  background-color: #0d1b4c;
  color: white;

  }
</style>
</head>

<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="../assets/img/logo.ico" alt="Logo" width="200" height="100">
      </div>
      <nav>
        <a href="index.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Ingresar Producto</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <header class="header">
        <h1 class="title">Ingresar Producto</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>

      <main class="content">
        <!-- Tabs centradas -->
        <div class="tabs">
          <button class="tab-btn active" onclick="window.location.href='ingresar_producto.php'">Ingresar Producto</button>
          <button class="tab-btn" onclick="window.location.href='ventas.php'">Ventas</button>
        </div>

        <!-- Formulario para agregar producto -->
        <section>
          <h3>Registrar nuevo producto</h3>
          <form method="POST" action="../procesos/insertar_producto.php" class="form-proveedor">
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="text" name="categoria" placeholder="Categoría" required>
            <input type="number" name="precio" step="0.01" placeholder="Precio" required>
            <input type="number" name="stock" placeholder="Stock disponible" required>
            <button type="submit" class="btn-primary">Agregar Producto</button>
          </form>
        </section>

        <!-- Tabla con productos -->
        <section style="margin-top:30px;">
          <h3>Productos existentes</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($p = mysqli_fetch_assoc($ejecucionDeConsulta)) { ?>
                <tr>
                  <td><?php echo $p['nombre_producto']; ?></td>
                  <td><?php echo $p['categoria']; ?></td>
                  <td>$<?php echo number_format($p['precio'], 2); ?></td>
                  <td><?php echo $p['stock']; ?></td>
                  <td>
                    <button class="btn-secondary" onclick="editarProducto(<?php echo $p['id_producto']; ?>)">Editar</button>
                    <button class="btn-primary" onclick="eliminarProducto(<?php echo $p['id_producto']; ?>)">Eliminar</button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </section>
      </main>
    </div>
  </div>

  <script src="../assets/js/alerta.js"></script>
  <?php mysqli_close($conexion); ?>
</body>
</html>

