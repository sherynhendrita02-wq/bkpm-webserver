<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mata Kuliah</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Data Mata Kuliah</h2>

        <a href="/bkpm-webserver/acara14/public/matakuliah/create"
           class="btn btn-primary">
            + Tambah Mata Kuliah
        </a>

    </div>

    <div class="card">

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Prodi</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($matakuliah)): ?>

                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada data mata kuliah
                        </td>
                    </tr>

                <?php else: ?>

                    <?php $no = 1; ?>

                    <?php foreach ($matakuliah as $mk): ?>

                        <tr>

                            <td>
                                <?= $no++ ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($mk['kode']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($mk['nama']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($mk['sks']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($mk['prodi_nama']) ?>
                            </td>

                            <td>

                                <a
                                    href="/bkpm-webserver/acara14/public/matakuliah/edit/<?= $mk['id'] ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form
                                    action="/bkpm-webserver/acara14/public/matakuliah/delete/<?= $mk['id'] ?>"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?');">

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
