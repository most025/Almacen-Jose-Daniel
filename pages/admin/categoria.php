<?php
include('../../config/conexion.php');
$sql = "SELECT * FROM categoria";
$ejecucionDeConsulta= mysqli_query($conexion, $sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Artículos</title>
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
    color: #0d1b4c;
    font-weight: bold;
  }
  .modal-agregar-categoria {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
  }
  .modal-contenido {
    background-color: #fefefe;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 40%; 
    border-radius: 10px;
  }
  .modal-editar-categoria {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
  }
  .modal-contenido-editar-categoria {
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
  .cerrarEditarCategoria {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }
  .cerrarEditarCategoria:hover, .cerrarEditarCategoria:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
  #tabla-categorias{
    background-color: #0d1b4c;
    color: white;
  }

</style>
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
        <h1 class="title">Administrar Artículos</h1>
        <div class="icons">
          <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
          <script src="../../assets/js/alerta.js"></script>
          <!-- <span>👤</span>
          <span>⚙️</span> -->
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn active" data-tab="categorias"><a href="categoria.php">Categorias</a></button>
          <button class="tab-btn" data-tab="articulos"><a href="articulos.php">Artículos</a></button>
        </div>
        <button class="btn-primary" onclick="abrirModal()">Agregar Categoria</button>

        <!-- CATEGORÍAS -->
        <div class="tab-content active" id="categorias">
          <!-- <h3>Gestión de Categorías</h3>
          <form class="form-categoria" action="../../procesos/insertar_categoria.php" method="POST">
            <input class="form-categoria-input" type="text" name="nombre" placeholder="Nombre de la Categoría" required>
            <input class="form-categoria-input" type="text" name="descripcion" placeholder="Descripción de la Categoría" required>
            <button class="btn-primary" type="submit">Agregar</button>
          </form> -->
          <!-- Modal para agregar categoría -->
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
          <script>
            // Función para abrir el modal
            function abrirModal() {
              document.getElementById("modalAgregar").style.display = "block";
            }

            // Función para cerrar el modal
            function cerrarModal() {
              document.getElementById("modalAgregar").style.display = "none";
            }

            // Cerrar el modal si se hace clic fuera de él
            window.onclick = function(event) {
              var modal = document.getElementById("modalAgregar");
              if (event.target == modal) {
                modal.style.display = "none";
              }
            }
            function abrirModalEditar(id_categoria, nombre, descripcion) {
              document.getElementById("editarCategoria").style.display = "block";
              document.getElementById("id_categoria").value = id_categoria;
              document.getElementById("nombre_edit").value = nombre;
              document.getElementById("descripcion_edit").value = descripcion;
            }
            // Función para cerrar el modal de edición
            function cerrarModalEditar() {
              document.getElementById("editarCategoria").style.display = "none";
            }
            window.onclick = function(event) {
  var modalEditar = document.getElementById("editarCategoria");
  var modalAgregar = document.getElementById("modalAgregar");
  if (event.target == modalEditar) {
    modalEditar.style.display = "none";
  }
  if (event.target == modalAgregar) {
    modalAgregar.style.display = "none";
  }
}


            
          </script>

          <table class="tabla">
            <thead id="tabla-categorias">
              <tr>
                <th>Nombre de Categoría</th>
                <th>Descripcion</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($filas = mysqli_fetch_assoc($ejecucionDeConsulta)){ ?>
              <tr>
                <td><?php echo $filas['nombre_categoria']; ?></td>
                <td><?php echo $filas['descripcion']; ?></td>
                <td>
                  <button class="btn-secondary" onclick="abrirModalEditar(<?php echo $filas['id_categoria']; ?>, '<?php echo $filas['nombre_categoria']; ?>', '<?php echo $filas['descripcion']; ?>')">Editar</button>
                  <button class="btn-primary" onclick="eliminarCategoria(<?php echo $filas['id_categoria']; ?>)">Eliminar</button>
                  <script src="../../assets/js/alerta.js"></script>
                </td>
              </tr>
               <?php } ?>
            </tbody>
          </table>
        </div>
        <div id="editarCategoria" class="modal-editar-categoria">
          <div class="modal-contenido-editar-categoria">
            <span class="cerrarEditarCategoria" onclick="cerrarModalEditar()">&times;</span>
            <h2>Editar Categoría</h2>
            <form class="form-categoria" action="../../procesos/editar_categoria.php" method="POST">
              <input type="hidden" id="id_categoria" name="id_categoria">
              <input class="form-categoria-input" type="text" id="nombre_edit" name="nombre" placeholder="Nombre de la Categoría" required>
              <input class="form-categoria-input" type="text" id="descripcion_edit" name="descripcion" placeholder="Descripción de la Categoría" required>
              <button class="btn-primary" type="submit">Guardar Cambios</button>
            </form>
          </div>
        </div>
</body>
<?php
mysqli_close($conexion);
?>
</html>
