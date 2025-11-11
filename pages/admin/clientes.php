<?php
include('../../config/conexion.php');
$sql = "SELECT * FROM clientes";
$ejecucionDeConsulta = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <script src="../../assets/js/script.js"></script>
  <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
  <script src="../../dependencias/sweetalert2@11.js"></script>
</head>

<style>
  .modal-agregar-clientes {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-contenido-cliente {
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

  .cerrar:hover,
  .cerrar:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  .modal-editar-clientes {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
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

  .form-cliente {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
  }

  .form-cliente input {
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

<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo"><img src="../../assets/img/logo.ico" alt="Logo" width="200" height="100"></div>
      <nav>
        <a href="dashboard.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
        <a href="reporte_ventas.php" class="active"><span class="icon">📈</span> <span class="text">Reportes de Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <header class="header">
        <h1 class="title">Gestión de Clientes</h1>
        <div class="icons">
          <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
          <script src="../../assets/js/alerta.js"></script>
        </div>
      </header>

      <main class="content">
        <div class="tabs">
          <button class="tab-btn active" onclick="abrirModalCliente()">Agregar Cliente</button>
        </div>

        <div id="agregarCliente" class="modal-agregar-clientes">
          <div class="modal-contenido-cliente">
            <span class="cerrar" onclick="cerrarModalCliente()">&times;</span>
            <h3>Agregar Cliente</h3>
            <form class="form-cliente" method="post" action="../../procesos/insertar_clientes.php">
              <input type="text" name="nombre" placeholder="Nombre" required>
              <input type="text" name="apellido" placeholder="Apellido" required>
              <input type="text" name="dni" placeholder="DNI" required>
              <input type="text" name="direccion" placeholder="Dirección" required>
              <input type="email" name="email" placeholder="Email" required>
              <input type="tel" name="telefono" placeholder="Teléfono" required>
              <button class="btn-primary" type="submit">Agregar</button>
            </form>
          </div>
        </div>

        <script>
          function abrirModalCliente() {
            document.getElementById("agregarCliente").style.display = "block";
          }
          function cerrarModalCliente() {
            document.getElementById("agregarCliente").style.display = "none";
          }

          function abrirModalEditar(id, nombre, apellido, dni, direccion, email, telefono) {
            document.getElementById("editarCliente").style.display = "block";
            document.getElementById("id_cliente").value = id;
            document.getElementById("nombre_edit").value = nombre;
            document.getElementById("apellido_edit").value = apellido;
            document.getElementById("dni_edit").value = dni;
            document.getElementById("direccion_edit").value = direccion;
            document.getElementById("email_edit").value = email;
            document.getElementById("telefono_edit").value = telefono;
          }

          function cerrarModalEditar() {
            document.getElementById("editarCliente").style.display = "none";
          }

          window.onclick = function(event) {
            var modalEditar = document.getElementById("editarCliente");
            var modalAgregar = document.getElementById("agregarCliente");
            if (event.target == modalEditar) modalEditar.style.display = "none";
            if (event.target == modalAgregar) modalAgregar.style.display = "none";
          }
        </script>

        <table class="tabla">
          <thead class="tabla-encabezado">
            <tr>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>DNI</th>
              <th>Dirección</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($filas = mysqli_fetch_assoc($ejecucionDeConsulta)) { ?>
              <tr>
                <td><?php echo $filas['nombre']; ?></td>
                <td><?php echo $filas['apellido']; ?></td>
                <td><?php echo $filas['dni']; ?></td>
                <td><?php echo $filas['direccion']; ?></td>
                <td><?php echo $filas['email']; ?></td>
                <td><?php echo $filas['telefono']; ?></td>
                <td>
                  <button class="btn-secondary"
                    onclick="abrirModalEditar(<?php echo $filas['id_cliente']; ?>, '<?php echo $filas['nombre']; ?>', '<?php echo $filas['apellido']; ?>', '<?php echo $filas['dni']; ?>', '<?php echo $filas['direccion']; ?>', '<?php echo $filas['email']; ?>', '<?php echo $filas['telefono']; ?>')">Editar</button>
                  <!--<button class="btn-primary" onclick="eliminarCliente(<?php //echo $filas['id_cliente']; ?>)">Eliminar</button>-->
                  <!-- <script src="../../assets/js/alerta.js"></script> -->
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </main>
    </div>
  </div>

  <?php mysqli_close($conexion); ?>

  <div id="editarCliente" class="modal-editar-clientes">
    <div class="modal-contenido-editar">
      <span class="cerrar" onclick="cerrarModalEditar()">&times;</span>
      <h3>Editar Cliente</h3>
      <form class="form-cliente" method="post" action="../../procesos/editar_clientes.php">
        <input type="hidden" id="id_cliente" name="id_cliente">
        <p>Nombre</p>
        <input type="text" id="nombre_edit" name="nombre" required>
        <p>Apellido</p>
        <input type="text" id="apellido_edit" name="apellido" required>
        <p>DNI</p>
        <input type="text" id="dni_edit" name="dni" required>
        <p>Dirección</p>
        <input type="text" id="direccion_edit" name="direccion" required>
        <p>Email</p>
        <input type="email" id="email_edit" name="email" required>
        <p>Teléfono</p>
        <input type="tel" id="telefono_edit" name="telefono" required>
        <button class="btn-primary" type="submit">Actualizar</button>
      </form>
    </div>
  </div>
</body>

</html>


