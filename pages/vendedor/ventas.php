<?php
// ventas.php - versión adaptada sin Bootstrap

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "almacen_jose_daniel");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta productos con stock > 0
$sql = "SELECT cp.id_carga, cp.nombre_producto, cp.precio_venta, cp.estado, ip.stockDisponible
        FROM carga_productos cp
        INNER JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
        WHERE ip.stockDisponible > 0 AND cp.estado = 'Activo'
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
        .container { display: flex; }
        .content { padding: 20px; }

        .card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        h2 { color: #0d1b4c; margin-bottom: 10px; }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #bbb;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 14px;
        }

        /* --- ESTILO DEL SELECT BUSCABLE --- */
        .searchable-select { position: relative; width: 100%; }
        .searchable-select input {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #bbb;
        }
        .searchable-results {
            position: absolute;
            top: 40px;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #bbb;
            max-height: 200px;
            overflow-y: auto;
            z-index: 200;
            border-radius: 6px;
            display: none;
        }
        .searchable-results div {
            padding: 8px;
            cursor: pointer;
        }
        .searchable-results div:hover {
            background-color: #e6e6e6;
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
        <a href="dashboard_vendedor.php"><span class="icon">🏠</span> Inicio</a>
        <a href="ingresar_producto.php"><span class="icon">🛠️</span> Productos</a>
        <a href="clientes.php"><span class="icon">👥</span> Clientes</a>
        <a href="ventas.php" class="active"><span class="icon">💰</span> Ventas</a>
        <a href="reporte_ventas.php"><span class="icon">📈</span> Reportes de Ventas</a>
    </nav>
</aside>

<!-- Main -->
<div class="main">

<header class="header">
    <h1 class="title">Gestión de Ventas</h1>
    <div class="icons">
        <button class="btn-primary"><a id="botonCerrar">Cerrar Sesión</a></button>
    </div>
</header>

<main class="content">

    <div class="tabs">
        <button class="tab-btn active" onclick="window.location.href='ingresar_producto.php'">Carga Producto</button>
        <button class="tab-btn active" onclick="window.location.href='reporte_ventas.php'">Reportes y Ventas</button>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">

        <!-- LISTA DE PRODUCTOS -->
        <div style="flex: 2;">
            <div class="card">
                <h2>Listado de Productos</h2>

                <input type="text" id="buscadorProductos" placeholder="Buscar producto..." onkeyup="filtrarProductos()">

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
                                <strong><?= $nombre ?></strong><br>
                                💲 <?= $precio ?><br>
                                <small>Stock: <?= $stock ?></small><br>

                                <input type="checkbox"
                                        name="productos[]"
                                        value="<?= $id ?>"
                                        data-nombre="<?= $nombre ?>"
                                        data-precio="<?= $precio ?>"
                                        data-stock="<?= $stock ?>"
                                        onclick="actualizarProductos(this)">
                                <label> Seleccionar</label>

                                <select class="cantidad mt-2"
                                        name="cantidad_<?= $id ?>"
                                        style="display:none;"
                                        onchange="actualizarTotal()">
                                    <?php for ($i = 1; $i <= $stock; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <div class="alert-info">No hay productos en stock.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FORMULARIO DE VENTA -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h2>Registrar Venta</h2>

                <form action="guardar_venta.php" method="POST" onsubmit="return validarFormulario()">

                    <p>Nombre del Cliente</p>

                    <!-- SELECT BUSCABLE -->
                    <div class="searchable-select">
                        <input type="text" id="searchCliente" placeholder="Buscar cliente...">
                        <input type="hidden" name="cliente" id="clienteHidden" required>

                        <div class="searchable-results" id="clienteResultados"></div>
                    </div>

                    <?php
                    $consulta = "SELECT id_cliente, nombre, apellido, dni FROM clientes WHERE estado='activo'";
                    $clientes = $conn->query($consulta);

                    $jsonClientes = [];
                    while($c = $clientes->fetch_assoc()){
                        $jsonClientes[] = [
                            "id" => $c['id_cliente'],
                            "text" => $c['nombre'] . " " . $c['apellido'] . " (DNI: " . $c['dni'] . ")"
                        ];
                    }
                    ?>

                    <script>
                        const clientes = <?= json_encode($jsonClientes); ?>;

                        const input = document.getElementById('searchCliente');
                        const results = document.getElementById('clienteResultados');
                        const hidden = document.getElementById('clienteHidden');

                        input.addEventListener('input', function(){
                            const val = this.value.toLowerCase();
                            results.innerHTML = "";
                            results.style.display = "block";

                            clientes
                                .filter(c => c.text.toLowerCase().includes(val))
                                .forEach(c => {
                                    const div = document.createElement("div");
                                    div.textContent = c.text;
                                    div.onclick = () => {
                                        input.value = c.text;
                                        hidden.value = c.id;
                                        results.style.display = "none";
                                    };
                                    results.appendChild(div);
                                });
                        });

                        document.addEventListener("click", function(e){
                            if(!results.contains(e.target) && e.target !== input){
                                results.style.display = "none";
                            }
                        });
                    </script>

                    <p>Método de Pago</p>
                    <select name="metodo_pago" required>
                        <option value="" disabled selected>Seleccione un método</option>
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

    if(!document.getElementById("clienteHidden").value){
        alert("Debe seleccionar un cliente válido.");
        return false;
    }

    let productos = [];
    let total = 0;

    checkboxes.forEach(cb => {
        let precio = parseFloat(cb.dataset.precio);
        let id_carga = cb.value;
        let sel = cb.parentElement.querySelector('select');
        let cantidad = sel ? parseInt(sel.value) : 1;

        productos.push({ id_carga, cantidad });
        total += precio * cantidad;
    });

    document.getElementById('productos_seleccionados').value = JSON.stringify(productos);
    document.getElementById('total_venta').value = total.toFixed(2);

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

<?php $conn->close(); ?>
</body>
</html>



