<?php
if (isset($_POST)){
    
    require_once '../../includes/conexion.php';
    $nuevo_costo = isset($_POST['costo']) ? mysqli_real_escape_string($db, $_POST['costo']) : false;
    $nuevo_nom = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false; 
    $errores = array();
    if (!empty($nuevo_nom && !is_numeric($nuevo_nom))){
        $nombre_valido = true;
    }else{
        $nombre_valido = false; 
        $errores['nombre'] = "El nombre no es valido";
    }
    if (!empty($nuevo_costo && is_numeric($nuevo_costo))){
        $nombre_valido = true;
    }else{
        $nombre_valido = false; 
        $errores['costo'] = "El costo no es valido";
    }
    if (count($errores)==0){
        if (isset($_GET['editar'])){
            $id_y = $_GET['editar'];  
            $sql = "UPDATE plan SET nombre = '$nuevo_nom', costo= '$nuevo_costo' WHERE id = $id_y;"; 
        }else{
            $sql = "INSERT INTO plan VALUE(NULL, '$nuevo_nom', '$nuevo_costo');";
        }
        $guardar = mysqli_query($db, $sql);
        if (isset($_GET['editar'])){
            $errores['disponible'] = "SE ACTUALIZO CORRECTAMENTE - Redireccionando...";
        }else{
            $errores['disponible'] = "SE CARGO CORRECTAMENTE - Redireccionando...";
        }  
        header('refresh:3, url=/principal.php');
        require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
        echo "<main id='principal' class='bloque-cont'>".
                    $errores['disponible']. 
                 "</main>";
    }else{
        $_SESSION['errores_entradas'] = $errores;   
        if (isset($_GET['editar'])){
            header("refresh:3, url=/edit/editCuota.php?id=".$_GET['editar']);
        }else{
            header('refresh:3, url=/create/createPlan.php');
        }
        echo "<main id='principal' class='bloque-cont'>".
                $_SESSION['errores_entradas']. 
             "</main>";
        require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');    
    }
    borrarErrores();
}
