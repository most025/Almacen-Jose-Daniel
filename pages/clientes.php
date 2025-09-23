<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Clientes</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <script  src="../assets/js/script.js"></script>
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
        <a href="administradores_de_artículos.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <!-- Header -->
      <header class="header">
        <h1 class="title">Administrar Clientes</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn active" data-tab="clientes">Clientes</button>
          <button class="tab-btn" data-tab="clientesNegocio">Clientes de Negocio</button>
        </div>

        <!-- CLIENTES -->
        <div class="tab-content active" id="clientes">
          <h3>Gestión de Clientes</h3>
          <form class="form-cliente">
            <input type="text" placeholder="Nombre">
            <input type="text" placeholder="Apellido">
            <input type="text" placeholder="Dirección">
            <input type="email" placeholder="Email">
            <input type="tel" placeholder="Teléfono">
            <input type="text" placeholder="RFC">
            <button class="btn-primary" type="button">Agregar</button>
          </form>
          <table class="tabla">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>RFC</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Carlos</td>
                <td>Pérez</td>
                <td>Calle Falsa 123</td>
                <td>carlos@mail.com</td>
                <td>123456789</td>
                <td>RFC1234</td>
                <td>
                  <button class="btn-secondary">Editar</button>
                  <button class="btn-primary">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- CLIENTES DE NEGOCIO -->
        <div class="tab-content" id="clientesNegocio">
          <h3>Gestión de Clientes de Negocio</h3>
          <form class="form-cliente-negocio">
            <input type="text" placeholder="Nombre">
            <input type="text" placeholder="Apellido">
            <input type="text" placeholder="Dirección">
            <input type="email" placeholder="Email">
            <input type="tel" placeholder="Teléfono">
            <input type="text" placeholder="RFC">
            <button class="btn-primary" type="button">Agregar</button>
          </form>
          <table class="tabla">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>RFC</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Ana</td>
                <td>López</td>
                <td>Avenida Principal 456</td>
                <td>ana@empresa.com</td>
                <td>987654321</td>
                <td>RFC5678</td>
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
</html>
