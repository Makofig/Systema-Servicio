<?php
    require_once BASE_PATH.'/includes/helper.php';
    require_once BASE_PATH.'/includes/conexion.php';
    $db = getDBConnection();
?>
<aside class="sidebar">    
    <div class="header">
        <title>INTER SYS</title>
        <h3>Bienvenido, </h3>
    </div>
    <!-- Barra lateral -->
    <div class="body">
        <ul>
            <li class="border"><a href="/home">Inicio</a></li>
        </ul> 
        <details>
            <summary>Clientes</summary>
            <div class="cont-details">
                <ul>
                    <li><a href="/src/create/crearCliente.php">Nuevo</a></li>
                </ul>
                <ul>
                    <li><a href="/src/list/listarClient.php">Listar</a></li>
                </ul>
                <ul>
                    <li><a href="/src/list/listarDeudoresCompleto.php">Deudores</a></li>
                </ul>
            </div>    
        </details>    
        <details>
            <summary>Plan</summary>
            <div class="cont-details">
            <ul>
                <li><a href="/src/create/crearPlan.php">Crear Plan</a></li>
            </ul>
            <ul>
                <li><a href="/src/edit/editPlan.php">Editar Plan</a></li>
            </ul>
            <?php 
                $consulta = "SELECT * FROM plan;";
                $result = mysqli_query($db, $consulta);
                if(!empty($result)): 
                    while ($cat = mysqli_fetch_assoc($result)): 
            ?>   
            <ul>
                <li>
                    <a href="/src/list/listarPlan.php?id=<?=$cat['id']?>"><?=$cat['nombre']?></a>
                </li>
            </ul>    
            <?php 
                    endwhile;
                endif; 
            ?> 
            </div>    
        </details>    
        <details>
            <summary>Access Point</summary>
            <div class="cont-details">
            <ul>
                <li><a href="/src/create/crearPoint.php">Cargar</a></li>
            </ul> 
            <ul>
                <li><a href="/src/list/listarPoint.php">Listar</a></li>
            </ul>
            </div>    
        </details>       
        <ul>
            <li class="border"><a href="/src/create/emitirCuota.php">Emitir Cuotas</a></li>
        </ul>    
        <ul>
            <li class="border"><a href="/src/estadistica.php">Estadisticas</a></li>
        </ul>
        <ul>
            <li class="border"><a href="/src/edit/editDatos.php">Mis datos</a></li>
        </ul> 
        <button type="button" onclick="location='/src/cerrar.php'" class="boton boton-azul"> 
            Salir
        </button>
    </div>      
</aside>
