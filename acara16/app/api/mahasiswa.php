<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../app/Core/Database.php';

try {

    $database = new Database();
    $pdo = $database->getConnection();

    // =========================
    // POST - Tambah Mahasiswa
    // =========================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Data JSON tidak valid'
            ]);

            exit;
        }

        $nim = $input['nim'] ?? '';
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $prodi_id = $input['prodi_id'] ?? '';
        $angkatan = $input['angkatan'] ?? '';

        if (
            empty($nim) ||
            empty($nama) ||
            empty($email) ||
            empty($prodi_id) ||
            empty($angkatan)
        ) {
            http_response_code(422);

            echo json_encode([
                'success' => false,
                'message' => 'Semua data wajib diisi'
            ]);

            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $nim,
            $nama,
            $email,
            $prodi_id,
            $angkatan
        ]);

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => [
                'id' => $pdo->lastInsertId(),
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'prodi_id' => $prodi_id,
                'angkatan' => $angkatan
            ]
        ]);

        exit;
    }


// =========================
// PUT - Update Mahasiswa
// =========================

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'ID mahasiswa wajib diisi'
        ]);

        exit;
    }

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!$input) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Data JSON tidak valid'
        ]);

        exit;
    }

    $nim = $input['nim'] ?? '';
    $nama = $input['nama'] ?? '';
    $email = $input['email'] ?? '';
    $prodi_id = $input['prodi_id'] ?? '';
    $angkatan = $input['angkatan'] ?? '';

    if (
        empty($nim) ||
        empty($nama) ||
        empty($email) ||
        empty($prodi_id) ||
        empty($angkatan)
    ) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Semua data wajib diisi'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE mahasiswa
        SET nim = ?,
            nama = ?,
            email = ?,
            prodi_id = ?,
            angkatan = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $nim,
        $nama,
        $email,
        $prodi_id,
        $angkatan,
        $id
    ]);

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'message' => 'Data mahasiswa berhasil diubah'
    ]);

    exit;
}

// =========================
// DELETE - Hapus Mahasiswa
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'ID mahasiswa wajib diisi'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        DELETE FROM mahasiswa
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Data mahasiswa tidak ditemukan'
        ]);

        exit;
    }

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'message' => 'Data mahasiswa berhasil dihapus'
    ]);

    exit;
}



    // =========================
    // GET by ID
    // =========================
    if (isset($_GET['id'])) {

        $id = (int) $_GET['id'];

        $stmt = $pdo->prepare("
            SELECT
                id,
                nim,
                nama,
                email,
                prodi_id,
                angkatan
            FROM mahasiswa
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $data = $stmt->fetch();

        if (!$data) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ]);

            exit;
        }

        http_response_code(200);

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diambil',
            'data' => $data
        ]);

        exit;
    }

    

    // =========================
    // GET All
    // =========================
    $stmt = $pdo->query("
        SELECT
            id,
            nim,
            nama,
            email,
            prodi_id,
            angkatan
        FROM mahasiswa
        ORDER BY nim
    ");

    $data = $stmt->fetchAll();

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'message' => 'Data mahasiswa berhasil diambil',
        'data' => $data
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server'
    ]);
}