<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MatakuliahController extends Controller
{
    private MatakuliahRepository $repository;
    private ProdiRepository $prodiRepository;

    public function __construct()
    {
        $pdo = Database::getInstance();

        $this->repository = new MatakuliahRepository($pdo);
        $this->prodiRepository = new ProdiRepository($pdo);
    }

    public function index(): void
    {
        $matakuliah = $this->repository->all();

        $this->view('matakuliah/index', [
            'matakuliah' => $matakuliah
        ]);
    }

    public function create(): void
    {
        $prodi = $this->prodiRepository->all();

        $this->view('matakuliah/create', [
            'prodi' => $prodi
        ]);
    }

    public function store(): void
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'sks' => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0)
        ];

        $this->repository->create($data);

        $this->redirect('/acara8/public/matakuliah');
    }

    public function edit(int $id): void
    {
        $matakuliah = $this->repository->find($id);

        if (!$matakuliah) {
            http_response_code(404);
            echo "Data mata kuliah tidak ditemukan";
            return;
        }

        $prodi = $this->prodiRepository->all();

        $this->view('matakuliah/edit', [
            'matakuliah' => $matakuliah,
            'prodi' => $prodi
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'sks' => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0)
        ];

        $this->repository->update($id, $data);

        $this->redirect('/acara8/public/matakuliah');
    }

    public function destroy(int $id): void
    {
        $this->repository->delete($id);

        $this->redirect('/acara8/public/matakuliah');
    }
}