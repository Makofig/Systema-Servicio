<?php
    //iniciar la session y la conexion a la base de datos 
    session_start();
    require_once BASE_PATH.'/includes/conexion.php'; 
    // recoger los datos del formulario para comparar
    //global $db;
    $db = getDBConnection(); 
   
    if (isset($_POST)){
        if (isset($_SESSION['error_login'])){
            unset($_SESSION['error_login']);
        }

        $email = trim($_POST['email']);
        $password = $_POST['passw'];
         
        //hacer una consulta para comprobar las credenciales 
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $db->prepare($sql); 
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
   
        if ($result && $result->num_rows === 1){
            $usuario = $result->fetch_assoc();
            //comprobar la contraseña
            $verific = password_verify($password, $usuario['password']);
            if ($verific){
                // utilizar una session para guardar los datos del usuario logueado
                $_SESSION['usuario'] = $usuario; 
                header('Location: /home');
                exit();
            }
        }
     
    }
    
    $_SESSION['error_login'] = "Email o contraseña incorrectos"; 
    header('Location: /login');
    exit();
?>

