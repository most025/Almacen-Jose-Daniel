<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $sql = "INSERT INTO proveedores (nombre_proveedor, direccion, telefono, email) VALUES (?, ?, ?, ?)";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssis", $nombre, $direccion, $telefono, $email);
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
                        title: 'Proveedor agregado',
                        text: 'El proveedor ha sido agregado exitosamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../pages/admin/proveedores.php';
                    });
                </script>
            </body>
            </html>
            <?php
            // echo "Proveedor agregado exitosamente.";
            // header("Location: ../pages/proveedores.php");
        } else {
            echo "Error al agregar proveedor: " . mysqli_error($conexion);
        }
    }
    mysqli_stmt_close($consultaPreparada);
}

mysqli_close($conexion);
?>