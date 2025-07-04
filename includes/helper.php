<?php
// RECUPERAR UN CLIENTE, CON EL ID; 
function clientId($db, $id){
    $consult = $db->prepare("SELECT nombre, apellido FROM cliente "
            . "WHERE id = ? ;");
    $consult->bind_param("s", $id);
    $consult->execute();
    $result = $consult->get_result();
    return $result;
    
}
// LISTAR LOS ACCES POINT; 
function listarAccesId($db, $id){
    $consulta = $db->prepare("SELECT * FROM cliente WHERE id_plan = ? ORDER BY (apellido) ASC;");
    $consulta->bind_param("s", $id);
    $consulta->execute();
    $result = $consulta->get_result();
    return $result;
}
// RECUPERA LOS DATOS DEL PLAN SEGUN ID; 
function recupPlanId($db, $id){
    $consulta = $db->prepare("SELECT * FROM plan WHERE id = ?;");
    $consulta->bind_param("s", $id);
    $consulta->execute();
    $result = $consulta->get_result();
    return $result;
}
// LISTAR LOS CLINTES PARA LA PAGINACION DE ELEMENTOS; 
function listClientPaginacion($db, $init, $limit){
    $consulta = $db->prepare("SELECT c.*, p.nombre AS nom_cli FROM cliente c "
            . "INNER JOIN plan p ON c.id_plan = p.id ORDER BY (c.apellido) ASC "
            . "LIMIT ?, ?;");
    $consulta->bind_param("ss", $init, $limit);
    $consulta->execute();
    $result = $consulta->get_result();
    return $result;
}
// LISTAR LOS DEUDORES DE MES ACTUAL;
function listDeudoresMon($db, $mon){
    $consult = $db->prepare("SELECT COUNT(c.id) AS total FROM cliente c "
            . "INNER JOIN pagos p ON p.id_cliente = c.id "
            . "WHERE (p.estado = 0 and p.num_cuotas = ?);");
    $consult->bind_param("s", $mon);
    $consult->execute();
    $result = $consult->get_result();
    $cont = mysqli_fetch_assoc($result);
    return implode($cont);
}
// LISTAR LOS CLIENTES ADEUDADOS LIMITANDO PARA LA PAGINACIÓN;
function listDeudoresPaginar($db, $mon, $init, $limit){
    $consulta = $db->prepare("SELECT c.nombre, c.apellido, c.id AS id_cl, p.* FROM pagos p "
            . "INNER JOIN cliente c ON c.id = p.id_cliente "
            . "WHERE (p.num_cuotas = ? and p.estado = 0) ORDER BY(c.apellido) ASC "
            . "LIMIT ?, ?;");
    $consulta->bind_param("sss", $mon, $init, $limit);
    $consulta->execute();
    $result = $consulta->get_result();
    return $result;
}
// TOTAL DE CLIENTES CON DEUDA 
function totalClientAdeudo($db){
    $consult = $db->prepare("SELECT COUNT(c.id) FROM cliente c "
            . "INNER JOIN pagos p ON p.id_cliente = c.id "
            . "WHERE p.estado = 0 ;");
    $consult->execute();
    $result = $consult->get_result();
    $cont = mysqli_fetch_assoc($result);
    return implode($cont);
}
function listClientPaginarDeudores($db, $init, $limit){ 
    $consulta = $db->prepare("SELECT c.*, p.*, c.id AS id_cli FROM cliente c "
            . "INNER JOIN pagos p ON p.id_cliente = c.id WHERE p.estado = '0' ORDER BY (c.apellido) ASC "
            . "LIMIT ?, ? ;");
    $consulta->bind_param("ss", $init, $limit);
    $consulta->execute();
    $result = $consulta->get_result();
    return $result;
}
// FUNCION PARA MOSTRAR ERRORES DE LOS CAMPOS;
function mostrarErrores($errores, $campo){
    $alerta = ''; 
    if (isset($errores [$campo])&& !empty($campo)){
        $alerta = "<div class='alerta alerta-error'>".$errores[$campo].'</div>'; 
    } 
    return $alerta; 
}
// BORRAR ERRORES DE SESION;
function borrarErrores(){
    if (isset($_SESSION['errores'])) {
        unset($_SESSION['errores']);
    }
    if (isset($_SESSION['completo'])) {
        unset($_SESSION['completo']);
    }
    if (isset($_SESSION['errores_entradas'])) {
        unset($_SESSION['errores_entradas']);
    }
    #$_SESSION['errores'] = null;
    #$_SESSION['completo'] = null;
    #$_SESSION['errores_entradas'] = null;  
    return true;
}

function BorrarSesiones (){
    if (isset($_SESSION)){
        session_destroy();
        session_cache_expire();
    }
}
// TOTAL DE LA RECAUDACIÓN EN COSTOS DE LOS PLANES; 
function Total($db){
    $sql = "SELECT SUM(p.costo) as total 
            FROM cliente c 
            INNER JOIN plan p ON c.id_plan = p.id";

    $result = $db->query($sql);
    $row = $result ? $result->fetch_assoc() : ['total' => 0];
    return (float) $row['total'];
}
// TOTAL DE LOS COSTOS, CUYOS CLIENTES HALLAN PAGADO Y SEAN DEL MES ACTUAL;
function Recaudado($db){
    $mesActual = date('n');
    $sql = "SELECT SUM(p.abonado) as total 
            FROM pagos p 
            INNER JOIN cuotas c ON c.id = p.id_cuota 
            WHERE p.num_cuotas = ?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $mesActual);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : ['total' => 0];
    return (float) $row['total'];
}
// TOTAL DE LOS COSTOS DE LOS PLANES, CUYOS CLIENTES NO HALLAN PAGADO; 
function deuda($db){
    $total = Total($db);
    $abonado = Recaudado($db);
    return max(0, $total - $abonado); // evita valores negativos
}
// TOTAL DE CLIENTES EN EL SISTEMA;
function ClienteTotal($db){
    $sql = "SELECT COUNT(id) FROM cliente; ";
    $consulta = mysqli_query($db, $sql);
    $cont = mysqli_fetch_assoc($consulta);
    return implode($cont);
}
// TOTAL DE CLIENTES CON PLAN DE 3MB; 
function ClientePremiun($db){
    $id = 1; 
    $sql = "SELECT COUNT(c.id) FROM cliente c ".
           "INNER JOIN plan p ON p.id = c.id_plan ".
           "WHERE c.id_plan = $id ";
    $consulta = mysqli_query($db, $sql);
    $cont = mysqli_fetch_assoc($consulta);
    return implode($cont);
}
// TOTAL DE CLIENTES CON PLAN DE 5MB; 
function ClienteVip($db){
    $id = 2; 
    $sql = "SELECT COUNT(c.id) FROM cliente c ".
           "INNER JOIN plan p ON p.id = c.id_plan ".
           "WHERE c.id_plan = $id ";
    $consulta = mysqli_query($db, $sql);
    $cont = mysqli_fetch_assoc($consulta);
    return implode($cont);
}
// TOTAL DE AP REGISTRADOS EN EL SISTEMA; 
function ApTotal($db){
    $sql = "SELECT COUNT(id) FROM accespoint; ";
    $consulta = mysqli_query($db, $sql);
    $contap = mysqli_fetch_assoc($consulta);
    return implode($contap);
}
// TOTAL DE CLIENTES POR AP; 
function TotalClienteAP($db, $id_url){
    $total = 0;
    // Validación básica del parámetro
    if (!is_numeric($id_url)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) as total FROM cliente WHERE id_point = ?"; 
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id_url);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $total = (int) $row['total'];
        }

        $stmt->close();
    }
   
    return $total;
}
// TOTAL CLIENTE POR PLAN 
function totalClientesPlan($db, $id_plan) {
    $total = 0;

    // Validación básica del parámetro
    if (!is_numeric($id_plan)) {
        return 0;
    }

    // Consulta segura con prepared statement
    $sql = "SELECT COUNT(*) as total FROM cliente WHERE id_plan = ?";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id_plan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $total = (int) $row['total'];
        }

        $stmt->close();
    }

    return $total;
}
// CONTROLA QUE LA CUOTA NO ESTE EMITIDA; 
function ControlCuota($db, $mes_url, $year_url){
    $mes = $mes_url;
    $año = $year_url; 
    if ($mes < 9){
        $codigo = $año .+'0'.+$mes;
    }else{
        $codigo = $año .+ $mes;
    }
    $search = array('-');
    $replace = array(); 
    $sql = "SELECT * FROM cuotas; ";
    $consulta = mysqli_query($db, $sql);
    $bandera = FALSE;
    while ($cont = mysqli_fetch_assoc($consulta)){
        $source = str_replace($search, $replace, $cont['fecha_emision']);
        $dest = substr($source, 0, 6);
        if ($codigo == $dest){
            $bandera = TRUE; 
        }
    }
    return $bandera;  
}
// CONTROLA QUE LA CUOTA NO ESTE EMITIDA POR ID DE CLIENTE;
function ControlCuotaId($db, $mes_url, $year_url, $id){
    $mes = $mes_url;
    $año = $year_url; 
    if ($mes < 9){
        $codigo = $año .+'0'.+$mes;
    }else{
        $codigo = $año .+ $mes;
    }
    $search = array('-');
    $replace = array(); 
    $sql = "SELECT * FROM pagos WHERE id_cliente = $id; ";
    $consulta = mysqli_query($db, $sql);
    $bandera = FALSE;
    while ($cont = mysqli_fetch_assoc($consulta)){
        $source = str_replace($search, $replace, $cont['fecha_emision']);
        $dest = substr($source, 0, 6);
        if ($codigo == $dest){
            $bandera = TRUE; 
        }
    }
    return $bandera;  
}
// GENERA LAS CUOTAS PARA TODOS LOS CLIENTES;
function GenerarCuotas($db, $numCuota, $id_cuot){
    $id_cuota = $id_cuot; 
    $sqlcl = "SELECT c.*, p.costo AS cos_pl FROM cliente c ".
            "INNER JOIN plan p ON p.id = c.id_plan; ";
    $consultcl = mysqli_query($db, $sqlcl);
    while ($resultcl = mysqli_fetch_assoc($consultcl)){
        $id_cl = $resultcl['id'];
        $cos_pl = $resultcl['cos_pl'];
        $sqlpa = "INSERT INTO pagos VALUE(NULL, '$id_cl', '$id_cuota', '$numCuota', CURDATE(), '$cos_pl', '0', '0', CURDATE(), NULL, NULL, NULL);";
        $guardar = mysqli_query($db, $sqlpa);
    }  
    header ('Location: /home');
}
// GENERA LAS CUOTAS PARA UN CLIENTE EN PARTICULAR;
function GenerarCuotasId($db, $numCuota, $id_cuot, $id){
    $id_cuota = $id_cuot; 
    $sqlcl = "SELECT c.*, p.costo AS cos_pl FROM cliente c ".
            "INNER JOIN plan p ON p.id = c.id_plan ".
            "WHERE c.id = $id;";
    $consultcl = mysqli_query($db, $sqlcl);
    while ($resultcl = mysqli_fetch_assoc($consultcl)){
        $id_cl = $resultcl['id'];
        $cos_pl = $resultcl['cos_pl'];
        $sqlpa = "INSERT INTO pagos VALUE(NULL, '$id_cl', '$id_cuota', '$numCuota', CURDATE(), '$cos_pl', '0', '0', CURDATE(), NULL, NULL, NULL);";
        $guardar = mysqli_query($db, $sqlpa);
    }  
}
// CONTROLA SI UN CLIENTE TIENE DEUDA;
function clientConDeudas($db, $id){
    $consult = $db->prepare("SELECT estado FROM pagos WHERE id_cliente = ? and estado = 0;");
    $consult->bind_param("s", $id);
    $consult->execute();
    $result = $consult->get_result();
    $estado = mysqli_fetch_assoc($result);
    if (!($estado === null)){
        if ($estado['estado'] === 0 ){
            return true;
        }
    }
    return false;
}

// SUMA DE ABONADOS EN LA TABLA DE PAGOS; 
function sumaAbonados($db, $year){
    $conulta = $db->prepare("select sum(abonado) from pagos WHERE YEAR(fecha_pago) = ?;");
    $conulta->bind_param("i", $year);
    $conulta->execute();
    $result = $conulta->get_result();
    $totalAbonado = implode(mysqli_fetch_assoc($result));
    return $totalAbonado;
}

// SUMA GENERAL DE LOS COSTOS DE LA TABLA PAGOS; 
function sumaCostos($db, $year){
    $consult = $db->prepare("select sum(costo) from pagos WHERE YEAR(fecha_pago) = ?;");
    $consult->bind_param("i", $year);
    $consult->execute();
    $result = $consult->get_result();
    $totalCompleto = implode(mysqli_fetch_assoc($result)); 
    return $totalCompleto;
}

// RECUPERANDO LOS DATOS PARA LA GRAFICA DE LOS PAGOS, DISCRIMINANDO POR RANGO DE DIAS; 
function tablaClientCuotas($db, $mes, $year){
    $reporte = [];

    for ($i = 1; $i <= $mes; $i++) {
        $pagoTemprano = 0; 
        $pagoIntermedio = 0; 
        $pagoTardio = 0;
        $noPagado = 0;

        $consulta = $db->prepare("
            SELECT estado, fecha_pago 
            FROM pagos 
            WHERE num_cuotas = ? AND YEAR(fecha_pago) = ?
        ");
        $consulta->bind_param("ii", $i, $year); 
        $consulta->execute();
        $result = $consulta->get_result(); 

        while ($pago = $result->fetch_assoc()) {
            if ($pago['estado'] == 1 && !empty($pago['fecha_pago'])) {
                $fecha = DateTime::createFromFormat('Y-m-d', $pago['fecha_pago']);
                $dia = (int)$fecha->format('d');

                if ($dia <= 10) {
                    $pagoTemprano++;
                } elseif ($dia <= 20) {
                    $pagoIntermedio++;
                } else {
                    $pagoTardio++;
                }
            } else {
                $noPagado++;
            }
        }

        $reporte[$i] = [
            'pago_temprano' => $pagoTemprano,
            'pago_intermedio' => $pagoIntermedio,
            'pago_tardio' => $pagoTardio,
            'deuda' => $noPagado,
        ];
    }
    return $reporte;
}

// RECUPERANDO DATOS PARA LA TABLA DE LOS COSTOS Y GRAFICA LINEAL; 
function tablaClientCuotasCosto($db, $mes, $year){
    $reporte = [];

    for ($i = 1; $i <= $mes; $i++) {
        $costoSuccess = 0;
        $costoWarning = 0;
        $costoDanger = 0;
        $costoFail = 0;

        // Consulta filtrando por mes y año
        $consulta = $db->prepare("
            SELECT estado, fecha_pago, abonado, costo 
            FROM pagos 
            WHERE num_cuotas = ? AND YEAR(fecha_pago) = ?
        ");
        $consulta->bind_param("ii", $i, $year);
        $consulta->execute();
        $result = $consulta->get_result();

        while ($pago = $result->fetch_assoc()) {
            // Validar si hay pago realizado
            if ($pago['estado'] == 1) {
                // Obtener día de la fecha de pago
                $fecha = date_create($pago['fecha_pago']);
                $dia = (int)date_format($fecha, "d");
                
                // Clasificación por fecha de pago
                if ($dia <= 10) {
                    $costoSuccess += $pago['abonado'];
                } elseif ($dia > 10 && $dia <= 20) {
                    $costoWarning += $pago['abonado'];
                } else {
                    $costoDanger += $pago['abonado'];
                }
            } else {
                // Si no pagó, se considera deuda
                $costoFail += $pago['costo'];
            }
        }
        
        // Guardar el reporte del mes
        $reporte[$i] = [
            'pago_temprano' => $costoSuccess,
            'pago_intermedio' => $costoWarning,
            'pago_tardio' => $costoDanger,
            'deuda' => $costoFail
        ];
    }   
 
    return $reporte;
}

// RECUPERANDO DATOS PARA LA TABLA DE LOS COSTOS Y GRAFICA LINEAL POR ID DE CLIENTE; 
function tablaClientCuotasId($db, $id){
    $search = array('-');
    $replace = array();

        $sucess = 0; 
        
        // CONSULTA
        $consulta = $db->prepare("select * from pagos WHERE id_cliente = ? ORDER BY(num_cuotas);");
        $consulta->bind_param("s", $id); 
        $consulta->execute();
        $result = $consulta->get_result(); 
        while ($pagos = mysqli_fetch_assoc($result)){ 

            // COMPOROBAR LOS RANGOS DE FECHAS
            // 1. EXTRAER EL DIA DE LA FICHA PARA COMPARAR (DIA <= 10; 10 > DIA <= 20 ; DIA > 20 );
            $source = str_replace($search, $replace, $pagos['fecha_pago']);
            $dest = substr($source, 6, 8);
            // 2. COMPROBAR EL ESTADO DEL PAGO Y SI PAGO CONTROLO EL RANGO DE FEHA, SINO ESTA FUERA DE FECHA;
            if ($pagos['estado'] == 1 ){
                switch ($dest){
                    case $dest <= 10: $sucess = $dest;
                        break;
                    case ($dest > 10 and $dest <= 20): $sucess = $dest;
                        break;
                    default : $sucess = $dest;
                }
            }else{
                $sucess = 0;  
            }
            $estado[] = $sucess;  
        }
    return $estado;
}

// PERMITE PASAR EL MES EN FORMATO NUMERO A STRING; 
function convertMes($mes){
    for ($m = 0; $m < $mes; $m++ ){
        switch ($m +1){
            case 1 : $estado = "Enero";
                break;
            case 2 : $estado = "Febrero"; 
                break;
            case 3 : $estado = "Marzo";
                break;
            case 4 : $estado = "Abril"; 
                break;
            case 5 : $estado = "Mayo"; 
                break;
            case 6 : $estado = "Junio"; 
                break;
            case 7 : $estado = "Julio"; 
                break;
            case 8 : $estado = "Agosto"; 
                break;
            case 9 : $estado = "Septiembre"; 
                break;
            case 10 : $estado = "Octubre"; 
                break;
            case 11 : $estado = "Noviembre"; 
                break;
            case 12 : $estado = "Diciembre"; 
                break;
        }
        $arrayMes[$m] = $estado;   
    }
    return $arrayMes;
}