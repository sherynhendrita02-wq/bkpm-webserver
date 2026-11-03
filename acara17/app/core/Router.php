<?php
class Router
{
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku' => 'BukuController',
    ];

    public function dispatch(): void
    {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        $resource = $segments[count($segments) - 1] ?? 'mahasiswa';
        $controllerName = $this->map[$resource] ?? null;

        header('Content-Type: application/json; charset=utf-8');
        if (!$controllerName) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Endpoint tidak ditemukan']);
            return;
        }

        $controller = new $controllerName();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        switch ($method) {
            case 'GET':
                $controller->index();
                break;
            case 'POST':
                $controller->store();
                break;
            case 'PUT':
                $controller->update($_GET['id'] ?? null);
                break;
            case 'DELETE':
                $controller->destroy($_GET['id'] ?? null);
                break;
            default:
                http_response_code(405);
                header('Allow: GET, POST, PUT, DELETE');
                echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
        }
    }
}
