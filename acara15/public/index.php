<?php

session_start();

/*
|--------------------------------------------------------------------------
| CORE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/BaseController.php';
require_once __DIR__ . '/../app/Core/BaseModel.php';
require_once __DIR__ . '/../app/Core/Controller.php';

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/repositories/ProdiRepository.php';

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/services/MahasiswaService.php';

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/controllers/ProdiController.php';
require_once __DIR__ . '/../app/controllers/MatakuliahController.php';

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

$database = new Database();

$pdo = $database->getConnection();

/*
|--------------------------------------------------------------------------
| REPOSITORY
|--------------------------------------------------------------------------
*/

$mahasiswaRepository = new MahasiswaRepository($pdo);
$prodiRepository = new ProdiRepository($pdo);

/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

$mahasiswaService = new MahasiswaService(
    $mahasiswaRepository,
    $prodiRepository
);

/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

$controller = new MahasiswaController(
    $mahasiswaRepository,
    $mahasiswaService
);

$prodiController = new ProdiController();

$matakuliahController = new MatakuliahController();

/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$base = '/bkpm-webserver/acara15/public';

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
| MAHASISWA - INDEX
|--------------------------------------------------------------------------
*/

if (
    $uri === '/mahasiswa'
    && $method === 'GET'
) {

    $controller->index();

    exit;
}

/*
|--------------------------------------------------------------------------
| MAHASISWA - CREATE FORM
|--------------------------------------------------------------------------
*/

if (
    $uri === '/mahasiswa/create'
    && $method === 'GET'
) {

    $controller->create();

    exit;
}

/*
|--------------------------------------------------------------------------
| MAHASISWA - STORE
|--------------------------------------------------------------------------
*/

if (
    $uri === '/mahasiswa'
    && $method === 'POST'
) {

    $controller->store();

    exit;
}

/*
|--------------------------------------------------------------------------
| MAHASISWA - EDIT FORM
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/mahasiswa/edit/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'GET'
) {

    $controller->edit(
        (int) $matches[1]
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| MAHASISWA - UPDATE
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/mahasiswa/update/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'POST'
) {

    $controller->update(
        (int) $matches[1]
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| MAHASISWA - DELETE
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/mahasiswa/delete/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'POST'
) {

    $controller->destroy(
        (int) $matches[1]
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| PRODI
|--------------------------------------------------------------------------
*/

if (
    $uri === '/prodi'
    && $method === 'GET'
) {

    $prodiController->index();

    exit;
}

if (
    $uri === '/prodi/create'
    && $method === 'GET'
) {

    $prodiController->create();

    exit;
}

if (
    $uri === '/prodi'
    && $method === 'POST'
) {

    $prodiController->store();

    exit;
}

if (
    preg_match(
        '#^/prodi/edit/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'GET'
) {

    $prodiController->edit(
        (int) $matches[1]
    );

    exit;
}

if (
    preg_match(
        '#^/prodi/update/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'POST'
) {

    $prodiController->update(
        (int) $matches[1]
    );

    exit;
}

if (
    preg_match(
        '#^/prodi/delete/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'POST'
) {

    $prodiController->destroy(
        (int) $matches[1]
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| MATA KULIAH
|--------------------------------------------------------------------------
*/

if (
    $uri === '/matakuliah'
    && $method === 'GET'
) {

    $matakuliahController->index();

    exit;
}

if (
    $uri === '/matakuliah/create'
    && $method === 'GET'
) {

    $matakuliahController->create();

    exit;
}

if (
    $uri === '/matakuliah'
    && $method === 'POST'
) {

    $matakuliahController->store();

    exit;
}

if (
    preg_match(
        '#^/matakuliah/edit/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'GET'
) {

    $matakuliahController->edit(
        (int) $matches[1]
    );

    exit;
}

if (
    preg_match(
        '#^/matakuliah/update/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'POST'
) {

    $matakuliahController->update(
        (int) $matches[1]
    );

    exit;
}

if (
    preg_match(
        '#^/matakuliah/delete/([0-9]+)$#',
        $uri,
        $matches
    )
    && $method === 'POST'
) {

    $matakuliahController->destroy(
        (int) $matches[1]
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/

http_response_code(404);

echo '404 - Halaman tidak ditemukan';