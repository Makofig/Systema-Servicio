<?php
require_once (BASE_PATH.'/includes/conexion.php');
$errores = array();

$db = getDBConnection();

if (isset($_SESSION['usuario']) && isset($_GET['id'])){
    $id = intval($_GET['id']);

    // Verificar si el cliente existe
    $check = $db->prepare("SELECT id FROM accespoint WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows === 1) {
        // Cliente existe, proceder a eliminar
        $stmt = $db->prepare("DELETE FROM accespoint WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();

        if ($success) {
            $errores['disponible'] = "✅ Access Point eliminado correctamente. Redireccionando...";
        } else {
            $errores['disponible'] = "❌ Error al intentar eliminar un Access Point. Intente nuevamente.";
        }
    } else {
        $errores['disponible'] = "⚠️ El Access Point con ID $id no existe.";
    }   
}else {
    $errores['disponible'] = "⚠️ No tiene permisos para realizar esta acción o falta el parámetro.";
}

header ("refresh:3, url=/home");
require_once (BASE_PATH.'/includes/pagina.php');
echo "<main id='principal' class='bloque-cont'>".
        $errores['disponible']. 
    "</main>";
borrarErrores();

exit();
?>