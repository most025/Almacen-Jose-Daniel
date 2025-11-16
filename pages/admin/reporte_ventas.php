<?php
include('../../config/conexion.php');
$sql = "SELECT * FROM proveedores";
$ejecucionDeConsulta = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="shortcut icon" href="../../assets/img/logo.ico" type="image/x-icon">
    <script src="../../assets/js/script.js"></script>
    <script src="../../dependencias/sweetalert2@11.js"></script>

    <style>
        .modal-reporte {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-contenido {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 900px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            animation: aparecer 0.3s ease;
        }

        @keyframes aparecer {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0d1b4c;
            margin-bottom: 10px;
        }

        .modal-header h5 {
            color: #0d1b4c;
            margin: 0;
        }

        .cerrar {
            color: #888;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .cerrar:hover {
            color: black;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #ddd;
            margin-top: 15px;
            padding-top: 10px;
        }

        .btn-info, .btn-secondary {
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
        }

        .btn-info {
            background-color: #0d6efd;
            color: white;
        }

        .btn-info:hover {
            background-color: #084298;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #565e64;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 15px;
        }

        .tab-btn {
            padding: 10px 20px;
            border: none;
            background-color: #0d1b4c;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .tab-btn.active, .tab-btn:hover {
            background-color: #1a2b7e;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #777;
        }

        .text-danger {
            color: #c0392b;
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
            <a href="ingresar_producto.php"><span class="icon">🛠️</span> <span class="text">Productos</span></a>
            <a href="clientes.php"><span class="icon">👥</span> <span class="text">Clientes</span></a>
            <a href="ventas.php"><span class="icon">💰</span> <span class="text">Ventas</span></a>
            <a href="reporte_ventas.php" class="active"><span class="icon">📈</span> <span class="text">Reportes de Ventas</span></a>
        </nav>
    </aside>

    <!-- Main -->
    <div class="main">

        <!-- Header -->
        <header class="header">
            <h1 class="title">📊 Reporte de Ventas</h1>
            <div class="icons">
                <button class="btn-primary">
                    <a id="botonCerrar">Cerrar Sesión</a>
                </button>
                <script src="../../assets/js/alerta.js"></script>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
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

