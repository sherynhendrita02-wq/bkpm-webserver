<?php

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $stmt = $this->connection->query(
            "SELECT * FROM mahasiswa ORDER BY nim"
        );

        return $stmt->fetchAll();
    }
}