<?php

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    // Menampilkan data mahasiswa
    public function index(): void
    {
        $keyword = $_GET['search'] ?? '';

        $data = $this->repository->all($keyword);

        $this->view('mahasiswa/index', [
            'mahasiswa' => $data,
            'keyword' => $keyword
        ]);
    }

    // Menampilkan form tambah
    public function create(): void
    {
        $this->view('mahasiswa/create');
    }

    // Menyimpan mahasiswa
    public function store(): void
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $this->repository->create($data);

        $this->redirect('/acara10/public/mahasiswa');
    }

    // Menampilkan form edit
    public function edit(int $id): void
    {
        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            die('Data mahasiswa tidak ditemukan.');
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    // Mengupdate mahasiswa
    public function update(int $id): void
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $this->repository->update($id, $data);

        $this->redirect('/acara10/public/mahasiswa');
    }

    // Menghapus mahasiswa
    public function destroy(int $id): void
    {
        $this->repository->delete($id);

        $this->redirect('/acara10/public/mahasiswa');
    }
}