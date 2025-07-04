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
        // Si existe 'year' en la URL, lo usamos. Si no, usamos el año actual.
        $year = isset($_GET['year']) ? intval($_GET['year']) : $fecha['year'];
        $reporte = tablaClientCuotasCosto($db, $mes, $year);

        /* ORGANIZAR LOS DATOS POR CATEGORÍA */
        $correcto = [
            'pago_temprano'    => [],
            'pago_intermedio'  => [],
            'pago_tardio'      => [],
            'deuda'            => []
        ];

        for ($i = 1; $i <= $mes; $i++) {
            foreach ($correcto as $key => &$array) {
                $array[] = $reporte[$i][$key] ?? 0;
            }
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
                <?php for ($i = 0; $i < $mes; $i++): ?>
                    <tr>
                        <td><?= htmlspecialchars($meses[$i]) ?></td>
                        <td class="color-1">
                            <strong><?= "$" . number_format($correcto['pago_temprano'][$i], 2) ?></strong>
                        </td>
                        <td class="color-4">
                            <strong><?= "$" . number_format($correcto['pago_intermedio'][$i], 2) ?></strong>
                        </td>
                        <td class="color-3">
                            <strong><?= "$" . number_format($correcto['pago_tardio'][$i], 2) ?></strong>
                        </td>
                        <td>
                            <?= "$" . number_format($correcto['deuda'][$i], 2) ?>
                        </td>
                    </tr>
                <?php endfor; ?>   
                </tbody>
            </table>
        </div>
        <!--TABLA DE PROMEDIO-->
        <div class="table">
            <?php
            // Inicializar totales
            $totalVeinte = 0; 
            $totalCompleto = 0;
            $sumNorm = [];
            $sumCompl = [];

            // Calcular sumas por mes
            for ($i = 0; $i < $mes; $i++) {
                $pagoTemprano = $correcto['pago_temprano'][$i] ?? 0;
                $pagoIntermedio = $correcto['pago_intermedio'][$i] ?? 0;
                $pagoTardio = $correcto['pago_tardio'][$i] ?? 0;

                $sumNorm[$i] = $pagoTemprano + $pagoIntermedio;
                $sumCompl[$i] = $sumNorm[$i] + $pagoTardio;

                $totalVeinte += $sumNorm[$i];
                $totalCompleto += $sumCompl[$i];
            }

            // Promedios
            $mediaVeinte = $mes > 0 ? $totalVeinte / $mes : 0;
            $mediaCompleto = $mes > 0 ? $totalCompleto / $mes : 0;
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
                    <?php for ($i = 0; $i < $mes; $i++): ?>
                    <tr>
                        <td><?= htmlspecialchars($meses[$i]) ?></td>
                        <td class="color-1"><strong>$<?= number_format($sumNorm[$i], 2) ?></strong></td>
                        <td class="color-4"><strong>$<?= number_format($sumCompl[$i], 2) ?></strong></td>
                    </tr>
                    <?php endfor; ?>
                    <tr>
                        <td><strong>Media</strong></td>
                        <td><strong>$<?= number_format($mediaVeinte, 2) ?></strong></td>
                        <td><strong>$<?= number_format($mediaCompleto, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>     
        <select name="year" id="yearSelect" class="estilo-select">
            <option value = "0">Seleccione un año</option>
            <?php
            // Obtener los años disponibles si repetir
            $consulta = $db->prepare("
                SELECT DISTINCT YEAR(fecha_emision) AS year 
                FROM cuotas 
                ORDER BY year DESC
            ");
            $consulta->execute();
            $result = $consulta->get_result();

            $añoSeleccionado = $_GET['year'] ?? 0;

            while ($row = $result->fetch_assoc()) {
                $year = $row['year'];
                $selected = ($añoSeleccionado == $year) ? 'selected' : '';
                echo "<option value=\"$year\" $selected>$year</option>";
            }
            ?>
        </select>
        <!--GRAFICA LINEAL-->
        <div class="grahp color-5">
            <canvas id="graficaLineal"></canvas>
            <script>
                // Obtener una referencia al elemento canvas del DOM
                const $graficaLineal = document.querySelector("#graficaLineal");
                // Las etiquetas son las que van en el eje X. 
                const etiquetasLineal = <?php echo json_encode($meses) ?> 
                // Podemos tener varios conjuntos de datos

                const datasetPagoTemprano = {
                    label: "10 Días",
                    data: <?php echo json_encode($correcto['pago_temprano']) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(27, 156, 133, 0.2)',// Color de fondo
                    borderColor: 'rgba(27, 156, 133, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datasetPagoIntermedio = {
                    label: "Días 10 - 20",
                    data: <?php echo json_encode($correcto['pago_intermedio']) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(247, 208, 96, 0.2)',// Color de fondo
                    borderColor: 'rgba(247, 208, 96, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datasetPagoTardio = {
                    label: "Días > 20",
                    data: <?php echo json_encode($correcto['pago_tardio']) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(255, 0, 96, 0.2)', // Color de fondo
                    borderColor: 'rgba(255, 0, 96, 1)', // Color del borde
                    borderWidth: 1,// Ancho del borde
                };
                const datasetPagoNoAbonado = {
                    label: "No-abonados",
                    data: <?php echo json_encode($correcto['deuda']) ?>, // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(10, 10, 10, 0.2)',// Color de fondo
                    borderColor: 'rgba(10, 10, 10, 1)',// Color del borde
                    borderWidth: 1,// Ancho del borde
                };

                new Chart($graficaLineal, {
                    type: 'line',// Tipo de gráfica
                    data: {
                        labels: etiquetasLineal,
                        datasets: [
                            datasetPagoTemprano,
                            datasetPagoIntermedio,
                            datasetPagoTardio,
                            datasetPagoNoAbonado,
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
<script>
    document.getElementById('yearSelect').addEventListener('change', function () {
        const selectedYear = this.value;
        if (selectedYear != "0") {
            const url = new URL(window.location.href);
            url.searchParams.set('year', selectedYear);
            window.location.href = url.toString();
        }
    });
</script>
      
 