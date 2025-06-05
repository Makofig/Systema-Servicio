<?php 
    require_once (BASE_PATH.'/includes/pagina.php');
    $result = clientId($db, $_GET['id']);
    $client = mysqli_fetch_assoc($result);
?> 
<main class="container-main">
<!-- Contenido Principal -->
    <div id="container">
        <h2><?=$client['apellido'].', '.$client['nombre']?></h2>
    
        <br/>
        <form action="/cuota/individual/guardar" method="POST">
            <label for="id">ID:</label>
            <input type="text" name="id" value="<?=$_GET['id']?>" readonly=""/> 
            <label for="cuota">Número de cuota: </label>
            <input type="text" name="cuota" required />
            <label for="fecha">Fecha de Emision: </label>
            <input type="date" class="custom-input" name="fecha" required/>
            <input type="submit" value="Emitir" class="boton-verde" />
        </form>
    </div>    
</main>

