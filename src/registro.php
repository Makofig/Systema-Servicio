<?php
if(isset($_POST)){
    require_once BASE_PATH.'includes/conexion.php';
    $db = getDBConnection();
    if (!isset($_SESSION)){
        session_start();
    }
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $apellidos = isset($_POST['apellido']) ? mysqli_real_escape_string($db, $_POST['apellido']) : false;
    $email = isset($_POST['email']) ? mysqli_real_escape_string($db, trim($_POST['email'])) : false;
    $password = isset($_POST['passw']) ? mysqli_real_escape_string($db, $_POST['passw']) : false;
    
   
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
    if(!empty($password)){
        $password_valido = true;
    } else {
        $password_valido = false; 
        $errores['password'] = "La contraseña está vacía";
    }
    $guardar = false; 
    if (count($errores)== 0){
        $guardar = true; 
        //Cifrar la contraseña 
        $passw_segura = password_hash($password, PASSWORD_BCRYPT, ['cost'=>4]);
        //Para verificar la password en el logueo 
        password_verify($password, $passw_segura);
        //insertamos los datos 
        $sql = ("INSERT INTO usuario VALUES(null, '$nombre', '$apellidos',' ', '$email', '$passw_segura', CURDATE());");
        $guardar_usu = mysqli_query($db, $sql);
        if ($guardar_usu){
            $_SESSION['completo'] = 'El registro se a guardado correctamente';
        }else {
            $_SESSION['errores']['registro'] = 'Fallo al guardar el usuario'; 
        }
    }else{
        $_SESSION['errores'] = $errores;
        //Redireccion a la pagina principal 
         header('Location: /login');
    }
}
//Redireccion a la pagina principal 
header('Location: /login');
?>