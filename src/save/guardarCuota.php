<?php
require_once (BASE_PATH.'/includes/helper.php');
require_once (BASE_PATH.'/includes/conexion.php');
if (isset($_POST)){
    $db = getDBConnection(); 

    $mes_url = $_POST['cuota']; 
    $fecha = $_POST['fecha'];
    $year_url = substr($fecha, 0, 4);  
    $disp = ControlCuota($db, $mes_url, $year_url);
    $errores = array();
    if (!$disp){
        $emitir = false; 
        $nuevo_cuota = isset($_POST['cuota']) ? mysqli_real_escape_string($db, $_POST['cuota']) : false;
        if (isset($_GET['editarsys'])){
            $fecha_Edit = isset($_POST['fecha2']) ? mysqli_real_escape_string($db, $_POST['fecha2']) : false;
            if (!empty($fecha_Edit)){
            $nombre_valido = true;
            }else{
                $nombre_valido = false; 
                $errores['fecha2'] = "El formato de la fecha no es valido";
            }
        }else{
            $nuevo_fecha = isset($_POST['fecha']) ? mysqli_real_escape_string($db, $_POST['fecha']) : false;
            if (!empty($nuevo_fecha)){
            $nombre_valido = true;
            }else{
                $nombre_valido = false; 
                $errores['fecha'] = "El formato de la fecha no es valido";
            }
        }
        //$errores = array();
        if (!empty($nuevo_cuota && is_numeric($nuevo_cuota))){
            $nombre_valido = true;
        }else{
            $nombre_valido = false; 
            $errores['cuota'] = "La cuota no es valida";
        } 
        if (count($errores)==0){
            if (isset($_GET['editar'])){       
                $id_cu = $_GET['editar'];
                $sql = "UPDATE pagos SET fecha_pago = '$fecha_Edit', abonado = '$nuevo_cuota', estado = '1' WHERE id = $id_cu ;";
            }else{
                $sql = "INSERT INTO cuotas VALUE(NULL, '$nuevo_cuota', '$nuevo_fecha');"; 
                $emitir = true; 
                $errores['disponible'] = "LA CUOTA SE EMITIO CORRECTAMENTE - Redireccionando...";
            }    
            $guardar = mysqli_query($db, $sql);
        }else{
            echo $errores;
            header('Location: /cuota/emitir');
        }
        if ($emitir){
            $sqlc = "SELECT * FROM cuotas;";
            $Consultc = mysqli_query($db, $sqlc);
            while ($res_c = mysqli_fetch_assoc($Consultc)){
                $id_ult = $res_c['id'];
                $cuo_ult = $res_c['numero'];
            }
            GenerarCuotas($db, $cuo_ult, $id_ult);
        }else{
            header('Location: /cliente/listar/1');
        }
    }else{
        $errores['disponible'] = "LA CUOTA YA FUE EMITIDA - Redireccionando..."; 
        header("refresh:3, url=/cuota/emitir");
        require_once (BASE_PATH.'/includes/pagina.php'); 
        echo "<main id='principal' class='bloque-cont'>".
                $errores['disponible']. 
             "</main>"; 
         
    }
    header('Location: /home');
    require_once (BASE_PATH.'/includes/pagina.php'); 
        echo "<main id='principal' class='bloque-cont'>".
                $errores['disponible']. 
             "</main>";
}

