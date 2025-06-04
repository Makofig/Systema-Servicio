<?php
    require_once (BASE_PATH.'/includes/pagina.php');
    require_once (BASE_PATH.'/includes/conexion.php'); 
    require_once (BASE_PATH.'/includes/helper.php'); 
?>  
<main class="container-main">
    <!-- Contenido Principal -->
    <?php 
    $db = getDBConnection();
    $Limite = 5;
    $iniciar = ($_GET['pagina']-1)* $Limite;
    $fecha = getdate();
    $num_cuota = $fecha['mon'];
    $totalMon = listDeudoresMon($db, $num_cuota) ?? 0;
   
    $TotalRegistro = $totalMon;
    $TotalPaginas = ceil($TotalRegistro / $Limite); 
    $resutl = listDeudoresPaginar($db, $num_cuota, $iniciar, $Limite);
    
    While ($ent = mysqli_fetch_assoc($resutl)):
    ?>
    <div>
        <a href="../content/contenido.php?id=<?=$ent['id_cl']?>">
            <h2><?=$ent['apellido'].' '.$ent['nombre']?></h2>
            <h3 class="fecha derecha"><?= number_format($ent['costo'],2);?></h3>
        </a>
    </div>
    <?php
    if ($_GET['pagina'] < ($TotalPaginas - 4)){
       $max = $_GET['pagina'] + 4; 
    }else{
        $max = $TotalPaginas; 
    }    
    $min = $_GET['pagina']; 

    ?>
    <?php endwhile; ?>
  
    <?php if (($totalMon != '0')): ?>
    <footer class="pagination">
        <?php if ($_GET['pagina'] > 1) : ?>
        <a href="/cliente/deudores/<?php echo $_GET['pagina'] - 1; ?>">Atrás</a>
        <?php endif; ?>
        <?php for ($i = $min; $i <= $max; $i++) : ?>
            <?php if ($i == $_GET['pagina']) : ?>
                <span class="current-page"><?php echo $i; ?></span>
            <?php else : ?>
                <a href="/cliente/deudores/<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($_GET['pagina'] < $TotalPaginas) : ?>
                <a href="/cliente/deudores<?php echo $_GET['pagina'] + 1; ?>">Siguiente</a>
        <?php endif; ?>
    </footer>
    <?php endif;?>
</main>

