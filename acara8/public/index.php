<?php

require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara8/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

$uri = $uri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];

$controller = new MahasiswaController();
$prodiController = new ProdiController();
$matakuliahController = new MatakuliahController();

// =====================================================
// ROUTING MAHASISWA
// =====================================================

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
if ($method === 'GET' && preg_match(
    '#^/mahasiswa/edit/([0-9]+)$#',
    $uri,
    $matches
)) {
    $controller->edit((int) $matches[1]);
    exit;
}

// Update mahasiswa
if ($method === 'POST' && preg_match(
    '#^/mahasiswa/update/([0-9]+)$#',
    $uri,
    $matches
)) {
    $controller->update((int) $matches[1]);
    exit;
}

// Hapus mahasiswa
if ($method === 'POST' && preg_match(
    '#^/mahasiswa/delete/([0-9]+)$#',
    $uri,
    $matches
)) {
    $controller->destroy((int) $matches[1]);
    exit;
}


// =====================================================
// ROUTING PRODI
// =====================================================

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
if ($method === 'GET' && preg_match(
    '#^/prodi/edit/([0-9]+)$#',
    $uri,
    $matches
)) {
    $prodiController->edit((int) $matches[1]);
    exit;
}

// Update prodi
if ($method === 'POST' && preg_match(
    '#^/prodi/update/([0-9]+)$#',
    $uri,
    $matches
)) {
    $prodiController->update((int) $matches[1]);
    exit;
}

// Hapus prodi
if ($method === 'POST' && preg_match(
    '#^/prodi/delete/([0-9]+)$#',
    $uri,
    $matches
)) {
    $prodiController->destroy((int) $matches[1]);
    exit;
}

// MATA KULIAH

if ($method === 'GET' && $uri === '/matakuliah') {
    $matakuliahController->index();
    exit;
}

if ($method === 'GET' && $uri === '/matakuliah/create') {
    $matakuliahController->create();
    exit;
}

if ($method === 'POST' && $uri === '/matakuliah') {
    $matakuliahController->store();
    exit;
}

//======================================================
// ROUTING MATA KULIAH
//======================================================

if ($method === 'GET' && preg_match(
    '#^/matakuliah/edit/([0-9]+)$#',
    $uri,
    $matches
)) {
    $matakuliahController->edit((int) $matches[1]);
    exit;
}

if ($method === 'POST' && preg_match(
    '#^/matakuliah/update/([0-9]+)$#',
    $uri,
    $matches
)) {
    $matakuliahController->update((int) $matches[1]);
    exit;
}

if ($method === 'POST' && preg_match(
    '#^/matakuliah/delete/([0-9]+)$#',
    $uri,
    $matches
)) {
    $matakuliahController->destroy((int) $matches[1]);
    exit;
}

// =====================================================
// 404
// =====================================================

http_response_code(404);
echo "404 - Halaman tidak ditemukan";