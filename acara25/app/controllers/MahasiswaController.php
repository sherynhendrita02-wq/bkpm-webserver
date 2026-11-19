<?php
class MahasiswaController {
    private $model;

    public function __construct() {
        $this->model = new Mahasiswa(); 
    }

    // GET: Mengambil semua data atau satu data (Publik / Tidak butuh token)
    public function index(): void {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $data = $this->model->find((int) $id);
            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }
            Response::json(200, true, 'Data berhasil diambil', $data);
        }
        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    // Fungsi bantuan untuk validasi input
    private function input(): array {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            Response::json(400, false, 'Body JSON tidak valid');
        }
        
        // Validasi field wajib
        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "Field {$field} wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Validasi gagal', null, $errors);
        }

        // Validasi NIM harus 9 digit
        if (!preg_match('/^[0-9]{9}$/', (string) $data['nim'])) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null, [
                'nim' => 'NIM harus terdiri dari tepat 9 angka'
            ]);
        }
        
        return $data;
    }

    // POST: Menambahkan data (DIPROTEKSI: HANYA ADMIN)
        public function store(): void {
        Auth::requireAdmin(); 

        $data = $this->input();
        try {
            $id = $this->model->create($data);
            Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
        } catch (PDOException $e) {
            // Tangkap error dari database (jika ada)
            if ($e->getCode() === '23000') {
                Response::json(422, false, 'NIM sudah terdaftar', null, ['nim' => 'NIM sudah digunakan mahasiswa lain']);
            }
            Response::json(500, false, 'Gagal menambahkan data');
        } catch (Exception $e) {
            // Tangkap error manual "NIM sudah terdaftar" dari Model (perbaikan acara 23: 409 -> 422)
            Response::json(422, false, $e->getMessage(), null, ['nim' => 'NIM sudah digunakan mahasiswa lain']);
        }
    }

    // PUT: Memperbarui data (DIPROTEKSI: HANYA ADMIN)
    public function update($id): void {
        // --- PROTEKSI TOKEN + ROLE ADMIN DI SINI ---
        Auth::requireAdmin();

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            Response::json(400, false, 'ID tidak valid');
        }
        
        $data = $this->input();
        
        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }
        
        try {
            $this->model->update($id, $data);
            Response::json(200, true, 'Data berhasil diperbarui');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json(422, false, 'NIM sudah digunakan', null, ['nim' => 'NIM sudah digunakan mahasiswa lain']);
            }
            Response::json(500, false, 'Gagal memperbarui data');
        }
    }

    // DELETE: Menghapus data (DIPROTEKSI: HANYA ADMIN)
    public function destroy($id): void {
        // --- PROTEKSI TOKEN + ROLE ADMIN DI SINI ---
        Auth::requireAdmin();

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            Response::json(400, false, 'ID tidak valid');
        }
        
        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        try {
            $this->model->delete($id);
            Response::json(200, true, 'Data berhasil dihapus');
        } catch (PDOException $e) {
            Response::json(500, false, 'Gagal menghapus data');
        }
    }
}