<?php

class MahasiswaService
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

    private function validate(array $data, ?int $id = null): array
    {
        $errors = [];

        // Validasi NIM
        if (empty($data['nim'])) {
            $errors['nim'] = 'NIM wajib diisi.';
        }

        // Cek NIM duplikat
        if (
            !empty($data['nim']) &&
            $this->repository->existsByNim($data['nim'], $id)
        ) {
            $errors['nim'] = 'NIM sudah terdaftar.';
        }

        // Validasi nama
        if (empty($data['nama'])) {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        // Validasi email
        if (empty($data['email'])) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        }

        // Validasi prodi
        if (empty($data['prodi_id'])) {
            $errors['prodi_id'] = 'Program studi wajib dipilih.';
        }

        // Validasi angkatan
        if (empty($data['angkatan'])) {
            $errors['angkatan'] = 'Angkatan wajib diisi.';
        }

        return $errors;
    }

    public function create(array $data): array
    {
        $errors = $this->validate($data);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $this->repository->create($data);

        return [
            'success' => true,
            'errors' => []
        ];
    }

    public function update(int $id, array $data): array
    {
        $errors = $this->validate($data, $id);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $this->repository->update($id, $data);

        return [
            'success' => true,
            'errors' => []
        ];
    }

    public function getAllProdi(): array
    {
        return $this->prodiRepository->all();
    }
}