<?php  
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
    $id = $_GET['id'];
    $consultaAlt = "SELECT * FROM accespoint WHERE id = $id; "; 
    $ent_act = mysqli_query($db, $consultaAlt);  
    if ($ent_act && mysqli_num_rows($ent_act)>=1){
        $res_ent = mysqli_fetch_assoc($ent_act);
        
    }else{
        header("Location: ../principal.php");
    } 
?>
<!-- Contenido Principal -->
<main class="container-main">
    <div id="container">
        <h1>Editar Access Point</h1>
        <p>
           Editar  <?=$res_ent['ssid']?>  
        </p>
        <br/>
        <form action="../save/guardarPoint.php?editar=<?=$res_ent['id']?>" method="POST">
            <section>
                <label for="nombre">SSID: </label>
                <label for="frecuencia">Frecuencia: </label>
                <input type="text" name="nombre" value="<?=$res_ent['ssid']?>"/>
                <?php echo isset($_SESSION['errores_entradas']) ? mostrarErrores($_SESSION['errores_entradas'], 'nombre'): '';?>
                <input type="text" name="frecuencia" value="<?=$res_ent['frecuencia']?>"/>
                <?php echo isset($_SESSION['errores_entradas']) ? mostrarErrores($_SESSION['errores_entradas'], 'frecuencia'): '';?>
            </section>
            
            <section>
                <label for="ip">Address: </label>
                <label for="local">Localidad: </label>
                <input type="text" name="ip" value="<?=$res_ent['ip_ap']?>"/>
                <?php echo isset($_SESSION['errores_entradas']) ? mostrarErrores($_SESSION['errores_entradas'], 'ip'): '';?>
                <input type="text" name="local" value="<?=$res_ent['localidad']?>"/></>
                <?php echo isset($_SESSION['errores_entradas']) ? mostrarErrores($_SESSION['errores_entradas'], 'local'): '';?>
            </section>
                     
            <input type="submit" value="Guardar" class="boton boton-verde"/>
        </form> 
    </div>
    <?php borrarErrores();?>
</main>


