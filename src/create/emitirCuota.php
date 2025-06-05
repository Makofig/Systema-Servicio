<?php 
    require_once (BASE_PATH.'/includes/pagina.php');
    $fecha = getdate();
    $mes = $fecha['mon'];     
?> 
<main class="container-main">
<!-- Contenido Principal -->
    <div id="container">
        <h1>Cuota N°: <?=$mes?></h1>
        <br/>
        <form action="/cuota/guardar" method="POST">
            <label for="cuota">Numero de la cuota: </label>
            <input type="text" name="cuota" required value="<?=$mes?>" />
            <label for="fecha">Fecha de Emision: </label>
            <input type="date" class="custom-input" name="fecha" required/>
            <input type="submit" value="Emitir" class="boton-verde"/>
        </form>
    </div>    
</main>
