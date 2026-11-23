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
        // Request multipart/form-data atau urlencoded (form dengan berkas) dibaca dari $_POST,
        // selain itu dibaca sebagai body JSON
        $tipe = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($tipe, 'multipart/form-data') === 0 || stripos($tipe, 'application/x-www-form-urlencoded') === 0) {
            $data = $_POST;
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!is_array($data)) {
                Response::json(400, false, 'Body JSON tidak valid');
            }
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


    // Folder penyimpanan foto (di dalam public/ agar dapat diakses browser)
    private const UPLOAD_DIR = __DIR__ . '/../../public/uploads/';

    // Memvalidasi berkas foto. Mengembalikan null jika tidak ada berkas yang diunggah,
    // atau array [file, ekstensi] jika berkas valid. Berkas dipindahkan terpisah oleh simpanFoto().
    private function validasiFoto(): ?array {
        if (empty($_FILES['foto']) || ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $file = $_FILES['foto'];
        if (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(422, false, 'Berkas gagal diunggah');
        }
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        // Ekstensi dan $_FILES['type'] bisa dipalsukan, sehingga isi berkas diperiksa dengan exif_imagetype()
        $tipeGambar = @exif_imagetype($file['tmp_name']);
        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'], true) || !in_array($tipeGambar, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            Response::json(422, false, 'Berkas harus berupa gambar JPG/PNG yang valid');
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB');
        }
        return [$file, $ekstensi];
    }

    // Memindahkan berkas ke folder uploads dengan nama unik, mengembalikan nama berkas baru
    private function simpanFoto(array $foto): string {
        [$file, $ekstensi] = $foto;
        $namaBaru = uniqid('foto_', true) . '.' . $ekstensi;
        if (!move_uploaded_file($file['tmp_name'], self::UPLOAD_DIR . $namaBaru)) {
            Response::json(500, false, 'Gagal menyimpan berkas');
        }
        return $namaBaru;
    }

    private function hapusFoto(?string $nama): void {
        if ($nama && is_file(self::UPLOAD_DIR . basename($nama))) {
            unlink(self::UPLOAD_DIR . basename($nama));
        }
    }

    // POST: Menambahkan data (DIPROTEKSI: HANYA ADMIN)
        public function store(): void {
        Auth::requireAdmin(); 

        $data = $this->input();
        $foto = $this->validasiFoto();
        $namaFoto = $foto ? $this->simpanFoto($foto) : null;
        $data['foto'] = $namaFoto;
        try {
            $id = $this->model->create($data);
            Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id, 'foto' => $namaFoto]);
        } catch (PDOException $e) {
            $this->hapusFoto($namaFoto);
            // Tangkap error dari database (jika ada)
            if ($e->getCode() === '23000') {
                Response::json(422, false, 'NIM sudah terdaftar', null, ['nim' => 'NIM sudah digunakan mahasiswa lain']);
            }
            Response::json(500, false, 'Gagal menambahkan data');
        } catch (Exception $e) {
            $this->hapusFoto($namaFoto);
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
        $lama = $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $foto = $this->validasiFoto();
        $namaFoto = $foto ? $this->simpanFoto($foto) : null;
        $data['foto'] = $namaFoto;

        try {
            $this->model->update($id, $data);
            // Tugas mandiri 1: hapus foto lama (unlink) setelah data berhasil diperbarui dengan foto baru
            if ($namaFoto) $this->hapusFoto($lama['foto']);
            Response::json(200, true, 'Data berhasil diperbarui', ['foto' => $namaFoto ?? $lama['foto']]);
        } catch (PDOException $e) {
            $this->hapusFoto($namaFoto);
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
        
        $lama = $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        try {
            $this->model->delete($id);
            $this->hapusFoto($lama['foto']); // berkas foto ikut dihapus agar tidak menjadi sampah
            Response::json(200, true, 'Data berhasil dihapus');
        } catch (PDOException $e) {
            Response::json(500, false, 'Gagal menghapus data');
        }
    }
}