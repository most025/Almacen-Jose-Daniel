<?php
include('../config/conexion.php');
$sql = "SELECT * FROM categoria";
$ejecucionDeConsulta= mysqli_query($conexion, $sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Artículos</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <script src="../assets/js/script.js"></script>
   <link rel="shortcut icon" href="../assets/img/logo.ico" type="image/x-icon">
</head>
<body>
  <div class="container">
    
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo"><img src="../assets/img/logo.ico" alt="Descripción de la imagen" width="200" height="100"></div>
       <nav>
        <a href="dashboad.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="productos.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <!-- Header -->
      <header class="header">
        <h1 class="title">Administrar Artículos</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn active" data-tab="categorias">Categorías</button>
          <button class="tab-btn" data-tab="articulos">Artículos</button>
        </div>

        <!-- CATEGORÍAS -->
        <div class="tab-content active" id="categorias">
          <h3>Gestión de Categorías</h3>
          <form class="form-categoria">
            <input type="text" placeholder="Nombre de la Categoría">
            <button class="btn-primary" type="button">Agregar</button>
          </form>
          <table class="tabla">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre de Categoría</th>
                <th>Descripcion</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($filas = mysqli_fetch_assoc($ejecucionDeConsulta)){ ?>
              <tr>
                <td><?php echo $filas['id_categoria']; ?></td>
                <td><?php echo $filas['nombre_categoria']; ?></td>
                <td><?php echo $filas['descripcion']; ?></td>
                <td>
                  <button class="btn-secondary">Editar</button>
                  <button class="btn-primary">Eliminar</button>
                </td>
              </tr>
               <?php } ?>
            </tbody>
          </table>
        </div>

        <!-- ARTÍCULOS -->
        <div class="tab-content" id="articulos">
          <h3>Gestión de Artículos</h3>
          <form class="form-articulo" enctype="multipart/form-data">
            <!-- Selección de Categoría -->
            <label for="categoria">Categoría:</label>
            <select id="categoria">
              <option value="">Selecciona Categoría</option>
              <option value="1">Bebidas</option>
              <option value="2">Snacks</option>
            </select>

            <!-- Datos del artículo -->
            <input type="text" placeholder="Nombre del Artículo">
            <textarea placeholder="Descripción"></textarea>
            <input type="number" placeholder="Cantidad">
            <input type="number" step="0.01" placeholder="Precio">

            <!-- Imagen -->
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen">

            <button class="btn-primary" type="button">Agregar</button>
          </form>

          <table class="tabla">
            <thead>
              <tr>
                <th>ID</th>
                <th>Categoría</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Bebidas</td>
                <td>Coca-Cola</td>
                <td>Botella 2L</td>
                <td>50</td>
                <td>$2.50</td>
                <td><img src="coca.jpg" alt="Coca" width="40"></td>
                <td>
                  <button class="btn-secondary">Editar</button>
                  <button class="btn-primary">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
</body>
<?php
mysqli_close($conexion);
?>
</html>
