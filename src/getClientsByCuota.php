<?php
// Aquí debes incluir la conexión a la base de datos y cualquier otra configuración necesaria
require_once '../includes/conexion.php';
require_once '../includes/helper.php';
// Verificar si se recibió el parámetro 'cuota' en la solicitud
if(isset($_GET['cuota'])) {
    // Obtener el valor de 'cuota' desde la solicitud
    $cuota = $_GET['cuota'];

    // Aquí deberías realizar la consulta a la base de datos para obtener los clientes de la cuota seleccionada
    // Por ejemplo, asumiendo que la tabla de clientes se llama 'clientes', y el campo que almacena la cuota se llama 'num_cuotas'
    // Debes reemplazar esto con tu consulta real
    $consulta = $db->prepare("SELECT c.*, p.* FROM cliente c "
            . "INNER JOIN pagos p ON p.id_cliente = c.id WHERE p.num_cuotas = ? and p.estado = '0';");
    $consulta->bind_param("s", $cuota);
    $consulta->execute();
    $resultado = $consulta->get_result();

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
        $html .= '<td class="primary"><a href="/edit/editPago.php?id='.$cliente['id'].'">Editar</a></td>';
        $html .= '</tr>';
    }

    // Devolver el HTML generado
    echo $html;
} else {
    // Si no se recibió el parámetro 'cuota', devolver un mensaje de error
    echo 'Error: No se recibió la cuota seleccionada.';
}
?>