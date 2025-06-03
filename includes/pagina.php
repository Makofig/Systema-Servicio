<?php require_once 'conexion.php'; ?>
<?php require_once 'helper.php';?>
<!DOCTYPE HTMAL>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE-edge"> -->
        <meta name="viewport" content="width=device-width, user-scalable=no,
          initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>INTER SYS</title>
        <link rel="icon" type="image/png" href="/assets/img/logo3.png" />
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css"/> 
    </head> 
    <style>
        
    </style>    
    <body>
        <?php 
            //Desactiva las advertencias temporanles. 
            //error_reporting(E_ERROR | E_PARSE); 
            require_once 'lateral.php';
            require_once 'up.php'; 
            borrarErrores();
        ?>
    

    
 
  