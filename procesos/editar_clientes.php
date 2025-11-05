<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni=$_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $sql = "UPDATE clientes 
            SET nombre = ?, apellido = ?, dni = ?, direccion = ?, telefono = ?, email = ?
            WHERE id_cliente = ?";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssssisi", $nombre, $apellido, $dni, $direccion, $telefono, $email, $id_cliente);

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
                        title: 'Cliente actualizado',
                        text: 'Los datos del cliente se han actualizado correctamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../pages/admin/clientes.php';
                    });
                </script>
            </body>
            </html>
            <?php
        } else {
            echo "Error al actualizar proveedor: " . mysqli_error($conexion);
        }
    } else {
        echo "Error en la preparación de la consulta.";
    }

    mysqli_stmt_close($consultaPreparada);
    mysqli_close($conexion);
}
?>
