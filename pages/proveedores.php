<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proveedores</title>
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
        <a href="index.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
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
        <h1 class="title">Gestión de Proveedores</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn active" data-tab="crud">Agregar Proveedores (CRUD)</button>
          <button class="tab-btn" data-tab="pedidos">Pedidos</button>
          <button class="tab-btn" data-tab="pagos">Pagos</button>
        </div>

        <!-- CRUD -->
        <div class="tab-content active" id="crud">
          <h3>CRUD de Proveedores</h3>
          <form class="form-proveedor">
            <input type="text" placeholder="Nombre del Proveedor">
            <input type="text" placeholder="Teléfono">
            <input type="text" placeholder="Email">
            <button class="btn-primary" type="button">Agregar</button>
          </form>
          <table class="tabla">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Proveedor A</td>
                <td>123456789</td>
                <td>a@correo.com</td>
                <td>
                  <button class="btn-secondary">Editar</button>
                  <button class="btn-primary">Eliminar</button>
                </td>
              </tr>
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
</body>

</html>