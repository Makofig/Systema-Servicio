<?php  
    require_once (BASE_PATH.'/includes/pagina.php'); 
?>  
<main class="container-main">
    <!-- Contenido Principal -->
    <!-- $entradas = conseguirEntradas($db); -->
    <?php 
    
    $Limite = 5;
    $iniciar = ($_GET['pagina']-1)* $Limite;
    $fecha = getdate();
    $num_cuota = $fecha['mon'];
    $sql = "SELECT COUNT(c.id) AS total FROM cliente c ".
           "INNER JOIN pagos p ON p.id_cliente = c.id ".
           "WHERE (p.estado = 1 and p.num_cuotas = $num_cuota) ;";
    $resultTotal = mysqli_query($db, $sql);
    $rowTotal = mysqli_fetch_assoc($resultTotal);
    $TotalRegistro = $rowTotal['total'];
    $TotalPaginas = ceil($TotalRegistro / $Limite); 
    $consulta= "SELECT c.nombre, c.apellido, c.id AS id_cl, p.* FROM pagos p ".
               "INNER JOIN cliente c ON c.id = p.id_cliente ".
               "WHERE (p.num_cuotas = $num_cuota and p.estado = 1)ORDER BY (c.apellido) ASC ".
                "LIMIT $iniciar, $Limite; ";
    $resultEntradas = mysqli_query($db, $consulta);
    While ($ent = mysqli_fetch_assoc($resultEntradas)):
    ?>
    <div>
        <a href="../content/contenido.php?id=<?=$ent['id_cl']?>">
            <h2><?=$ent['apellido'].' '.$ent['nombre']?></h2>
            <h3 class="fecha derecha"><?= number_format($ent['costo'],2);?></h3>
        </a>
    </div>
    <?php endwhile; ?>
    <footer class="pagination">
    <?php if ($_GET['pagina'] > 1) : ?>
        <a href="listarPagado.php?pagina=<?php echo $_GET['pagina'] - 1; ?>">Atrás</a>
    <?php endif; ?>
    <?php
        if ($_GET['pagina'] < ($TotalPaginas - 4)){
           $max = $_GET['pagina'] + 4; 
        }else{
            $max = $TotalPaginas; 
        }    
        $min = $_GET['pagina'];
    ?>
    <?php for ($i = $min; $i <= $max; $i++) : ?>
        <?php if ($i == $_GET['pagina']) : ?>
            <span class="current-page"><?php echo $i; ?></span>
        <?php else : ?>
            <a href="listarPagado.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($_GET['pagina'] < $TotalPaginas) : ?>
            <a href="listarPagado.php?pagina=<?php echo $_GET['pagina'] + 1; ?>">Siguiente</a>
    <?php endif; ?>
    </footer>
</main>
