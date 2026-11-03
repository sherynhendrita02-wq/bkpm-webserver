
<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // GET: Menampilkan semua data atau satu mahasiswa
    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (isset($_GET['id'])) {
            if (!$id || $id < 1) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid'
                ]);
                return;
            }

            $data = $this->model->find($id);

            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $data
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $this->model->getAll()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // POST: Menambahkan mahasiswa baru
    public function store(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            !is_array($data) ||
            !isset($data['nim'], $data['nama'], $data['prodi']) ||
            trim((string) $data['nim']) === '' ||
            trim((string) $data['nama']) === '' ||
            trim((string) $data['prodi']) === ''
        ) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Field nim, nama, dan prodi wajib diisi'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return;
        }

        try {
            $id = $this->model->create($data);

            http_response_code(201);

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => [
                    'id' => $id
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            $status = (int) $e->getCode();

            if ($status < 400 || $status > 599) {
                $status = 500;
            }

            http_response_code($status);

            echo json_encode([
                'success' => false,
                'message' => $status === 409
                    ? 'NIM sudah terdaftar'
                    : 'Gagal menyimpan data'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // PUT: Memperbarui data mahasiswa
    public function update($id): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_var($id, FILTER_VALIDATE_INT);

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!$id || $id < 1) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid'
            ]);

            return;
        }

        if (
            !is_array($data) ||
            !isset($data['nim'], $data['nama'], $data['prodi']) ||
            trim((string) $data['nim']) === '' ||
            trim((string) $data['nama']) === '' ||
            trim((string) $data['prodi']) === ''
        ) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Field nim, nama, dan prodi wajib diisi'
            ]);

            return;
        }

        if (!$this->model->find($id)) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);

            return;
        }

        try {
            $this->model->update($id, $data);

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                http_response_code(409);

                echo json_encode([
                    'success' => false,
                    'message' => 'NIM sudah digunakan mahasiswa lain'
                ]);

                return;
            }

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui data'
            ]);
        }
    }

    // DELETE: Menghapus mahasiswa
    public function destroy($id): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid'
            ]);

            return;
        }

        if (!$this->model->find($id)) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);

            return;
        }

        try {
            $this->model->delete($id);

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
