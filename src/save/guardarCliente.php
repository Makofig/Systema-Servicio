<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/helper.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/conexion.php');
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // FORMATEO PARA LA CARGA A LA BASE DE DATOS; 
    $nuevo_nom = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $nuevo_ape = isset($_POST['apellido']) ? mysqli_real_escape_string($db, $_POST['apellido']) : false;
    $nuevo_tel = isset($_POST['telefono']) ? mysqli_real_escape_string($db, $_POST['telefono']) : false; 
    $nuevo_dir = isset($_POST['direccion']) ? mysqli_real_escape_string($db, $_POST['direccion']) : false;
    $nuevo_ip = isset($_POST['ip']) ? mysqli_real_escape_string($db, $_POST['ip']) : false;
    $nuevo_pla = isset($_POST['plan']) ? (int)$_POST['plan'] : false;
    $nuevo_ap = isset($_POST['ap']) ? (int)$_POST['ap'] : false;
    $nuevo_fech_init = isset($_POST['fecha_inicio']) ?  mysqli_real_escape_string($db, $_POST['fecha_inicio']) : false;
    // Validar el nombre
    if (empty($_POST["nombre"])) {
        $nombre_error = "Por favor, ingresa tu nombre.";
    } else {
        $nombre = test_input($_POST["nombre"]);
        // Verificar si el nombre contiene solo letras y espacios
        if (!preg_match("/^[a-zA-Z ]*$/",$nombre)) {
            $nombre_error = "Solo se permiten letras y espacios en blanco.";
        }
    }
    
    // Validar el apellido
    if (empty($_POST["apellido"])) {
        $apellido_error = "Por favor, ingresa tu apellido.";
    } else {
        $apellido = test_input($_POST["apellido"]);
        // Verificar si el apellido contiene solo letras y espacios
        if (!preg_match("/^[a-zA-Z ]*$/",$apellido)) {
            $apellido_error = "Solo se permiten letras y espacios en blanco.";
        }
    }

    // Validar la dirección IP
    $ip = test_input($_POST["ip"]);
    if (!empty($ip) && !filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip_error = "Por favor, ingresa una dirección IP válida.";
    }

    // Validar el número de teléfono
    $telefono = test_input($_POST["telefono"]);
    if (!empty($telefono) && !preg_match("/^[0-9]+$/",$telefono)) {
        $telefono_error = "Por favor, ingresa un número de teléfono válido.";
    }

    // Validar la dirección
    $direccion = test_input($_POST["direccion"]);

    // Si no hay errores, procesar los datos
    if (empty($nombre_error) && empty($apellido_error) && empty($ip_error) && empty($telefono_error)) {
        // Aquí puedes hacer lo que desees con los datos del formulario
        // Por ejemplo, guardarlos en una base de datos o enviarlos por correo electrónico
        // También podrías redirigir al usuario a otra página
        if (isset($_GET['editar'])){
            $edit_y = $_GET['editar'];
            $sql = "UPDATE cliente SET nombre='$nuevo_nom', apellido='$nuevo_ape', ip='$nuevo_ip', direccion='$nuevo_dir', telefono= $nuevo_tel, id_plan=$nuevo_pla, id_point = $nuevo_ap, fecha_alta = '$nuevo_fech_init' ".
                    " WHERE id = $edit_y  ; ";    
        }else{
            if ($_POST['operacion'] === "Siguiente"){
                //Abro el archivo para escribir
                $file = @fopen("cliente.txt", "a");        
                //Guardo el arreglo codificado a json
                fwrite($file, "$nuevo_pla,$nuevo_ap,$nuevo_nom,$nuevo_ape,$nuevo_dir,$nuevo_tel,$nuevo_ip".PHP_EOL);
                //Cierro el archivo
                fclose($file);
                //Redireccionando a la misma pagina 
                header('Location: cargar-cliente.php');
                exit();
            }else{
                if(file_exists("cliente.txt")){
                    $archivo = "cliente.txt";
                    $file = fopen($archivo, "r"); 
                    $consulta = "INSERT INTO cliente (id, id_plan, id_point, nombre, apellido, direccion, telefono, ip, fecha_alta) VALUE(NULL,?,?,?,?,?,?,?, CURDATE())";
                    $stmt = $db->prepare($consulta); 
                    $stmt->bind_param("sssssss", $plan, $ap, $nombre, $apellido, $direc, $tel, $ip);  
                    //Leer el archivo linea a linea 
                    while (($line = fgets($file))!== false){
                        $data = explode(",", $line);

                        $plan = trim($data[0]);
                        $ap = trim($data[1]);
                        $nombre = trim($data[2]);
                        $apellido = trim($data[3]);
                        $direc = trim($data[4]);
                        $tel = trim($data[5]);
                        $ip = trim($data[6]);
                        $stmt->execute(); 
                       
                    }//FIN DEL WHILe
                    fclose($file); 
                    unlink("cliente.txt");
                    $stmt->close();
                }else{
                    $sql = "INSERT INTO cliente VALUE(NULL, $nuevo_pla, $nuevo_ap, '$nuevo_nom', '$nuevo_ape', '$nuevo_dir', '$nuevo_tel', '$nuevo_ip', CURDATE());";
                    $guardar = mysqli_query($db, $sql);
                }//FIN IF SI EXISTE  
            }//FIN IF $_POST['operacion']         
        }//FIN IF $_GET['editar'] 
        if (isset($_GET['editar'])){
            $guardar = mysqli_query($db, $sql);
            $errores['disponible'] = "SE ACTUALIZO CORRECTAMENTE - Redireccionando...";
        }else{
            $errores['disponible'] = "SE CARGO CORRECTAMENTE - Redireccionando...";           
        }
        header("refresh:3, url=../principal.php");  
        require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
        echo "<main id='principal' class='bloque-cont'>".
                $errores['disponible']. 
             "</main>"; 
        exit();
    }else{      
        if (isset($_GET['editar'])){
            header("Location: /edit/editarCliente.php?id=".$_GET['editar']."&nombre_error=$nombre_error&apellido_error=$apellido_error&ip_error=$ip_error&telefono_error=$telefono_error");
        }else{
            header("Location: /create/crearCliente.php?nombre_error=$nombre_error&apellido_error=$apellido_error&ip_error=$ip_error&telefono_error=$telefono_error");
            exit();
        } 
    }
}

// Función para limpiar los datos de entrada
function test_input($data) {
    $data = trim($data); // Elimina espacion en blanco, del inicion y el final de la cadena; 
    $data = stripslashes($data); // Escapa las // y las comillas ""; 
    $data = htmlspecialchars($data);
    return $data;
}

