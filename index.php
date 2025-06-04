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

// Buscar 
$router->post('buscar', '/src/buscar.php');

// Cliente 
$router->get('cliente/crear', '/src/create/crearCliente.php');
$router->get('cliente/listar/{pagina}', '/src/list/listarClient.php');
$router->get('cliente/pagado/{pagina}', '/src/list/listarPagado.php');
$router->get('cliente/contenido/{id}', '/src/content/contenido.php'); 
$router->get('cliente/deudores/{pagina}', '/src/list/listarDeudores.php');
$router->get('cliente/deudores/completo', '/src/list/listarDeudoresCompleto.php');
#$router->get('cliente/getClientesByCuota', '/src/list/getClientesByCuota.php');

// Plan 
$router->get('plan/crear', '/src/create/crearPlan.php'); 
$router->get('plan/editar', '/src/edit/editPlan.php');
$router->get('plan/listar/{id}', '/src/list/listarPlan.php');

// Access Point 
$router->get('point/crear', '/src/create/crearPoint.php');
$router->get('point/listar', '/src/list/listarPoint.php');
$router->get('point/contenido/{id}', '/src/content/contenidoPoint.php'); 

// Emitir Cuotas 
$router->get('cuota/emitir', '/src/create/emitirCuota.php');

// Estadisticas
$router->get('estadisticas', '/src/estadistica.php');

// Editar Datos Usuario 
$router->get('datos/editar', '/src/edit/editDatos.php');

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
