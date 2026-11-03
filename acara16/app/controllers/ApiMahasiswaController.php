
<?php

require_once __DIR__ . '/../Core/Database.php';

class ApiMahasiswaController
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    private function json(int $status, bool $success, string $message, $data = null): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        exit;
    }

    public function index(?int $id = null): void
    {
        if ($id !== null) {
            $stmt = $this->pdo->prepare(
                'SELECT id, nim, nama, email, prodi_id, angkatan
                 FROM mahasiswa WHERE id = ?'
            );
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $this->json(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $this->json(200, true, 'Data mahasiswa ditemukan', $data);
        }

        $stmt = $this->pdo->query(
            'SELECT id, nim, nama, email, prodi_id, angkatan
             FROM mahasiswa ORDER BY id DESC'
        );

        $this->json(200, true, 'Data mahasiswa berhasil ditampilkan',
            $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function store(): void
    {
        $data = $this->readJson();

        foreach (['nim', 'nama', 'email', 'prodi_id', 'angkatan'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $this->json(422, false, "Field {$field} wajib diisi");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->json(422, false, 'Format email tidak valid');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
             VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $data['nim'], trim($data['nama']), trim($data['email']),
            $data['prodi_id'], $data['angkatan']
        ]);

        $data['id'] = (int) $this->pdo->lastInsertId();
        $this->json(201, true, 'Data mahasiswa berhasil ditambahkan', $data);
    }

    public function update(int $id): void
    {
        $data = $this->readJson();

        foreach (['nim', 'nama', 'email', 'prodi_id', 'angkatan'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $this->json(422, false, "Field {$field} wajib diisi");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->json(422, false, 'Format email tidak valid');
        }

        $check = $this->pdo->prepare('SELECT id FROM mahasiswa WHERE id = ?');
        $check->execute([$id]);

        if (!$check->fetch()) {
            $this->json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE mahasiswa
             SET nim = ?, nama = ?, email = ?, prodi_id = ?, angkatan = ?
             WHERE id = ?'
        );

        $stmt->execute([
            $data['nim'], trim($data['nama']), trim($data['email']),
            $data['prodi_id'], $data['angkatan'], $id
        ]);

        $this->json(200, true, 'Data mahasiswa berhasil diperbarui', [
            'id' => $id,
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan']
        ]);
    }

    public function destroy(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM mahasiswa WHERE id = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            $this->json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $this->json(200, true, 'Data mahasiswa berhasil dihapus', ['id' => $id]);
    }

    private function readJson(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            $this->json(400, false, 'Format JSON tidak valid');
        }

        return $data;
    }
}
