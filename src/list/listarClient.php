<style>
    .danger{
        background: rgba(255,0,0,0.6) !important;
    }
    .success{
        background: rgba(0, 255, 0, 0.3) !important;
    }
</style>
<?php require_once (BASE_PATH.'/includes/pagina.php'); ?>
<?php require_once (BASE_PATH.'/includes/conexion.php'); ?>  
<?php require_once (BASE_PATH.'/includes/helper.php'); ?>
        <main class="container-main">
            <!-- Contenido Principal -->
            <?php
            $db = getDBConnection();
            $Limite = 5;
            $iniciar = ($_GET['pagina']-1)* $Limite;
            $TotalRegistro = ClienteTotal($db); 
            $TotalPaginas = ceil($TotalRegistro / $Limite); 
            $listClient = listClientPaginacion($db, $iniciar, $Limite); 

            While ($ent = mysqli_fetch_assoc($listClient)):
                $debe = clientConDeudas($db, $ent['id']);
                if ($debe){
                    $class = 'danger';
                }else{
                    $class = 'success'; 
                }
            ?>
            <div class="<?=$class?>">
                <a href="/src/content/contenido.php?id=<?=$ent['id']?>">
                    <h2><?=$ent['apellido'].' '.$ent['nombre']?></h2>
                    <h4 class="fecha"><?=$ent['fecha_alta'].' | '.$ent['nom_cli'];?></h4>
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
            <?php if (($listClient->num_rows != 0)): ?>
                <footer class="pagination">
                <?php if ($_GET['pagina'] > 1) : ?>
                    <a href="/cliente/listar/<?php echo $_GET['pagina'] - 1; ?>">Atrás</a>
                <?php endif; ?> 
                <?php for ($i = $min; $i <= $max; $i++) : ?>
                    <?php if ($i == $_GET['pagina']) : ?>
                        <span class="current-page"><?php echo $i; ?></span>
                    <?php else : ?>
                        <a href="/cliente/listar/<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?> 

                <?php if ($_GET['pagina'] < $TotalPaginas) : ?>
                        <a href="/cliente/listar/<?php echo $_GET['pagina'] + 1; ?>">Siguiente</a>
                <?php endif; ?>
                </footer>
            <?php endif;?>
        </main>

  


