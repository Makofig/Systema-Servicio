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