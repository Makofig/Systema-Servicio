<?php
if (isset($_POST)){
    
    require_once BASE_PATH.'/includes/conexion.php';
    $db = getDBConnection(); 

    $nuevo_ssid = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $nuevo_frecu = isset($_POST['frecuencia']) ? mysqli_real_escape_string($db, $_POST['frecuencia']) : false;
    $nuevo_address = isset($_POST['ip']) ? mysqli_real_escape_string($db, $_POST['ip']) : false;
    $nuevo_local = isset($_POST['local']) ? mysqli_real_escape_string($db, $_POST['local']) : false;
    $errores = array();
    if (!empty($nuevo_ssid && !is_numeric($nuevo_ssid))){
        $nombre_valido = true;
    }else{
        $nombre_valido = false; 
        $errores['nombre'] = "El SSID no es valido";
    }
    if (!empty($nuevo_frecu && !is_numeric($nuevo_frecu))){
        $nombre_valido = true;
    }else{
        $nombre_valido = false; 
        $errores['frecuencia'] = "La frecuencia no es valido";
    }
    if (!empty($nuevo_address && !is_numeric($nuevo_address))){
        $nombre_valido = true;
    }else{
        $nombre_valido = false; 
        $errores['ip'] = "El address no es valido (xxx.xxx.xxx.xxx)";
    }
    if (!empty($nuevo_local && !is_numeric($nuevo_local))){
        $nombre_valido = true;
    }else{
        $nombre_valido = false; 
        $errores['local'] = "La localidad no es valido";
    }
    if (count($errores)==0){
        if (isset($_GET['editar'])){
            $id_y = $_GET['editar'];  
            $sql = "UPDATE accespoint SET ssid = '$nuevo_ssid', frecuencia= '$nuevo_frecu', ip_ap = '$nuevo_address', localidad = '$nuevo_local' WHERE id = $id_y;"; 
        }else{
            $sql = "INSERT INTO accespoint VALUE(NULL, '$nuevo_ssid', '$nuevo_frecu', '$nuevo_address', '0', '$nuevo_local');";
        }
        $guardar = mysqli_query($db, $sql);
        if (isset($_GET['editar'])){
            $errores['disponible'] = "SE ACTUALIZO CORRECTAMENTE - Redireccionando...";
        }else{
            $errores['disponible'] = "SE CARGO CORRECTAMENTE - Redireccionando...";
        }
        header('refresh:3, url=/home');
        require_once (BASE_PATH.'/includes/pagina.php'); 
        echo "<main id='principal' class='bloque-cont'>".
                    $errores['disponible']. 
                 "</main>";       
    }else{
        if (isset($_GET['editar'])){
            header("refresh:3, url=../edit/editarPoint.php?id=".$_GET['editar']);
        }else{
            header('refresh:3, url=/point/crear');
        }
        $_SESSION['errores_entradas'] = $errores;
        require_once (BASE_PATH.'/includes/pagina.php'); 
        echo "<main id='principal' class='bloque-cont'>".
                $_SESSION['errores_entradas']. 
             "</main>";  
    }
    borrarErrores();
}
