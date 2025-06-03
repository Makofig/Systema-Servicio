<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/Master-php/InterSys-2_1.0/includes/conexion.php');
$errores = array();
if (isset($_SESSION['usuario']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM accespoint WHERE id = $id; ";
    mysqli_query($db, $sql);
}
$errores['disponible']="SE ELIMINO CORRECTAMENTE - Redireccionando...";
require_once ($_SERVER['DOCUMENT_ROOT'].'/Master-php/InterSys-2_1.0/includes/pagina.php');
echo "<div id='principal' class='bloque-cont'>".
                $errores['disponible']. 
             "</div>";
borrarErrores();
header ("refresh:3, url=../principal.php");
