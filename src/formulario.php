<?php require_once BASE_PATH.'/includes/helper.php'?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Registro de usuario</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/inicio.css"/>
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <form accept-charset="utf-8" action="/registro" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" required>
            <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'nombre'): '';?>

            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" required>
            <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'apellido'): '';?>

            <label for="email">Email</label>
            <input type="email" name="email" required>
            <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'email'): '';?>

            <label for="passw">Contraseña</label>
            <input type="password" name="passw" required>
            <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'passw'): '';?>

            <input type="submit" name="submit" value="Registrar" class="boton2">
        </form>
         <?php borrarErrores(); ?>
    </div>
</body>
</html>

