<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');?>   
<main class="container-main">
<!-- Contenido Principal -->
    <div id="container">
        <h1>Mis Datos</h1>
        <?php if (isset($_SESSION['completo'])): ?>
            <div class="alerta alerta-error">
                <?=$_SESSION['completo'];?>
            </div>   
        <?php elseif(isset($_SESSION['errores']['registro'])): ?>    
            <div class="alerta alerta-error">
                <?=$_SESSION['errores']['registro'];?>
            </div> 
        <?php endif;?>
        <form action="../actualizarUsuario.php" method="POST">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" value="<?=$_SESSION['usuario']['nombre']?>"/>
                <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'nombre'): '';?> 

                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" value="<?=$_SESSION['usuario']['apellido']?>"/>
                <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'apellido'): '';?>

                <label for="email">Email</label>
                <input type="email" name="email" value="<?=$_SESSION['usuario']['email']?>"/>
                <?php echo isset($_SESSION['errores']) ? mostrarErrores($_SESSION['errores'], 'email'): '';?>

                <input type="submit" name="submit" class="boton-verde" value="Actualizar" />
            </form>
    </div>    
    <?php borrarErrores(); ?>
</main>


