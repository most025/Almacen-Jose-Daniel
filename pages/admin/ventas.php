<?php
// ventas.php - versión corregida

// Conexión a la base de datos (ajusta si usas otro usuario/contraseña)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "almacen_jose_daniel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para mostrar productos del inventario (solo con stock > 0)
$sql = "SELECT cp.id_carga, cp.nombre_producto, cp.precio_venta, ip.stockDisponible
        FROM carga_productos cp
        INNER JOIN inventario_productos ip ON cp.id_carga = ip.id_carga
        WHERE ip.stockDisponible > 0
        ORDER BY cp.nombre_producto ASC";

$result = $conn->query($sql);

// Si hubo error en la consulta, lo registramos y $result se convertirá en null
if ($result === false) {
    error_log("Error en consulta SQL: " . $conn->error);
    $result = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ventas</title>
  <link rel="stylesheet" href="css/estilos1.css">
  <script defer src="script.js"></script>
  <link rel="icon" href="logo.ico" type="icono1.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Estilos básicos para que se vea ordenado */
    body { background: #1c56caff; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .container { padding-top: 20px; }
    .card { border-radius: 10px; }
    .producto-item .border { min-height: 120px; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar mb-4">
      <div class="logo"><img src="logo.png" alt="Descripción de la imagen" width="200" height="100"></div>
       <nav>
        <a href="index.php"><span class="icon">🏠</span> <span class="text">Inicio</span></a>
        <a href="proveedores.php"><span class="icon">📦</span> <span class="text">Proveedores</span></a>
        <a href="articulos.php"><span class="icon">🛠️</span> <span class="text">Administrar Artículos</span></a>
        <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
        <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="main">
      <!-- Header -->
      <header class="header mb-4 d-flex justify-content-between align-items-center">
        <h1 class="title">Ventas</h1>
        <div class="icons">
          <span>👤</span>
          <span>⚙️</span>
        </div>
      </header>

      <!-- Content -->
      <main class="content">
        <!-- Tabs -->
        <div class="tabs mb-4">
          <button class="tab-btn active btn btn-sm btn-outline-success" onclick="window.location.href='ingresar_producto.php'">Carga Producto</button>
          <a href="reporte_ventas.php" class="btn btn-sm btn-outline-success">Reportes y Ventas</a>
        </div>

        <div class="container">
          <div class="row">
            <!-- Productos disponibles -->
            <div class="col-lg-7 col-md-12 mb-4">
              <div class="card p-4">
                <h2>Listado de Productos</h2>

                <!-- Buscador -->
                <div class="mb-3">
                  <input type="text" id="buscadorProductos" class="form-control" placeholder="Buscar producto..." onkeyup="filtrarProductos()">
                </div>

                <div class="row">
                  <?php
                  // Si $result es nulo o no tiene filas mostramos mensaje
                  if ($result && $result->num_rows > 0):
                    while ($prod = $result->fetch_assoc()):
                      // seguridad: escapar salida
                      $id = (int)$prod['id_carga'];
                      $nombre = htmlspecialchars($prod['nombre_producto'], ENT_QUOTES, 'UTF-8');
                      $precio = number_format((float)$prod['precio_venta'], 2, '.', '');
                      $stock = (int)$prod['stockDisponible'];
                  ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3 producto-item">
                      <div class="border p-2 rounded">
                        <strong><?php echo $nombre; ?></strong><br>
                        <span>💲<?php echo $precio; ?></span><br>
                        <small>Stock: <?php echo $stock; ?></small><br>

                        <input type="checkbox" name="productos[]" value="<?php echo $id; ?>"
                               data-nombre="<?php echo $nombre; ?>"
                               data-precio="<?php echo $precio; ?>"
                               data-stock="<?php echo $stock; ?>"
                               onclick="actualizarProductos(this)">
                        <label> Seleccionar</label>

                        <select class="form-control cantidad mt-2" name="cantidad_<?php echo $id; ?>"
                                style="display:none;" onchange="actualizarTotal()">
                          <?php for ($i = 1; $i <= $stock; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                    </div>
                  <?php
                    endwhile;
                  else:
                  ?>
                    <div class="col-12">
                      <div class="alert alert-info">No hay productos disponibles en inventario.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Formulario de venta -->
            <div class="col-lg-5 col-md-12">
              <div class="card p-4">
                <h2>Registrar Venta</h2>
                <form action="guardar_venta.php" method="POST" onsubmit="return validarFormulario()">
                  <div class="mb-3">
                    <label for="cliente">Nombre del Cliente</label>
                    <input type="text" name="cliente" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label for="metodo_pago">Método de Pago</label>
                    <select class="form-control" name="metodo_pago" required>
                      <option value="" disabled selected>Seleccione</option>
                      <option value="Efectivo">Efectivo</option>
                      <option value="Transferencia">Transferencia</option>
                    </select>
                  </div>

                  <input type="hidden" name="productos_seleccionados" id="productos_seleccionados">
                  <input type="hidden" name="total_venta" id="total_venta">

                  <div class="mb-3">
                    <label>Productos Seleccionados:</label>
                    <ul id="listaProductos" class="ps-3"></ul>
                  </div>

                  <div class="mb-3">
                    <h4>Total: 💰$<span id="total">0.00</span></h4>
                  </div>

                  <button type="submit" class="btn btn-success w-100">Confirmar Venta</button>
                 </form>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>
</div>

<script>
function validarFormulario() {
  var checkboxes = document.querySelectorAll('input[name="productos[]"]:checked');
  if (checkboxes.length === 0) {
    alert('Debe seleccionar al menos un producto.');
    return false;
  }

  let productos = [];     // Para enviar al PHP y actualizar stock
  let descripcion = [];   // Para mostrar y guardar en ventas_productos
  let total = 0;

  checkboxes.forEach(function(cb) {
    let nombre = cb.dataset.nombre;
    let precio = parseFloat(cb.dataset.precio);
    let id_carga = cb.value;
    let sel = cb.closest('.p-2').querySelector('select');
    let cantidad = sel ? parseInt(sel.value) : 1;

    productos.push({ id_carga: id_carga, cantidad: cantidad });
    descripcion.push(`${nombre} x${cantidad}`);
    total += precio * cantidad;
  });

  // Guardar en hidden inputs
  document.getElementById('productos_seleccionados').value = JSON.stringify(productos);
  document.getElementById('total_venta').value = total.toFixed(2);

  // Actualizar lista visual en el formulario
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
  const select = checkbox.closest('.p-2').querySelector('select');
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
    const select = cb.closest('.p-2').querySelector('select');
    const cantidad = select ? parseInt(select.value) : 1;
    total += precio * cantidad;

    const li = document.createElement('li');
    li.textContent = `${cb.dataset.nombre} x${cantidad} - $${(precio * cantidad).toFixed(2)}`;
    lista.appendChild(li);
  });

  document.getElementById('total').textContent = total.toFixed(2);
}

function filtrarProductos() {
  const input = document.getElementById('buscadorProductos');
  if (!input) return;
  const filtro = input.value.toLowerCase();
  document.querySelectorAll('.producto-item').forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(filtro) ? '' : 'none';
  });
}
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// cerrar conexión
$conn->close();
?>
