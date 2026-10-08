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

        if ($this->repository->hasNim($data['nim'])) {
            $this->view('mahasiswa/create', [
                'error' => 'NIM tersebut sudah terdaftar. Gunakan NIM yang berbeda.',
                'input' => $data
            ]);
            return;
        }

        try {
            $this->repository->create($data);
        } catch (PDOException $exception) {
            if (
                !$this->isUniqueConstraintViolation($exception)
                || !$this->repository->hasNim($data['nim'])
            ) {
                throw $exception;
            }

            $this->view('mahasiswa/create', [
                'error' => 'NIM tersebut sudah terdaftar. Gunakan NIM yang berbeda.',
                'input' => $data
            ]);
            return;
        }

        $this->redirect('/bkpm-webserver/acara10/public/mahasiswa');
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

        if ($this->repository->hasNim($data['nim'], $id)) {
            $this->view('mahasiswa/edit', [
                'mahasiswa' => array_merge($this->repository->find($id) ?? [], $data),
                'error' => 'NIM tersebut sudah digunakan oleh mahasiswa lain.'
            ]);
            return;
        }

        try {
            $this->repository->update($id, $data);
        } catch (PDOException $exception) {
            if (
                !$this->isUniqueConstraintViolation($exception)
                || !$this->repository->hasNim($data['nim'], $id)
            ) {
                throw $exception;
            }

            $this->view('mahasiswa/edit', [
                'mahasiswa' => array_merge($this->repository->find($id) ?? [], $data),
                'error' => 'NIM tersebut sudah digunakan oleh mahasiswa lain.'
            ]);
            return;
        }

        $this->redirect('/bkpm-webserver/acara10/public/mahasiswa');
    }

    // Menghapus mahasiswa
    public function destroy(int $id): void
    {
        $this->repository->delete($id);

        $this->redirect('/bkpm-webserver/acara10/public/mahasiswa');
    }

    private function isUniqueConstraintViolation(PDOException $exception): bool
    {
        $errorInfo = $exception->errorInfo;

        return is_array($errorInfo)
            && ($errorInfo[0] ?? null) === '23000'
            && in_array((int) ($errorInfo[1] ?? 0), [19, 1062], true);
    }
}