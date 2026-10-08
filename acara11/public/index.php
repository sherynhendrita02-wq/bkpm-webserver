<?php

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/BaseController.php';
require_once __DIR__ . '/../app/Core/BaseModel.php';
require_once __DIR__ . '/../app/Core/Controller.php';

require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';

require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';


// ===============================
// DATABASE
// ===============================

$database = new Database();

$pdo = $database->getConnection();


// ===============================
// MAHASISWA
// ===============================

$mahasiswaRepository = new MahasiswaRepository($pdo);

$controller = new MahasiswaController($mahasiswaRepository);


// ===============================
// CONTROLLER LAIN
// ===============================

$prodiController = new ProdiController();

$matakuliahController = new MatakuliahController();


// ===============================
// URL
// ===============================

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara10/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

$uri = $uri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];


// ==================================================
// MAHASISWA
// ==================================================

// Menampilkan data mahasiswa
if ($uri === '/mahasiswa' && $method === 'GET') {

    $controller->index();


// Form tambah mahasiswa
} elseif ($uri === '/mahasiswa/create' && $method === 'GET') {

    $controller->create();


// Proses tambah mahasiswa
} elseif ($uri === '/mahasiswa' && $method === 'POST') {

    $controller->store();


// Form edit mahasiswa
} elseif (
    preg_match('#^/mahasiswa/edit/([0-9]+)$#', $uri, $matches)
    && $method === 'GET'
) {

    $controller->edit((int) $matches[1]);


// Proses update mahasiswa
} elseif (
    preg_match('#^/mahasiswa/update/([0-9]+)$#', $uri, $matches)
    && $method === 'POST'
) {

    $controller->update((int) $matches[1]);


// Proses hapus mahasiswa
} elseif (
    preg_match('#^/mahasiswa/delete/([0-9]+)$#', $uri, $matches)
    && $method === 'POST'
) {

    $controller->destroy((int) $matches[1]);


// ==================================================
// PRODI
// ==================================================

} elseif ($uri === '/prodi' && $method === 'GET') {

    $prodiController->index();

} elseif ($uri === '/prodi/create' && $method === 'GET') {

    $prodiController->create();

} elseif ($uri === '/prodi' && $method === 'POST') {

    $prodiController->store();

} elseif (
    preg_match('#^/prodi/edit/([0-9]+)$#', $uri, $matches)
    && $method === 'GET'
) {

    $prodiController->edit((int) $matches[1]);

} elseif (
    preg_match('#^/prodi/update/([0-9]+)$#', $uri, $matches)
    && $method === 'POST'
) {

    $prodiController->update((int) $matches[1]);

} elseif (
    preg_match('#^/prodi/delete/([0-9]+)$#', $uri, $matches)
    && $method === 'POST'
) {

    $prodiController->destroy((int) $matches[1]);


// ==================================================
// MATA KULIAH
// ==================================================

} elseif ($uri === '/matakuliah' && $method === 'GET') {

    $matakuliahController->index();

} elseif ($uri === '/matakuliah/create' && $method === 'GET') {

    $matakuliahController->create();

} elseif ($uri === '/matakuliah' && $method === 'POST') {

    $matakuliahController->store();

} elseif (
    preg_match('#^/matakuliah/edit/([0-9]+)$#', $uri, $matches)
    && $method === 'GET'
) {

    $matakuliahController->edit((int) $matches[1]);

} elseif (
    preg_match('#^/matakuliah/update/([0-9]+)$#', $uri, $matches)
    && $method === 'POST'
) {

    $matakuliahController->update((int) $matches[1]);

} elseif (
    preg_match('#^/matakuliah/delete/([0-9]+)$#', $uri, $matches)
    && $method === 'POST'
) {

    $matakuliahController->destroy((int) $matches[1]);


// ==================================================
// 404
// ==================================================

} else {

    http_response_code(404);

    echo "404 - Halaman tidak ditemukan";
}