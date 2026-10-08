<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Tambah Data Mahasiswa</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <form action="/bkpm-webserver/acara10/public/mahasiswa" method="POST">

                <div class="mb-3">
                    <label class="form-label">NIM</label>

                    <input
                        type="text"
                        name="nim"
                        class="form-control"
                        value="<?= htmlspecialchars($input['nim'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($input['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($input['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prodi ID</label>

                    <input
                        type="number"
                        name="prodi_id"
                        class="form-control"
                        value="<?= htmlspecialchars($input['prodi_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Angkatan</label>

                    <input
                        type="number"
                        name="angkatan"
                        value="<?= htmlspecialchars($input['angkatan'] ?? '2026', ENT_QUOTES, 'UTF-8') ?>"
                        class="form-control"
                        required>
                </div>

                <a
                    href="/bkpm-webserver/acara10/public/mahasiswa"
                    class="btn btn-secondary">
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>