<?php 
include '../../includes/conexion.php';

$id = $_GET['editar']; 
$costo = $_POST['costo'];
$fecha = $_POST['fecha']; 
$coment = $_POST['coment']; 
$image = $_FILES['image'];
$image2 = $_FILES['image2']; 
$nameImage = $image['name'];
$nameImage2 = $image2['name']; 
$typeImage = $image['type'];
$abonado = $_POST['entrega']; 

// CONSULTA PARA ACCEDER A LAS IMAGENES GUARDADAS 
$consultaPla = "SELECT image, image2, comentario FROM pagos WHERE id = $id";
$result_plan = mysqli_query($db, $consultaPla);
$cuota = mysqli_fetch_assoc($result_plan);

if ($cuota['image'] != null){
    $nameImage = $cuota['image'];
}
if ($cuota['image2'] != null){
    $nameImage2 = $cuota['image2'];
}   
if ($cuota['comentario'] != null){
    $comentCompleto = $cuota['comentario'] .PHP_EOL. $coment; 
}else{
    $comentCompleto = $coment;
}

/*actualizarPago($db, $id, $costo, $fecha);*/
// PARA ACTUALIZAR; 
try {
    // CREO LA CARPETA PARA GUARDAR LAS IMAGENES; 
    if(!is_dir('../../images')){
	mkdir('../../images', 0777);
    }
 
    ($abonado >= $costo ) ? $estado = '1' : $estado = '0';
    
    move_uploaded_file($image['tmp_name'], '../../images/'.$nameImage);
    move_uploaded_file($image2['tmp_name'], '../../images/'.$nameImage2);
    // ACTUALIZAR LA CUOTA; 
    $errores['disponible'] = "SE ACTUALIZO CORRECTAMENTE - Redireccionando...";
    $consult = $db->prepare("UPDATE pagos SET abonado = ?, fecha_pago = ?, comentario = ?, image = ?, image2 = ? , estado = ?  WHERE id = ?;");
    $consult->bind_param("sssssss", $abonado, $fecha, $comentCompleto, $nameImage, $nameImage2, $estado, $id); 
    $consult->execute();
    
    if ($consult->error){
        throw new Exception("Error al actualizar la cuota: ". $consult->error);  
    }
        
    header('refresh:3, url= /list/listarDeudoresCompleto.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
    echo "<main id='principal' class='bloque-cont'>".
            $errores['disponible']. 
         "</main>";
    
} catch (Exception $ex) {
    header('refresh:3, url= guardarPago.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
    echo "<main id='principal' class='bloque-cont'>".
            $e->getMessage(). 
         "</main>";
}






