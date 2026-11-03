
<?php

require_once __DIR__ . '/../../app/controllers/ApiMahasiswaController.php';

$controller = new ApiMahasiswaController();
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (!preg_match('~/api/mahasiswa(?:/(\d+))?/?$~', $uri, $matches)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan',
        'data' => null
    ]);
    exit;
}

$id = isset($matches[1]) ? (int) $matches[1] : null;

try {
    switch ($method) {
        case 'GET':
            $controller->index($id);
            break;

        case 'POST':
            if ($id !== null) {
                http_response_code(405);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
                exit;
            }
            $controller->store();
            break;

        case 'PUT':
            if (!$id) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID wajib diisi']);
                exit;
            }
            $controller->update($id);
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID wajib diisi']);
                exit;
            }
            $controller->destroy($id);
            break;

        default:
            header('Allow: GET, POST, PUT, DELETE');
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
}
