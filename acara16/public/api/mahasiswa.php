<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../app/Core/Database.php';

function responseJson(int $status, bool $success, string $message, $data = null): void
{
    http_response_code($status);

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;
}

try {
    $database = new Database();
    $pdo = $database->getConnection();

    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Ambil ID dari URL, misalnya /api/mahasiswa/1
    $id = null;

    if (preg_match('~/api/mahasiswa/(\d+)/?$~', $uri, $matches)) {
        $id = (int) $matches[1];
    }

    // Baca JSON dari request body
    $rawBody = file_get_contents('php://input');
    $body = [];

    if (in_array($method, ['POST', 'PUT'], true)) {
        $body = json_decode($rawBody, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($body)) {
            responseJson(400, false, 'Format JSON tidak valid');
        }
    }

    // GET: seluruh data mahasiswa atau berdasarkan ID
    if ($method === 'GET') {
        if ($id !== null) {
            $stmt = $pdo->prepare(
                'SELECT id, nim, nama, email, prodi_id, angkatan
                 FROM mahasiswa WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mahasiswa) {
                responseJson(404, false, 'Data mahasiswa tidak ditemukan');
            }

            responseJson(200, true, 'Data mahasiswa berhasil ditemukan', $mahasiswa);
        }

        $stmt = $pdo->query(
            'SELECT id, nim, nama, email, prodi_id, angkatan
             FROM mahasiswa ORDER BY id DESC'
        );

        responseJson(200, true, 'Data mahasiswa berhasil ditampilkan',
            $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Validasi data untuk POST dan PUT
    if (in_array($method, ['POST', 'PUT'], true)) {
        $required = ['nim', 'nama', 'email', 'prodi_id', 'angkatan'];

        foreach ($required as $field) {
            if (!isset($body[$field]) || trim((string) $body[$field]) === '') {
                responseJson(422, false, "Field {$field} wajib diisi");
            }
        }

        if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            responseJson(422, false, 'Format email tidak valid');
        }

        if (!ctype_digit((string) $body['nim'])) {
            responseJson(422, false, 'NIM harus berupa angka');
        }
    }

    // POST: tambah mahasiswa
    if ($method === 'POST') {
        $stmt = $pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan)'
        );

        $stmt->execute([
            'nim' => $body['nim'],
            'nama' => trim($body['nama']),
            'email' => trim($body['email']),
            'prodi_id' => $body['prodi_id'],
            'angkatan' => $body['angkatan']
        ]);

        $body['id'] = (int) $pdo->lastInsertId();

        responseJson(201, true, 'Data mahasiswa berhasil ditambahkan', $body);
    }

    // PUT: ubah mahasiswa berdasarkan ID
    if ($method === 'PUT') {
        if ($id === null || $id < 1) {
            responseJson(400, false, 'ID mahasiswa pada URL wajib diisi');
        }

        $check = $pdo->prepare('SELECT id FROM mahasiswa WHERE id = :id');
        $check->execute(['id' => $id]);

        if (!$check->fetch()) {
            responseJson(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $stmt = $pdo->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan
             WHERE id = :id'
        );

        $stmt->execute([
            'nim' => $body['nim'],
            'nama' => trim($body['nama']),
            'email' => trim($body['email']),
            'prodi_id' => $body['prodi_id'],
            'angkatan' => $body['angkatan'],
            'id' => $id
        ]);

        responseJson(200, true, 'Data mahasiswa berhasil diperbarui', [
            'id' => $id,
            'nim' => $body['nim'],
            'nama' => trim($body['nama']),
            'email' => trim($body['email']),
            'prodi_id' => $body['prodi_id'],
            'angkatan' => $body['angkatan']
        ]);
    }

    // DELETE: hapus mahasiswa berdasarkan ID
    if ($method === 'DELETE') {
        if ($id === null || $id < 1) {
            responseJson(400, false, 'ID mahasiswa pada URL wajib diisi');
        }

        $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() === 0) {
            responseJson(404, false, 'Data mahasiswa tidak ditemukan');
        }

        responseJson(200, true, 'Data mahasiswa berhasil dihapus', ['id' => $id]);
    }

    header('Allow: GET, POST, PUT, DELETE');
    responseJson(405, false, 'HTTP method tidak diizinkan');

} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        responseJson(409, false, 'NIM mungkin sudah digunakan atau data terkait tidak valid');
    }

    error_log($e->getMessage());
    responseJson(500, false, 'Terjadi kesalahan pada database');
} catch (Throwable $e) {
    error_log($e->getMessage());
    responseJson(500, false, 'Terjadi kesalahan pada server');
}
