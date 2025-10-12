<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Clientes</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <script src="../../assets/js/script.js"></script>
  <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
</head>
<body>
<div class="container">
    
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo"><img src="../../assets/img/logo.ico" width="200" height="100"></div>
      <nav>
        <a href="dashboard.php">🏠 Inicio</a>
        <a href="proveedores.php">📦 Proveedores</a>
        <a href="productos.php">🛠️ Artículos</a>
        <a href="clientes.php" class="active">👥 Clientes</a>
        <a href="ventas.php">💰 Ventas</a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <header class="header">
        <h1 class="title">Administrar Clientes</h1>
      </header>

      <main class="content">
        <h3>Gestión de Clientes</h3>

        <!-- FORMULARIO -->
        <form class="form-cliente">
            <input type="text" placeholder="Nombre">
            <input type="text" placeholder="Apellido">
            <input type="text" placeholder="DNI">
            <input type="text" placeholder="Dirección">
            <input type="email" placeholder="Email">
            <input type="tel" placeholder="Teléfono">
            <button class="btn-primary" type="button">Agregar</button>
        </form>
         <div class="filtro-busqueda">
            <input type="text" placeholder="DNI cliente...">
            <button class="btn-secondary">Buscar</button>
        </div>

        <!-- TABLA DE EJEMPLO -->
        <table class="tabla">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Comprobante</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Carlos</td>
                <td>Pérez</td>
                <td>43432214</td>
                <td>Calle Falsa 123</td>
                <td>carlos@mail.com</td>
                <td>123456789</td>
                <td><button class="btn-secondary">Descargar Recibo</button></td>
                <td>
                  <button class="btn-secondary">Editar</button>
                  <button class="btn-primary">Eliminar</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Ana</td>
                <td>López</td>
                <td>33432234</td>
                <td>Avenida Principal 456</td>
                <td>ana@empresa.com</td>
                <td>987654321</td>
                <td><button class="btn-secondary">Descargar Recibo</button></td>
                <td>
                  <button class="btn-secondary">Editar</button>
                  <button class="btn-primary">Eliminar</button>
                </td>
              </tr>
            </tbody>
        </table>
      </main>
    </div>
</div>

</body>
</html>

