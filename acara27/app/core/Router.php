<?php
class Router {
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
        'login'     => 'AuthController' // <-- 1. TAMBAHKAN LOGIN DI SINI
    ];

    public function dispatch(): void {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        
        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        $resource = $segments[count($segments) - 1] ?? 'mahasiswa';
        $controllerName = $this->map[$resource] ?? null;
        
        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();

        // 2. TAMBAHKAN BLOK PENGECEKAN LOGIN DI SINI
        if ($resource === 'register') {
            $controller->register();
            return;
        }
        if ($resource === 'login') {
            $controller->login();
            return;
        }

        // PHP tidak mengisi $_POST/$_FILES pada request PUT bertipe multipart, sehingga
        // form dengan berkas dikirim sebagai POST + ?_method=PUT lalu diteruskan ke update()
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST' && in_array(strtoupper($_GET['_method'] ?? ''), ['PUT', 'DELETE'], true)) {
            $method = strtoupper($_GET['_method']);
        }

        switch ($method) {
            case 'GET': $controller->index(); break;
            case 'POST': $controller->store(); break;
            case 'PUT': $controller->update($_GET['id'] ?? null); break;
            case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
            default:
                header('Allow: GET, POST, PUT, DELETE');
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}