<?php  
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
    $id = $_GET['id'];
    
    $consultaAlt = $db->prepare("SELECT c.*, c.nombre, CONCAT (c.nombre, ' ' , c.apellido) AS cliente FROM cliente c ".
                    "INNER JOIN plan p ON c.id_plan = p.id ".
                    "WHERE c.id = ?; "); 
    $consultaAlt->bind_param("s", $id); 
    $consultaAlt->execute();
    $ent_act = $consultaAlt->get_result();

    if ($ent_act && mysqli_num_rows($ent_act)>=1){
        $res_ent = mysqli_fetch_assoc($ent_act);
        
    }else{
        header("Location: ../principal.php");
    }
    
?>
<!-- Contenido Principal -->
<main class="container-main">
    <div id="container">
        <h1>Editar Cliente</h1>
        <p>
           Editar  <?=$res_ent['nombre']?>  
        </p>
        <br/>
        <form action="../save/guardarCliente.php?editar=<?=$res_ent['id']?>" method="POST">
            <section>
                <label for="nombre">Nombre: </label>
                <label for="apellido">Apellido: </label>
                <input type="text" name="nombre" value="<?=$res_ent['nombre']?>"/>
                <?php if (isset($_GET['nombre_error'])) :?>
                    <p class="error"><?php echo $_GET['nombre_error']; ?></p>
                <?php endif ?>
                <input type="text" name="apellido" value="<?=$res_ent['apellido']?>"/>
                <?php if (isset($_GET['apellido_error'])) :?>
                    <p class="error"><?php echo $_GET['apellido_error']; ?></p>
                <?php endif ?>
            </section>
            
            <section>
                <label for="ip">Dirección IP: </label>
                <label for="telefono">Telefono: </label>
                <input type="text" name="ip" value="<?=$res_ent['ip']?>"/>
                <?php if (isset($_GET['ip_error'])) :?>
                    <p class="error"><?php echo $_GET['ip_error']; ?></p>
                <?php endif ?>
                <input type="text" name="telefono" value="<?=$res_ent['telefono']?>"/>
                <?php if (isset($_GET['telefono_error'])) :?>
                    <p class="error"><?php echo $_GET['telefono_error']; ?></p>
                <?php endif ?>
            </section>
            
            <section>
                <label for="direccion">Domicilio: </label>
                <label for="fecha_inicio">Fecha de inicio:</label>
                <input type="text" name="direccion" value="<?=$res_ent['direccion']?>"/></>
                <input type="date" name="fecha_inicio"/>
            </section>
            <section>
                <label for="plan">Plan</label>
                <label for="ap">AP</label>
                <select class="estilo-select" name="plan">
                    <?php 
                        $consulta = "SELECT * FROM plan;";
                        $result = mysqli_query($db, $consulta);
                        if(!empty($result)):
                            while ($cat = mysqli_fetch_assoc($result)): 
                    ?>    
                    <option value="<?=$cat['id']?>" <?=($cat['id'] == $res_ent['id_plan']) ? 'selected="selected"' : '' ?>>
                                <?=$cat['nombre']?>                    
                    </option>
                    <?php 
                            endwhile;
                        endif; 
                    ?>           
                </select>
                <!--SELECT ACCESS POINT-->
            
                <select class="estilo-select" name="ap">
                    <?php 
                        $ban = true; 
                        $consulap = "SELECT * FROM accespoint;";
                        $resultap = mysqli_query($db, $consulap);
                        if(!empty($resultap)):
                            while ($catap = mysqli_fetch_assoc($resultap)): 
                            if ($catap['id_point'] = null){
                                $ban = false;
                            }
                    ?>    
                    <option value="<?=$catap['id']?>" <?=($ban = true) ? 'selected="selected"' : '' ?>>
                                <?=$catap['ssid']?>                    
                    </option>
                    <?php 
                            endwhile;
                        endif; 
                    ?>           
                </select>
            </section>
  
            <input type="submit" value="Guardar" class="boton boton-verde"/>
        </form> 
    </div>
</main>

