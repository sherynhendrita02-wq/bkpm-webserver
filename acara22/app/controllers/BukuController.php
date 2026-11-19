
<?php
class BukuController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
    }

    // Mengirim respons JSON
    private function respond(
        array $body,
        int $status = 200
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $body,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }

    // Membaca request JSON
    private function readJson(): ?array
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        return is_array($data) ? $data : null;
    }

    // Memvalidasi data buku
    private function validData(?array $data): bool
    {
        return $data !== null
            && !empty($data['judul'])
            && !empty($data['penulis'])
            && !empty($data['penerbit'])
            && isset($data['tahun_terbit'])
            && filter_var(
                $data['tahun_terbit'],
                FILTER_VALIDATE_INT
            ) !== false;
    }

    // GET: mengambil semua buku atau satu buku
    public function index(): void
    {
        if (isset($_GET['id'])) {
            $id = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT
            );

            if (!$id || $id < 1) {
                $this->respond([
                    'success' => false,
                    'message' => 'ID tidak valid'
                ], 400);
                return;
            }

            $data = $this->model->find($id);

            if (!$data) {
                $this->respond([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
                return;
            }

            $this->respond([
                'success' => true,
                'data' => $data
            ]);
            return;
        }

        $this->respond([
            'success' => true,
            'data' => $this->model->getAll()
        ]);
    }

    // POST: menambahkan buku
    public function store(): void
    {
        $data = $this->readJson();

        if (!$this->validData($data)) {
            $this->respond([
                'success' => false,
                'message' => 'Field judul, penulis, penerbit, dan tahun_terbit wajib diisi dengan benar'
            ], 400);
            return;
        }

        try {
            $id = $this->model->create($data);

            $this->respond([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => ['id' => $id]
            ], 201);
        } catch (PDOException $e) {
            $this->respond([
                'success' => false,
                'message' => 'Buku gagal ditambahkan'
            ], 500);
        }
    }

    // PUT: memperbarui buku
    public function update($rawId): void
    {
        $id = filter_var(
            $rawId,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'ID tidak valid'
            ], 400);
            return;
        }

        $data = $this->readJson();

        if (!$this->validData($data)) {
            $this->respond([
                'success' => false,
                'message' => 'Field judul, penulis, penerbit, dan tahun_terbit wajib diisi dengan benar'
            ], 400);
            return;
        }

        if (!$this->model->find($id)) {
            $this->respond([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
            return;
        }

        try {
            $this->model->update($id, $data);

            $this->respond([
                'success' => true,
                'message' => 'Buku berhasil diperbarui'
            ]);
        } catch (PDOException $e) {
            $this->respond([
                'success' => false,
                'message' => 'Buku gagal diperbarui'
            ], 500);
        }
    }

    // DELETE: menghapus buku
    public function destroy($rawId): void
    {
        $id = filter_var(
            $rawId,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'ID tidak valid'
            ], 400);
            return;
        }

        if (!$this->model->delete($id)) {
            $this->respond([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
            return;
        }

        $this->respond([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
