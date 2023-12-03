<?php
// index.php
require_once('./config.php');
require_once('./templates/header.php');
require_once('./message.php');

// Obtén la URL actual
$request_uri = $_SERVER['REQUEST_URI'];

// Define las rutas y los controladores correspondientes
$routes = [
    '/' => 'HomeController',
    '/page' => 'HomeController',
    '/login' => 'LoginController',
    '/logout' => 'LoginController',
    '/users' => 'UserController',
    '/newUser' => 'UserController',
    '/deleteUser' => 'UserController',
    '/updateUser' => 'UserController',
    '/posts' => 'PostController',
    '/searchPosts' => 'PostController',
    '/newPost' => 'PostController',
    '/editPost' => 'PostController',
    '/deletePost' => 'PostController',
    '/showPost' => 'PostController',
    '/category' => 'HomeController',
    '/search' => 'HomeController',
];

// Verifica si la ruta solicitada existe en la lista de rutas
$request_uri_without_prefix = '/'.explode('/',$_SERVER['REQUEST_URI'])[1];
if (array_key_exists($request_uri_without_prefix, $routes)) {
    // Obtiene el nombre del controlador
    $controllerName = $routes[$request_uri_without_prefix];
    
    // Incluye el archivo del controlador
    include_once("./controllers/{$controllerName}.php");
    
    // Crea una instancia del controlador y llama al método por defecto
    $controller = new $controllerName();
    $controllerMethod = strtolower($_SERVER['REQUEST_METHOD']) . ucfirst(substr($request_uri_without_prefix, 1));
    
    if (method_exists($controller, $controllerMethod)) {
        $controller->$controllerMethod();
    } else {
        $controller->index();
    }
} else {
    // Ruta no encontrada, 
    echo "404 Not Found";
}
?>
<!-- Pie de página -->
<footer>
    <p>&copy; 2023 Blog de Emma y Marina.</p>
</footer>