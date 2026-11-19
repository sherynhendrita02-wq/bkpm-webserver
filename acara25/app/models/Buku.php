
<?php
class Buku
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Mengambil semua data buku
    public function getAll(): array
    {
        return $this->db
            ->query('SELECT * FROM buku ORDER BY id DESC')
            ->fetchAll();
    }

    // Mengambil satu buku berdasarkan ID
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM buku WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    // Menambahkan data buku
    public function create(array $data): string
    {
        $stmt = $this->db->prepare(
            'INSERT INTO buku
            (judul, penulis, penerbit, tahun_terbit)
            VALUES
            (:judul, :penulis, :penerbit, :tahun_terbit)'
        );

        $stmt->execute([
            ':judul' => trim($data['judul']),
            ':penulis' => trim($data['penulis']),
            ':penerbit' => trim($data['penerbit']),
            ':tahun_terbit' => (int) $data['tahun_terbit']
        ]);

        return $this->db->lastInsertId();
    }

    // Memperbarui data buku
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE buku SET
                judul = :judul,
                penulis = :penulis,
                penerbit = :penerbit,
                tahun_terbit = :tahun_terbit
            WHERE id = :id'
        );

        $stmt->execute([
            ':judul' => trim($data['judul']),
            ':penulis' => trim($data['penulis']),
            ':penerbit' => trim($data['penerbit']),
            ':tahun_terbit' => (int) $data['tahun_terbit'],
            ':id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }

    // Menghapus data buku
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM buku WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
