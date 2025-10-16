<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO categoria (nombre_categoria, descripcion) VALUES (?, ?)";
    $consultaPreparada = mysqli_prepare($conexion, $sql);

    if ($consultaPreparada) {
        mysqli_stmt_bind_param($consultaPreparada, "ss", $nombre, $descripcion);
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
                        title: 'Categoría agregada',
                        text: 'La categoría ha sido agregada exitosamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../pages/admin/categoria.php';
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