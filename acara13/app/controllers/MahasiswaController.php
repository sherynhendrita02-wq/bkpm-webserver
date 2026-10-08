<?php

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repository;
    private MahasiswaService $service;

    public function __construct(
        MahasiswaRepository $repository,
        MahasiswaService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(): void
    {
        $keyword = $_GET['search'] ?? '';

        $mahasiswa = $this->repository->all($keyword);

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa,
            'keyword' => $keyword
        ]);
    }

    public function create(): void
    {
        $prodi = $this->service->getAllProdi();

        $this->view('mahasiswa/create', [
            'prodi' => $prodi
        ]);
    }

    public function store(): void
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $result = $this->service->create($data);

        if (!$result['success']) {
            $prodi = $this->service->getAllProdi();

            $this->view('mahasiswa/create', [
                'prodi' => $prodi,
                'errors' => $result['errors'],
                'old' => $data
            ]);

            return;
        }

        $_SESSION['flash'] = 'Mahasiswa berhasil ditambahkan.';

        $this->redirect('/bkpm-webserver/acara13/public/mahasiswa');
    }

    public function edit(int $id): void
    {
        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            die('Data mahasiswa tidak ditemukan.');
        }

        $prodi = $this->service->getAllProdi();

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $result = $this->service->update($id, $data);

        if (!$result['success']) {
            $mahasiswa = $this->repository->find($id);
            $prodi = $this->service->getAllProdi();

            $this->view('mahasiswa/edit', [
                'mahasiswa' => array_merge($mahasiswa ?? [], $data),
                'prodi' => $prodi,
                'errors' => $result['errors']
            ]);

            return;
        }

        $_SESSION['flash'] = 'Mahasiswa berhasil diubah.';

        $this->redirect('/bkpm-webserver/acara13/public/mahasiswa');
    }

    public function destroy(int $id): void
    {
        $this->repository->delete($id);

        $_SESSION['flash'] = 'Mahasiswa berhasil dihapus.';

        $this->redirect('/bkpm-webserver/acara13/public/mahasiswa');
    }
}