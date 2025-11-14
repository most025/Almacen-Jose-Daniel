<?php
// ventas.php - versión adaptada sin Bootstrap, coherente con proveedores.php e ingresar_producto.php

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta productos con stock > 0
$sql = "SELECT cp.id_carga, cp.nombre_producto, cp.precio_venta,cp.estado, ip.stockDisponible
        FROM carga_productos cp
        INNER JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
        WHERE ip.stockDisponible > 0 and cp.estado = 'Activo'
        ORDER BY cp.nombre_producto ASC";
$result = $conn->query($sql);
if ($result === false) {
    error_log('Error SQL: ' . $conn->error);
    $result = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/alerta.js"></script>
    <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
    <script src="../../dependencias/sweetalert2@11.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
        }

        .content {
            padding: 20px;
        }

        .card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        h2 {
            color: #0d1b4c;
            margin-bottom: 10px;
        }

        .buscador {
            margin-bottom: 10px;
        }

        input[type="text"], input[type="number"], select, .cantidad {
            width: 100%;
            padding: 8px;
            border: 1px solid #bbb;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 14px;
        }

        .producto-item {
            display: inline-block;
            vertical-align: top;
            width: 30%;
            background: #fafafa;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 6px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            transition: transform 0.1s ease-in-out;
        }

        .producto-item:hover {
            transform: scale(1.02);
            background-color: #fdfdfd;
        }

        .producto-item strong {
            color: #0d1b4c;
        }

        .btn-primary, .btn-secondary, .btn-success {
            cursor: pointer;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            text-align: center;
        }

        .btn-secondary { background-color: #888; }
        .btn-success { background-color: #1c56ca; }

        .btn-success:hover { background-color: #2a64d1; }

        .lista {
            list-style: none;
            padding-left: 15px;
        }

        .tabs {
            margin-bottom: 15px;
        }

        .tab-btn {
            background-color: #0d1b4c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .tab-btn:hover {
            background-color: #e66e1d;
        }

        .alert-info {
            background-color: #e0e7ff;
            color: #0d1b4c;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        #total {
            color: #0d1b4c;
            font-weight: bold;
        }

        a.link-ventas {
            color: #0d1b4c;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
        }
        a.link-ventas:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="../../assets/img/logo.ico" alt="Logo" width="200" height="100">
            </div>
            <nav>
                <a href="dashboard.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
                <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
                <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
                <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
                <a href="ventas.php" class="active"><span class="icon">💰</span> <span class="text">Ventas</span></a>
                <a href="reporte_ventas.php"><span class="icon">📈</span> <span class="text">Reportes de Ventas</span></a>
            </nav>
        </aside>

        <!-- Main -->
        <div class="main">
            <!-- Header -->
            <header class="header">
                <h1 class="title">Gestión de Ventas</h1>
                <div class="icons">
                    <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
                    <script src="../../assets/js/alerta.js"></script>
                </div>
            </header>

            <!-- Contenido -->
            <main class="content">
                <div class="tabs">
                    <button class="tab-btn active" onclick="window.location.href='ingresar_producto.php'">Carga Producto</button>
                    <button class="tab-btn active" onclick="window.location.href='reporte_ventas.php'">Reportes y Ventas</button>
                </div>

                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <!-- Lista de productos -->
                    <div style="flex: 2;">
                        <div class="card">
                            <h2>Listado de Productos</h2>
                            <div class="buscador">
                                <input type="text" id="buscadorProductos" placeholder="Buscar producto..." onkeyup="filtrarProductos()">
                            </div>

                            <div id="listaProductosContainer">
                                <?php
                                if ($result && $result->num_rows > 0):
                                    while ($prod = $result->fetch_assoc()):
                                        $id = (int)$prod['id_carga'];
                                        $nombre = htmlspecialchars($prod['nombre_producto']);
                                        $precio = number_format((float)$prod['precio_venta'], 2, '.', '');
                                        $stock = (int)$prod['stockDisponible'];
                                ?>
                                        <div class="producto-item">
                                            <strong><?php echo $nombre; ?></strong><br>
                                            💲 <?php echo $precio; ?><br>
                                            <small>Stock: <?php echo $stock; ?></small><br>

                                            <input type="checkbox" name="productos[]" value="<?php echo $id; ?>"
                                                data-nombre="<?php echo $nombre; ?>"
                                                data-precio="<?php echo $precio; ?>"
                                                data-stock="<?php echo $stock; ?>"
                                                onclick="actualizarProductos(this)">
                                            <label> Seleccionar</label>

                                            <select class="cantidad mt-2" name="cantidad_<?php echo $id; ?>" style="display:none;"
                                                onchange="actualizarTotal()">
                                                <?php for ($i = 1; $i <= $stock; $i++): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                <?php
                                    endwhile;
                                else:
                                ?>
                                    <div class="alert-info">No hay productos disponibles en inventario.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de venta -->
                    <div style="flex: 1; min-width: 300px;">
                        <div class="card">
                            <h2>Registrar Venta</h2>
                            <form action="guardar_venta.php" method="POST" onsubmit="return validarFormulario()">
                                <p>Nombre del Cliente</p>
                                <input type="text" name="cliente" required>

                                <!-- <p>DNI del Cliente</p>
                                <input type="number" name="dni" required placeholder="Ingrese el DNI"> -->

                                <p>Método de Pago</p>
                                <select name="metodo_pago" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                </select>

                                <input type="hidden" name="productos_seleccionados" id="productos_seleccionados">
                                <input type="hidden" name="total_venta" id="total_venta">

                                <p>Productos Seleccionados:</p>
                                <ul id="listaProductos" class="lista"></ul>

                                <h3>Total: 💰$<span id="total">0.00</span></h3>
                                <br>
                                <button type="submit" class="btn-success" style="width:100%;">Confirmar Venta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function validarFormulario() {
            var checkboxes = document.querySelectorAll('input[name="productos[]"]:checked');
            if (checkboxes.length === 0) {
                alert('Debe seleccionar al menos un producto.');
                return false;
            }

            let productos = [];
            let descripcion = [];
            let total = 0;

            checkboxes.forEach(function(cb) {
                let nombre = cb.dataset.nombre;
                let precio = parseFloat(cb.dataset.precio);
                let id_carga = cb.value;
                let sel = cb.parentElement.querySelector('select');
                let cantidad = sel ? parseInt(sel.value) : 1;

                productos.push({ id_carga, cantidad });
                descripcion.push(`${nombre} x${cantidad}`);
                total += precio * cantidad;
            });

            document.getElementById('productos_seleccionados').value = JSON.stringify(productos);
            document.getElementById('total_venta').value = total.toFixed(2);

            const lista = document.getElementById('listaProductos');
            lista.innerHTML = '';
            descripcion.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                lista.appendChild(li);
            });

            return true;
        }

        function actualizarProductos(checkbox) {
            const select = checkbox.parentElement.querySelector('select');
            if (!select) return;
            select.style.display = checkbox.checked ? 'block' : 'none';
            if (!checkbox.checked) select.value = 1;
            actualizarTotal();
        }

        function actualizarTotal() {
            let total = 0;
            const lista = document.getElementById('listaProductos');
            lista.innerHTML = '';

            document.querySelectorAll('input[name="productos[]"]:checked').forEach(cb => {
                const precio = parseFloat(cb.dataset.precio);
                const select = cb.parentElement.querySelector('select');
                const cantidad = select ? parseInt(select.value) : 1;
                total += precio * cantidad;

                const li = document.createElement('li');
                li.textContent = `${cb.dataset.nombre} x${cantidad} - $${(precio * cantidad).toFixed(2)}`;
                lista.appendChild(li);
            });

            document.getElementById('total').textContent = total.toFixed(2);
        }

        function filtrarProductos() {
            const filtro = document.getElementById('buscadorProductos').value.toLowerCase();
            document.querySelectorAll('.producto-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filtro) ? '' : 'none';
            });
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>


