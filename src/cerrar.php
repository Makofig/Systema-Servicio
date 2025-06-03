<?php

session_start();
if (isset($_SESSION['usuario'])){
    session_destroy(); 
}
if (session_status() == PHP_SESSION_ACTIVE){
    session_destroy(); 
}
if (isset($_COOKIE['nombre'])){
    $nomb = $_COOKIE['nombre'];
    setcookie($nomb, 0, time()-60);
}
/*$directorio = $_SERVER['DOCUMENT_ROOT'].'/Master-php/InterSys-2_1.0/index.php';*/

header("Location: ../index.php");
?>