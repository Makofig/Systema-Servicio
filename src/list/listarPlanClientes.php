<?php require_once (BASE_PATH.'/includes/pagina.php'); ?>
<main class="container-main">
    <!-- Contenido Principal -->
    <?php  
        /*RECUPERANDO */
        $client = listarAccesId($db, $_GET['id']);
        $plan = recupPlanId($db, $_GET['id']); 
        $result = mysqli_fetch_assoc($plan);                                   
        if (empty($result['id'])){
            header("Location: ../../index.php");
        }
    ?>    
    <h1>Clientes <?=$result['nombre'] ?></h1>
    </br>
    <!-- $entradas = conseguirEntradas($db); -->
    <?php While ($ent = mysqli_fetch_assoc($client)): ?>
    <div>
        <a href="../content/contenido.php?id=<?=$ent['id']?>">
            <h2><?=$ent['apellido'].' '.$ent['nombre']?></h2>
            <span class="fecha"><?=$ent['fecha_alta'].' | '.$ent['ip'];?></span>
            <h3 class="precio"><?="$".number_format($result['costo'], 2); ?></h3>
        </a>
    </div>    
    <?php endwhile; ?>   
</main>
<div class="clearfix"></div>

  