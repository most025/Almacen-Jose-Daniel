<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Artículos</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <script src="../../assets/js/script.js"></script>
  <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
  <style>
    .form-table-container {
      display: flex;
      gap: 20px; /* Espacio entre el formulario y la tabla */
    }

    .form-articulo {
      flex: 1; /* El formulario ocupa la mitad del contenedor */
    }

    .tabla {
      flex: 1; /* La tabla ocupa la mitad del contenedor */
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo"><img src="../../assets/img/logo.ico" alt="Descripción de la imagen" width="200" height="100"></div>
      <nav>
        <a href="dashboard.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="articulos.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <!-- Header -->
      <header class="header">
        <h1 class="title">Administrar Articulos</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>
        <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn active" data-tab="categorias"><a href="productos.php">Categorías</a></button>
          <button class="tab-btn" data-tab="articulos"><a href="articulos.php">Artículos</a></button>
        </div>
      <main class="content">
        <!-- Contenedor para el formulario y la tabla -->
        <div class="form-table-container">
          <!-- Formulario -->
          <form class="form-articulo" enctype="multipart/form-data">
            <h3>Agregar Artículo</h3>
            <label for="categoria">Categoría:</label>
            <select id="categoria">
              <option value="">Selecciona Categoría</option>
              <option value="1">Bebidas</option>
              <option value="2">Snacks</option>
            </select>

            <input type="text" placeholder="Nombre del Artículo">
            <textarea placeholder="Descripción"></textarea>
            <input type="number" placeholder="Cantidad">
            <input type="number" step="0.01" placeholder="Costo">
            <input type="number" step="0.01" placeholder="Precio">

            <!--<label for="imagen">Imagen:</label>
            <input type="file" id="imagen">-->

            <button class="btn-primary" type="button">Agregar</button>
          </form>

          <!-- Tabla -->
          <div class="tabla-container">
            <h3>Artículos</h3>
            <table class="tabla">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Categoría</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Cantidad</th>
                  <th>Costo</th>
                  <th>Precio</th>
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
                  <td>$3.50</td>
                  <td>
                    <button class="btn-secondary" onclick="window.location.href='editar_producto.php?id=<?php echo $fila['id_producto']; ?>'">Editar</button>
                    <button class="btn-primary" onclick="window.location.href='../procesos/eliminar_productos.php?id_producto=<?php echo $fila['id_producto']; ?>'">Eliminar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>