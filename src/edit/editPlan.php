<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');?> 
<main class="container-main">
<!-- Contenido Principal -->
    <div id="container">
        <h1>Editar Plan</h1>
        <p>
            Editar Plan de: 
        </p>
        <br/>
        <form action="editPlan.php" method="GET">
            <select class="estilo-select" name="plan">
                <?php 
                    $id_p = null ;
                    $consulta = "SELECT * FROM plan;";
                    $result = mysqli_query($db, $consulta);
                    if(!empty($result)):
                        while ($cat = mysqli_fetch_assoc($result)): 
                ?>    
                <option value="<?=$cat['id']?>">
                            <?=$cat['nombre']?>                    
                </option>
                <?php       
                        endwhile;
                    endif;
                ?>
            </select>
            <input type="submit" class="boton-verde" value="Seleccionar">
        </form>
        <br/>
        <?php 
            if (isset($_GET['plan'])):
                $id_p = $_GET['plan'];
                $consultaPla = "SELECT * FROM plan WHERE id = $id_p";
                $result_plan = mysqli_query($db, $consultaPla);
                $plan = mysqli_fetch_assoc($result_plan);
        ?>
        <form action="../save/guardarPlan.php?editar=<?=$plan['id']?>" method="POST">
            <label for="plan">Plan</label>
            <label for="nombre">Nombre del Plan: </label>
            <input type="text" name="nombre" value="<?=$plan['nombre']?>"/>
            <label for="costo">Precio del Plan: </label>
            <input type="text" name="costo" value="<?=$plan['costo']?>"/>
            <input type="submit" value="Guardar" class="boton-verde"/>
            <button type="button" onclick="location='../delet/eliminarPlan.php?id=<?=$plan['id']?>'" class="boton boton-verde"> 
                    Eliminar
            </button>
        </form>
        <?php
            endif;
        ?>
    </div>    
</main>
<div class="clearfix"></div>