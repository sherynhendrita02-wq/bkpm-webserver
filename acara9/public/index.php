<?php

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Controller.php';

require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/Repositories/MatakuliahRepository.php';

require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';


/*
|--------------------------------------------------------------------------
| DATABASE & DEPENDENCY INJECTION
|--------------------------------------------------------------------------
*/

$database = new Database();

$mahasiswaRepository = new MahasiswaRepository($database);
$prodiRepository = new ProdiRepository($database->getConnection());

$controller = new MahasiswaController(
    $mahasiswaRepository,
    $prodiRepository
);

$prodiController = new ProdiController();

$matakuliahController = new MatakuliahController();


/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/bkpm-webserver/acara9/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

/*
 * Menghilangkan slash "/" di akhir URL
 * Contoh:
 * /mahasiswa/ menjadi /mahasiswa
 */
$uri = rtrim($uri, '/');

$uri = $uri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| ROUTING MAHASISWA
|--------------------------------------------------------------------------
*/


// Menampilkan data mahasiswa
if ($method === 'GET' && $uri === '/mahasiswa') {

    $controller->index();

    exit;
}


// Form tambah mahasiswa
if ($method === 'GET' && $uri === '/mahasiswa/create') {

    $controller->create();

    exit;
}


// Menyimpan mahasiswa
if ($method === 'POST' && $uri === '/mahasiswa') {

    $controller->store();

    exit;
}


// Form edit mahasiswa
if (
    $method === 'GET' &&
    preg_match(
        '#^/mahasiswa/edit/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $_GET['id'] = $matches[1];

    $controller->edit();

    exit;
}


// Update mahasiswa
if (
    $method === 'POST' &&
    preg_match(
        '#^/mahasiswa/update/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    /*
     * Controller update() mengambil ID
     * dari $_POST['id'].
     *
     * Jika ID belum ada di form,
     * gunakan ID dari URL.
     */
    if (!isset($_POST['id'])) {
        $_POST['id'] = $matches[1];
    }

    $controller->update();

    exit;
}


// Hapus mahasiswa
if (
    $method === 'POST' &&
    preg_match(
        '#^/mahasiswa/delete/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    /*
     * MahasiswaController::delete()
     * mengambil ID dari $_GET['id'].
     */
    $_GET['id'] = $matches[1];

    $controller->delete();

    exit;
}


/*
|--------------------------------------------------------------------------
| ROUTING PRODI
|--------------------------------------------------------------------------
*/


// Menampilkan data prodi
if ($method === 'GET' && $uri === '/prodi') {

    $prodiController->index();

    exit;
}


// Form tambah prodi
if ($method === 'GET' && $uri === '/prodi/create') {

    $prodiController->create();

    exit;
}


// Menyimpan prodi
if ($method === 'POST' && $uri === '/prodi') {

    $prodiController->store();

    exit;
}


// Form edit prodi
if (
    $method === 'GET' &&
    preg_match(
        '#^/prodi/edit/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $prodiController->edit(
        (int) $matches[1]
    );

    exit;
}


// Update prodi
if (
    $method === 'POST' &&
    preg_match(
        '#^/prodi/update/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $prodiController->update(
        (int) $matches[1]
    );

    exit;
}


// Hapus prodi
if (
    $method === 'POST' &&
    preg_match(
        '#^/prodi/delete/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $prodiController->destroy(
        (int) $matches[1]
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| ROUTING MATA KULIAH
|--------------------------------------------------------------------------
*/


// Menampilkan data mata kuliah
if ($method === 'GET' && $uri === '/matakuliah') {

    $matakuliahController->index();

    exit;
}


// Form tambah mata kuliah
if ($method === 'GET' && $uri === '/matakuliah/create') {

    $matakuliahController->create();

    exit;
}


// Menyimpan mata kuliah
if ($method === 'POST' && $uri === '/matakuliah') {

    $matakuliahController->store();

    exit;
}


// Form edit mata kuliah
if (
    $method === 'GET' &&
    preg_match(
        '#^/matakuliah/edit/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $matakuliahController->edit(
        (int) $matches[1]
    );

    exit;
}


// Update mata kuliah
if (
    $method === 'POST' &&
    preg_match(
        '#^/matakuliah/update/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $matakuliahController->update(
        (int) $matches[1]
    );

    exit;
}


// Hapus mata kuliah
if (
    $method === 'POST' &&
    preg_match(
        '#^/matakuliah/delete/([0-9]+)$#',
        $uri,
        $matches
    )
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

echo "404 - Halaman tidak ditemukan";