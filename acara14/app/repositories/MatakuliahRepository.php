<?php

class MatakuliahRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                m.*,
                p.nama AS prodi_nama
            FROM matakuliah m
            JOIN prodi p ON m.prodi_id = p.id
            ORDER BY m.kode
        ");

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM matakuliah
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO matakuliah
            (kode, nama, sks, prodi_id)
            VALUES
            (:kode, :nama, :sks, :prodi_id)
        ");

        $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE matakuliah
            SET
                kode = :kode,
                nama = :nama,
                sks = :sks,
                prodi_id = :prodi_id
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM matakuliah
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}