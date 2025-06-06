<?php require_once (BASE_PATH.'/includes/pagina.php');?> 

<!-- Contenido Principal -->

<main class="container-main">
    <h1>Editar Pago</h1>
    <?php 
        if (isset($_GET['id'])):
            $id_c = $_GET['id'];

            $sql = "SELECT id, id_cliente, costo, num_cuotas FROM pagos WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $id_c);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1){
                $cuota = $result->fetch_assoc();

                $client = mysqli_fetch_assoc(clientId($db, $cuota['id_cliente']));
            }
           
           
    ?>
    <h2><?=$client['apellido'].' '.$client['nombre']?></h2>
    <form action="/cliente/pagos/guardar/<?=$cuota['id']?>" method="POST" enctype="multipart/form-data">
        <section>
            <label for="costo">Costo</label>
            <label for="entrega">Entrego</label>
            <input type="text" name="costo" value="<?=$cuota['costo']?>" readonly="" title="Costo De Cuota"/>
            <input type="text" name="entrega" title="Valor De Entrega"/>
        </section>    
        
        <section>
            <label for="cuota">N° de Cuota</label>
            <label for="fecha">Fecha de Pago</label>   
            <input name="cuota" type="number" readonly="" required="" value="<?=$cuota['num_cuotas']?>" /> 
            <input type="date" class="custom-input" name="fecha" required="" />
        </section>
                    
        <label for="coment">Comentario</label>
        <textarea name="coment" maxlength="255" placeholder="Ingrese el comentario..."></textarea>
        
        <section>
            <label for="image">Ticket 1</label>
            <label for="image2">Ticket 2</label>
            <input type="file" name="image" title="Ticket 1" /> 
            <input type="file" name="image2" title="Ticket 2" />
        </section>
        
        <input type="submit" value="Guardar" class="boton boton-verde"/>
        
    </form>
    <?php
        $stmt->close(); 
        endif;
    ?>
</main>
<div class="clearfix"></div>