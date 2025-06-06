<?php require_once (BASE_PATH.'/includes/pagina.php');?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importar chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
</head>
<style>
    main section{
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        width: 98%;
        height: auto;
        gap: 1rem;
        background:#c3c3c3;
        box-sizing: border-box;
        padding: 0.8rem;
        margin: 0 0 0 0.8rem; 
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
    }
    main .grahp{ 
        border-radius: 0.8rem;
        overflow-x: auto; 
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }
    .color-1{
        /*background-color:#1B9C85;*/ 
        color: rgba(27, 156, 133, 1);     
    }
    .color-2{
        color:#F7D060; 
    }
    .color-3{
        /*background-color:#FF0060;*/
        color: rgba(255, 0, 96, 1);
    }
    .color-4{
        /*background-color: #7d8da1;*/
        color: rgba(125, 141, 161, 1);
    } 
    .color-5{
        background-color: #cdcdcd;
    }
    /*Estilando las tablas */
    main .table{
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem; 
    }
    main table{
        width: 100%;
        text-align: center; 
        font-size: 1.2rem;
    }
    main table tbody td{
        height: 2.5rem;
        border-bottom: 1px solid rgba(132, 139, 200, 0.18);
    } 
    main table tbody tr:last-child td{
            border: none; 
    }
</style>
<!-- Contenido Principal -->
<main class="container-main">
    <section>
        <article>
            <?php    
                $id_url = $_GET['id'];
                $consultaAlt = $db->prepare("SELECT c.*,a.ssid ,p.costo, p.nombre AS nom_cli FROM cliente c ".
                               "INNER JOIN plan p ON c.id_plan = p.id ".
                               "INNER JOIN accespoint a ON c.id_point = a.id ".
                               "WHERE c.id = ?; ");
                $consultaAlt->bind_param("s", $id_url); 
                $consultaAlt->execute();
                $ent_act = $consultaAlt->get_result();

                if ($ent_act && mysqli_num_rows($ent_act)>=1){
                    $res_ent = mysqli_fetch_assoc($ent_act);

                }else{
                    header("Location: /home");
                }
            ?>        
            <h2><?=$res_ent['nombre'] ?>, <?=$res_ent['apellido'] ?></h2>
            <h3>Phone: <?=$res_ent['telefono']?></h3>
            <h4>Fecha de Inicion: <?=$res_ent['fecha_alta'] ?></h4> 
            <h4>Address: <?=$res_ent['ip']?></h4>
            <p><?=$res_ent['direccion'] ?></p>
            <h3><?="$".number_format($res_ent['costo'], 2); ?></h3>
            <h3><?=$res_ent['nom_cli']?> | <?=$res_ent['ssid']?></h3>
            <div>
            <?php if(isset($_SESSION['usuario']['id'])): ?>
                <button type="button" onclick="location='/cliente/editar/<?=$res_ent['id']?>'" class="boton boton-verde"> 
                    Editar Cliente
                </button>
                <button type="button" onclick="location='../cuotas.php?id=<?=$res_ent['id']?>'" class="boton boton-verde"> 
                    Pagos
                </button>
                <button type="button" onclick="location='/src/delet/eliminarCliente.php?id=<?=$res_ent['id']?>'" class="boton boton-azul"> 
                    Eliminar Cliente
                </button>
            <?php endif;?>
            </div>     
        </article>
        <!--GRAFICA LINEAL-->
         <?php 
            /* DATOS PARA LA GRAFICA LINEAL */
            $fecha = getdate();
            $mes = $fecha['mon']; 
            $sum = 0; 
            
            $reporte = tablaClientCuotasId($db, $id_url);
            
            foreach ($reporte as $valor){
                $sum = $sum + $valor; 
            }
            
            $meses = convertMes($mes); 

        ?>
        <div class="grahp color-5">
            <canvas id="graficaLineal"></canvas>
            <script>
                // Obtener una referencia al elemento canvas del DOM
                const $graficaLineal = document.querySelector("#graficaLineal");
                // Las etiquetas son las que van en el eje X. 
                const etiquetasLineal = <?php echo json_encode($meses) ?> 
                // Podemos tener varios conjuntos de datos

                const datosVentas2018 = {
                    label: "Pagos de Cuotas",
                    data: <?php echo json_encode($reporte) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(27, 156, 133, 0.2)',// Color de fondo
                    borderColor: 'rgba(27, 156, 133, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                /*const datosVentas2019 = {
                    label: "Días 10 - 20",
                    data: <?php echo json_encode($reporte) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(247, 208, 96, 0.2)',// Color de fondo
                    borderColor: 'rgba(247, 208, 96, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datosVentas2020 = {
                    label: "Días > 20",
                    data: <?php echo json_encode($reporte) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(255, 0, 96, 0.2)', // Color de fondo
                    borderColor: 'rgba(255, 0, 96, 1)', // Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datosVentas2021 = {
                    label: "No-abonados",
                    data: <?php echo json_encode($reporte) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(10, 10, 10, 0.2)',// Color de fondo
                    borderColor: 'rgba(10, 10, 10, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };*/

                new Chart($graficaLineal, {
                    type: 'line',// Tipo de gráfica
                    data: {
                        labels: etiquetasLineal,
                        datasets: [
                            datosVentas2018,
                            //datosVentas2019,
                            //datosVentas2020,
                            //datosVentas2021,
                            // Aquí más datos...
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                        },
                    }
                });
            </script>
            <p>Promedio <?= ($sum / $mes) ?></p>
        </div> 
    </section>
    
</main>    
<div class="clearfix"></div>


