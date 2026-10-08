<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Tambah Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">
        Tambah Data Mahasiswa
    </h2>


    <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

            <ul class="mb-0">

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <div class="card">

        <div class="card-body">

            <form
                action="/bkpm-webserver/acara14/public/mahasiswa"
                method="POST">


                <div class="mb-3">

                    <label class="form-label">
                        NIM
                    </label>

                    <input
                        type="text"
                        name="nim"
                        class="form-control"
                        value="<?= htmlspecialchars($old['nim'] ?? '') ?>"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($old['nama'] ?? '') ?>"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Program Studi
                    </label>

                    <select
                        name="prodi_id"
                        class="form-select"
                        required>

                        <option value="">
                            -- Pilih Program Studi --
                        </option>

                        <?php foreach ($prodi as $p): ?>

                            <option
                                value="<?= $p['id'] ?>"
                                <?= (($old['prodi_id'] ?? '') == $p['id'])
                                    ? 'selected'
                                    : '' ?>>

                                <?= htmlspecialchars($p['nama']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Angkatan
                    </label>

                    <input
                        type="number"
                        name="angkatan"
                        class="form-control"
                        value="<?= htmlspecialchars($old['angkatan'] ?? '2026') ?>"
                        required>

                </div>


                <a
                    href="/bkpm-webserver/acara14/public/mahasiswa"
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