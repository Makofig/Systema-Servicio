<?php 
require_once __DIR__ . '/vendor/autoload.php'; // Ruta al autoload de Composer
require_once 'router/Router.php'; // Asegúrate de que la ruta es correcta

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router(); 
// Registrar las rutas 
$router->get('login', '/src/login.php');
$router->get('formulario', '/src/formulario.php'); 
$router->get('logout', '/src/cerrar.php'); 
$router->get('home', '/src/principal.php'); 

$router->post('authenticate', '/src/authenticate.php');
$router->post('registro', '/src/registro.php'); 
// Obtener la URL Limp ia 
// $url = $_GET['url'] ?? 'login';
define('BASE_PATH', __DIR__);

if($_SERVER['REQUEST_URI'] === '/index.php'){
    $url = 'login';
}else{
    $url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
}
if ($url === '') {
    $url = 'login';
}
// Despachar la URL 
// echo $_SERVER['REQUEST_URI']; 
$router->dispatch($url);
?>
