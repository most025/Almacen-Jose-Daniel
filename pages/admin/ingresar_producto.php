<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Eliminar producto si se recibe un ID por GET
if (isset($_GET['eliminar'])) {
    $id_carga = $_GET['eliminar'];

    // Eliminar primero del inventario (por la clave foránea)
    $sql_delete_inventario = "DELETE FROM inventario_productos WHERE id_carga = ?";
    $stmt_delete_inventario = $conn->prepare($sql_delete_inventario);
    $stmt_delete_inventario->bind_param("i", $id_carga); // "i" indica que $id_carga es un entero
    $stmt_delete_inventario->execute();

    // Luego eliminar del registro principal
    $sql_delete_carga = "DELETE FROM carga_productos WHERE id_carga = ?";
    $stmt_delete_carga = $conn->prepare($sql_delete_carga);
    $stmt_delete_carga->bind_param("i", $id_carga); // "i" indica que $id_carga es un entero
    $stmt_delete_carga->execute();

    header("Location: articulos.php"); // Redirige a articulos.php
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Almacén</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../assets/js/alerta.js"></script>
    <script src="../../assets/js/script.js"></script>
    <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
    <script src="../../dependencias/sweetalert2@11.js"></script>
</head>
<style>
    .form-proveedor {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
    }

    .form-proveedor input {
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

    .modal-agregar-proveedores {
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

    .cerrar:hover,
    .cerrar:focus {
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
</style>

<body>
    <div class="container">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/logo.ico" alt="Descripción de la imagen" width="200"
                    height="100"></div>
            <nav>
                <a href="dashboard.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
                <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
                <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Administrar
                        Artículos</span></a>
                <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
                <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
            </nav>
        </aside>

        <!-- Main -->
        <div class="main">
            <!-- Header -->
            <header class="header">
                <h1 class="title">PRODUCTOS</h1>
                <div class="icons">
                    <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
                    <script src="../../assets/js/alerta.js"></script>
                    <!--<span>👤</span>
                    <span>⚙️</span>-->
                </div>
            </header>

            <!-- Content -->
            <main class="content">
                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-btn active" onclick="abrirModalProveedor()">Nuevos Productos</button>
                    <!-- <button class="tab-btn" data-tab="pedidos">Pedidos</button>
          <button class="tab-btn" data-tab="pagos">Pagos</button> -->
                </div>

                <!-- CRUD -->
                <div class="tab-content active" id="crud">
                    <div id="agregarProveedor" class="modal-agregar-proveedores">

                        <div class="modal-contenido-proveedor">
                            <span class="cerrar" onclick="cerrarModalProveedor()">&times;</span>
                            <h4>Nuevos Productos</h4>
                            <form action="guardar_producto.php" method="POST" class="form-proveedor">
                                <label class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombre_producto" class="form-control" placeholder="Ej: Arroz 1kg"
                                    required>

                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2" required></textarea>

                                <label class="form-label">Categoría</label>
                                <input type="text" name="categoria" class="form-control" placeholder="Ej: Alimentos"
                                    required>

                                <label class="form-label">Precio de Compra</label>
                                <input type="number" name="precio_compra" class="form-control" step="0.01" min="0"
                                    required>

                                <label class="form-label">Precio de Venta</label>
                                <input type="number" name="precio_venta" class="form-control" step="0.01" min="0"
                                    required>

                                <label class="form-label">Stock Disponible</label>
                                <input type="number" name="stock" class="form-control" min="0" required>

                                <button type="submit" class="btn btn-success w-100">Registrar Producto</button>
                            </form>
                        </div>
                    </div>
                    <script>
                        function abrirModalProveedor() {
                            document.getElementById("agregarProveedor").style.display = "block";
                        }

                        // Función para cerrar el modal
                        function cerrarModalProveedor() {
                            document.getElementById("agregarProveedor").style.display = "none";
                            document.querySelector("body").style.overflow = "auto";
                        }

                        // Cerrar el modal si se hace clic fuera de él
                        window.onclick = function (event) {
                            var modal = document.getElementById("agregarProveedor");
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
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

                        // Cerrar el modal si se hace clic fuera de él
                        window.onclick = function (event) {
                            var modalEditar = document.getElementById("editarProveedor");
                            var modalAgregar = document.getElementById("agregarProveedor");
                            if (event.target == modalEditar) {
                                modalEditar.style.display = "none";
                            }
                            if (event.target == modalAgregar) {
                                modalAgregar.style.display = "none";
                            }
                        }

                    </script>
                    <!-- Tabla -->
                    <div class="col-md-8">
                        <div class="card p-4 shadow-sm">
                            <h4 class="mb-3">Listado de Productos</h4>
                            <div class="table-container">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>$ - Com</th>
                                            <th>$ - Vta</th>
                                            <th>Stock</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT cp.id_carga, cp.nombre_producto, cp.categoria, cp.precio_compra, cp.precio_venta, 
                                                        ip.stockDisponible
                                                FROM carga_productos cp
                                                LEFT JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
                                                ORDER BY cp.id_carga DESC";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($producto = $result->fetch_assoc()) {
                                                $nombre = htmlspecialchars($producto['nombre_producto']);
                                                echo "<tr>
                                                        <td>{$nombre}</td>
                                                        <td>{$producto['categoria']}</td>
                                                        <td>\${$producto['precio_compra']}</td>
                                                        <td>\${$producto['precio_venta']}</td>
                                                        <td>{$producto['stockDisponible']}</td>
                                                        <td>
                                                            <a href='editar_producto.php?id={$producto['id_carga']}' class='btn btn-warning btn-sm me-1'>✏️ Editar</a>
                                                            <button class='btn btn-danger btn-sm' 
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#confirmarEliminarModal' 
                                                                    data-id='{$producto['id_carga']}' 
                                                                    data-nombre='{$nombre}'>
                                                                    🗑️ Eliminar
                                                            </button>
                                                        </td>
                                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No hay productos registrados.</td></tr>";
                                        }
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-2">¿Seguro que deseas eliminar este producto?</p>
                    <p><strong id="nombreProductoEliminar" class="text-danger"></strong></p>
                    <p class="text-muted"><small>Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Captura el id y nombre del producto al abrir el modal
        const eliminarModal = document.getElementById('confirmarEliminarModal');
        eliminarModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const idProducto = button.getAttribute('data-id');
            const nombreProducto = button.getAttribute('data-nombre');

            const confirmarBtn = document.getElementById('btnConfirmarEliminar');
            const nombreSpan = document.getElementById('nombreProductoEliminar');

            confirmarBtn.href = '?eliminar=' + idProducto;
            nombreSpan.textContent = nombreProducto;
        });
    </script>

</body>

</html>

