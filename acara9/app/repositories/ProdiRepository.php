<?php

class ProdiRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Menampilkan semua data prodi
    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM prodi
            ORDER BY kode
        ");

        return $stmt->fetchAll();
    }

    // Mencari satu data berdasarkan ID
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM prodi
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM prodi
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetchColumn() !== false;
    }

    // Menambahkan data prodi
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO prodi
            (kode, nama)
            VALUES
            (:kode, :nama)
        ");

        $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    // Mengubah data prodi
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE prodi
            SET
                kode = :kode,
                nama = :nama
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama']
        ]);
    }

    // Menghapus data prodi
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM prodi
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}