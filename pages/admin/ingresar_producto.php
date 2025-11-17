<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// "Eliminar" producto => cambiar estado a inactivo
if (isset($_GET['eliminar'])) {
    $id_carga = $_GET['eliminar'];

    // En lugar de borrar, actualizamos el estado
    $sql_inactivar = "UPDATE carga_productos SET estado = 'inactivo' WHERE id_carga = ?";
    $stmt = $conn->prepare($sql_inactivar);
    $stmt->bind_param("i", $id_carga);
    $stmt->execute();

    header("Location: ingresar_producto.php");
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
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/alerta.js"></script>
    <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
    <script src="../../dependencias/sweetalert2@11.js"></script>

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
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-contenido-proveedor {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 10px;
            max-width: 600px;
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

        .form-proveedor {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 10px;
        }

        .form-proveedor input,
        .form-proveedor select,
        .form-proveedor textarea {
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

        .tabla {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla th,
        .tabla td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .tabla tr:hover {
            background-color: #f1f1f1;
        }

        .btn-primary,
        .btn-secondary,
        .btn-eliminar {
            cursor: pointer;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
        }

        .btn-secondary {
            color: #37353E;
        }

        .btn-eliminar {
            background-color: #b30000;
        }

        .btn-eliminar:hover {
            background-color: #ff0000;
        }

        p {
            margin: 0;
            font-weight: bold;
            color: black;
            font-size: 11px;
        }

        #boton-secundario {
            background-color: #FFE797;
        }

        #boton-secundario:hover {
            background-color: #FFD966;
        }

        #link-categorias {
            text-decoration: none;
            color: #37353E;
            font-weight: bold;
        }

        .modal-editar-producto {
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

        .modal-contenido-editar-producto {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 10px;
            max-width: 500px;
        }

        .form-Producto {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 10px;
        }

        .form-Producto input,
        .form-Producto select {
            display: inline;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .cerrarEditarProductos {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .textarea{
            resize: vertical;
            min-height: 60px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/logo.ico" alt="Logo" width="200" height="100"></div>
            <nav>
                <a href="dashboard.php"><span class="icon">🏠</span>Inicio</a>
                <a href="proveedores.php"><span class="icon">📦</span>Proveedores</a>
                <a href="ingresar_producto.php" class="active"><span class="icon">🛠️</span>Productos</a>
                <a href="clientes.php"><span class="icon">👥</span>Clientes</a>
                <a href="ventas.php"><span class="icon">💰</span>Ventas</a>
                <a href="reporte_ventas.php"><span class="icon">📈</span>Reportes de ventas</a>
            </nav>
        </aside>

        <!-- Main -->
        <div class="main">
            <header class="header">
                <h1 class="title">Gestión de Productos</h1>
                <div class="icons">
                    <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
                    <script src="../../assets/js/alerta.js"></script>
                </div>
            </header>

            <main class="content">
                <div class="tabs">
                    <button class="tab-btn active" onclick="abrirModalProveedor()">Agregar Producto</button>
                    <button class="tab-btn active" id="boton-secundario"><a id="link-categorias" href="categoria.php">Categorías</a></button>
                </div>

                <div class="tab-content active" id="crud">
                    <!-- Modal agregar -->
                    <div id="agregarProveedor" class="modal-agregar-proveedores">
                        <div class="modal-contenido-proveedor">
                            <span class="cerrar" onclick="cerrarModalProveedor()">&times;</span>
                            <h3>Registrar Nuevo Producto</h3>
                            <form action="guardar_producto.php" method="POST" class="form-proveedor">
                                <p>Nombre del Producto</p>
                                <input type="text" name="nombre_producto" required>
                                <p>Descripción</p>
                                <textarea name="descripcion" rows="2" required></textarea>
                                <p>Categoría</p>
                                <?php
                                $consulta = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado = 'activo'";
                                $resultado = $conn->query($consulta);
                                ?>
                                <select name="categoria" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <?php while ($row = $resultado->fetch_assoc()) { ?>
                                        <option value="<?= $row['id_categoria']; ?>"><?= $row['nombre_categoria']; ?></option>
                                    <?php } ?>
                                </select>
                                <p>Precio de Compra</p>
                                <input type="number" name="precio_compra" step="0.01" required>
                                <p>Precio de Venta</p>
                                <input type="number" name="precio_venta" step="0.01" required>
                                <p>Stock</p>
                                <input type="number" name="stock" min="0" required>
                                <button type="submit" class="btn-primary">Registrar</button>
                            </form>
                        </div>
                    </div>
                    <div id="editarProducto" class="modal-editar-producto">
                        <div class="modal-contenido-editar-producto">
                            <span class="cerrarEditarProductos" onclick="cerrarModalEditar()">&times;</span>
                            <h2>Editar Producto</h2>
                            <form class="form-Producto" action="../../procesos/editar_productos.php" method="POST">
                                <input type="hidden" id="id_producto" name="id_carga">

                                <input type="text" id="nombre_edit" name="nombre_producto" placeholder="Nombre del Producto" required>
                                <textarea  class="textarea"id="descripcion_edit" name="descripcion" placeholder="Descripción del Producto" required></textarea>

                                <select name="categoria" id="categoria_edit" required>
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    <?php
                                    $consulta = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado = 'Activo'";
                                    $resultado = $conn->query($consulta);
                                    while ($row = $resultado->fetch_assoc()) {
                                        echo "<option value='{$row['id_categoria']}'>{$row['nombre_categoria']}</option>";
                                    }
                                    ?>
                                </select>

                                <input type="number" id="precio_compra_edit" name="precio_compra" placeholder="Precio de Compra" step="0.01" min="0" required>
                                <input type="number" id="precio_venta_edit" name="precio_venta" placeholder="Precio de Venta" step="0.01" min="0" required>
                                <input type="number" id="stock_edit" name="stockDisponible" placeholder="Stock Disponible" min="0" required>

                                <button class="btn-primary" type="submit">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>

                    <script>
                         function abrirModalProveedor() {
                            document.getElementById("agregarProveedor").style.display = "block";
                        }

                        function cerrarModalProveedor() {
                            document.getElementById("agregarProveedor").style.display = "none";
                            document.querySelector("body").style.overflow = "auto";
                        }
                        window.onclick = function(event) {
                            var modal = document.getElementById("agregarProveedor");
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
                        }

                        function abrirModalEditar(id, nombre, descripcion, id_categoria, categoria, precio_compra, precio_venta, stock) {
                            document.getElementById("editarProducto").style.display = "block";
                            document.getElementById("id_producto").value = id;
                            document.getElementById("nombre_edit").value = nombre;
                            document.getElementById("descripcion_edit").value = descripcion;
                            document.getElementById("categoria_edit").value = id_categoria;
                            document.getElementById("precio_compra_edit").value = precio_compra;
                            document.getElementById("precio_venta_edit").value = precio_venta;
                            document.getElementById("stock_edit").value = stock;
                        }

                        function cerrarModalEditar() {
                            document.getElementById("editarProducto").style.display = "none";
                        }
                    </script>

                    <!-- Tabla -->
                    <table class="tabla">
                        <thead class="tabla-encabezado">
                            <tr>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>$ Compra</th>
                                <th>$ Venta</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT cp.id_carga, cp.nombre_producto, cp.descripcion, c.id_categoria, c.nombre_categoria AS categorias, 
                                            cp.precio_compra, cp.precio_venta, ip.stockDisponible
                                    FROM carga_productos cp
                                    INNER JOIN categoria c ON cp.id_categoria = c.id_categoria
                                    INNER JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
                                    WHERE cp.estado = 'Activo'
                                    ORDER BY cp.id_carga DESC";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($producto = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$producto['nombre_producto']}</td>
                                            <td>{$producto['categorias']}</td>
                                            <td>\${$producto['precio_compra']}</td>
                                            <td>\${$producto['precio_venta']}</td>
                                            <td>{$producto['stockDisponible']}</td>
                                            <td>
                                             <button class='btn-secondary' onclick=\"abrirModalEditar('{$producto['id_carga']}', '{$producto['nombre_producto']}', '{$producto['descripcion']}',  '{$producto['id_categoria']}','{$producto['categorias']}', '{$producto['precio_compra']}', '{$producto['precio_venta']}', '{$producto['stockDisponible']}')\">Editar</button>
                                                <a href='?eliminar={$producto['id_carga']}' onclick=\"return confirm('¿Seguro que quieres eliminar este producto?')\">
                                                    <button class='btn-primary'>Eliminar</button>
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No hay productos registrados.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
