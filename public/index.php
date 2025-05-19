<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/autoload.php';

$db = new Database();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Mapeo simple de rutas
$routeMap = [
    '/' => ['AuthController', 'showLogin'],
    '/login' => ['AuthController', 'showLogin'],
    '/register' => ['AuthController', 'showRegister'],
    '/dashboard' => ['DashboardController', 'index'],
    '/auth/login' => ['AuthController', 'login'],
    '/auth/register' => ['AuthController', 'register'],
    '/logout' => ['AuthController', 'logout'],

    '/students' => ['StudentController', 'index'],
    '/students/create' => ['StudentController', 'create'],
    '/students/store' => ['StudentController', 'store']
];

if (array_key_exists($path, $routeMap)) {
    list($controllerName, $method) = $routeMap[$path];
    $controllerFile = __DIR__ . "/../app/controllers/{$controllerName}.php";
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName($db);
        
        // Cargar header ANTES de la vista
        require_once __DIR__ . '/../app/views/layouts/head.php';
        
        // Ejecutar el método del controlador
        $controller->$method($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : []);
        
        // Cargar footer DESPUÉS de la vista
        require_once __DIR__ . '/../app/views/layouts/footer.php';
    } else {
        http_response_code(500);
        die('Controlador no encontrado');
    }
} else {
    http_response_code(404);
    echo '404 Not Found';
}
?>