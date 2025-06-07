<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
   
    require_once (BASE_PATH.'/includes/conexion.php');
   
    $db = getDBConnection(); 

    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    //Array de Errores 
    $errores = []; // $errores = []; 
     
    //Validar los datos 
    if(!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)){
        $nombre_valido = true;
    } else { 
        $errores['nombre'] = "El nombre no es válido";
    }
    if(!empty($apellido) && !is_numeric($apellido) && !preg_match("/[0-9]/", $apellido)){
        $apellido_valido = true;
    } else {
        $errores['apellido'] = "El Apellido no es válido";
    }
    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_valido = true;
    } else { 
        $errores['email'] = "El email no es válido";
    }
 
    if (empty($errores)){
        $usuarioActual  = $_SESSION['usuario'];

        // Verificar si ya existe otro usuario con el mismo email
        $sql = "SELECT id FROM usuario WHERE email = ? AND id != ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $email, $usuarioActual['id']);
        $stmt->execute();
        $result = $stmt->get_result();
       
        if($result->num_rows === 0){
            
            // Actualizar datos
            $sqlAct = "UPDATE usuario SET nombre = ?, apellido = ?, email = ? WHERE id = ?";
            $updateStmt = $db->prepare($sqlAct);
            $updateStmt->bind_param("sssi", $nombre, $apellido, $email, $usuarioActual['id']);

            if ($updateStmt->execute()) {
                // Actualizar sesión
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['apellido'] = $apellido;
                $_SESSION['usuario']['email'] = $email;
                $_SESSION['completo'] = '✅ Tus datos se han actualizado correctamente.';
            } else {
                $_SESSION['errores']['registro'] = '❌ Fallo al actualizar los datos. Intenta nuevamente.';
            }
        }else{
            $_SESSION['errores']['registro'] = '⚠️ Ya existe un usuario con ese email.';
        }
        
    }else{
        $_SESSION['errores'] = $errores;
    }
}
//Redireccion a la pagina principal 
header('Location: /usuario/datos/editar');
exit();   
?>