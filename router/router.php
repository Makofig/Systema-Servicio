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

        if (preg_match('#^cliente/listar/([0-9]+)$#', $url, $matches)) {
            $_GET['pagina'] = $matches[1];
            require __DIR__ . '/../src/list/listarClient.php';
            return;
        }
        if (preg_match('#^cliente/listar/pagado/([0-9]+)$#', $url, $matches)) {
            $_GET['pagina'] = $matches[1];
            require __DIR__ . '/../src/list/listarClient.php';
            return;
        }
        if (preg_match('#^cliente/deudores/([0-9]+)$#', $url, $matches)) {
            $_GET['pagina'] = $matches[1];
            require __DIR__ . '/../src/list/listarDeudores.php';
            return;
        }
        if (preg_match('#^plan/listar/([0-9]+)$#', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require __DIR__ . '/../src/list/listarPlan.php';
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