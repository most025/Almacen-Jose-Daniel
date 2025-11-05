<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni=$_POST['dni'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO clientes (nombre, apellido, telefono, dni, email, direccion) VALUES (?, ?, ?, ?, ?, ?)";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssssss", $nombre, $apellido, $telefono, $dni, $email, $direccion);
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
            // echo "Proveedor agregado exitosamente.";
            // header("Location: ../pages/proveedores.php");
        } else {
            echo "Error al agregar la categoría: " . mysqli_error($conexion);
        }
    }
    mysqli_stmt_close($consultaPreparada);
}

mysqli_close($conexion);
?>