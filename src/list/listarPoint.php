<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php'); ?>  
<main class="container-main">
    <!-- Contenido Principal -->
    <?php 
    $consulta= "SELECT * FROM accespoint; "; 
    $resultEntradas = mysqli_query($db, $consulta);
    While ($ent = mysqli_fetch_assoc($resultEntradas)):
    ?>
    <div>
        <a href="../content/contenidoPoint.php?id=<?=$ent['id']?>">    
            <h2><?=$ent['ssid']?></h2>  
            <h4 class="fecha"><?=$ent['ip_ap'].' | '.$ent['frecuencia'];?></h4>
            <h3>Clientes: <?= TotalClienteAP($db,$ent['id'])?></h3>  
        </a>             
    </div>
    <?php endwhile; ?>
</main>
