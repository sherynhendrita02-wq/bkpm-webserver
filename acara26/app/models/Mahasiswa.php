<?php
class Mahasiswa {
    private PDO $db;
    
    private function nimExists(string $nim): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim"
        );

        $stmt->execute([':nim' => $nim]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): string {
        
        if ($this->nimExists($data['nim'])) {
            throw new Exception('NIM sudah terdaftar', 409);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi, foto) VALUES (:nim, :nama, :prodi, :foto)'
        );
        $stmt->execute([
            ':nim' => trim($data['nim']),
            ':nama' => trim($data['nama']),
            ':prodi' => trim($data['prodi']),
            ':foto' => $data['foto'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        // Kolom foto hanya diubah jika ada foto baru; jika tidak, foto lama dipertahankan
        $sql = 'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi'
             . (!empty($data['foto']) ? ', foto = :foto' : '')
             . ' WHERE id = :id';
        $params = [
            ':nim' => trim($data['nim']),
            ':nama' => trim($data['nama']),
            ':prodi' => trim($data['prodi']),
            ':id' => $id,
        ];
        if (!empty($data['foto'])) $params[':foto'] = $data['foto'];
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
