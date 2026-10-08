<?php

class MahasiswaController
{
    public function index(): void
    {
        echo "Halaman Data Mahasiswa";
    }

    public function create(): void
    {
        echo "Halaman Tambah Mahasiswa";
    }

    public function store(): void
    {
        echo "Data mahasiswa berhasil diproses";
    }

    public function show($id): void
    {
        echo "Menampilkan mahasiswa dengan ID: " . $id;
    }
}