<?php

session_start();

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

$config = require __DIR__ . '/../config/database.php';

$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

try {

    $pdo = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

} catch (PDOException $e) {

    die("Koneksi database gagal: " . $e->getMessage());

}


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/MahasiswaController.php';


/*
|--------------------------------------------------------------------------
| MIDDLEWARE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Core/Middleware/Authmiddleware.php';


/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../routes/web.php';


/*
|--------------------------------------------------------------------------
| REQUEST
|--------------------------------------------------------------------------
*/

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$base = '/acara7/public';

if (str_starts_with($uri, $base)) {

    $uri = substr(
        $uri,
        strlen($base)
    );
}

$uri = $uri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| CARI ROUTE
|--------------------------------------------------------------------------
*/

$route = $routes[$method][$uri] ?? null;


/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/

if ($route === null) {

    http_response_code(404);

    echo "<h1>404 - Halaman Tidak Ditemukan</h1>";

    exit;
}


/*
|--------------------------------------------------------------------------
| MIDDLEWARE
|--------------------------------------------------------------------------
*/

if (isset($route['middleware'])) {

    foreach ($route['middleware'] as $middlewareName) {

        $middleware = new $middlewareName();

        $middleware->handle();
    }
}


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

$controllerName = $route[0];

$action = $route[1];

$controller = new $controllerName();

$controller->$action();