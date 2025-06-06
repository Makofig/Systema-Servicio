<?php 
// class Router para el manejo de las rutas 
class Router {
    private $getRoutes = [];
    private $postRoutes = []; 

    public function get($path, $file) {
        $this->getRoutes[$path] = $file; 
    }

    public function post($path, $file) {
        $this->postRoutes[$path] = $file; 
    }

    public function dispatch($url){
        $path = trim($url, '/'); // Eliminar barras al inicio y al final
        // Rutas para capturar los parametros de la URL Cliete 
        if (preg_match('#^cliente/listar/([0-9]+)$#', $url, $matches)) {
            $_GET['pagina'] = $matches[1];
            require __DIR__ . '/../src/list/listarClient.php';
            return;
        }
        if (preg_match('#^cliente/pagado/([0-9]+)$#', $url, $matches)) {
            $_GET['pagina'] = $matches[1];
            require __DIR__ . '/../src/list/listarPagado.php';
            return;
        }
        if (preg_match('#^cliente/pagos/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/list/listarCuotasIndividual.php';
            return;
        }
        if (preg_match('#^cliente/pagos/editar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/edit/editarPago.php';
            return;
        }
        if (preg_match('#^cliente/pagos/guardar/([0-9]+)$#', $url, $matches)) {
            $_GET['editar'] = $matches[1];
            require __DIR__ . '/../src/save/guardarPago.php';
            return;
        }
        if (preg_match('#^cliente/contenido/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/content/contenido.php';
            return;
        }
        if (preg_match('#^cliente/deudores/([0-9]+)$#', $url, $matches)) {
            $_GET['pagina'] = $matches[1];
            require __DIR__ . '/../src/list/listarDeudores.php';
            return;
        }
        if (preg_match('#^cliente/editar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/edit/editarCliente.php';
            return;
        }
        if (preg_match('#^cliente/guardar/([0-9]+)$#', $url, $matches)) {
            $_GET['editar'] = $matches[1];
            require __DIR__ . '/../src/save/guardarCliente.php';
            return;
        }
        if (preg_match('#^cliente/eliminar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/delet/eliminarCliente.php';
            return;
        }
        // rutas para capturar los parametros de la URL Plan 
        if (preg_match('#^plan/listar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/list/listarPlan.php';
            return;
        }
        if (preg_match('#^plan/guardar/([0-9]+)$#', $url, $matches)) {
            $_GET['editar'] = $matches[1];
            require __DIR__ . '/../src/save/guardarPlan.php';
            return;
        }
        // Rutas para capturar los parametros de la URL Access Point 
        if (preg_match('#^point/contenido/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/content/contenidoPoint.php';
            return;
        }
        if (preg_match('#^point/editar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/edit/editarPoint.php';
            return;
        }
        if (preg_match('#^point/guardar/([0-9]+)$#', $url, $matches)) {
            $_GET['editar'] = $matches[1];
            require __DIR__ . '/../src/save/guardarPoint.php';
            return;
        }
        if (preg_match('#^point/eliminar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/delet/eliminarPoint.php';
            return;
        }
        // Rutas para capturar los parametros de la URL Cuota
        if (preg_match('#^cuota/emitir/individual/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/create/emitirCuotaIndividual.php';
            return;
        }
        if (preg_match('#^cuota/ver/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/list/verCuota.php';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($this->postRoutes[$path])){
            require __DIR__.'/../'.$this->postRoutes[$path];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($this->getRoutes[$path])){ 
            require __DIR__.'/../'.$this->getRoutes[$path];
        }else {
            http_response_code(404);
            echo "<h2>404 PAGE NOT FOUND</h2>"; 
        }
    }
}
?>