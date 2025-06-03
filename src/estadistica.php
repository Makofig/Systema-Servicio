<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php'); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importar chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
</head>
<style>
    main .contenedor{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        /*grid-template-rows: 0.2fr repeat(3, 1fr); */
        padding: 0.3rem 0.8rem 0 0.8rem;
        gap: 0.5rem 0.8rem;
    }
    main .bloque{
        height: 6rem;
        min-width: 12rem;
        display: inherit; 
        text-align: center;
        place-content: center;
        color: white;
        border-radius: 0.8rem;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    main .bloque:hover{
        box-shadow: none;
    }
    main .grahp{
        display: flex;
        width: auto; 
        justify-content: center; 
        align-items: center;
        border-radius: 0.8rem;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }
    main > div div:nth-child(4){
        grid-row: 2/5;
    }
    main .contenedor div:last-child{
        grid-column: 1/4;
        grid-row: 2/5;
    }
    .color-1{
        /*background-color:#1B9C85;*/ 
        background-color: rgba(27, 156, 133, 0.5);
        border: 2px solid rgba(27, 156, 133, 1);
    }
    .color-2{
        background-color:#F7D060; 
    }
    .color-3{
        /*background-color:#FF0060;*/
        background-color: rgba(255, 0, 96, 0.5);
        border: 2px solid rgba(255, 0, 96, 1);
    }
    .color-4{
        /*background-color: #7d8da1;*/
        background-color: rgba(125, 141, 161, 0.5);
        border: 2px solid rgba(125, 141, 161, 1);
    } 
    .color-5{
        background-color: #cdcdcd;
    }
   
</style>
<main>
    <div class="contenedor">
        <?php 
            /*$fecha = getdate();
            $mes = $fecha['mon'];
            var_dump($mes); 
            for ($i = 1; $i <= $mes; $i++){
                echo $i;

            }*/
            /* DATOS PARA LA GRAFICA CIRCULAR */
           
            $totalAbonado = sumaAbonados($db); 
            $totalCompleto = sumaCostos($db); 
            $totalAdeudado = $totalCompleto - $totalAbonado; 
            
            $porcentajeAbonado = ($totalAbonado * 100)/$totalCompleto; 
            $porcentajeAdeudado = ($totalAdeudado * 100)/$totalCompleto;
            $datosPagos = [round($porcentajeAbonado, 2), round($porcentajeAdeudado, 2)];
            
            /* DATOS PARA LA GRAFICA LINEAL */
            $fecha = getdate();
            $mes = $fecha['mon']; 
            
            $reporte = tablaClientCuotas($db, $mes);
            
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
        <div class="bloque color-1"><span>Total Abonado: $<?=number_format($totalAbonado, 2) ?></span></div>
        <div class="bloque color-3"><span>Total Adeudado: $<?=number_format($totalAdeudado, 2) ?></span></div>
        <div class="bloque color-4"><span>Total: $<?=number_format($totalCompleto, 2) ?></span></div>    
        <div class="grahp color-5">
            <canvas id="grafica"></canvas>
            <script type="text/javascript">
                // Obtener una referencia al elemento canvas del DOM
                const $grafica = document.querySelector("#grafica");
                // Las etiquetas son las porciones de la gráfica
                const etiquetas = ["Abonado", "Deuda"]
                // Podemos tener varios conjuntos de datos. Comencemos con uno
                const datosIngresos = {
                    data: <?php echo json_encode($datosPagos) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    // Ahora debería haber tantos background colors como datos, es decir, para este ejemplo, 4
                    backgroundColor: [
                        'rgba(27, 156, 133, 0.5)',
                        'rgba(255, 0, 96, 0.5)',
                        
                    ],// Color de fondo
                    borderColor: [
                        'rgba(27, 156, 133, 1)',
                        'rgba(255, 0, 96, 1)',
                        
                    ],// Color del borde
                    borderWidth: 2,// Ancho del borde
                };
                new Chart($grafica, {
                    type: 'doughnut',// Tipo de gráfica. Puede ser doughnut o pie
                    data: {
                        labels: etiquetas,
                        datasets: [
                            datosIngresos,
                            // Aquí más datos...
                        ]
                    },
                });
            </script>
        </div>
        <!--
        <div class="bloque color-4">
            <?php
                foreach ($meses as $index => $valor):
            ?>
                <span>Total-Adeudados:<?=$valor?> - <?=json_encode($correcto[4][$index])?></span>
            <?php endforeach;?>    
        </div>
        -->
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
    </div>
</main>