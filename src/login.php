<?php
    //iniciar la session y la conexion a la base de datos 
    //session_start();
    require_once '../includes/conexion.php';
    // recoger los datos del formulario para comparar
    if (isset($_POST)){
         if (isset($_SESSION['error_login'])){
                $_SESSION['error_login'] = null;
         }
        $email = trim($_POST['email']);
        $password = $_POST['passw'];
        //hacer una consulta para comprobar las credenciales 
        $sql = "SELECT * FROM usuario WHERE email = '$email'"; 
        $login = mysqli_query($db, $sql); 
        if ($login && mysqli_num_rows($login) == 1){
            $usuario = mysqli_fetch_assoc($login);
             //comprobar la contraseña
            $verific = password_verify($password, $usuario['password']);
            if ($verific){
                // utilizar una session para guardar los datos del usuario logueado
                $_SESSION['usuario'] = $usuario; 
            }else{
                 //si algo falla enviar una sesion con el fallo
                $_SESSION['error_login'] = "Login incorrecto"; 
            }
        }else{
            $_SESSION['error_login'] = "Login incorrecto";   
        }
     
    }
    if (!isset($_SESSION['error_login'])){
        header('Location: principal.php');
    }else{
        header('Location: ../index.php');
    }
?>

