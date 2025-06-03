<?php
if(isset($_POST)){
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/conexion.php');
   
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $apellidos = isset($_POST['apellido']) ? mysqli_real_escape_string($db, $_POST['apellido']) : false;
    $email = isset($_POST['email']) ? mysqli_real_escape_string($db, trim($_POST['email'])) : false;
  
    //Array de Errores 
    $errores = array();
     
    //Validar los datos 
    if(!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)){
        $nombre_valido = true;
    } else {
        $nombre_valido = false; 
        $errores['nombre'] = "El nombre no es válido";
    }
    if(!empty($apellidos) && !is_numeric($apellidos) && !preg_match("/[0-9]/", $nombre)){
        $apellido_valido = true;
    } else {
        $apellido_valido = false; 
        $errores['apellido'] = "El Apellido no es válido";
    }
    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_valido = true;
    } else {
        $email_valido = false; 
        $errores['email'] = "El email no es válido";
    }
    
    $guardar = false; 
    if (count($errores)== 0){
        $guardar = true; 
        $usu = $_SESSION['usuario'];
        //Comprobar si el email no existe 
        $sql = "SELECT email FROM usuarios WHERE email = '$email'";
        $isset_email = mysqli_query($db, $sql);
        $isset_user = mysqli_fetch_assoc($isset_email); 
        if($isset_user['id']== $usu['id'] || empty($isset_user)){
            //Actualizamos los datos 
            
            $sql = ("UPDATE usuarios SET".
                    "nombre = '$nombre', ".
                    "apellido = '$apellidos',".
                    "email = '$email'".
                    "WHERE id = ".$usu['id']);
            $guardar_usu = mysqli_query($db, $sql);
            if ($guardar_usu){
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['apellido'] =$apellidos;
                $_SESSION['usuario']['email'] = $email;        
                $_SESSION['completo'] = 'Tus datos se han actualizado correctamente';
            }else {
                $_SESSION['errores']['registro'] = 'Fallo al actualizar los datos'; 
            }
        }else{
            $_SESSION['errores']['registro'] = 'El usuario ya existe';
        }
        
    }else{
        $_SESSION['errores'] = $errores;
        
    }
}
//Redireccion a la pagina principal 
header('Location: /edit/editDatos.php');    
?>