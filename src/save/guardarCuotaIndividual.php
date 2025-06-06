<?php
require_once (BASE_PATH.'/includes/helper.php');
require_once (BASE_PATH.'/includes/conexion.php');
if (isset($_POST)){
    //RECUPERANDO LOS DATOS DE POST; 
    $db = getDBConnection(); 
    
    $id_url = $_POST['id'];
    $mes_url = isset($_POST['cuota']) ? mysqli_real_escape_string($db, $_POST['cuota']) : false;
    $fecha = isset($_POST['fecha']) ? mysqli_real_escape_string($db, $_POST['fecha']) : false;
    
    $year_url = substr($fecha, 0, 4);  
    $disp = ControlCuotaId($db, $mes_url, $year_url, $id_url);

    $errores = array();
    if (!$disp){
        $emitir = false; 
        $nuevo_cuota = $mes_url; 
        $nuevo_fecha = $fecha;
        //ERRORES DE LOS DATOS; 
        if (!empty($nuevo_fecha)){
            $nombre_valido = true;
        }else{
            $nombre_valido = false; 
            $errores['fecha'] = "El formato de la fecha no es valido";
        }
        
        if (!empty($nuevo_cuota && is_numeric($nuevo_cuota))){
            $nombre_valido = true;
        }else{
            $nombre_valido = false; 
            $errores['cuota'] = "La cuota no es valida";
        } 
        
        if (count($errores) == 0){
         
            $sqlc = "SELECT * FROM cuotas;";
            $Consultc = mysqli_query($db, $sqlc);
            while ($res_c = mysqli_fetch_assoc($Consultc)){
                if ($mes_url === $res_c['numero']){
                    $id_ult = $res_c['id'];
                    $cuo_ult = $res_c['numero'];
                }   
            }
           
            GenerarCuotasId($db, $cuo_ult, $id_ult, $id_url);
            
            $errores['disponible'] = "LA CUOTA SE EMITIO CORRECTAMENETE - Redireccionando..."; 
        }           
    }else{
        $errores['disponible'] = "LA CUOTA YA FUE EMITIDA - Redireccionando..."; 
        header("refresh:3, url=/cuota/emitir/individual/".$id_url);
        require_once (BASE_PATH.'/includes/pagina.php'); 
        echo "<main id='principal' class='bloque-cont'>".
                $errores['disponible']. 
             "</main>"; 
         
    }
header('refresh:3, url=/home');
require_once (BASE_PATH.'/includes/pagina.php'); 
    echo "<main id='principal' class='bloque-cont'>".
            $errores['disponible']. 
         "</main>";
}

