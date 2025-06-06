<?php require_once (BASE_PATH.'/includes/pagina.php');?>  
<!-- Contenido Principal -->
<main class="container-main">
    <?php    
        $id_url = $_GET['id'];
        $consultaAlt = "SELECT * FROM accespoint WHERE id = $id_url; ";
        $ent_act = mysqli_query($db, $consultaAlt);
        if ($ent_act && mysqli_num_rows($ent_act)>=1){
            $res_ent = mysqli_fetch_assoc($ent_act);

        }else{
            header("Location: /home");
        }
    ?> 
    <article>
        <h1><?=$res_ent['ssid'] ?></h1>
        <!-- $entradas = conseguirEntradas($db); -->
        <h2><?=$res_ent['ip_ap'] ?> | <?=$res_ent['frecuencia']?></h2>
        <p><?=$res_ent['localidad'] ?></p>
        <p>Clientes: <?= TotalClienteAP($db, $id_url) ?></p>
        <div>
        <?php if(isset($_SESSION['usuario']['id'])): ?>
            <button type="button" onclick="location='/point/editar/<?=$res_ent['id']?>'" class="boton boton-verde"> 
                Editar AP
            </button>
            <button type="button" onclick="location='/point/eliminar/<?=$res_ent['id']?>'" class="boton boton-azul"> 
                Eliminar AP
            </button>
        <?php endif; ?> 
        </div>    
    </article>        
</main>    
<div class="clearfix"></div>