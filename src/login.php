<?php require_once BASE_PATH.'/includes/helper.php'?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <!--<meta http-equiv="X-UA-Compatible" content="IE-edge">-->
    <meta name="viewport" content="width=device-width, user-scalable=no,
          initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/inicio.css"/>
</head>
<body>
    <section class="container">
        <div class="logo">
            <img src="./assets/img/logo3.png" alt="Logo de la empresa">
        </div>
        <h2>Inicio de sesión</h2>
        <?php if (isset($_SESSION['error_login'])): ?>
            <p class="error"><?php echo $_SESSION['error_login']; ?></p>
        <?php endif ?>
        <form accept-charset="utf-8" action="/authenticate" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required autocomplete="none">
            <label for="passw">Contraseña:</label>
            <input type="password" name="passw" required>
            <input type="submit" value="Iniciar sesión">
        </form>
        <?php borrarErrores();?>    
        <div class="register-link">
            <a href="/formulario">¿No tienes una cuenta? Regístrate aquí</a>
        </div>
    </section> 
</body>
</html>