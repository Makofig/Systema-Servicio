<?php
    require_once BASE_PATH.'/includes/helper.php';
    require_once BASE_PATH.'/includes/conexion.php';
    $db = getDBConnection();
?>
<aside class="sidebar">    
    <div class="header">
        <title>INTER SYS</title>
        <h3>Bienvenido, <?=$_SESSION['usuario']['nombre']?></h3>
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
                    <li><a href="/cliente/crear">Nuevo</a></li>
                </ul>
                <ul>
                    <li><a href="/cliente/listar/1">Listar</a></li>
                </ul>
                <ul>
                    <li><a href="/cliente/deudores/completo">Deudores</a></li>
                </ul>
            </div>    
        </details>    
        <details>
            <summary>Plan</summary>
            <div class="cont-details">
            <ul>
                <li><a href="/plan/crear">Crear Plan</a></li>
            </ul>
            <ul>
                <li><a href="/plan/editar">Editar Plan</a></li>
            </ul>
            <ul>
                <li><a href="/plan/listar">Listar Plan</a></li>
            </ul>   
            </div>    
        </details>    
        <details>
            <summary>Access Point</summary>
            <div class="cont-details">
            <ul>
                <li><a href="/point/crear">Cargar</a></li>
            </ul> 
            <ul>
                <li><a href="/point/listar">Listar</a></li>
            </ul>
            </div>    
        </details>       
        <ul>
            <li class="border"><a href="/cuota/emitir">Emitir Cuotas</a></li>
        </ul>    
        <ul>
            <li class="border"><a href="/estadisticas">Estadisticas</a></li>
        </ul>
        <ul>
            <li class="border"><a href="/usuario/datos/editar">Mis datos</a></li>
        </ul> 
        <button type="button" onclick="location='/logout'" class="boton boton-azul"> 
            Salir
        </button>
    </div>      
</aside>
