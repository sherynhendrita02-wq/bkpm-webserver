<?php
class Router {
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController' // <-- TAMBAHKAN INI
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

        // --- TAMBAHKAN BLOK INI UNTUK REGISTER ---
        if ($resource === 'register') {
            $controller->register();
            return;
        }
        // -----------------------------------------

        switch ($_SERVER['REQUEST_METHOD'] ?? 'GET') {
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