<?php

class MahasiswaController extends Controller
{
    private MahasiswaRepository $repository;
    private ProdiRepository $prodiRepository;

    public function __construct(
        MahasiswaRepository $repository,
        ProdiRepository $prodiRepository
    ) {
        $this->repository = $repository;
        $this->prodiRepository = $prodiRepository;
    }

    public function index(): void
    {
        $keyword = $_GET['search'] ?? '';

        $data = $this->repository->all($keyword);

        $this->view('mahasiswa/index', [
            'mahasiswa' => $data,
            'keyword' => $keyword
        ]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', [
            'prodi' => $this->prodiRepository->all()
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

        $data['prodi_id'] = (int) $data['prodi_id'];

        if (!$this->prodiRepository->exists($data['prodi_id'])) {
            $this->view('mahasiswa/create', [
                'prodi' => $this->prodiRepository->all(),
                'input' => $data,
                'error' => 'Program studi yang dipilih tidak ditemukan. Silakan pilih prodi yang tersedia.'
            ]);
            return;
        }

        $this->repository->create($data);

        $this->redirect('/bkpm-webserver/acara9/public/mahasiswa');
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            die('Data mahasiswa tidak ditemukan.');
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodi' => $this->prodiRepository->all()
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $data['prodi_id'] = (int) $data['prodi_id'];

        if (!$this->prodiRepository->exists($data['prodi_id'])) {
            $this->view('mahasiswa/edit', [
                'mahasiswa' => array_merge($this->repository->find($id) ?? [], $data),
                'prodi' => $this->prodiRepository->all(),
                'error' => 'Program studi yang dipilih tidak ditemukan. Silakan pilih prodi yang tersedia.'
            ]);
            return;
        }

        $this->repository->update($id, $data);

        $this->redirect('/bkpm-webserver/acara9/public/mahasiswa');
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $this->repository->delete($id);

        $this->redirect('/bkpm-webserver/acara9/public/mahasiswa');
    }
}