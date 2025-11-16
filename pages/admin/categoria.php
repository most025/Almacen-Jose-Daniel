<?php
include('../../config/conexion.php');

// Solo traer las categorías activas
$sql = "SELECT * FROM categoria WHERE estado = 'Activo'";
$ejecucionDeConsulta = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Categorías</title>
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <script src="../../assets/js/script.js"></script>
  <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
  <script src="../../dependencias/sweetalert2@11.js"></script>
</head>

<style>
  .form-categoria-input {
    padding: 10px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 50px;
    width: 530px;
    background-color: #F5F5F0;
  }
  .tab-btn, a {
    text-decoration: none;
    color: #F9F8F6;
    font-weight: bold;
  }
  .modal-agregar-categoria, .modal-editar-categoria {
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
  .modal-contenido, .modal-contenido-editar-categoria {
    background-color: #fefefe;
    margin: 10% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 40%; 
    border-radius: 10px;
  }
  .cerrar, .cerrarEditarCategoria {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }
  .cerrar:hover, .cerrarEditarCategoria:hover {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
  #tabla-categorias {
    background-color: #0d1b4c;
    color: white;
  }
  #boton-secundario {
    background-color: #FFE797;
    color: #37353E;
    font-weight: bold;
  }
  #boton-secundario:hover {
    background-color: #FFD966;
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
        <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Productos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
        <a href="reporte_ventas.php" class="active"><span class="icon">📈</span> <span class="text">Reportes de Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <header class="header">
        <h1 class="title">Administrar Categorías</h1>
        <div class="icons">
          <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
          <script src="../../assets/js/alerta.js"></script>
        </div>
      </header>

      <main class="content">
        <div class="tabs">
          <button class="tab-btn active" data-tab="categorias"><a href="categoria.php">Categorías</a></button>
          <button class="tab-btn active" id="boton-secundario" onclick="window.location.href='ingresar_producto.php'">Carga Producto</button>
        </div>

        <button class="btn-primary" onclick="abrirModal()">Agregar Categoría</button>

        <!-- Modal agregar -->
        <div id="modalAgregar" class="modal-agregar-categoria">
          <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal()">&times;</span>
            <h2>Agregar Nueva Categoría</h2>
            <form class="form-categoria" action="../../procesos/insertar_categoria.php" method="POST">
              <input class="form-categoria-input" type="text" name="nombre" placeholder="Nombre de la Categoría" required>
              <input class="form-categoria-input" type="text" name="descripcion" placeholder="Descripción de la Categoría" required>
              <button class="btn-primary" type="submit">Agregar</button>
            </form>
          </div>
        </div>

        <!-- Tabla -->
        <table class="tabla">
          <thead id="tabla-categorias">
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($filas = mysqli_fetch_assoc($ejecucionDeConsulta)) { ?>
            <tr>
              <td><?php echo $filas['nombre_categoria']; ?></td>
              <td><?php echo $filas['descripcion']; ?></td>
              <td><?php echo $filas['estado']; ?></td>
              <td>
                <button class="btn-secondary" 
                  onclick="abrirModalEditar(<?php echo $filas['id_categoria']; ?>, 
                    '<?php echo $filas['nombre_categoria']; ?>', 
                    '<?php echo $filas['descripcion']; ?>')">Editar</button>

                <button class="btn-primary" 
                  onclick="eliminarCategoria(<?php echo $filas['id_categoria']; ?>)">Eliminar</button>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

        <!-- Modal editar -->
        <div id="editarCategoria" class="modal-editar-categoria">
          <div class="modal-contenido-editar-categoria">
            <span class="cerrarEditarCategoria" onclick="cerrarModalEditar()">&times;</span>
            <h2>Editar Categoría</h2>
            <form class="form-categoria" action="../../procesos/editar_categoria.php" method="POST">
              <input type="hidden" id="id_categoria" name="id_categoria">
              <input class="form-categoria-input" type="text" id="nombre_edit" name="nombre" placeholder="Nombre de la Categoría" required>
              <input class="form-categoria-input" type="text" id="descripcion_edit" name="descripcion" placeholder="Descripción" required>
              <button class="btn-primary" type="submit">Guardar Cambios</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    function abrirModal() {
      document.getElementById("modalAgregar").style.display = "block";
    }
    function cerrarModal() {
      document.getElementById("modalAgregar").style.display = "none";
    }
    function abrirModalEditar(id, nombre, descripcion) {
      document.getElementById("editarCategoria").style.display = "block";
      document.getElementById("id_categoria").value = id;
      document.getElementById("nombre_edit").value = nombre;
      document.getElementById("descripcion_edit").value = descripcion;
    }
    function cerrarModalEditar() {
      document.getElementById("editarCategoria").style.display = "none";
    }

    // Función para inactivar una categoría (borrado lógico)
    function eliminarCategoria(id) {
      Swal.fire({
        title: "¿Marcar como inactiva?",
        text: "La categoría no se eliminará, solo se marcará como inactiva.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, marcar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../procesos/eliminar_categoria.php?id=${id}`)
            .then(response => response.text())
            .then(data => {
              Swal.fire("Actualizada", "La categoría fue marcada como inactiva.", "success")
              .then(() => location.reload());
            })
            .catch(() => Swal.fire("Error", "No se pudo actualizar la categoría.", "error"));
        }
      });
    }
  </script>
</body>
<?php mysqli_close($conexion); ?>
</html>
