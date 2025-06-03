<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/autoload.php';

$db = new Database();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Mapeo de rutas estáticas
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
    '/students/store' => ['StudentController', 'store'],

    '/subjects' => ['SubjectController', 'index'],
    '/subjects/create' => ['SubjectController', 'create'],
    '/subjects/store' => ['SubjectController', 'store'],

    '/publishers' => ['PublisherController', 'index'],
    '/publishers/create' => ['PublisherController', 'create'],
    '/publishers/store' => ['PublisherController', 'store']
];

// Soporte para rutas dinámicas
if (array_key_exists($path, $routeMap)) {
    list($controllerName, $method) = $routeMap[$path];
} elseif (preg_match('#^/students/edit/(\d+)$#', $path, $matches)) {
    $controllerName = 'StudentController';
    $method = 'edit';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/students/update/(\d+)$#', $path, $matches)) {
    $controllerName = 'StudentController';
    $method = 'update';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/students/delete/(\d+)$#', $path, $matches)) {
    $controllerName = 'StudentController';
    $method = 'delete';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/subjects/edit/(\d+)$#', $path, $matches)) {
    $controllerName = 'SubjectController';
    $method = 'edit';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/subjects/update/(\d+)$#', $path, $matches)) {
    $controllerName = 'SubjectController';
    $method = 'update';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/subjects/delete/(\d+)$#', $path, $matches)) {
    $controllerName = 'SubjectController';
    $method = 'delete';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/publishers/edit/(\d+)$#', $path, $matches)) {
    $controllerName = 'PublisherController';
    $method = 'edit';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/publishers/update/(\d+)$#', $path, $matches)) {
    $controllerName = 'PublisherController';
    $method = 'update';
    $_GET['id'] = $matches[1];
} elseif (preg_match('#^/publishers/delete/(\d+)$#', $path, $matches)) {
    $controllerName = 'PublisherController';
    $method = 'delete';
    $_GET['id'] = $matches[1];
} else {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

$controllerFile = __DIR__ . "/../app/controllers/{$controllerName}.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName($db);

    require_once __DIR__ . '/../app/views/layouts/head.php';

    if (isset($_GET['id'])) {
        $controller->$method($_GET['id']);
    } else {
        $controller->$method($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : []);
    }

} else {
    http_response_code(500);
    die('Controlador no encontrado');
}
?>
