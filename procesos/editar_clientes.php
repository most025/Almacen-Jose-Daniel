<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni=$_POST['dni'];
    $telefono = $_POST['telefono'];
    
 $sqlValidacion = "SELECT id_cliente FROM clientes WHERE dni = ? AND id_cliente != ?";
 $prepaarConsultaValidacion = mysqli_prepare($conexion, $sqlValidacion);
 mysqli_stmt_bind_param($prepaarConsultaValidacion, "si", $dni, $id_cliente);
 mysqli_stmt_execute($prepaarConsultaValidacion);
    mysqli_stmt_store_result($prepaarConsultaValidacion);
    if (mysqli_stmt_num_rows($prepaarConsultaValidacion)>0) {
        ?>
        <!DOCTYPE html>
        <head>
            <script src="../dependencias/sweetalert2@11.js"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'DNI en uso',
                    text: 'El DNI ingresado ya pertenece a otro cliente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>
        <?php
        exit; // Detener el script
    }
    mysqli_stmt_close($prepaarConsultaValidacion);

    $sql = "UPDATE clientes 
            SET nombre = ?, apellido = ?, dni = ?, telefono = ?
            WHERE id_cliente = ?";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssssi", $nombre, $apellido, $dni, $telefono, $id_cliente);

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
