<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Ruta al autoload de Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
//Conexion 
#var_dump("getenv:", getenv('DB_USER')); 
#var_dump($_SERVER['DB_USER']); 
#var_dump(__DIR__); 
#var_dump(BASE_URL); 
function getDBConnection() {
    $host = $_ENV['HOST'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DB_DATABASE'];
    $port = $_ENV['DB_PORT'];

    $db = new mysqli($host, $user, $password, $database, $port);
    if ($db->connect_error) {
        die("Error de conexión: " . $db->connect_error);
    }
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $db;
}
/*
global $db;
$host = $_ENV['HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '123456789';
$database = $_ENV['DB_DATABASE'] ?? 'login_db';
$port= $_ENV['DB_PORT'] ?? '3306'; 
//$db = mysqli_connect($host, $user, $password, $database, $port);
$db = new mysqli($host, $user, $password, $database, $port);
//mysqli_query($db, "SET NAME 'utf-8'");
mb_internal_encoding('utf-8');
mb_http_output('utf-8');

if (!$db){
    echo "ERROR EN LA CONEXION A LA BASE DE DATOS. "; 
}
//Iniciar Sesion 
if (!isset($_SESSION)){    
    session_start();
}
*/    
?>


