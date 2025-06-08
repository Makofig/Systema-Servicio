<?php
// Aquí debes incluir la conexión a la base de datos y cualquier otra configuración necesaria
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

require_once (BASE_PATH.'/includes/conexion.php');
require_once (BASE_PATH.'/includes/helper.php');
// Verificar si se recibió el parámetro 'cuota' en la solicitud
if(isset($_GET['cuota'])) {
    $db = getDBConnection();
    // Obtener el valor de 'cuota' desde la solicitud
    $cuota = $_GET['cuota'] ?? null; 
    $year = $_GET['year'] ?? null; 

    // Aquí deberías realizar la consulta a la base de datos para obtener los clientes de la cuota seleccionada
    // Por ejemplo, asumiendo que la tabla de clientes se llama 'clientes', y el campo que almacena la cuota se llama 'num_cuotas'
    // Debes reemplazar esto con tu consulta real
    $consulta = $db->prepare("SELECT cl.*, p.*, co.*, p.id as id_pagos FROM cliente cl "
            . "INNER JOIN pagos p ON p.id_cliente = cl.id "
            . "INNER JOIN cuotas co ON p.id_cuota = co.id "
            . "WHERE p.num_cuotas = ? and YEAR(co.fecha_emision) = ? and p.estado = '0';");
    $consulta->bind_param("ss", $cuota, $year);
    $consulta->execute();
    $resultado = $consulta->get_result();
 
    $total = $resultado->num_rows; 
    
    $div = '<div style="color:red; font-size:25px">'.$total.'</div>';
    // Construir el HTML para mostrar los clientes
    $html = '';
    while ($cliente = mysqli_fetch_assoc($resultado)) {   
        
        $html .= '<tr>';
        $html .= '<td>'.$cliente['apellido'].'</td>';
        $html .= '<td>'.$cliente['nombre'].'</td>';
        $html .= '<td>'.$cliente['telefono'].'</td>';
        $html .= '<td>'.$cliente['num_cuotas'].'</td>';
        $html .= '<td>'.$cliente['fecha_emision'].'</td>';
        $html .= '<td>$'.number_format($cliente['costo'], 2).'</td>';
        $html .= '<td class="primary"><a href="/cliente/pagos/editar/'.$cliente['id_pagos'].'">Editar</a></td>';
        $html .= '</tr>';
    }

    // Devolver el HTML generado
    echo $div, $html;
} else {
    // Si no se recibió el parámetro 'cuota', devolver un mensaje de error
    echo 'Error: No se recibió la cuota seleccionada.';
}
?>
