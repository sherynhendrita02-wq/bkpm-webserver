<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Prodi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Prodi</h2>

        <a href="/acara8/public/prodi/create" class="btn btn-primary">
            + Tambah Prodi
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($prodi)): ?>

                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data prodi
                        </td>
                    </tr>

                <?php else: ?>

                    <?php $no = 1; ?>

                    <?php foreach ($prodi as $p): ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($p['kode']) ?></td>

                            <td><?= htmlspecialchars($p['nama']) ?></td>

                            <td>

                                <a
                                    href="/acara8/public/prodi/edit/<?= $p['id'] ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form
                                    action="/acara8/public/prodi/delete/<?= $p['id'] ?>"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus prodi ini?');">

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>

                                </form>

                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>