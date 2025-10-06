<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proveedor = $_POST['id_proveedor'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $sql = "UPDATE proveedores 
            SET nombre_proveedor = ?, direccion = ?, telefono = ?, email = ? 
            WHERE id_proveedor = ?";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ssssi", $nombre, $direccion, $telefono, $email, $id_proveedor);
        
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
                        title: 'Proveedor actualizado',
                        text: 'Los datos del proveedor se han actualizado correctamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../pages/proveedores.php';
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
