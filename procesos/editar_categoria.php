<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_categoria = $_POST['id_categoria'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE categoria
            SET nombre_categoria = ?, descripcion = ?
            WHERE id_categoria = ?";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssi", $nombre, $descripcion, $id_categoria);

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
                        title: 'Categoría actualizada',
                        text: 'Los datos de la categoría se han actualizado correctamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../pages/admin/categoria.php';
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
