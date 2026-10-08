<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';

class MahasiswaController extends Controller
{
    private MahasiswaRepository $repository;

    public function __construct()
    {
        $pdo = Database::getInstance();
        $this->repository = new MahasiswaRepository($pdo);
    }

    // Menampilkan semua data mahasiswa
    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');

        $mahasiswa = $this->repository->all($keyword);

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa,
            'keyword' => $keyword
        ]);
    }

    // Menampilkan form tambah
    public function create(): void
    {
        $this->view('mahasiswa/create');
    }

    // Menyimpan data mahasiswa
    public function store(): void
    {
        $data = [
            'nim' => trim($_POST['nim'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0)
        ];

        $this->repository->create($data);

        $this->redirect('/acara8/public/mahasiswa');
    }

    // Menampilkan form edit
    public function edit(int $id): void
    {
        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            http_response_code(404);
            echo "Data mahasiswa tidak ditemukan";
            return;
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    // Mengupdate data mahasiswa
    public function update(int $id): void
    {
        $data = [
            'nim' => trim($_POST['nim'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0)
        ];

        $this->repository->update($id, $data);

        $this->redirect('/acara8/public/mahasiswa');
    }

    // Menghapus data mahasiswa
    public function destroy(int $id): void
    {
        $this->repository->delete($id);

        $this->redirect('/acara8/public/mahasiswa');
    }
}