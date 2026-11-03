
<?php

class Router
{
    // Peta resource URL ke controller API
    private array $map = [
        'mahasiswa' => 'ApiMahasiswaController',
    ];

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($uri, '/'));
        $resource = end($segments) ?: '';

        $controllerName = $this->map[$resource] ?? null;

        // Jika endpoint tidak ditemukan
        if (!$controllerName) {
            $this->jsonError(
                404,
                'Endpoint tidak ditemukan'
            );
        }

        // Membuat controller API
        $controller = new $controllerName();

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $id = isset($_GET['id']) && is_numeric($_GET['id'])
            ? (int) $_GET['id']
            : null;

        switch ($method) {
            case 'GET':
                $controller->index($id);
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                if ($id === null || $id <= 0) {
                    $this->jsonError(
                        400,
                        'Parameter id wajib diisi'
                    );
                }

                $controller->update($id);
                break;

            case 'DELETE':
                if ($id === null || $id <= 0) {
                    $this->jsonError(
                        400,
                        'Parameter id wajib diisi'
                    );
                }

                $controller->destroy($id);
                break;

            default:
                header('Allow: GET, POST, PUT, DELETE');

                $this->jsonError(
                    405,
                    'Method tidak diizinkan'
                );
        }
    }

    private function jsonError(
        int $status,
        string $message
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => $message
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        exit;
    }
}
