<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "almacen_jose_daniel";

$conexion = mysqli_connect($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}else {
    // echo "Connection successful";
}
?>