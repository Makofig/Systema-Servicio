<?php  
require_once (BASE_PATH.'/includes/pagina.php'); 
?>
<style>
    header{
        display: none;
    }
    .bar-up{
        display: none;
    }
    main section{
        display: flex; 
        flex-direction: column;
        width: 98%;
        height: 540px; 
        gap: 0.8rem 0;
        align-items: center;
        background:#c3c3c3;
        box-sizing: border-box;
        padding: 0.8rem;
        margin: 0 0 0 0.8rem;
        overflow-y: scroll; 
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
    }
    main section table{
        position: relative;
        width: 90%;
        text-align: center; 
    }
    main section table td {
       padding: 0.2rem 0;
       border-bottom: 1px solid rgba(132, 139, 200, 0.18);
    }
    main section table tr:last-child td{
        border-bottom: none; 
    }
    .primary a{
        color: #6C9BCF;
    }
    main section table tbody div{
        display: flex; 
        position: absolute;
        top:-30px;
        right: 20px;
        align-items: center;
        justify-content: center;
    }
</style>
<main>
    <section>
        <!-- CONTENIDO DEL MAIN -->
        <h2>Lista de Deudores</h2>
        <select class="estilo-select" name="select" id="select">
            <?php
                $consulta = $db->prepare("select id,fecha_emision, numero AS cuota from cuotas;");
                $consulta->execute();
                $resultado = $consulta->get_result();
                while ($ent = mysqli_fetch_assoc($resultado)): 
                    switch ($ent['cuota']) {
                        case '1':
                            $mes = 'Enero'; 
                            break;
                        case '2':
                            $mes = 'Febrero'; 
                            break;    
                        case '3':
                            $mes = 'Marzo'; 
                            break;
                        case '4':
                            $mes = 'Abril'; 
                            break;
                        case '5':
                            $mes = 'Mayo'; 
                            break;
                        case '6':
                            $mes = 'Junio'; 
                            break;
                        case '7':
                            $mes = 'Julio'; 
                            break;
                        case '8':
                            $mes = 'Agosto'; 
                            break;
                        case '9':
                            $mes = 'Septiembre'; 
                            break;
                        case '10':
                            $mes = 'Octubre'; 
                            break;
                        case '11':
                            $mes = 'Noviembre'; 
                            break;
                        case '12':
                            $mes = 'Diciembre'; 
                            break;

                        default:
                            $mes = 'Mes no definido';
                            break;
                    }
            ?>
            <option value="<?=$ent['cuota']. '-' . date('Y', strtotime($ent['fecha_emision']))?>">
                <?=$mes .' '. $ent['fecha_emision']?>
            </option>
                <?php endwhile;?>
        </select>
        <table id="tablaClientes">
            <thead>
                <tr>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Celular</th>
                    <th>N° Cuota</th>
                    <th>Fecha</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                   
            </tbody>
        </table>
        
    </section>
</main>
<script>
    /*let select = document.getElementById("select");
    
    select.addEventListener('change',(event)=>{
        console.log(event.target.value); 
    })*/
    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar cambios en el select
        var selectedCuota = '3'; 
        // Realizar una petición AJAX para obtener los clientes de la cuota predeterminada
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/src/list/getClientesByCuota.php?cuota=' + selectedCuota, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Insertar la respuesta en el cuerpo de la tabla de clientes
                document.querySelector('#tablaClientes tbody').innerHTML = xhr.responseText;
            } else {
                console.error('Hubo un error al obtener los clientes');
            }
        };
        xhr.send();
        
        document.getElementById('select').addEventListener('change', function() {
            // Obtener el valor seleccionado
            var selected = this.value.split('-'); // ["6", "2023"] 
            var cuota = selected[0]; 
            var year = selected[1]; 

            // Realizar una petición AJAX para obtener los clientes de la cuota seleccionada
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/src/list/getClientesByCuota.php?cuota=' + cuota + '&year=' + year, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Limpiar el cuerpo de la tabla
                    document.querySelector('#tablaClientes tbody').innerHTML = xhr.responseText;
                } else {
                    console.error('Hubo un error al obtener los clientes');
                }
            };
            xhr.send();
        });
    });
</script> 