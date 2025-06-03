<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/conexion.php');
$errores=array();
if (isset($_SESSION['usuario']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM cliente WHERE id = $id; ";
    mysqli_query($db, $sql);
}
$errores['disponible']="SE ELIMINO CORRECTAMENTE - Redireccionando...";
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/pagina.php');
echo "<div id='principal' class='bloque-cont'>".
                $errores['disponible']. 
             "</div>";
borrarErrores();
header ("refresh:5, url=../principal.php");