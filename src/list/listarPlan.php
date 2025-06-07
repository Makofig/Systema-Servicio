<?php require_once (BASE_PATH.'/includes/pagina.php'); ?>  
<main class="container-main">
    <!-- Contenido Principal -->
    <?php 
    $consulta= "SELECT * FROM plan; "; 
    $resultEntradas = mysqli_query($db, $consulta);
    While ($ent = mysqli_fetch_assoc($resultEntradas)):
    ?>
    <div>
        <a href="/plan/listar/clientes/<?=$ent['id']?>">    
            <h2><?=$ent['nombre']?></h2>  
            <h4 class="fecha"><?=$ent['costo'];?></h4>
            <h3>Clientes: <?= totalClientesPlan($db,$ent['id'])?></h3>  
        </a>             
    </div>
    <?php endwhile; ?>
</main>
