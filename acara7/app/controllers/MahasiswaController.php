<?php

require_once __DIR__ . '/../models/MahasiswaModel.php';

class MahasiswaController
{
    public function index(): void
    {
        global $pdo;

        $model = new MahasiswaModel($pdo);

        $mahasiswa = $model->all();

        echo "<h1>Data Mahasiswa</h1>";

        echo "<table border='1' cellpadding='8'>";

        echo "
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Prodi ID</th>
                <th>Angkatan</th>
                <th>Status</th>
            </tr>
        ";

        foreach ($mahasiswa as $index => $mhs) {

            echo "<tr>";

            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>" . htmlspecialchars($mhs['nim']) . "</td>";
            echo "<td>" . htmlspecialchars($mhs['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($mhs['email']) . "</td>";
            echo "<td>" . htmlspecialchars($mhs['prodi_id']) . "</td>";
            echo "<td>" . htmlspecialchars($mhs['angkatan']) . "</td>";
            echo "<td>" . htmlspecialchars($mhs['status']) . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }
}