<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Programa</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
<script src="../../dependencias/sweetalert2@11.js"></script>
</head>
<body>
  <div class="container">
    
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo"><img src="../../assets/img/logo.ico" alt="Descripción de la imagen" width="200" height="100"></div>
      <nav>
        <a href="dashboard_vendedor.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="#"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="#"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="#"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="#"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>

    </aside>

    <!-- Main -->
    <div class="main">
      <!-- Header -->
      <header class="header">
        <h1 class="title">Inicio</h1>
        <div class="icons">
          <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
          <script src="../../assets/js/alerta.js"></script>
          <!-- <span>👤</span>
          <span>⚙️</span> -->
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <h2>Bienvenido</h2>
        <p>Selecciona una opción del menú lateral.</p>
      </main>
    </div>
  </div>
</body>
</html>
