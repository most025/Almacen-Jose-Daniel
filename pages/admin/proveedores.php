<?php
include('../../config/conexion.php');
$sql = "SELECT * FROM proveedores";
$ejecucionDeConsulta = mysqli_query($conexion, $sql);
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
<style>
  .modal-agregar-proveedores {
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

  .form-proveedor {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
  }

  .form-proveedor input {
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

  .inactivo {
    text-decoration: line-through;
    color: red;
    opacity: 0.7;
  }
</style>

<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo"><img src="../../assets/img/logo.ico" alt="Descripción de la imagen" width="200" height="100"></div>
      <nav>
        <a href="dashboard.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="proveedores.php" class="active"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
        <a href="reporte_ventas.php"><span class="icon">📈</span> <span class="text">Reportes de Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <header class="header">
        <h1 class="title">Gestión de Proveedores</h1>
        <div class="icons">
          <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
          <script src="../../assets/js/alerta.js"></script>
        </div>
      </header>

      <main class="content">
        <div class="tabs">
          <button class="tab-btn active" onclick="abrirModalProveedor()">Agregar Proveedor</button>
        </div>

        <!-- MODAL AGREGAR -->
        <div id="agregarProveedor" class="modal-agregar-proveedores">
          <div class="modal-contenido-proveedor">
            <span class="cerrar" onclick="cerrarModalProveedor()">&times;</span>
            <h3>Nuevo Proveedor</h3>
            <form class="form-proveedor" method="post" action="../../procesos/insertar_proveedores.php">
              <input type="text" name="nombre" placeholder="Nombre del Proveedor" required>
              <input type="text" name="direccion" placeholder="Dirección" required>
              <input type="tel" name="telefono" placeholder="Teléfono" required>
              <input type="email" name="email" placeholder="Email" required>
              <input type="hidden" name="estado" value="Activo">
              <button class="btn-primary" type="submit">Agregar</button>
            </form>
          </div>
        </div>

        <!-- TABLA DE PROVEEDORES -->
        <table class="tabla">
          <thead class="tabla-encabezado">
            <tr>
              <th>Nombre</th>
              <th>Dirección</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($filas = mysqli_fetch_assoc($ejecucionDeConsulta)) { ?>
              <tr class="<?php echo $filas['estado'] === 'Inactivo' ? 'inactivo' : ''; ?>">
                <td><?php echo $filas['nombre_proveedor']; ?></td>
                <td><?php echo $filas['direccion']; ?></td>
                <td><?php echo $filas['telefono']; ?></td>
                <td><?php echo $filas['email']; ?></td>
                <td><?php echo $filas['estado']; ?></td>
                <td>
                  <button class="btn-secondary" onclick="abrirModalEditar(<?php echo $filas['id_proveedor']; ?>, '<?php echo $filas['nombre_proveedor']; ?>', '<?php echo $filas['direccion']; ?>', '<?php echo $filas['telefono']; ?>', '<?php echo $filas['email']; ?>')">Editar</button>
                  <?php if ($filas['estado'] === 'Activo') { ?>
                    <button class="btn-primary" onclick="cambiarEstadoProveedor(<?php echo $filas['id_proveedor']; ?>, 'Inactivo')">Desactivar</button>
                  <?php } else { ?>
                    <button class="btn-primary" onclick="cambiarEstadoProveedor(<?php echo $filas['id_proveedor']; ?>, 'Activo')">Activar</button>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

        <!-- MODAL EDITAR -->
        <div id="editarProveedor" class="modal-editar-proveedores">
          <div class="modal-contenido-editar">
            <span class="cerrar" onclick="cerrarModalEditar()">&times;</span>
            <h3>Editar Proveedor</h3>
            <form class="form-proveedor" method="post" action="../../procesos/editar_proveedores.php">
              <input type="hidden" id="id_proveedor" name="id_proveedor">
              <input type="text" id="nombre_edit" name="nombre" placeholder="Nombre del Proveedor" required>
              <input type="text" id="direccion_edit" name="direccion" placeholder="Dirección del Proveedor" required>
              <input type="tel" id="telefono_edit" name="telefono" placeholder="Teléfono del Proveedor" required>
              <input type="email" id="email_edit" name="email" placeholder="Email del Proveedor" required>
              <button class="btn-primary" type="submit">Actualizar</button>
            </form>
          </div>
        </div>

      </main>
    </div>
  </div>

  <script>
    function abrirModalProveedor() {
      document.getElementById("agregarProveedor").style.display = "block";
    }

    function cerrarModalProveedor() {
      document.getElementById("agregarProveedor").style.display = "none";
    }

    function abrirModalEditar(id, nombre, direccion, telefono, email) {
      document.getElementById("editarProveedor").style.display = "block";
      document.getElementById("id_proveedor").value = id;
      document.getElementById("nombre_edit").value = nombre;
      document.getElementById("direccion_edit").value = direccion;
      document.getElementById("telefono_edit").value = telefono;
      document.getElementById("email_edit").value = email;
    }

    function cerrarModalEditar() {
      document.getElementById("editarProveedor").style.display = "none";
    }

    function cambiarEstadoProveedor(id, nuevoEstado) {
      fetch(`../../procesos/cambiar_estado_proveedor.php?id=${id}&estado=${nuevoEstado}`)
        .then(response => response.text())
        .then(data => location.reload());
    }
  </script>

  <?php mysqli_close($conexion); ?>
</body>
</html>
