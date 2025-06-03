<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');?>
<style>
    main section{
        display: flex; 
        flex-direction: column;
        width: 98%;
        gap: 0.8rem 0;
        align-items: center;
        background:#c3c3c3;
        box-sizing: border-box;
        padding: 0.8rem;
        margin: 0 0 0 0.8rem; 
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
    }
    main section table{
        position: relative;
        width: 90%;
        text-align: center; 
        font-size: 1.3rem; 
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
    .danger{
        color: #FF0060;
    }
    .success{
        color: #1B9C85;
    }
    .disabled-link{
        color: #888888 !important; 
        pointer-events: none; 
    }
</style>
<main>
    <?php 
    $id_url = $_GET['id'];
    $consulta= "SELECT * FROM pagos WHERE id_cliente = $id_url ORDER BY(num_cuotas);"; 
    $resultEntradas = mysqli_query($db, $consulta);
    $result = clientId($db, $id_url);
    $client = mysqli_fetch_assoc($result);
    ?>
    <!-- CONTENIDO DEL MAIN -->
    <section>
        <h2><?= $client['apellido'].' '.$client['nombre'] ?></h2>
        <h3 class="primary"><a href="/create/emitirCuotaIndividual.php?id=<?=$id_url?>">Emitir cuota</a></h3>
        <table>
            <thead>
                <tr>
                    <th>N° Cuota</th>
                    <th>Total</th>
                    <th>Entregado</th>
                    <th>Fecha de Emisión</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
        <?php while ($ent = mysqli_fetch_assoc($resultEntradas)) : 

            if ($ent['estado'] == false){
                $deuda = 'Adeudado';
                $class = 'danger';
                $disabled = ''; 
            }else{
                $deuda = 'Pagado';
                $class = 'success';
                $disabled = 'disabled-link';
            } 
        ?>       
            <tr>    
                <td><?=$ent['num_cuotas']?></td>
                <td>$<?= number_format($ent['costo'], 2)?></td>
                <td>$<?= number_format($ent['abonado'], 2)?></td>
                <td><?=$ent['fecha_emision']?></td>
                <td class="<?=$class?>"><?=$deuda?></td>
                <td class='primary'><a href="/edit/editPago.php?id=<?=$ent['id']?>" class="<?=$disabled?>">Editar</a></td>  
                <td class='primary'><a href="/list/verCuota.php?id=<?=$ent['id']?>" >Ver</a></td>
            </tr>    
        <?php endwhile;?>        
            </tbody>
        </table>           
    </section>
</main>

