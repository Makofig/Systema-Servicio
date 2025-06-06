<?php
$numId = $_GET['id'];
require_once (BASE_PATH.'/includes/pagina.php'); 
?>
<style>
    main h1{
        padding: 0 0 0 0.5rem;
    }
    main .div-principal{
        display: grid; 
        padding: 1rem;
        grid-template-columns: 2fr 1.5fr; 
        grid-template-areas: "section" "section"; 
        gap: 0.5rem; 
    }
    main .div-principal section{
        display: flex; 
        flex-direction: column;
        padding: 0.5rem ; 
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
    }
    main .div-principal section:last-child{
        width: 450px;
        height: 300px;
        overflow: auto; 
        padding: 0.5rem; 
        align-content: center;
        text-align: center;
        color: #677483;
       
    }
    main .div-principal section img{
        aspect-ratio: 1/1; 
    }
    main table {
        text-align: center; 
    }
    main table tbody td{
        height: 2.8rem;
        border-bottom: 1px solid rgba(132, 139, 200, 0.18);;
        color: #677483;
    } 
    main table tbody tr:last-child td{
            border: none; 
    }
    .danger{
        color: #FF0060;
    }
    .success{
        color: #1B9C85;
    }
</style>
<main>
    <?php

        $sql= "SELECT * FROM pagos WHERE id = ?"; 
        $consulta = $db->prepare($sql);
        $consulta->bind_param("i", $numId);
        $consulta->execute();
        $resultPago = $consulta->get_result();

        if ($resultPago && $resultPago->num_rows === 1){
            $ent = $resultPago->fetch_assoc();
            
        }else {
            header("refresh:3, url=/cliente/pagos/".$ent['id_cliente']);  
            echo "<main id='principal' class='bloque-cont'>".
                    $errores['disponible']. 
                "</main>"; 
            exit();
        }
        
        if ($ent['estado'] == false){
            $deuda = 'Adeudado';
            $class = 'danger';
            $disabled = ''; 
        }else{
            $deuda = 'Pagado';
            $class = 'success';
            $disabled = 'disabled-link';
        } 
            
        $client = mysqli_fetch_assoc($result = clientId($db, $ent['id_cliente']));
        
    ?>
    <h1><?=$client['apellido'].', '.$client['nombre']?></h1>
    <div class="div-principal">
        <section>
            <table>
                <tbody>
                    <tr><td><p>Numero de Cuota: <?=$ent['num_cuotas']?></p></td></tr>
                    <tr><td><p>Fecha de pago: <?=$ent['fecha_pago']?></p></td></tr>
                    <tr><td><p>Costo: <?=$ent['costo']?></p></td></tr>
                    <tr><td><p>Abonado: <?=$ent['abonado']?></p></td></tr>
                    <tr><td><span>Estado: </span><span class="<?=$class?>"><?=$deuda?></span></td></tr>
                    <tr><td><p>Comentario: <?=$ent['comentario']?></p></td></tr>
                </tbody>  
            </table>
        </section>
        <section>
            <?php
                $ban = TRUE; 
                if(($ent['image'] != null)||($ent['image2'] != null)){
                    $gestor = opendir('../../images');
                    
                    if($gestor){
                        while(($image = readdir($gestor))!==  false && $ban){ 
                            if($image != '.' && $image != '..'){
                                if ($image = $ent['image']){ 
                                    $ban = FALSE; 
                                    // RECUPERAMOS EL TAMAÑO DE LA IMAGEN; 
                                    $source = imagecreatetruecolor(1000, 1333);
                                    $tamaño = getimagesize("../../images/$image");
                                    // ANCHO DE LA IMAGEN;
                                    $ancho = $tamaño[0]; 
                                    // REDIMENCIONA LA IMAGEN 
                                    imagescale($source, $ancho);
                            
                                    echo "<img src='../../images/$image' alt='Ticket 1'/><br/>";
                                    
                                }
                                if ($image = $ent['image2']){ 
                                    $ban = FALSE; 
                                    // RECUPERAMOS EL TAMAÑO DE LA IMAGEN; 
                                    $source = imagecreatetruecolor(1000, 1333);
                                    $tamaño = getimagesize("../../images/$image");
                                    // ANCHO DE LA IMAGEN;
                                    $ancho = $tamaño[0]; 
                                    // REDIMENCIONA LA IMAGEN 
                                    imagescale($source, $ancho);
                                    echo "<img src='../../images/$image' alt='Ticket 2'/><br/>";
                                    
                                }
                            }
                        }
                    }  
                }else{
                    echo "No hay Ticket Cargdo";
                }    
            ?>
        </section>    
    </div>
</main>


