<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Edit Data Mahasiswa</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <form
                action="/bkpm-webserver/acara9/public/mahasiswa/update/<?= $mahasiswa['id'] ?>"
                method="POST">

                <div class="mb-3">
                    <label class="form-label">NIM</label>

                    <input
                        type="text"
                        name="nim"
                        class="form-control"
                        value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($mahasiswa['email']) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>

                    <select name="prodi_id" class="form-select" required>
                        <option value="">Pilih program studi</option>
                        <?php foreach ($prodi as $item): ?>
                            <option
                                value="<?= (int) $item['id'] ?>"
                                <?= (string) $mahasiswa['prodi_id'] === (string) $item['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item['kode'] . ' - ' . $item['nama'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Angkatan</label>

                    <input
                        type="number"
                        name="angkatan"
                        class="form-control"
                        value="<?= htmlspecialchars($mahasiswa['angkatan']) ?>"
                        required>
                </div>

                <a
                    href="/bkpm-webserver/acara9/public/mahasiswa"
                    class="btn btn-secondary">
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Update
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>