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

    <div class="card">
        <div class="card-body">

            <form action="/acara8/public/mahasiswa" method="POST">

                <div class="mb-3">
                    <label class="form-label">NIM</label>

                    <input
                        type="text"
                        name="nim"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prodi ID</label>

                    <input
                        type="number"
                        name="prodi_id"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Angkatan</label>

                    <input
                        type="number"
                        name="angkatan"
                        value="2026"
                        class="form-control"
                        required>
                </div>

                <a
                    href="/acara8/public/mahasiswa"
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