<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <?php if (!empty($_SESSION['flash'])): ?>

        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?> alert-dismissible fade show">

            <?= htmlspecialchars($_SESSION['flash']['message']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        <?php unset($_SESSION['flash']); ?>

    <?php endif; ?>


    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Data Mahasiswa</h2>

        <a
            href="/bkpm-webserver/acara15/public/mahasiswa/create"
            class="btn btn-primary">

            + Tambah Mahasiswa

        </a>

    </div>


    <!-- SEARCH -->

    <form
        action="/bkpm-webserver/acara15/public/mahasiswa"
        method="GET"
        class="mb-3">

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari NIM atau nama..."
                value="<?= htmlspecialchars($keyword ?? '') ?>">

            <button
                type="submit"
                class="btn btn-primary">

                Cari

            </button>

            <a
                href="/bkpm-webserver/acara15/public/mahasiswa"
                class="btn btn-secondary">

                Reset

            </a>

        </div>

    </form>


    <div class="card">

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                <tr>

                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>Aksi</th>

                </tr>

                </thead>


                <tbody>

                <?php if (empty($mahasiswa)): ?>

                    <tr>

                        <td
                            colspan="7"
                            class="text-center">

                            Belum ada data mahasiswa.

                        </td>

                    </tr>

                <?php else: ?>

                    <?php $no = 1; ?>

                    <?php foreach ($mahasiswa as $m): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($m['nim']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m['nama']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m['email']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m['prodi_nama']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m['angkatan']) ?>
                            </td>

                            <td>

                                <a
                                    href="/bkpm-webserver/acara15/public/mahasiswa/edit/<?= $m['id'] ?>"
                                    class="btn btn-warning btn-sm">

                                    Edit

                                </a>


                                <form
                                    action="/bkpm-webserver/acara15/public/mahasiswa/delete/<?= $m['id'] ?>"
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>