<?php
session_start();
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta simple (OJO: vulnerable a SQL Injection si no validás bien los datos)
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario='$usuario' AND clave='$password'";
    $result = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Guardar solo el usuario (no la clave)
        $_SESSION['usuario'] = $row['nombre_usuario'];
        $_SESSION['id_usuario'] = $row['id_usuario'];

        header("Location: dashboad.php");
        exit();
    } else {
         echo "<script type='text/javascript'>alert('El Usuario y contraseña son incorrectos. Por favor, inténtalo de nuevo.');
    location.assign('login.php');
    </script>";
    }

    mysqli_free_result($result);
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="shortcut icon" href="../assets/img/logo.ico" type="image/x-icon">
</head>
<body class="login-body">
  <div class="login-container">
    <h1>Almacén</h1>
    <div class="logo"><img src="../assets/img/logo.ico" alt="Logo" width="200" height="100"></div>

    <form action="login.php" method="POST" class="login-form">
      <h2>Ingresar</h2>
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit" class="btn-primary">Ingresar</button>
    </form>

    <form action="invitado.php" method="POST" class="login-form">
      <h2>Entrar como invitado</h2>
      <button type="submit" class="btn-primary">Entrar como invitado</button>
    </form>
  </div>
</body>
</html>
