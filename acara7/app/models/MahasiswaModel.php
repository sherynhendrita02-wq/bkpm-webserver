<?php

require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $stmt = $this->connection->query("
            SELECT *
            FROM mahasiswa
            ORDER BY nim ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}