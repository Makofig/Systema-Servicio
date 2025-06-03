<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php'); ?> 
<!-- Contenido Principal -->
<main class="container-main">
    <?php  
    if (!isset($_POST['busqueda'])){
        header("Location: ../index.php");
    }
    ?>    
    <h1 style="padding: 1rem;">Busqueda: <?=$_POST['busqueda'] ?></h1>
    </br>
    <?php 
    $busq = $_POST['busqueda'];
    $busqLower = strtolower($busq); 
    $consulta = $db->prepare("SELECT * FROM cliente WHERE LOWER(nombre) LIKE ? or LOWER(apellido) LIKE ?;");
    $consulta->bind_param("ss", $busqLower, $busqLower);
    $consulta->execute();
    $resultEntradas = $consulta->get_result();
    While ($ent = mysqli_fetch_assoc($resultEntradas)):                        
    ?>
    <div>
        <a href="/content/contenido.php?id=<?=$ent['id']?>">
            <h2><?=$ent['apellido'].' '.$ent['nombre']?></h2>
            <span class="fecha"><?=$ent['fecha_alta'].' | ';?></span>
            <p><?=$ent['direccion']?></p>
        </a>
    </div>
    <?php endwhile; ?>
</main>
