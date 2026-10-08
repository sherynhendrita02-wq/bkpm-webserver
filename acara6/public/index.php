<?php

session_start();

require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara6/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

$uri = $uri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];

if (
    $method === 'GET' &&
    preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)
) {
    $controller = new MahasiswaController();

    $controller->show($matches[1]);

    exit;
}

if (isset($routes[$method][$uri])) {

    $route = $routes[$method][$uri];

    $controllerName = $route[0];
    $action = $route[1];

    if (isset($route['middleware'])) {

        foreach ($route['middleware'] as $middlewareName) {

            $middleware = new $middlewareName();

            $middleware->handle();
        }
    }

    $controller = new $controllerName();

    $controller->$action();

} else {

    http_response_code(404);

    echo "404 - Halaman tidak ditemukan";
}