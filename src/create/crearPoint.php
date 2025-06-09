<?php require_once (BASE_PATH.'/includes/pagina.php');?> 
<main class="container-main">
<!-- Contenido Principal -->
    <div id="container">
        <h1>Nuevo Access Point</h1>
        <p>Añadir Nuevo AP</p>
        <br/>
        <form action="/point/guardar" method="POST">
            <section>
                <label for="nombre">SSID: </label>
                <input type="text" name="nombre" required/>
                <label for="frecuencia">Frecuencia: </label>
                <input type="text" name="frecuencia" required/>
            </section>
            <section>
                <label for="ip">Address: </label> 
                <input type="text" name="ip" required/>
                <label for="local">Localidad: </label>
                <input type="text" name="local" required/>  
            </section>
            
            <input type="submit" value="Guardar" class="boton-verde"/>
        </form>
    </div>    
</main>