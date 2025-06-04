<?php require_once (BASE_PATH.'/includes/pagina.php'); ?> 
<!-- Contenido Principal -->
<main class="container-main">
    <?php  
    if (!isset($_POST['busqueda'])){
        header("Location: /home");
    }
    ?>    
    <h1 style="padding: 1rem;">Busqueda: <?=$_POST['busqueda'] ?></h1>
    </br>
    <?php 
    $busq = trim($_POST['busqueda']); // quitamos los espacios en blanco al inicio y al final 
    $busqLower = strtolower($busq); 
    $busqLike = "%$busqLower%"; // Comodines para busqueda parcial 
    
    $consulta = $db->prepare("SELECT * FROM cliente WHERE LOWER(nombre) LIKE ? or LOWER(apellido) LIKE ?;");
    $consulta->bind_param("ss", $busqLike, $busqLike);
    $consulta->execute();
    $resultEntradas = $consulta->get_result();
    While ($ent = mysqli_fetch_assoc($resultEntradas)):                        
    ?>
    <div>
        <a href="/cliente/contenido/<?=$ent['id']?>">
            <h2><?=$ent['apellido'].' '.$ent['nombre']?></h2>
            <span class="fecha"><?=$ent['fecha_alta'].' | ';?></span>
            <p><?=$ent['direccion']?></p>
        </a>
    </div>
    <?php endwhile; ?>
</main>
