<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class ProdiController extends Controller
{
    private ProdiRepository $repository;

    public function __construct()
    {
        $pdo = Database::getInstance();
        $this->repository = new ProdiRepository($pdo);
    }

    public function index(): void
    {
        $prodi = $this->repository->all();

        $this->view('prodi/index', [
            'prodi' => $prodi
        ]);
    }

    public function create(): void
    {
        $this->view('prodi/create');
    }

    public function store(): void
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? '')
        ];

        $this->repository->create($data);

        $this->redirect('/acara8/public/prodi');
    }

    public function edit(int $id): void
    {
        $prodi = $this->repository->find($id);

        if (!$prodi) {
            http_response_code(404);
            echo "Data prodi tidak ditemukan";
            return;
        }

        $this->view('prodi/edit', [
            'prodi' => $prodi
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? '')
        ];

        $this->repository->update($id, $data);

        $this->redirect('/acara8/public/prodi');
    }

    public function destroy(int $id): void
    {
        $this->repository->delete($id);

        $this->redirect('/acara8/public/prodi');
    }
}