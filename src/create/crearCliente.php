<?php 
    require_once (BASE_PATH.'/includes/pagina.php'); 
?>  
<!-- Contenido Principal -->
<main class="container-main">
    <div id="container">
        <h1>Cliente Nuevo</h1>
        <p>
            Añadir nuevo Cliente 
        </p>
        <br/>
        <form action="/cliente/guardar" method="POST">
            <section>
                <label for="nombre">Nombre: </label>
                <label for="apellido">Apellido: </label>
                <input type="text" name="nombre" required/>
                <?php if (isset($_GET['nombre_error'])) :?>
                    <p class="error"><?php echo $_GET['nombre_error']; ?></p>
                <?php endif ?>
                <input type="text" name="apellido" required/>
                <?php if (isset($_GET['apellido_error'])) :?>
                    <p class="error"><?php echo $_GET['apellido_error']; ?></p>
                <?php endif ?>
            </section>                
            <section>
                <label for="ip">Dirección IP: </label>
                <label for="telefono">N° de Telefono: </label>
                <input type="text" name="ip"/>
                <?php if (isset($_GET['ip_error'])) :?>
                    <p class="error"><?php echo $_GET['ip_error']; ?></p>
                <?php endif ?>
                <input type="tel" name="telefono"/>
                <?php if (isset($_GET['telefono_error'])) :?>
                    <p class="error"><?php echo $_GET['telefono_error']; ?></p>
                <?php endif ?>
            </section> 
            <label for="direccion">Domicilio: </label>
            <input type="text" name="direccion"/>
            <?php if (isset($_GET['errores_entradas'])) :?>
                <p class="error"><?php echo $_GET['errores_entradas']; ?></p>
            <?php endif ?>
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
                    <option value="<?=$cat['id']?>">
                                <?=$cat['nombre']?>                    
                    </option>
                    <?php 
                            endwhile;
                        endif; 
                    ?>           
                </select>
                <?php if (isset($_SESSION['errores_entradas'])) :?>
                    <p class="error"><?php echo $_SESSION['errores_entradas']; ?></p>
                <?php endif ?>
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
                <?php if (isset($_SESSION['errores_entradas'])) :?>
                    <p class="error"><?php echo $_SESSION['errores_entradas']; ?></p>
                <?php endif ?>
            </section>
                
            <input type="submit" value="Siguiente" class="boton-verde" name="operacion"/>
            <input type="submit" value="Guardar" class="boton-verde" name="operacion"/>
        </form>
    </div>  
</main>