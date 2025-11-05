<?php
// ===============================
// Conexión base de datos
// ===============================
$host = "localhost";
$user = "root";
$password = "";
$dbname = "almacen_jose_daniel"; 

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Ventas</title>
<link rel="icon" type="image/x-icon" href="logo.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
.wrapper { display:flex; min-height:100vh; }
.sidebar { width:220px; padding:20px; background:#0d1b4c; color:#fff; }
.sidebar a { display:block; margin:10px 0; color:#fff; text-decoration:none; }
.main { flex:1; padding:20px; background:#f4f6fa; }
.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.table-wrapper { overflow-x:auto; }
.modal-lg { max-width:95%; }
</style>
</head>

<body>
<div class="wrapper">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo text-center mb-3">
      <img src="logo.ico" alt="Logo" width="120">
    </div>
    <nav>
      <a href="index.php">🏠 Inicio</a>
      <a href="proveedores.php">📦 Proveedores</a>
      <a href="ingresar_producto.php">🛠️ Artículos</a>
      <a href="clientes.php">👥 Clientes</a>
      <a href="ventas.php">💰 Ventas</a>
    </nav>
  </aside>

  <!-- Contenido principal -->
  <div class="main">
    <header class="header">
      
      <div class="icons">
        <span>👤</span>
        <span>⚙️</span>
      </div>
    </header>

    <!-- Botones -->
    <div class="btn-group mb-3">
      <button class="btn btn-primary" onclick="cargarReporte('diario')">📅 Diario</button>
      <button class="btn btn-success" onclick="cargarReporte('mensual')">🗓️ Mensual</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reporteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="tituloReporte">Reporte</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="contenidoReporte">
            <p class="text-center text-muted">Selecciona un reporte para visualizarlo.</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-info" id="btnImprimir">🖨️ Imprimir</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
// Cargar el reporte en la modal mediante AJAX
function cargarReporte(filtro) {
  const modal = new bootstrap.Modal(document.getElementById('reporteModal'));
  document.getElementById('tituloReporte').innerText = 
      `Reporte de Ventas (${filtro.charAt(0).toUpperCase() + filtro.slice(1)})`;
  document.getElementById('contenidoReporte').innerHTML = 
      "<p class='text-center text-muted'>Cargando reporte...</p>";

  fetch(`reporte_datos.php?filtro=${filtro}`)
    .then(res => res.text())
    .then(data => {
      document.getElementById('contenidoReporte').innerHTML = data;
      modal.show();
    })
    .catch(() => {
      document.getElementById('contenidoReporte').innerHTML = 
          "<p class='text-danger text-center'>Error al cargar los datos.</p>";
    });
}

// Imprimir el contenido del reporte
document.getElementById('btnImprimir').addEventListener('click', () => {
  const contenido = document.getElementById('contenidoReporte').innerHTML;
  const ventana = window.open('', '_blank');
  ventana.document.write(`
    <html>
      <head>
        <title>Imprimir Reporte</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="logo.ico">
      </head>
      <body class="p-4">
        <div class="text-center mb-4">
          <img src="logo.ico" alt="Logo" width="80">
          <h2 class="mt-3">📋 Reporte de Ventas</h2>
          <p><strong>Fecha y Hora:</strong> ${new Date().toLocaleString()}</p>
        </div>
        ${contenido}
      </body>
    </html>
  `);
  ventana.document.close();
  ventana.print();
});
</script>

</body>
</html>
