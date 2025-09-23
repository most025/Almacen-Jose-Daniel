<?php
session_start();

// Si ya hay sesión, redirigimos
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="icon" href="../assets/img/logo.ico" type="icono1.ico">
</head>
<body class="login-body">
  <div class="login-container">
    <h1>Almacén</h1><div class="logo"><img src="../assets/img/logo.ico" alt="Descripción de la imagen" width="200" height="100"></div>

    <!-- Formulario de Ingreso -->
    <form action="procesar_login.php" method="POST" class="login-form">
      <h2>Ingresar</h2>
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit" class="btn-primary">Ingresar</button>
    </form>

    <!-- Formulario de Registro -->

    <!-- Entrar como invitado -->
    <form action="invitado.php" method="POST" class="login-form">
      <h2>Entrar como invitado</h2>
      <button type="submit" class="btn-primary">Entrar como invitado</button>
    </form>
  </div>
</body>
</html>
