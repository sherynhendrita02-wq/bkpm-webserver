<?php

require_once __DIR__ . '/../Core/BaseModel.php';

class MahasiswaRepository extends BaseModel
{
    public function all(string $keyword = ''): array
    {
        if ($keyword !== '') {

            $stmt = $this->pdo->prepare("
                SELECT
                    m.*,
                    p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                WHERE m.nim LIKE :nim
                   OR m.nama LIKE :nama
                ORDER BY m.nim
            ");

            $search = '%' . $keyword . '%';

            $stmt->execute([
                'nim' => $search,
                'nama' => $search
            ]);

            return $stmt->fetchAll();
        }

        $stmt = $this->pdo->query("
            SELECT
                m.*,
                p.nama AS prodi_nama
            FROM mahasiswa m
            JOIN prodi p ON m.prodi_id = p.id
            ORDER BY m.nim
        ");

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM mahasiswa
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function hasNim(string $nim, ?int $excludeId = null): bool
    {
        $sql = 'SELECT 1 FROM mahasiswa WHERE nim = :nim';
        $params = ['nim' => $nim];

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() !== false;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan)
            VALUES
            (:nim, :nama, :email, :prodi_id, :angkatan)
        ");

        $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE mahasiswa
            SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM mahasiswa
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}