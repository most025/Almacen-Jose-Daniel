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
    $stmt_delete_inventario->bind_param("i", $id_carga);
    $stmt_delete_inventario->execute();

    // Luego eliminar del registro principal
    $sql_delete_carga = "DELETE FROM carga_productos WHERE id_carga = ?";
    $stmt_delete_carga = $conn->prepare($sql_delete_carga);
    $stmt_delete_carga->bind_param("i", $id_carga);
    $stmt_delete_carga->execute();

    header("Location: articulos.php");
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
        .cerrar:hover, .cerrar:focus {
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
        .tabla th, .tabla td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .tabla tr:hover {
            background-color: #f1f1f1;
        }
        .btn-primary, .btn-secondary {
            cursor: pointer;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
        }
        .btn-primary {
            background-color: #0d1b4c;
        }
        .btn-secondary {
            background-color: #888;
        }
        .btn-primary:hover {
            background-color: #142a6e;
        }
        .btn-secondary:hover {
            background-color: #666;
        }
        p {
            margin: 0;
            font-weight: bold;
            color: black;
            font-size: 11px;
        }
    </style>
</head>

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
            </nav>
        </aside>

        <!-- Main -->
        <div class="main">
            <!-- Header -->
            <header class="header">
                <h1 class="title">Gestión de Productos</h1>
                <div class="icons">
                    <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
                </div>
            </header>

            <!-- Content -->
            <main class="content">
                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-btn active" onclick="abrirModalProveedor()">Agregar Productos</button>
                </div>

                <!-- CRUD -->
                <div class="tab-content active" id="crud">
                    <div id="agregarProveedor" class="modal-agregar-proveedores">
                        <div class="modal-contenido-proveedor">
                            <span class="cerrar" onclick="cerrarModalProveedor()">&times;</span>
                            <h3>Registrar Nuevo Producto</h3>
                            <form action="guardar_producto.php" method="POST" class="form-proveedor">
                                <p>Nombre del Producto</p>
                                <input type="text" name="nombre_producto" placeholder="Ej: Arroz 1kg" required>

                                <p>Descripción</p>
                                <textarea name="descripcion" rows="2" required></textarea>

                                <p>Categoría</p>
                                <?php
                                $consulta = "SELECT id_categoria, nombre_categoria FROM categoria";
                                $resultado = $conn->query($consulta);
                                ?>
                                <select name="categoria" id="categoria" required>
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    <?php while ($row = $resultado->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['id_categoria']; ?>"><?php echo $row['nombre_categoria']; ?></option>
                                    <?php } ?>
                                </select>

                                <p>Precio de Compra</p>
                                <input type="number" name="precio_compra" step="0.01" min="0" required>

                                <p>Precio de Venta</p>
                                <input type="number" name="precio_venta" step="0.01" min="0" required>

                                <p>Stock Disponible</p>
                                <input type="number" name="stock" min="0" required>

                                <button type="submit" class="btn-primary">Registrar</button>
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
                            $sql = "SELECT cp.id_carga, cp.nombre_producto, cp.categoria, cp.precio_compra, cp.precio_venta, ip.stockDisponible
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
                                                <button class='btn-secondary' onclick=\"window.location.href='editar_producto.php?id={$producto['id_carga']}'\">Editar</button>
                                                
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
            </main>
        </div>
    </div>
</body>
</html>


