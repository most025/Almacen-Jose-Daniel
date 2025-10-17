<?php
include('../../config/conexion.php');
$sql = "SELECT * FROM proveedores";
$ejecucionDeConsulta= mysqli_query($conexion, $sql);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proveedores</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <script src="../../assets/js/script.js"></script>
  <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
  <script src="../../dependencias/sweetalert2@11.js"></script>
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
        <h1 class="title">Gestión de Proveedores</h1>
        <div class="icons">
         <!--👤</span>
          <span>⚙️</span>-->
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <!-- <button class="tab-btn active" data-tab="crud">Agregar Proveedores</button> -->
          <!-- <button class="tab-btn" data-tab="pedidos">Pedidos</button>
          <button class="tab-btn" data-tab="pagos">Pagos</button> -->
        </div>

        <!-- CRUD -->
        <div class="tab-content active" id="crud">
          <h3>Proveedores</h3>
          <form class="form-proveedor" method="post" action="../../procesos/insertar_proveedores.php">
            <input type="text" name="nombre" placeholder="Nombre del Proveedor" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="email" placeholder="Email" required>
            <button class="btn-primary" type="submit">Agregar</button>
          </form>
          <table class="tabla">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>direccion</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($filas = mysqli_fetch_assoc($ejecucionDeConsulta)){ ?>
              <tr>
                <td><?php echo $filas['nombre_proveedor']; ?></td>
                <td><?php echo $filas['direccion']; ?></td>
                <td><?php echo $filas['telefono']; ?></td>
                <td><?php echo $filas['email']; ?></td>
                <td>
                  <button class="btn-secondary" onclick="window.location.href='editar_proveedor.php?id=<?php echo $filas['id_proveedor']; ?>'">Editar</button>

                  <button class="btn-primary" onclick="eliminarProveedor(<?php echo $filas['id_proveedor']; ?>)">Eliminar</button>
                    <script src="../../assets/js/alerta.js"></script>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

        <!-- Pedidos -->
        <div class="tab-content" id="pedidos">
          <h3>Pedidos a Proveedores</h3>
          <p>Aquí podrás gestionar los pedidos.</p>
        </div>

        <!-- Pagos -->
        <div class="tab-content" id="pagos">
          <h3>Pagos a Proveedores</h3>
          <p>Aquí podrás gestionar los pagos realizados a los proveedores.</p>
        </div>
      </main>
    </div>
  </div>
  <?php
  mysqli_close($conexion);
  ?>
  <!-- <script src="../assets/js/alerta.js"></script> -->
</body>

</html>