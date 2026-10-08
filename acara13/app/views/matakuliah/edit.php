<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Mata Kuliah</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="card">

        <div class="card-header">
            <h4>Edit Mata Kuliah</h4>
        </div>

        <div class="card-body">

            <form
                action="/bkpm-webserver/acara13/public/matakuliah/update/<?= $matakuliah['id'] ?>"
                method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Kode Mata Kuliah
                    </label>

                    <input
                        type="text"
                        name="kode"
                        class="form-control"
                        value="<?= htmlspecialchars($matakuliah['kode']) ?>"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nama Mata Kuliah
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($matakuliah['nama']) ?>"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        SKS
                    </label>

                    <input
                        type="number"
                        name="sks"
                        class="form-control"
                        min="1"
                        max="6"
                        value="<?= htmlspecialchars($matakuliah['sks']) ?>"
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

                        <?php foreach ($prodi as $p): ?>

                            <option
                                value="<?= $p['id'] ?>"
                                <?= $p['id'] == $matakuliah['prodi_id'] ? 'selected' : '' ?>>

                                <?= htmlspecialchars($p['kode']) ?>
                                -
                                <?= htmlspecialchars($p['nama']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary">
                    Update
                </button>

                <a
                    href="/bkpm-webserver/acara13/public/matakuliah"
                    class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>