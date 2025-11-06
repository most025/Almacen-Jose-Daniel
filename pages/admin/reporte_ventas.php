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
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="../../assets/js/script.js"></script>
    <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
    <script src="../../dependencias/sweetalert2@11.js"></script>
    <script src="../../assets/js/alerta.js"></script>

    <style>
        /* ======== Estilos generales ======== */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6fa;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            padding: 20px;
            background: #0d1b4c;
            color: #fff;
        }

        .sidebar a {
            display: block;
            margin: 10px 0;
            color: #fff;
            text-decoration: none;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .main {
            flex: 1;
            padding: 20px;
            background: #f4f6fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header .title {
            color: #0d1b4c;
            font-size: 24px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #0d1b4c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #162c6a;
        }

        /* ======== Botones de reporte ======== */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-btn {
            background-color: #0d1b4c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .tab-btn:hover {
            background-color: #162c6a;
        }

        .tab-btn.active {
            background-color: #0078d4;
        }

        /* ======== Modal estilo proveedores ======== */
        .modal-reporte {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-contenido {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 900px;
            border-radius: 10px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0d1b4c;
            color: white;
            padding: 10px 15px;
            border-radius: 10px 10px 0 0;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-info {
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-info:hover {
            background-color: #005fa3;
        }

        .btn-secondary {
            background-color: #555;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #333;
        }

        .cerrar {
            color: #fff;
            font-size: 22px;
            cursor: pointer;
        }

        .cerrar:hover {
            color: #ccc;
        }

        #contenidoReporte {
            padding: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #777;
        }

        .text-danger {
            color: #d9534f;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="../../assets/img/logo.ico" alt="Logo" width="200" height="100">
            </div>
            <nav>
                <a href="dashboard.php">🏠 Inicio</a>
                <a href="proveedores.php">📦 Proveedores</a>
                <a href="ingresar_producto.php">🛠️ Administrar Artículos</a>
                <a href="clientes.php">👥 Clientes</a>
                <a href="ventas.php">💰 Ventas</a>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <div class="main">
            <header class="header">
                <h1 class="title">Reporte de Ventas</h1>
                <button class="btn-primary" id="botonCerrar">Cerrar Sesión</button>
            </header>

            <!-- Botones de filtro -->
            <div class="tabs">
                <button class="tab-btn active" onclick="cargarReporte('diario')">📅 Diario</button>
                <button class="tab-btn" onclick="cargarReporte('mensual')">🗓️ Mensual</button>
            </div>

            <!-- Modal -->
            <div id="reporteModal" class="modal-reporte">
                <div class="modal-contenido">
                    <div class="modal-header">
                        <h5 id="tituloReporte">Reporte</h5>
                        <span class="cerrar" onclick="cerrarModal()">&times;</span>
                    </div>
                    <div id="contenidoReporte">
                        <p class="text-center text-muted">Selecciona un reporte para visualizarlo.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-info" id="btnImprimir">🖨️ Imprimir</button>
                        <button class="btn-secondary" onclick="cerrarModal()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script funcional -->
    <script>
        const modal = document.getElementById("reporteModal");
        const contenido = document.getElementById("contenidoReporte");

        function cargarReporte(filtro) {
            document.getElementById("tituloReporte").innerText =
                `Reporte de Ventas (${filtro.charAt(0).toUpperCase() + filtro.slice(1)})`;
            contenido.innerHTML = "<p class='text-center text-muted'>Cargando reporte...</p>";

            fetch(`reporte_datos.php?filtro=${filtro}`)
                .then(res => res.text())
                .then(data => {
                    contenido.innerHTML = data;
                    abrirModal();
                })
                .catch(() => {
                    contenido.innerHTML = "<p class='text-danger text-center'>Error al cargar los datos.</p>";
                });
        }

        function abrirModal() {
            modal.style.display = "block";
        }

        function cerrarModal() {
            modal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target === modal) cerrarModal();
        };

        document.getElementById('btnImprimir').addEventListener('click', () => {
            const contenido = document.getElementById('contenidoReporte').innerHTML;
            const ventana = window.open('', '_blank');
            ventana.document.write(`
                <html>
                <head>
                    <title>Imprimir Reporte</title>
                    <link rel="icon" type="image/x-icon" href="../../assets/img/logo.ico">
                </head>
                <body style="font-family: Arial; padding: 20px;">
                    <div style="text-align:center;">
                        <img src="../../assets/img/logo.ico" alt="Logo" width="80">
                        <h2>📋 Reporte de Ventas</h2>
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

