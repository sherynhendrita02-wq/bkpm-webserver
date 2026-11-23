<?php
trigger_error('uji log', E_USER_WARNING);

require_once __DIR__ . '/../app/core/autoload.php';
// Acara 30 - Langkah 3: penguatan konfigurasi PHP untuk produksi.
// Pengaturan utama dilakukan lewat MultiPHP INI Editor di cPanel; blok ini menjadi lapisan cadangan
// (berjalan bila env = 'production' pada app/config/config.local.php).
error_reporting(E_ALL);
if (Config::isProduction()) {
    ini_set('display_errors', '0');   // pesan error tidak ditampilkan ke pengguna
    ini_set('log_errors', '1');       // kesalahan tetap dicatat untuk ditelusuri pengembang
    if (Config::get('error_log')) ini_set('error_log', Config::get('error_log'));

    // Exception yang lolos dijawab JSON generik; rincian (path, query) hanya masuk ke log
    set_exception_handler(function (Throwable $e): void {
        error_log('Uncaught ' . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada server']);
    });
} else {
    ini_set('display_errors', '1');   // pengembangan: error langsung terlihat
}

// Header CORS: berlaku untuk seluruh resource yang melewati Router.
// Asal diatur lewat config ('cors_origin'); bawaan '*' untuk pengembangan.
header('Access-Control-Allow-Origin: ' . Config::get('cors_origin', '*'));
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight request dari browser (OPTIONS) cukup dijawab 204 tanpa isi
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

(new Router())->dispatch();
