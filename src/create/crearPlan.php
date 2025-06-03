<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');?> 
<!-- Contenido Principal -->
<main class="container-main">
    <div id="container">
        <h1>Crear Nuevo Plan</h1>
        <p>
            Añadir Nuevo Plan
        </p>
        <br/>
        <form action="../save/guardarPlan.php" method="POST">
            <label for="nombre">Nombre del Plan: </label>
            <input type="text" name="nombre" required/>
            <label for="costo">Precio del Plan: </label>
            <input type="text" name="costo" required/>
            <input type="submit" value="Guardar" class="boton-verde"/>
        </form> 
    </div>    
</main>
