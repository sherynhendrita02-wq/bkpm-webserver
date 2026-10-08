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

    <div class="card">
        <div class="card-body">

            <form
                action="/bkpm-webserver/acara14/public/mahasiswa/update/<?= $mahasiswa['id'] ?>"
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
                    <label class="form-label">ID Prodi</label>

                    <input
                        type="number"
                        name="prodi_id"
                        class="form-control"
                        value="<?= htmlspecialchars($mahasiswa['prodi_id']) ?>"
                        required>
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
                    href="/bkpm-webserver/acara14/public/mahasiswa"
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
