<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];

    //Verificar si el DNI ya existe
    $sqlVerificacion = "SELECT id_cliente FROM clientes WHERE dni = ?";
    $prepararValidacion = mysqli_prepare($conexion, $sqlVerificacion);
    mysqli_stmt_bind_param($prepararValidacion, "s", $dni);
    mysqli_stmt_execute($prepararValidacion);
    mysqli_stmt_store_result($prepararValidacion);

    if (mysqli_stmt_num_rows($prepararValidacion) > 0) {
        // DNI duplicado → Mostrar alerta
        ?>
        <!DOCTYPE html>
        <head>
            <script src="../dependencias/sweetalert2@11.js"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'DNI duplicado',
                    text: 'Ya existe un cliente registrado con ese DNI.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = '../pages/admin/clientes.php';
                });
            </script>
        </body>
        </html>
        <?php
        exit; // Detener el script
    }
    mysqli_stmt_close($prepararValidacion);

    // Insertar cliente si no existe el DNI
    $sql = "INSERT INTO clientes (nombre, apellido, telefono, dni, estado) VALUES (?, ?, ?, ?, 'Activo')";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssss", $nombre, $apellido, $telefono, $dni);
        if (mysqli_stmt_execute($consultaPreparada)) {
            ?>
            <!DOCTYPE html>
            <head>
                <script src="../dependencias/sweetalert2@11.js"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente agregado',
                        text: 'El cliente ha sido agregado exitosamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../pages/admin/clientes.php';
                    });
                </script>
            </body>
            </html>
            <?php
        } else {
            echo "Error al agregar el cliente: " . mysqli_error($conexion);
        }
    }
    mysqli_stmt_close($consultaPreparada);
}

mysqli_close($conexion);
?>