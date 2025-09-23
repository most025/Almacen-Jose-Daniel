<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ventas</title>
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
        <a href="administradores_de_artículos.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <!-- Header -->
      <header class="header">
        <h1 class="title">Ventas</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn active" data-tab="vender">Vender Producto</button>
          <button class="tab-btn" data-tab="reportes">Reportes y Ventas</button>
        </div>

        <!-- VENDER PRODUCTO -->
        <div class="tab-content active" id="vender">
          <div class="venta-grid">
            <!-- Formulario de venta -->
            <div class="venta-form">
              <h3>Vender un producto</h3>
              <form>
                <label>Selecciona Cliente:</label>
                <select>
                  <option value="">-- Seleccionar Cliente --</option>
                  <option value="1">Carlos Pérez</option>
                  <option value="2">Ana López</option>
                </select>

                <label>Selecciona Producto:</label>
                <select>
                  <option value="">-- Seleccionar Producto --</option>
                  <option value="1">Coca-Cola</option>
                  <option value="2">Papas Fritas</option>
                </select>

                <label>Descripción:</label>
                <input type="text" placeholder="Descripción">

                <label>Cantidad:</label>
                <input type="number" placeholder="Cantidad">

                <label>Precio:</label>
                <input type="number" step="0.01" placeholder="Precio">

                <button class="btn-primary" type="button">Agregar al carrito</button>
              </form>
            </div>

            <!-- Carrito de venta -->
            <div class="venta-carrito">
              <h3>Hacer Venta</h3>
              <p><strong>Nombre de cliente:</strong> <span id="clienteVenta">No seleccionado</span></p>
              <table class="tabla">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Quitar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Coca-Cola</td>
                    <td>Botella 2L</td>
                    <td>$2.50</td>
                    <td>2</td>
                    <td><button class="btn-secondary">X</button></td>
                  </tr>
                </tbody>
              </table>
              <p><strong>Total de venta:</strong> $0</p>
              <button class="btn-primary">Finalizar Venta</button>
            </div>
          </div>
        </div>

        <!-- REPORTES -->
        <div class="tab-content" id="reportes">
          <h3>Reportes y Ventas</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total de compra</th>
                <th>Ticket</th>
                <th>Reporte</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>001</td>
                <td>2025-09-15</td>
                <td>Carlos Pérez</td>
                <td>$50.00</td>
                <td><button class="btn-secondary">Descargar PDF</button></td>
                <td><button class="btn-primary">Descargar PDF</button></td>
              </tr>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>
</body>

</html>