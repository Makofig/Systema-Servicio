<?php require_once (BASE_PATH.'/includes/pagina.php'); ?> 

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
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        grid-column: 1/-1;
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
<main class="container-main"> 
    <!-- Contenido Principal -->
    <?php 
        /* DATOS PARA LA GRAFICA LINEAL */
        $fecha = getdate();
        $mes = $fecha['mon']; 

        $reporte = tablaClientCuotasCosto($db, $mes);

        /* CORRECCIÓN DEL ARRAY PARA MOSTRAR LOS DATOS EN LA GRAFICA */
        $array = array();
        for ($j = 0; $j < 4; $j++){
            for ($i = 0; $i < $mes; $i++){
                $array[$i] = $reporte[$i+1][$j];
            }
            $correcto[$j+1] = $array; 
            $array = [];
        } 

        $meses = convertMes($mes); 
        
    ?>
    <section>
        
        <!-- TABLA -->
        <div class="table">
            <h2>Costos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>10 Dias</th>
                        <th>10 - 20 Dias</th>
                        <th>20 > Dias </th>
                        <th>No-Recaudado</th>
                    </tr>
                </thead>
                <tbody>
            <?php   
                $cont = 0;  
                $reco = $mes -1; 
                while ($cont <= $reco) :  
                                             
            ?>       
                <tr>    
                    <td><?= ($meses[$cont]);?></td>
                    <td class="color-1"><strong><?="$". number_format(json_encode($correcto[1][$cont]), 2);?></strong></td>
                    <td class="color-4"><strong><?="$". number_format(json_encode($correcto[2][$cont]), 2);?></strong></td>
                    <td class="color-3"><strong><?="$". number_format(json_encode($correcto[3][$cont]), 2);?></strong></td>
                    <td><?="$". number_format(json_encode($correcto[4][$cont]), 2);?></td>
                    <!--<td class='primary'><a href="./details/detailClient.php?id=<?="Hola"?>">Detalles</a></td>-->   
                </tr>    
            <?php $cont = $cont +1;  endwhile;?>        
                </tbody>
            </table>
        </div>
        <!--TABLA DE PROMEDIO-->
        <div class="table">
            <!-- SUMA ENTRE LOS PRIMEROS 20 DIAS -->
            <?php
                $totalVeinte = 0; 
                $totalCompleto = 0; 
                for ($i=0; $i <= $reco; $i++){
                    $sumNorm[$i] = $correcto[1][$i] + $correcto[2][$i];
                    $sumCompl[$i] = $sumNorm[$i] + $correcto[3][$i];
                    $totalVeinte = $totalVeinte + $sumNorm[$i];
                    $totalCompleto = $totalCompleto + $sumCompl[$i];
                } 
                // PROMEDIO DE RECAUDACIÓN EN ENTRE LOS PRIMEROS 20 DÍAS; 
                $mediaVeinte = $totalVeinte / $mes;   
                $mediaCompleto = $totalCompleto / $mes; 
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>20 días</th>
                        <th>Completo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $cont = 0;    
                        while ($cont <= $reco) :
                    ?>
                    <tr>
                        <td><?= ($meses[$cont]);?></td>
                        <td class="color-1"><strong>$<?= number_format($sumNorm[$cont], 2);?></strong></td>
                        <td class="color-4"><strong>$<?= number_format($sumCompl[$cont], 2);?></strong></td>
                    </tr>
                    <?php $cont = $cont +1; endwhile;?>
                    <tr>
                        <td>Media</td>
                        <td><strong>$<?= number_format($mediaVeinte, 2)?></strong></td>
                        <td><strong>$<?= number_format($mediaCompleto, 2)?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>     
        <!--GRAFICA LINEAL-->
        <div class="grahp color-5">
            <canvas id="graficaLineal"></canvas>
            <script>
                // Obtener una referencia al elemento canvas del DOM
                const $graficaLineal = document.querySelector("#graficaLineal");
                // Las etiquetas son las que van en el eje X. 
                const etiquetasLineal = <?php echo json_encode($meses) ?> 
                // Podemos tener varios conjuntos de datos

                const datosVentas2018 = {
                    label: "10 Días",
                    data: <?php echo json_encode($correcto[1]) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(27, 156, 133, 0.2)',// Color de fondo
                    borderColor: 'rgba(27, 156, 133, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datosVentas2019 = {
                    label: "Días 10 - 20",
                    data: <?php echo json_encode($correcto[2]) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(247, 208, 96, 0.2)',// Color de fondo
                    borderColor: 'rgba(247, 208, 96, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datosVentas2020 = {
                    label: "Días > 20",
                    data: <?php echo json_encode($correcto[3]) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(255, 0, 96, 0.2)', // Color de fondo
                    borderColor: 'rgba(255, 0, 96, 1)', // Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datosVentas2021 = {
                    label: "No-abonados",
                    data: <?php echo json_encode($correcto[4]) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(10, 10, 10, 0.2)',// Color de fondo
                    borderColor: 'rgba(10, 10, 10, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };

                new Chart($graficaLineal, {
                    type: 'line',// Tipo de gráfica
                    data: {
                        labels: etiquetasLineal,
                        datasets: [
                            datosVentas2018,
                            datosVentas2019,
                            datosVentas2020,
                            datosVentas2021,
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
        </div> 
    </section>
</main>
      
 